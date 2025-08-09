<?php
require_once __DIR__ . '/../system/dbc.php';
require_once __DIR__ . '/../system/func.php';
header('Content-Type: application/json; charset=utf-8');

// Включаем детальное логирование для отладки
error_log("mine/score.php - Начало обработки запроса");

try {
    auth(); // проверяем авторизацию

    // Читаем входящий JSON
    $raw = file_get_contents('php://input');
    error_log("mine/score.php - Получены данные: " . substr($raw, 0, 200) . "...");
    
    $data = json_decode($raw, true);

    if (!$data || !isset($data['score'], $data['token'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Неверные данные.']);
        exit;
    }

    $score = (int)$data['score'];
    $token = $data['token'];
    $mission_completed = isset($data['mission_completed']) ? (bool)$data['mission_completed'] : false;
    $mission_type = isset($data['mission_type']) ? (int)$data['mission_type'] : -1;
    $mission_progress = isset($data['mission_progress']) ? (int)$data['mission_progress'] : 0;

    // Ограничиваем максимум возможного счёта в теории
    if ($score < 0 || $score > 50000) { // 50k — заведомо потолок
        echo json_encode(['status' => 'error', 'message' => 'Попытка мошенничества.']);
        exit;
    }

    // Проверяем nonce в сессии (одноразовый)
    if (empty($_SESSION['mine_nonce']) || $token !== $_SESSION['mine_nonce']) {
        error_log("mine/score.php - Ошибка проверки токена. Текущий токен в сессии: " . 
                 (empty($_SESSION['mine_nonce']) ? 'ПУСТО' : substr($_SESSION['mine_nonce'], 0, 6)) . 
                 ", полученный токен: " . substr($token, 0, 6));
        
        echo json_encode([
            'status' => 'error', 
            'message' => 'Токен безопасности недействителен. Пожалуйста, начните игру заново.'
        ]);
        exit;
    }

    // Обновляем токен, чтобы нельзя было отправить повторно
    $old_token = $_SESSION['mine_nonce'];
    unset($_SESSION['mine_nonce']);
    error_log("mine/score.php - Токен проверен и сброшен: " . substr($old_token, 0, 6) . "...");

    $idUser = (int)$user['id'];
    $today = date('Y-m-d');

    // Проверяем наличие таблицы mine_scores_daily
    $checkTable = $mc->query("SHOW TABLES LIKE 'mine_scores_daily'");
    if (!$checkTable) {
        error_log("mine/score.php - Ошибка проверки таблицы: " . $mc->error);
        throw new Exception("Ошибка проверки таблицы: " . $mc->error);
    }
    
    if($checkTable->num_rows == 0) {
        // Таблица не существует, создаем ее
        error_log("mine/score.php - Создаем таблицу mine_scores_daily");
        $createTableSql = "CREATE TABLE IF NOT EXISTS `mine_scores_daily` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `id_user` INT NOT NULL,
            `score` INT NOT NULL DEFAULT 0,
            `date` DATE NOT NULL,
            `mission_completed` TINYINT(1) NOT NULL DEFAULT 0,
            `mission_type` INT DEFAULT NULL,
            `mission_progress` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY(`id_user`),
            KEY(`date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        
        $createTable = $mc->query($createTableSql);
        if (!$createTable) {
            error_log("mine/score.php - Ошибка создания таблицы: " . $mc->error);
            throw new Exception("Ошибка создания таблицы: " . $mc->error);
        }
    }

    // Инициализируем базовые значения для ответа
    $rewardMsg = 'Вы ничего не получили.';
    $rewards = [];
    $result_status = 'ok';

    // Получаем текущий лучший результат
    $stmt = $mc->prepare("SELECT `score`, `mission_completed` FROM `mine_scores_daily` WHERE `id_user` = ? AND `date` = ?");
    if (!$stmt) {
        error_log("mine/score.php - Ошибка подготовки запроса получения результата: " . $mc->error);
        throw new Exception("Ошибка подготовки запроса: " . $mc->error);
    }
    
    $stmt->bind_param('is', $idUser, $today);
    if (!$stmt->execute()) {
        error_log("mine/score.php - Ошибка выполнения запроса получения результата: " . $stmt->error);
        throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    if (!$result) {
        error_log("mine/score.php - Ошибка получения результатов: " . $stmt->error);
        throw new Exception("Ошибка получения результатов: " . $stmt->error);
    }
    
    $res = $result->fetch_assoc();
    
    error_log("mine/score.php - Результат запроса: " . json_encode($res));

    if ($res) {
        // Обновляем существующую запись
        if ($score > $res['score']) {
            error_log("mine/score.php - Обновляем лучший результат");
            $stmtUpd = $mc->prepare("UPDATE `mine_scores_daily` 
                                    SET `score` = ?, 
                                        `mission_completed` = ?, 
                                        `mission_type` = ?,
                                        `mission_progress` = ? 
                                    WHERE `id_user` = ? AND `date` = ?");
            if (!$stmtUpd) {
                error_log("mine/score.php - Ошибка подготовки запроса обновления: " . $mc->error);
                throw new Exception("Ошибка подготовки запроса обновления: " . $mc->error);
            }
            
            $stmtUpd->bind_param('iiiiis', $score, $mission_completed, $mission_type, $mission_progress, $idUser, $today);
            if (!$stmtUpd->execute()) {
                error_log("mine/score.php - Ошибка выполнения запроса обновления: " . $stmtUpd->error);
                throw new Exception("Ошибка выполнения запроса обновления: " . $stmtUpd->error);
            }
        } else if (!$res['mission_completed'] && $mission_completed) {
            // Если миссия выполнена впервые, обновляем только этот флаг
            error_log("mine/score.php - Обновляем статус миссии");
            $stmtUpd = $mc->prepare("UPDATE `mine_scores_daily` 
                                    SET `mission_completed` = 1, 
                                        `mission_progress` = ? 
                                    WHERE `id_user` = ? AND `date` = ?");
            if (!$stmtUpd) {
                error_log("mine/score.php - Ошибка подготовки запроса обновления миссии: " . $mc->error);
                throw new Exception("Ошибка подготовки запроса обновления миссии: " . $mc->error);
            }
            
            $stmtUpd->bind_param('iis', $mission_progress, $idUser, $today);
            if (!$stmtUpd->execute()) {
                error_log("mine/score.php - Ошибка выполнения запроса обновления миссии: " . $stmtUpd->error);
                throw new Exception("Ошибка выполнения запроса обновления миссии: " . $stmtUpd->error);
            }
        }
    } else {
        // Создаем новую запись
        error_log("mine/score.php - Создаем новую запись результата");
        $stmtIns = $mc->prepare("INSERT INTO `mine_scores_daily` 
                                (`id_user`, `score`, `date`, `mission_completed`, `mission_type`, `mission_progress`) 
                                VALUES (?,?,?,?,?,?)");
        if (!$stmtIns) {
            error_log("mine/score.php - Ошибка подготовки запроса вставки: " . $mc->error);
            throw new Exception("Ошибка подготовки запроса вставки: " . $mc->error);
        }
        
        // Преобразуем mission_completed в целое число для запроса
        $stmt_mc = (int)$mission_completed;
        
        // Форматируем дату в строгом формате, соответствующем SQL DATE
        $today = date('Y-m-d');
        
        // Логируем значения перед запросом
        error_log("mine/score.php - Значения для вставки: id_user=$idUser, score=$score, date=$today, mission_completed=$stmt_mc, mission_type=$mission_type, mission_progress=$mission_progress");
        
        // Используем правильный порядок и типы параметров
        $stmtIns->bind_param('iisiii', $idUser, $score, $today, $stmt_mc, $mission_type, $mission_progress);
        if (!$stmtIns->execute()) {
            error_log("mine/score.php - Ошибка выполнения запроса вставки: " . $stmtIns->error . " SQL State: " . $stmtIns->sqlstate);
            throw new Exception("Ошибка выполнения запроса вставки: " . $stmtIns->error);
        }
    }

    // Базовая награда за счёт
    if ($score >= 1000) {
        // + золото (1000 / 10)
        $gold = intval($score / 10);
        
        error_log("mine/score.php - Выдаем награду золотом: " . $gold);
        
        $userTable = $mc->query("SHOW COLUMNS FROM `users` LIKE 'gold'");
        if ($userTable && $userTable->num_rows > 0) {
            $stmtGold = $mc->prepare("UPDATE `users` SET `gold` = `gold` + ? WHERE `id` = ?");
            if (!$stmtGold) {
                error_log("mine/score.php - Ошибка подготовки запроса награды: " . $mc->error);
                throw new Exception("Ошибка подготовки запроса награды: " . $mc->error);
            }
            
            $stmtGold->bind_param('ii', $gold, $idUser);
            if (!$stmtGold->execute()) {
                error_log("mine/score.php - Ошибка выполнения запроса награды: " . $stmtGold->error);
                throw new Exception("Ошибка выполнения запроса награды: " . $stmtGold->error);
            }
            
            $rewardMsg = "Получено золото: $gold";
            $rewards[] = ["gold" => $gold];
        } else {
            error_log("mine/score.php - В таблице users нет столбца gold");
            $rewardMsg = "Получено $gold золота (будет добавлено позже)";
            $rewards[] = ["gold" => $gold];
        }
    }

    // Награда за миссию
    if ($mission_completed) {
        // Бонус серебра за выполненное задание (и шанс на самоцвет)
        $silver = 500; // Отдельная награда за выполнение задания
        
        error_log("mine/score.php - Выдаем награду серебром: " . $silver);
        
        $userTable = $mc->query("SHOW COLUMNS FROM `users` LIKE 'silver'");
        if ($userTable && $userTable->num_rows > 0) {
            $stmtSilver = $mc->prepare("UPDATE `users` SET `silver` = IFNULL(`silver`, 0) + ? WHERE `id` = ?");
            if (!$stmtSilver) {
                error_log("mine/score.php - Ошибка подготовки запроса серебра: " . $mc->error);
                throw new Exception("Ошибка подготовки запроса серебра: " . $mc->error);
            }
            
            $stmtSilver->bind_param('ii', $silver, $idUser);
            if (!$stmtSilver->execute()) {
                error_log("mine/score.php - Ошибка выполнения запроса серебра: " . $stmtSilver->error);
                throw new Exception("Ошибка выполнения запроса серебра: " . $stmtSilver->error);
            }
            
            if (!empty($rewardMsg)) {
                $rewardMsg .= " и $silver серебра за выполнение задания";
            } else {
                $rewardMsg = "Получено $silver серебра за выполнение задания";
            }
            $rewards[] = ["silver" => $silver];
        } else {
            error_log("mine/score.php - В таблице users нет столбца silver");
            $rewardMsg = "Получено $silver серебра (будет добавлено позже)";
            $rewards[] = ["silver" => $silver];
        }
        
        // Увеличенный шанс на самоцвет за выполнение задания (40%)
        if (mt_rand(1, 100) <= 40) {
            // Допустим, предмет id=1234 — драгоценный самоцвет
            $itemId = 1234 + $mission_type; // Разные самоцветы в зависимости от типа задания
            
            // Проверяем наличие таблицы inventory
            $checkInventory = $mc->query("SHOW TABLES LIKE 'inventory'");
            if ($checkInventory && $checkInventory->num_rows > 0) {
                $stmtItem = $mc->prepare("INSERT INTO `inventory` (`id_user`, `item_id`, `count`) VALUES (?,?,1)");
                if (!$stmtItem) {
                    error_log("mine/score.php - Ошибка подготовки запроса инвентаря: " . $mc->error);
                    throw new Exception("Ошибка подготовки запроса инвентаря: " . $mc->error);
                }
                
                $stmtItem->bind_param('ii', $idUser, $itemId);
                if (!$stmtItem->execute()) {
                    error_log("mine/score.php - Ошибка выполнения запроса инвентаря: " . $stmtItem->error);
                    throw new Exception("Ошибка выполнения запроса инвентаря: " . $stmtItem->error);
                }
                
                $rewardMsg .= " и редкий самоцвет!";
                $rewards[] = ["item" => $itemId];
            } else {
                error_log("mine/score.php - Таблица inventory не существует");
                $rewardMsg .= " и редкий самоцвет (будет добавлен позже)!";
                $rewards[] = ["item" => $itemId];
            }
        }
    }
    // Шанс на самоцвет
    elseif ($score >= 2000) {
        // Шанс 20% на самоцвет
        if (mt_rand(1, 100) <= 20) {
            // Допустим, предмет id=1234 — драгоценный самоцвет
            $itemId = 1234;
            
            // Проверяем наличие таблицы inventory
            $checkInventory = $mc->query("SHOW TABLES LIKE 'inventory'");
            if ($checkInventory && $checkInventory->num_rows > 0) {
                $stmtItem = $mc->prepare("INSERT INTO `inventory` (`id_user`, `item_id`, `count`) VALUES (?,?,1)");
                if (!$stmtItem) {
                    error_log("mine/score.php - Ошибка подготовки запроса инвентаря: " . $mc->error);
                    throw new Exception("Ошибка подготовки запроса инвентаря: " . $mc->error);
                }
                
                $stmtItem->bind_param('ii', $idUser, $itemId);
                if (!$stmtItem->execute()) {
                    error_log("mine/score.php - Ошибка выполнения запроса инвентаря: " . $stmtItem->error);
                    throw new Exception("Ошибка выполнения запроса инвентаря: " . $stmtItem->error);
                }
                
                $rewardMsg .= " и редкий самоцвет!";
                $rewards[] = ["item" => $itemId];
            } else {
                error_log("mine/score.php - Таблица inventory не существует");
                $rewardMsg .= " и редкий самоцвет (будет добавлен позже)!";
                $rewards[] = ["item" => $itemId];
            }
        }
    }
    
} catch (Exception $e) {
    error_log("mine/score.php - Критическая ошибка: " . $e->getMessage());
    $result_status = 'error';
    $rewardMsg = 'Произошла ошибка при сохранении результата: ' . $e->getMessage();
}

// Формируем окончательный ответ
$response = [
    'status' => $result_status ?? 'error',
    'message' => $rewardMsg ?? 'Неизвестная ошибка',
    'score' => $score ?? 0,
    'mission_completed' => $mission_completed ?? false,
    'rewards' => $rewards ?? []
];

error_log("mine/score.php - Отправляем ответ: " . json_encode($response));
echo json_encode($response); 