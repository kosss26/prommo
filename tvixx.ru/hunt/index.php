<?php
require_once ('../system/func.php');
auth(); // Закроем от неавторизированных
requestModer(); // Закроем для тех у кого есть запрос на модератора

// Проверяем, что герой не в бою
if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script><?php
    exit(0);
}

// Проверяем результаты боя
if ($mc->query("SELECT * FROM `resultbattle` WHERE `id_user`='" . $user['id'] . "' ORDER BY `id` DESC LIMIT 1")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/result.php");</script><?php
    exit(0);
}

if (isset($_GET['help']) && $_GET['start'] == 'ok') {
    $fight = $mc->query("SELECT * FROM `battle` WHERE `id`='" . $_GET['help'] . "'")->fetch_array(MYSQLI_ASSOC);
    $mc->query("INSERT INTO `battle`(`user_id`, `enemy_id`, `user_hp`, `enemy_hp`, `user_uron`, `enemy_uron`, `helpid`, `pocinul`) VALUES ('" . $user['id'] . "','" . $fight['enemy_id'] . "','" . $user['health'] . "','" . $fight['enemy_hp'] . "',0,0,'" . $fight['id'] . "',0)");
    ?><script>/*nextshowcontemt*/showContent("/");</script><?php
    exit(0);
}

// Проверка на регистрацию в турнирах
if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "'")->num_rows > 0) {
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Что бы начать охоту отмените дуэли !','" . time() . "','msg')");
    ?><script>/*nextshowcontemt*/showContent("/huntb/index.php");</script><?php
    exit(0);
}

// Добавляем обратно удаленный код для квестов
$quests_count_res = $mc->query("SELECT * FROM `quests_count` WHERE (`id_quests`,`count`) IN (SELECT `id_quests`,`count` FROM `quests_users` WHERE `id_user` ='" . $user['id'] . "')");
$quests_counts = [];
if ($quests_count_res->num_rows > 0) {
    $quests_counts = $quests_count_res->fetch_all(MYSQLI_ASSOC);
}

// Проверка локации
$loc = $user['location'];
if ($loc == 0 || $loc == 23 && $user['access'] < 2) {
    $mc->query("UPDATE `users` SET `location`='4' WHERE `id`='" . $user["id"] . "'");
    ?><script>NewFuckOff();</script><?php
    exit(0);
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
            --hunt-button: #4ADE80;
            --hunt-button-hover: #22c55e;
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

        .hunt_container {
            width: 96%;
            max-width: 600px;
            margin: 15px auto;
        }

        .hunt_header {
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

        .hunt_subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-top: 5px;
            font-weight: normal;
        }

        .hunt_list {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            margin-bottom: 20px;
        }

        .hunt_monster {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 8px;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            transition: all 0.3s;
            background: var(--glass-bg);
            position: relative;
            cursor: pointer;
        }

        .hunt_monster:hover {
            transform: translateY(-2px);
            background: var(--item-hover);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .monster_icon {
            width: 50px;
            height: 50px;
            margin-right: 12px;
            transition: transform 0.3s;
            object-fit: contain;
        }

        .hunt_monster:hover .monster_icon {
            transform: scale(1.1);
        }

        .monster_name {
            flex: 1;
            font-size: 15px;
            color: var(--text);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .monster_name:hover {
            color: var(--accent);
        }

        .monster_level {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            color: #111;
            padding: 3px 8px;
            background: var(--accent);
            border-radius: 10px;
        }

        .monster_quest_badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-2);
            color: #111;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 10px;
            margin-left: 5px;
        }

        .admin_edit {
            font-size: 14px;
            color: var(--muted);
            margin: 5px 0 0 72px;
        }

        .admin_edit a {
            color: var(--text);
            text-decoration: none;
            transition: color 0.3s;
        }

        .admin_edit a:hover {
            color: var(--accent);
        }

        .admin_effects {
            font-size: 12px;
            color: var(--muted);
            font-style: italic;
            margin: 5px 0 0 72px;
        }

        .hunt_divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--glass-border), transparent);
            margin: 10px 0;
            border: none;
        }

        .hunt_button {
            display: block;
            width: 100%;
            max-width: 300px;
            padding: 10px 20px;
            border-radius: var(--radius);
            background: var(--hunt-button);
            color: #111;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            margin: 0 auto;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hunt_button:hover {
            background: var(--hunt-button-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .hunt_empty {
            text-align: center;
            padding: 25px;
            color: var(--muted);
            font-style: italic;
            font-size: 15px;
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px dashed var(--glass-border);
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .hunt_container {
                margin: 10px auto;
            }

            .hunt_header {
                padding: 8px 12px;
                font-size: 15px;
            }

            .hunt_subtitle {
                font-size: 13px;
            }

            .hunt_monster {
                padding: 8px 10px;
            }

            .monster_icon {
                width: 45px;
                height: 45px;
            }

            .monster_name {
                font-size: 14px;
            }

            .monster_level {
                padding: 2px 6px;
                font-size: 11px;
            }

            .hunt_button {
                padding: 8px 15px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .hunt_monster {
                padding: 6px 8px;
            }

            .monster_icon {
                width: 40px;
                height: 40px;
                margin-right: 8px;
            }

            .monster_name {
                font-size: 13px;
            }

            .monster_level {
                padding: 2px 5px;
                font-size: 10px;
            }

            .monster_quest_badge {
                width: 18px;
                height: 18px;
                font-size: 9px;
            }

            .admin_edit {
                font-size: 12px;
                margin-left: 48px;
            }

            .admin_effects {
                font-size: 11px;
                margin-left: 48px;
            }

            .hunt_button {
                padding: 8px 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="hunt_container">
        <div class="hunt_header">
            Охота на монстров
            <div class="hunt_subtitle">Выберите противника для сражения</div>
        </div>
        
        <div class="hunt_list">
            <?php
            $mobvlocke = 0;
            if ($mob1 = $mc->query("SELECT * FROM `hunt` WHERE `id` IN (SELECT `id_hunt` FROM `hunt_equip` WHERE `id_loc` = '$loc') ORDER BY `level` ASC")) {
                $mobidarr = [];
                while ($mob = $mob1->fetch_array(MYSQLI_ASSOC)) {
                    if (!$mc->query("SELECT * FROM `userHuntNotActiveMob` WHERE `id_user` = '" . $user['id'] . "' AND `id_mob` = '" . $mob['id'] . "'")->num_rows) {
                        if ($mob['quests'] == 1) {
                            for ($i = 0; $i < count($quests_counts); $i++) {
                                if (in_array($mob['id'], json_decode(urldecode($quests_counts[$i]['mob_battle'])))) {
                                    $mobidarr[] = $mob['id'];
                                    ?>
                                    <div class="hunt_monster arrowHunt<?= $mob['id']; ?>" onclick="showContent('/hunt/attack/<?php echo $mobvlocke; ?>')">
                                        <img class="monster_icon" src="/img/icon/mob/<?php echo $mob['iconid']; ?>.png">
                                        <div class="monster_name" title="<?php echo htmlspecialchars($mob['name']); ?>">
                                            <?php echo htmlspecialchars($mob['name']); ?>
                                            <div class="monster_quest_badge">
                                                <i class="fas fa-scroll" title="Монстр для задания"></i>
                                            </div>
                                            <span class="monster_level"><?php echo $mob['level']; ?></span>
                                        </div>
                                    </div>
                                    <?php if ($user['access'] > 2): ?>
                                        <div class="admin_edit">
                                            <a onclick="showContent('/admin/hunt.php?mob=edit&id=<?php echo $mob['id']; ?>')">
                                                Изменить моба id[<?= $mob['id']; ?>] (Админ)
                                            </a>
                                        </div>
                                        <div class="admin_effects">
                                            <?php echo $mob['nameeffects']; ?>
                                        </div>
                                    <?php endif; ?>
                                    <hr class="hunt_divider"/>
                                    <?php
                                    $mobvlocke++;
                                    break;
                                }
                            }
                        } else {
                            $mobidarr[] = $mob['id'];
                            ?>
                            <div class="hunt_monster arrowHunt<?= $mob['id']; ?>" onclick="showContent('/hunt/attack/<?php echo $mobvlocke; ?>')">
                                <img class="monster_icon" src="/img/icon/mob/<?php echo $mob['iconid']; ?>.png">
                                <div class="monster_name" title="<?php echo htmlspecialchars($mob['name']); ?>">
                                    <?php echo htmlspecialchars($mob['name']); ?>
                                    <span class="monster_level"><?php echo $mob['level']; ?></span>
                                </div>
                            </div>
                            <?php if ($user['access'] > 2): ?>
                                <div class="admin_edit">
                                    <a onclick="showContent('/admin/hunt.php?mob=edit&id=<?php echo $mob['id']; ?>')">
                                        Изменить моба id[<?= $mob['id']; ?>] (Админ)
                                    </a>
                                </div>
                                <div class="admin_effects">
                                    <?php echo $mob['nameeffects']; ?>
                                </div>
                            <?php endif; ?>
                            <hr class="hunt_divider"/>
                            <?php
                            $mobvlocke++;
                        }
                    }
                }
                $mc->query("UPDATE `users` SET `huntList` = '" . json_encode($mobidarr) . "' WHERE `users`.`id` = '" . $user['id'] . "'");
            } else {
                ?>
                <div class="hunt_empty">
                    Нет доступных противников в этой локации
                </div>
                <?php 
            } 
            ?>
        </div>
        
        <div class="hunt_button" onclick="showContent('/hunt/tec.php')">
            Текущие бои
        </div>
    </div>

    <?php
    $footval = 'huntindex';
    require_once ('../system/foot/foot.php');
    ?>
</body>
</html>