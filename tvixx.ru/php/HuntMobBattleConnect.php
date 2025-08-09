<?php

require '../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$Check = 0;
$Checkrow = 0;
$Btid;
$battle_start_time = time();
$command = 0;
$arr = [[], []];
//разница в уровне
$level_incdec = 2;
//проверяем данные
if (!empty($_POST["Login"]) && !empty($_POST["Password"])) {
    //если есть айди мобов
    if (isset($_POST["Btid"])) {
        $Btid = $_POST["Btid"];
        //или нету
    } else {
        exit(0);
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
        if ($PA['vinos_m'] <= 0) {
            echo "98";
            exit(0);
        }
        if ($PA['temp_health'] <= 0) {
            echo "97";
            exit(0);
        }
        $PweaponEffect = array();
        //если Вы уже сражаетесь в этой битве .
        $Checkres = $mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $PA['id'] . "' AND `battle_id`='$Btid'||`Mid`='" . $PA['id'] . "' AND `Ptype`='0' AND `player_activ`='1' AND `end_battle`='0'");
        if ($Checkres->num_rows) {
            echo "1";
            exit(0);
        } else {
            if ($PA['side'] == 1 || $PA['side'] == 0) {
                $command = 0;
            }
            if ($PA['side'] == 2 || $PA['side'] == 3) {
                $command = 1;
            }
            //получаем команду игрока
            $lvlstartures = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='$Btid' && `Pnamevs`!='' ");
            if ($lvlstartures->num_rows > 0) {
                //определяем минимальный и максимальный лвл игрока 
                $minlvl = $user['level'] - $level_incdec; //$user['level'] - 1;
                $maxlvl = $user['level'] + $level_incdec; //$user['level'] + 1;
                $lvlstartu = $lvlstartures->fetch_array(MYSQLI_ASSOC);
                if ($lvlstartu['level'] < $minlvl || $lvlstartu['level'] > $maxlvl) {
                    echo "2";
                    exit(0);
                }
            }
            //получаем команду игрока
            $allures = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='$Btid'&&`command`='$command'&&`Plife`>'0'");
            $alluhp = 0;
            if ($allures->num_rows > 0) {
                $allu = $allures->fetch_all(MYSQLI_ASSOC);
                for ($i = 0; $i < count($allu); $i++) {
                    $alluhp += $allu[$i]['Plife'];
                }
            }
            //получаем всех игроков из другой команды и проверяем их общее хп
            $allmres = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='$Btid'&&`command`!='$command'&&`Plife`>'0'");
            $allmhp = 0;
            if ($allmres->num_rows > 0) {
                $allm = $allmres->fetch_all(MYSQLI_ASSOC);
                for ($i = 0; $i < count($allm); $i++) {
                    $allmhp += $allm[$i]['Plife'];
                }
            }
            if ($alluhp > ($allmhp / 2) || $PA['temp_health'] > ($allmhp / 2)) {
                echo "2";
                exit(0);
            }

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
                    . "'',"
                    . "'',"
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
                    . "'',"
                    . "'0',"
                    . "'" . $Btid . "',"
                    . "'" . $battle_start_time . "',"
                    . "'" . $command . "',"
                    . "'0',"
                    . "'1',"
                    . "'0',"
                    . "'0',"
                    . "'" . $PA['stil'] . "'"
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
