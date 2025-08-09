<?php

require_once ('../../system/func.php');
require_once ('../../system/dbc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/date_functions.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

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
    --team1-color: #e74c3c;
    --team2-color: #3498db;
}

body {
    background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end)) !important;
    color: var(--text) !important;
    font-family: 'Inter', sans-serif !important;
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

.zem_container {
    max-width: 800px;
    margin: 15px auto;
    padding: 20px;
    position: relative;
    background: var(--card-bg) !important;
    border-radius: var(--radius) !important;
    border: 1px solid var(--glass-border) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
    backdrop-filter: blur(5px);
    color: var(--text) !important;
    font-family: 'Inter', sans-serif;
    animation: fadeIn 0.5s ease-out;
}

.zem_header {
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    color: var(--accent);
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    background: var(--glass-bg);
    position: relative;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
}

.zem_header::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
}

.zem_info {
    background: var(--glass-bg);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
    transition: all 0.3s;
}

.zem_info:hover {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    transform: translateY(-3px);
}

.zem_stat {
    display: flex;
    align-items: center;
    margin: 12px 0;
    color: var(--text);
    font-size: 15px;
    font-weight: 500;
    padding: 10px;
    border-bottom: 1px solid var(--glass-border);
    transition: all 0.3s;
}

.zem_stat:last-child {
    border-bottom: none;
}

.zem_stat:hover {
    background: var(--item-hover);
    transform: translateX(5px);
    border-radius: 8px;
}

.zem_stat b {
    margin-left: 8px;
    color: var(--accent);
    font-weight: 600;
}

.zem_stat img {
    margin: 0 5px;
    filter: brightness(1.2);
    transition: transform 0.3s;
    width: 20px;
    height: 20px;
    vertical-align: middle;
}

.zem_stat:hover img {
    transform: scale(1.15);
}

.zem_owner {
    font-size: 16px;
    margin: 15px 0;
    padding: 15px;
    background: var(--secondary-bg);
    border-radius: var(--radius);
    border: 1px solid var(--glass-border);
    transition: all 0.3s;
    text-align: center;
}

.zem_owner:hover {
    background: var(--item-hover);
    transform: translateY(-2px);
}

.zem_owner a {
    color: var(--accent);
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.zem_owner a:hover {
    color: var(--accent-2);
    text-decoration: underline;
}

.zem_battles {
    margin: 20px 0;
    padding: 15px;
    background: var(--secondary-bg);
    border-radius: var(--radius);
    border: 1px solid var(--glass-border);
    transition: all 0.3s;
}

.zem_battles:hover {
    background: var(--item-hover);
    transform: translateY(-2px);
}

.zem_battles b {
    color: var(--accent);
    display: block;
    margin-bottom: 10px;
    font-size: 16px;
}

.zem_description {
    color: var(--text);
    line-height: 1.7;
    padding: 20px;
    background: var(--glass-bg);
    border-radius: var(--radius);
    border: 1px solid var(--glass-border);
    margin-top: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
    font-size: 15px;
}

.zem_description p {
    margin: 10px 0;
}

.zem_button {
    background: linear-gradient(to bottom, var(--accent), var(--accent-2));
    color: #111;
    padding: 12px 25px;
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 15px auto;
    width: fit-content;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    position: relative;
    overflow: hidden;
}

.zem_button:after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.zem_button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

.zem_button:hover:after {
    left: 100%;
}

.zem_button:active {
    transform: translateY(1px);
}

.zem_button.disabled {
    opacity: 0.7;
    cursor: not-allowed;
    background: linear-gradient(to bottom, #555, #333);
    color: var(--muted);
}

.clan_link {
    color: var(--accent);
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
}

.clan_link:hover {
    color: var(--accent-2);
    text-decoration: underline;
}

.member_card {
    display: flex !important;
    align-items: center !important;
    padding: 15px !important;
    background: var(--glass-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: var(--radius) !important;
    cursor: pointer;
    transition: all 0.3s !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
    backdrop-filter: blur(8px);
    margin-bottom: 10px !important;
    width: auto !important;
}

.member_card:hover {
    background: var(--item-hover) !important;
    transform: translateX(8px) !important;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3) !important;
}

.member_number {
    color: var(--accent-2);
    font-weight: 600;
    min-width: 35px;
    text-align: center;
    font-size: 15px;
}

.member_info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 15px;
}

.clan_separator {
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
    margin: 20px 0;
    border: none;
}

.clan_title {
    font-size: 18px;
    font-weight: bold;
    color: var(--accent);
    text-align: center;
    margin: 15px 0;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

/* Анимация появления */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
    .zem_container {
        padding: 15px;
        margin: 10px;
    }
    
    .zem_header {
        font-size: 18px;
        padding: 12px;
    }
    
    .zem_info, .zem_description {
        padding: 15px;
    }
    
    .zem_stat {
        font-size: 14px;
    }
    
    .zem_button {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .member_card {
        padding: 12px 10px;
    }
}
</style>

<?php  


$loca =  $user['location'];
$location = $mc->query("SELECT * FROM `location` WHERE `id`='$loca'")->fetch_array(MYSQLI_ASSOC);



$vosemp = mktime(19, 50, 0, date("m"),date("d"), date("Y"));
$shestp = mktime(17, 50, 0, date("m"),date("d"), date("Y"));

$timezone  = 0; //(GMT +3:00) Moscow never sleeps Яяяяяяяяяяяяяяяяя люблю тебя маскваааааааа. 
$tectime =  time() + 3600*($timezone+date("I"));

$tectimes =  $tectime; // типа 17:50 ща
//$tectimes = time() + 3600*($timezone+date("I"));
$nextZahvat = formatNextBattleDate($location['nextZahvat']);

// Если сегодня бой, устанавливаем время боя
if($location['nextZahvat'] ==  $shestp || $location['nextZahvat'] ==  $vosemp) {
    $timezahvat = $shestp;
    $mc->query("UPDATE `location` SET `nextZahvat` = '".$timezahvat."' WHERE `id` = ".$user['location']."");
}

///Если клан не воюет в 8, то нельзя подглядывать
$ifnot8 = $mc->query("SELECT * FROM `location` WHERE (`idClan` = '". $user['id_clan'] ."' OR `idNextClan` = '". $user['id_clan'] ."') AND `nextZahvat`= '".$vosemp."' AND `id` = '". $user['location'] ."'")->fetch_array(MYSQLI_ASSOC);

// Добавляем проверку времени для регистрации
$current_time = time() + 3600*($timezone+date("I"));
$registration_start = mktime(17, 50, 0, date("m"), date("d"), date("Y")); // 17:50
$battle_start = mktime(18, 00, 0, date("m"), date("d"), date("Y")); // 18:00
$battle_end = mktime(19, 00, 0, date("m"), date("d"), date("Y")); // 19:00

$registration_second_start = mktime(19, 50, 0, date("m"), date("d"), date("Y")); // 19:50
$battle_second_start = mktime(20, 00, 0, date("m"), date("d"), date("Y")); // 20:00
$battle_second_end = mktime(21, 00, 0, date("m"), date("d"), date("Y")); // 21:00

$can_register_first = ($tectime >= $registration_start && $tectime < $battle_start);
$can_register_second = ($tectime >= $registration_second_start && $tectime < $battle_second_start);

// Определяем, идет ли сейчас боевой период
$is_battle_time = ($tectime >= $registration_start && $tectime < $battle_end) || 
                  ($tectime >= $registration_second_start && $tectime < $battle_second_end);

$notfight8 = false;
if($tectimes >= $vosemp)
{
    if(!is_array($ifnot8) || empty($ifnot8))
    {
        $notfight8 = true;
    }
}

///если бой не сегодня или не в боевой период, показываем информацию о земле
if($location['nextZahvat'] > $tectimes || !$is_battle_time || $user['id_clan'] == 0 || $location['nextZahvat'] == 0 || $notfight8)
{
    $dhdClan = $location['dhdClan'];
    $dhdUser = $location['dhdUser'] * $user['level'];

    //idClan
    $ClanName = "-";
    if($location['idClan'] != 0) {
        $Clan = $mc->query("SELECT `name` FROM `clan` WHERE `id` = '" . $mc->real_escape_string($location['idClan']) . "'")->fetch_array(MYSQLI_ASSOC);
        if ($Clan) {
            $clanId = (int)$location['idClan'];
            $clanName = htmlspecialchars($Clan['name']);
            $ClanName = "<a onclick=\"showContent('/clan/clan_all.php?see_clan={$clanId}')\" class=\"clan_link\">{$clanName}</a>";
        }
    }

    $MyClanFight = $mc->query("SELECT `Name` FROM `location` WHERE `idNextClan` = ". $user['id_clan'] ." AND `idClan` != ". $user['id_clan'] ."")->fetch_all(MYSQLI_ASSOC);
    $MyClanOborona = $mc->query("SELECT `Name` FROM `location` WHERE `idClan` = ". $user['id_clan'] ." AND `nextZahvat` <= ". $vosemp ." AND `idNextClan` != 0 AND `idNextClan` != ". $user['id_clan'] ." ")->fetch_all(MYSQLI_ASSOC);
?>
<div class="zem_container">
    <div class="zem_header">
        <i class="fas fa-map-marked-alt"></i> <?php echo $location['Name'];?>
    </div>

    <div class="zem_info">
        <div class="zem_stat">
            <i class="fas fa-calendar-alt"></i> Следующее сражение: <b><?= $nextZahvat; ?></b>
        </div>

        <div class="zem_owner">
            <i class="fas fa-flag"></i> Владелец: <?= $ClanName; ?>
        </div>

        <div class="zem_stat">
            <i class="fas fa-coins"></i> Доход казны: 
            <img class="ico_head_all" src="/images/icons/zoloto.png"><?= money($dhdClan, 'zoloto'); ?>
            <img class="ico_head_all" src="/images/icons/serebro.png"><?= money($dhdClan, 'serebro'); ?>
            <img class="ico_head_all" src="/images/icons/med.png"><?= money($dhdClan, 'med'); ?>
        </div>

        <div class="zem_stat">
            <i class="fas fa-user-plus"></i> Личный доход:
            <img class="ico_head_all" src="/images/icons/zoloto.png"><?= money($dhdUser, 'zoloto'); ?>
            <img class="ico_head_all" src="/images/icons/serebro.png"><?= money($dhdUser, 'serebro'); ?>
            <img class="ico_head_all" src="/images/icons/med.png"><?= money($dhdUser, 'med'); ?>
        </div>

        <?php if(is_array($MyClanFight) && !empty($MyClanFight) || is_array($MyClanOborona) && !empty($MyClanOborona)) { ?>
            <div class="zem_battles">
                <b><i class="fas fa-crosshairs"></i> Ваш клан сражается за следующие земли:</b>
                <?php if(is_array($MyClanFight) && !empty($MyClanFight) && $user['id_clan'] != 0) { ?>
                    <div><b><i class="fas fa-shield-alt"></i> Нападение:</b></div>
                    <?php foreach($MyClanFight as $fight) { ?>
                        <div><?= $fight["Name"] ?></div>
                    <?php } ?>
                <?php } ?>

                <?php if(is_array($MyClanOborona) && !empty($MyClanOborona) && $user['id_clan'] != 0) { ?>
                    <div><b><i class="fas fa-shield-alt"></i> Оборона:</b></div>
                    <?php foreach($MyClanOborona as $oborona) { ?>
                        <div><?= $oborona["Name"] ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <div class="zem_description">
        <div class="zem_header" style="font-size: 16px; padding: 10px;">
            <i class="fas fa-info-circle"></i> Захват локаций
        </div>
        <p>
            Местность может быть захвачена кланом.
            Для определения самого достойного претендента в
            18:00 МСК проводится соревнование отборных
            бойцов клана. Клан победителя сможет сразиться с
            владельцами земли в 20:00. В случае неявки одной
            из сторон победа останется за явившимися, в случае неявки
            обоих - за владельцами.
            Захваченная земля неприкосновенна три дня с момента захвата.
        </p>
    </div>
</div>
<?php
}else{
////Время отжимать клан пришло

    // Проверяем, действительно ли сейчас боевое время
    if(!$is_battle_time) {
        // Если не боевое время, перенаправляем на страницу земли
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if($location['nextZahvat'] < mktime(18, 00, 0, date("m"),date("d"), date("Y")))
    {
        //первый этап
        $ucharr = $mc->query("SELECT * FROM `huntb_list` WHERE ". " `type`='7' AND `location` = '".$user['location']."' ORDER BY `time_start` ASC ")->fetch_all(MYSQLI_ASSOC);
        ?>
        <div class="zem_container">
            <div class="zem_header">
                <i class="fas fa-map-marked-alt"></i> <?= $location['Name'];?>
            </div>

            <?php if (count($ucharr)) { ?>
                <div class="zem_info">
                    <?php for ($i = 0; $i < count($ucharr); $i++) { 
                        $usrunc = $mc->query("SELECT * FROM `users` WHERE `id` >= '" . $ucharr[$i]['user_id'] . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC); ?>
                        
                        <div class="member_card" onclick="showContent('/profile/<?= $usrunc['id']; ?>')">
                            <div class="member_number"><?= $i + 1; ?></div>
                            <div class="member_info">
                                <!-- флаг -->
                                <?= $usrunc['side'] == 0 || $usrunc['side'] == 1 ? '<img src="/img/icon/icoevil.png" width="24" height="24" alt="Нормасцы" style="vertical-align: middle;">' : '<img src="/img/icon/icogood.png" width="24" height="24" alt="Шейваны" style="vertical-align: middle;">'; ?>
                                <!-- ник -->
                                <div class="<?= $usrunc['name'] == $user['name'] ? 'online' : ''; ?>" style="color: var(--text); font-weight: <?= $usrunc['name'] == $user['name'] ? 'bold' : 'normal'; ?>;">
                                    <?= htmlspecialchars($usrunc['name']); ?> [<?= $usrunc['level']; ?>]
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="zem_info">
                    <div class="zem_stat" style="justify-content: center;">
                        <i class="fas fa-exclamation-circle"></i> Нет зарегистрированных бойцов
                    </div>
                </div>
            <?php } ?>

            <div style="text-align: center;">
                <?php if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "' && `type`='7'")->num_rows>0) { ?>
                    <div class="zem_button" onclick="showContent('/huntb/zem/remove.php')">
                        <i class="fas fa-times-circle"></i> Отказаться
                    </div>
                <?php } else { 
                    if ($can_register_first) { ?>
                        <div class="zem_button" onclick="showContent('/huntb/zem/add.php?add=1800')">
                            <i class="fas fa-plus-circle"></i> Зарегистрироваться
                        </div>
                    <?php } else { ?>
                        <div class="zem_button disabled">
                            <i class="fas fa-clock"></i> Регистрация с 17:50 до 18:00
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
        <?php
    }else{
        //второй этап

        $ucharrUname = $mc->query("SELECT `name` FROM `clan` WHERE `id` = ". $user['id_clan'] ."")->fetch_array(MYSQLI_ASSOC);
        $ucharrAloca = $mc->query("SELECT * FROM `location` WHERE `id` = ".$user['location']." ORDER BY `nextZahvat` DESC")->fetch_array(MYSQLI_ASSOC);
        $vragclanid = "0";
        if($ucharrAloca['idClan'] != $user['id_clan'])
        {
            $vragclanid = $ucharrAloca['idClan'];
        }else{
            $vragclanid = $ucharrAloca['idNextClan'];
        }
        $ucharrAname = $mc->query("SELECT `name` FROM `clan` WHERE `id` = ". $vragclanid ."")->fetch_array(MYSQLI_ASSOC);

        $ucharrU = $mc->query("SELECT *,(SELECT `id_clan` FROM `users` WHERE `users`.`id` = `user_id`) as `clan` FROM `huntb_list` WHERE `type`=8  AND `location` = ".$user['location']." HAVING `clan` = ". $user['id_clan'] ."")->fetch_all(MYSQLI_ASSOC);

        $ucharrA = $mc->query("SELECT *,(SELECT `id_clan` FROM `users` WHERE `users`.`id` = `user_id`) as `clan` FROM `huntb_list` WHERE `type`=8 AND `location` = ".$user['location']." HAVING `clan` = ". $vragclanid ." ")->fetch_all(MYSQLI_ASSOC);

       ?>
       <div class="zem_container">
            <div class="zem_header">
                <i class="fas fa-map-marked-alt"></i> <?= $location['Name'];?>
            </div>
            
            <div class="clan_title">
                <i class="fas fa-users"></i> <?= htmlspecialchars($ucharrUname['name']); ?>
            </div>
            
            <?php if (is_array($ucharrU) && count($ucharrU) > 0) { ?>
                <div class="zem_info">
                    <?php for ($i = 0; $i < count($ucharrU); $i++) { 
                        $usrunc = $mc->query("SELECT * FROM `users` WHERE `id` >= '" . $ucharrU[$i]['user_id'] . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC); ?>
                        
                        <div class="member_card" onclick="showContent('/profile/<?= $usrunc['id']; ?>')">
                            <div class="member_number"><?= $i + 1; ?></div>
                            <div class="member_info">
                                <!-- флаг -->
                                <?= $usrunc['side'] == 0 || $usrunc['side'] == 1 ? '<img src="/img/icon/icoevil.png" width="24" height="24" alt="Нормасцы" style="vertical-align: middle;">' : '<img src="/img/icon/icogood.png" width="24" height="24" alt="Шейваны" style="vertical-align: middle;">'; ?>
                                <!-- ник -->
                                <div class="<?= $usrunc['name'] == $user['name'] ? 'online' : ''; ?>" style="color: var(--text); font-weight: <?= $usrunc['name'] == $user['name'] ? 'bold' : 'normal'; ?>;">
                                    <?= htmlspecialchars($usrunc['name']); ?> [<?= $usrunc['level']; ?>]
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="zem_info">
                    <div class="zem_stat" style="justify-content: center;">
                        <i class="fas fa-exclamation-circle"></i> Нет зарегистрированных бойцов
                    </div>
                </div>
            <?php } ?>
            
            <div class="clan_separator"></div>
            
            <div class="clan_title">
                <i class="fas fa-users"></i> <?= htmlspecialchars($ucharrAname['name']); ?>
            </div>
            
            <?php if (is_array($ucharrA) && count($ucharrA) > 0) { ?>
                <div class="zem_info">
                    <?php for ($i = 0; $i < count($ucharrA); $i++) { 
                        $usrunc = $mc->query("SELECT * FROM `users` WHERE `id` >= '" . $ucharrA[$i]['user_id'] . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC); ?>
                        
                        <div class="member_card" onclick="showContent('/profile/<?= $usrunc['id']; ?>')">
                            <div class="member_number"><?= $i + 1; ?></div>
                            <div class="member_info">
                                <!-- флаг -->
                                <?= $usrunc['side'] == 0 || $usrunc['side'] == 1 ? '<img src="/img/icon/icoevil.png" width="24" height="24" alt="Нормасцы" style="vertical-align: middle;">' : '<img src="/img/icon/icogood.png" width="24" height="24" alt="Шейваны" style="vertical-align: middle;">'; ?>
                                <!-- ник -->
                                <div class="<?= $usrunc['name'] == $user['name'] ? 'online' : ''; ?>" style="color: var(--text); font-weight: <?= $usrunc['name'] == $user['name'] ? 'bold' : 'normal'; ?>;">
                                    <?= htmlspecialchars($usrunc['name']); ?> [<?= $usrunc['level']; ?>]
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="zem_info">
                    <div class="zem_stat" style="justify-content: center;">
                        <i class="fas fa-exclamation-circle"></i> Нет зарегистрированных бойцов
                    </div>
                </div>
            <?php } ?>
            
            <div style="text-align: center;">
                <?php if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "' && `type`='8'")->num_rows>0) { ?>
                    <div class="zem_button" onclick="showContent('/huntb/zem/remove.php')">
                        <i class="fas fa-times-circle"></i> Отказаться
                    </div>
                <?php } else { ?>
                    <div class="zem_button" onclick="showContent('/huntb/zem/add.php?add=2000')">
                        <i class="fas fa-plus-circle"></i> Зарегистрироваться
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php
    }
}

$footval = 'huntb1x1';
require_once ('../../system/foot/foot.php');