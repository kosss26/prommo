<?php
require_once 'system/func.php';
$footval = "top";
require_once ('system/foot/foot.php');

$contentgen = "";
if (isset($_GET['vse'])) {
    $contentgen = 'vse';
} elseif (isset($_GET['mylvl'])) {
    $contentgen = 'mylvl';
} elseif (isset($_GET['clan'])) {
    $contentgen = 'clan';
}

// Если параметр не задан, по умолчанию показываем раздел "Все"
if (empty($contentgen)) {
    $contentgen = 'vse';
}

$strlist = isset($_GET['list']) ? (int)$_GET['list'] : 1;
$countstr = 10; // Количество записей на странице
$strlistnum = ($strlist - 1) * $countstr; // Смещение для SQL-запроса
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

        .player_list {
            width: 96%;
            max-width: 600px;
            margin: 15px auto;
        }

        .level_nav {
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

        .level_link {
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

        .level_link:hover {
            background: var(--accent);
            color: #111;
            transform: translateY(-2px);
        }

        .level_link.active {
            background: var(--accent);
            color: #111;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .section_title {
            text-align: center;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 15px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            backdrop-filter: blur(8px);
            position: relative;
        }

        .top_list {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .player_item {
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

        .player_item:hover {
            transform: translateY(-2px);
            background: var(--item-hover);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .player_number {
            width: 30px;
            text-align: right;
            margin-right: 10px;
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }

        .player_icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            transition: transform 0.3s;
        }

        .player_item:hover .player_icon {
            transform: scale(1.1);
        }

        .player_name {
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

        .player_name:hover {
            color: var(--accent);
        }

        .player_level {
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

        .player_online {
            font-size: 12px;
            color: #fff;
            margin-left: 10px;
            background-color: #2e7d32;
            padding: 3px 8px;
            border-radius: 10px;
            white-space: nowrap;
            display: inline-block;
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
            .level_nav {
                padding: 8px;
                gap: 6px;
            }

            .level_link {
                padding: 5px 12px;
                font-size: 13px;
            }

            .player_item {
                padding: 8px 10px;
            }

            .player_number {
                width: 25px;
            }

            .player_icon {
                width: 20px;
                height: 20px;
            }

            .player_name {
                font-size: 14px;
            }

            .player_level {
                padding: 2px 6px;
                font-size: 11px;
            }

            .player_online {
                padding: 2px 6px;
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            .level_nav {
                padding: 6px;
                gap: 5px;
            }

            .level_link {
                padding: 4px 10px;
                font-size: 12px;
            }

            .player_item {
                padding: 6px 8px;
            }

            .player_number {
                width: 20px;
                font-size: 13px;
            }

            .player_icon {
                width: 18px;
                height: 18px;
                margin-right: 8px;
            }

            .player_name {
                font-size: 13px;
            }

            .player_level {
                padding: 2px 5px;
                font-size: 10px;
            }

            .player_online {
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
    <div class="player_list">
        <div class="level_nav">
            <a onclick="showContent('top.php?vse')" class="level_link <?= $contentgen === 'vse' ? 'active' : '' ?>">
                Все
            </a>
            <a onclick="showContent('top.php?mylvl=<?= $user['level'] ?>')" class="level_link <?= $contentgen === 'mylvl' ? 'active' : '' ?>">
                <?= $user['level'] ?> Уровень
            </a>
            <a onclick="showContent('top.php?clan')" class="level_link <?= $contentgen === 'clan' ? 'active' : '' ?>">
                Кланы
            </a>
        </div>

        <div class="top_list">
            <?php
            $maxstr = 1;
            $num = 0;

            if ($contentgen === 'vse') {
                $totalUsers = $mc->query("SELECT COUNT(*) as total FROM `users` WHERE `access` IN (0, 1)")->fetch_assoc()['total'];
                $maxstr = ceil($totalUsers / $countstr);
                echo "<div class='player_item' style='justify-content: center; font-weight: 600;'>Всего игроков: $totalUsers</div>";

                $stmt = $mc->prepare("SELECT id, name, level, side, online FROM `users` WHERE `access` IN (0, 1) ORDER BY `level` DESC, `exp` DESC LIMIT ?, ?");
                $stmt->bind_param("ii", $strlistnum, $countstr);
                $stmt->execute();
                $user = $stmt->get_result();
            } elseif ($contentgen === 'mylvl') {
                $stmt = $mc->prepare("SELECT COUNT(*) as total FROM `users` WHERE `level` = ?");
                $stmt->bind_param("i", $user['level']);
                $stmt->execute();
                $totalUsers = $stmt->get_result()->fetch_assoc()['total'];
                $maxstr = ceil($totalUsers / $countstr);

                $stmt = $mc->prepare("SELECT id, name, level, side, online FROM `users` WHERE `level` = ? ORDER BY `exp` DESC LIMIT ?, ?");
                $stmt->bind_param("iii", $user['level'], $strlistnum, $countstr);
                $stmt->execute();
                $user = $stmt->get_result();
            } elseif ($contentgen === 'clan') {
                $totalClans = $mc->query("SELECT COUNT(*) as total FROM `clan`")->fetch_assoc()['total'];
                $maxstr = ceil($totalClans / $countstr);

                $stmt = $mc->prepare("SELECT id, name, reit FROM `clan` ORDER BY `reit` DESC LIMIT ?, ?");
                $stmt->bind_param("ii", $strlistnum, $countstr);
                $stmt->execute();
                $clan = $stmt->get_result();
            }

            if ($contentgen === 'vse' || $contentgen === 'mylvl') {
                while ($result = $user->fetch_assoc()) {
                    $num++;
                    $icon = ($result['side'] == 2 || $result['side'] == 3) 
                        ? '<img class="player_icon" src="/img/icon/icogood.png" alt="Свет">' 
                        : '<img class="player_icon" src="/img/icon/icoevil.png" alt="Тьма">';
                    $onlineStatus = ($result['online'] > time() - 60) ? '<span class="player_online">Онлайн</span>' : '';
                    ?>
                    <div class="player_item">
                        <span class="player_number"><?= $num + $strlistnum ?>.</span>
                        <?= $icon ?>
                        <a onclick="showContent('/profile/<?= $result['id'] ?>')" class="player_name" title="<?= htmlspecialchars($result['name']) ?>">
                            <?= htmlspecialchars($result['name']) ?>
                        </a>
                        <span class="player_level"><?= $result['level'] ?></span>
                        <?= $onlineStatus ?>
                    </div>
                    <?php
                }
            } elseif ($contentgen === 'clan') {
                while ($resul = $clan->fetch_assoc()) {
                    $num++;
                    $clan_sides = $mc->query("SELECT side, COUNT(*) as count FROM `users` WHERE `id_clan` = '" . $resul['id'] . "' GROUP BY side ORDER BY count DESC LIMIT 1")->fetch_assoc();
                    $side = isset($clan_sides['side']) ? $clan_sides['side'] : 0;
                    $icon = ($side == 2 || $side == 3) 
                        ? '<img class="player_icon" src="/img/icon/icogood.png" alt="Свет">' 
                        : '<img class="player_icon" src="/img/icon/icoevil.png" alt="Тьма">';
                    ?>
                    <div class="player_item">
                        <span class="player_number"><?= $num + $strlistnum ?>.</span>
                        <?= $icon ?>
                        <a onclick="showContent('/clan/clan_all.php?see_clan=<?= $resul['id'] ?>')" class="player_name" title="<?= htmlspecialchars($resul['name']) ?>">
                            <?= htmlspecialchars($resul['name']) ?>
                        </a>
                        <span class="player_level"><?= $resul['reit'] ?></span>
                    </div>
                    <?php
                }
                $mc->query("UPDATE `clan` SET `num`='$num' WHERE `id`='" . $user['id_clan'] . "'");
            }
            ?>
        </div>

        <?php if ($maxstr > 1): ?>
            <div class="pagination-themed">
                <?= pagination("top.php?" . ($contentgen ? "$contentgen&" : ""), $strlist, $maxstr) ?>
            </div>
        <?php endif; ?>
    </div>

    <?php
    function pagination($href, $currentPage, $maxPages, $visibleLinks = 2) {
        if ($maxPages <= 1) return '';

        $output = '<nav class="pagination-themed">';

        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $output .= "<a onclick=\"showContent('{$href}list={$prevPage}');\" class=\"pagination-link pagination-arrow\">&laquo;</a>";
        } else {
            $output .= "<span class=\"pagination-link pagination-disabled pagination-arrow\">&laquo;</span>";
        }

        if ($currentPage > $visibleLinks + 1) {
            $output .= "<a onclick=\"showContent('{$href}list=1');\" class=\"pagination-link\">1</a>";
            if ($currentPage > $visibleLinks + 2) $output .= '<span class="pagination-separator">...</span>';
        }

        $start = max(1, $currentPage - $visibleLinks);
        $end = min($maxPages, $currentPage + $visibleLinks);

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $output .= "<span class=\"pagination-link pagination-current\">{$i}</span>";
            } else {
                $output .= "<a onclick=\"showContent('{$href}list={$i}');\" class=\"pagination-link\">{$i}</a>";
            }
        }

        if ($currentPage < $maxPages - $visibleLinks) {
            if ($currentPage < $maxPages - $visibleLinks - 1) $output .= '<span class="pagination-separator">...</span>';
            $output .= "<a onclick=\"showContent('{$href}list={$maxPages}');\" class=\"pagination-link\">{$maxPages}</a>";
        }

        if ($currentPage < $maxPages) {
            $nextPage = $currentPage + 1;
            $output .= "<a onclick=\"showContent('{$href}list={$nextPage}');\" class=\"pagination-link pagination-arrow\">&raquo;</a>";
        } else {
            $output .= "<span class=\"pagination-link pagination-disabled pagination-arrow\">&raquo;</span>";
        }

        $output .= '</nav>';
        return $output;
    }
    ?>
</body>
</html>