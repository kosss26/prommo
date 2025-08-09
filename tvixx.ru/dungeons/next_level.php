<?php
require_once '../system/func.php';
require_once '../system/dbc.php';
require_once '../system/header.php';

auth(); // Закроем от неавторизированных
requestModer(); // Закроем для тех у кого есть запрос на модератора

// Проверяем наличие ID сессии
if (!isset($_GET['session']) || !is_numeric($_GET['session'])) {
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

$session_id = intval($_GET['session']);

// Получаем данные сессии
$session = $mc->query("SELECT * FROM `dungeon_sessions` WHERE `id` = '$session_id' AND `user_id` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
if (!$session) {
    // Сессия не найдена или принадлежит другому игроку
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Сессия исследования не найдена!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Декодируем данные сессии
$exploration_data = json_decode($session['session_data'], true);
if (!$exploration_data) {
    // Некорректные данные сессии
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Ошибка данных сессии подземелья!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Получаем информацию о подземелье
$dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '" . $exploration_data['dungeon_id'] . "'")->fetch_array(MYSQLI_ASSOC);
if (!$dungeon) {
    // Подземелье не найдено
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Данные подземелья не найдены!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Проверяем, что игрок завершил текущий уровень
if ($exploration_data['current_room'] < $exploration_data['total_rooms'] || $exploration_data['remaining_monsters'] > 0) {
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вы еще не завершили текущий уровень подземелья!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/explore.php?session=<?= $session_id ?>");</script><?php
    exit(0);
}

// Проверяем, можно ли перейти на следующий уровень
$max_level = $dungeon['max_level'] ?? 10;
if ($exploration_data['current_level'] >= $max_level) {
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вы уже достигли максимального уровня этого подземелья!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/complete.php?session=<?= $session_id ?>");</script><?php
    exit(0);
}

// Если все проверки пройдены, выполняем переход на следующий уровень
$next_level = $exploration_data['current_level'] + 1;

// Обновляем прогресс подземелья
$mc->query("UPDATE `dungeon_progress` SET `current_level` = '$next_level', `max_reached_level` = GREATEST(`max_reached_level`, '$next_level') WHERE `user_id` = '" . $user['id'] . "' AND `dungeon_id` = '" . $exploration_data['dungeon_id'] . "'");

// Генерируем данные для следующего уровня
$difficulty_multiplier = 1 + ($dungeon['difficulty'] * 0.2);
$level_multiplier = 1 + (($next_level - 1) * 0.1);

// Увеличиваем количество комнат и монстров с каждым уровнем
$room_count = min(3 + floor($next_level / 2), 8); // Максимум 8 комнат
$monster_count = min(3 + floor($next_level / 3), 10); // Максимум 10 монстров

// Получаем случайных монстров для этого уровня
$monsters_base_level = $dungeon['min_level'] + floor(($next_level - 1) / 2);
$monsters = $mc->query("SELECT * FROM `mobs` WHERE `lvl` >= $monsters_base_level ORDER BY RAND() LIMIT $monster_count")->fetch_all(MYSQLI_ASSOC);

if (!$monsters) {
    // Если подходящих монстров нет, берем любых с минимальным уровнем
    $monsters = $mc->query("SELECT * FROM `mobs` WHERE `lvl` >= " . $dungeon['min_level'] . " ORDER BY RAND() LIMIT $monster_count")->fetch_all(MYSQLI_ASSOC);
}

if (!$monsters) {
    // В крайнем случае берем любых монстров
    $monsters = $mc->query("SELECT * FROM `mobs` ORDER BY RAND() LIMIT $monster_count")->fetch_all(MYSQLI_ASSOC);
}

// Усиливаем характеристики монстров
foreach ($monsters as &$monster) {
    $monster['health'] = ceil($monster['health'] * $difficulty_multiplier * $level_multiplier);
    $monster['damage'] = ceil($monster['damage'] * $difficulty_multiplier * $level_multiplier);
    $monster['exp'] = ceil($monster['exp'] * (1 + $next_level * 0.1));
    $monster['gold'] = ceil($monster['gold'] * (1 + $next_level * 0.15));
}

// Формируем данные для нового уровня
$next_floor_data = [
    'floor_name' => "Уровень $next_level",
    'description' => "Глубокий уровень подземелья с усиленными монстрами",
    'room_count' => $room_count,
    'boss_monster_id' => isset($monsters[0]) ? $monsters[0]['id'] : 1,
    'monsters' => $monsters
];

// Обновляем данные исследования
$exploration_data['current_level'] = $next_level;
$exploration_data['current_room'] = 1;
$exploration_data['total_rooms'] = $room_count;
$exploration_data['remaining_monsters'] = count($monsters);
$exploration_data['floor_data'] = $next_floor_data;

// Сохраняем изменения в сессии
$mc->query("UPDATE `dungeon_sessions` SET `session_data` = '" . $mc->real_escape_string(json_encode($exploration_data)) . "' WHERE `id` = '$session_id'");

// Обрабатываем завершение предыдущего уровня
// Выдаем бонус за прохождение уровня
$level_completion_gold = 100 * ($next_level - 1);
$level_completion_exp = 50 * ($next_level - 1);

// Выдаем награды
if ($level_completion_gold > 0) {
    $mc->query("UPDATE `users` SET `gold` = `gold` + $level_completion_gold WHERE `id` = '" . $user['id'] . "'");
    $exploration_data['rewards']['gold'] += $level_completion_gold;
}

if ($level_completion_exp > 0) {
    $mc->query("UPDATE `users` SET `exp` = `exp` + $level_completion_exp WHERE `id` = '" . $user['id'] . "'");
    $exploration_data['rewards']['exp'] += $level_completion_exp;
}

// Обновляем данные с учетом наград
$mc->query("UPDATE `dungeon_sessions` SET `session_data` = '" . $mc->real_escape_string(json_encode($exploration_data)) . "' WHERE `id` = '$session_id'");

?>

<style>
/* Основные стили страницы перехода на следующий уровень */
.next_level_container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(20, 20, 30, 0.9);
    border-radius: 16px;
    color: #e0e0e0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.dungeon_header {
    background: linear-gradient(135deg, #006400, #228B22, #32CD32);
    color: #FFF8DC;
    padding: 18px 20px;
    border-radius: 12px;
    text-align: center;
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 25px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.dungeon_header:before {
    content: '';
    position: absolute;
    top: 0;
    left: -50%;
    width: 200%;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.level_complete_message {
    font-size: 1.3rem;
    color: #FFD700;
    margin-bottom: 30px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.reward_box {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    padding: 15px;
    margin: 20px auto;
    max-width: 400px;
    border: 1px solid rgba(50, 200, 50, 0.3);
    animation: glow_green 2s infinite;
}

@keyframes glow_green {
    0% { box-shadow: 0 0 5px rgba(50, 200, 50, 0.3); }
    50% { box-shadow: 0 0 15px rgba(50, 200, 50, 0.5); }
    100% { box-shadow: 0 0 5px rgba(50, 200, 50, 0.3); }
}

.reward_title {
    font-size: 1.1rem;
    font-weight: bold;
    color: #FFD700;
    margin-bottom: 15px;
}

.rewards_grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    text-align: center;
}

.reward_item {
    background: rgba(255, 255, 255, 0.1);
    padding: 10px;
    border-radius: 8px;
}

.reward_label {
    font-size: 0.9rem;
    color: #c0c0c0;
    margin-bottom: 5px;
}

.reward_value {
    font-size: 1.2rem;
    font-weight: bold;
    color: #FFD700;
}

.next_level_info {
    background: rgba(50, 50, 70, 0.6);
    padding: 20px;
    border-radius: 10px;
    margin: 25px 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.level_title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #FFD700;
    margin-bottom: 15px;
}

.level_description {
    margin-bottom: 20px;
    line-height: 1.5;
}

.level_stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    text-align: center;
    margin-top: 20px;
}

.level_stat {
    background: rgba(0, 0, 0, 0.2);
    padding: 12px;
    border-radius: 8px;
}

.stat_label {
    font-size: 0.9rem;
    color: #c0c0c0;
    margin-bottom: 5px;
}

.stat_value {
    font-size: 1.1rem;
    font-weight: bold;
    color: #FFD700;
}

.action_btns {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
}

.dungeon_btn {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
}

.continue_btn {
    background: #228B22;
}

.continue_btn:hover {
    background: #32CD32;
    transform: scale(1.05);
}

.leave_btn {
    background: #4169E1;
}

.leave_btn:hover {
    background: #6495ED;
    transform: scale(1.05);
}
</style>

<div class="next_level_container">
    <div class="dungeon_header">
        <?= $dungeon['name'] ?> - Уровень пройден!
    </div>
    
    <div class="level_complete_message">
        Вы успешно прошли уровень <?= $next_level - 1 ?>!
    </div>
    
    <div class="reward_box">
        <div class="reward_title">Награды за уровень</div>
        <div class="rewards_grid">
            <div class="reward_item">
                <div class="reward_label">Золото</div>
                <div class="reward_value">+<?= $level_completion_gold ?></div>
            </div>
            <div class="reward_item">
                <div class="reward_label">Опыт</div>
                <div class="reward_value">+<?= $level_completion_exp ?></div>
            </div>
        </div>
    </div>
    
    <div class="next_level_info">
        <div class="level_title">Уровень <?= $next_level ?></div>
        <div class="level_description">
            Вы спускаетесь глубже в подземелье. Монстры становятся сильнее, но и сокровища ценнее. 
            Будьте осторожны, впереди вас ждут новые опасности!
        </div>
        
        <div class="level_stats">
            <div class="level_stat">
                <div class="stat_label">Комнат</div>
                <div class="stat_value"><?= $room_count ?></div>
            </div>
            <div class="level_stat">
                <div class="stat_label">Монстров</div>
                <div class="stat_value"><?= count($monsters) ?></div>
            </div>
            <div class="level_stat">
                <div class="stat_label">Сложность</div>
                <div class="stat_value">
                    <?php 
                    $difficulty_percent = round($difficulty_multiplier * $level_multiplier * 100);
                    echo "+{$difficulty_percent}%";
                    ?>
                </div>
            </div>
            <div class="level_stat">
                <div class="stat_label">Награды</div>
                <div class="stat_value">
                    <?php 
                    $reward_percent = round((1 + $next_level * 0.15) * 100);
                    echo "+{$reward_percent}%";
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="action_btns">
        <button class="dungeon_btn continue_btn" onclick="showContent('/dungeons/explore.php?session=<?= $session_id ?>')">Продолжить исследование</button>
        <button class="dungeon_btn leave_btn" onclick="showContent('/dungeons/complete.php?session=<?= $session_id ?>')">Покинуть подземелье</button>
    </div>
</div> 