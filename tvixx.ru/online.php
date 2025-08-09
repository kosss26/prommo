<?php
// --- Начало PHP логики ---
require_once ('system/dbc.php'); // Подключаем базу данных (добавлено, т.к. используется в friends.php)
require_once ('system/func.php'); // Подключаем основные функции
$footval = "online"; // Переменная для футера
require_once ('system/foot/foot.php'); // Подключаем футер
auth(); // Проверка авторизации пользователя
requestModer(); // Проверка запроса на модератора
// --- Конец PHP логики ---

// --- Начало функции пагинации (адаптировано под новый стиль) ---
/**
 * Генерирует HTML для пагинации в стиле страницы друзей.
 *
 * @param string $href Базовый URL для ссылок пагинации (например, "online.php").
 * @param int $currentPage Текущий номер страницы (начиная с 1).
 * @param int $maxPages Общее количество страниц.
 * @param int $visibleLinks Количество видимых ссылок до и после текущей страницы.
 * @return string HTML-код пагинации.
 */
function pagination($href, $currentPage, $maxPages, $visibleLinks = 2) {
    if ($maxPages <= 1) {
        return ''; // Не показывать пагинацию, если страниц мало
    }

    $output = '<nav class="pagination-themed">'; // Обертка для пагинации

    // Ссылка "Назад"
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $output .= "<a onclick=\"showContent('{$href}?list={$prevPage}');\" href=\"#\" class=\"pagination-link pagination-arrow\">&laquo;</a>";
    } else {
        $output .= "<span class=\"pagination-link pagination-disabled pagination-arrow\">&laquo;</span>";
    }

    // Первая страница и многоточие
    if ($currentPage > $visibleLinks + 1) {
        $output .= "<a onclick=\"showContent('{$href}?list=1');\" href=\"#\" class=\"pagination-link\">1</a>";
        if ($currentPage > $visibleLinks + 2) {
            $output .= '<span class="pagination-separator">...</span>';
        }
    }

    // Страницы вокруг текущей
    $start = max(1, $currentPage - $visibleLinks);
    $end = min($maxPages, $currentPage + $visibleLinks);

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $output .= "<span class=\"pagination-link pagination-current\">{$i}</span>";
        } else {
            $output .= "<a onclick=\"showContent('{$href}?list={$i}');\" href=\"#\" class=\"pagination-link\">{$i}</a>";
        }
    }

    // Последняя страница и многоточие
    if ($currentPage < $maxPages - $visibleLinks) {
        if ($currentPage < $maxPages - $visibleLinks - 1) {
            $output .= '<span class="pagination-separator">...</span>';
        }
        $output .= "<a onclick=\"showContent('{$href}?list={$maxPages}');\" href=\"#\" class=\"pagination-link\">{$maxPages}</a>";
    }

    // Ссылка "Вперед"
    if ($currentPage < $maxPages) {
        $nextPage = $currentPage + 1;
        $output .= "<a onclick=\"showContent('{$href}?list={$nextPage}');\" href=\"#\" class=\"pagination-link pagination-arrow\">&raquo;</a>";
    } else {
        $output .= "<span class=\"pagination-link pagination-disabled pagination-arrow\">&raquo;</span>";
    }

    $output .= '</nav>';
    return $output;
}
// --- Конец функции пагинации ---

// --- Получение данных для отображения ---
$date = time();
$listLevelParam = isset($_GET['listLevel']) ? $_GET['listLevel'] : '';
$listPageParam = isset($_GET['list']) ? (int)$_GET['list'] : 1;
$limf = ($listPageParam - 1) * 10; // Смещение для запроса последнего онлайна

// Формирование условия для фильтрации по уровню
$onlineLevelCondition = "";
if ($listLevelParam === "1") $onlineLevelCondition = "AND `level` BETWEEN 1 AND 5";
elseif ($listLevelParam === "2") $onlineLevelCondition = "AND `level` BETWEEN 6 AND 10";
elseif ($listLevelParam === "3") $onlineLevelCondition = "AND `level` BETWEEN 10 AND 15";
elseif ($listLevelParam === "4") $onlineLevelCondition = "AND `level` >= 16";

// Запрос онлайн игроков
$stmtOnline = $mc->prepare("SELECT id, name, level, side FROM `users` WHERE `online` > (? - 60) {$onlineLevelCondition} ORDER BY `level` DESC LIMIT 200");
$stmtOnline->bind_param("i", $date);
$stmtOnline->execute();
$onlinePlayersResult = $stmtOnline->get_result();
$onlinePlayers = $onlinePlayersResult ? $onlinePlayersResult->fetch_all(MYSQLI_ASSOC) : [];

// Запрос недавно вышедших игроков (для пагинации)
$stmtLastOnlineTotal = $mc->prepare("SELECT COUNT(*) as total FROM `users` WHERE `online` < (? - 60) AND `online` > 0");
$stmtLastOnlineTotal->bind_param("i", $date);
$stmtLastOnlineTotal->execute();
$lastOnlineTotalResult = $stmtLastOnlineTotal->get_result();
$lastOnlineTotal = $lastOnlineTotalResult ? $lastOnlineTotalResult->fetch_assoc()['total'] : 0;
$maxPagesLastOnline = ceil($lastOnlineTotal / 10);

$stmtLastOnline = $mc->prepare("SELECT id, name, level, side, online FROM `users` WHERE `online` < (? - 60) AND `online` > 0 ORDER BY `online` DESC LIMIT ?, 10");
$offset = $limf; // Используем переменную для bind_param
$stmtLastOnline->bind_param("ii", $date, $offset);
$stmtLastOnline->execute();
$lastOnlineResult = $stmtLastOnline->get_result();
$lastOnlinePlayers = $lastOnlineResult ? $lastOnlineResult->fetch_all(MYSQLI_ASSOC) : [];
// --- Конец получения данных ---

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Онлайн - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#41280A"> <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-grad-start: #111;
            --bg-grad-end: #1a1a1a;
            --accent: #f5c15d;
            --accent-2: #ff8452;
            --card-bg: rgba(255,255,255,0.05);
            --glass-bg: rgba(255,255,255,0.08);
            --glass-border: rgba(255,255,255,0.12);
            --text: #fff;
            --muted: #c2c2c2;
            --radius: 16px;
            --secondary-bg: rgba(255,255,255,0.03);
            --item-hover: rgba(255,255,255,0.15);
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            color: var(--text);
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        }
        
        a {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .online-container {
            width: 96%;
            max-width: 600px;
            margin: 15px auto;
        }

        .level-nav-themed {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            margin-bottom: 20px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
        }

        .level-link-themed {
            padding: 6px 14px;
            border-radius: 12px;
            color: var(--text);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--glass-border);
            background: var(--secondary-bg);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .level-link-themed:hover {
            background: var(--accent);
            color: #111;
            transform: translateY(-2px);
        }

        .level-link-active-themed {
            background: var(--accent);
            color: #111;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .list-section-header {
            text-align: center;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 15px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            backdrop-filter: blur(8px);
            position: relative;
        }

        .player-list-container {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            margin-bottom: 25px;
        }

        .player-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 8px;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            transition: all 0.3s;
            background: var(--glass-bg);
            position: relative;
        }

        .player-item:hover {
            transform: translateY(-2px);
            background: var(--item-hover);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .player-number {
            width: 30px;
            text-align: right;
            margin-right: 10px;
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }

        .player-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            transition: transform 0.3s;
        }

        .player-item:hover .player-icon {
            transform: scale(1.1);
        }

        .player-name {
            flex: 1;
            font-size: 15px;
            text-decoration: none;
            color: var(--text);
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .player-name:hover {
            color: var(--accent);
        }

        .player-level {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            color: #111;
            padding: 3px 8px;
            background: var(--accent);
            border-radius: 10px;
            margin-left: 10px;
        }

        .last-online-time {
            font-size: 12px;
            color: var(--muted);
            margin-left: 10px;
            background: var(--secondary-bg);
            padding: 3px 8px;
            border-radius: 10px;
            white-space: nowrap;
        }

        .no-players {
            text-align: center;
            padding: 25px;
            color: var(--muted);
            font-style: italic;
            font-size: 15px;
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px dashed var(--glass-border);
        }

        .pagination-themed {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination-link {
            display: inline-block;
            min-width: 30px;
            padding: 6px 10px;
            text-align: center;
            border-radius: 8px;
            color: var(--text);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .pagination-link.pagination-arrow {
            font-weight: bold;
            padding: 6px 8px;
        }

        .pagination-link:not(.pagination-disabled):not(.pagination-current):hover {
            background: var(--accent);
            color: #111;
            transform: translateY(-2px);
        }

        .pagination-current {
            background: var(--accent);
            color: #111;
            font-weight: bold;
            border-color: var(--accent);
            cursor: default;
        }

        .pagination-disabled {
            color: var(--muted);
            background-color: rgba(255,255,255,0.03);
            border-color: var(--glass-border);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-separator {
            color: var(--muted);
            margin: 0 2px;
        }

        @media (max-width: 768px) {
            .level-nav-themed {
                padding: 8px;
                gap: 6px;
            }

            .level-link-themed {
                padding: 5px 12px;
                font-size: 13px;
            }

            .player-item {
                padding: 8px 10px;
            }

            .player-number {
                width: 25px;
            }

            .player-icon {
                width: 20px;
                height: 20px;
            }

            .player-name {
                font-size: 14px;
            }

            .player-level {
                padding: 2px 6px;
                font-size: 11px;
            }

            .last-online-time {
                padding: 2px 6px;
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            .level-nav-themed {
                padding: 6px;
                gap: 5px;
            }

            .level-link-themed {
                padding: 4px 10px;
                font-size: 12px;
            }

            .player-item {
                padding: 6px 8px;
            }

            .player-number {
                width: 20px;
                font-size: 13px;
            }

            .player-icon {
                width: 18px;
                height: 18px;
                margin-right: 8px;
            }

            .player-name {
                font-size: 13px;
            }

            .player-level {
                padding: 2px 5px;
                font-size: 10px;
            }

            .last-online-time {
                padding: 2px 5px;
                font-size: 10px;
            }

            .pagination-themed {
                flex-wrap: wrap;
                gap: 4px;
            }

            .pagination-link {
                padding: 5px 8px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="online-container">

        <nav class="level-nav-themed">
            <a onclick="showContent('online.php');" class="level-link-themed <?php echo empty($listLevelParam) ? 'level-link-active-themed' : ''; ?>">
                Все
            </a>
            <a onclick="showContent('online.php?listLevel=1');" class="level-link-themed <?php echo $listLevelParam == "1" ? 'level-link-active-themed' : ''; ?>">
                1-5
            </a>
            <a onclick="showContent('online.php?listLevel=2');" class="level-link-themed <?php echo $listLevelParam == "2" ? 'level-link-active-themed' : ''; ?>">
                6-10
            </a>
            <a onclick="showContent('online.php?listLevel=3');" class="level-link-themed <?php echo $listLevelParam == "3" ? 'level-link-active-themed' : ''; ?>">
                10-15
            </a>
            <a onclick="showContent('online.php?listLevel=4');" class="level-link-themed <?php echo $listLevelParam == "4" ? 'level-link-active-themed' : ''; ?>">
                16+
            </a>
        </nav>

        <div class="list-section-header">
            Сейчас в игре
        </div>
        <div class="player-list-container">
            <?php if (empty($onlinePlayers)): ?>
                <div class="no-players">Сейчас никого нет онлайн в выбранной категории.</div>
            <?php else: ?>
                <?php
                $onlineCounter = 0;
                foreach ($onlinePlayers as $player) {
                    $onlineCounter++;
                    // Выбор иконки в зависимости от стороны персонажа (как в friends.php)
                    $iconPath = "/img/icon/";
                    $iconPath .= ($player['side'] == 2 || $player['side'] == 3) ? "icogood.png" : "icoevil.png";
                ?>
                <div class="player-item">
                    <span class="player-number"><?php echo $onlineCounter; ?>.</span>
                    <img class="player-icon" src="<?php echo $iconPath; ?>" alt="Фракция">
                    <a class="player-name" onclick="showContent('/profile/<?php echo $player['id']; ?>')" title="<?php echo htmlspecialchars($player['name']); ?>">
                        <?php echo htmlspecialchars($player['name']); ?>
                    </a>
                    <div class="player-level">
                        <?php echo (int)$player['level']; ?>
                    </div>
                </div>
                <?php } ?>
            <?php endif; ?>
        </div>

        <div class="list-section-header">
            Недавно вышли
        </div>
        <div class="player-list-container">
             <?php if (empty($lastOnlinePlayers)): ?>
                <div class="no-players">Нет данных о недавно вышедших игроках.</div>
            <?php else: ?>
                <?php
                $lastOnlineCounter = $limf; // Начинаем счетчик с учетом смещения пагинации
                foreach ($lastOnlinePlayers as $player) {
                    $lastOnlineCounter++;
                    $iconPath = "/img/icon/";
                    $iconPath .= ($player['side'] == 2 || $player['side'] == 3) ? "icogood.png" : "icoevil.png";
                    $onlineTime = date("H:i:s", $player['online']); // Форматируем время
                ?>
                <div class="player-item">
                    <span class="player-number"><?php echo $lastOnlineCounter; ?>.</span>
                    <img class="player-icon" src="<?php echo $iconPath; ?>" alt="Фракция">
                    <a class="player-name" onclick="showContent('/profile/<?php echo $player['id']; ?>')" title="<?php echo htmlspecialchars($player['name']); ?>">
                        <?php echo htmlspecialchars($player['name']); ?>
                    </a>
                     <div class="player-level">
                        <?php echo (int)$player['level']; ?>
                    </div>
                    <span class="last-online-time"><?php echo $onlineTime; ?></span>
                </div>
                <?php } ?>
            <?php endif; ?>
        </div>

        <?php if ($maxPagesLastOnline > 1): ?>
            <?php echo pagination("online.php", $listPageParam, $maxPagesLastOnline); ?>
        <?php endif; ?>

    </div>

</body>
</html>
