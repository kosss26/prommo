<?php
require_once '../system/func.php';
require_once '../system/dbc.php';

// Защита от перенаправления на disconnect.php
session_start();
if (!isset($_SESSION['user_id']) && isset($_COOKIE['id'])) {
    $_SESSION['user_id'] = $_COOKIE['id'];
}

require_once '../system/header.php';
require_once 'functions.php';

auth(); // Закроем от неавторизированных
requestModer(); // Закроем для тех у кого есть запрос на модератора

// Надежная проверка авторизации
checkDungeonAuth();

// Получаем данные пользователя
$user_id = $_SESSION['user_id'];
$user = $mc->query("SELECT * FROM `users` WHERE `id` = '$user_id'")->fetch_array(MYSQLI_ASSOC);

// Получаем данные сессии
$session_id = $_GET['session'] ?? null;
if (!$session_id) {
    header('Location: /dungeons/index.php');
    exit;
}

$session = $mc->query("SELECT * FROM `dungeon_sessions` WHERE `id` = '$session_id' AND `user_id` = '$user_id'")->fetch_array(MYSQLI_ASSOC);
if (!$session) {
    header('Location: /dungeons/index.php');
    exit;
}

$exploration_data = json_decode($session['session_data'], true);

// Получаем данные боя
$battle_id = $_GET['battle'] ?? null;
if (!$battle_id) {
    header('Location: /dungeons/explore.php?session=' . $session_id);
    exit;
}

$battle = $mc->query("SELECT * FROM `battle` WHERE `id` = '$battle_id' AND `user_id` = '$user_id'")->fetch_array(MYSQLI_ASSOC);
if (!$battle) {
    header('Location: /dungeons/explore.php?session=' . $session_id);
    exit;
}

// Получаем данные монстра
$enemy_id = $battle['enemy_id'];
$enemy = $mc->query("SELECT * FROM `dungeon_monsters` WHERE `id` = '$enemy_id'")->fetch_array(MYSQLI_ASSOC);
if (!$enemy) {
    header('Location: /dungeons/explore.php?session=' . $session_id);
    exit;
}

// Обрабатываем действия в бою
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'attack':
            // Расчет параметров игрока
            $player_accuracy = isset($user['accuracy']) ? $user['accuracy'] : 70;
            $player_damage_min = isset($user['damage_min']) ? $user['damage_min'] : 10;
            $player_damage_max = isset($user['damage_max']) ? $user['damage_max'] : 30;
            
            // Расчет параметров монстра
            $monster_accuracy = isset($enemy['accuracy']) ? $enemy['accuracy'] : 60;
            $monster_evasion = isset($enemy['evasion']) ? $enemy['evasion'] : 10;
            $monster_armor = isset($enemy['armor']) ? $enemy['armor'] : 5;
            
            $player_evasion = isset($user['evasion']) ? $user['evasion'] : 20;
            $player_armor = isset($user['armor']) ? $user['armor'] : 10;
            
            // Проверка попадания игрока
            $hit_chance = $player_accuracy - $monster_evasion;
            $hit_roll = rand(1, 100);
            
            if ($hit_roll <= $hit_chance) {
                // Игрок попадает
                $base_damage = rand($player_damage_min, $player_damage_max);
                $damage_reduction = $monster_armor / 100; // Броня снижает урон в процентах
                $user_damage = max(1, round($base_damage * (1 - $damage_reduction)));
            } else {
                // Игрок промахивается
                $user_damage = 0;
            }
            
            // Проверка попадания монстра
            $monster_hit_chance = $monster_accuracy - $player_evasion;
            $monster_hit_roll = rand(1, 100);
            
            if ($monster_hit_roll <= $monster_hit_chance) {
                // Монстр попадает
                $monster_damage_base = isset($enemy['damage']) ? $enemy['damage'] : 15;
                $monster_damage_min = max(1, round($monster_damage_base * 0.8));
                $monster_damage_max = round($monster_damage_base * 1.2);
                $base_damage = rand($monster_damage_min, $monster_damage_max);
                $damage_reduction = $player_armor / 100; // Броня снижает урон в процентах
                $enemy_damage = max(1, round($base_damage * (1 - $damage_reduction)));
            } else {
                // Монстр промахивается
                $enemy_damage = 0;
            }
            
            // Применяем урон
            $battle['enemy_hp'] = max(0, $battle['enemy_hp'] - $user_damage);
            $battle['user_hp'] = max(0, $battle['user_hp'] - $enemy_damage);
            
            // Обновляем статистику урона
            $battle['user_uron'] += $user_damage;
            $battle['enemy_uron'] += $enemy_damage;
            
            // Проверяем условия победы
            if ($battle['enemy_hp'] <= 0) {
                $battle['victory'] = 1;
                $battle['end_time'] = time();
                
                // Рассчитываем награды
                $gold_reward = rand($enemy['gold_min'], $enemy['gold_max']);
                $exp_reward = rand($enemy['exp_min'], $enemy['exp_max']);
                
                // Если это босс, увеличиваем награды
                if ($enemy['is_boss']) {
                    $gold_reward *= 3;
                    $exp_reward *= 3;
                }
                
                // Обновляем данные пользователя
                $mc->query("UPDATE `users` SET 
                    `gold` = `gold` + " . $gold_reward . ",
                    `exp` = `exp` + " . $exp_reward . "
                    WHERE `id` = '$user_id'");
                
                // Обновляем статистику
                $exploration_data['stats']['monsters_defeated']++;
                if ($enemy['is_boss']) {
                    $exploration_data['stats']['boss_defeated']++;
                }
                
                // Сохраняем данные сессии
                $session_data_json = json_encode($exploration_data, JSON_UNESCAPED_UNICODE);
                $mc->query("UPDATE `dungeon_sessions` SET `session_data` = '" . $mc->real_escape_string($session_data_json) . "' WHERE `id` = '$session_id'");
                
                // Добавляем сообщение о победе
                $mc->query("INSERT INTO `msg` (`id_user`, `message`, `date`, `type`) VALUES ('$user_id', 'Вы победили " . $enemy['name'] . " и получили " . $gold_reward . " золота и " . $exp_reward . " опыта!', '" . time() . "', 'msg')");
                
            } elseif ($battle['user_hp'] <= 0) {
                $battle['victory'] = 0;
                $battle['end_time'] = time();
                
                // Обновляем данные пользователя
                $mc->query("UPDATE `users` SET `health` = 1 WHERE `id` = '$user_id'");
                
                // Добавляем сообщение о поражении
                $mc->query("INSERT INTO `msg` (`id_user`, `message`, `date`, `type`) VALUES ('$user_id', 'Вы проиграли бой с " . $enemy['name'] . "!', '" . time() . "', 'msg')");
            }
            
            // Сохраняем данные боя
            $mc->query("UPDATE `battle` SET 
                `user_hp` = '" . $battle['user_hp'] . "',
                `enemy_hp` = '" . $battle['enemy_hp'] . "',
                `user_uron` = '" . $battle['user_uron'] . "',
                `enemy_uron` = '" . $battle['enemy_uron'] . "',
                `victory` = '" . $battle['victory'] . "',
                `end_time` = '" . $battle['end_time'] . "'
                WHERE `id` = '$battle_id'");
            
            // Перенаправляем на страницу боя
            header('Location: battle.php?session=' . $session_id . '&battle=' . $battle_id);
            exit;
            break;
            
        case 'flee':
            // Проверяем шанс сбежать
            $flee_chance = $enemy['is_boss'] ? 30 : 70; // С босса сбежать сложнее
            
            if (rand(1, 100) <= $flee_chance) {
                // Успешный побег
                $battle['fled'] = 1;
                $battle['end_time'] = time();
                
                // Сохраняем данные боя
                $mc->query("UPDATE `battle` SET 
                    `fled` = 1,
                    `end_time` = '" . $battle['end_time'] . "'
                    WHERE `id` = '$battle_id'");
                
                // Добавляем сообщение о побеге
                $mc->query("INSERT INTO `msg` (`id_user`, `message`, `date`, `type`) VALUES ('$user_id', 'Вы успешно сбежали от " . $enemy['name'] . "!', '" . time() . "', 'msg')");
                
                // Перенаправляем на страницу исследования
                header('Location: /dungeons/explore.php?session=' . $session_id);
                exit;
            } else {
                // Неудачный побег
                // Получаем ответный удар от монстра
                $monster_damage_base = isset($enemy['damage']) ? $enemy['damage'] : 15;
                $enemy_damage = rand(max(1, round($monster_damage_base * 0.8)), round($monster_damage_base * 1.2));
                
                $battle['user_hp'] = max(0, $battle['user_hp'] - $enemy_damage);
                $battle['enemy_uron'] += $enemy_damage;
                
                // Проверяем, не убит ли игрок
                if ($battle['user_hp'] <= 0) {
                    $battle['victory'] = 0;
                    $battle['end_time'] = time();
                    
                    // Обновляем данные пользователя
                    $mc->query("UPDATE `users` SET `health` = 1 WHERE `id` = '$user_id'");
                    
                    // Добавляем сообщение о поражении
                    $mc->query("INSERT INTO `msg` (`id_user`, `message`, `date`, `type`) VALUES ('$user_id', 'Вы не смогли сбежать и были повержены " . $enemy['name'] . "!', '" . time() . "', 'msg')");
                } else {
                    // Добавляем сообщение о неудачном побеге
                    $mc->query("INSERT INTO `msg` (`id_user`, `message`, `date`, `type`) VALUES ('$user_id', 'Вы не смогли сбежать и получили " . $enemy_damage . " урона!', '" . time() . "', 'msg')");
                }
                
                // Сохраняем данные боя
                $mc->query("UPDATE `battle` SET 
                    `user_hp` = '" . $battle['user_hp'] . "',
                    `enemy_uron` = '" . $battle['enemy_uron'] . "',
                    `victory` = '" . $battle['victory'] . "',
                    `end_time` = '" . $battle['end_time'] . "'
                    WHERE `id` = '$battle_id'");
                
                // Перенаправляем на страницу боя
                header('Location: battle.php?session=' . $session_id . '&battle=' . $battle_id);
                exit;
            }
            break;
    }
}

// Проверяем, завершен ли бой
if (isset($battle['end_time']) && $battle['end_time'] > 0) {
    if ($battle['victory']) {
        header('Location: /dungeons/explore.php?session=' . $session_id . '&message=victory');
    } else {
        header('Location: /dungeons/explore.php?session=' . $session_id . '&message=defeat');
    }
    exit;
}

// Если бой не завершен, отображаем страницу боя
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бой в подземелье</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="battle-container">
            <div class="battle-header">
                <h1>Бой в подземелье</h1>
                <div class="battle-stats">
                    <div class="stat">
                        <i class="fas fa-heart"></i>
                        <span>Здоровье: <?php echo $battle['user_hp']; ?>/<?php echo $user['max_health'] ?? 100; ?></span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-sword"></i>
                        <span>Нанесено урона: <?php echo $battle['user_uron']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="battle-content">
                <div class="battle-enemy">
                    <div class="enemy-image">
                        <?php $image_path = file_exists("../img/mobs/{$enemy['image']}") ? "../img/mobs/{$enemy['image']}" : "../img/mobs/default_monster.png"; ?>
                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($enemy['name']); ?>">
                    </div>
                    <div class="enemy-info">
                        <h2><?php echo htmlspecialchars($enemy['name']); ?></h2>
                        <div class="enemy-stats">
                            <div class="stat">
                                <i class="fas fa-heart"></i>
                                <span>Здоровье: <?php echo $battle['enemy_hp']; ?>/<?php echo $enemy['health']; ?></span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-sword"></i>
                                <span>Нанесено урона: <?php echo $battle['enemy_uron']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="battle-actions">
                    <form method="post" class="action-form">
                        <input type="hidden" name="action" value="attack">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sword"></i> Атаковать
                        </button>
                    </form>
                    
                    <form method="post" class="action-form">
                        <input type="hidden" name="action" value="flee">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-running"></i> Сбежать
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/battle.js"></script>
</body>
</html>