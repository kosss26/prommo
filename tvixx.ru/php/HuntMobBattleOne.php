<?php

require '../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$Check = 0;
$Checkrow = 0;
$Mobid;
$Mauto = 1;
$battle_id = rand(0, time()) . rand(0, time()) . rand(0, time()) . rand(0, time());
$battle_start_time = time();
$command = 0;
$arr = [[], []];
//проверяем данные
if (!empty($_POST["Login"]) && !empty($_POST["Password"])) {
    //если есть айди мобов
    if (isset($_POST["Mobid"])) {
        $Mobid = $_POST["Mobid"];
        //или нету
    } else if (isset($_POST["Configmob"])) {
        $Mobid = 1;
    }
    //получаем данные
    $LOGIN = urldecode($_POST["Login"]);
    $PASS = $_POST["Password"];
    //получаем результат персонажа
    $Pres = $mc->query("SELECT * FROM `users` WHERE `login` = '$LOGIN' AND `password` = '$PASS'");
    //если что то получили
    if ($Pres->num_rows) {
        //получаем параметры из результата
        $PA = $Pres->fetch_array(MYSQLI_ASSOC);
        $PA['weaponico'] = 0;
        $PA['Pshieldnum'] = 0;
        $PweaponEffect = array();
        if ($MA = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . json_decode($PA['huntList'])[$Mobid] . "'")->fetch_array(MYSQLI_ASSOC)) {
            //заносим список айди мобов в локе герою
            $mc->query("UPDATE `users` SET `huntList` = '' WHERE `users`.`id` = '" . $PA['id'] . "'");
        } else {
            echo "0";
            exit(0);
        }
        if ($MA['hp']<=0) {
            echo "2";
            exit(0);
        } 
        if ($PA['vinos_m']<=0) {
            echo "98";
            exit(0);
        }
        if ($PA['temp_health']<=0) {
            echo "97";
            exit(0);
        } 
        $MPweaponEffect = array();
        //если герой в бою и живой то завершим скрипт и отправим ответ 1 (герой уже сражается)
        $Checkres = $mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $PA['id'] . "' AND `Ptype`='0' AND `player_activ`='1' AND `end_battle`='0'");
        if ($Checkres->num_rows) {
            echo "1";
            exit(0);
        } else {
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
                    . "'" . $PA['location'] . "',"
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
                    . "'" . $PA['location'] . "',"
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
            echo "99";
            exit(0);
        }
    }
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json);
}
