<?php
// Определяем путь к корневой директории
$root_path = dirname(dirname(__FILE__));
$system_path = $root_path . '/system';

// Проверяем существование системных файлов
if (file_exists($system_path . '/func.php')) {
    require_once $system_path . '/func.php';
} else {
    die("Системный файл func.php не найден по пути: " . $system_path . '/func.php');
}

if (file_exists($system_path . '/dbc.php')) {
    require_once $system_path . '/dbc.php';
} else {
    die("Системный файл dbc.php не найден по пути: " . $system_path . '/dbc.php');
}

// Проверяем, что пользователь является администратором
if (!isset($user) || $user['access'] < 10) {
    die("У вас нет прав для выполнения этой операции!");
}

// Статус установки
$success = true;
$errors = [];
$messages = [];

// Функция для выполнения SQL запроса с обработкой ошибок
function executeSql($sql, $message = null) {
    global $mc, $success, $errors, $messages;
    
    if ($mc->query($sql)) {
        if ($message) {
            $messages[] = $message;
        }
        return true;
    } else {
        $success = false;
        $errors[] = "Ошибка MySQL: " . $mc->error . " в запросе: " . $sql;
        return false;
    }
}

// Создаем все необходимые директории
$dirs = [
    $root_path . '/img/dungeons',
    $root_path . '/img/dungeons/icons',
    $root_path . '/img/dungeons/backgrounds',
    $root_path . '/img/mobs'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            $messages[] = "Директория создана: " . $dir;
        } else {
            $success = false;
            $errors[] = "Не удалось создать директорию: " . $dir;
        }
    }
}

// Массив с SQL запросами для создания таблиц
$tables = [
    // Таблица подземелий
    "CREATE TABLE IF NOT EXISTS `dungeons` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `difficulty` int(11) NOT NULL DEFAULT '1',
        `min_level` int(11) NOT NULL DEFAULT '1',
        `max_level` int(11) NOT NULL DEFAULT '5',
        `active` tinyint(1) NOT NULL DEFAULT '1',
        `gold_reward` int(11) NOT NULL DEFAULT '100',
        `exp_reward` int(11) NOT NULL DEFAULT '50',
        `daily_attempts` int(11) NOT NULL DEFAULT '3',
        `required_achievements` text,
        `theme` varchar(50) NOT NULL DEFAULT 'dungeon',
        `background` varchar(255) NOT NULL DEFAULT 'dungeon_bg.jpg',
        `music` varchar(255) NOT NULL DEFAULT 'dungeon_theme.mp3',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица комнат подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_rooms` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `dungeon_id` int(11) NOT NULL,
        `room_number` int(11) NOT NULL,
        `type` varchar(50) NOT NULL DEFAULT 'normal',
        `description` text NOT NULL,
        `background` varchar(255) NOT NULL,
        `required_items` text,
        `puzzle_type` varchar(50),
        `puzzle_data` text,
        `monster_spawns` text,
        `rewards` text,
        `exits` text NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `dungeon_id` (`dungeon_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица сессий подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_sessions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `dungeon_id` int(11) NOT NULL,
        `current_room` int(11) NOT NULL DEFAULT '1',
        `session_data` text NOT NULL,
        `start_time` int(11) NOT NULL,
        `completed` tinyint(1) NOT NULL DEFAULT '0',
        `victory` tinyint(1) NOT NULL DEFAULT '0',
        `score` int(11) NOT NULL DEFAULT '0',
        `time_spent` int(11) NOT NULL DEFAULT '0',
        `items_found` int(11) NOT NULL DEFAULT '0',
        `monsters_defeated` int(11) NOT NULL DEFAULT '0',
        `puzzles_solved` int(11) NOT NULL DEFAULT '0',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        KEY `dungeon_id` (`dungeon_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица достижений подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_achievements` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `icon` varchar(255) NOT NULL,
        `condition_type` varchar(50) NOT NULL,
        `condition_value` int(11) NOT NULL,
        `reward_type` varchar(50) NOT NULL,
        `reward_value` int(11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица прогресса достижений
    "CREATE TABLE IF NOT EXISTS `dungeon_achievement_progress` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `achievement_id` int(11) NOT NULL,
        `progress` int(11) NOT NULL DEFAULT '0',
        `completed` tinyint(1) NOT NULL DEFAULT '0',
        `completed_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_achievement` (`user_id`, `achievement_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица предметов подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_items` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `type` varchar(50) NOT NULL,
        `rarity` varchar(50) NOT NULL DEFAULT 'common',
        `effects` text,
        `icon` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица инвентаря подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_inventory` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `item_id` int(11) NOT NULL,
        `quantity` int(11) NOT NULL DEFAULT '1',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_item` (`user_id`, `item_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица головоломок
    "CREATE TABLE IF NOT EXISTS `dungeon_puzzles` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `type` varchar(50) NOT NULL,
        `difficulty` int(11) NOT NULL DEFAULT '1',
        `solution` text NOT NULL,
        `hints` text,
        `rewards` text,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица статистики подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_stats` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `dungeons_completed` int(11) NOT NULL DEFAULT '0',
        `rooms_visited` int(11) NOT NULL DEFAULT '0',
        `monsters_defeated` int(11) NOT NULL DEFAULT '0',
        `items_found` int(11) NOT NULL DEFAULT '0',
        `puzzles_solved` int(11) NOT NULL DEFAULT '0',
        `total_score` int(11) NOT NULL DEFAULT '0',
        `best_score` int(11) NOT NULL DEFAULT '0',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица ежедневных наград
    "CREATE TABLE IF NOT EXISTS `dungeon_daily_rewards` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `reward_type` varchar(50) NOT NULL,
        `reward_value` int(11) NOT NULL,
        `claimed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `claimed_date` date NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_date` (`user_id`, `claimed_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица рейтинга подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_leaderboard` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `score` int(11) NOT NULL DEFAULT '0',
        `dungeons_completed` int(11) NOT NULL DEFAULT '0',
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица попыток подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_attempts` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `current_attempts` int(11) NOT NULL DEFAULT '3',
        `max_attempts` int(11) NOT NULL DEFAULT '3',
        `last_reset` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица прогресса подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_progress` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `dungeon_id` int(11) NOT NULL,
        `max_reached_level` int(11) NOT NULL DEFAULT '0',
        `completed_times` int(11) NOT NULL DEFAULT '0',
        `first_completed` timestamp NULL DEFAULT NULL,
        `last_completed` timestamp NULL DEFAULT NULL,
        `total_score` int(11) NOT NULL DEFAULT '0',
        `best_score` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_dungeon` (`user_id`, `dungeon_id`),
        KEY `dungeon_id` (`dungeon_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Таблица настроек подземелий
    "CREATE TABLE IF NOT EXISTS `dungeon_settings` (
        `key` varchar(255) NOT NULL,
        `value` text NOT NULL,
        `description` text,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

// Выполняем запросы на создание таблиц
foreach ($tables as $sql) {
    executeSql($sql);
}

// Добавляем настройку для сброса попыток
$current_date = date('Y-m-d');
executeSql("INSERT INTO `dungeon_settings` (`key`, `value`, `description`) VALUES 
    ('dungeon_last_reset', '$current_date', 'Дата последнего сброса попыток подземелий')
    ON DUPLICATE KEY UPDATE `value` = '$current_date'", 
    "Добавлена настройка для сброса попыток подземелий");

// Добавляем настройку для количества попыток по умолчанию
executeSql("INSERT INTO `dungeon_settings` (`key`, `value`, `description`) VALUES 
    ('default_attempts', '3', 'Количество попыток подземелий по умолчанию')
    ON DUPLICATE KEY UPDATE `value` = '3'", 
    "Добавлена настройка для количества попыток по умолчанию");

// Модификация таблицы битв для поддержки подземелий
$column_exists = $mc->query("SHOW COLUMNS FROM `battle` LIKE 'dungeon_session_id'");
if ($column_exists && $column_exists->num_rows == 0) {
    executeSql("ALTER TABLE `battle` ADD COLUMN `dungeon_session_id` int(11) DEFAULT NULL AFTER `helpid`", 
        "Добавлена колонка dungeon_session_id в таблицу battle");
}

$index_exists = $mc->query("SHOW INDEX FROM `battle` WHERE Key_name = 'dungeon_session_id'");
if ($index_exists && $index_exists->num_rows == 0) {
    executeSql("ALTER TABLE `battle` ADD INDEX `dungeon_session_id` (`dungeon_session_id`)", 
        "Добавлен индекс dungeon_session_id в таблицу battle");
}

// Добавляем тестовые подземелья если таблица пуста
$dungeon_count = $mc->query("SELECT COUNT(*) as cnt FROM `dungeons`")->fetch_assoc()['cnt'];

if ($dungeon_count == 0) {
    $dungeons = [
        [
            'name' => 'Пещеры испытаний',
            'description' => 'Подземелье для начинающих искателей приключений. Здесь вы можете освоить основы исследования подземелий и сражений с монстрами.',
            'min_level' => 1,
            'max_level' => 5,
            'difficulty' => 1,
            'gold_reward' => 100,
            'exp_reward' => 50,
            'daily_attempts' => 3,
            'theme' => 'cave',
            'background' => 'caves.jpg',
            'active' => 1
        ],
        [
            'name' => 'Заброшенные катакомбы',
            'description' => 'Древние катакомбы, наполненные опасными монстрами и ценными сокровищами. Требуется хорошее снаряжение и опыт.',
            'min_level' => 5,
            'max_level' => 10,
            'difficulty' => 2,
            'gold_reward' => 250,
            'exp_reward' => 125,
            'daily_attempts' => 3,
            'theme' => 'catacombs',
            'background' => 'catacombs.jpg',
            'active' => 1
        ],
        [
            'name' => 'Подземелье древнего дракона',
            'description' => 'Логово древнего дракона и его прислужников. Только самые смелые и сильные герои осмеливаются бросить вызов этому подземелью.',
            'min_level' => 10,
            'max_level' => 15,
            'difficulty' => 3,
            'gold_reward' => 500,
            'exp_reward' => 250,
            'daily_attempts' => 3,
            'theme' => 'dragon',
            'background' => 'dragon_lair.jpg',
            'active' => 1
        ]
    ];

    foreach ($dungeons as $dungeon) {
        $sql = "INSERT INTO `dungeons` (`name`, `description`, `min_level`, `max_level`, `difficulty`, 
                `gold_reward`, `exp_reward`, `daily_attempts`, `theme`, `background`, `active`) 
                VALUES (
                    '" . $mc->real_escape_string($dungeon['name']) . "',
                    '" . $mc->real_escape_string($dungeon['description']) . "',
                    '" . intval($dungeon['min_level']) . "',
                    '" . intval($dungeon['max_level']) . "',
                    '" . intval($dungeon['difficulty']) . "',
                    '" . intval($dungeon['gold_reward']) . "',
                    '" . intval($dungeon['exp_reward']) . "',
                    '" . intval($dungeon['daily_attempts']) . "',
                    '" . $mc->real_escape_string($dungeon['theme']) . "',
                    '" . $mc->real_escape_string($dungeon['background']) . "',
                    '" . intval($dungeon['active']) . "'
                )";
        
        executeSql($sql, "Добавлено подземелье: " . $dungeon['name']);
    }
}

// Добавляем базовые достижения если таблица пуста
$achievement_count = $mc->query("SELECT COUNT(*) as cnt FROM `dungeon_achievements`")->fetch_assoc()['cnt'];

if ($achievement_count == 0) {
    $achievements = [
        [
            'name' => 'Первые шаги',
            'description' => 'Посетите первое подземелье',
            'icon' => 'fas fa-shoe-prints',
            'condition_type' => 'dungeons_visited',
            'condition_value' => 1,
            'reward_type' => 'gold',
            'reward_value' => 50
        ],
        [
            'name' => 'Исследователь',
            'description' => 'Посетите 10 различных комнат в подземельях',
            'icon' => 'fas fa-map-marked',
            'condition_type' => 'rooms_visited',
            'condition_value' => 10,
            'reward_type' => 'exp',
            'reward_value' => 100
        ],
        [
            'name' => 'Охотник на монстров',
            'description' => 'Победите 5 монстров в подземельях',
            'icon' => 'fas fa-skull',
            'condition_type' => 'monsters_defeated',
            'condition_value' => 5,
            'reward_type' => 'gold',
            'reward_value' => 100
        ],
        [
            'name' => 'Победитель босса',
            'description' => 'Победите босса подземелья',
            'icon' => 'fas fa-crown',
            'condition_type' => 'boss_defeated',
            'condition_value' => 1,
            'reward_type' => 'exp',
            'reward_value' => 250
        ],
        [
            'name' => 'Коллекционер',
            'description' => 'Соберите 10 различных предметов в подземельях',
            'icon' => 'fas fa-treasure-chest',
            'condition_type' => 'items_found',
            'condition_value' => 10,
            'reward_type' => 'gold',
            'reward_value' => 200
        ]
    ];

    foreach ($achievements as $achievement) {
        $sql = "INSERT INTO `dungeon_achievements` (`name`, `description`, `icon`, `condition_type`, 
                `condition_value`, `reward_type`, `reward_value`) 
                VALUES (
                    '" . $mc->real_escape_string($achievement['name']) . "',
                    '" . $mc->real_escape_string($achievement['description']) . "',
                    '" . $mc->real_escape_string($achievement['icon']) . "',
                    '" . $mc->real_escape_string($achievement['condition_type']) . "',
                    '" . intval($achievement['condition_value']) . "',
                    '" . $mc->real_escape_string($achievement['reward_type']) . "',
                    '" . intval($achievement['reward_value']) . "'
                )";
        
        executeSql($sql, "Добавлено достижение: " . $achievement['name']);
    }
}

// Обновляем файлы index.php для использования таблицы dungeon_settings вместо settings
$files_to_check = [
    'index.php', 'functions.php', 'stats.php', 'admin.php'
];

foreach ($files_to_check as $file) {
    $filepath = __DIR__ . '/' . $file;
    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        $updated_content = str_replace(
            "FROM `settings` WHERE `key` = 'dungeon_last_reset'", 
            "FROM `dungeon_settings` WHERE `key` = 'dungeon_last_reset'", 
            $content
        );
        $updated_content = str_replace(
            "INTO `settings` (`key`, `value`)", 
            "INTO `dungeon_settings` (`key`, `value`)", 
            $updated_content
        );
        
        if ($content !== $updated_content) {
            if (file_put_contents($filepath, $updated_content)) {
                $messages[] = "Файл $file обновлен для использования таблицы dungeon_settings";
            } else {
                $errors[] = "Не удалось обновить файл $file";
            }
        }
    }
}

// Выводим результаты
?>
<!DOCTYPE html>
<html>
<head>
    <title>Установка системы подземелий</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #1a1a1a;
            color: #e0e0e0;
            line-height: 1.6;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #4CAF50;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #444;
        }
        h2 {
            color: #e0e0e0;
            margin-top: 25px;
        }
        .success-box {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid #4CAF50;
            color: #4CAF50;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error-box {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid #F44336;
            color: #F44336;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        li:last-child {
            border-bottom: none;
        }
        .buttons {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #555;
        }
        .btn-secondary:hover {
            background-color: #666;
        }
        .highlight {
            background-color: #333;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        code {
            font-family: monospace;
            color: #E0E0E0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Установка системы подземелий</h1>
        
        <?php if ($success): ?>
            <div class="success-box">
                <strong>Успех!</strong> Система подземелий успешно установлена.
            </div>
        <?php else: ?>
            <div class="error-box">
                <strong>Ошибка!</strong> Не удалось установить систему подземелий.
            </div>
        <?php endif; ?>
        
        <?php if (!empty($messages)): ?>
            <h2>Выполненные действия:</h2>
            <ul>
                <?php foreach ($messages as $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <h2>Ошибки:</h2>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <h2>Следующие шаги:</h2>
        <ol>
            <li>Перейдите в <a href="/dungeons/admin.php" style="color:#4CAF50;">админ-панель подземелий</a>, чтобы управлять подземельями</li>
            <li>Или <a href="/dungeons/" style="color:#4CAF50;">откройте страницу подземелий</a>, чтобы начать игру</li>
        </ol>
        
        <div class="buttons">
            <a href="/dungeons/admin.php" class="btn">Перейти в админ-панель</a>
            <a href="/dungeons/" class="btn btn-secondary">Перейти к подземельям</a>
        </div>
    </div>
</body>
</html> 