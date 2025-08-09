<?php
require_once __DIR__ . '/../system/dbc.php';
require_once __DIR__ . '/../system/func.php';

// Закрываем от неавторизированных пользователей
auth();

// Генерируем новый nonce при каждой загрузке игры для защиты от подмены очков
$_SESSION['mine_nonce'] = bin2hex(random_bytes(16));
$mine_nonce = $_SESSION['mine_nonce'];
error_log("mine/index.php - Сгенерирован новый токен: " . substr($mine_nonce, 0, 6) . "...");

// Проверяем наличие таблицы mine_scores_daily
$checkTable = $mc->query("SHOW TABLES LIKE 'mine_scores_daily'");
if($checkTable->num_rows == 0) {
    // Таблица не существует, создаем ее
    $createTable = $mc->query("CREATE TABLE IF NOT EXISTS `mine_scores_daily` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
}

// Получаем лучшие результаты (топ-10)
$topScores = [];
try {
    // Явно указываем формат даты и используем более безопасную конструкцию
    $today = date('Y-m-d');
    
    // Логируем для диагностики
    error_log("mine/index.php - Получаем топ-10 результатов на дату: " . $today);
    
    $stmt = $mc->prepare("SELECT u.name, m.score 
                        FROM mine_scores_daily m 
                        JOIN users u ON m.id_user = u.id 
                        WHERE m.date = ? 
                        ORDER BY m.score DESC LIMIT 10");
    if($stmt) {
        $stmt->bind_param('s', $today);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result) {
            $topScores = $result->fetch_all(MYSQLI_ASSOC);
            error_log("mine/index.php - Получено " . count($topScores) . " результатов");
        } else {
            error_log("mine/index.php - Ошибка при получении результатов: " . $stmt->error);
        }
    } else {
        error_log("mine/index.php - Ошибка при подготовке запроса: " . $mc->error);
    }
} catch (Exception $e) {
    // Обработка ошибки
    error_log("Ошибка в mine/index.php: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover, height=device-height">
    <meta name="theme-color" content="#0f0f1a" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Кристаллическая шахта</title>
    <link rel="stylesheet" href="/css/mine.css?v=<?= time() ?>">
    <style>
        /* Fallback, если css/mine.css не прогрузится */
        body{margin:0;font-family:'Roboto','Segoe UI',Tahoma,sans-serif;background-color:#0f0f1a;color:#fff;overflow:hidden;position:fixed;width:100%;height:100%;}
    </style>
</head>
<body>
    <div id="mine-app">
        <!-- Главное меню -->
        <div id="mine-menu" class="mine-screen active">
            <div class="mine-decorations">
                <div class="mine-crystal mine-crystal-1"></div>
                <div class="mine-crystal mine-crystal-2"></div>
                <div class="mine-crystal mine-crystal-3"></div>
                <div class="mine-crystal mine-crystal-4"></div>
                <div class="mine-crystal mine-crystal-5"></div>
                <div class="mine-particles"></div>
            </div>
            
            <div class="mine-menu-content">
                <h1 class="mine-title">Кристаллическая<br><span class="mine-title-accent">Шахта</span></h1>
                
                <div class="mine-menu-container">
                    <button class="mine-btn mine-btn-large mine-btn-primary" id="btn-play">
                        <span class="mine-btn-icon">▶</span>
                        <span class="mine-btn-text">Играть</span>
                    </button>
                    <button class="mine-btn mine-btn-large" id="btn-leaderboard">
                        <span class="mine-btn-icon">🏆</span>
                        <span class="mine-btn-text">Рейтинг</span>
                    </button>
                    <button class="mine-btn mine-btn-large mine-btn-secondary" id="btn-return">
                        <span class="mine-btn-icon">⟲</span>
                        <span class="mine-btn-text">Вернуться в игру</span>
                    </button>
                </div>
                
                <div class="mine-daily-reward">
                    <div class="mine-daily-reward-header">Ежедневная награда</div>
                    <div class="mine-daily-reward-items">
                        <div class="mine-reward-item">
                            <div class="mine-reward-icon mine-reward-gold"></div>
                            <div class="mine-reward-amount">Золото</div>
                        </div>
                        <div class="mine-reward-item">
                            <div class="mine-reward-icon mine-reward-gem"></div>
                            <div class="mine-reward-amount">Самоцветы</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Экран рейтинга -->
        <div id="mine-leaderboard" class="mine-screen">
            <div class="mine-modal-content mine-leaderboard-content">
                <div class="mine-modal-header">
                    <h2 class="mine-title-small">Лучшие игроки дня</h2>
                </div>
                <div class="mine-leaderboard-container">
                    <?php if(empty($topScores)): ?>
                        <div class="mine-empty-list">Пока нет рекордов</div>
                    <?php else: ?>
                        <table class="mine-leaderboard-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Игрок</th>
                                    <th>Очки</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($topScores as $index => $score): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($score['name']) ?></td>
                                    <td><?= number_format($score['score']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <div class="mine-modal-footer">
                    <button class="mine-btn mine-btn-large mine-btn-secondary" id="btn-back-from-leaderboard" style="z-index:30; position:relative;">
                        <span class="mine-btn-icon">⬅</span>
                        <span class="mine-btn-text">Назад</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Экран игры -->
        <div id="mine-game" class="mine-screen">
            <header class="mine-header">
                <div class="mine-header-top">
                    <button class="mine-btn mine-btn-small" id="btn-menu">
                        <span class="mine-btn-icon">≡</span>
                        <span class="mine-btn-text">Меню</span>
                    </button>
                    <h1 class="mine-title-small">Кристаллическая шахта</h1>
                </div>
                <div class="mine-info">
                    <div class="mine-info-moves">
                        <span id="mine-timer">25</span> <span id="mine-moves-text"></span>
                    </div>
                    <div class="mine-info-score">
                        <span class="mine-score-label">Очки:</span> <span id="mine-score">0</span>
                    </div>
                </div>
                <!-- Полоса заданий -->
                <div id="mine-missions" class="mine-missions">
                    <div id="mine-mission-display" class="mine-mission-display">
                        <div class="mine-mission-text">Соберите&nbsp;<span id="mine-mission-count">0</span> <img id="mine-mission-icon" src="" alt="gem"></div>
                        <div class="mine-mission-progress">
                            <div id="mine-mission-bar" class="mine-mission-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="mine-main">
                <div id="mine-board" class="mine-board"></div>
            </main>

            <footer class="mine-footer">
                <button id="mine-restart" class="mine-btn mine-btn-primary">
                    <span class="mine-btn-icon">🔄</span>
                    <span class="mine-btn-text">ЗАНОВО</span>
                </button>
            </footer>
        </div>
    </div>

    <!-- Модальное окно с результатами -->
    <div id="result-modal" class="mine-modal">
        <div class="mine-modal-content">
            <div class="mine-modal-header">
                <h2>Итоги игры</h2>
                <div class="mine-modal-close">&times;</div>
            </div>
            <div class="mine-modal-body">
                <div class="mine-results-score">
                    <span class="mine-results-label">Ваш результат:</span>
                    <span id="result-score" class="mine-results-value">0</span>
                </div>
                
                <div class="mine-results-mission">
                    <div id="result-mission-status" class="mine-mission-status">Задание выполнено!</div>
                    <div class="mine-results-mission-progress">
                        <span>Прогресс:</span>
                        <div class="mine-mission-progress">
                            <div id="result-mission-bar" class="mine-mission-bar" style="width: 0%"></div>
                        </div>
                        <span id="result-mission-progress">0/0</span>
                    </div>
                </div>
                
                <div class="mine-results-rewards">
                    <h3>Награды:</h3>
                    <div id="result-rewards" class="mine-rewards-list">
                        <!-- Сюда будут добавляться награды (золото, серебро, предметы) -->
                    </div>
                </div>
            </div>
            <div class="mine-modal-footer">
                <button id="result-restart" class="mine-btn mine-btn-large">Играть снова</button>
                <button id="result-exit" class="mine-btn">Выйти</button>
            </div>
        </div>
    </div>

    <script>
        const MINE_TOKEN = '<?= $mine_nonce ?>';
        const MINE_USER_ID = <?= (int)$user['id']; ?>;
    </script>
    <script type="module" src="/js/mine/game.js?v=<?= time() ?>"></script>
</body>
</html> 