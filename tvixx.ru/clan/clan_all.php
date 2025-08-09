<?php
require_once '../system/func.php';

//pagination(ссылка "index.php/*?", номер страницы:>0, максимум страниц:>0)
function pagination($href, $strlist, $maxstr) {
    $strend = 0;
    $output = "";

    if ($strlist > 4) {
        $output .= "<a onclick=showContent('/" . $href . "&list=1');><span class='pagination-link'>1</span></a> .. ";
    } elseif ($strlist == 4) {
        $output .= "<a onclick=showContent('/" . $href . "&list=1');><span class='pagination-link'>1</span></a>";
    }

    for ($i = -2; $i <= 3; $i++) {
        $strnm = $i + $strlist;
        if ($strnm + 1 == $maxstr) {
            $strend = 1;
        }
        if ($strnm > 0) {
            if ($i == 0) {
                $output .= "<span class='pagination-current'>" . $strlist . "</span>";
            } elseif ($i == 3) {
                if ($strlist < $maxstr) {
                    if ($strend == 0) {
                        $output .= ".. <a onclick=showContent('/" . $href . "&list=" . $maxstr . "');><span class='pagination-link'>" . $maxstr . "</span></a>";
                    } else {
                        $output .= "<a onclick=showContent('/" . $href . "&list=" . $maxstr . "');><span class='pagination-link'>" . $maxstr . "</span></a>";
                    }
                }
            } else {
                if ($strnm < $maxstr) {
                    $output .= "<a onclick=showContent('/" . $href . "&list=" . $strnm . "');><span class='pagination-link'>" . $strnm . "</span></a>";
                }
            }
        }
    }
    return $output;
}

//если получен айди клана
if (isset($_GET['see_clan']) && $_GET['see_clan'] != 0 && $_GET['see_clan'] != $user['id_clan']) {
    //получмим параметры клана
    if ($clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $_GET['see_clan'] . "'")->fetch_array(MYSQLI_ASSOC)) {
        if (isset($_GET['list'])) {
            $limf = ($_GET['list'] - 1) * 10;
        } else {
            $limf = 0;
        }
        //получим игроков на странице
        if ($clan_users = $mc->query("SELECT * FROM `users` WHERE `id_clan`='" . $_GET['see_clan'] . "' ORDER BY `reit` DESC LIMIT " . $limf . ",10")->fetch_all(MYSQLI_ASSOC)) {
            //количество всех участников
            $clan_usersall_count = $mc->query("SELECT * FROM `users` WHERE `id_clan`='" . $_GET['see_clan'] . "'")->num_rows;
            //сторона клана по главе
            $clan_side = $mc->query("SELECT `side` FROM `users` WHERE `id_clan`='" . $_GET['see_clan'] . "' ORDER BY `des` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
            //количество онлайн
            $clan_users_online = $mc->query("SELECT * FROM `users` WHERE `online`>'" . (time() - 60) . "'&&`id_clan`='" . $_GET['see_clan'] . "'")->num_rows;
            //ico clan
            $clan_ico = $clan_side['side'] == 0 || $clan_side['side'] == 1 ? '<i class="fas fa-skull" style="color: var(--team1-color);"></i>' : '<i class="fas fa-shield-alt" style="color: var(--team2-color);"></i>';
            //позиция клана в рейтинге
            $clan_pos = $mc->query("SELECT * FROM `clan` WHERE `reit`>='" . $clan['reit'] . "' ORDER BY `reit` DESC")->num_rows;
            ?>
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <title>Клан <?= htmlspecialchars($clan['name']); ?> - Mobitva v1.0</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="theme-color" content="#111">
                <meta name="author" content="Kalashnikov"/>
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
                --table-header: rgba(255,255,255,0.1);
                --table-row-alt: rgba(255,255,255,0.02);
                --table-row-hover: rgba(255,255,255,0.07);
                --team1-color: #e74c3c;
                --team2-color: #3498db;
                --danger-color: #ff4c4c;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: 100%;
                color: var(--text);
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
            }

            .clan_container {
                max-width: 800px;
                margin: 15px auto;
                padding: 0 15px;
                animation: fadeIn 0.5s ease-out;
            }

            .clan_header {
                position: relative;
                padding: 20px;
                margin-bottom: 20px;
                text-align: center;
                background: var(--glass-bg);
                border: 1px solid var(--glass-border);
                border-radius: var(--radius);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(8px);
            }

            .clan_name {
                font-size: 22px;
                font-weight: 600;
                color: var(--accent);
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 15px;
                letter-spacing: 0.5px;
            }

            .clan_name i {
                font-size: 18px;
                transition: transform 0.3s;
            }

            .clan_name:hover i {
                transform: scale(1.1);
            }

            .clan_stats {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin: 15px 0 5px;
            }

            .clan_stat {
                background: var(--card-bg);
                padding: 10px 20px;
                border-radius: var(--radius);
                color: var(--muted);
                font-size: 15px;
                border: 1px solid var(--glass-border);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(8px);
                transition: all 0.3s;
                color: #000;
            }

            .clan_stat:hover {
                transform: translateY(-2px);
                background: var(--item-hover);
            }

            .clan_stat .online {
                color: var(--accent-2);
                font-weight: 600;
            }

            .clan_stat_value {
                color: #000;
                font-weight: 600;
                margin-left: 6px;
            }

            .clan_stat_clickable {
                cursor: pointer;
            }

            .clan_stat_clickable:hover {
                color: #000;
            }

            .clan_stat_clickable:hover .clan_stat_value {
                color: var(--accent);
            }

            .clan_stat i {
                margin-right: 6px;
                color: var(--accent);
            }

            .clan_buttons {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
                margin: 20px 0;
            }

            .clan_button {
                background: var(--glass-bg);
                color: var(--text);
                border: 1px solid var(--glass-border);
                padding: 12px 20px;
                border-radius: var(--radius);
                cursor: pointer;
                transition: all 0.3s;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 14px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(8px);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .clan_button i {
                margin-right: 8px;
            }

            .clan_button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
                background: var(--item-hover);
                color: var(--accent);
            }

            .member_list {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }

            .member_card {
                display: flex;
                align-items: center;
                padding: 15px;
                background: var(--card-bg);
                border: 1px solid var(--glass-border);
                border-radius: var(--radius);
                cursor: pointer;
                transition: all 0.3s;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(8px);
            }

            .member_card:hover {
                background: var(--item-hover);
                transform: translateX(5px);
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            }

            .member_number {
                color: var(--accent-2);
                font-weight: 600;
                min-width: 35px;
                text-align: center;
                font-size: 15px;
            }

            .member_info {
                flex: 1;
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 0 15px;
            }

            .member_role {
                display: flex;
                gap: 8px;
                align-items: center;
            }

            .member_role img {
                width: 20px;
                height: 20px;
                object-fit: contain;
                filter: brightness(1.2);
                transition: transform 0.3s;
            }

            .member_card:hover .member_role img {
                transform: scale(1.15);
            }

            .member_name {
                font-size: 15px;
                color: var(--text);
                font-weight: 500;
                transition: color 0.3s;
            }

            .member_name.online {
                color: var(--accent-2);
                font-weight: 600;
            }

            .member_card:hover .member_name {
                color: var(--accent);
            }

            .member_rating {
                background: var(--secondary-bg);
                color: var(--muted);
                padding: 6px 12px;
                border-radius: 20px;
                font-weight: 600;
                font-size: 14px;
                min-width: 45px;
                text-align: center;
                border: 1px solid var(--glass-border);
                transition: all 0.3s;
            }

            .member_card:hover .member_rating {
                background: var(--glass-bg);
                color: var(--accent);
                transform: scale(1.05);
            }

            .clan_pagination {
                margin-top: 25px;
                text-align: center;
                padding: 15px;
                background: var(--glass-bg);
                border: 1px solid var(--glass-border);
                border-radius: var(--radius);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(8px);
            }

            .pagination-link {
                margin: 0 6px;
                font-size: 15px;
                color: var(--muted);
                transition: all 0.3s;
                display: inline-block;
                padding: 3px 8px;
            }

            .pagination-link:hover {
                color: var(--accent);
                transform: translateY(-2px);
            }

            .pagination-current {
                font-size: 15px;
                color: var(--accent);
                font-weight: 600;
                margin: 0 6px;
                display: inline-block;
                padding: 3px 8px;
                background: var(--glass-bg);
                border-radius: 4px;
            }

            .clan_exit_button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin: 25px auto 10px;
                padding: 12px 24px;
                background: var(--danger-color);
                color: var(--text);
                border: none;
                border-radius: var(--radius);
                font-size: 15px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s;
                cursor: pointer;
                min-width: 160px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .clan_exit_button i {
                margin-right: 8px;
            }

            .clan_exit_button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
                background: #ff3333;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @media (max-width: 480px) {
                .clan_container {
                    padding: 0 10px;
                    margin: 10px auto;
                }
                
                .clan_header {
                    padding: 15px;
                }
                
                .clan_name {
                    font-size: 18px;
                    gap: 10px;
                }
                
                .clan_name i {
                    font-size: 16px;
                }
                
                .clan_stats {
                    flex-direction: column;
                    gap: 10px;
                    align-items: center;
                }
                
                .clan_stat {
                    width: 100%;
                    text-align: center;
                    padding: 8px 15px;
                    font-size: 14px;
                }
                
                .clan_buttons {
                    gap: 10px;
                }
                
                .clan_button {
                    padding: 10px;
                    font-size: 13px;
                }
                
                .clan_exit_button {
                    padding: 10px 20px;
                    font-size: 14px;
                    margin-top: 20px;
                }
                
                .member_card {
                    padding: 12px 10px;
                }
                
                .member_number {
                    min-width: 30px;
                    font-size: 14px;
                }
                
                .member_info {
                    padding: 0 10px;
                }
                
                .member_name {
                    font-size: 14px;
                }
                
                .member_rating {
                    font-size: 13px;
                    padding: 4px 10px;
                    min-width: 40px;
                }
                
                .member_role img {
                    width: 18px;
                    height: 18px;
                }
            }
            </style>
            </head>
            <body>

            <div class="clan_container">
                <div class="clan_header">
                    <div class="clan_name">
                        <?= $clan_ico; ?>
                        <?= htmlspecialchars($clan['name']); ?>
                        <?= $clan_ico; ?>
                    </div>
                    <div class="clan_stats">
                        <div class="clan_stat">
                            <i class="fas fa-users"></i> Онлайн: <span class="online"><?=$clan_users_online;?></span>/<span class="clan_stat_value"><?=$clan_usersall_count;?></span>
                        </div>
                        <div class="clan_stat clan_stat_clickable" onclick="showContent('/top.php?clan')">
                            <i class="fas fa-trophy"></i> Рейтинг: <span class="clan_stat_value"><?=$clan['reit'];?></span>(<span class="clan_stat_value"><?=$clan_pos;?></span>)
                        </div>
                    </div>
                </div>

                <?php if ($user['id_clan'] == $_GET['see_clan']): ?>
                <div class="clan_buttons">
                    <button class="clan_button" onclick="showContent('/chatclan.php')">
                        <i class="fas fa-comments"></i> Чат клана
                    </button>
                    <button class="clan_button" onclick="showContent('/huntb/grab/index.php')">
                        <i class="fas fa-coins"></i> Грабежи
                    </button>
                </div>
                <?php endif; ?>

                <div class="member_list">
                    <?php for ($i = 0; $i < count($clan_users); $i++): ?>
                        <div class="member_card" onclick="showContent('/profile/<?= $clan_users[$i]['id']; ?>')">
                            <div class="member_number"><?= $i + $limf + 1; ?></div>
                            <div class="member_info">
                                <div class="member_role">
                                    <?php if ($clan_users[$i]['des'] == 3): ?>
                                        <img class="role_icon" src="/img/img23.png" alt="Глава">
                                    <?php elseif ($clan_users[$i]['des'] == 2): ?>
                                        <?= $clan_ico; ?>
                                    <?php endif; ?>
                                    <?php if ($clan_users[$i]['des'] > 0): ?>
                                        <img class="role_icon" src="/images/super/2su.png" alt="Чемпион">
                                    <?php endif; ?>
                                </div>
                                <div class="member_name <?= $clan_users[$i]['online'] > (time() - 60) ? 'online' : ''; ?>">
                                    <?= htmlspecialchars($clan_users[$i]['name']); ?>
                                </div>
                            </div>
                            <div class="member_rating"><?= $clan_users[$i]['reit']; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>

                <?php if (ceil($clan_usersall_count / 10) > 1): ?>
                    <div class="clan_pagination">
                        <?= pagination("clan/clan_all.php?see_clan=" . $_GET['see_clan'], isset($_GET['list']) ? $_GET['list'] : 1, ceil($clan_usersall_count / 10)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($user['id_clan'] == $_GET['see_clan']): ?>
                <div style="text-align: center;">
                    <a class="clan_exit_button" onclick="showContent('/clan/clan_all.php?exit&see_clan=<?=$user['id_clan'];?>')">
                        <i class="fas fa-sign-out-alt"></i> Покинуть клан
                    </a>
                </div>
                <?php endif; ?>
            </div>
            </body>
            </html>
            <?php
        } else {
            ?>
            <center><a style="text-decoration: underline;" onclick="showContent('main.php')">--Нет такого клана--</a></center>
            <?php
        }
    } else {
        ?>
        <center><a style="text-decoration: underline;" onclick="showContent('main.php')">--Нет такого клана--</a></center>
        <?php
    }
    $footval = "clannone";
} elseif (isset($_GET['see_clan']) && $user['id_clan'] == $_GET['see_clan'] && $clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC)) {
    //получмим параметры клана
    if (isset($_GET['list'])) {
        $limf = ($_GET['list'] - 1) * 10;
    } else {
        $limf = 0;
    }
    //получим игроков на странице
    if ($clan_users = $mc->query("SELECT * FROM `users` WHERE `id_clan`='" . $user['id_clan'] . "' ORDER BY `reit` DESC LIMIT " . $limf . ",10")->fetch_all(MYSQLI_ASSOC)) {
        //количество всех участников
        $clan_usersall_count = $mc->query("SELECT * FROM `users` WHERE `id_clan`='" . $user['id_clan'] . "'")->num_rows;
        //сторона клана по главе
        $clan_side = $mc->query("SELECT `side` FROM `users` WHERE `id_clan`='" . $_GET['see_clan'] . "' ORDER BY `des` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
        //количество онлайн
        $clan_users_online = $mc->query("SELECT * FROM `users` WHERE `online`>'" . (time() - 60) . "'&&`id_clan`='" . $user['id_clan'] . "'")->num_rows;
        //ico clan
        $clan_ico = $clan_side['side'] == 0 || $clan_side['side'] == 1 ? '<i class="fas fa-skull" style="color: var(--team1-color);"></i>' : '<i class="fas fa-shield-alt" style="color: var(--team2-color);"></i>';
        //позиция клана в рейтинге
        $clan_pos = $mc->query("SELECT * FROM `clan` WHERE `reit`>='" . $clan['reit'] . "' ORDER BY `reit` DESC")->num_rows;
        //если нет главы выдать первому десятнику
        if($countGlav = $mc->query("SELECT COUNT(*) FROM `users` WHERE (`id_clan` = '".$user['id_clan']."' AND `des` = '3') ")->fetch_array(MYSQLI_ASSOC)){
        	if($countGlav['COUNT(*)'] <= 0 ){
        	  $des1 = $mc->query("SELECT * FROM `users` WHERE (`id_clan` = '".$user['id_clan']."' AND `des` = '2')")->fetch_array(MYSQLI_ASSOC);
              $mc->query("UPDATE `users` SET `des` = '3' WHERE `id` = '".$des1['id']."'");
          }
        }
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Клан <?= htmlspecialchars($clan['name']); ?> - Mobitva v1.0</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="theme-color" content="#111">
            <meta name="author" content="Kalashnikov"/>
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
            --table-header: rgba(255,255,255,0.1);
            --table-row-alt: rgba(255,255,255,0.02);
            --table-row-hover: rgba(255,255,255,0.07);
            --team1-color: #e74c3c;
            --team2-color: #3498db;
            --danger-color: #ff4c4c;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        }

        .clan_container {
            max-width: 800px;
            margin: 15px auto;
            padding: 0 15px;
            animation: fadeIn 0.5s ease-out;
        }

        .clan_header {
            position: relative;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .clan_name {
            font-size: 22px;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            letter-spacing: 0.5px;
        }

        .clan_name i {
            font-size: 18px;
            transition: transform 0.3s;
        }

        .clan_name:hover i {
            transform: scale(1.1);
        }

        .clan_stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 15px 0 5px;
        }

        .clan_stat {
            background: var(--card-bg);
            padding: 10px 20px;
            border-radius: var(--radius);
            color: var(--muted);
            font-size: 15px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
            transition: all 0.3s;
            color: #000;
        }

        .clan_stat:hover {
            transform: translateY(-2px);
            background: var(--item-hover);
        }

        .clan_stat .online {
            color: var(--accent-2);
            font-weight: 600;
        }

        .clan_stat_value {
            color: #000;
            font-weight: 600;
            margin-left: 6px;
        }

        .clan_stat_clickable {
            cursor: pointer;
        }

        .clan_stat_clickable:hover {
            color: #000;
        }

        .clan_stat_clickable:hover .clan_stat_value {
            color: var(--accent);
        }

        .clan_stat i {
            margin-right: 6px;
            color: var(--accent);
        }

        .clan_buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .clan_button {
            background: var(--glass-bg);
            color: var(--text);
            border: 1px solid var(--glass-border);
            padding: 12px 20px;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clan_button i {
            margin-right: 8px;
        }

        .clan_button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            background: var(--item-hover);
            color: var(--accent);
        }

        .member_list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .member_card {
            display: flex;
            align-items: center;
            padding: 15px;
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
        }

        .member_card:hover {
            background: var(--item-hover);
            transform: translateX(5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .member_number {
            color: var(--accent-2);
            font-weight: 600;
            min-width: 35px;
            text-align: center;
            font-size: 15px;
        }

        .member_info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 15px;
        }

        .member_role {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .member_role img {
            width: 20px;
            height: 20px;
            object-fit: contain;
            filter: brightness(1.2);
            transition: transform 0.3s;
        }

        .member_card:hover .member_role img {
            transform: scale(1.15);
        }

        .member_name {
            font-size: 15px;
            color: var(--text);
            font-weight: 500;
            transition: color 0.3s;
        }

        .member_name.online {
            color: var(--accent-2);
            font-weight: 600;
        }

        .member_card:hover .member_name {
            color: var(--accent);
        }

        .member_rating {
            background: var(--secondary-bg);
            color: var(--muted);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            min-width: 45px;
            text-align: center;
            border: 1px solid var(--glass-border);
            transition: all 0.3s;
        }

        .member_card:hover .member_rating {
            background: var(--glass-bg);
            color: var(--accent);
            transform: scale(1.05);
        }

        .clan_pagination {
            margin-top: 25px;
            text-align: center;
            padding: 15px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
        }

        .pagination-link {
            margin: 0 6px;
            font-size: 15px;
            color: var(--muted);
            transition: all 0.3s;
            display: inline-block;
            padding: 3px 8px;
        }

        .pagination-link:hover {
            color: var(--accent);
            transform: translateY(-2px);
        }

        .pagination-current {
            font-size: 15px;
            color: var(--accent);
            font-weight: 600;
            margin: 0 6px;
            display: inline-block;
            padding: 3px 8px;
            background: var(--glass-bg);
            border-radius: 4px;
        }

        .clan_exit_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 25px auto 10px;
            padding: 12px 24px;
            background: var(--danger-color);
            color: var(--text);
            border: none;
            border-radius: var(--radius);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            min-width: 160px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .clan_exit_button i {
            margin-right: 8px;
        }

        .clan_exit_button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            background: #ff3333;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .clan_container {
                padding: 0 10px;
                margin: 10px auto;
            }
            
            .clan_header {
                padding: 15px;
            }
            
            .clan_name {
                font-size: 18px;
                gap: 10px;
            }
            
            .clan_name i {
                font-size: 16px;
            }
            
            .clan_stats {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
            
            .clan_stat {
                width: 100%;
                text-align: center;
                padding: 8px 15px;
                font-size: 14px;
            }
            
            .clan_buttons {
                gap: 10px;
            }
            
            .clan_button {
                padding: 10px;
                font-size: 13px;
            }
            
            .clan_exit_button {
                padding: 10px 20px;
                font-size: 14px;
                margin-top: 20px;
            }
            
            .member_card {
                padding: 12px 10px;
            }
            
            .member_number {
                min-width: 30px;
                font-size: 14px;
            }
            
            .member_info {
                padding: 0 10px;
            }
            
            .member_name {
                font-size: 14px;
            }
            
            .member_rating {
                font-size: 13px;
                padding: 4px 10px;
                min-width: 40px;
            }
            
            .member_role img {
                width: 18px;
                height: 18px;
            }
        }
        </style>
        </head>
        <body>

        <div class="clan_container">
            <div class="clan_header">
                <div class="clan_name">
                    <?= $clan_ico; ?>
                    <?= htmlspecialchars($clan['name']); ?>
                    <?= $clan_ico; ?>
                </div>
                <div class="clan_stats">
                    <div class="clan_stat">
                        <i class="fas fa-users"></i> Онлайн: <span class="online"><?=$clan_users_online;?></span>/<span class="clan_stat_value"><?=$clan_usersall_count;?></span>
                    </div>
                    <div class="clan_stat clan_stat_clickable" onclick="showContent('/top.php?clan')">
                        <i class="fas fa-trophy"></i> Рейтинг: <span class="clan_stat_value"><?=$clan['reit'];?></span>(<span class="clan_stat_value"><?=$clan_pos;?></span>)
                    </div>
                </div>
            </div>

            <?php if ($user['id_clan'] == $_GET['see_clan']): ?>
            <div class="clan_buttons">
                <button class="clan_button" onclick="showContent('/chatclan.php')">
                    <i class="fas fa-comments"></i> Чат клана
                </button>
                <button class="clan_button" onclick="showContent('/huntb/grab/index.php')">
                    <i class="fas fa-coins"></i> Грабежи
                </button>
            </div>
            <?php endif; ?>

            <div class="member_list">
                <?php for ($i = 0; $i < count($clan_users); $i++): ?>
                    <div class="member_card" onclick="showContent('/profile/<?= $clan_users[$i]['id']; ?>')">
                        <div class="member_number"><?= $i + $limf + 1; ?></div>
                        <div class="member_info">
                            <div class="member_role">
                                <?php if ($clan_users[$i]['des'] == 3): ?>
                                    <img class="role_icon" src="/img/img23.png" alt="Глава">
                                <?php elseif ($clan_users[$i]['des'] == 2): ?>
                                    <?= $clan_ico; ?>
                                <?php endif; ?>
                                <?php if ($clan_users[$i]['des'] > 0): ?>
                                    <img class="role_icon" src="/images/super/2su.png" alt="Чемпион">
                                <?php endif; ?>
                            </div>
                            <div class="member_name <?= $clan_users[$i]['online'] > (time() - 60) ? 'online' : ''; ?>">
                                <?= htmlspecialchars($clan_users[$i]['name']); ?>
                            </div>
                        </div>
                        <div class="member_rating"><?= $clan_users[$i]['reit']; ?></div>
                    </div>
                <?php endfor; ?>
            </div>

            <?php if (ceil($clan_usersall_count / 10) > 1): ?>
                <div class="clan_pagination">
                    <?= pagination("clan/clan_all.php?see_clan=" . $user['id_clan'], isset($_GET['list']) ? $_GET['list'] : 1, ceil($clan_usersall_count / 10)); ?>
                </div>
            <?php endif; ?>

            <div style="text-align: center;">
                <a class="clan_exit_button" onclick="showContent('/clan/clan_all.php?exit&see_clan=<?=$user['id_clan'];?>')">
                    <i class="fas fa-sign-out-alt"></i> Покинуть клан
                </a>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
    $footval = "clan";
//если не в клане и лвл старше 14
} elseif ($user['level'] > 14) {
    ?>
    <table class="table_block2">
        <tr>
            <td class="block01" style="width: 2%"></td>
            <td class="block02" style="width: 96%"></td>
            <td class="block03" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block04"></td>
            <td class="block05" style="text-align: center; font-size:15px;">
                Вы можете дождаться и принять приглашения<br> другого клана либо создать свой.
                <br><br>
                Стоимость создания клана: 100
                <img class="ico_head_all" src="/images/icons/plata.png">
               <br><br>
               <div style="text-align:left;">&nbsp;Название клана:</div>
                <form id='formid'>
                    <input onfocus="this.style.backgroundColor = '#f0e0c0';" onblur="this.style.backgroundColor = '#e0d0a0'" type="text" name="name" style="outline: none;margin-top: 10px;height: 45px;width: 96%;background-color: #e0d0a0;border: 1px solid #8B4513;border-radius: 8px;padding: 5px 10px;font-size: 16px;color: #5D4037;box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);" value=""  maxlength="100" placeholder="Введите название клана..."/>
                    <button type='button' class="button_alt_01" style="margin-top: 15px; width:90%;padding: 12px;font-size: 16px;text-transform: uppercase;font-weight: bold;letter-spacing: 1px;box-shadow: 0 4px 6px rgba(0,0,0,0.1);border-radius: 8px;transition: all 0.3s;" onclick="showContent('/clan/create.php?create&' + $('#formid').serialize())">Создать</button>
                    <br>
                </form>
            </td>
            <td class="block06"></td>
        </tr>
        <tr>
            <td class="block07"></td>
            <td class="block08"></td>
            <td class="block09"></td>
        </tr>
    </table>
    <?php
    $footval = "clannone";
//или лвл ниже 15
} elseif ($user['level'] < 15) {
    ?>
    <table class="table_block2">
        <tr>
            <td class="block01" style="width: 2%"></td>
            <td class="block02" style="width: 96%"></td>
            <td class="block03" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block04"></td>
            <td class="block05" style="text-align:center;">
                Дождитесь приглашения в клан или с <b>15</b> уровня сможете создать свой клан!
            </td>
            <td class="block06"></td>
        </tr>
        <tr>
            <td class="block07"></td>
            <td class="block08"></td>
            <td class="block09"></td>
        </tr>
    </table>
    <?php
    $footval = "clannone";
} else {
    ?>
    <center><a style="text-decoration: underline;" onclick="showContent('main.php')">--Нет такого клана--</a></center>
        <?php
        $footval = "clannone";
    }
    if (isset($_GET['exit'])) {
        if (empty($_GET['exit'])) {
            message_yn(
                    "Покинуть клан",
                    "clan/clan_all.php?exit=yes&see_clan=" . $user['id_clan'],
                    "clan/clan_all.php?see_clan=" . $user['id_clan'],
                    "Да",
                    "Нет"
                    );
        }
    }

    if (isset($_GET['exit']) && $_GET['exit'] == 'yes' && isset($_GET['see_clan']) == $user['id_clan']) {
        $mc->query("UPDATE `users` SET `id_clan`='0' WHERE `id`='" . $user['id'] . "'");
        ?>
    <script>
        showContent('/main.php');
    </script>
    <?php
}

require_once '../system/foot/foot.php';
