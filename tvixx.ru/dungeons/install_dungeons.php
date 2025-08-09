<?php
// Определяем путь к корневой директории
$root_path = dirname(dirname(__FILE__));
$system_path = $root_path . '/system';

// Инициализируем переменные для отслеживания успешности установки
$success = true;
$errors = [];
$messages = [];

// Проверяем существование системных файлов
if (file_exists($system_path . '/func.php')) {
    require_once $system_path . '/func.php';
} else {
    $success = false;
    $errors[] = "Системный файл func.php не найден по пути: " . $system_path . '/func.php';
    die("Системный файл func.php не найден по пути: " . $system_path . '/func.php');
}

if (file_exists($system_path . '/dbc.php')) {
    require_once $system_path . '/dbc.php';
} else {
    $success = false;
    $errors[] = "Системный файл dbc.php не найден по пути: " . $system_path . '/dbc.php';
    die("Системный файл dbc.php не найден по пути: " . $system_path . '/dbc.php');
}

// Проверяем, что пользователь является администратором
if (!isset($user) || $user['access'] < 10) {
    die("У вас нет прав для выполнения этой операции!");
}

// Функция для проверки существования таблицы
function tableExists($tableName) {
    global $mc, $success, $errors;
    $result = $mc->query("SHOW TABLES LIKE '$tableName'");
    
    if (!$result) {
        $success = false;
        $errors[] = "Ошибка при проверке существования таблицы '$tableName': " . $mc->error;
        return false;
    }
    
    return $result->num_rows > 0;
}

// Создаем таблицу dungeons, если она не существует
if (!tableExists('dungeons')) {
    $query_result = $mc->query("CREATE TABLE IF NOT EXISTS `dungeons` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `level` int(11) NOT NULL DEFAULT 1,
        `difficulty` enum('easy','normal','hard','expert','legendary') NOT NULL DEFAULT 'normal',
        `min_level` int(11) NOT NULL DEFAULT 1,
        `max_level` int(11) NOT NULL DEFAULT 5,
        `max_rooms` int(11) NOT NULL DEFAULT 10,
        `background` varchar(255) DEFAULT NULL,
        `boss_id` int(11) DEFAULT NULL,
        `gold_reward` int(11) DEFAULT 0,
        `exp_reward` int(11) DEFAULT 0,
        `item_chance` int(11) DEFAULT 5,
        `enabled` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    
    if (!$query_result) {
        $success = false;
        $errors[] = "Ошибка при создании таблицы dungeons: " . $mc->error;
    } else {
        $messages[] = "Таблица dungeons создана успешно.";
        echo "Таблица dungeons создана успешно.<br>";
        
        // Добавляем тестовые данные подземелий
        $dungeons_check = $mc->query("SELECT * FROM `dungeons`");
        if (!$dungeons_check) {
            $success = false;
            $errors[] = "Ошибка при проверке данных таблицы dungeons: " . $mc->error;
        } else if ($dungeons_check->num_rows == 0) {
            $query_result = $mc->query("INSERT INTO `dungeons` 
                (`name`, `description`, `level`, `difficulty`, `min_level`, `max_rooms`, `gold_reward`, `exp_reward`, `enabled`) 
            VALUES 
                ('Заброшенные катакомбы', 'Древние катакомбы, наполненные нежитью и забытыми сокровищами.', 1, 'normal', 1, 10, 100, 50, 1),
                ('Лесное логово', 'Густой лес, населенный дикими зверями и разбойниками.', 2, 'normal', 5, 12, 200, 100, 1),
                ('Забытые пещеры', 'Глубокие пещеры с опасными монстрами и древними артефактами.', 3, 'hard', 10, 15, 350, 200, 1),
                ('Проклятый храм', 'Древний храм, проклятый темной магией и населенный демонами.', 4, 'expert', 15, 18, 500, 350, 1),
                ('Демоническая крепость', 'Неприступная крепость, служащая домом для могущественного демона.', 5, 'legendary', 20, 20, 800, 500, 1)");
            
            if (!$query_result) {
                $success = false;
                $errors[] = "Ошибка при добавлении тестовых подземелий: " . $mc->error;
            } else {
                $messages[] = "Тестовые подземелья добавлены успешно.";
                echo "Тестовые подземелья добавлены успешно.<br>";
            }
        }
    }
}

// Создаем таблицу dungeon_monsters, если она не существует
if (!tableExists('dungeon_monsters')) {
    $query_result = $mc->query("CREATE TABLE IF NOT EXISTS `dungeon_monsters` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text,
        `image` varchar(255) DEFAULT 'default_monster.png',
        `level` int(11) NOT NULL DEFAULT 1,
        `health` int(11) NOT NULL DEFAULT 100,
        `damage` int(11) NOT NULL DEFAULT 10,
        `armor` int(11) NOT NULL DEFAULT 0,
        `accuracy` int(11) NOT NULL DEFAULT 70,
        `evasion` int(11) NOT NULL DEFAULT 10,
        `is_boss` tinyint(1) NOT NULL DEFAULT 0,
        `gold_min` int(11) NOT NULL DEFAULT 5,
        `gold_max` int(11) NOT NULL DEFAULT 20,
        `exp_min` int(11) NOT NULL DEFAULT 5,
        `exp_max` int(11) NOT NULL DEFAULT 20,
        `min_level` int(11) NOT NULL DEFAULT 1,
        `special_ability` varchar(255) DEFAULT NULL,
        `special_chance` int(11) DEFAULT NULL,
        `enabled` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    
    if (!$query_result) {
        $success = false;
        $errors[] = "Ошибка при создании таблицы dungeon_monsters: " . $mc->error;
    } else {
        $messages[] = "Таблица dungeon_monsters создана успешно.";
        echo "Таблица dungeon_monsters создана успешно.<br>";
        
        // Добавляем тестовых монстров
        $monster_check = $mc->query("SELECT * FROM `dungeon_monsters`");
        if (!$monster_check) {
            $success = false;
            $errors[] = "Ошибка при проверке данных таблицы dungeon_monsters: " . $mc->error;
        } else if ($monster_check->num_rows == 0) {
            // Обычные монстры
            $query_result = $mc->query("INSERT INTO `dungeon_monsters` 
                (`name`, `description`, `image`, `level`, `health`, `damage`, `is_boss`, `gold_min`, `gold_max`, `exp_min`, `exp_max`, `min_level`) 
            VALUES 
                ('Скелет-лучник', 'Древний скелет с луком, стреляющий с удивительной меткостью.', 'skeleton.png', 1, 80, 12, 0, 5, 15, 10, 20, 1),
                ('Гоблин-разведчик', 'Мелкий, но подлый гоблин с острым кинжалом.', 'goblin.png', 2, 100, 15, 0, 10, 20, 15, 25, 3),
                ('Зомби', 'Медлительный, но сильный мертвец с жаждой плоти.', 'zombie.png', 3, 150, 18, 0, 15, 25, 20, 30, 5),
                ('Лесной паук', 'Огромный ядовитый паук с мощными челюстями.', 'spider.png', 4, 120, 20, 0, 20, 30, 25, 35, 7),
                ('Орк-воин', 'Мускулистый орк с огромным топором и жаждой крови.', 'orc.png', 5, 200, 25, 0, 25, 40, 30, 45, 10),
                ('Огненный элементаль', 'Существо из чистого пламени, сжигающее всё на своём пути.', 'elemental.png', 6, 180, 30, 0, 30, 50, 35, 55, 12),
                ('Тролль', 'Огромный тролль с невероятной силой и регенерацией.', 'troll.png', 7, 300, 35, 0, 40, 60, 40, 65, 15)");
                
            if (!$query_result) {
                $success = false;
                $errors[] = "Ошибка при добавлении обычных монстров: " . $mc->error;
            } else {
                $messages[] = "Обычные монстры добавлены успешно.";
                
                // Боссы
                $query_result = $mc->query("INSERT INTO `dungeon_monsters` 
                    (`name`, `description`, `image`, `level`, `health`, `damage`, `is_boss`, `gold_min`, `gold_max`, `exp_min`, `exp_max`, `min_level`) 
                VALUES 
                    ('Король скелетов', 'Повелитель нежити с древним проклятым мечом.', 'skeleton_king.png', 5, 500, 40, 1, 100, 200, 100, 200, 5),
                    ('Древний лич', 'Могущественный некромант, владеющий тёмной магией.', 'lich.png', 10, 800, 60, 1, 200, 400, 200, 400, 10),
                    ('Драконий страж', 'Древний дракон, охраняющий несметные сокровища.', 'dragon.png', 15, 1200, 80, 1, 400, 800, 400, 800, 15),
                    ('Повелитель демонов', 'Могущественный демон из адских глубин.', 'demon_lord.png', 20, 1500, 100, 1, 800, 1200, 800, 1200, 20),
                    ('Древний голем', 'Колоссальный голем, созданный забытой цивилизацией.', 'golem.png', 25, 2000, 120, 1, 1000, 1500, 1000, 1500, 25)");
                    
                if (!$query_result) {
                    $success = false;
                    $errors[] = "Ошибка при добавлении боссов: " . $mc->error;
                } else {
                    $messages[] = "Боссы добавлены успешно.";
                    echo "Тестовые монстры добавлены успешно.<br>";
                }
            }
        }
    }
}

// Создаем таблицу dungeon_sessions, если она не существует
if (!tableExists('dungeon_sessions')) {
    $query_result = $mc->query("CREATE TABLE IF NOT EXISTS `dungeon_sessions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `dungeon_id` int(11) NOT NULL,
        `session_data` longtext NOT NULL,
        `start_time` int(11) NOT NULL,
        `last_activity` int(11) NOT NULL,
        `completed` tinyint(1) NOT NULL DEFAULT 0,
        `success` tinyint(1) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        KEY `dungeon_id` (`dungeon_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    
    if (!$query_result) {
        $success = false;
        $errors[] = "Ошибка при создании таблицы dungeon_sessions: " . $mc->error;
    } else {
        $messages[] = "Таблица dungeon_sessions создана успешно.";
        echo "Таблица dungeon_sessions создана успешно.<br>";
    }
}

// Создаем таблицу dungeon_attempts, если она не существует
if (!tableExists('dungeon_attempts')) {
    $query_result = $mc->query("CREATE TABLE IF NOT EXISTS `dungeon_attempts` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `current_attempts` int(11) NOT NULL DEFAULT 3,
        `max_attempts` int(11) NOT NULL DEFAULT 3,
        `last_reset` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    
    if (!$query_result) {
        $success = false;
        $errors[] = "Ошибка при создании таблицы dungeon_attempts: " . $mc->error;
    } else {
        $messages[] = "Таблица dungeon_attempts создана успешно.";
        echo "Таблица dungeon_attempts создана успешно.<br>";
    }
}

// Создаем таблицу dungeon_progress, если она не существует
if (!tableExists('dungeon_progress')) {
    $query_result = $mc->query("CREATE TABLE IF NOT EXISTS `dungeon_progress` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `dungeon_id` int(11) NOT NULL,
        `current_level` int(11) NOT NULL DEFAULT 1,
        `rooms_explored` int(11) NOT NULL DEFAULT 0,
        `monsters_defeated` int(11) NOT NULL DEFAULT 0,
        `boss_defeated` tinyint(1) NOT NULL DEFAULT 0,
        `completion_time` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_dungeon` (`user_id`,`dungeon_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    
    if (!$query_result) {
        $success = false;
        $errors[] = "Ошибка при создании таблицы dungeon_progress: " . $mc->error;
    } else {
        $messages[] = "Таблица dungeon_progress создана успешно.";
        echo "Таблица dungeon_progress создана успешно.<br>";
    }
}

// Создаем таблицу dungeon_settings, если она не существует
if (!tableExists('dungeon_settings')) {
    $query_result = $mc->query("CREATE TABLE IF NOT EXISTS `dungeon_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `key` varchar(255) NOT NULL,
        `value` text NOT NULL,
        `description` text,
        PRIMARY KEY (`id`),
        UNIQUE KEY `key` (`key`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    
    if (!$query_result) {
        $success = false;
        $errors[] = "Ошибка при создании таблицы dungeon_settings: " . $mc->error;
    } else {
        $messages[] = "Таблица dungeon_settings создана успешно.";
        echo "Таблица dungeon_settings создана успешно.<br>";
        
        // Добавляем базовые настройки
        $query_result = $mc->query("INSERT INTO `dungeon_settings` (`key`, `value`, `description`) VALUES
            ('default_attempts', '3', 'Количество попыток по умолчанию для новых игроков'),
            ('dungeon_last_reset', '" . date('Y-m-d') . "', 'Дата последнего сброса попыток подземелий')");
        
        if (!$query_result) {
            $success = false;
            $errors[] = "Ошибка при добавлении базовых настроек: " . $mc->error;
        } else {
            $messages[] = "Базовые настройки добавлены.";
            echo "Базовые настройки добавлены.<br>";
        }
    }
}

// Подводим итоги установки
if ($success) {
    $messages[] = "Установка и проверка таблиц подземелий завершена успешно!";
    echo "<p>Установка и проверка таблиц подземелий завершена успешно!</p>";
    echo "<p><a href='/dungeons/index.php'>Вернуться к подземельям</a></p>";
} else {
    echo "<p>При установке возникли ошибки. Пожалуйста, проверьте сообщения ниже.</p>";
    echo "<pre>" . implode("\n", $errors) . "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Установка системы подземелий</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .message {
            background: #e2f0fb;
            color: #0c5460;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        pre {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow: auto;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Установка системы подземелий</h1>
        
        <?php if ($success): ?>
        <div class="success">
            <strong>Успех!</strong> Все таблицы были успешно созданы и заполнены тестовыми данными.
        </div>
        
        <h2>Выполненные операции:</h2>
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endforeach; ?>
        </div>
        
        <p>Система подземелий установлена и готова к использованию. Вы можете:</p>
        <ul>
            <li>Управлять подземельями через администраторский интерфейс</li>
            <li>Настраивать сложность, количество уровней и типы монстров</li>
            <li>Редактировать награды и условия прохождения</li>
        </ul>
        
        <a href="/dungeons/index.php" class="btn">Перейти к подземельям</a>
        
        <?php else: ?>
        <div class="error">
            <strong>Ошибка!</strong> Во время установки произошли ошибки.
        </div>
        
        <?php if (!empty($messages)): ?>
        <h2>Успешно выполненные операции:</h2>
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <h2>Сообщения об ошибках:</h2>
        <?php if (!empty($errors)): ?>
            <pre><?php echo htmlspecialchars(implode("\n", $errors)); ?></pre>
        <?php else: ?>
            <p>Произошла неизвестная ошибка.</p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html> 