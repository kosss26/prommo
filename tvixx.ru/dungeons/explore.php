<?php
// Включаем отладку только при необходимости
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    $debug_info = [
        'session' => $_SESSION,
        'cookie' => $_COOKIE,
        'request' => $_REQUEST,
        'server' => $_SERVER
    ];
    error_log("Debug explore.php: " . print_r($debug_info, true));
}

require_once '../system/func.php';
require_once '../system/dbc.php';
require_once 'functions.php';

// Проверка и запуск сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Восстановление сессии из cookies
if (!isset($_SESSION['user_id']) && isset($_COOKIE['id'])) {
    $_SESSION['user_id'] = $_COOKIE['id'];
    if (DEBUG_MODE) error_log("EXPLORE.PHP: Восстановили user_id из кук: " . $_COOKIE['id']);
}

auth(); // Проверка авторизации
requestModer(); // Проверка запроса на модератора
checkDungeonAuth(); // Дополнительная проверка для подземелий

// Проверка ID сессии
$session_id = isset($_GET['session']) ? (int)$_GET['session'] : null;
if (!$session_id) {
    header('Location: /dungeons/index.php');
    exit(0);
}

// Получение данных сессии
$stmt = $mc->prepare("SELECT * FROM `dungeon_sessions` WHERE `id` = ? AND `user_id` = ?");
$stmt->bind_param("ii", $session_id, $user['id']);
$stmt->execute();
$session = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
if (!$session) {
    header('Location: /dungeons/index.php');
    exit(0);
}

$exploration_data = json_decode($session['session_data'], true) ?: [
    'dungeon_id' => 1,
    'current_room' => 0,
    'rooms' => [],
    'stats' => [
        'rooms_explored' => 0,
        'items_found' => 0,
        'monsters_defeated' => 0
    ],
    'messages' => []
];

// Инициализация текущей комнаты
if (!isset($exploration_data['current_room']) || !isset($exploration_data['rooms'][$exploration_data['current_room']])) {
    $exploration_data['current_room'] = 0;
    $exploration_data['rooms'][0] = generateRoom(1, $exploration_data['dungeon_id']);
    $exploration_data['stats']['rooms_explored'] = 1;
    $exploration_data['messages'][] = "Вы входите в подземелье...";
    saveSessionData($session_id, $exploration_data);
}

$current_room = $exploration_data['rooms'][$exploration_data['current_room']];

// Обработка взаимодействия с объектом
if (isset($_GET['interact'])) {
    $target_id = (int)$_GET['interact'];
    $result = handleInteraction($target_id, $current_room);
    
    if (isset($result['success']) && $result['success']) {
        $exploration_data['rooms'][$exploration_data['current_room']] = $current_room;
        if (isset($result['message'])) {
            $exploration_data['messages'][] = $result['message'];
        }
        saveSessionData($session_id, $exploration_data);

        if (isset($result['redirect'])) {
            header("Location: {$result['redirect']}&session=$session_id");
            exit(0);
        }
        if (isset($result['puzzle'])) {
            $puzzle_data = $result['puzzle'];
        }
    }
}

// Обработка перемещения
if (isset($_GET['move'])) {
    $direction = $_GET['move'];
    if (isset($current_room['exits'][$direction])) {
        $next_room_id = $current_room['exits'][$direction];
        if (!isset($exploration_data['rooms'][$next_room_id])) {
            $level = $current_room['level'] ?? 1;
            $exploration_data['rooms'][$next_room_id] = generateRoom($level, $exploration_data['dungeon_id']);
            $exploration_data['stats']['rooms_explored']++;
        }
        $exploration_data['current_room'] = $next_room_id;
        $exploration_data['messages'][] = "Вы переходите в новую комнату...";
        saveSessionData($session_id, $exploration_data);
        $current_room = $exploration_data['rooms'][$exploration_data['current_room']];
    }
}

// Тип комнаты и прогресс
$room_type = isset($current_room['level']) && $current_room['level'] == $exploration_data['dungeon_id'] ? 'boss' : 'normal';
$progress = min(100, round(($exploration_data['stats']['rooms_explored'] / (10 * $exploration_data['dungeon_id'])) * 100));
$dungeon_name = $exploration_data['dungeon_name'] ?? "Подземелье уровня {$exploration_data['dungeon_id']}";
$room_description = $current_room['description'] ?? "Вы находитесь в комнате подземелья.";

// Вспомогательная функция для сохранения сессии
function saveSessionData($session_id, $data) {
    global $mc;
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    $stmt = $mc->prepare("UPDATE `dungeon_sessions` SET `session_data` = ? WHERE `id` = ?");
    $stmt->bind_param("si", $json, $session_id);
    $stmt->execute();
}

// Функция для получения иконок
function getObjectIcon($type) {
    $icons = [
        'chest' => 'fas fa-treasure-chest',
        'monster' => 'fas fa-skull',
        'boss' => 'fas fa-dragon',
        'puzzle' => 'fas fa-puzzle-piece',
        'trap' => 'fas fa-bomb',
        'fountain' => 'fas fa-tint',
        'portal' => 'fas fa-portal-enter',
        'item' => 'fas fa-gem',
        'door' => 'fas fa-door-closed'
    ];
    return $icons[$type] ?? 'fas fa-question';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dungeon_name); ?></title>
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

        .explore-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 15px;
        }

        .explore-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary-color);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .explore-header h2 {
            margin: 0;
            font-size: 20px;
        }

        .explore-level {
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 5px;
        }

        .room-description {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            margin-bottom: 15px;
            font-size: 16px;
        }

        .room-objects {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .room-object {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .room-object i {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .interact-button {
            display: inline-block;
            padding: 8px 15px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .interact-button:hover {
            background: var(--secondary-color);
        }

        .no-objects {
            text-align: center;
            color: #666;
            font-style: italic;
        }

        .room-exits {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
        }

        .exits-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .exit-button {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .exit-button:hover {
            background: var(--secondary-color);
        }

        .messages {
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
        }

        .messages h3 {
            margin-top: 0;
            font-size: 18px;
        }

        .messages ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .messages li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .messages li:last-child {
            border-bottom: none;
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
            .explore-header h2 { font-size: 18px; }
            .explore-level { font-size: 12px; }
            .room-objects { grid-template-columns: 1fr; }
            .room-object i { font-size: 20px; }
            .interact-button { padding: 6px 12px; }
            .exit-button { padding: 8px 16px; }
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
    <div class="explore-container fade-in">
        <div class="explore-header">
            <h2><?php echo htmlspecialchars($dungeon_name); ?></h2>
            <div class="explore-level">Уровень <?php echo htmlspecialchars($current_room['level'] ?? '1'); ?></div>
        </div>

        <div class="room-description"><?php echo htmlspecialchars($room_description); ?></div>

        <div class="room-objects">
            <?php if (!empty($current_room['objects'])): ?>
                <?php foreach ($current_room['objects'] as $key => $object): ?>
                    <?php if (isset($object['interacted']) && $object['interacted']) continue; ?>
                    <div class="room-object <?php echo htmlspecialchars($object['type']); ?>">
                        <i class="<?php echo getObjectIcon($object['type']); ?>"></i>
                        <span><?php echo htmlspecialchars($object['description']); ?></span>
                        <a href="?session=<?php echo $session_id; ?>&interact=<?php echo $key; ?>" class="interact-button">
                            <?php echo htmlspecialchars(getInteractText($object['type'])); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-objects">В комнате пусто...</p>
            <?php endif; ?>
        </div>

        <div class="room-exits">
            <?php if (!empty($current_room['exits'])): ?>
                <h3>Выходы:</h3>
                <div class="exits-container">
                    <?php foreach ($current_room['exits'] as $direction => $room_id): ?>
                        <a href="?session=<?php echo $session_id; ?>&move=<?php echo htmlspecialchars($direction); ?>" class="exit-button <?php echo htmlspecialchars($direction); ?>">
                            <?php echo htmlspecialchars(getDirectionName($direction)); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-exits">Нет видимых выходов...</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="messages">
        <?php if (!empty($exploration_data['messages'])): ?>
            <h3>Журнал:</h3>
            <ul>
                <?php foreach (array_slice(array_reverse($exploration_data['messages']), 0, 5) as $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (isset($_GET['message'])): ?>
            <div class="message"><?php echo htmlspecialchars(getMessageText($_GET['message'])); ?></div>
        <?php endif; ?>
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
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let touchStartX = 0, touchStartY = 0, touchEndX = 0, touchEndY = 0;
            document.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
                touchStartY = e.changedTouches[0].screenY;
            });
            document.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                touchEndY = e.changedTouches[0].screenY;
                const diffX = touchEndX - touchStartX, diffY = touchEndY - touchStartY;
                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    if (diffX > 0) document.querySelector('.exit-button.west')?.click();
                    else document.querySelector('.exit-button.east')?.click();
                } else if (Math.abs(diffY) > 50) {
                    if (diffY > 0) document.querySelector('.exit-button.south')?.click();
                    else document.querySelector('.exit-button.north')?.click();
                }
            });

            document.querySelectorAll('.exit-button, .interact-button').forEach(btn => {
                btn.addEventListener('touchstart', () => btn.style.transform = 'scale(0.95)');
                btn.addEventListener('touchend', () => btn.style.transform = 'scale(1)');
            });
        });
    </script>
</body>
</html>

<?php
// Вспомогательные функции
function getInteractText($type) {
    $texts = [
        'monster' => 'Атаковать',
        'boss' => 'Атаковать',
        'chest' => 'Открыть',
        'fountain' => 'Испить',
        'puzzle' => 'Исследовать',
        'trap' => 'Обезвредить'
    ];
    return $texts[$type] ?? 'Взаимодействовать';
}

function getDirectionName($direction) {
    $names = [
        'north' => 'Север',
        'south' => 'Юг',
        'east' => 'Восток',
        'west' => 'Запад',
        'up' => 'Наверх',
        'down' => 'Вниз'
    ];
    return $names[$direction] ?? ucfirst($direction);
}

function getMessageText($type) {
    $messages = [
        'victory' => 'Вы одержали победу в бою!',
        'defeat' => 'Вы потерпели поражение...'
    ];
    return $messages[$type] ?? $type;
}
?>