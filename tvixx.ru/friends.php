<?php
require_once ('system/dbc.php');
require_once ('system/func.php');
auth(); // Закрываем от неавторизированных

// Проверяем входящие запросы друзей
$stmt = $mc->prepare("SELECT *, COUNT(0) AS count_friends FROM `friends` WHERE `id_user2` = ? AND `red`='1'");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$drs = $stmt->get_result()->fetch_assoc();

if ($drs['count_friends'] != 0 && !isset($_GET['yes']) && !isset($_GET['no'])) {
    $stmt = $mc->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $stmt->bind_param("i", $drs['id_user']);
    $stmt->execute();
    $use = $stmt->get_result()->fetch_assoc();

    message_yn("{$use['name']} хочет добавить вас в друзья", "/friends.php?yes", "/friends.php?no", "Да", "Нет");
    echo '<script>showContent("/main.php");</script>';
}

if (isset($drs['id_user']) && isset($_GET['yes'])) {
    $stmt = $mc->prepare("SELECT COUNT(0) AS count_friends FROM `friends` WHERE 
        ((`id_user` = ? AND `id_user2` = ?) OR (`id_user` = ? AND `id_user2` = ?)) AND `red`=0");
    $stmt->bind_param("iiii", $user['id'], $drs['id_user'], $drs['id_user'], $user['id']);
    $stmt->execute();
    $provercaFriends = $stmt->get_result()->fetch_assoc();

    if ($provercaFriends['count_friends'] == 0) {
        $stmt = $mc->prepare("UPDATE `friends` SET `red` = '0' WHERE `id_user` = ? AND `id_user2` = ?");
        $stmt->bind_param("ii", $drs['id_user'], $user['id']);
        $stmt->execute();
    } else {
        $stmt = $mc->prepare("DELETE FROM `friends` WHERE 
            ((`id_user` = ? AND `id_user2` = ?) OR (`id_user` = ? AND `id_user2` = ?)) AND `red`='1'");
        $stmt->bind_param("iiii", $drs['id_user'], $user['id'], $user['id'], $drs['id_user']);
        $stmt->execute();
    }
    echo '<script>showContent("friends.php");</script>';
}

if (isset($drs['id_user']) && isset($_GET['no'])) {
    $stmt = $mc->prepare("DELETE FROM `friends` WHERE `id_user` = ? AND `id_user2` = ?");
    $stmt->bind_param("ii", $drs['id_user'], $user['id']);
    $stmt->execute();
    echo '<script>showContent("friends.php");</script>';
}

if (isset($_GET['addfriends'])) {
    $stmt = $mc->prepare("INSERT INTO `friends` (`id_user`, `id_user2`, `red`) VALUES (?, ?, '1')");
    $stmt->bind_param("ii", $user['id'], $_GET['addfriends']);
    $stmt->execute();
    message("Заявка отправлена!");
}

if (isset($_GET['dellfriends'])) {
    $stmt = $mc->prepare("DELETE FROM `friends` WHERE 
        (`id_user` = ? AND `id_user2` = ?) OR (`id_user` = ? AND `id_user2` = ?)");
    $stmt->bind_param("iiii", $user['id'], $_GET['dellfriends'], $_GET['dellfriends'], $user['id']);
    $stmt->execute();
    message("Игрок удалён!");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#41280A">
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

        .friends-container {
            width: 96%;
            max-width: 600px;
            margin: 15px auto;
        }

        .friends-header {
            text-align: center;
            padding: 12px;
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

        .friends-list {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .friend-item {
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

        .friend-item:hover {
            transform: translateY(-2px);
            background: var(--item-hover);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .friend-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            transition: transform 0.3s;
        }

        .friend-item:hover .friend-icon {
            transform: scale(1.1);
        }

        .friend-name {
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

        .friend-name:hover {
            color: var(--accent);
        }

        .friend-level {
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

        .friend-online {
            color: #4ADE80 !important;
        }

        .friend-offline {
            color: var(--muted) !important;
        }

        .no-friends {
            text-align: center;
            padding: 25px;
            color: var(--muted);
            font-style: italic;
            font-size: 15px;
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px dashed var(--glass-border);
        }

        @media (max-width: 768px) {
            .friend-item {
                padding: 8px 10px;
            }

            .friend-icon {
                width: 20px;
                height: 20px;
            }

            .friend-name {
                font-size: 14px;
            }

            .friend-level {
                padding: 2px 6px;
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            .friend-item {
                padding: 6px 8px;
            }

            .friend-icon {
                width: 18px;
                height: 18px;
                margin-right: 8px;
            }

            .friend-name {
                font-size: 13px;
            }

            .friend-level {
                padding: 2px 5px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="friends-container">
        <div class="friends-header">
            Список друзей
        </div>
        
        <div class="friends-list">
            <?php
            $stmt = $mc->prepare("SELECT * FROM `friends` WHERE (`id_user` = ? OR `id_user2` = ?) AND `red`=0");
            $stmt->bind_param("ii", $user['id'], $user['id']);
            $stmt->execute();
            $friendsAll1 = $stmt->get_result();
            $hasFriends = false;

            while ($friendsAll = $friendsAll1->fetch_assoc()) {
                $hasFriends = true;
                $friendsId = ($friendsAll['id_user'] != $user['id']) ? $friendsAll['id_user'] : $friendsAll['id_user2'];

                $stmt = $mc->prepare("SELECT `name`, `level`, `side`, `online` FROM `users` WHERE `id` = ?");
                $stmt->bind_param("i", $friendsId);
                $stmt->execute();
                $friendsName = $stmt->get_result()->fetch_assoc();

                $icon = ($friendsName['side'] == 0 || $friendsName['side'] == 1) 
                    ? '<img class="friend-icon" src="/img/icon/icoevil.png" alt="">' 
                    : '<img class="friend-icon" src="/img/icon/icogood.png" alt="">';

                $onlineClass = ($friendsName['online'] > time() - 60) ? "friend-online" : "friend-offline";
                ?>
                <div class="friend-item">
                    <?= $icon ?>
                    <a class="friend-name <?= $onlineClass ?>" onclick="showContent('/profile/<?= $friendsId ?>')" title="<?= htmlspecialchars($friendsName['name']) ?>">
                        <?= htmlspecialchars($friendsName['name']) ?>
                    </a>
                    <div class="friend-level">
                        <?= (int)$friendsName['level'] ?>
                    </div>
                </div>
            <?php } 
            
            if (!$hasFriends): ?>
                <div class="no-friends">
                    У вас пока нет друзей
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    $footval = 'friends';
    include 'system/foot/foot.php';
    ?>
</body>
</html>