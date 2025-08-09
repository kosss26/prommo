<?php
require_once ('system/func.php');
require_once ('system/dbc.php');

$footval = "chattav";
auth(); // Закроем от неавторизированных
requestModer(); // Закроем для тех у кого есть запрос на модератора

$tavcount = 10;
$date = time();
if (isset($_GET['chat'])) {
    $chat = (int) $_GET['chat'];
} else {
    $chat = 0;
}
$result = $mc->query("SELECT * FROM `users` WHERE `onlinechat`>($date-15) AND `room`='" . $chat . "' ORDER BY `access` DESC ");
$num_rows = $result->num_rows;
$page = ceil($num_rows / $tavcount);
if (!empty($_GET['list'])) {
    if ((int) $_GET['list'] >= 0 && (int) $_GET['list'] <= ceil($num_rows / $tavcount)) {
        $listcount = (int) $_GET['list'] - 1;
        $str = $listcount * $tavcount;
    } else {
        $listcount = 0;
        $str = 0;
    }
} else {
    $str = 0;
    $listcount = 0;
}
$onlineid = $str;
$online1 = $mc->query("SELECT * FROM `users` WHERE `onlinechat`>($date-15) AND `room`='" . $chat . "' ORDER BY `access` DESC LIMIT " . $str . ",10");
$innerHtml = "";
while ($online = $online1->fetch_array(MYSQLI_ASSOC)) {
    $access = (int) $online['access'];
    $star = "";
    $onlineid++;
    if ($online['side'] == 2 || $online['side'] == 3) {
        $icon = "<img height=17 src=/img/icon/icogood.png width=17>";
    } else {
        $icon = "<img height=17 src=/img/icon/icoevil.png width=17>";
    }
    if ($access == 1) {
        $star = "<img height='15' src='/img/icon/star.png' width='15' alt=''>";
    } else if ($access == 2) {
        $star = "<img height='15' src='/img/icon/star2.png' width='15' alt=''>";
    } else if ($access > 2) {
        $star = "<img height='15' src='/img/icon/star3.png' width='15' alt=''>";
    }
    $innerHtml = $innerHtml . '<div style="margin-left: 5px;float:left;">' . $onlineid . ' ' . $icon . $star . ' </div><div style="float:right;margin-right: 5px;">' . $online['level'] . '</div><div style="text-align: center;"><a style="font-size:17px; text-decoration:underline;" onclick=\'showContent("/profile/' . $online['id'] . '")\'><font>' . $online['name'] . '</font></a></div><br>';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Таверна - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111111">
    <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            --panel-shadow: 0 8px 24px rgba(0,0,0,0.55);
        }

        *,*::before,*::after {
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

        .main-wrapper {
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: clamp(8px, 2vw, 18px);
        }

        .content-container {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 12px;
            backdrop-filter: blur(10px);
            margin-bottom: 18px;
            box-shadow: var(--panel-shadow);
        }

        h2 {
            text-align: center;
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 24px;
        }

        .divider {
            height: 1px;
            width: 100%;
            border: none;
            background: linear-gradient(to right, transparent, var(--glass-border), transparent);
            margin: 14px 0;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: calc(var(--radius) - 4px);
            margin-bottom: 10px;
            transition: all .3s ease;
        }

        .chat-item:hover {
            background: var(--item-hover);
            transform: translateY(-2px);
        }

        .chat-number {
            width: 30px;
            text-align: center;
            font-weight: 500;
            color: var(--muted);
        }

        .chat-icons {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .chat-icons img {
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.5));
        }

        .chat-name {
            flex: 1;
            text-align: center;
        }

        .chat-name a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
        }

        .chat-name a:hover {
            color: var(--accent);
        }

        .chat-level {
            background: rgba(0,0,0,0.2);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--accent);
        }

        .chat-tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-radius: var(--radius);
            overflow: hidden;
            width: 100%;
        }

        .chat-tab {
            flex: 1;
            background: var(--secondary-bg);
            color: var(--text);
            border: 1px solid var(--glass-border);
            padding: 12px 0;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }

        .chat-tab:hover {
            background: var(--item-hover);
        }

        .chat-tab.active {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #111;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: calc(var(--radius) - 8px);
            background: var(--secondary-bg);
            border: 1px solid var(--glass-border);
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .pagination-item:hover:not(.pagination-current):not(.pagination-disabled) {
            background: var(--item-hover);
            color: var(--accent);
        }

        .pagination-current {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #111;
            font-weight: 700;
        }

        .pagination-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .room-title {
            text-align: center;
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 700;
            font-size: clamp(18px, 4vw, 24px);
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            position: relative;
            padding-bottom: 10px;
        }

        .room-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--accent), transparent);
        }

        .user-counter {
            display: inline-block;
            background: rgba(0,0,0,0.2);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 14px;
            margin-left: 5px;
            color: var(--accent);
        }

        @media (max-width: 480px) {
            .main-wrapper {
                padding: 10px;
            }

            .chat-item {
                padding: 8px;
            }

            .chat-level {
                padding: 2px 6px;
                font-size: 12px;
            }

            .pagination-item {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="content-container">
        <div class="room-title">
            <?php 
            $room_name = "Чат";
            switch($chat) {
                case 0: $room_name = "Общий зал"; break;
                case 1: $room_name = "Задворки"; break;
                case 2: $room_name = "Клан"; break;
                case 3: $room_name = "МД"; break;
                case 4: $room_name = "АДМИН"; break;
                case 5: $room_name = "Логи"; break;
                default: $room_name = $chat > 5 ? "Клан" : "Чат";
            }
            echo $room_name . " <span class='user-counter'>" . $num_rows . "</span>";
            ?>
        </div>

        <div class="chat-tabs">
            <div class="chat-tab <?= $chat == 0 ? 'active' : '' ?>" onclick="showContent('/chattav.php?chat=0')">Общий</div>
            <div class="chat-tab <?= $chat == 1 ? 'active' : '' ?>" onclick="showContent('/chattav.php?chat=1')">Задворки</div>
            <?php if(isset($user) && $user['id_clan'] > 0): ?>
            <div class="chat-tab <?= $chat == 2 ? 'active' : '' ?>" onclick="showContent('/chattav.php?chat=2')">Клан</div>
            <?php endif; ?>
            <?php if(isset($user) && $user['access'] > 0): ?>
            <div class="chat-tab <?= $chat == 3 ? 'active' : '' ?>" onclick="showContent('/chattav.php?chat=3')">МД</div>
            <?php endif; ?>
        </div>

        <div class="chat-users">
            <?php
            $online_users = $mc->query("SELECT * FROM `users` WHERE `onlinechat`>($date-15) AND `room`='" . $chat . "' ORDER BY `access` DESC LIMIT " . $str . ",10");
            $item_count = $str + 1;
            
            while ($online = $online_users->fetch_array(MYSQLI_ASSOC)) {
                $access = (int) $online['access'];
                
                $star = "";
                switch($access) {
                    case 1: $star = "<img height='15' src='/img/icon/star.png' width='15' alt=''>"; break;
                    case 2: $star = "<img height='15' src='/img/icon/star2.png' width='15' alt=''>"; break;
                    case 3:
                    case 4:
                    case 5: $star = "<img height='15' src='/img/icon/star3.png' width='15' alt=''>"; break;
                }
                
                $icon = ($online['side'] == 2 || $online['side'] == 3) 
                    ? "<img height=17 src=/img/icon/icogood.png width=17>"
                    : "<img height=17 src=/img/icon/icoevil.png width=17>";
                ?>
                
                <div class="chat-item">
                    <div class="chat-number"><?= $item_count ?></div>
                    <div class="chat-icons">
                        <?= $icon ?><?= $star ?>
                    </div>
                    <div class="chat-name">
                        <a onclick="showContent('/profile/<?= $online['id'] ?>')">
                            <?= htmlspecialchars($online['name']) ?>
                        </a>
                    </div>
                    <div class="chat-level"><?= $online['level'] ?></div>
                </div>
            <?php 
                $item_count++;
            } 
            ?>
            
            <?php if ($online_users->num_rows == 0): ?>
                <div style="text-align: center; padding: 20px; color: var(--muted);">
                    <i class="fas fa-ghost" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <p>Пока никого нет. Вы первый!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($page > 1): ?>
            <div class="pagination">
                <?php
                // Ссылка "Назад"
                if ($listcount + 1 > 1) {
                    $prevPage = $listcount;
                    echo "<a onclick=\"showContent('chattav.php?chat={$chat}&list={$prevPage}');\" class=\"pagination-item\"><i class=\"fas fa-chevron-left\"></i></a>";
                } else {
                    echo "<span class=\"pagination-item pagination-disabled\"><i class=\"fas fa-chevron-left\"></i></span>";
                }

                // Страницы
                $startPage = max(1, $listcount - 1);
                $endPage = min($page, $listcount + 3);
                
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $listcount + 1) {
                        echo "<span class=\"pagination-item pagination-current\">{$i}</span>";
                    } else {
                        echo "<a onclick=\"showContent('chattav.php?chat={$chat}&list={$i}');\" class=\"pagination-item\">{$i}</a>";
                    }
                }

                // Многоточие и последняя страница
                if ($endPage < $page) {
                    if ($endPage < $page - 1) {
                        echo "<span class=\"pagination-item pagination-disabled\">...</span>";
                    }
                    echo "<a onclick=\"showContent('chattav.php?chat={$chat}&list={$page}');\" class=\"pagination-item\">{$page}</a>";
                }

                // Ссылка "Вперед"
                if ($listcount + 1 < $page) {
                    $nextPage = $listcount + 2;
                    echo "<a onclick=\"showContent('chattav.php?chat={$chat}&list={$nextPage}');\" class=\"pagination-item\"><i class=\"fas fa-chevron-right\"></i></a>";
                } else {
                    echo "<span class=\"pagination-item pagination-disabled\"><i class=\"fas fa-chevron-right\"></i></span>";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ('system/foot/foot.php'); ?>
</body>
</html>