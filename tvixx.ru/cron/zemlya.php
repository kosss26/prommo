<?php

require_once 'bd.php';

if (isset($_GET['time'])) {
    if ($_GET['time'] == "1800") {
        //время 18:00 первый вжв (7)
        $ucharr = $mc->query("SELECT * FROM `huntb_list` WHERE `type` = 7 ORDER BY `location` ASC")->fetch_all(MYSQLI_ASSOC);

        ///Получаем отсортированный список, теперь пройдем цикл и знаем что к чему
        //$zem[1][1] = ""; [имялокации][Люди в локации] 
        $zem;
        $namezem;
        $namelocation = 0;
        $teclocation = 0;
        $teclocation2 = 0;
        $locazahvbd = "";
        for ($i = 0; $i < count($ucharr); $i++) {
            if ($namelocation != $ucharr[$i]['location']) {
                $teclocation += 1; //если лока сменилась новый массив
                $teclocation2 = 1;
                $namelocation = $ucharr[$i]['location'];
            }
            $zem[$teclocation][$teclocation2] = $ucharr[$i]['user_id'];
            $namezem[$teclocation] = $ucharr[$i]['location'];

            if ($locazahvbd == "") {
                $locazahvbd .= " `id` != " . $namezem[$teclocation];
            } else {
                $locazahvbd .= " AND `id` != " . $namezem[$teclocation];
            }
            // echo $ucharr[$i]['user_id']."<br>";
            $teclocation2 += 1;
        }


        $nextZahvattime2 = mktime(19, 50, 0, date("m"), date("d") + 2, date("Y"));
        $mc->query("UPDATE `location` SET `nextZahvat` = '" . $nextZahvattime2 . "' WHERE " . $locazahvbd . "");







        echo count($zem) . "///" . json_encode($zem) . "<br>";
        //массив есть, теперьдумаем, что делать
        for ($i = 1; $i < count($zem) + 1; $i++) {
            if (count($zem[$i]) > 1) {
                //если людей много
                $battle_id = rand(0, time()) . rand(0, time()) . rand(0, time());
                $battle_start_time = time();
                $namebattle = "Отбор (Земля)";
                for ($j = 1; $j < count($zem[$i]) + 1; $j++) {
                    $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $zem[$i][$j] . "'")->fetch_array(MYSQLI_ASSOC);
                    echo $j . "." . $users['name'] . " Драка: " . $zem[$i][$j] . " за локу :" . $namezem[$i] . "<br>";

                    if ($users['temp_health'] < 2) {
                        //echo "Ноль хп. нет";
                        $users['temp_health'] = 10;
                    }

                    hero_add($j, $namebattle, $users, $battle_id, $battle_start_time, 7);
                    echo $j . "." . $users['temp_health'] . "." . $users['name'] . " Драка: " . $zem[$i][$j] . " за локу :" . $namezem[$i] . "//" . $namebattle . "<br>";
                    $namebattle = "";
                }
            } else {

                echo "Один на локе: " . $zem[$i][1] . " за локу :" . $namezem[$i] . "<br>";
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $zem[$i][1] . "'")->fetch_array(MYSQLI_ASSOC);
                $nextZahvattime = mktime(19, 50, 0, date("m"), date("d"), date("Y"));
                $mc->query("UPDATE `location` SET `idNextClan` = '" . $users['id_clan'] . "', `nextZahvat`='" . $nextZahvattime . "' WHERE `id` = " . $namezem[$i] . "");
                $mc->query("DELETE FROM `huntb_list` WHERE `location` ='" . $namezem[$i] . "' AND `type`=7");
                //а теперь отсылаем всему клану инфу о том, что они идиоты
                $Nameloca = $mc->query("SELECT `Name` FROM `location` WHERE `id`=" . $namezem[$i] . "")->fetch_array(MYSQLI_ASSOC);
                $usersinclan = $mc->query("SELECT `id` FROM `users` WHERE `id_clan` = " . $users['id_clan'] . "")->fetch_all(MYSQLI_ASSOC);
                for ($b = 0; $b < count($usersinclan); $b++) {
                    $smsclan = "Ваш клан добился права на бой за " . $Nameloca['Name'] . "! К 8-ми часам по Московскому времени собирайте всех из Вашего клана и идите в бой на локацию";
                    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usersinclan[$b]['id'] . "','" . $smsclan . "','" . time() . "','msg')");
                }
            }
        }
        $nexttime2 = mktime(17, 50, 0, date("m"), date("d"), date("Y"));
        $nextZahvattime2 = mktime(19, 50, 0, date("m"), date("d"), date("Y"));
        $mc->query("UPDATE `location` SET `nextZahvat`='" . $nextZahvattime2 . "' WHERE nextZahvat` <= '" . $nexttime2 . "'");
    }





    if ($_GET['time'] == "2000") {

        //20:00
        $ucharr = $mc->query("SELECT * FROM `huntb_list` WHERE `type` = 8 ORDER BY `location` ASC")->fetch_all(MYSQLI_ASSOC);
        $zem;
        $namezem;
        $namelocation = 0;
        $teclocation = 0;
        $teclocation2 = 0;

        $locazahvbd = ""; // составляем запрос что бы узнать кто продляется
        for ($i = 0; $i < count($ucharr); $i++) {
            if ($namelocation != $ucharr[$i]['location']) {
                $teclocation += 1; //если лока сменилась новый массив
                $teclocation2 = 1;
                $namelocation = $ucharr[$i]['location'];
            }

            $zem[$teclocation][$teclocation2] = $ucharr[$i]['user_id'];
            $namezem[$teclocation] = $ucharr[$i]['location'];
            //echo $ucharr[$i]['user_id']."<br>";
            if ($locazahvbd == "") {
                $locazahvbd .= " `id` != " . $namezem[$teclocation];
            } else {
                $locazahvbd .= " AND `id` != " . $namezem[$teclocation];
            }



            $teclocation2 += 1;
        }

        $nextZahvattime2 = mktime(19, 50, 0, date("m"), date("d") + 2, date("Y"));
        $mc->query("UPDATE `location` SET `nextZahvat` = '" . $nextZahvattime2 . "' WHERE " . $locazahvbd . "");



        for ($i = 1; $i < count($namezem) + 1; $i++) {

            if (count($zem[$i]) > 1) {
                //если людей много
                //если людей много
                $battle_id = rand(0, time()) . rand(0, time()) . rand(0, time());
                $battle_start_time = time();
                $namebattle = "Земля";
                $novragov = true;
                $nv = 0;
                for ($j = 1; $j < count($zem[$i]) + 1; $j++) {
                    $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $zem[$i][$j] . "'")->fetch_array(MYSQLI_ASSOC);
                    echo $users['name'] . " Драка2: " . $zem[$i][$j] . " за локу :" . $namezem[$i] . "<br>";


                    if ($users['temp_health'] < 2) {
                        //echo "Ноль хп. нет";
                        $users['temp_health'] = 10;
                    }
                    hero_add($users['id_clan'], $namebattle, $users, $battle_id, $battle_start_time, 8);
                    ////Типа если 2 игрока с 1 клана, а с другого пусто, то ниче не будет
                    if ($nv != 0 && $nv != $users['id_clan']) {
                        $novragov = false;
                    }
                    $nv = $users['id_clan'];

                    $namebattle = "";
                }


                if ($novragov) {
                    //отдать локу одному
                    echo "Нет соперника";
                    $nexttime2 = mktime(17, 50, 0, date("m"), date("d") + 2, date("Y"));
                    $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $zem[$i][1] . "'")->fetch_array(MYSQLI_ASSOC);
                    echo "UPDATE `location` SET `idClan` = '" . $users['id_clan'] . "', `idNextClan` = '0', `nextZahvat`='" . $nexttime2 . "' WHERE `id` = " . $namezem[$i] . "" . "<br>";
                    $mc->query("UPDATE `location` SET `idClan` = '" . $users['id_clan'] . "', `idNextClan` = '0', `nextZahvat`='" . $nexttime2 . "' WHERE `id` = " . $namezem[$i] . "");
                    //DELETE FROM `battle` WHERE `battle_id` = ''
                    $mc->query("DELETE FROM `battle` WHERE `battle_id` = '" . $battle_id . "'");
                    $mc->query("DELETE FROM `huntb_list` WHERE `location` ='" . $namezem[$i] . "' AND `type`=8");

                    //а теперь отсылаем всему клану инфу о том, что они идиоты
                    $Nameloca = $mc->query("SELECT `Name` FROM `location` WHERE `id`=" . $namezem[$i] . "")->fetch_array(MYSQLI_ASSOC);
                    $usersinclan = $mc->query("SELECT `id` FROM `users` WHERE `id_clan` = " . $users['id_clan'] . "")->fetch_all(MYSQLI_ASSOC);
                    for ($b = 0; $b < count($usersinclan); $b++) {
                        $smsclan = "Ваш клан захватил " . $Nameloca['Name'] . "! ";
                        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usersinclan[$b]['id'] . "','" . $smsclan . "','" . time() . "','msg')");
                    }
                }
            } else {
                //отдать локу одному
                $nexttime2 = mktime(17, 50, 0, date("m"), date("d") + 2, date("Y"));
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $zem[$i][1] . "'")->fetch_array(MYSQLI_ASSOC);
                echo $users['name'] . " отдать: " . $zem[$i][1] . " за локу :" . $namezem[$i] . "<br>";
                $mc->query("UPDATE `location` SET `idClan` = '" . $users['id_clan'] . "', `idNextClan` = '0', `nextZahvat`='" . $nexttime2 . "' WHERE `id` = " . $namezem[$i] . "");
                $mc->query("DELETE FROM `huntb_list` WHERE `location` ='" . $namezem[$i] . "' AND `type`=8");

                //а теперь отсылаем всему клану инфу о том, что они идиоты
                $Nameloca = $mc->query("SELECT `Name` FROM `location` WHERE `id`=" . $namezem[$i] . "")->fetch_array(MYSQLI_ASSOC);
                $usersinclan = $mc->query("SELECT `id` FROM `users` WHERE `id_clan` = " . $users['id_clan'] . "")->fetch_all(MYSQLI_ASSOC);
                for ($v = 0; $v < count($usersinclan); $v++) {
                    $smsclan = "Ваш клан захватил " . $Nameloca['Name'] . "! ";
                    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usersinclan[$v]['id'] . "','" . $smsclan . "','" . time() . "','msg')");
                }
            }
        }

        $nexttime2 = mktime(17, 50, 0, date("m"), date("d") + 1, date("Y"));
        $nextZahvattime2 = mktime(19, 50, 0, date("m"), date("d") + 2, date("Y"));
        $mc->query("UPDATE `location` SET `nextZahvat`='" . $nextZahvattime2 . "' , `idNextClan` = '0' WHERE nextZahvat` <= '" . $nexttime2 . "'");
    }
}

function hero_add($command, $type_battle, $userjuhg8, $battle_id, $battle_start_time, $type) {
    global $mc;
    $PA = $userjuhg8;
    $PA['weaponico'] = 0;
    $PA['Pshieldnum'] = 0;
    $PweaponEffect = array();

    //pl 1
    $arr1 = [];
    $shops_ids = [];
    $arr1['temp_health'] = $PA['temp_health'];
    $arr1['max_health'] = $PA['health'];
    $arr1['strength'] = $PA['strength'];
    $arr1['toch'] = $PA['toch'];
    $arr1['lov'] = $PA['lov'];
    $arr1['kd'] = $PA['kd'];
    $arr1['block'] = $PA['block'];
    $arr1['bron'] = $PA['bron'];
    //пересчет параметров игрока
    //получаем список одетых вещей героя
    $result221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $PA['id'] . "' AND `dress`='1'  && `BattleFlag`='1' || `id_user` = '" . $PA['id'] . "' AND `id_punct`>'9' && `BattleFlag`='1'");
    $myrow221 = $result221->fetch_all(MYSQLI_ASSOC);
    //перебираем параметры вещей

    for ($i = 0; $i < count($myrow221); $i++) {
        //read thing
        $result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221[$i]['id_shop'] . "'");
        if ($result1->num_rows) {
            //thing to arr par
            $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
            $shops_ids[] = [addslashes($infoshop['name']), $infoshop['id']];
            $arr1['max_health'] += $infoshop['health'];
            $arr1['strength'] += $infoshop['strength'];
            $arr1['toch'] += $infoshop['toch'];
            $arr1['lov'] += $infoshop['lov'];
            $arr1['kd'] += $infoshop['kd'];
            $arr1['block'] += $infoshop['block'];
            $arr1['bron'] += $infoshop['bron'];
            //переводим в иконку оружия
            if ((int) $infoshop['id_punct'] == 1) {
                if ($infoshop['id_image'] <= 36 || $infoshop['id_image'] >= 279 && $infoshop['id_image'] <= 298) {
                    $PA['weaponico'] = $infoshop['id_image'];
                } else {
                    $PA['weaponico'] = 0;
                }
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
            . "`stil`,"
            . "`shops_ids`"
            . ")VALUES("
            . "NULL,"
            . "'" . $PA['name'] . "',"
            . "'$type_battle',"
            . "'',"
            . "'" . $PA['level'] . "',"
            . "'" . $PA['side'] . "',"
            . "'" . $arr1['max_health'] . "',"
            . "'" . $PA['temp_health'] . "',"
            . "'" . $arr1['toch'] . "',"
            . "'" . $arr1['block'] . "',"
            . "'" . $arr1['strength'] . "',"
            . "'" . $arr1['bron'] . "',"
            . "'" . $arr1['kd'] . "',"
            . "'" . $arr1['lov'] . "',"
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
            . "'$type',"
            . "'" . $battle_id . "',"
            . "'" . $battle_start_time . "',"
            . "'" . $command . "',"
            . "'0',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'" . $PA['stil'] . "',"
            . "'" . json_encode($shops_ids, JSON_UNESCAPED_UNICODE) . "'"
            . ")");
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json);
}

?>