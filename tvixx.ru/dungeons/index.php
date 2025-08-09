<?php
// Включаем отладку только при необходимости (можно включить в продакшене через константу)
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_log("=== DEBUG INDEX.PHP ===");
    error_log("REQUEST: " . print_r($_REQUEST, true));
    error_log("SESSION: " . print_r($_SESSION, true));
    error_log("SERVER: " . print_r($_SERVER, true));
    error_log("COOKIE: " . print_r($_COOKIE, true));
}

require_once '../system/func.php';
require_once '../system/dbc.php';

// Проверка и запуск сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Восстановление сессии из cookies
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['id'])) {
        $_SESSION['user_id'] = $_COOKIE['id'];
        if (DEBUG_MODE) error_log("INDEX.PHP: Восстановили user_id из кук: " . $_COOKIE['id']);
    } elseif (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
        $login = $mc->real_escape_string(urldecode($_COOKIE['login']));
        $password = $mc->real_escape_string($_COOKIE['password']);
        $stmt = $mc->prepare("SELECT `id` FROM `users` WHERE `login` = ? AND `password` = ? LIMIT 1");
        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        $user_data = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
        if ($user_data && isset($user_data['id'])) {
            $_SESSION['user_id'] = $user_data['id'];
            if (DEBUG_MODE) error_log("INDEX.PHP: Восстановили user_id из логина/пароля: " . $user_data['id']);
        }
    }
}

require_once '../system/header.php';
require_once 'functions.php';

auth(); // Проверка авторизации
requestModer(); // Проверка запроса на модератора
checkDungeonAuth(); // Дополнительная проверка авторизации для подземелий

// Проверка текущих активностей
$user_id = $user['id'];
$stmt = $mc->prepare("SELECT * FROM `battle` WHERE `user_id` = ? AND `player_activ` = '1' AND `end_battle` = '0'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo '<script>showContent("/dungeons/battle.php");</script>';
    exit(0);
}

$stmt = $mc->prepare("SELECT * FROM `resultbattle` WHERE `id_user` = ? ORDER BY `id` DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo '<script>showContent("/hunt/result.php");</script>';
    exit(0);
}

$stmt = $mc->prepare("SELECT * FROM `huntb_list` WHERE `user_id` = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $mc->query("INSERT INTO `msg` (`id_user`, `message`, `date`, `type`) VALUES ('$user_id', 'Чтобы начать исследование подземелий отмените дуэли!', '" . time() . "', 'msg')");
    echo '<script>showContent("/huntb/index.php");</script>';
    exit(0);
}

// Обработка нового дня
$current_date = date('Y-m-d');
$stmt = $mc->prepare("SELECT `value` FROM `dungeon_settings` WHERE `key` = 'dungeon_last_reset'");
$stmt->execute();
$last_reset_date = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);

if (!$last_reset_date || $last_reset_date['value'] !== $current_date) {
    $mc->query("UPDATE `dungeon_attempts` SET `current_attempts` = `max_attempts`");
    $stmt = $mc->prepare("REPLACE INTO `dungeon_settings` (`key`, `value`, `description`) VALUES ('dungeon_last_reset', ?, 'Дата последнего сброса попыток подземелий')");
    $stmt->bind_param("s", $current_date);
    $stmt->execute();
}

// Настройки попыток
$stmt = $mc->prepare("SELECT `value` FROM `dungeon_settings` WHERE `key` = 'default_attempts'");
$stmt->execute();
$default_attempts_settings = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
$default_attempts = $default_attempts_settings ? (int)$default_attempts_settings['value'] : 3;

// Список подземелий
$dungeons = $mc->query("SELECT * FROM `dungeons` ORDER BY `min_level` ASC")->fetch_all(MYSQLI_ASSOC);

// Попытки пользователя
$stmt = $mc->prepare("SELECT * FROM `dungeon_attempts` WHERE `user_id` = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$attempts = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
if (!$attempts) {
    $stmt = $mc->prepare("INSERT INTO `dungeon_attempts` (`user_id`, `current_attempts`, `max_attempts`) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $default_attempts, $default_attempts);
    $stmt->execute();
    $attempts = ['user_id' => $user_id, 'current_attempts' => $default_attempts, 'max_attempts' => $default_attempts];
}

// Прогресс в подземельях
$dungeons_progress = [];
$stmt = $mc->prepare("SELECT * FROM `dungeon_progress` WHERE `user_id` = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $dungeons_progress[$row['dungeon_id']] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подземелья</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6200ea;
            --secondary-color: #ff1744;
            --background-color: #f9f9f9;
            --text-color: #333;
            --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: var(--background-color);
            color: var(--text-color);
        }

        .dungeons-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .dungeons-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
        }

        .dungeons-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .attempts-counter {
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 5px;
        }

        .admin-panel-link {
            text-align: center;
            margin-bottom: 15px;
        }

        .admin-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: var(--secondary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .admin-button:hover {
            background: darken(var(--secondary-color), 10%);
        }

        .dungeons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .dungeon-card {
            background: white;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .dungeon-card:hover {
            transform: translateY(-5px);
        }

        .dungeon-card-header {
            padding: 15px;
            background: var(--primary-color);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dungeon-card-title {
            margin: 0;
            font-size: 18px;
        }

        .dungeon-card-level {
            font-size: 14px;
            opacity: 0.8;
        }

        .dungeon-card-content {
            padding: 15px;
        }

        .dungeon-description {
            margin: 0 0 10px;
            font-size: 14px;
            color: #666;
        }

        .dungeon-progress {
            margin-bottom: 10px;
        }

        .progress-bar {
            background: #ddd;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress {
            background: var(--secondary-color);
            height: 100%;
            transition: width 0.5s;
        }

        .dungeon-rewards {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .reward-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .dungeon-card-actions {
            padding: 15px;
            text-align: center;
        }

        .battle-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .battle-button:hover {
            background: var(--secondary-color);
        }

        .battle-button.disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .mobile-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-around;
            background: var(--primary-color);
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        .mobile-menu-item {
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 12px;
            padding: 5px;
            flex: 1;
        }

        .mobile-menu-item i {
            display: block;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .mobile-menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .dungeons-header h1 {
                font-size: 20px;
            }

            .attempts-counter {
                font-size: 14px;
            }

            .dungeons-grid {
                grid-template-columns: 1fr;
            }

            .dungeon-card-title {
                font-size: 16px;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="dungeons-container fade-in">
        <div class="dungeons-header">
            <h1>Подземелья</h1>
            <div class="attempts-counter">
                Попытки: <?php echo htmlspecialchars($attempts['current_attempts']); ?>/<?php echo htmlspecialchars($attempts['max_attempts']); ?>
            </div>
        </div>

        <?php if (isset($user['access']) && $user['access'] >= 4): ?>
            <div class="admin-panel-link">
                <a href="/dungeons/admin.php" class="admin-button">
                    <i class="fas fa-shield-alt"></i> Админ-панель
                </a>
            </div>
        <?php endif; ?>

        <div class="dungeons-grid">
            <?php foreach ($dungeons as $dungeon): ?>
                <?php
                $progress = $dungeons_progress[$dungeon['id']] ?? null;
                $is_available = $user['level'] >= $dungeon['min_level'];
                $gold_reward = isset($dungeon['gold_reward']) ? $dungeon['gold_reward'] : ($dungeon['min_level'] * 100);
                $exp_reward = isset($dungeon['exp_reward']) ? $dungeon['exp_reward'] : ($dungeon['min_level'] * 50);
                ?>
                <div class="dungeon-card <?php echo $is_available ? 'available' : 'locked'; ?>">
                    <div class="dungeon-card-header">
                        <h2 class="dungeon-card-title"><?php echo htmlspecialchars($dungeon['name']); ?></h2>
                        <span class="dungeon-card-level">Уровень <?php echo htmlspecialchars($dungeon['min_level']); ?>+</span>
                    </div>
                    <div class="dungeon-card-content">
                        <p class="dungeon-description"><?php echo htmlspecialchars($dungeon['description']); ?></p>
                        <?php if ($progress): ?>
                            <div class="dungeon-progress">
                                <div class="progress-bar">
                                    <div class="progress" style="width: <?php echo ($progress['current_level'] / $dungeon['max_level']) * 100; ?>%"></div>
                                </div>
                                <span>Уровень <?php echo htmlspecialchars($progress['current_level']); ?>/<?php echo htmlspecialchars($dungeon['max_level']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="dungeon-rewards">
                            <div class="reward-item">
                                <i class="fas fa-coins"></i>
                                <span><?php echo number_format($gold_reward); ?> золота</span>
                            </div>
                            <div class="reward-item">
                                <i class="fas fa-star"></i>
                                <span><?php echo number_format($exp_reward); ?> опыта</span>
                            </div>
                        </div>
                    </div>
                    <div class="dungeon-card-actions">
                        <?php if ($is_available): ?>
                            <a href="/dungeons/start.php?id=<?php echo htmlspecialchars($dungeon['id']); ?>" class="battle-button">
                                <i class="fas fa-play"></i> Начать
                            </a>
                        <?php else: ?>
                            <button class="battle-button disabled" disabled>
                                <i class="fas fa-lock"></i> Недоступно
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <nav class="mobile-menu">
        <a href="/dungeons/index.php" class="mobile-menu-item">
            <i class="fas fa-home"></i>
            <span>Главная</span>
        </a>
        <a href="/dungeons/stats.php" class="mobile-menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Статистика</span>
        </a>
        <a href="/dungeons/complete.php" class="mobile-menu-item">
            <i class="fas fa-trophy"></i>
            <span>Достижения</span>
        </a>
        <?php if (isset($user['access']) && $user['access'] >= 4): ?>
            <a href="/dungeons/admin.php" class="mobile-menu-item admin-link">
                <i class="fas fa-shield-alt"></i>
                <span>Админ-панель</span>
            </a>
        <?php endif; ?>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let touchStartX = 0;
            let touchEndX = 0;

            document.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });

            document.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchEndX - touchStartX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) window.history.back();
                    // Свайп влево можно настроить под другую навигацию
                }
            });
        });
    </script>
</body>
</html>