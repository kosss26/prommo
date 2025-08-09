<?php
require_once 'functions.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$session_id = $_GET['session'] ?? null;

if (!$session_id) {
    header('Location: /dungeons/');
    exit;
}

// Получаем данные сессии
$session = $mc->query("SELECT * FROM `dungeon_sessions` WHERE `id` = '$session_id' AND `user_id` = '$user_id'")->fetch_array(MYSQLI_ASSOC);
if (!$session) {
    header('Location: /dungeons/');
    exit;
}

$exploration_data = json_decode($session['session_data'], true);

// Получаем данные подземелья
$dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '" . $exploration_data['dungeon_id'] . "'")->fetch_array(MYSQLI_ASSOC);
if (!$dungeon) {
    header('Location: /dungeons/');
    exit;
}

// Рассчитываем статистику
$stats = [
    'rooms_visited' => count($exploration_data['rooms']),
    'monsters_defeated' => $exploration_data['stats']['monsters_defeated'] ?? 0,
    'items_found' => $exploration_data['stats']['items_found'] ?? 0,
    'boss_defeated' => $exploration_data['stats']['boss_defeated'] ?? 0,
    'gold_earned' => $exploration_data['stats']['gold_earned'] ?? 0,
    'exp_earned' => $exploration_data['stats']['exp_earned'] ?? 0
];

// Рассчитываем награды
$rewards = calculateDungeonRewards($dungeon, $stats);

// Обновляем данные пользователя
$mc->query("UPDATE `users` SET 
    `gold` = `gold` + " . $rewards['gold'] . ",
    `exp` = `exp` + " . $rewards['exp'] . "
    WHERE `id` = '$user_id'");

// Проверяем достижения
$achievements = checkAchievementConditions($exploration_data['stats']);

// Обновляем достижения пользователя
foreach ($achievements as $achievement) {
    $mc->query("INSERT INTO `user_achievements` (`user_id`, `achievement_id`, `completed_at`) 
        VALUES ('$user_id', '" . $achievement['id'] . "', " . time() . ")
        ON DUPLICATE KEY UPDATE `completed_at` = " . time());
    
    // Добавляем награды за достижения
    if ($achievement['reward_gold'] > 0) {
        $mc->query("UPDATE `users` SET `gold` = `gold` + " . $achievement['reward_gold'] . " WHERE `id` = '$user_id'");
    }
    if ($achievement['reward_exp'] > 0) {
        $mc->query("UPDATE `users` SET `exp` = `exp` + " . $achievement['reward_exp'] . " WHERE `id` = '$user_id'");
    }
}

// Обновляем статистику подземелья
$mc->query("UPDATE `dungeon_sessions` SET 
    `completed` = 1,
    `victory` = " . ($stats['boss_defeated'] > 0 ? 1 : 0) . ",
    `score` = " . calculateDungeonScore($stats) . ",
    `time_spent` = " . (time() - $session['start_time']) . ",
    `items_found` = " . $stats['items_found'] . ",
    `monsters_defeated` = " . $stats['monsters_defeated'] . ",
    `puzzles_solved` = " . ($exploration_data['stats']['puzzles_solved'] ?? 0) . "
    WHERE `id` = '$session_id'");

// Обновляем таблицу лидеров
updateLeaderboard($user_id, $dungeon['id'], $stats);

// Проверяем ежедневные награды
checkDailyRewards($user_id, $dungeon['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Завершение подземелья</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #1a1a1a;
            color: #fff;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .completion-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .completion-title {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        
        .completion-subtitle {
            font-size: 1.2em;
            color: #aaa;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: #2a2a2a;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-icon {
            font-size: 2em;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        
        .stat-value {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #aaa;
        }
        
        .rewards-section {
            background: #2a2a2a;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 40px;
        }
        
        .rewards-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        
        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .reward-item {
            background: #3a3a3a;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        
        .reward-icon {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #FFD700;
        }
        
        .reward-value {
            font-size: 1.2em;
            font-weight: bold;
        }
        
        .achievements-section {
            background: #2a2a2a;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 40px;
        }
        
        .achievements-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .achievement-card {
            background: #3a3a3a;
            border-radius: 5px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .achievement-icon {
            font-size: 2em;
            color: #FFD700;
        }
        
        .achievement-info {
            flex: 1;
        }
        
        .achievement-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .achievement-description {
            color: #aaa;
            font-size: 0.9em;
        }
        
        .achievement-reward {
            color: #4CAF50;
            font-size: 0.9em;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        
        .action-button {
            background: #4CAF50;
            border: none;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background 0.3s;
            text-align: center;
            text-decoration: none;
        }
        
        .action-button:hover {
            background: #45a049;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .rewards-grid {
                grid-template-columns: 1fr;
            }
            
            .achievements-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="completion-header">
            <h1 class="completion-title">Подземелье завершено!</h1>
            <p class="completion-subtitle"><?php echo htmlspecialchars($dungeon['name']); ?></p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-door-open"></i></div>
                <div class="stat-value"><?php echo $stats['rooms_visited']; ?></div>
                <div class="stat-label">Посещено комнат</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-skull"></i></div>
                <div class="stat-value"><?php echo $stats['monsters_defeated']; ?></div>
                <div class="stat-label">Побеждено монстров</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-treasure-chest"></i></div>
                <div class="stat-value"><?php echo $stats['items_found']; ?></div>
                <div class="stat-label">Найдено предметов</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-crown"></i></div>
                <div class="stat-value"><?php echo $stats['boss_defeated']; ?></div>
                <div class="stat-label">Побеждено боссов</div>
            </div>
        </div>
        
        <div class="rewards-section">
            <h2 class="rewards-title">Награды</h2>
            <div class="rewards-grid">
                <div class="reward-item">
                    <div class="reward-icon"><i class="fas fa-coins"></i></div>
                    <div class="reward-value"><?php echo $rewards['gold']; ?> золота</div>
                </div>
                
                <div class="reward-item">
                    <div class="reward-icon"><i class="fas fa-star"></i></div>
                    <div class="reward-value"><?php echo $rewards['exp']; ?> опыта</div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($achievements)): ?>
        <div class="achievements-section">
            <h2 class="achievements-title">Полученные достижения</h2>
            <div class="achievements-grid">
                <?php foreach ($achievements as $achievement): ?>
                <div class="achievement-card">
                    <div class="achievement-icon">
                        <i class="<?php echo $achievement['icon']; ?>"></i>
                    </div>
                    <div class="achievement-info">
                        <div class="achievement-name"><?php echo htmlspecialchars($achievement['name']); ?></div>
                        <div class="achievement-description"><?php echo htmlspecialchars($achievement['description']); ?></div>
                        <?php if ($achievement['reward_gold'] > 0 || $achievement['reward_exp'] > 0): ?>
                        <div class="achievement-reward">
                            <?php if ($achievement['reward_gold'] > 0): ?>
                            +<?php echo $achievement['reward_gold']; ?> золота
                            <?php endif; ?>
                            <?php if ($achievement['reward_exp'] > 0): ?>
                            +<?php echo $achievement['reward_exp']; ?> опыта
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="action-buttons">
            <a href="/dungeons/" class="action-button">Вернуться к списку подземелий</a>
            <a href="/dungeons/stats.php" class="action-button">Статистика</a>
            <a href="/dungeons/achievements.php" class="action-button">Достижения</a>
        </div>
    </div>
</body>
</html>