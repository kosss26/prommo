<?php
// Отладочная информация - выведем данные о запросе и сессии
error_log("=== DEBUG START.PHP ===");
error_log("REQUEST: " . print_r($_REQUEST, true));
error_log("SESSION: " . print_r($_SESSION, true));
error_log("SERVER: " . print_r($_SERVER, true));
error_log("COOKIE: " . print_r($_COOKIE, true));

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

// Проверяем наличие ID подземелья
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

$dungeon_id = intval($_GET['id']);

// Получаем информацию о подземелье
$dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '$dungeon_id' AND `active` = '1'")->fetch_array(MYSQLI_ASSOC);
if (!$dungeon) {
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Подземелье не найдено!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Проверяем уровень игрока
if ($user['level'] < $dungeon['min_level']) {
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Ваш уровень слишком низкий для этого подземелья!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Проверяем попытки
$attempts = $mc->query("SELECT * FROM `dungeon_attempts` WHERE `user_id` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
if (!$attempts) {
    $mc->query("INSERT INTO `dungeon_attempts` (`user_id`, `current_attempts`, `max_attempts`) VALUES ('" . $user['id'] . "', 3, 3)");
    $attempts = [
        'current_attempts' => 3,
        'max_attempts' => 3
    ];
}

if ($attempts['current_attempts'] <= 0) {
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','У вас закончились попытки!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Уменьшаем количество попыток
$mc->query("UPDATE `dungeon_attempts` SET `current_attempts` = `current_attempts` - 1 WHERE `user_id` = '" . $user['id'] . "'");

// Инициализируем данные сессии
$session_data = [
    'dungeon_id' => $dungeon_id,
    'dungeon_name' => $dungeon['name'],
    'current_room' => 1,
    'rooms' => [],
    'path' => [],
    'remaining_monsters' => 0,
    'level' => $dungeon['min_level'],
    'stats' => [
        'rooms_visited' => 0,
        'monsters_defeated' => 0,
        'items_found' => 0,
        'puzzles_solved' => 0,
        'boss_defeated' => 0,
        'gold_earned' => 0,
        'exp_earned' => 0
    ]
];

// Генерируем первую комнату
$first_room = generateRoom($dungeon_id, 1);
if (!$first_room) {
    // Ошибка генерации комнаты
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Ошибка генерации комнаты!','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/dungeons/index.php");</script><?php
    exit(0);
}

// Добавляем уровень в данные комнаты
$first_room['level'] = 1;
$session_data['rooms'][1] = $first_room;

// Создаем сессию
$session_data_json = json_encode($session_data, JSON_UNESCAPED_UNICODE);
$mc->query("INSERT INTO `dungeon_sessions` (`user_id`, `dungeon_id`, `session_data`, `start_time`) 
            VALUES ('" . $user['id'] . "', '$dungeon_id', '" . $mc->real_escape_string($session_data_json) . "', '" . time() . "')");

$session_id = $mc->insert_id;

// Перенаправляем на страницу исследования
header("Location: /dungeons/explore.php?session=" . $session_id);
exit(0);
?> 