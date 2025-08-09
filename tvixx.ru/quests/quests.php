<?php
//НЕ ТРОГАЙ
require_once '../system/func.php';
require_once '../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$footval = 'quests';
include '../system/foot/foot.php';
?>
<style>
    html{
    }
    .questbtn{display:inline-block;  background-repeat: no-repeat;background-size: contain;height: 31px;width: 102px;}

    .quest1{background-image: url('/img/quest/questA1.png');}
    .quest1:hover{background-image: url('/img/quest/quest1.png');}
    .quest1_1{background-image: url('/img/quest/quest1.png');margin-bottom: -2px;}

    .quest2{background-image: url('/img/quest/questA2.png');}
    .quest2:hover{ background-image: url('/img/quest/quest2.png');}
    .quest2_1{background-image: url('/img/quest/quest2.png');margin-bottom: -3px;}

    .quest3{background-image: url('/img/quest/questA3.png');}
    .quest3:hover{ background-image: url('/img/quest/quest3.png');}
    .quest3_1{background-image: url('/img/quest/quest3.png');margin-bottom: -2px;}

    .locpers{
        position: absolute;
        height: 100%;
        width: 100%;
        z-index: -1;
        bottom: 14%;
    }
    .locpers1{background: url("/img/qestpers/GOL_app_quest-merchant.png?123.0") no-repeat;background-size: 60%;background-position: bottom;}/*жиртрес*/
    .locpers2{background: url("/img/qestpers/GOL_app_quest-warrior.png?123.0") no-repeat;background-size: 60%;background-position: bottom;}/*Человек в броне*/
    .locpers3{background: url("/img/qestpers/GOL_app_quest-drunkard.png?123.0") no-repeat;background-size: 60%;background-position: bottom;}/*бомж*/
    .locpers4{background: url("/img/qestpers/GOL_app_quest-farrier.png?123.0") no-repeat;background-size: 60%;background-position: bottom;}/*Зеленая борода*/
    .locpers5{background: url("/img/qestpers/GOL_app_quest-girl.png?123.0") no-repeat;background-size: 60%;background-position: bottom;}/*Мадама*/
    .locpers6{background: url("/img/qestpers/GOL_app_quest-skeleton.png?123.0") no-repeat;background-size: 60%;background-position: bottom;}/*Скелет*/
    .locpers7{background: url(/img/qestpers/GOL_app_quest-spy.png?123.0) no-repeat;background-size: 60%;background-position: bottom;}/*в шарфике*/
    .btnyes{
        background: url(/img/button/btnyes.png);
        height: 100%;
        width: 100%;
        background-repeat: no-repeat;
        background-size: 75px;
        background-position: center;
    }
    .btnno{
        background: url("/img/button/btnno.png");
        height: 100%;
        width: 100%;
        background-repeat: no-repeat;
        background-size: 75px;
        background-position: center;
    }
    .btn:hover{
        opacity: 0.7;
    }
    /* --- Новый современный дизайн страницы квестов --- */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
    :root{
        --bg-grad-start:#111;
        --bg-grad-end:#1a1a1a;
        --accent:#f5c15d;
        --accent-2:#ff8452;
        --card-bg:rgba(255,255,255,0.05);
        --glass-bg:rgba(255,255,255,0.08);
        --glass-border:rgba(255,255,255,0.12);
        --text:#fff;
        --radius:16px;
        --q-glass-border:rgba(255,255,255,0.12);
        --q-text:var(--text);
        --q-radius:var(--radius);
        --muted: #999;
    }
    html,body{
        margin:0;
        padding:0;
        width:100%;
        min-height:100%;
        font-family:'Inter',sans-serif;
        background:linear-gradient(135deg,var(--bg-grad-start),var(--bg-grad-end));
        color:var(--text);
    }

    /* Обёртка для представлений */
    .view{
        max-width:600px;
        margin:14px auto;
        padding:12px;
        background:var(--card-bg);
        border:1px solid var(--glass-border);
        border-radius:var(--radius);
        backdrop-filter:blur(8px);
        -webkit-backdrop-filter:blur(8px);
        box-shadow:0 6px 20px rgba(0,0,0,0.35);
    }

    .view a{
        display:block;
        color:var(--accent);
        font-weight:600;
        padding:6px 0;
        transition:all .25s ease;
    }
    .view a:hover{
        color:var(--accent-2);
        transform:translateX(2px);
    }

    .view hr{
        border:none;
        height:1px;
        background:linear-gradient(to right, transparent, var(--glass-border), transparent);
        margin:8px 0;
    }

    /* Стили для карточек квестов */
    .quest-card {
        margin: 12px 0;
        padding: 12px;
        background: rgba(30, 30, 35, 0.75) !important; /* Зафиксированный темный фон */
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25), inset 0 0 1px rgba(255,255,255,0.2); /* Добавлена внутренняя подсветка */
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .quest-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.35), inset 0 0 2px rgba(255,255,255,0.3);
        background: rgba(40, 40, 50, 0.85) !important; /* Темнее при наведении */
    }
    
    .quest-card::before {
        display: none;
    }
    
    .quest-card a {
        padding: 4px 0 4px 8px !important;
        display: block;
        color: var(--accent) !important; /* Зафиксированный цвет текста */
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    
    .quest-card a:hover {
        color: var(--accent-2) !important; /* Другой цвет при наведении */
        transform: translateX(2px);
    }
    
    .quest-card .quest-comment {
        font-size: 0.9em;
        opacity: 0.7;
        margin-left: 8px;
        margin-top: 4px;
        color: var(--muted) !important; /* Зафиксированный цвет для комментариев */
    }

    /* Кнопки переключения списков */
    .questbtn{
        width:100px;
        height:34px;
        margin:4px;
        transition:transform .25s ease, filter .25s ease;
        filter:drop-shadow(0 4px 8px rgba(0,0,0,0.35));
    }
    .questbtn:hover{
        transform:translateY(-3px) scale(1.05);
        filter:brightness(1.15) drop-shadow(0 6px 12px rgba(0,0,0,0.45));
    }

    /* Кнопки действий в окнах квестов */
    .btnyes, .btnno, .button_alt_01{
        transition:opacity .25s ease, transform .25s ease;
    }
    .btnyes:hover, .btnno:hover, .button_alt_01:hover{
        transform:translateY(-2px) scale(1.04);
        opacity:0.85;
    }
    /* --- конец нового дизайна --- */

    /* --- Доп. стили для описания квеста и модальных окон --- */
    .ramka_dvig{
        max-width:600px;
        margin:16px auto;
        border-radius:var(--radius);
        overflow:hidden;
        position:relative;
        border:2px solid var(--accent);
        box-shadow:0 8px 24px rgba(0,0,0,0.55);
        background:var(--card-bg);
    }
    .ramka_dvig .location>div:first-child, .ramka_dvig .location img{
        width:100%;
        display:block;
        object-fit:cover;
    }
    .perg img{
        display:none;
    }
    .perg{
        position:absolute;
        bottom:12px;
        left:50%;
        transform:translateX(-50%);
        width:100%;
        display:flex;
        justify-content:center;
        pointer-events:none;
    }
    .perg_text{
        pointer-events:auto;
    }

    /* Обновляем таблицу-рамку описания */
    .ptb_1l, .ptb_1c, .ptb_1r, .ptb_3l, .ptb_3c, .ptb_3r{
        display:none;
    }
    .ptb_2c{
        background:var(--glass-bg);
        border:1px solid var(--glass-border);
        border-radius:var(--radius);
        padding:16px 12px;
        position:relative;
    }

    /* Модальное окно */
    .msgQuests{
        display:flex!important;
        align-items:center;
        justify-content:center;
        background:rgba(0,0,0,0.6) !important;
        backdrop-filter:blur(6px);
        -webkit-backdrop-filter:blur(6px);
    }
    .msgQuests table{
        width:90%!important;
        max-width:480px;
    }
    .msgQuests .text_msg_quest{
        color:#ffffff;
        font-size:16px;
        line-height:1.45;
    }
    .msgQuests div[style*="box-shadow"] {
        background: rgba(15,32,39,0.93) !important;   /* тёмный графит */
        border: 1px solid var(--q-glass-border) !important;
        border-radius: var(--q-radius) !important;
        color: var(--q-text) !important;              /* светлый текст */
    }
    /* --- конец доп. стилей --- */

    .ptb_2l, .ptb_2r{
        display:none;
    }
    /* Группа кнопок фильтра заданий */
    .questbtn{
        margin:4px 6px;
    }
    .questbtn-group{
        display:flex;
        justify-content:center;
        align-items:center;
        flex-wrap:wrap;
    }
    /* Текст описания квеста */
    .text_msg_quest{
        color:#ffffff;
        font-size:16px;
        line-height:1.45;
    }
</style>

<?php
//********ПРОСМОТР СПИСКА ЗАДАНИЙ pos - (активн,доступн,заверш) num - порядковый номер айдишника в массиве
if (isset($user) && !isset($_GET['num']) && !isset($_GET['pos'])) {
    //если герой зарегистрирован на турниры то кинуть в нужный турнир
    if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "'")->num_rows > 0) {
        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вы не можете брать задания пока вы зарегистрированы в дуэлях !','" . time() . "','msg')");
        ?><script>/*nextshowcontemt*/showContent("/huntb/index.php");</script><?php
        exit(0);
    }
    ?>
    <center>
        <div class="quest1 questbtn" onclick="getView(1);"></div>
        <div class="quest2 questbtn" onclick="getView(2);"></div>
        <div class="quest3 questbtn" onclick="getView(3);"></div>
        <hr style="margin-top: -3px;">
    </center>
    <?php
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
    $questsidarr = [];
    $questsidarr[0] = [];
    $questsidarr[1] = [];
    $questsidarr[2] = [];
    $user_quests = $mc->query("SELECT `id_quests`,`count`,`time_ce`,`variant` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' ORDER BY `time_view` DESC")->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="view1 view" style="display: none">
        <?php
        for ($i = 0, $c = 0; $i < count($user_quests); $i++) {
            if ($user_quests[$i]['variant'] != 4) {
                if ($quests = $mc->query("SELECT `id`,`name`,`rasa`,`comment` FROM `quests` WHERE `id` = '" . $user_quests[$i]['id_quests'] . "' && `part_num`>'" . ($user_quests[$i]['count']) . "'")->fetch_array(MYSQLI_ASSOC)) {
                    $user_quests_this = $mc->query("SELECT `auto_start_c` FROM `quests_count` WHERE `id_quests` = '" . $user_quests[$i]['id_quests'] . "' && `count` = '" . $user_quests[$i]['count'] . "'")->fetch_array(MYSQLI_ASSOC);
                    //проверяем автозапуск частей взятых
                    if ($user_quests_this['auto_start_c'] == 1) {
                        $mc->query("UPDATE `users` SET `questsList` = '[[" . $user_quests[$i]['id_quests'] . "],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
                        ?><script>/*nextshowcontemt*/showContent('/quests/quests.php?num=0&pos=0&sluch=0');</script><?php
                        exit(0);
                    }
                    //в случае провала
                    if ($user_quests[$i]['time_ce'] > 0 && $user_quests[$i]['time_ce'] < time()) {
                        $mc->query("UPDATE `users` SET `questsList` = '[[" . $user_quests[$i]['id_quests'] . "],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
                        ?><script>/*nextshowcontemt*/showContent('/quests/quests.php?num=0&pos=0&sluch=1');</script><?php
                        exit(0);
                    }
                    ?>
                    <div class="quest-card">
                        <a onclick="showContent('/quests/quests.php?num=<?= $c; ?>&pos=0')"><?= urldecode($quests['name']); ?></a>
                        <?php
                        if ($user['access'] > 2) {
                            $icon = "";
                            if ($quests['rasa'] == 1) {
                                $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
                            } elseif ($quests['rasa'] == 2) {
                                $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
                            }
                            ?>
                            <?= $icon; ?><a onclick="showContent('/admin/quest/quest.php?action=edit&id=<?= $quests['id']; ?>');"> >>изменить<< </a>
                            <div class="quest-comment"><?= urldecode($quests['comment']) != '' ? "//" . urldecode($quests['comment']) : ""; ?></div>
                        <?php } ?>
                    </div>
                    <?php
                    $questsidarr[0][] = $user_quests[$i]['id_quests'];
                    $c++;
                }
            }
        }
        ?>
    </div>
    <div class="view2 view" style="display: none">
        <?php
        //выбираем квесты которые не взяты , не пройдены, доступны по уровню , по локации
        $arrDostype = $mc->query("SELECT * FROM `quests` WHERE "
                        . "`locId`='" . $user['location'] . "'"
                        . "&&`level_min`<='" . $user['level'] . "'"
                        . "&&`level_max`>='" . $user['level'] . "'"
                        . "&&(`rasa`='" . $accessloc . "' || `rasa`='0')"
                        . " && `id` NOT IN "
                        . "( SELECT `id_quests` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' )"
                        . " && `id` NOT IN "
                        . "( SELECT `id_quests` FROM `quests_notActive` WHERE `id_user` = '" . $user['id'] . "' )")->fetch_all(MYSQLI_ASSOC);
        $c = 0;
        foreach ($arrDostype as $arr) {

            //недоступен при наличии взятого квеста
            if ($arr['quest_not'] > 0 && $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests`='" . $arr['quest_not'] . "'")->num_rows != 0) {
                continue;
            }
            //доступен при наличии пройденного или недоступен при отсутствии
            if ($arr['pred_quest'] > 0 && $mc->query("SELECT * FROM `quests_notActive` WHERE `id_user` = '" . $user['id'] . "' && `id_quests`='" . $arr['pred_quest'] . "'")->num_rows == 0) {
                continue;
            }


            //доступен при наличии предметов
            $arrClothesDost = json_decode($arr['predmet']);
            if (count($arrClothesDost) > 0) {
                $bool1 = FALSE;
                foreach ($arrClothesDost as $value) {
                    //если все вещи есть то кв доступен
                    if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $value[0] . "'")->num_rows >= $value[1]) {
                        continue;
                    } else {
                        $bool1 = TRUE;
                        break;
                    }
                }
                if ($bool1) {
                    continue;
                }
            }
            //доступен при отсутствии предметов
            $arrClothesNone = json_decode($arr['predmet_none']);
            if (count($arrClothesNone) > 0) {
                $bool2 = FALSE;
                foreach ($arrClothesNone as $value) {
                    //если каких то вещей меньше чем нужно чтоб квеста не было то он есть
                    if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $value[0] . "'")->num_rows < $value[1]) {
                        $bool2 = TRUE;
                        break;
                    }
                }
                if (!$bool2) {
                    continue;
                }
            }

            if ($arr['health'] > 0 && $arr['health'] > $user['health']) {
                continue;
            } elseif ($arr['health'] < 0 && $arr['health'] < $user['health']) {
                continue;
            }

            if ($arr['strength'] > 0 && $arr['strength'] > $user['strength']) {
                continue;
            } elseif ($arr['strength'] < 0 && $arr['strength'] < $user['strength']) {
                continue;
            }
            
            if ($arr['toch'] > 0 && $arr['toch'] > $user['toch']) {
                continue;
            } elseif ($arr['toch'] < 0 && $arr['toch'] < $user['toch']) {
                continue;
            }
            
            if ($arr['bron'] > 0 && $arr['bron'] > $user['bron']) {
                continue;
            } elseif ($arr['bron'] < 0 && $arr['bron'] < $user['bron']) {
                continue;
            }
            
            if ($arr['lov'] > 0 && $arr['lov'] > $user['lov']) {
                continue;
            } elseif ($arr['lov'] < 0 && $arr['lov'] < $user['lov']) {
                continue;
            }
            
            if ($arr['kd'] > 0 && $arr['kd'] > $user['kd']) {
                continue;
            } elseif ($arr['kd'] < 0 && $arr['kd'] < $user['kd']) {
                continue;
            }
            
            if ($arr['block'] > 0 && $arr['block'] > $user['block']) {
                continue;
            } elseif ($arr['block'] < 0 && $arr['block'] < $user['block']) {
                continue;
            }
            
            if ($arr['level'] > 0 && $arr['level'] > $user['level']) {
                continue;
            } elseif ($arr['level'] < 0 && $arr['level'] < $user['level']) {
                continue;
            }
            
            if ($arr['exp'] > 0 && $arr['exp'] > $user['exp']) {
                continue;
            } elseif ($arr['exp'] < 0 && $arr['exp'] < $user['exp']) {
                continue;
            }
            
            if ($arr['slava'] > 0 && $arr['slava'] > $user['slava']) {
                continue;
            } elseif ($arr['slava'] < 0 && $arr['slava'] < $user['slava']) {
                continue;
            }
            
            if ($arr['vinos_t'] > 0 && $arr['vinos_t'] > $user['vinos_t']) {
                continue;
            } elseif ($arr['vinos_t'] < 0 && $arr['vinos_t'] < $user['vinos_t']) {
                continue;
            }
            
            if ($arr['vinos_m'] > 0 && $arr['vinos_m'] > $user['vinos_m']) {
                continue;
            } elseif ($arr['vinos_m'] < 0 && $arr['vinos_m'] < $user['vinos_m']) {
                continue;
            }
            
            if ($arr['tur_reit'] > 0 && $arr['tur_reit'] > $user['tur_reit']) {
                continue;
            } elseif ($arr['tur_reit'] < 0 && $arr['tur_reit'] < $user['tur_reit']) {
                continue;
            }
            
            if ($arr['rep_p'] > 0 && $arr['rep_p'] > $user['rep_p']) {
                continue;
            } elseif ($arr['rep_p'] < 0 && $arr['rep_p'] < $user['rep_p']) {
                continue;
            }
            
            if ($arr['rep_m'] > 0 && $arr['rep_m'] > $user['rep_m']) {
                continue;
            } elseif ($arr['rep_m'] < 0 && $arr['rep_m'] < $user['rep_m']) {
                continue;
            }
            
            if ($arr['platinum'] > 0 && $arr['platinum'] > $user['platinum']) {
                continue;
            } elseif ($arr['platinum'] < 0 && $arr['platinum'] < $user['platinum']) {
                continue;
            }
            
            if ($arr['med'] > 0 && $arr['med'] > $user['money']) {
                continue;
            } elseif ($arr['med'] < 0 && $arr['med'] < $user['money']) {
                continue;
            }
            
            if ($arr['pobedmonser'] > 0 && $arr['pobedmonser'] > $user['pobedmonser']) {
                continue;
            } elseif ($arr['pobedmonser'] < 0 && $arr['pobedmonser'] < $user['pobedmonser']) {
                continue;
            }
            
            if ($arr['pobedigroki'] > 0 && $arr['pobedigroki'] > $user['pobedigroki']) {
                continue;
            } elseif ($arr['pobedigroki'] < 0 && $arr['pobedigroki'] < $user['pobedigroki']) {
                continue;
            }
            
            //звание 
            if ($arr['zvanie'] != '') {
                $slavaRes = $mc->query("SELECT * FROM `slava` WHERE `name` = '" . $arr['zvanie'] . "' ");
                if ($slavaRes->num_rows > 0) {
                    $slava = $slavaRes->fetch_array(MYSQLI_ASSOC);
                    //если слава звания больше 0
                    if ($slava['slava'] >= 0) {
                        if ($user['slava'] < $slava['slava'] || $user['level'] < $slava['lvl']) {
                            continue;
                        }
                    }
                    //если слава меньше 0
                    if ($slava['slava'] < 0) {
                        //получим предыдущее звание
                        $slavaResNext = $mc->query("SELECT * FROM `slava` WHERE `slava` > '" . $slava['slava'] . "' ORDER BY `slava` ASC LIMIT 1");
                        if ($slavaResNext->num_rows > 0) {
                            $slavaNext = $slavaResNext->fetch_array(MYSQLI_ASSOC);
                            //если слава вне диапазона то континуе
                            if ($user['slava'] >= $slavaNext['slava'] || $user['level'] < $slavaNext['lvl']) {
                                continue;
                            }
                        }
                    }
                }
            }
            $questsidarr[1][] = $arr['id'];
            if ($arr['auto_start'] == 1) {
                $mc->query("UPDATE `users` SET `questsList` = '" . json_encode($questsidarr) . "' WHERE `users`.`id` = '" . $user['id'] . "'");
                ?>
                <script>/*nextshowcontemt*/showContent('/quests/quests.php?num=<?= $c; ?>&pos=1');</script>
                <?php
                exit(0);
            }
            ?>
            <div class="quest-card">
                <a onclick="showContent('/quests/quests.php?num=<?= $c; ?>&pos=1')"><?= urldecode($arr['name']); ?></a>
                <?php
                if ($user['access'] > 2) {
                    $icon = "";
                    if ($arr['rasa'] == 1) {
                        $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
                    } elseif ($arr['rasa'] == 2) {
                        $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
                    }
                    ?>
                    <?= $icon; ?><a onclick="showContent('/admin/quest/quest.php?action=edit&id=<?= $arr['id']; ?>');"> >>изменить<< </a>
                    <div class="quest-comment"><?= urldecode($arr['comment']) != '' ? "//" . urldecode($arr['comment']) : ""; ?></div>
                <?php } ?>
            </div>
            <?php
            $c++;
        }
        ?>
    </div>
    <div class="view3 view" style="display: none">
        <?php
        for ($i = 0, $c = 0; $i < count($user_quests); $i++) {
            if (($user_quests[$i]['variant'] == 4 && $quests = $mc->query("SELECT `id`,`name`,`rasa`,`comment` FROM `quests` WHERE `id` = '" . $user_quests[$i]['id_quests'] . "' ")->fetch_array(MYSQLI_ASSOC)) || $quests = $mc->query("SELECT `id`,`name`,`rasa`,`comment` FROM `quests` WHERE `id` = '" . $user_quests[$i]['id_quests'] . "' && `part_num`<='" . ($user_quests[$i]['count']) . "'")->fetch_array(MYSQLI_ASSOC)) {
                ?>
                <div class="quest-card">
                    <a onclick="showContent('/quests/quests.php?num=<?= $c; ?>&pos=2&sluch=0')"><?= urldecode($quests['name']); ?></a>
                    <?php
                    if ($user['access'] > 2) {
                        $icon = "";
                        if ($quests['rasa'] == 1) {
                            $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
                        } elseif ($quests['rasa'] == 2) {
                            $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
                        }
                        ?>
                        <?= $icon; ?><a onclick="showContent('/admin/quest/quest.php?action=edit&id=<?= $quests['id']; ?>');"> >>изменить<< </a>
                        <div class="quest-comment"><?= urldecode($quests['comment']) != '' ? "//" . urldecode($quests['comment']) : ""; ?></div>
                    <?php } ?>
                </div>
                <?php
                $questsidarr[2][] = $user_quests[$i]['id_quests'];
                $c++;
            }
        }
        $mc->query("UPDATE `users` SET `questsList` = '" . json_encode($questsidarr) . "' WHERE `users`.`id` = '" . $user['id'] . "'");
        ?>
    </div>
    <script>
        if (typeof (getView) != "function") {
            getView = function (e) {
                $(".questbtn").removeClass("quest1_1 quest2_1 quest3_1");
                $(".quest" + e).addClass("quest" + e + "_1");
                $(".view").css({display: "none"});
                $(".view" + e).css({display: "block"});
            };
        }
        getView(1);
    </script>
    <?php
//*********ИЛИ ПРОСМОТР КОНКРЕТНЫХ ЗАДАНИЙ (pos - (активн,доступн,заыерш) num - (порядковый номер) )-questsList 
} else if (isset($user) && isset($_GET['num']) && isset($_GET['pos']) && $_GET['num'] >= 0 && $_GET['pos'] >= 0 && $_GET['pos'] <= 2) {
    //переводим строку со списками в массив со списками [[id1,2,3...],[],[]]
    $questsList = json_decode($user['questsList']);
    //если варианта развития события нет то определить его как начальный 
    $sluch = 0;
    if (isset($_GET['sluch'])) {
        $sluch = $_GET['sluch'];
    }

    //обработка части квестов
    if (isset($_GET['otvet']) && ($_GET['otvet'] == 0 || $_GET['otvet'] == 2 || $_GET['otvet'] == 3)) {
        //проверяем конец квеста
        //получаем айдишник квеста выбранного
        $id_quests = $questsList[$_GET['pos']][$_GET['num']];
        //если условия выполнены обработаем квест
        if ($sluch == 0 && checkCountQuest($id_quests)) {
            //выдаем награды
            rewardCountQuest($id_quests);
            addBattleQuests($id_quests);
            //обработаем переключение квестов
            $questsList = nextCountQuests($id_quests);
            //проверяем что герой не в бою
            if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows > 0) {
                ?><script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script><?php
                exit(0);
            }
            $_GET['pos'] = 0;
            $_GET['num'] = 0;
        } else if ($sluch == 0) {
            addBattleQuests($id_quests);
            //сбросим в бд список квестов
            $mc->query("UPDATE `users` SET `questsList` = '[[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
            //проверяем что герой не в бою
            if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows > 0) {
                ?><script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script><?php
                exit(0);
            }
            ?>
            <script>/*nextshowcontemt*/showContent("/main.php");</script>
            <?php
            exit(0);
            //или бработаем в случае отказа либо провала 
        } else if ($sluch == 1 || $sluch == 2) {
            //выдаем награды
            rewardCountQuest($id_quests);
            //обработаем переключение квестов
            $questsList = nextCountQuests($id_quests);
            $_GET['pos'] = 0;
            $_GET['num'] = 0;
            //или просто закроем его
        } else {
            //сбросим в бд список квестов
            $mc->query("UPDATE `users` SET `questsList` = '[[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
            ?>
            <script>/*nextshowcontemt*/showContent("/main.php");</script>
            <?php
            exit(0);
        }
        //или отказ взятия квеста , тогда показать вариант для отказа
    } else if (isset($_GET['otvet']) && $_GET['otvet'] == 1 && $sluch == 0) {
        $sluch = 2;
        //или закрыть квест и выйти
    } else if (isset($_GET['otvet'])) {
        ?>
        <script>/*nextshowcontemt*/showContent("/main.php");</script>
        <?php
        exit(0);
    }

    //ТУТ ВСЕ
    //вывод частей квестов
    if (isset($questsList[$_GET['pos']][$_GET['num']])) {
        if ($_GET['pos'] == 0 || $_GET['pos'] == 1 || $_GET['pos'] == 2) {
            //если выбрано активные
            if ($_GET['pos'] == 0) {
                //получаем выбранный квест активный и его базовые параметры
                $arrquests = $mc->query("SELECT * FROM `quests` WHERE `id` = '" . $questsList[$_GET['pos']][$_GET['num']] . "'")->fetch_array(MYSQLI_ASSOC);
                //получаем описание квеста у пользователя
                $arruserquests = $mc->query("SELECT * FROM `quests_users` WHERE  `id_user` = '" . $user['id'] . "' && `id_quests` = '" . $questsList[$_GET['pos']][$_GET['num']] . "'")->fetch_array(MYSQLI_ASSOC);
                //обновляем время просмотра
                $mc->query("UPDATE `quests_users` SET `time_view` = '" . time() . "' WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '" . $questsList[$_GET['pos']][$_GET['num']] . "'");
                //получаем часть квеста соответственно тому что у пользователя
                $arrcountquests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $questsList[$_GET['pos']][$_GET['num']] . "' && `count`='" . $arruserquests['count'] . "'")->fetch_array(MYSQLI_ASSOC);
            }
            if ($_GET['pos'] == 1) {
                //получаем выбранный квест активный и его базовые параметры
                $arrquests = $mc->query("SELECT * FROM `quests` WHERE `id` = '" . $questsList[$_GET['pos']][$_GET['num']] . "'")->fetch_array(MYSQLI_ASSOC);
                //получаем часть квеста первую
                $arrcountquests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $questsList[$_GET['pos']][$_GET['num']] . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
            }
            if ($_GET['pos'] == 2) {
                //получаем завершенный квест и его базовые параметры
                $arrquests = $mc->query("SELECT * FROM `quests` WHERE `id` = '" . $questsList[$_GET['pos']][$_GET['num']] . "'")->fetch_array(MYSQLI_ASSOC);
                //получаем описание квеста у пользователя
                $arruserquests = $mc->query("SELECT * FROM `quests_users` WHERE  `id_user` = '" . $user['id'] . "' && `id_quests` = '" . $questsList[$_GET['pos']][$_GET['num']] . "'")->fetch_array(MYSQLI_ASSOC);
                //получаем часть квеста соответственно тому что у пользователя
                $arrcountquests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $questsList[$_GET['pos']][$_GET['num']] . "' && `count`='" . $arruserquests['count'] . "'")->fetch_array(MYSQLI_ASSOC);
            }
            //создадим массив замены количеств шмоток и тд
            //time %time%,duels %duels%,drop&id %drop0%,shop&id %shop0%
            $arrRep = [];
            $arrRep['time'] = 0;
            $arrRep['duels'] = 0;
            $arrRep['drop'] = [];
            $arrRep['shop'] = [];
            if ($mc->query("SELECT * FROM `quests_users` WHERE  `id_user` = '" . $user['id'] . "' && `id_quests` = '" . $arrcountquests['id_quests'] . "'")->num_rows > 0) {
                $arrThisQuestUser = $mc->query("SELECT * FROM `quests_users` WHERE  `id_user` = '" . $user['id'] . "' && `id_quests` = '" . $arrcountquests['id_quests'] . "'")->fetch_array(MYSQLI_ASSOC);
                $arrRep['time'] = $arrThisQuestUser['time_ce'] - time();
                $arrRep['duels'] = $arrcountquests['herowin_c'] - $arrThisQuestUser['herowin_c'];
            } else {
                $arrRep['time'] = $arrcountquests['time_ce'];
                $arrRep['duels'] = $arrcountquests['herowin_c'];
            }
            if ($arrRep['time'] < 0) {
                $arrRep['time'] = 0;
            }
            if ($arrRep['duels'] < 0) {
                $arrRep['duels'] = 0;
            }
            $arrTemp0 = json_decode(urldecode($arrcountquests['drop_vesh']));
            for ($i = 0; $i < count($arrTemp0); $i++) {
                $countBagDrop = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp0[$i][0] . "'")->num_rows;
                $tmpnum = $arrTemp0[$i][1] - $countBagDrop;
                if ($tmpnum < 0) {
                    $tmpnum = 0;
                }
                $arrRep['drop'][] = [$arrTemp0[$i][0], $tmpnum];
            }
            $arrTemp1 = json_decode(urldecode($arrcountquests['buy_vesh']));
            for ($i = 0; $i < count($arrTemp1); $i++) {
                $countBagBuy = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp1[$i][0] . "'")->num_rows;
                $tmpnum = $arrTemp1[$i][1] - $countBagBuy;
                if ($tmpnum < 0) {
                    $tmpnum = 0;
                }
                $arrRep['shop'][] = [$arrTemp1[$i][0], $tmpnum];
            }
            //отправляем на вывод часть квеста
            visualQuests($arrcountquests, $_GET['num'], $_GET['pos'], $arrRep);
        } else {
            ?>
            <script>/*nextshowcontemt*/showContent("/main.php?error=1");</script>
            <?php
            exit(0);
        }
    } else {
        ?>
        <script>/*nextshowcontemt*/showContent("/main.php?error=2");</script>
        <?php
        exit(0);
    }
} else {
    ?>
    <script>/*nextshowcontemt*/showContent("/main.php?error=3");</script>
    <?php
    exit(0);
}

function chekBagDrop($id_quests) {
    global $mc;
    global $user;
    //делаем выборку имеющихся квестов пользователя
    $arr_user_quest_res = $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '$id_quests'");
    //если таковые имеются то получаем результат и проверяем победы над героями
    if ($arr_user_quest_res->num_rows > 0) {
        //получаем результат
        $arrThisQuestUser = $arr_user_quest_res->fetch_array(MYSQLI_ASSOC);
        //получаем часть квеста базовую на основании имеющейся
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . $arrThisQuestUser['count'] . "'")->fetch_array(MYSQLI_ASSOC);
    } else {
        //получаем часть квеста базовую первую
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
    }
    //проверка дроп вещей в сумке
    $arrTemp0 = json_decode(urldecode($arrCountQuests['drop_vesh']));
    for ($i = 0; $i < count($arrTemp0); $i++) {
        $countBagDrop = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp0[$i][0] . "'")->num_rows;
        //если выбито шмоток меньше чем нужно то false 
        if ($countBagDrop < $arrTemp0[$i][1]) {
            return FALSE;
        }
    }
    return TRUE;
}

//******функция проверки условий для прохождения части квеста
function checkCountQuest($id_quests) {
    global $mc;
    global $user;
    //делаем выборку имеющихся квестов пользователя
    $arr_user_quest_res = $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '$id_quests'");
    //если таковые имеются то получаем результат и проверяем победы над героями
    if ($arr_user_quest_res->num_rows > 0) {
        //получаем результат
        $arrThisQuestUser = $arr_user_quest_res->fetch_array(MYSQLI_ASSOC);
        //получаем часть квеста базовую на основании имеющейся
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . $arrThisQuestUser['count'] . "'")->fetch_array(MYSQLI_ASSOC);
        //далее проверяем победы если набито меньше чем нужно то false
        if ($arrThisQuestUser['herowin_c'] < $arrCountQuests['herowin_c']) {
            return FALSE;
        }
    } else {
        //получаем часть квеста базовую первую
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
    }
    //проверяем что пришел в локацию
    if ($arrCountQuests['gotolocid'] > 0 && $arrCountQuests['gotolocid'] != $user['location']) {
        return FALSE;
    }
    //проверка дроп вещей в сумке
    $arrTemp0 = json_decode(urldecode($arrCountQuests['drop_vesh']));
    for ($i = 0; $i < count($arrTemp0); $i++) {
        $countBagDrop = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp0[$i][0] . "'")->num_rows;
        //если выбито шмоток меньше чем нужно то false 
        if ($countBagDrop < $arrTemp0[$i][1]) {
            return FALSE;
        }
    }
    //проверка купленных вещей в сумке
    $arrTemp1 = json_decode(urldecode($arrCountQuests['buy_vesh']));
    for ($i = 0; $i < count($arrTemp1); $i++) {
        $countBagBuy = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp1[$i][0] . "'")->num_rows;
        //если куплено шмоток меньше чем нужно то false 
        if ($countBagBuy < $arrTemp1[$i][1]) {
            return FALSE;
        }
    }
    return TRUE;
}

//******функция выдачи снятия наград по варианту части квеста
function rewardCountQuest($id_quests) {
    global $mc;
    global $user;
    global $sluch;
    //определяем префикс
    $pre = ($sluch == 0) ? "" : (($sluch == 1) ? "proval_" : (($sluch == 2) ? "otkaz_" : ""));
    //делаем выборку имеющихся квестов пользователя
    $arr_user_quest_res = $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '$id_quests'");
    //если таковые имеются то получаем результат и проверяем победы над героями
    if ($arr_user_quest_res->num_rows > 0) {
        //получаем результат
        $arrThisQuestUser = $arr_user_quest_res->fetch_array(MYSQLI_ASSOC);
        //получаем часть квеста базовую на основании имеющейся
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . $arrThisQuestUser['count'] . "'")->fetch_array(MYSQLI_ASSOC);
    } else {
        //получаем часть квеста базовую первую
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
    }
    //все это сработает в случаях 0 и 2 а в случае 1 только если вышло время или онос
    if ($sluch == 0 || $sluch == 2 || ($sluch == 1 && is_array($arrThisQuestUser) && $arrThisQuestUser['time_ce'] > 0 && $arrThisQuestUser['time_ce'] < time())) {
        //забрать вещи
        $temparr000 = json_decode(urldecode($arrCountQuests[$pre . 'delpv']));
        $arrTemp0 = is_array($temparr000) ? $temparr000 : [];
        for ($i = 0; $i < count($arrTemp0); $i++) {
            $mc->query("DELETE FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $arrTemp0[$i][0] . "' LIMIT " . $arrTemp0[$i][1]);
            //смотрим на удаляемую вещь
            $infoshop1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $arrTemp0[$i][0] . "'")->fetch_array(MYSQLI_ASSOC);
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> потерял вещь </font><font color='#0033cc'>" . $infoshop1['name'] . "(" . $arrTemp0[$i][1] . ")</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Логи кв','','4', '" . $chatmsg . " квест " . $arrCountQuests['id_quests'] . " часть " . $arrCountQuests['count'] . " " . date('H:i:s') . "','','','' )");
        }
        //забрать статы
        $mc->query("UPDATE `users` SET "
                . "`exp` = `exp`-'" . $arrCountQuests[$pre . 'delpexp'] . "',"
                . "`slava` = `slava`-'" . $arrCountQuests[$pre . 'delpslava'] . "',"
                . "`vinos_t` = `vinos_t`-'" . $arrCountQuests[$pre . 'delpvinos_t'] . "',"
                . "`vinos_m` = `vinos_m`-'" . $arrCountQuests[$pre . 'delpvinos_m'] . "',"
                . "`platinum` = `platinum`-'" . $arrCountQuests[$pre . 'delpplatinum'] . "',"
                . "`money` = `money`-'" . $arrCountQuests[$pre . 'delpmed'] . "',"
                . "`pobedmonser` = `pobedmonser`-'" . $arrCountQuests[$pre . 'delppobedmonser'] . "',"
                . "`pobedigroki` = `pobedigroki`-'" . $arrCountQuests[$pre . 'delppobedigroki'] . "'"
                . " WHERE `id` = '" . $user['id'] . "'");
        if ($arrCountQuests[$pre . 'delpexp'] > 0 ||
                $arrCountQuests[$pre . 'delpslava'] > 0 || $arrCountQuests[$pre . 'delpvinos_t'] > 0 ||
                $arrCountQuests[$pre . 'delpvinos_m'] > 0 || $arrCountQuests[$pre . 'delpplatinum'] > 0 ||
                $arrCountQuests[$pre . 'delppobedmonser'] > 0 || $arrCountQuests[$pre . 'delppobedigroki'] > 0) {
            $uortext = "";
            if ($arrCountQuests[$pre . 'delpexp'] > 0) {
                $uortext .= " , опыт -" . $arrCountQuests[$pre . 'delpexp'];
            }
            if ($arrCountQuests[$pre . 'delpslava'] > 0) {
                $uortext .= " , слава -" . $arrCountQuests[$pre . 'delpslava'];
            }
            if ($arrCountQuests[$pre . 'delpvinos_t'] > 0) {
                $uortext .= " , вынос тек -" . $arrCountQuests[$pre . 'delpvinos_t'];
            }
            if ($arrCountQuests[$pre . 'delpvinos_m'] > 0) {
                $uortext .= " , вынос макс -" . $arrCountQuests[$pre . 'delpvinos_m'];
            }
            if ($arrCountQuests[$pre . 'delpplatinum'] > 0) {
                $uortext .= " , ПЛАТИНА -" . $arrCountQuests[$pre . 'delpplatinum'];
            }
            if ($arrCountQuests[$pre . 'delpmed'] > 0) {
                $uortext .= " , юники -" . $arrCountQuests[$pre . 'delpmed'];
            }
            if ($arrCountQuests[$pre . 'delppobedmonser'] > 0) {
                $uortext .= " , поб м -" . $arrCountQuests[$pre . 'delppobedmonser'];
            }
            if ($arrCountQuests[$pre . 'delppobedigroki'] > 0) {
                $uortext .= " , поб г -" . $arrCountQuests[$pre . 'delppobedigroki'];
            }
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> потерял </font><font color='#0033cc'>" . $uortext . " квест " . $arrCountQuests['id_quests'] . " часть " . $arrCountQuests['count'] . "</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Логи кв','','4', '" . $chatmsg . " " . date('H:i:s') . "','','','' )");
        }
        //выдать вещи
        $temparr001 = json_decode(urldecode($arrCountQuests[$pre . 'addpv']));
        $temparr002 = json_decode(urldecode($arrCountQuests[$pre . 'addprv']));
        $arrTemp1 = array_merge(is_array($temparr001) ? $temparr001 : [], genRandArrVal(is_array($temparr002) ? $temparr002 : [], $arrCountQuests[$pre . 'addprnv']));
        for ($i = 0; $i < count($arrTemp1); $i++) {
            //смотрим на новую вещь
            $infoshop1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $arrTemp1[$i][0] . "'")->fetch_array(MYSQLI_ASSOC);
            //дата истечения в unix
            if ($infoshop1['time_s'] > 0) {
                $time_the_lapse = $infoshop1['time_s'] + time();
            } else {
                $time_the_lapse = 0;
            }
            for ($i1 = 0; $i1 < $arrTemp1[$i][1]; $i1++) {
                $mc->query("INSERT INTO `userbag`("
                        . "`id_user`,"
                        . " `id_shop`,"
                        . " `id_punct`,"
                        . " `dress`,"
                        . " `iznos`,"
                        . " `time_end`,"
                        . " `id_quests`,"
                        . " `koll`,"
                        . " `max_hc`,"
                        . " `stil`,"
                        . " `BattleFlag`"
                        . ") VALUES ("
                        . "'" . $user['id'] . "',"
                        . "'" . $infoshop1['id'] . "',"
                        . "'" . $infoshop1['id_punct'] . "',"
                        . "'0',"
                        . "'" . $infoshop1['iznos'] . "',"
                        . "'$time_the_lapse',"
                        . "'" . $infoshop1['id_quests'] . "',"
                        . "'" . $infoshop1['koll'] . "',"
                        . "'" . $infoshop1['max_hc'] . "',"
                        . "'" . $infoshop1['stil'] . "',"
                        . "'" . $infoshop1['BattleFlag'] . "'"
                        . ")");
                $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> получил </font><font color='#0033cc'>" . $infoshop1['name'] . "</font>");
                $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Логи кв','','4', '" . $chatmsg . " квест " . $arrCountQuests['id_quests'] . " часть " . $arrCountQuests['count'] . " " . date('H:i:s') . "','','','' )");
                if ($infoshop1['chatSend']) {
                    $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','0', '" . $chatmsg . "','','','' )");
                    $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','1', '" . $chatmsg . "','','','' )");
                }
            }
        }
        //выдать статы
        $mc->query("UPDATE `users` SET "
                . "`exp` = `exp`+'" . $arrCountQuests[$pre . 'addpexp'] . "',"
                . "`slava` = `slava`+'" . $arrCountQuests[$pre . 'addpslava'] . "',"
                . "`vinos_t` = `vinos_t`+'" . $arrCountQuests[$pre . 'addpvinos_t'] . "',"
                . "`vinos_m` = `vinos_m`+'" . $arrCountQuests[$pre . 'addpvinos_m'] . "',"
                . "`platinum` = `platinum`+'" . $arrCountQuests[$pre . 'addpplatinum'] . "',"
                . "`money` = `money`+'" . $arrCountQuests[$pre . 'addpmed'] . "',"
                . "`pobedmonser` = `pobedmonser`+'" . $arrCountQuests[$pre . 'addppobedmonser'] . "',"
                . "`pobedigroki` = `pobedigroki`+'" . $arrCountQuests[$pre . 'addppobedigroki'] . "'"
                . " WHERE `id` = '" . $user['id'] . "'");
        if ($arrCountQuests[$pre . 'addpexp'] > 0 ||
                $arrCountQuests[$pre . 'addpslava'] > 0 || $arrCountQuests[$pre . 'addpvinos_t'] > 0 ||
                $arrCountQuests[$pre . 'addpvinos_m'] > 0 || $arrCountQuests[$pre . 'addpplatinum'] > 0 ||
                $arrCountQuests[$pre . 'addppobedmonser'] > 0 || $arrCountQuests[$pre . 'addppobedigroki'] > 0) {
            $uortext = "";
            if ($arrCountQuests[$pre . 'addpexp'] > 0) {
                $uortext .= " , опыт +" . $arrCountQuests[$pre . 'addpexp'];
            }
            if ($arrCountQuests[$pre . 'addpslava'] > 0) {
                $uortext .= " , слава +" . $arrCountQuests[$pre . 'addpslava'];
            }
            if ($arrCountQuests[$pre . 'addpvinos_t'] > 0) {
                $uortext .= " , вынос тек +" . $arrCountQuests[$pre . 'addpvinos_t'];
            }
            if ($arrCountQuests[$pre . 'addpvinos_m'] > 0) {
                $uortext .= " , вынос макс +" . $arrCountQuests[$pre . 'addpvinos_m'];
            }
            if ($arrCountQuests[$pre . 'addpplatinum'] > 0) {
                $uortext .= " , ПЛАТИНА +" . $arrCountQuests[$pre . 'addpplatinum'];
            }
            if ($arrCountQuests[$pre . 'addpmed'] > 0) {
                $uortext .= " , юники +" . $arrCountQuests[$pre . 'addpmed'];
            }
            if ($arrCountQuests[$pre . 'addppobedmonser'] > 0) {
                $uortext .= " , поб м +" . $arrCountQuests[$pre . 'addppobedmonser'];
            }
            if ($arrCountQuests[$pre . 'addppobedigroki'] > 0) {
                $uortext .= " , поб г +" . $arrCountQuests[$pre . 'addppobedigroki'];
                //прибавка побед над героями всем квестам взятым игрока
                $mc->query("UPDATE `quests_users` SET `herowin_c` = `herowin_c`+'" . $arrCountQuests[$pre . 'addppobedigroki'] . "' WHERE `id_user`='" . $user['id'] . "'");
            }
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> получил </font><font color='#0033cc'>" . $uortext . " квест " . $arrCountQuests['id_quests'] . " часть " . $arrCountQuests['count'] . "</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Логи кв','','4', '" . $chatmsg . " " . date('H:i:s') . "','','','' )");
        }
    }
}

//************функция переключения частей квеста
function nextCountQuests($id_quests) {
    global $mc;
    global $user;
    global $sluch;
    $questsidarr = [[], [], []];
    //определяем префикс
    $pre = ($sluch == 0) ? "" : (($sluch == 1) ? "proval_" : (($sluch == 2) ? "otkaz_" : ""));
    //делаем выборку имеющихся квестов пользователя
    $base_Quest = $mc->query("SELECT * FROM `quests` WHERE `id` = '$id_quests'")->fetch_array(MYSQLI_ASSOC);
    $arr_user_quest_res = $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '$id_quests'");
    //если таковые имеются то получаем результат
    if ($arr_user_quest_res->num_rows > 0) {
        //получаем результат
        $arrThisQuestUser = $arr_user_quest_res->fetch_array(MYSQLI_ASSOC);
        //получаем часть квеста базовую на основании имеющейся
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . $arrThisQuestUser['count'] . "'")->fetch_array(MYSQLI_ASSOC);
    } else {
        //получаем часть квеста базовую первую
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
    }

    //все это сработает в случаях 0 и 2 а в случае 1 только если вышло время или онос
    if ($sluch == 0 || $sluch == 2 || ($sluch == 1 && is_array($arrThisQuestUser) && $arrThisQuestUser['time_ce'] > 0 && $arrThisQuestUser['time_ce'] < time())) {
        //завершить квест
        if ($arrCountQuests[$pre . 'type_if'] == 1) {
            $mc->query("DELETE FROM `quests_users` WHERE `id_user`='" . $user['id'] . "' && `id_quests`='$id_quests'");
            if ($base_Quest['time_r'] > 0) {
                $mc->query("INSERT INTO `quests_notActive` (`id`, `id_user`, `id_quests`, `time_end`) VALUES (NULL, '" . $user['id'] . "', '$id_quests', '" . (time() + $base_Quest['time_r']) . "')");
            } else if ($base_Quest['time_r'] < 0) {
                $mc->query("INSERT INTO `quests_notActive` (`id`, `id_user`, `id_quests`, `time_end`) VALUES (NULL, '" . $user['id'] . "', '$id_quests', '" . $base_Quest['time_r'] . "')");
            }
        }
        $arrCountQuestsNextRes = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . ($arrCountQuests['count'] + 1) . "'");
        $time_ce = -1;
        if ($arrCountQuestsNextRes->num_rows > 0) {
            $arrCountQuestsNext = $arrCountQuestsNextRes->fetch_array(MYSQLI_ASSOC);
            if (is_array($arrCountQuestsNext) && $arrCountQuestsNext['time_ce'] > 0) {
                $time_ce = $arrCountQuestsNext['time_ce'] + time();
            }
        }
        //завершить этот запустить новый
        if ($arrCountQuests[$pre . 'type_if'] == 2) {
            $mc->query("DELETE FROM `quests_users` WHERE `id_user`='" . $user['id'] . "' && `id_quests`='$id_quests'");
            if ($base_Quest['time_r'] > 0) {
                $mc->query("INSERT INTO `quests_notActive` (`id`, `id_user`, `id_quests`, `time_end`) VALUES (NULL, '" . $user['id'] . "', '$id_quests', '" . (time() + $base_Quest['time_r']) . "')");
            } else if ($base_Quest['time_r'] < 0) {
                $mc->query("INSERT INTO `quests_notActive` (`id`, `id_user`, `id_quests`, `time_end`) VALUES (NULL, '" . $user['id'] . "', '$id_quests', '" . $base_Quest['time_r'] . "')");
            }
            $thisQuest = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $arrCountQuests[$pre . 'new_quest'] . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
            $mc->query("DELETE FROM `quests_users` WHERE `id_user`='" . $user['id'] . "' && `id_quests`='" . $thisQuest['id_quests'] . "'");
            //вставляем в бд запись 1 часть нового квеста выбранному пользователю
            $mc->query("INSERT INTO `quests_users` ("
                    . "`id`, `id_user`, `id_quests`, `count`, `time_view`, `time_ce`,`herowin_c`,`variant`"
                    . ") VALUES ("
                    . "NULL, '" . $user['id'] . "', '" . $thisQuest['id_quests'] . "', '2', '" . time() . "', '$time_ce', '0' , '" . $arrCountQuests[$pre . 'type_if'] . "'"
                    . ")");
            //перезаписываем айдишник выбранного
            $questsidarr[0][0] = $thisQuest['id_quests'];
        }
        //перейти к следующей части
        if ($arrCountQuests[$pre . 'type_if'] == 3 || $arrCountQuests[$pre . 'type_if'] == 4 || $arrCountQuests[$pre . 'type_if'] == 5) {
            $mc->query("DELETE FROM `quests_users` WHERE `id_user`='" . $user['id'] . "' && `id_quests`='$id_quests'");
            //вставляем в бд запись части квеста выбранному пользователю
            $mc->query("INSERT INTO `quests_users` ("
                    . "`id`, `id_user`, `id_quests`, `count`, `time_view`, `time_ce`,`herowin_c`,`variant`"
                    . ") VALUES ("
                    . "NULL, '" . $user['id'] . "', '$id_quests', '" . ($arrCountQuests['count'] + 1) . "', '" . time() . "', '$time_ce', '0' , '" . $arrCountQuests[$pre . 'type_if'] . "'"
                    . ")");
            //перезаписываем айдишник выбранного
            $questsidarr[0][0] = $id_quests;
            $arrCountQuestsOld = $arrCountQuests;
            //делаем выборку имеющихся квестов пользователя
            $arr_user_quest_res = $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '$id_quests'");
            //если таковые имеются то получаем результат и проверяем победы над героями
            if ($arr_user_quest_res->num_rows > 0) {
                //получаем результат
                $arrThisQuestUser = $arr_user_quest_res->fetch_array(MYSQLI_ASSOC);
                //получаем часть квеста базовую на основании имеющейся
                $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . $arrThisQuestUser['count'] . "'")->fetch_array(MYSQLI_ASSOC);
            } else {
                //получаем часть квеста базовую первую
                $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
            }
            if ($arrCountQuests['autobattle'] == 1 || $arrCountQuests['autobattle'] == 4) {
                addBattleQuests($id_quests);
                ?>
                <script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script>
                <?php
                exit(0);
            } else if ($arrCountQuestsOld[$pre . 'type_if'] == 4 || $arrCountQuestsOld[$pre . 'type_if'] == 5) {
                ?>
                <script>/*nextshowcontemt*/showContent('/main.php');</script>
                <?php
                exit(0);
            }
        }
    }
    //переключить вариант на первый
    $sluch = 0;
    //обновляем в бд список квестов
    $mc->query("UPDATE `users` SET `questsList` = '" . json_encode($questsidarr) . "' WHERE `users`.`id` = '" . $user['id'] . "'");
    return $questsidarr;
}

//********функция создания боя
function addBattleQuests($id_quests) {
    global $mc;
    global $user;
    //делаем выборку имеющихся квестов пользователя
    $arr_user_quest_res = $mc->query("SELECT * FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' && `id_quests` = '$id_quests'");
    //если таковые имеются то получаем результат и проверяем победы над героями
    if ($arr_user_quest_res->num_rows > 0) {
        //получаем результат
        $arrThisQuestUser = $arr_user_quest_res->fetch_array(MYSQLI_ASSOC);
        //получаем часть квеста базовую на основании имеющейся
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='" . $arrThisQuestUser['count'] . "'")->fetch_array(MYSQLI_ASSOC);
    } else {
        //получаем часть квеста базовую первую
        $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $id_quests . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
    }
    $flag = chekBagDrop($id_quests);
    if ($arrCountQuests['autobattle'] == 1 && !$flag || $arrCountQuests['autobattle'] == 3 && !$flag || $arrCountQuests['autobattle'] == 2 || $arrCountQuests['autobattle'] == 4) {
        $PA = $user;
        $battle_id = rand(0, time()) . rand(0, time()) . rand(0, time()) . rand(0, time());
        $battle_start_time = time();
        $list_mob = json_decode(urldecode($arrCountQuests['mob_battle']));
        $MA = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . $list_mob[0] . "'")->fetch_array(MYSQLI_ASSOC);

        $PA['weaponico'] = 0;
        $PA['Pshieldnum'] = 0;
        $command = 0;
        $arr = [[], []];
        $PweaponEffect = array();

        $arr = [];
        //setzero
        $arr['temp_health'] = $PA['temp_health'];
        $arr['max_health'] = $PA['health'];
        $arr['strength'] = $PA['strength'];
        $arr['toch'] = $PA['toch'];
        $arr['lov'] = $PA['lov'];
        $arr['kd'] = $PA['kd'];
        $arr['block'] = $PA['block'];
        $arr['bron'] = $PA['bron'];
        //пересчет параметров игрока
        //получаем список одетых вещей героя
        $result221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $PA['id'] . "' AND `dress`='1' && `BattleFlag`='1' || `id_user` = '" . $PA['id'] . "' AND `id_punct`>'9' && `BattleFlag`='1'");
        $myrow221 = $result221->fetch_all(MYSQLI_ASSOC);
        //перебираем параметры вещей

        for ($i = 0; $i < count($myrow221); $i++) {
            //read thing
            $result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221[$i]['id_shop'] . "'");
            if ($result1->num_rows) {
                //thing to arr par
                $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
                $arr['max_health'] += $infoshop['health'];
                $arr['strength'] += $infoshop['strength'];
                $arr['toch'] += $infoshop['toch'];
                $arr['lov'] += $infoshop['lov'];
                $arr['kd'] += $infoshop['kd'];
                $arr['block'] += $infoshop['block'];
                $arr['bron'] += $infoshop['bron'];
                //переводим в иконку оружия
                if ((int) $infoshop['id_punct'] == 1) {
                    $PA['weaponico'] = $infoshop['id_image'];
                }
                //получаем количество щита
                if ((int) $infoshop['id_punct'] == 2) {
                    $PA['Pshieldnum'] = $infoshop['koll'];
                }
                if ($PA['stil'] >= 0 && $PA['stil'] < 5) {
                    //запись эффектов оружия
                    if (is_array(json_decode_nice($infoshop['effects']))) {
                        $PweaponEffect = array_merge($PweaponEffect, json_decode_nice($infoshop['effects']));
                    }
                }
            }
        }

        if ($PA['side'] == 1 || $PA['side'] == 0) {
            $command = 0;
        }
        if ($PA['side'] == 2 || $PA['side'] == 3) {
            $command = 1;
        }
        $mc->query("INSERT INTO`battle`"
                . "("
                . "`id`,"
                . "`Pname`,"
                . "`Pnamevs`,"
                . "`Pvsname`,"
                . "`level`,"
                . "`Pico`,"
                . "`Pflife`,"
                . "`Plife` ,"
                . "`Ptochnost`,"
                . "`Pblock`,"
                . "`Puron`,"
                . "`Pbronia`,"
                . "`Poglushenie`,"
                . "`Puvorot`,"
                . "`Pweaponico`,"
                . "`Pshieldnum`,"
                . "`Pshieldonoff`,"
                . "`Ptype`,"
                . "`Pvisible`,"
                . "`Mvisible`,"
                . "`Panimation`,"
                . "`Manimation`,"
                . "`Phod`,"
                . "`Phodtime`,"
                . "`Pauto`,"
                . "`PAlwaysEffect`,"
                . "`PeleksirVisible`,"
                . "`PweaponEffect`,"
                . "`PentityEffect`,"
                . "`MentityEffect`,"
                . "`super`,"
                . "`Mid`,"
                . "`location`,"
                . "`type_battle`,"
                . "`battle_id`,"
                . "`battle_start_time`,"
                . "`command`,"
                . "`lost_mob_id`,"
                . "`player_activ`,"
                . "`end_battle`,"
                . "`counter`,"
                . "`stil`"
                . ")VALUES("
                . "NULL,"
                . "'" . $PA['name'] . "',"
                . "'" . $PA['name'] . "',"
                . "'" . $MA['name'] . "',"
                . "'" . $PA['level'] . "',"
                . "'" . $PA['side'] . "',"
                . "'" . $arr['max_health'] . "',"
                . "'" . $PA['temp_health'] . "',"
                . "'" . $arr['toch'] . "',"
                . "'" . $arr['block'] . "',"
                . "'" . $arr['strength'] . "',"
                . "'" . $arr['bron'] . "',"
                . "'" . $arr['kd'] . "',"
                . "'" . $arr['lov'] . "',"
                . "'" . $PA['weaponico'] . "',"
                . "'" . $PA['Pshieldnum'] . "',"
                . "'0',"
                . "'0',"
                . "'1',"
                . "'1',"
                . "'0',"
                . "'0',"
                . "'1',"
                . "'" . time() . "',"
                . "'0',"
                . "'[]',"
                . "'1',"
                . "'" . json_encode($PweaponEffect) . "',"
                . "'[]',"
                . "'[]',"
                . "'" . $PA['superudar'] . "',"
                . "'" . $PA['id'] . "',"
                . "'23',"
                . "'0',"
                . "'" . $battle_id . "',"
                . "'" . $battle_start_time . "',"
                . "'" . $command . "',"
                . "'0',"
                . "'1',"
                . "'0',"
                . "'0',"
                . "'" . $PA['stil'] . "'"
                . ")");
        for ($i = 0; $i < count($list_mob); $i++) {
            $MA = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . $list_mob[$i] . "'")->fetch_array(MYSQLI_ASSOC);
            //записываем эффекты противнику
            $MPweaponEffect = json_decode_nice($MA['effects']);
            $mc->query("INSERT INTO`battle`"
                    . "("
                    . "`id`,"
                    . "`Pname`,"
                    . "`Pnamevs`,"
                    . "`Pvsname`,"
                    . "`level`,"
                    . "`Pico`,"
                    . "`Pflife`,"
                    . "`Plife`,"
                    . "`Ptochnost`,"
                    . "`Pblock`,"
                    . "`Puron`,"
                    . "`Pbronia`,"
                    . "`Poglushenie`,"
                    . "`Puvorot`,"
                    . "`Pweaponico`,"
                    . "`Pshieldnum`,"
                    . "`Pshieldonoff`,"
                    . "`Ptype`,"
                    . "`Pvisible`,"
                    . "`Mvisible`,"
                    . "`Panimation`,"
                    . "`Manimation`,"
                    . "`Phod`,"
                    . "`Phodtime`,"
                    . "`Pauto`,"
                    . "`PAlwaysEffect`,"
                    . "`PeleksirVisible`,"
                    . "`PweaponEffect`,"
                    . "`PentityEffect`,"
                    . "`MentityEffect`,"
                    . "`super`,"
                    . "`Mid`,"
                    . "`location`,"
                    . "`type_battle`,"
                    . "`battle_id`,"
                    . "`battle_start_time`,"
                    . "`command`,"
                    . "`lost_mob_id`,"
                    . "`player_activ`,"
                    . "`end_battle`,"
                    . "`counter`,"
                    . "`stil`"
                    . ")VALUES("
                    . "NULL,"
                    . "'" . $MA['name'] . "',"
                    . "'',"
                    . "'',"
                    . "'" . $MA['level'] . "',"
                    . "'" . $MA['iconid'] . "',"
                    . "'" . $MA['max_hp'] . "',"
                    . "'" . $MA['hp'] . "',"
                    . "'" . $MA['toch'] . "',"
                    . "'" . $MA['block'] . "',"
                    . "'" . $MA['damage'] . "',"
                    . "'" . $MA['bron'] . "',"
                    . "'" . $MA['kd'] . "',"
                    . "'" . $MA['lov'] . "',"
                    . "'0',"
                    . "'20',"
                    . "'0',"
                    . "'1',"
                    . "'1',"
                    . "'1',"
                    . "'0',"
                    . "'0',"
                    . "'0',"
                    . "'" . time() . "',"
                    . "'1',"
                    . "'[]',"
                    . "'1',"
                    . "'" . json_encode($MPweaponEffect) . "',"
                    . "'[]',"
                    . "'[]',"
                    . "'',"
                    . "'" . $MA['id'] . "',"
                    . "'23',"
                    . "'0',"
                    . "'" . $battle_id . "',"
                    . "'" . $battle_start_time . "',"
                    . "'2',"
                    . "'0',"
                    . "'1',"
                    . "'0',"
                    . "'0',"
                    . "'" . $MA['stil'] . "'"
                    . ")");
        }
    }
}

//**********ВИЗУАЛИЗАЦИЯ часть квеста ,номер порядковый , тип (активный 0,доступный 1 , завершенный 2) , варианты окон (0 начальное ,1 в случае провала , 2 в случае отказа)
function visualQuests($arr, $num, $type, $arrRep) {
    global $mc;
    global $user;
    global $sluch;
    require_once '../system/header.php';
    //определяем префикс
    $pre = ($sluch == 0) ? "" : (($sluch == 1) ? "proval_" : (($sluch == 2) ? "otkaz_" : ""));
    //получаем параметры локации для фона
    $location = $mc->query("SELECT * FROM `location` WHERE `id`='" . $user['location'] . "'")->fetch_array(MYSQLI_ASSOC);
    //закрыть кв если текст отказа пуст
    if ($sluch == 2 && $arr[$pre . 'msg_text'] == "") {
        ?>
        <script>/*nextshowcontemt*/showContent('/main.php');</script>
        <?php
        exit(0);
    }
//если иконка и свиток
    if ($arr[$pre . 'type_c'] >= 0 && $arr[$pre . 'type_c'] <= 2) {
        ?>
        <div class="ramka_dvig">
            <div class="location">
                <div class="location<?= $location['IdImage']; ?>"></div>
                <div class="locpers locpers<?= $arr[$pre . 'img_id']; ?>"></div>
                <font class="snowConteiner" style="pointer-events: all;position: absolute;left: 0;top: 0;z-index: -1;">
                </font>
                <div onclick="<?= $user['access'] > 2 ? "showContent('/main?snow_set=" . $location['snow'] . "')" : ""; ?>" style="background-image: url(img/location/GOL_app_location6.png);background-repeat: no-repeat;background-size: cover;">
                    <img onload="<?= $location['snow'] == 1 ? "snowAppend($('.snowConteiner'));" : ""; ?>" src="img/location/GOL_app_location6.png" style="width: 100%; opacity: 0;">
                </div>
                <div class="perg">
                    <img src="img/location/GOL_app_perg.png" style="width:50%;opacity: 1;">
                    <span class="perg_text">
                        <?= $location['Name']; ?> 
                        <?= $location['snow'] == 1 && $user['access'] > 2 ? " ❄" : ""; ?>
                    </span>
                </div>
            </div>
        </div>
        <table class="msgQuests" style="margin: auto;border-spacing: 0;width: 100%;">
            <tr>
                <td class="ptb_1l" ></td>
                <td class="ptb_1c" ></td>
                <td class="ptb_1r" ></td>
            </tr>

            <tr>
                <td class="ptb_2l"></td>
                <td class="ptb_2c" style="text-align: center;">
                    <div class="text_msg_quest"><?= urldecode($arr[$pre . 'msg_text']); ?></div>
                    <br>
                    <table style="margin: auto;width: 100%;height: 100px;">
                        <tr>
                            <?php if ($arr[$pre . 'type_c'] == 0) { ?>
                                <td><div class="btnyes btn" onclick="showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=0&sluch=<?= $sluch; ?>');"></div></td>
                                <td><div class="btnno btn" onclick="showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=1&sluch=<?= $sluch; ?>');"></div></td>
                            <?php } else if ($arr[$pre . 'type_c'] == 1) { ?>
                                <td><div class="btnyes btn" onclick="showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=2&sluch=<?= $sluch; ?>');"></div></td>
                            <?php } else if ($arr[$pre . 'type_c'] == 2) { ?>
                                <td><div class="btnno btn" onclick="showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=3&sluch=<?= $sluch; ?>');"></div></td>
                            <?php } ?>
                        </tr>
                    </table>

                </td>
                <td class="ptb_2r"></td>
            <tr>
                <td class="ptb_3l"></td>
                <td class="ptb_3c"></td>
                <td class="ptb_3r"></td>
            </tr>
        </table>
        <?php
    } else if ($arr[$pre . 'type_c'] >= 3 && $arr[$pre . 'type_c'] <= 5) {
        ?>
        <div class="msgQuests" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;">
            <table style="margin: auto;width: 100%;max-width: 480px;height: 100%">
                <tr>
                    <td style="vertical-align: middle;text-align: center;">
                        <div style="box-shadow: 0 0 10px rgba(0,0,0,0.7);width:80%;margin: auto;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                            <div class="text_msg_quest" style="padding: 6px;padding-bottom: 12px;"><?= urldecode($arr[$pre . 'msg_text']); ?></div>
                            <table style="width: 100%;margin: auto;height: 50px;">
                                <tr>
                                    <?php if ($arr[$pre . 'type_c'] == 3) { ?>
                                        <td><div class="btnyes btn" onclick="hideMsgQuests();showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=0&sluch=<?= $sluch; ?>');"></div></td>
                                        <td><div class="btnno btn" onclick="hideMsgQuests();showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=1&sluch=<?= $sluch; ?>');"></div></td>
                                    <?php } else if ($arr[$pre . 'type_c'] == 4) { ?>
                                        <td><div class="button_alt_01 btn" onclick="hideMsgQuests();showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=2&sluch=<?= $sluch; ?>');" style="margin: auto;width:85%">Согласиться</div></td>
                                    <?php } else if ($arr[$pre . 'type_c'] == 5) { ?>
                                        <td><div class="button_alt_01" onclick="hideMsgQuests();showContent('/quests/quests.php?num=<?= $num; ?>&pos=<?= $type; ?>&otvet=3&sluch=<?= $sluch; ?>');" style="margin: auto;width:85%">Ок</div></td>
                                    <?php } ?>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <script type='text/javascript'>
                $('.msgQuests:eq(-1)').animate({'opacity': '1'}, 500);
                if (typeof (hideMsgQuests) != "function") {
                    var control = 0;
                    hideMsgQuests = function () {
                        if (control == 0) {
                            $('.msgQuests:eq(-1)').animate({'opacity': '0'}, 500);
                            MyLib.setTimeid[200] = setTimeout(function () {
                                control = 1;
                                $('.msgQuests:eq(-1)').remove();
                                $('.msgQuests:eq(-1)').animate({'opacity': '1'}, 500);
                                control = 0;
                            }, 600);
                        }
                    };
                }
            </script>
        </div>
        <?php
    } else {
        ?>
        <script>/*nextshowcontemt*/showContent("/main.php");</script>
        <?php
        exit(0);
    }
    //ЗАМЕНА ТЕКСТА ПО МАСКЕ
    ?>
    <script type='text/javascript'>
        var arrBuff = <?= json_encode($arrRep); ?>;
        $('.text_msg_quest:eq(-1)').html($('.text_msg_quest:eq(-1)').html().replace(/%time%/, arrBuff['time']));
        $('.text_msg_quest:eq(-1)').html($('.text_msg_quest:eq(-1)').html().replace(/%duels%/, arrBuff['duels']));
        for (var i = 0; i < arrBuff['drop'].length; i++) {
            $('.text_msg_quest:eq(-1)').html($('.text_msg_quest:eq(-1)').html().replace('%drop' + arrBuff['drop'][i][0] + '%', arrBuff['drop'][i][1]));
        }
        for (var i = 0; i < arrBuff['shop'].length; i++) {
            $('.text_msg_quest:eq(-1)').html($('.text_msg_quest:eq(-1)').html().replace('%shop' + arrBuff['shop'][i][0] + '%', arrBuff['shop'][i][1]));
        }

    </script>  
    <?php
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json);
}

function genRandArrVal($array, $a) {
    if ($a > 0 && count($array) > 0) {
        $newarr = [];
        if ($a > count($array)) {
            $a = count($array);
        }
        $keys = array_rand($array, $a);
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        for ($i = 0; $i < count($keys); $i++) {
            $newarr[] = $array[$keys[$i]];
        }
        return $newarr;
    } else {
        return $array;
    }
}
