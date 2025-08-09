<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connect.php';

/* ВЫХОД ИЗ ИГРЫ */
if (isset($_GET['exit_game'])) {
    setcookie("login", "", time() - 86400 * 31);
    setcookie("password", "", time() - 86400 * 31);
    $user = NULL;
}
/* Навигация */
if (isset($user) && $user['game_ban'] > 0) {
    setcookie("login", "", time() - 86400 * 31);
    setcookie("password", "", time() - 86400 * 31);
    ?><script>
        /*nextshowcontemt*/showContent('/index.php?msg=' + encodeURIComponent('персонаж заблокирован'));
    </script>
    <?php
    exit(0);
}
if (isset($user)) {
    if ($user['login'] != $login or $user['password'] != $password) {
        setcookie('login', '', time() - 86400 * 31);
        setcookie('password', '', time() - 86400 * 31);
    }
    $query = 'SELECT*FROM users WHERE login="' . $login . '" AND banned !=1';
    $ban = $mc->query('SELECT COUNT(0),`time`,`msgid` FROM `chatban` WHERE `user` = "' . $user['id'] . '"')->fetch_array(MYSQLI_ASSOC);
    if ($ban['COUNT(0)'] != 0) {
        if ($ban['time'] <= time()) {
            $mc->query('DELETE FROM `ban` WHERE `user` = "' . $user['id'] . '"');
        }
    }

    $users = $mc->query("SELECT * FROM `users` WHERE `login` = '" . $login . "' and `password`='" . $password . "' LIMIT 1")->fetch_assoc();

    if (isset($user['id']) && $users['login'] != $login or $users['password'] != $password) {
        setcookie('login', '', time() - 86400 * 31);
        setcookie('password', '', time() - 86400 * 31);
    }
}

function ico($dir, $file) {
    $img = '<img src="/images/' . $dir . '/' . $file . '" width="16px" alt="*" />';
    return $img;
}

function error($sms) {
    ?>
    <script>/*nextshowcontemt*/showContent("/main?0066");</script>
    <?php
    exit(0);
}

/* Вывод времени */

function noauth() {
    global $user;
    if (isset($user['id']) && !isset($_GET['exit_game'])) {
        ?>
        <script>/*nextshowcontemt*/showContent("/main.php?136.132423");</script>
        <?php
        exit(0);
    }
}

function auth() {
    global $user;
    if (!isset($user['id']) && !isset($_GET['exit_game'])) {
        ?>
        <script>/*nextshowcontemt*/showContent("/?002");</script>
        <?php
        exit(0);
    }
}

function requestModer() {
    global $request_access;
    if ($request_access == true) {
        ?>
        <script>/*nextshowcontemt*/showContent("/request_moder.php");</script>
        <?php
        exit(0);
    }
}

function norequestModer() {
    global $request_access;
    if ($request_access == false) {
        ?>
        <script>/*nextshowcontemt*/showContent("/main?003");</script>
        <?php
        exit(0);
    }
}

function access($access = 0) {
    global $user;
    if ($user['access'] < $access) {
        ?>
        <script>/*nextshowcontemt*/showContent("/main?005");</script>
        <?php
        exit(0);
    }
}

if (isset($_GET['msg'])) {
    message($_GET['msg']);
}

function message($text) {
    ?>
    <div class="msg" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;">
        <table style="margin: auto;width: 240px;height: 100%">
            <tr>
                <td style="vertical-align: middle;text-align: center;">
                    <div style="width:100%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                        <br>
                        <div class="text_msg"><?= urldecode($text); ?></div>
                        <br>
                        <div onclick="hideMsg111();" class="button_alt_01" style="margin: auto;" id="bt1t" >Ок</div>
                        <br>
                    </div>
                </td>
            </tr>
        </table>
        <script type='text/javascript'>
            $('.msg:eq(-1)').animate({'opacity': '1'}, 500);
            var control = 0;
            if (typeof (hideMsg111) !== "function") {
                hideMsg111 = function () {
                    if (control === 0) {
                        $('.msg:eq(-1)').animate({'opacity': '0'}, 500);
                        MyLib.setTimeid[200] = setTimeout(function () {
                            control = 1;
                            $('.msg:eq(-1)').remove();
                            $('.msg:eq(-1)').animate({'opacity': '1'}, 500);
                            control = 0;
                        }, 600);
                    }
                };
            }
        </script>
    </div>
    <?php
}

function message_yn($text, $btny, $btnn, $namea, $nameb) {
    if (isset($namea) == "") {
        $namea = "Принять";
    }
    if (isset($nameb) == "") {
        $nameb = "Отклонить";
    }
    ?>
    <div class="msg" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;">
        <table style="margin: auto;width: 240px;height: 100%">
            <tr>
                <td style="vertical-align: middle;text-align: center;">
                    <div style="width:100%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                        <div class="text_msg" style="margin: 10px;"><?= urldecode($text); ?></div>
                        <div class="button_alt_01" onclick="hideMsg222();showContent('<?= urldecode($btny); ?>');" style="margin: auto;margin-bottom: 5px;" ><?= urldecode($namea); ?></div>
                        <div class="button_alt_01" onclick="hideMsg222();showContent('<?= urldecode($btnn); ?>');" style="margin: auto;margin-bottom: 5px;"><?= urldecode($nameb); ?></div>
                    </div>
                </td>
            </tr>
        </table>
        <script type='text/javascript'>
            $('.msg:eq(-1)').animate({'opacity': '1'}, 500);
            if (typeof (hideMsg222) !== "function") {
                var control = 0;
                hideMsg222 = function () {
                    if (control === 0) {
                        $('.msg:eq(-1)').animate({'opacity': '0'}, 500);
                        MyLib.setTimeid[200] = setTimeout(function () {
                            control = 1;
                            console.log(38472378487);
                            $('.msg:eq(-1)').remove();
                            $('.msg:eq(-1)').animate({'opacity': '1'}, 500);
                            control = 0;
                        }, 600);
                    }
                };
            }
        </script>
    </div>
    <?php
}

function GetLevel($exp) {
    $level = 0;
    if ($exp > 1000)
        $level = 1;
    if ($exp > 3000)
        $level = 2;
    if ($exp > 6000)
        $level = 3;
    if ($exp > 10000)
        $level = 4;
    if ($exp > 15000)
        $level = 5;
    if ($exp > 21000)
        $level = 6;
    if ($exp > 29000)
        $level = 7;
    if ($exp > 37000)
        $level = 8;
    if ($exp > 46000)
        $level = 9;
    if ($exp > 56000)
        $level = 10;
    if ($exp > 67000)
        $level = 11;
    if ($exp > 97000)
        $level = 12;
    if ($exp > 150000)
        $level = 13;
    return $level;
}

//тотемы
function setTotem() {
    $totemcost = [0, 500, 1000, 1500, 2000, 2500, 3000, 4000, 5000, 6000, 7000, 8000];
    //получить параметры пользователя
    global $user;
    global $mc;
    //проверить на пустоту параметры пользователя
    if (isset($user)) {
        //получение инфы клана
        if ($clan = $mc->query("SELECT * FROM `clan` WHERE `id` = '" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC)) {

            //проверяем содержание тотема в клане
            if ($clan['gold'] >= ($clan['totemtec'] * 10)) {
                //получение тотема 12 шт айди от 1085 до 1096
                //если герой глава клана то поставить ему максимальный тотем соответствующий лвлу
                if ($user['des'] == '3') {
                    //если уровень героя больше или равно тотему то установить тотем текущий клана
                    if ($user['level'] - 5 >= $clan['totemtec']) {
                        totemAdd($clan['totemtec']);
                        //или установить тотем соответствующий лвлу
                    } else if ($user['level'] - 5 <= $clan['totemtec']) {
                        totemAdd($user['level'] - 5);
                    }
                    //или посчитать тотем для героя в клане по рейтингу
                } else {
                    for ($i = count($totemcost) - 1; $i >= 0; $i--) {
                        if ($user['reit'] >= $totemcost[$i] && $i <= $user['level'] - 5 && $i <= $clan['totemtec']) {
                            totemAdd($i);
                            break;
                        }
                    }
                }
            } else {
                //запишем 0 тотем
                $mc->query("UPDATE `users` SET `totem` = '0' WHERE `users`.`id` = '" . $user['id'] . "'");
                //удалить все тотемы игрока
                $mc->query("DELETE FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' AND `id_shop` > '1084' AND `id_shop` < '1097'");
            }
        } else {
            //запишем 0 тотем
            $mc->query("UPDATE `users` SET `totem` = '0' WHERE `users`.`id` = '" . $user['id'] . "'");
            //удалить все тотемы игрока
            $mc->query("DELETE FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' AND `id_shop` > '1084' AND `id_shop` < '1097'");
        }
    }
}

function totemAdd($numtotem) {
    global $mc;
    //тотем 12 шт айди от 1085 до 1096
    $totem = [1085, 1086, 1087, 1088, 1089, 1090, 1091, 1092, 1093, 1094, 1095, 1096, 1096, 1096, 1096, 1096, 1096, 1096];
    //получить параметры пользователя
    global $user;
    //проверить на пустоту параметры пользователя
    if (isset($user)) {
        if ($numtotem < 0) {
            $numtotem = 0;
        }
        $checkTotem = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' AND `id_shop` > '1084' AND `id_shop` < '1097'")->num_rows;
        if ($user['totem'] != $numtotem || $checkTotem == 0) {
            //удалить все тотемы игрока
            $mc->query("DELETE FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' AND `id_shop` > '1084' AND `id_shop` < '1097'");
            //получаем параметры нового тотема
            if ($infoshop1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $totem[$numtotem] . "'")->fetch_array(MYSQLI_ASSOC)) {
                //запишем новый тотем
                $mc->query("UPDATE `users` SET `totem` = '$numtotem' WHERE `users`.`id` = '" . $user['id'] . "'");
                //одеваем новый
                $mc->query("INSERT INTO `userbag`("
                        . "`id_user`,"
                        . " `id_shop`,"
                        . " `id_punct`,"
                        . " `dress`,"
                        . " `iznos`,"
                        . " `time_end`,"
                        . " `koll`,"
                        . " `BattleFlag`"
                        . ") VALUES ("
                        . "'" . $user['id'] . "',"
                        . "'" . $infoshop1['id'] . "',"
                        . "'" . $infoshop1['id_punct'] . "',"
                        . "'1',"
                        . "'-1',"
                        . "'0',"
                        . "'-1',"
                        . "'" . $infoshop1['BattleFlag'] . "'"
                        . ")");
                //пересчитаем статы
                ?>
                <script>/*nextshowcontemt*/showContent('/main?msg=' + encodeURIComponent('Новый уровень тотема'));</script>
                <?php
                exit(0);
            }
        }
    }
}

if (isset($user)) {
    $dta = time();
    $ip = $_SERVER["REMOTE_ADDR"];
    $mc->query("UPDATE `users` SET `ip`='" . $ip . "',`online`='" . $dta . "' WHERE `id`='" . $user["id"] . "'");


    $profile = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
    $arrtablopit = $mc->query("SELECT * FROM exp ORDER BY `exp`.`lvl` DESC")->fetch_all(MYSQLI_ASSOC);

    /*
      health Здоровье
      toch точность
      strength урон
      lov уворот
      kd оглушение
      block блок
      bron

     */
    for ($i = 0; $i < count($arrtablopit); $i++) {
        If ($profile['exp'] >= $arrtablopit[$i]['exp'] && $profile['level'] < $arrtablopit[$i]['lvl']) {
            // Определяем суперудар в зависимости от уровня
            $newSuperUdar = $profile['superudar'];
            $superudarMessage = "";
            
            // Функция генерации случайного суперудара с заданной длиной
            $generateRandomSuperUdar = function($length) {
                $su = '';
                for ($j = 0; $j < $length; $j++) {
                    $su .= rand(1, 3);
                }
                return $su;
            };
            
            if ($arrtablopit[$i]['lvl'] == 5) {
                // На 5 уровне добавляем второй суперудар из 3 цифр
                $currentSU = $profile['superudar'];
                $newSU = $generateRandomSuperUdar(3);
                $newSuperUdar = $currentSU . ',' . $newSU;
                $superudarMessage = "Поздравляем! Вы открыли второй супер-удар!";
            } else if ($arrtablopit[$i]['lvl'] == 10) {
                // На 10 уровне добавляем третий суперудар из 4 цифр
                $currentSUs = explode(',', $profile['superudar']);
                if (count($currentSUs) >= 2) {
                    $newSU = $generateRandomSuperUdar(4);
                    $currentSUs[] = $newSU;
                    $newSuperUdar = implode(',', $currentSUs);
                    $superudarMessage = "Поздравляем! Вы открыли третий супер-удар!";
                }
            } else if ($arrtablopit[$i]['lvl'] == 15) {
                // На 15 уровне добавляем четвертый суперудар из 6 цифр
                $currentSUs = explode(',', $profile['superudar']);
                if (count($currentSUs) >= 3) {
                    $newSU = $generateRandomSuperUdar(6);
                    $currentSUs[] = $newSU;
                    $newSuperUdar = implode(',', $currentSUs);
                    $superudarMessage = "Поздравляем! Вы открыли четвертый супер-удар!";
                }
            }
            
            // Если есть сообщение о суперударе, отправляем его
            if (!empty($superudarMessage)) {
                $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','" . $superudarMessage . "','" . time() . "','sys')");
            }
            
            $mc->query("UPDATE `users` SET "
                    . "`level` = '" . $arrtablopit[$i]['lvl'] . "',"
                    . "`slava` = `slava`+'" . ($arrtablopit[$i]['lvl'] * 25) . "',"
                    . "`health` = '" . (10 + (5 * $arrtablopit[$i]['lvl'])) . "',"
                    . "`strength` = '" . (1 + (2 * $arrtablopit[$i]['lvl']) - 2) . "',"
                    . "`toch` = '" . (8 + (2 * $arrtablopit[$i]['lvl']) - 2) . "',"
                    . "`bron` = '" . (0 + (2 * $arrtablopit[$i]['lvl']) - 2) . "',"
                    . "`lov` = '" . (3 + (2 * $arrtablopit[$i]['lvl']) - 2) . "',"
                    . "`kd` = '" . (2 + (2 * $arrtablopit[$i]['lvl']) - 2) . "',"
                    . "`block` = '" . (0 + (2 * $arrtablopit[$i]['lvl']) - 2) . "',"
                    . "`superudar` = '" . $newSuperUdar . "'"
                    . " WHERE `users`.`id` = '" . $user['id'] . "'");
            if ($arrtablopit[$i]['lvl'] >= 10) {
                $chatmsg = "<font color=\\'#0033cc\\'>" . $profile['name'] . " достиг " . $arrtablopit[$i]['lvl'] . " уровня!</font>";
                $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Лвл ап','','0', '" . $chatmsg . "','','','' )");
                $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Лвл ап','','1', '" . $chatmsg . "','','','' )");
            }

            //проверяем ссылку реф
            $ref = $profile['ref'];
            if ($ref > 0) {
                //если герой есть с таким номером реф
                if ($mc->query("SELECT * FROM `users` WHERE `myref` = '$ref'")->num_rows > 0) {
                    //здесь пропишем реф бонусы
                    $ref_exp = 0;
                    $ref_slava = 0;
                    $ref_clan_reit = 0;
                    $ref_platinum = 0;
                    //бонусы игроку
                    $bon_money = 0;
                    $bon_platina = 0;
                    if ($arrtablopit[$i]['lvl'] == 5) {
                        $ref_exp += 1;
                        $ref_slava += 1;
                        $ref_platinum += 1;
                        $bon_money = 100000;
                        //запишем зарегистрированному 10золота и сообщение
                        $mc->query("UPDATE `users` SET `money` = `money`+'100000' WHERE `id` = '" . $user['id'] . "' ");
                        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вы получили 10<img class=\"ico_head_all\" src=\"/images/icons/zoloto.png\">. От пригласившего вас игрока.','" . time() . "','ref')");
                    } else if ($arrtablopit[$i]['lvl'] == 10) {
                        $ref_exp += 1;
                        $ref_slava += 1;
                        $ref_platinum += 10;
                        $bon_money = 200000;
                        //запишем зарегистрированному 20золота и сообщение
                        $mc->query("UPDATE `users` SET `money` = `money`+'200000' WHERE `id` = '" . $user['id'] . "' ");
                        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вы получили 20<img class=\"ico_head_all\" src=\"/images/icons/zoloto.png\">. От пригласившего вас игрока.','" . time() . "','ref')");
                    } else if ($arrtablopit[$i]['lvl'] == 15) {
                        $ref_exp += 1;
                        $ref_slava += 1;
                        $ref_platinum += 25;
                        $bon_money = 40;
                        $bon_platina = 5;
                        //запишем зарегистрированному 40золота 5платин и сообщение
                        $mc->query("UPDATE `users` SET `money` = `money`+'400000',`platinum` = `platinum`+'5' WHERE `id` = '" . $user['id'] . "' ");
                        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вы получили 40<img class=\"ico_head_all\" src=\"/images/icons/zoloto.png\"> 5<img class=\"ico_head_all\" src=\"/images/icons/plata.png\">. От пригласившего вас игрока.','" . time() . "','ref')");
                    } else if ($arrtablopit[$i]['lvl'] > 1) {
                        $ref_exp += 1;
                        $ref_slava += 1;
                    }

                    //а пригласившему 1 славы и 1 опыта в ref_bonus
                    if ($mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num` = '$ref'")->num_rows > 0) {
                        //обновить
                        $mc->query("UPDATE `ref_bonus` SET "
                                . "`exp`=`exp`+'$ref_exp',"
                                . "`slava`=`slava`+'$ref_slava',"
                                . "`clan_reit`=`clan_reit`+'$ref_clan_reit',"
                                . "`platinum`=`platinum`+'$ref_platinum'"
                                . "WHERE `ref_num` = '$ref'");
                    } else {
                        //или создать если записи бонусов нет
                        $mc->query("INSERT INTO `ref_bonus` ("
                                . "`id`,"
                                . " `ref_num`,"
                                . " `exp`,"
                                . " `slava`,"
                                . " `clan_reit`,"
                                . " `platinum`"
                                . ") VALUES ("
                                . "NULL,"
                                . " '$ref',"
                                . " '$ref_exp',"
                                . " '$ref_slava',"
                                . " '$ref_clan_reit',"
                                . " '$ref_platinum'"
                                . ")");
                    }
                }
            }
            ?><script>/*nextshowcontemt*/showContent('/newlevel.php');</script><?php
            exit(0);
        }
    }
//сброс Према после окончания срока
    if ($user['prem'] > 0) {
        if ($user['prem_t'] <= time()) {
            $mc->query("UPDATE `users` SET `prem` = '0',`prem_t` = '0' WHERE `id` = '" . $user['id'] . "'");
        }
    }
    if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' AND `dress` = '1' AND `id_punct` < 9 AND `iznos` = '0'")->num_rows > 0) {
        $mc->query("UPDATE `userbag` SET `dress` = '0' WHERE `id_user` = '" . $user['id'] . "' AND `id_punct` < 9 AND `iznos` = '0'");
        ?><script>/*nextshowcontemt*/showContent('/main.php?msg=Ваши вещи в ужасном состоянии');</script><?php
        exit(0);
    }
}
//проверка побед монстров, поражений и побед игроков ,если меньше 0 выдать штраф и обнулить
//мобы победа

/* if($user['pobedmonser'] < 0){
  if($mc->query("UPDATE `users` SET `pobedmonser` = '0' WHERE `id` = '".$user['id']."'")){
  message("Победы Монстров бвли обнулены а также вы получили <b> Штрафные санкции</b>");
  //shop_buy(1672, 'y');
  }
  }
  //мобы поражения
  if($user['losemonser'] < 0){
  if($mc->query("UPDATE `users` SET `losemonser` = '0' WHERE `id` = '".$user['id']."'")){
  message("Поражения Монстров были обнулены а также вы получили <b> Штрафные санкции</b>");
  //shop_buy(1672, 'y');
  }
  }
  //игроки
  if($user['pobedigroki'] < 0){
  //require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_kupit.php';
  //require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_kupit.php';
  if($mc->query("UPDATE `users` SET `pobedigroki` = '0' WHERE `id` = '".$user['id']."'")){
  message("Победы игроков были обнулены а также вы получили <b> Штрафные санкции</b>");
  //shop_buy(1672, 'y');
  }
  } */

// Функция добавления игрока в бой
function hero1_add($command, $user, $enemy_name, $battle_id, $battle_start_time, $type) {
    global $mc;
    
    // Получаем параметры игрока с учетом экипировки
    $stats = getUserBattleStats($user['id']);
    
    return $mc->query("INSERT INTO `battle` (
        `id`, `Pname`, `Pnamevs`, `Pvsname`, `level`, `Pico`, 
        `Pflife`, `Plife`, `Ptochnost`, `Pblock`, `Puron`, 
        `Pbronia`, `Poglushenie`, `Puvorot`, `Pweaponico`, 
        `Pshieldnum`, `Pshieldonoff`, `Ptype`, `Pvisible`, 
        `Mvisible`, `Panimation`, `Manimation`, `Phod`, 
        `Phodtime`, `Pauto`, `PAlwaysEffect`, `PeleksirVisible`, 
        `PweaponEffect`, `PentityEffect`, `MentityEffect`, 
        `super`, `Mid`, `location`, `type_battle`, 
        `battle_id`, `battle_start_time`, `command`, 
        `lost_mob_id`, `player_activ`, `end_battle`, `counter`
    ) VALUES (
        NULL, '" . $user['name'] . "', '', '" . $enemy_name . "', 
        '" . $user['level'] . "', '" . $user['side'] . "', 
        '" . $stats['max_health'] . "', '" . $stats['max_health'] . "', 
        '" . $stats['toch'] . "', '" . $stats['block'] . "', 
        '" . $stats['strength'] . "', '" . $stats['bron'] . "', 
        '" . $stats['kd'] . "', '" . $stats['lov'] . "', 
        '" . $stats['weaponico'] . "', '" . $stats['shield'] . "', 
        '0', '0', '1', '1', '0', '0', '0', 
        '" . time() . "', '1', '[]', '1', '[]', '[]', '[]', 
        '" . $stats['super'] . "', '" . $user['id'] . "', '0', 
        '" . $type . "', '" . $battle_id . "', 
        '" . $battle_start_time . "', '" . $command . "', 
        '0', '1', '0', '0'
    )");
}

// Функция добавления бота в бой
function bot_add($name, $command, $side, $stil, $level, $battle_id, $battle_start_time, $type) {
    global $mc;
    
    // Генерируем параметры бота на основе уровня
    $stats = getBotStats($level);
    
    return $mc->query("INSERT INTO `battle` (
        `id`, `Pname`, `Pnamevs`, `Pvsname`, `level`, `Pico`, 
        `Pflife`, `Plife`, `Ptochnost`, `Pblock`, `Puron`, 
        `Pbronia`, `Poglushenie`, `Puvorot`, `Pweaponico`, 
        `Pshieldnum`, `Pshieldonoff`, `Ptype`, `Pvisible`, 
        `Mvisible`, `Panimation`, `Manimation`, `Phod`, 
        `Phodtime`, `Pauto`, `PAlwaysEffect`, `PeleksirVisible`, 
        `PweaponEffect`, `PentityEffect`, `MentityEffect`, 
        `super`, `Mid`, `location`, `type_battle`, 
        `battle_id`, `battle_start_time`, `command`, 
        `lost_mob_id`, `player_activ`, `end_battle`, `counter`, `stil`
    ) VALUES (
        NULL, '" . $name . "', '', '', '" . $level . "', 
        '" . $side . "', '" . $stats['max_health'] . "', 
        '" . $stats['max_health'] . "', '" . $stats['toch'] . "', 
        '" . $stats['block'] . "', '" . $stats['strength'] . "', 
        '" . $stats['bron'] . "', '" . $stats['kd'] . "', 
        '" . $stats['lov'] . "', '0', '0', '0', '0', '1', '1', 
        '0', '0', '0', '" . time() . "', '1', '[]', '1', '[]', 
        '[]', '[]', '" . $stats['super'] . "', '-1', '0', 
        '" . $type . "', '" . $battle_id . "', 
        '" . $battle_start_time . "', '" . $command . "', '0', 
        '1', '0', '0', '" . $stil . "'
    )");
}

// Функция получения боевых параметров игрока с учетом экипировки
function getUserBattleStats($user_id) {
    global $mc;
    $stats = [
        'max_health' => 0,
        'strength' => 0,
        'toch' => 0,
        'lov' => 0,
        'kd' => 0,
        'block' => 0,
        'bron' => 0,
        'weaponico' => 0,
        'shield' => 0,
        'super' => '0'
    ];
    
    // Получаем базовые параметры игрока
    $user = $mc->query("SELECT * FROM `users` WHERE `id` = '$user_id'")->fetch_array(MYSQLI_ASSOC);
    if ($user) {
        $stats['max_health'] = $user['health'];
        $stats['strength'] = $user['strength'];
        $stats['toch'] = $user['toch'];
        $stats['lov'] = $user['lov'];
        $stats['kd'] = $user['kd'];
        $stats['block'] = $user['block'];
        $stats['bron'] = $user['bron'];
    }
    
    return $stats;
}

// Функция генерации параметров бота
function getBotStats($level) {
    return [
        'max_health' => 10 + (5 * $level),
        'strength' => 1 + (2 * $level) - 2,
        'toch' => 8 + (2 * $level) - 2,
        'lov' => 3 + (2 * $level) - 2,
        'kd' => 2 + (2 * $level) - 2,
        'block' => (2 * $level) - 2,
        'bron' => (2 * $level) - 2,
        'super' => '0'
    ];
}

// Взять квест
function takeQuest($user_id, $quest_id) {
    global $mc;
    
    // Проверяем, можно ли взять квест
    $quest = $mc->query("SELECT * FROM quests WHERE id = '$quest_id'")->fetch_assoc();
    if (!$quest) return false;
    
    // Проверяем уровень
    if ($quest['min_level'] > $user['level']) return false;
    
    // Проверяем, не взят ли уже квест
    $check = $mc->query("SELECT id FROM quest_progress WHERE id_user = '$user_id' AND id_quests = '$quest_id' AND status = 'ACTIVE'")->fetch_assoc();
    if ($check) return false;
    
    // Проверяем кулдаун для повторяемых квестов
    if ($quest['is_repeatable']) {
        $last_completion = $mc->query("SELECT time_next FROM quest_progress 
            WHERE id_user = '$user_id' AND id_quests = '$quest_id' 
            ORDER BY time_end DESC LIMIT 1")->fetch_assoc();
            
        if ($last_completion && strtotime($last_completion['time_next']) > time()) {
            return false;
        }
    }
    
    // Добавляем запись о взятом квесте
    $mc->query("INSERT INTO quest_progress SET 
        id_user = '$user_id',
        id_quests = '$quest_id',
        current_stage = 1,
        status = 'ACTIVE',
        time_start = NOW()");
        
    return true;
}

// Обновить прогресс квеста
function updateQuestProgress($user_id, $quest_id, $progress_data) {
    global $mc;
    
    return $mc->query("UPDATE quest_progress SET 
        progress_data = '" . json_encode($progress_data) . "'
        WHERE id_user = '$user_id' AND id_quests = '$quest_id' AND status = 'ACTIVE'");
}

// Завершить квест
function completeQuest($user_id, $quest_id) {
    global $mc;
    
    $quest = $mc->query("SELECT * FROM quests WHERE id = '$quest_id'")->fetch_assoc();
    
    // Устанавливаем время следующего взятия для повторяемых квестов
    $time_next = $quest['is_repeatable'] ? 
        "DATE_ADD(NOW(), INTERVAL {$quest['cooldown_hours']} HOUR)" : 
        "NULL";
    
    return $mc->query("UPDATE quest_progress SET 
        status = 'COMPLETED',
        time_end = NOW(),
        time_next = $time_next
        WHERE id_user = '$user_id' AND id_quests = '$quest_id' AND status = 'ACTIVE'");
}

// Провалить квест
function failQuest($user_id, $quest_id) {
    global $mc;
    
    return $mc->query("UPDATE quest_progress SET 
        status = 'FAILED',
        time_end = NOW()
        WHERE id_user = '$user_id' AND id_quests = '$quest_id' AND status = 'ACTIVE'");
}
?>
