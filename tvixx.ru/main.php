<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//version check
require_once 'functions/version_check.php';

require_once 'system/func.php';

if (isset($user)&&$user['access']>99999) {
    ?><script>/*nextshowcontemt*/showContent("/disconnect.php");</script><?php
    exit(0);
}
require_once 'system/header.php';
//require_once 'api.php';
require_once 'system/dbc.php';
require_once 'functions/check_new_clan.php';
require_once 'functions/check_slava.php';
require_once 'functions/check_holidays_quests.php';
setTotem();
health_rechange();
auth(); // Закроем от неавторизированых
requestModer(); // Закроем для тех у кого есть запрос на модератора
//проверяем что герой не в бою
if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script><?php
    exit(0);
}
//проверяем результаты если есть то перекинем туда чтобы обработало монстров
if ($mc->query("SELECT * FROM `resultbattle` WHERE `id_user`='" . $user['id'] . "' ORDER BY `id` DESC LIMIT 1")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/result.php");</script><?php
    exit(0);
}
//стираем инфу о магазе надооо
$mc->query("UPDATE `users` SET `shopList` = '[[],[],[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
//стираем инфу о квестax
$mc->query("UPDATE `users` SET `questsList` = '[[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
//уберем отсидевшие квесты за огурцы
$mc->query("DELETE FROM `quests_notActive` WHERE `id_user`='" . $user['id'] . "' && `time_end`>='0' && `time_end`<'" . time() . "'");
//удаление не активных мобов у которых время вышло 
$mc->query("DELETE FROM `userHuntNotActiveMob` WHERE `time_end` < '" . time() . "'");
//стираем инфу о таймауте противников для дуэлей
$mc->query("DELETE FROM `response` WHERE `time_start`<'" . time() . "'");
//удалим из 1_1 
$mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "' && (`type`='1'||`type`='2')");

//название локации и изображение
$loca = $user["location"];
$side = $user["side"];
if ($side == 0) {
    $accessloc = 2;
} elseif ($side == 1) {
    $accessloc = 2;
} elseif ($side == 2) {
    $accessloc = 1;
} elseif ($side == 3) {
    $accessloc = 1;
}
$locationArrList = [];
$locationArrNextList = [];
if (is_array(json_decode($user['location_list']))) {
    $locationArrList = json_decode($user['location_list']);
} else {
    $locationArrList[0] = $user["location"];
    $_GET['location'] = 0;
}
if (!isset($_GET['location']) || isset($_GET['location']) && $_GET['location'] >= count($locationArrList)) {
    $locationArrList[0] = $user["location"];
    $_GET['location'] = 0;
}

if (isset($_GET['location'])) {
    $user["location"] = $locationArrList[$_GET['location']];
    $mc->query("UPDATE `users` SET `location`='" . $user["location"] . "' WHERE `id`='" . $user["id"] . "'");
    $loca = $user["location"];
}

if ($loca == 0 || $loca == 23 && $user['access'] < 2) {
    $mc->query("UPDATE `users` SET `location`='4' WHERE `id`='" . $user["id"] . "'");
    ?><script>/*nextshowcontemt*/NewFuckOff();</script><?php
    exit(0);
}
if (isset($_GET['snow_set']) && $user['access'] > 2) {
    if ($_GET['snow_set'] == 0) {
        $_GET['snow_set'] = 1;
    } elseif ($_GET['snow_set'] == 1) {
        $_GET['snow_set'] = 0;
    }
    $mc->query("UPDATE `location` SET `snow`='" . $_GET['snow_set'] . "' WHERE `id`='$loca'");
    ?><script>/*nextshowcontemt*/showContent("/main");</script><?php
    exit(0);
}

$location = $mc->query("SELECT * FROM `location` WHERE `id`='$loca'")->fetch_array(MYSQLI_ASSOC);

if (isset($_REQUEST['success'])) {
    $login = urldecode($_POST['login']);
    $pass = urldecode($_POST['password']);

    $sql = $mc->query("SELECT `login`,`password` FROM `users` WHERE `login` = '" . $login . "' and `password`='" . md5($pass) . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC);

    if (empty($login))
        message('Введите логин');
    elseif (empty($pass))
        message('Введите пароль');
    elseif ($sql == 0)
        message('<div style="color: red;">Пользователь не существует</div>');
    else {
        setcookie('login', htmlentities(urlencode($login)), time() + 2592000, '/'); //А эта кука не работает в IE
        setcookie('password', md5($pass), time() + 2592000, '/'); //А эта кука не работает в IE
        ?><script>/*nextshowcontemt*/showContent("/main");</script><?php
        exit(0);
    }
}

// Определение функции nextCountQuests
function nextCountQuests($quests_counts) {
    global $mc;
    global $user;
    
    if (empty($quests_counts)) return;
    
    foreach ($quests_counts as $quest_count) {
        $id_quests = $quest_count['id_quests'];
        $count = $quest_count['count'];
        
        // Получаем базовую информацию о квесте
        $base_Quest = $mc->query("SELECT * FROM `quests` WHERE `id` = '$id_quests'")->fetch_array(MYSQLI_ASSOC);
        
        // Получаем информацию о текущей части квеста
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '$id_quests' && `count` = '$count'")->fetch_array(MYSQLI_ASSOC);
        
        // Проверяем наличие следующей части
        $next_part_exists = $mc->query("SELECT COUNT(*) as cnt FROM `quests_count` WHERE `id_quests` = '$id_quests' && `count` = '" . ($count + 1) . "'")->fetch_array(MYSQLI_ASSOC);
        
        // Если текущая часть последняя и квест не завершен, отмечаем его как завершенный
        if ($next_part_exists['cnt'] == 0 && $base_Quest['part_num'] <= $count) {
            // Обновляем статус квеста на завершенный (вариант 4)
            $mc->query("UPDATE `quests_users` SET `variant` = '4' WHERE `id_user` = '" . $user['id'] . "' AND `id_quests` = '$id_quests'");
        }
    }
}

// Определение функции chekDostypeQuest
function chekDostypeQuest($quest) {
    global $mc;
    global $user;
    
    // Если нет условий для взятия квеста, то квест доступен
    if (empty($quest['if_quest']) || $quest['if_quest'] == 0) {
        return true;
    }
    
    // Проверяем, есть ли у пользователя квест, который требуется для взятия этого
    $required_quest_exists = $mc->query("SELECT COUNT(*) as cnt FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' AND `id_quests` = '" . $quest['if_quest'] . "'")->fetch_array(MYSQLI_ASSOC);
    
    // Если нужен пройденный квест, проверяем его наличие и завершенность
    if ($quest['if_quest_part'] == 0) {
        // Проверка на наличие и завершенность квеста
        $completed_quest = $mc->query("SELECT COUNT(*) as cnt FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' AND `id_quests` = '" . $quest['if_quest'] . "' AND `variant` = '4'")->fetch_array(MYSQLI_ASSOC);
        
        return $completed_quest['cnt'] > 0;
    } else {
        // Проверка на прохождение определенной части квеста
        $quest_part_completed = $mc->query("SELECT COUNT(*) as cnt FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' AND `id_quests` = '" . $quest['if_quest'] . "' AND `count` >= '" . $quest['if_quest_part'] . "'")->fetch_array(MYSQLI_ASSOC);
        
        return $quest_part_completed['cnt'] > 0;
    }
    
    return false;
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
:root{
  --bg-grad-start:#111;
  --bg-grad-end:#1a1a1a;
  --accent:#f5c15d;
  --accent-2:#ff8452;
  --card-bg:rgba(255,255,255,0.05);
  --glass-bg:rgba(255,255,255,0.08);
  --glass-border:rgba(255,255,255,0.12);
  --text:#fff;
  --muted:#c2c2c2;
  --radius:16px;
}
*,*::before,*::after{box-sizing:border-box;}
html,body{margin:0;padding:0;width:100%;min-height:100%;color:var(--text);font-family:'Inter', Arial, sans-serif;background:linear-gradient(135deg,var(--bg-grad-start),var(--bg-grad-end));}
a{color:inherit;text-decoration:none;}

.main-wrapper{
  width:100%;
  max-width:600px;
  margin:auto;
  padding:clamp(8px,2vw,18px);
}

.location-container{
  position:relative;
  border-radius:var(--radius);
  overflow:hidden;
  box-shadow:0 8px 24px rgba(0,0,0,0.55);
  border:2px solid var(--accent);
  background:var(--card-bg);
}

.location-image{
  width:100%;
  aspect-ratio:3/2;
  object-fit:cover;
  transition:transform .5s ease;
  filter:brightness(.9);
}

.location-container:hover .location-image{transform:scale(1.06);}
.location-name{
  position:absolute;
  bottom:12px;
  left:50%;
  transform:translateX(-50%);
  backdrop-filter:blur(6px);
  background:var(--glass-bg);
  border:1px solid var(--glass-border);
  border-radius:12px;
  padding:6px 18px;
  font-weight:600;
  font-size:clamp(14px,3vw,18px);
  box-shadow:0 4px 12px rgba(0,0,0,0.4);
}

.main-actions{
  display:flex;
  gap:8px;
  margin:18px 0;
}

.action-button{
  flex:1;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  padding:12px 6px;
  border:none;
  border-radius:var(--radius);
  background:var(--glass-bg);
  border:1px solid var(--glass-border);
  color:var(--text);
  font-weight:600;
  font-size:clamp(12px,3vw,14px);
  backdrop-filter:blur(8px);
  transition:all .3s ease;
}

.action-button i{
  font-size:clamp(18px,5vw,24px);
  margin:0 0 4px;
}

.action-button:hover{transform:translateY(-2px);background:var(--accent);} 
.action-button:active{transform:translateY(1px);} 

.action-button.hunt{background:linear-gradient(135deg,#467d1b,#2c5110);} 
.action-button.quest{background:linear-gradient(135deg,#6e3b96,#4b2870);} 
.action-button.duel{background:linear-gradient(135deg,#994916,#65320f);} 

.counter-badge{
  display:inline-block;
  margin-top:4px;
  background:rgba(0,0,0,0.4);
  border-radius:8px;
  padding:2px 8px;
  font-size:10px;
}

.content-container{
  background:var(--card-bg);
  border:1px solid var(--glass-border);
  border-radius:var(--radius);
  padding:12px;
  backdrop-filter:blur(10px);
  margin-bottom:18px;
  box-shadow:0 6px 20px rgba(0,0,0,0.35);
}

.notification-item,
.location-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px;
  background:var(--glass-bg);
  border:1px solid var(--glass-border);
  border-radius:12px;
  margin-bottom:10px;
  transition:all .3s ease;
}

.notification-item:hover,
.location-item:hover{
  background:rgba(255,255,255,0.15);
  transform:translateY(-2px);
}

.notification-count{
  background:var(--accent-2);
  padding:2px 8px;
  border-radius:8px;
  font-weight:700;
  font-size:12px;
}

.divider{
  height:1px;
  width:100%;
  border:none;
  background:linear-gradient(to right, transparent, var(--glass-border), transparent);
  margin:14px 0;
}

/* Responsive tweaks */
@media(min-width:700px){
  .location-name{font-size:20px;padding:8px 22px;}
  .action-button{flex-direction:row;font-size:16px;padding:12px 10px;}
  .action-button i{margin:0 6px 0 0;}
}

/* snow effect container ensure pointer none remains */
.snow-effect{pointer-events:none;}
</style>

<div class="main-wrapper">
    <!-- Секция локации с красивой рамкой -->
    <div class="location-container">
        <div class="location-inner" onclick="<?= $user['access'] > 2 ? "showContent('/main?snow_set=" . $location['snow'] . "')" : ""; ?>">
            <img onload="<?= $location['snow'] == 1 ? "snowAppend($('.snow-effect'));" : ""; ?>" 
                 src="img/location/<?= $location['IdImage']; ?>.jpg" class="location-image">
            <div class="snow-effect snowConteiner"></div>
            <div class="location-name">
                <?= htmlspecialchars($location['Name']); ?> 
                <?= $location['snow'] == 1 && $user['access'] > 2 ? " ❄" : ""; ?>
            </div>
        </div>
    </div>

    <!-- Основные действия: Охота, Задания, Дуэли в гармоничном ряду горизонтально -->
    <div class="main-actions">
        <div class="action-button hunt arrowHunt" onclick="showContent('/hunt/')">
            <i class="fas fa-skull"></i> Охота
</div>

            <?php
        // Подготовка счетчиков для заданий
            $quests_count_res = $mc->query("SELECT * FROM `quests_count` WHERE (`id_quests`,`count`) IN (SELECT `id_quests`,`count` FROM `quests_users` WHERE `id_user` ='" . $user['id'] . "')");
            $quests_counts = [];
            if ($quests_count_res->num_rows > 0) {
                $quests_counts = $quests_count_res->fetch_all(MYSQLI_ASSOC);
            }
//закончим пройденные квесты
            nextCountQuests($quests_counts);
        
//ПОЛУЧАЕМ ВСЕ ВЗЯТЫЕ КВЕСТЫ ИГРОКА
            $user_quests = $mc->query("SELECT `id_quests`,`count`,`time_ce`,`herowin_c`,`variant` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' ORDER BY `time_view` DESC")->fetch_all(MYSQLI_ASSOC);
        $active_quests = 0;
        $completed_quests = 0;
        
        // Считаем активные квесты
            for ($i = 0; $i < count($user_quests); $i++) {
                if ($user_quests[$i]['variant'] != 4) {
                    if ($quests = $mc->query("SELECT `name` FROM `quests` WHERE `id` = '" . $user_quests[$i]['id_quests'] . "' && `part_num`>'" . ($user_quests[$i]['count']) . "'")->fetch_array(MYSQLI_ASSOC)) {
                    $active_quests++;
                }
            } else {
                $completed_quests++;
            }
        }
        
        // Считаем доступные квесты
        $available_quests = 0;
            $arrDostype = $mc->query("SELECT * FROM `quests` WHERE "
                            . "`locId`='" . $user['location'] . "'"
                            . "&&`level_min`<='" . $user['level'] . "'"
                            . "&&`level_max`>='" . $user['level'] . "'"
                            . "&&(`rasa`='" . $accessloc . "' || `rasa`='0')"
                            . " && `id` NOT IN "
                            . "( SELECT `id_quests` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' )"
                            . " && `id` NOT IN "
                            . "( SELECT `id_quests` FROM `quests_notActive` WHERE `id_user` = '" . $user['id'] . "' )")->fetch_all(MYSQLI_ASSOC);
            foreach ($arrDostype as $arr) {
            if (chekDostypeQuest($arr)) {
                $available_quests++;
            }
        }
        ?>
        
        <!-- Кнопка заданий с счетчиками -->
        <div class="action-button quest" onclick="showContent('/quests/quests.php')">
            <i class="fas fa-scroll"></i> Задания
            <span class="counter-badge">
                <span class="count-active"><?= $active_quests; ?></span>
                <span class="count-separator">/</span>
                <span class="count-available"><?= $available_quests; ?></span>
                <span class="count-separator">/</span>
                <span class="count-completed"><?= $completed_quests; ?></span>
                        </span>
        </div>
        
        <?php if (isset($user) && $user['level'] >= 2): ?>
        <div class="action-button duel arrowDuel" onclick="showContent('/huntb/')">
            <i class="fas fa-swords"></i> Дуэли
        </div>
        <?php endif; ?>
    </div>

    <!-- Основной контент -->
    <div class="content-container">
        <?php
        // Уведомления о новостях
        if ($user['news_all'] > 0): ?>
            <div class="notification-item" onclick="showContent('/main.php?news')">
                <img src="/img/quest.png" class="notification-icon" alt="">
                <div class="notification-content">
                    Новость: <span class="notification-count"><?= intval($user['news_all']); ?></span>
                </div>
            </div>
            <div class="divider"></div>
        <?php endif; ?>

        <?php
        // Уведомления о сообщениях
        $newmessages = $mc->query("SELECT COUNT(*) as `Msg` FROM `mailRoom`, `mail2` WHERE `mail2`.`id` = `mailRoom`.`room_id` AND (`mail2`.`id1` = '" . $user['id'] . "' OR `mail2`.`id2` = '" . $user['id'] . "') AND `mailRoom`.`id_sender` != '" . $user['id'] . "' AND `mailRoom`.`readMsg` = 0")->fetch_array(MYSQLI_ASSOC);
        if ($newmessages['Msg'] > 0): ?>
            <div class="notification-item" onclick="showContent('/mail')">
                <img src="/img/quest.png" class="notification-icon" alt="">
                <div class="notification-content">
                    Почта: <span class="notification-count"><?= $newmessages['Msg']; ?></span>
                </div>
            </div>
            <div class="divider"></div>
        <?php endif; ?>

        <!-- Секция локаций без заголовка -->
        <div class="locations-section">
            <?php
            $cicleloc = 1;
            $posLoc = 0;
            $has_locations = false;
            
            while ($cicleloc < 11) {
                $questLocVisible = true;
                if ($location['IdLoc' . $cicleloc . ''] != 0) {
                    $lo = $mc->query("SELECT * FROM `location` WHERE `id`='" . $location['IdLoc' . $cicleloc . ''] . "'")->fetch_array(MYSQLI_ASSOC);
                    if ($lo['quests'] > 0) {
                        $questLocVisible = false;
                        //сравниваем с квестами игрока
                        for ($i = 0; $i < count($quests_counts); $i++) {
                            if ($lo['id'] == $quests_counts[$i]['gotolocid']) {
                                $questLocVisible = true;
                                break;
                            }
                        }
                    }
                    //проверяем наличие шмоток при наличии которых появится локация
                    if ($lo['thingid'] > 0) {
                        if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $lo['thingid'] . "' LIMIT 1")->num_rows > 0) {
                            $questLocVisible = true;
                        } else {
                            $questLocVisible = false;
                        }
                    }
                    //проверяем наличие локации при которой появится скрытка
                    if ($lo['id_loc_dostup_sk'] > 0 && $user['id_clan'] > 0 ) {
                        if ($lo['idClan'] == $user['id_clan']) {
                            $questLocVisible = true;
                        } else {
                            $questLocVisible = false;
                        }
                    }
                    
                    if ($questLocVisible && ($lo['access'] == $accessloc || $lo['access'] == 3)) {
                        if ($lo['accesslevel'] <= $user["level"]) {
                            $has_locations = true;
                            //админ учаток
                            if ($user['access'] > 2 && $lo['id'] == 23) {
                                $locationArrNextList[] = $lo['id'];
                                ?>
                                <div class="location-item" onclick="showContent('/main.php?l12&location=<?= $posLoc; ?>')">
                                    <img src="/img/loc.png?136.2231" class="location-icon" alt="">
                                    <div class="location-text locArrow<?= $lo['id']; ?>"><?= $lo['Name']; ?></div>
                                </div>
                                <?php
                                $posLoc++;
                            } elseif ($lo['id'] != 23) {
                                $locationArrNextList[] = $lo['id'];
                                ?>
                                <div class="location-item" onclick="showContent('/main.php?l12&location=<?= $posLoc; ?>')">
                                    <img src="/img/loc.png?136.2231" class="location-icon" alt="">
                                    <div class="location-text locArrow<?= $lo['id']; ?>"><?= $lo['Name']; ?></div>
                                </div>
                                <?php
                                $posLoc++;
                            }
                        }
                    }
                }
                $cicleloc++;
            }
            
            if (!$has_locations) {
                echo '<div class="location-empty">Нет доступных локаций для перехода</div>';
            }
            
            $mc->query("UPDATE `users` SET `location_list`='" . json_encode($locationArrNextList) . "' WHERE `id`='" . $user["id"] . "'");
            ?>
        </div>
    </div>
</div>

<?php
// Оставляем всю логику обработки ниже без изменений

if ($user['id_clan'] != 0) {
    if ($user['dhdenter'] == 1) {
        //выдаем дхд
        $dhdclan = $mc->query("SELECT (sum(`dhdUser`) * " . $user["level"] . ") as `dhd` FROM `location` WHERE `idClan` = " . $user['id_clan'] . "")->fetch_array(MYSQLI_ASSOC);
        if ($dhdclan['dhd'] > 0) {
            $mc->query("UPDATE `users` SET `money` = `money`+ " . $dhdclan['dhd'] . ", `dhdenter`= 0  WHERE `id` = " . $user['id'] . "");
            message('Вам был выплачен доход в размере ' . money($dhdclan['dhd'], 'zoloto') . " золотых");
        } else {
            $mc->query("UPDATE `users` SET `dhdenter`= 0  WHERE `id` = " . $user['id'] . "");
        }
    }
}

//Запрос в друзья
if ($result = $mc->query("SELECT *,COUNT(0) FROM `friends` WHERE `id_user2` = '" . $user['id'] . "' AND `red`='1'")) {
    $drs = $result->fetch_array(MYSQLI_ASSOC);

    if ($drs['COUNT(0)'] != 0) {
        $result1 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $drs['id_user'] . "'");
        $use = $result1->fetch_array(MYSQLI_ASSOC);
        message_yn($use['name'] . " Хочет добавить вас в друзья", "/friends.php?yes", "/friends.php?no", "Да", "Нет");
    }
}

if (isset($_GET['aplication'])) {
    if ($_GET['aplication'] == 'enable') {
        message('Спасибо, что скачали оффициальное приложение. Я бы хотел подарить вам кучу платы, но боюсь,что это перебор');
    }
}

//нажал на новость и произошол сброс флага и счетчика+ редирект на новости
if (isset($_GET['news'])) {
    $mc->query("UPDATE `users` SET `news_all`='0' WHERE `id` = '" . $user['id'] . "' ");
    ?>
    <script>
        showContent('/new.php');
    </script>
    <?php
}

// Оставляем весь оставшийся код без изменений

$footval = "main";
require_once ('system/foot/foot.php');
?>
<script>MyLib.footName = "main";</script>  