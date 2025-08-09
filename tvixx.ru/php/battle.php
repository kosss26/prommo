<?php

require '../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$ButtonBattleColorCount = 0;
$PAutotimerCount = 10; //*1
$MAutotimerCount = 10; //*1
$PtimerCount = 10; //*3
$MtimerCount = 10; //*3
$BattleResult = 0;
$Buttonvisible = 0;
$arr = [[], [], []];
$PeleksirEffect = [];
$PeleksirNCarr = 0;

if (isset($user)) {
    $PlayerArr = $user;
    //чтение элексиров одетых
    $PresEleksirUserBag = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $PlayerArr['id'] . "' AND `id_punct` = '9' AND `dress` = '1' ORDER BY `userbag`.`dress` DESC,`userbag`.`id` DESC");
    $PeleksirUserBagArrAll = $PresEleksirUserBag->fetch_all(MYSQLI_ASSOC);
    //параметры элексиров в магазине
    $PeleksirShopArrAll = "";
    for ($is = 0; is_array($PeleksirUserBagArrAll) && $is < count($PeleksirUserBagArrAll); $is++) {
        $PresEleksirShop = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $PeleksirUserBagArrAll[$is]['id_shop'] . "' LIMIT 1");
        if (is_array($PeleksirShopArrAll)) {
            $PeleksirShopArrAll = array_merge($PeleksirShopArrAll, [$PresEleksirShop->fetch_array(MYSQLI_ASSOC)]);
        } else {
            $PeleksirShopArrAll = [$PresEleksirShop->fetch_array(MYSQLI_ASSOC)];
        }
    }
    //получаем параметры игрока в бою если герой активен
    $Pres = $mc->query("SELECT * FROM `battle` WHERE `Mid` = '" . $PlayerArr['id'] . "' && `player_activ`='1' && `end_battle`='0' && `Plife`>'0' && `Ptype`='0' LIMIT 1");

    //отключим моба который долго сражаеся с противником
    $mc->query("UPDATE `battle` SET `lost_mob_id`='0' WHERE `Phodtime`<'" . (time() - 60) . "'");
    if ($Pres->num_rows) {
        $PA = $Pres->fetch_array(MYSQLI_ASSOC);
        //проверим что герой не один в бою
        if ($mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' && `command`!='" . $PA['command'] . "' LIMIT 1")->num_rows < 1) {
            $mc->query("UPDATE `battle` SET `end_battle` = '1' WHERE `battle_id`='" . $PA['battle_id'] . "'");
            errorbattle(0);
            exit(0);
        }
        //проверяем что есть еще жывые противники
        if ($mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' && `command`!='" . $PA['command'] . "' && `Plife`>'0' LIMIT 1")->num_rows == 0) {
            $mc->query("UPDATE `battle` SET `end_battle` = '1' , `player_activ`='0' WHERE `battle_id`='" . $PA['battle_id'] . "'");
            errorbattle(44);
            exit(0);
        }
        if ($PA['lost_mob_id'] < '1') {
            //если моб отстутствует то попытаться установить нового
            $MPres = $mc->query("SELECT * FROM `battle` WHERE "
                    . "`battle_id`='" . $PA['battle_id'] . "' && "
                    . "`command`!='" . $PA['command'] . "' &&"
                    . "`player_activ`='1' &&"
                    . "`end_battle`='0' &&"
                    . "`Plife`>'0' && "
                    . "`lost_mob_id`<'1'"
                    . " ORDER BY `counter` ASC LIMIT 1");
            $mc->query("UPDATE `battle` SET `Phodtime` = '" . (time() + 3) . "' WHERE `id`='" . $PA['id'] . "'");
            $PA['Phodtime'] = (time() + 3);
            chekCommand();
        } elseif ($PA['lost_mob_id'] != '0' && $PA['lost_mob_id'] != '-1') {
            //или прочитаем старого
            $MPres = $mc->query("SELECT * FROM `battle` WHERE `id`='" . $PA['lost_mob_id'] . "' LIMIT 1");
        }
        $Pname = $PA['Pname'];
        $Pnamevs = $PA['Pnamevs'];
        $Pvsname = $PA['Pvsname'];
        $Plevel = (int) $PA['level'];
        $Pico = (int) $PA['Pico'];
        $Pflife = (int) $PA['Pflife'];
        $Plife = (int) $PA['Plife'];
        $Ptochnost = (int) $PA['Ptochnost'];
        $Pblock = (int) $PA['Pblock'];
        $Puron = (int) $PA['Puron'];
        $Pbronia = (int) $PA['Pbronia'];
        $Poglushenie = (int) $PA['Poglushenie'];
        $Puvorot = (int) $PA['Puvorot'];
        $Pweaponico = (int) $PA['Pweaponico'];
        $Pshieldnum = (int) $PA['Pshieldnum'];
        $Pshieldonoff = (int) $PA['Pshieldonoff'];
        $Ptype = (int) $PA['Ptype'];
        $Pvisible = (int) $PA['Pvisible'];
        $Panimation = (int) $PA['Panimation'];
        $PManimation = (int) $PA['Manimation'];
        $Phod = (int) $PA['Phod'];
        $Phodtime = (int) $PA['Phodtime'];
        $Pauto = (int) $PA['Pauto'];
        $PAlwaysEffect = json_decode($PA['PAlwaysEffect']);
        $Phodeffect = json_decode($PA['Phodeffect']);
        $Pmhodeffect = json_decode($PA['Pmhodeffect']);
        $PeleksirVisible = (int) $PA['PeleksirVisible'];
        $PeleksirUsed = [];
        if ($PA['PeleksirUsed'] != "") {
            $PeleksirUsed = json_decode($PA['PeleksirUsed']);
        }
        $PweaponEffect = json_decode($PA['PweaponEffect']);
        $PentityEffect = json_decode($PA['PentityEffect']);
        $PMentityEffect = json_decode($PA['MentityEffect']);
        $PentityArr = 0;
        $PMentityArr = 0;
        $PRuron = 0;
        $Pnumudar = $PA['numudar'];
        $Psuper = $PA['super'];
        //сброс значений энтити игрока
        $mc->query("UPDATE `battle` SET `PentityEffect` = '[]',`MentityEffect` = '[]' WHERE `id`='" . $PA['id'] . "' ");

        //если противник оглушен был то 2
        if ($PManimation == 8) {
            $prazgon = 2;
        } else {
            $prazgon = 1;
        }


        if (isset($MPres) && $MPres->num_rows) {
            //получим параметры противника
            $MPA = $MPres->fetch_array(MYSQLI_ASSOC);
            //параметры героя
            //параметры противника
            $MPid = $MPA['Mid'];
            $MPname = $MPA['Pname'];
            $MPnamevs = $MPA['Pnamevs'];
            $MPvsname = $MPA['Pvsname'];
            $MPlevel = (int) $MPA['level'];
            $MPico = (int) $MPA['Pico'];
            $MPflife = (int) $MPA['Pflife'];
            $MPlife = (int) $MPA['Plife'];
            $MPtochnost = (int) $MPA['Ptochnost'];
            $MPblock = (int) $MPA['Pblock'];
            $MPuron = (int) $MPA['Puron'];
            $MPbronia = (int) $MPA['Pbronia'];
            $MPoglushenie = (int) $MPA['Poglushenie'];
            $MPuvorot = (int) $MPA['Puvorot'];
            $MPweaponico = (int) $MPA['Pweaponico'];
            $MPshieldnum = (int) $MPA['Pshieldnum'];
            $MPshieldonoff = (int) $MPA['Pshieldonoff'];
            $MPtype = (int) $MPA['Ptype'];
            $MPvisible = (int) $MPA['Pvisible'];
            $MMvisible = (int) $MPA['Mvisible'];
            $MPhod = (int) $MPA['Phod'];
            $MPhodtime = (int) $MPA['Phodtime'];
            $MPauto = (int) $MPA['Pauto'];
            $MPAlwaysEffect = json_decode($MPA['PAlwaysEffect']);
            $MPhodeffect = json_decode($MPA['Phodeffect']);
            $MPmhodeffect = json_decode($MPA['Pmhodeffect']);
            $MPeleksirVisible = (int) $MPA['PeleksirVisible'];
            $MPweaponEffect = json_decode($MPA['PweaponEffect']);
            $MPentityEffect = json_decode($MPA['PentityEffect']);
            $MMentityEffect = json_decode($MPA['MentityEffect']);
            $MPentityArr = 0;
            $MPMentityArr = 0;
            $MRuron = 0;
            $MPnumudar = $MPA['numudar'];
            $MPsuper = $MPA['super'];
            //если противник оглушен был то 1
            if ($Panimation == 8) {
                $mrazgon = 2;
            } else {
                $mrazgon = 1;
            }
            //запишем игроку номер айди моба в бою
            $PA['lost_mob_id'] = $MPA['id'];
            //обновим айди противника герою в бд и мобу
            $mc->query("UPDATE `battle` SET `lost_mob_id` = '" . $MPA['id'] . "' WHERE `id` = '" . $PA['id'] . "'");
            $mc->query("UPDATE `battle` SET `lost_mob_id` = '" . $PA['id'] . "' WHERE `id` = '" . $MPA['id'] . "'");
            $Buttonvisible = (int) $PA['Phod'];
            //включение хода героя если у обоих 0
            if ($Phod === 0 && $MPhod === 0 || $Phod === 1 && $MPhod === 1) {
                $Phod = 0;
                $MPhod = 1;
                $mc->query("UPDATE `battle` SET `PeleksirVisible`='1',`Phodtime` = '" . (time() + 3) . "',`Phod` = '0' WHERE `id`='" . $PA['id'] . "' ");
                $mc->query("UPDATE `battle` SET `PeleksirVisible`='1',`Phodtime` = '" . time() . "',`Phod` = '1' WHERE `id`='" . $MPA['id'] . "' ");
            }
            //проверка щита героя
            if ($Pshieldonoff === 1 && $Pshieldnum < 1) {
                $Pshieldonoff = 0;
                $mc->query("UPDATE `battle` SET `Pshieldonoff` = '0' WHERE `id`='" . $PA['id'] . "' ");
            }
            //проверка щита героя
            if ($MPshieldonoff === 1 && $MPshieldnum < 1) {
                $MPshieldonoff = 0;
                $mc->query("UPDATE `battle` SET `Pshieldonoff` = '0' WHERE `id`='" . $MPA['id'] . "' ");
            }
            $Phodc = $PA['Phodc'];
            $MPhodc = $MPA['Phodc'];

            if ($Phodc == 1 && $Phod == 1) {
                $Phodc = 0;
                $mc->query("UPDATE `battle` SET `Phodc`='0' WHERE `id`='" . $PA['id'] . "' ");
            }
            if ($MPhodc == 1 && $MPhod == 1) {
                $MPhodc = 0;
                $mc->query("UPDATE `battle` SET `Phodc`='0' WHERE `id`='" . $MPA['id'] . "' ");
            }
            if ($Phodc == $MPhodc) {
                $Phodc = 0;
                $MPhodc = 0;
            }

            //автоматический ход
            //если ход игрока
            if ($Phod == 1 && $Phodc == 0 && $Plife > 0 && $MPlife > 0) {
                if (time() >= $Phodtime + $PtimerCount * 3 || $Pauto == 1 && time() >= $Phodtime + $PAutotimerCount) {
                    $mc->query("UPDATE `battle` SET `Pauto`='1' WHERE `id`='" . $PA['id'] . "' ");
                    $Pauto = 1;
                    Pturn(3);
                    oglush();
                    $Buttonvisible = 0;
                } else if (time() > $Phodtime + $PtimerCount * 2) {
                    $ButtonBattleColorCount = 2;
                } else if (time() > $Phodtime + $PtimerCount) {
                    $ButtonBattleColorCount = 1;
                }
            } else {
                $mc->query("UPDATE `battle` SET `Phodtime` = '" . time() . "' WHERE `id`='" . $PA['id'] . "' ");
            }

            //если ход противника
            if ($MPhod == 1 && $MPhodc == 0 && $MPlife > 0 && $Plife > 0) {
                if (time() >= $MPhodtime + 3 && $MPtype == 1 || time() >= $MPhodtime + $MtimerCount * 3 || $MPauto == 1 && time() >= $MPhodtime + $MAutotimerCount) {
                    $mc->query("UPDATE `battle` SET `Pauto`='1' WHERE `id`='" . $MPA['id'] . "' ");
                    if ($MPid == -1) {
                        $MPnumudar = $MPnumudar . '2';
                    }
                    $MPauto = 1;
                    Mturn(3);
                    oglush();
                    $Buttonvisible = 0;
                } else if (time() > $MPhodtime + $MtimerCount * 2) {
                    $ButtonBattleColorCount = 2;
                } else if (time() > $MPhodtime + $MtimerCount) {
                    $ButtonBattleColorCount = 1;
                }
            } else {
                $mc->query("UPDATE `battle` SET `Phodtime` = '" . time() . "' WHERE `id`='" . $MPA['id'] . "' ");
            }
            if ($Pauto == 1) {
                $ButtonBattleColorCount = 2;
            }
            //взять количество и номер иконки элексира[[ 5,0,0,[[ [[1,5,5]] ]] ]]
            for ($i = 0; is_array($PeleksirUserBagArrAll) && $i < count($PeleksirUserBagArrAll); $i++) {
                if (is_array($PeleksirNCarr)) {
                    $PeleksirNCarr = array_merge($PeleksirNCarr, array([$PeleksirUserBagArrAll[$i]['koll'], $PeleksirShopArrAll[$i]['id_image']]));
                } else {
                    $PeleksirNCarr = array([$PeleksirUserBagArrAll[$i]['koll'], $PeleksirShopArrAll[$i]['id_image']]);
                }
            }
//проверка нажатий
            if (!empty($_POST["numClick"]) && $Phod === 1 && $Buttonvisible == 1) {
                $numClick = $_POST["numClick"];
                $Pauto = 0;
                switch ($numClick) {
                    case $numClick > 0 && $numClick < 4:
                        $Buttonvisible = 0;
                        $ButtonBattleColorCount = 0;
                        $mc->query("UPDATE `battle` SET `Pauto`='0' WHERE `id`='" . $PA['id'] . "' ");
                        $Pnumudar = $Pnumudar . '' . $numClick;
                        Pturn($numClick + 1);
                        oglush();
                        break;
                    case 4:
                        if ($Pshieldonoff < 1 && $Pshieldnum > 0) {
                            $Pshieldonoff = 1;
                            $mc->query("UPDATE `battle` SET `Pauto`='0' ,`Pshieldonoff` = '1', `Panimation` = '1' WHERE `id`='" . $PA['id'] . "' ");
                        } else {
                            $Pshieldonoff = 0;
                            $mc->query("UPDATE `battle` SET `Pauto`='0' ,`Pshieldonoff` = '0',`Panimation` = '0' WHERE `id`='" . $PA['id'] . "' ");
                        }
                        break;
                    case $numClick > 4:
                        Pturn($numClick);
                        break;
                }
            }

            //убит
            //удаление из бд битвы если хп 0
            if ($MPlife <= 0) {
                $Buttonvisible = 0;
                $MPlife = 0;
                $PManimation = 7;
                //если противник игрок то запишем ему хп игроку в бд и нам победу прибавим над игроками
                if ($MPA['Ptype'] == 0) {
                    $mc->query("UPDATE `users` SET `hp_rt`='" . time() . "',`temp_health`='0' WHERE `id`='" . $MPA['Mid'] . "'");
                    $mc->query("UPDATE `users` SET `hp_rt`='" . time() . "',`pobedigroki`=`pobedigroki`+'1' WHERE `id`='" . $PA['Mid'] . "'");
                    $mc->query("UPDATE `quests_users` SET `herowin_c` = `herowin_c`+'1' WHERE `id_user`='" . $PA['Mid'] . "'");
                } else {
                    //победа над монстром
                    $mc->query("UPDATE `users` SET `pobedmonser`=`pobedmonser`+'1' WHERE `id`='" . $PA['Mid'] . "'");
                    //запишем время отсутствия моба если оно больше 0
                    if ($Mobhuntpar = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . $MPA['Mid'] . "' AND `intervalTime`>'0'")->fetch_array(MYSQLI_ASSOC)) {
                        $mc->query("INSERT INTO `userHuntNotActiveMob` ("
                                . "`id`,"
                                . " `id_user`,"
                                . " `id_mob`,"
                                . " `time_end`"
                                . ") VALUES ("
                                . "NULL,"
                                . " '" . $PA['Mid'] . "',"
                                . " '" . $MPA['Mid'] . "',"
                                . " '" . ($Mobhuntpar['intervalTime'] + time()) . "'"
                                . ")");
                    }
                }
                // следующему СВОБОДНОМУ противнику зададим параметр хода 
                $mc->query("UPDATE `battle` SET `PeleksirVisible`='1',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0' WHERE "
                        . "`battle_id`='" . $PA['battle_id'] . "' AND "
                        . "`command`!='" . $PA['command'] . "' AND"
                        . "`player_activ`='1' AND"
                        . "`end_battle`='0' AND"
                        . "`Plife`>'0' AND "
                        . "`lost_mob_id`<'1'"
                        . "ORDER BY `counter` ASC LIMIT 1");
                //герою значение противника и хода обнулим 
                $mc->query("UPDATE `battle` SET `lost_mob_id` = '0',`PeleksirVisible`='1',`Phodtime` = '" . (time() + 3) . "',`Phod` = '0',`Phodc` = '1' WHERE `id`='" . $PA['id'] . "' ");
                //противнику зададим параметр мертв
                $mc->query("UPDATE `battle` SET `lost_mob_id` = '0',`prev_mob_id`='" . $PA['id'] . "',`player_activ` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                $Phod = 0;
                $MPhod = 1;
                $Phodc = 1;
                $MPhodc = 0;
            }
            if ($Plife <= 0) {
                $Buttonvisible = 0;
                $Plife = 0;
                $Panimation = 7;
                $BattleResult = 1;
                //если противник игрок то запишем ему победу над нами или поражение нам от мобов
                if ($MPA['Ptype'] == 0) {
                    $mc->query("UPDATE `users` SET `hp_rt`='" . time() . "',`pobedigroki`=`pobedigroki`+'1' WHERE `id`='" . $MPA['Mid'] . "'");
                    $mc->query("UPDATE `quests_users` SET `herowin_c` = `herowin_c`+'1' WHERE `id_user`='" . $MPA['Mid'] . "'");
                } else {
                    $mc->query("UPDATE `users` SET `losemonser`=`losemonser`+'1' WHERE `id`='" . $PA['Mid'] . "'");
                }
                // след СВОБОДНОМУ герою ход из команды героя
                $mc->query("UPDATE `battle` SET `PeleksirVisible`='1',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0' WHERE "
                        . "`battle_id`='" . $PA['battle_id'] . "' AND "
                        . "`command`!='" . $MPA['command'] . "' AND"
                        . "`player_activ`='1' AND"
                        . "`end_battle`='0' AND"
                        . "`Plife`>'0' AND "
                        . "`lost_mob_id`<'1'"
                        . "ORDER BY `counter` ASC LIMIT 1");
                //запишем хп игроку в бд
                $mc->query("UPDATE `users` SET `hp_rt`='" . time() . "',`temp_health`='0' WHERE `id`='" . $PlayerArr['id'] . "'");
                //противнику ход 0 и противника 0
                $mc->query("UPDATE `battle` SET `lost_mob_id` = '0',`PeleksirVisible`='1',`Phodtime` = '" . (time() + 3) . "',`Phod` = '0',`Phodc` = '1' WHERE `id`='" . $MPA['id'] . "' ");
                //герою смерть
                $mc->query("UPDATE `battle` SET `lost_mob_id` = '0',`player_activ` = '0' WHERE `id`='" . $PA['id'] . "' ");
                $Phod = 1;
                $MPhod = 0;
                $Phodc = 0;
                $MPhodc = 1;
            }

            if ($Phodc != 0 && $MPhodc != 0 && $Phodc != $MPhodc && $Plife > 0 && $MPlife > 0) {
                //определяем что оба походили по 1 разу
                $selectedmobid = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' && `lost_mob_id` <'1' && `command`!='" . $PA['command'] . "' && `player_activ`='1' ORDER BY `counter` ASC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
                $selectedmob2id = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' && `lost_mob_id`<'1' && `command`!='" . $MPA['command'] . "' && `player_activ`='1' ORDER BY `counter` ASC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
                //получили количество участников с разных сторон 
                //если герой и противник не оглушен и есть другие участники
                if ($Panimation != 8 && $PManimation != 8 && isset($selectedmobid)) {
                    //если последним ходил герой
                    if ($Phodc > $MPhodc) {
                        $Phod = 0;
                        $MPhod = 1;
                        $Phodc = 0;
                        $MPhodc = 0;
                        $Buttonvisible = 0;
                        //запишем герою айди нового противника
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0',`lost_mob_id` = '" . $selectedmobid['id'] . "'  WHERE `id`='" . $PA['id'] . "' ");
                        //и противнику айди героя
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0',`lost_mob_id` = '" . $PA['id'] . "' WHERE `id`='" . $selectedmobid['id'] . "' ");
                        //старому противнику обнулим 
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0',`lost_mob_id` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                    } elseif ($Phodc < $MPhodc) {
                        $Phod = 1;
                        $MPhod = 0;
                        $Phodc = 0;
                        $MPhodc = 0;
                        //если последним ходил противник
                        //запишем герою новый айди противника
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0',`lost_mob_id` = '" . $selectedmobid['id'] . "'  WHERE `id`='" . $PA['id'] . "' ");
                        //и противнику айди героя
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0',`lost_mob_id` = '" . $PA['id'] . "' WHERE `id`='" . $selectedmobid['id'] . "' ");
                        //старому обнулим
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0',`lost_mob_id` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                    }
                } elseif ($Panimation != 8 && $PManimation != 8 && isset($selectedmob2id)) {
                    //или сменим противника противнику если есть другие игроки в команде героя
                    //если последним ходил герой
                    if ($Phodc > $MPhodc) {
                        $Buttonvisible = 0;
                        $Phod = 0;
                        $MPhod = 1;
                        $Phodc = 0;
                        $MPhodc = 0;
                        //запишем противнику айди нового противника
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0',`lost_mob_id` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                        //герою обнулим противника
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0',`lost_mob_id` = '0' WHERE `id`='" . $PA['id'] . "' ");
                    } elseif ($Phodc < $MPhodc) {
                        $Phod = 1;
                        $MPhod = 0;
                        $Phodc = 0;
                        $MPhodc = 0;
                        //если последним ходил противник
                        //запишем противнику айди нового противника
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0',`lost_mob_id` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                        //герою обнулим противника
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0',`lost_mob_id` = '0' WHERE `id`='" . $PA['id'] . "' ");
                    }
                } else {
                    //обнулим ходы просто
                    //если последним ходил герой
                    if ($Phodc > $MPhodc) {
                        $Phod = 0;
                        $MPhod = 1;
                        $Phodc = 0;
                        $MPhodc = 0;
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0' WHERE `id`='" . $PA['id'] . "' ");
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                    } elseif ($Phodc < $MPhodc) {
                        $Phod = 1;
                        $MPhod = 0;
                        $Phodc = 0;
                        $MPhodc = 0;
                        //если последним ходил противник
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0' WHERE `id`='" . $PA['id'] . "' ");
                        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0' WHERE `id`='" . $MPA['id'] . "' ");
                    }
                }
            }

            chekCommand();



            $PMentityArr = json_decode($PMentityArr);
            if (is_array($PMentityEffect)) {
                if (is_array($PMentityArr)) {
                    $PMentityArr = array_merge($PMentityEffect, $PMentityArr);
                } else {
                    $PMentityArr = $PMentityEffect;
                }
            }
            $PMentityArr = json_encode($PMentityArr);
            $PentityArr = json_decode($PentityArr);
            if (is_array($PentityEffect)) {
                if (is_array($PentityArr)) {
                    $PentityArr = array_merge($PentityEffect, $PentityArr);
                } else {
                    $PentityArr = $PentityEffect;
                }
            }
            $PentityArr = json_encode($PentityArr);
            //сброс анимаций игрока в бд
            if ($Panimation < 7 || $Panimation > 8) {
                $mc->query("UPDATE `battle` SET `Panimation` = '" . $Pshieldonoff . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "'");
            }
            if ($PManimation < 7 || $PManimation > 8) {
                $mc->query("UPDATE `battle` SET `Manimation` = '" . $MPshieldonoff . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
            }

//вывод данных
            echo json_encode(array(
                "error" => "101" . $mc->error,
                "Buttonvisible" => $Buttonvisible,
                "ButtonBattleColorCount" => $ButtonBattleColorCount,
                "BattleResult" => $BattleResult,
                "Pname" => $Pname,
                "Plife" => $Plife,
                "Pweapon" => (int) $Pweaponico,
                "Pico" => $Pico,
                "Ptype" => $Ptype,
                "Pvisible" => $Pvisible,
                "PeleksirVisible" => $PeleksirVisible,
                "Panimation" => $Panimation,
                "Pshield" => $Pshieldonoff,
                "PshieldNC" => $Pshieldnum,
                "PeleksirNCarr" => $PeleksirNCarr,
                "Pentityarr" => $PentityArr,
                "lost_mob_id" => (int) $PA['lost_mob_id'],
                "Mname" => $MPname,
                "Mlife" => $MPlife,
                "Mweapon" => $MPweaponico,
                "Mico" => $MPico,
                "Mtype" => $MPtype,
                "Mvisible" => $MPvisible,
                "Manimation" => $PManimation,
                "Mshield" => $MPshieldonoff,
                "Mentityarr" => $PMentityArr
            ));
        } else {
            if ($PA['end_battle'] == 1 && $PA['prev_mob_id'] == 0 || $PA['player_activ'] == 0 && $PA['prev_mob_id'] == 0) {
                $BattleResult = 1;
            } else {
                $BattleResult = 0;
            }
            if ($Plife <= 0) {
                $Panimation = 7;
            }
            $PMentityArr = json_decode($PMentityArr);
            if (is_array($PMentityEffect)) {
                if (is_array($PMentityArr)) {
                    $PMentityArr = array_merge($PMentityEffect, $PMentityArr);
                } else {
                    $PMentityArr = $PMentityEffect;
                }
            }
            $PMentityArr = json_encode($PMentityArr);
            $PentityArr = json_decode($PentityArr);
            if (is_array($PentityEffect)) {
                if (is_array($PentityArr)) {
                    $PentityArr = array_merge($PentityEffect, $PentityArr);
                } else {
                    $PentityArr = $PentityEffect;
                }
            }
            $PentityArr = json_encode($PentityArr);
            $mc->query("UPDATE `battle` SET `Panimation` = '" . $Pshieldonoff . "',`Manimation` = '0',`Plife`='" . $Plife . "',`lost_mob_id` = '-1',`prev_mob_id`='0' WHERE `id`='" . $PA['id'] . "'");
            echo json_encode(array(
                "error" => "102" . $mc->error,
                "Buttonvisible" => $Buttonvisible,
                "ButtonBattleColorCount" => $ButtonBattleColorCount,
                "BattleResult" => $BattleResult,
                "Pname" => $Pname,
                "Plife" => $Plife,
                "Pweapon" => (int) $Pweaponico,
                "Pico" => $Pico,
                "Ptype" => $Ptype,
                "Pvisible" => $Pvisible,
                "PeleksirVisible" => $PeleksirVisible,
                "Panimation" => $Panimation,
                "Pshield" => $Pshieldonoff,
                "PshieldNC" => $Pshieldnum,
                "PeleksirNCarr" => $PeleksirNCarr,
                "Pentityarr" => $PentityArr,
                "lost_mob_id" => (int) $PA['lost_mob_id'],
                "Mname" => "",
                "Mlife" => "",
                "Mweapon" => "",
                "Mico" => "",
                "Mrasa" => "",
                "Mtype" => "",
                "Mvisible" => "",
                "Manimation" => $PManimation,
                "Mshield" => "",
                "Mentityarr" => $PMentityArr
            ));
        }
    } else {
        errorbattle(1);
    }
}

function oglush() {
    global $Panimation;
    global $PManimation;
    global $Plife;
    global $MPlife;
    global $mc;
    global $PA;
    global $MPA;
    global $Phod;
    global $MPhod;
    global $Phodc;
    global $MPhodc;
    if ($Panimation == 8 && $Plife > 0 && $MPlife > 0) {
        $Phod = 0;
        $MPhod = 1;
        $Phodc = 0;
        $MPhodc = 0;
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0' WHERE `id`='" . $PA['id'] . "' ");
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0' WHERE `id`='" . $MPA['id'] . "' ");
    } elseif ($PManimation == 8 && $Plife > 0 && $MPlife > 0) {
        $Phod = 1;
        $MPhod = 0;
        $Phodc = 0;
        $MPhodc = 0;
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`Phodc` = '0' WHERE `id`='" . $PA['id'] . "' ");
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '0' WHERE `id`='" . $MPA['id'] . "' ");
    }
}

function errorbattle($e) {
    global $PA;
    global $mc;
    echo json_encode(array(
        "error" => $e,
        "Buttonvisible" => "",
        "ButtonBattleColorCount" => "",
        "BattleResult" => $e,
        "Pname" => "",
        "Plife" => "",
        "Pweapon" => "",
        "Pico" => "",
        "Pvisible" => 1,
        "PeleksirVisible" => "",
        "Panimation" => "",
        "Pshield" => "",
        "PshieldNC" => "",
        "PeleksirNCarr" => "",
        "Pentityarr" => "",
        "lost_mob_id" => -1,
        "Mname" => " ",
        "Mlife" => "",
        "Mweapon" => "",
        "Mico" => "",
        "Mvisible" => "",
        "Manimation" => "",
        "Mshield" => "",
        "Mentityarr" => ""
    ));
}

function udarpopalbool() {
    $a = 8;
    if ($a < 1) {
        $a = 1;
    }
    $a = 100000 / $a;
    $a = round($a);
    $b = rand(1, 100000);
    $c = $a + $b;
    if ($c > 100000) {
        return 1;
    } else {
        return 0;
    }
}

function Pturn($num) {
    global $Pauto;
    global $prazgon;
    global $mc;
    global $PA;
    global $MPA;
    global $Buttonvisible;
    global $ButtonBattleColorCount;
    global $Pname;
    global $Plife;
    global $Pweaponico;
    global $Pico;
    global $Ptype;
    global $Pvisible;
    global $PeleksirNCarr;
    global $MPname;
    global $MPweaponico;
    global $MPico;
    global $MPtype;
    global $MPvisible;
    global $Plevel;
    global $Panimation;
    global $PAlwaysEffect;
    global $Phodeffect;
    global $Pmhodeffect;
    global $PeleksirVisible;
    global $PeleksirUsed;
    global $PweaponEffect;
    global $PentityArr;
    global $PMentityArr;



    global $MPlevel;
    global $PManimation;
    global $MPAlwaysEffect;
    global $MPhodeffect;
    global $MPmhodeffect;
    global $MPweaponEffect;
    global $MPentityEffect;
    global $MMentityEffect;

//обработка параметров героев при ударе
    global $Pflife;
    global $Plife;
    global $Ptochnost;
    global $Pblock;
    global $Puron;
    global $Pbronia;
    global $Poglushenie;
    global $Puvorot;
    global $Pshieldnum;
    global $Pshieldonoff;

    global $PRuron;
    global $Pnumudar;
    global $Psuper;

    global $MPflife;
    global $MPlife;
    global $MPtochnost;
    global $MPblock;
    global $MPuron;
    global $MRuron;
    global $MPbronia;
    global $MPoglushenie;
    global $MPuvorot;
    global $MPshieldnum;
    global $MPshieldonoff;
    global $PeleksirUserBagArrAll;
    global $PeleksirShopArrAll;

    global $Phodc;
    global $MPhodc;

    if ($num > 1 && $num < 5) {
        $Phodc = $MPhodc + 1;
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '$Phodc' WHERE `id`='" . $PA['id'] . "' ");
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`PeleksirVisible`='1' WHERE `id`='" . $MPA['id'] . "' ");

        $Panimation = $num;
        //супер удары игрока
        $suhero = 0;
        if ($Psuper != "" && $Pauto == 0) {
            $arrsuhero = explode(",", $Psuper);
            for ($i = 0; is_array($arrsuhero) && $i < count($arrsuhero); $i++) {
                if (strstr($Pnumudar, $arrsuhero[$i])) {
                    unset($arrsuhero[$i]);
                    $arrsuhero = indexaTArr($arrsuhero);
                    $Panimation = 5;
                    $Psuper = implode(",", $arrsuhero);
                    $suhero = 1;
                    $Pnumudar = "";
                    break;
                }
            }
        }
        //генерация попаданий
        //точность героя
        $t0 = rand($Ptochnost, $Ptochnost + $Plevel);
        //уворот противника
        $u0 = rand($MPuvorot, $MPuvorot + $MPlevel);
        //флаг попадания
        $popal = 0;
        $popal2 = 0;
        $glush = rand($Poglushenie, $Poglushenie + $Plevel) > rand(0, $MPbronia + ($MPlevel * 12)) && !udarpopalbool();
        //если точность больше уворота то попал
        if ($t0 > $u0) {
            $popal = 1;
        } else {
            //или проверим по второй формуле
            if (rand($t0, $u0 * 1.1) > $u0) {
                $popal = 1;
            } else if (udarpopalbool()) {
                $popal = 1;
            }
        }
        //если точность больше блока то попал
        if ($t0 > $MPblock) {
            $popal2 = 1;
        }
        if ($MPshieldonoff == 0) {
            if ($popal) {
                //глушение
                if ($glush) {
                    //оглушен
                    $PManimation = 8;
                } else {
                    //попал
                    $PManimation = 9;
                }
            } else if ($PManimation == 8) {
                $PManimation = 9;
            } else {
                $PManimation = 6;
            }
        }
        //точка больше блока противника в щите
        if ($MPshieldonoff == 1) {
            if ($popal) {
                if ($popal2 || $glush) {
                    //глушение
                    if ($glush) {
                        //оглушен
                        $PManimation = 8;
                    } else {
                        //попал
                        $PManimation = 9;
                    }
                } else if ($PManimation == 8) {
                    $PManimation = 9;
                } else {
                    $MPshieldnum--;
                    if ($MPshieldnum < 1) {
                        $MPshieldonoff = 0;
                    }
                    $PManimation = $MPshieldonoff;
                }
            } else {
                $PManimation = 6;
            }
        }

        //сброс анимаций игрока в бд
        if ($Panimation < 7 || $Panimation > 8) {
            $mc->query("UPDATE `battle` SET `Panimation` = '" . $Pshieldonoff . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        } else {
            $mc->query("UPDATE `battle` SET `Panimation` = '" . $Panimation . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        }
        if ($PManimation < 7 || $PManimation > 8) {
            $mc->query("UPDATE `battle` SET `Manimation` = '" . $MPshieldonoff . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        } else {
            $mc->query("UPDATE `battle` SET `Manimation` = '" . $PManimation . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        }
//запись анимаций противника в бд
        $mc->query("UPDATE `battle` SET `Panimation` = '" . $PManimation . "',`Manimation` = '" . $Panimation . "',`Plife`='" . $MPlife . "' WHERE `id`='" . $MPA['id'] . "' ");
    }

//arr:[0:[0:[0:2,1:5,2:1]]]
    if ($num > 1 && $num < 5) {
        //удаление отработанных эффектов игрока на каждый ход
        for ($i0 = 0; is_array($PAlwaysEffect) && $i0 < count($PAlwaysEffect); $i0++) {
            if (!count($PAlwaysEffect[$i0])) {
                unset($PAlwaysEffect[$i0]);
                $PAlwaysEffect = indexaTArr($PAlwaysEffect);
                $i0 = -1;
            } else if (count($PAlwaysEffect[$i0]) && $PAlwaysEffect[$i0][0][1] <= 0) {
                unset($PAlwaysEffect[$i0][0]);
                $PAlwaysEffect = indexaTArr($PAlwaysEffect);
                $i0 = -1;
            }
        }
        //применение эффектов игрока игрока на каждый ход
        for ($i = 0; is_array($PAlwaysEffect) && $i < count($PAlwaysEffect); $i++) {
            if (count($PAlwaysEffect[$i][0]) && $PAlwaysEffect[$i][0][1] > 0) {
                $PAlwaysEffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($PentityArr)) {
                    $PentityArr = array_merge(array([$PAlwaysEffect[$i][0][0], $PAlwaysEffect[$i][0][2]]), $PentityArr);
                } else {
                    $PentityArr = array([$PAlwaysEffect[$i][0][0], $PAlwaysEffect[$i][0][2]]);
                }
            }
        }
        //удаление отработанных эффектов игрока на каждый ход героя
        for ($i0 = 0; is_array($Phodeffect) && $i0 < count($Phodeffect); $i0++) {
            if (!count($Phodeffect[$i0])) {
                unset($Phodeffect[$i0]);
                $Phodeffect = indexaTArr($Phodeffect);
                $i0 = -1;
            } else if (count($Phodeffect[$i0]) && $Phodeffect[$i0][0][1] <= 0) {
                unset($Phodeffect[$i0][0]);
                $Phodeffect = indexaTArr($Phodeffect);
                $i0 = -1;
            }
        }
        //применение эффектов игрока игрока на каждый ход героя
        for ($i = 0; is_array($Phodeffect) && $i < count($Phodeffect); $i++) {
            if (count($Phodeffect[$i][0]) && $Phodeffect[$i][0][1] > 0) {
                $Phodeffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($PentityArr)) {
                    $PentityArr = array_merge(array([$Phodeffect[$i][0][0], $Phodeffect[$i][0][2]]), $PentityArr);
                } else {
                    $PentityArr = array([$Phodeffect[$i][0][0], $Phodeffect[$i][0][2]]);
                }
            }
        }

        //удаление отработанных противника на каждый ход
        for ($i0 = 0; is_array($MPAlwaysEffect) && $i0 < count($MPAlwaysEffect); $i0++) {
            if (!count($MPAlwaysEffect[$i0])) {
                unset($MPAlwaysEffect[$i0]);
                $MPAlwaysEffect = indexaTArr($MPAlwaysEffect);
                $i0 = -1;
            } else if (count($MPAlwaysEffect[$i0]) && $MPAlwaysEffect[$i0][0][1] <= 0) {
                unset($MPAlwaysEffect[$i0][0]);
                $MPAlwaysEffect = indexaTArr($MPAlwaysEffect);
                $i0 = -1;
            }
        }
        //применение эффектов противника на каждый ход
        for ($i = 0; is_array($MPAlwaysEffect) && $i < count($MPAlwaysEffect); $i++) {
            if (count($MPAlwaysEffect[$i][0]) && $MPAlwaysEffect[$i][0][1] > 0) {
                $MPAlwaysEffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($PMentityArr)) {
                    $PMentityArr = array_merge(array([$MPAlwaysEffect[$i][0][0], $MPAlwaysEffect[$i][0][2]]), $PMentityArr);
                } else {
                    $PMentityArr = array([$MPAlwaysEffect[$i][0][0], $MPAlwaysEffect[$i][0][2]]);
                }
            }
        }

        //удаление отработанных противника на каждый ход игрока
        for ($i0 = 0; is_array($MPmhodeffect) && $i0 < count($MPmhodeffect); $i0++) {
            if (!count($MPmhodeffect[$i0])) {
                unset($MPmhodeffect[$i0]);
                $MPmhodeffect = indexaTArr($MPmhodeffect);
                $i0 = -1;
            } else if (count($MPmhodeffect[$i0]) && $MPmhodeffect[$i0][0][1] <= 0) {
                unset($MPmhodeffect[$i0][0]);
                $MPmhodeffect = indexaTArr($MPmhodeffect);
                $i0 = -1;
            }
        }
        //применение эффектов противника на каждый ход игрока
        for ($i = 0; is_array($MPmhodeffect) && $i < count($MPmhodeffect); $i++) {
            if (count($MPmhodeffect[$i][0]) && $MPmhodeffect[$i][0][1] > 0) {
                $MPmhodeffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($PMentityArr)) {
                    $PMentityArr = array_merge(array([$MPmhodeffect[$i][0][0], $MPmhodeffect[$i][0][2]]), $PMentityArr);
                } else {
                    $PMentityArr = array([$MPmhodeffect[$i][0][0], $MPmhodeffect[$i][0][2]]);
                }
            }
        }
    }



    //обработка эффектов элексиров 
    //уменьшение на 1 количества использований
    if ($num > 4 && $PeleksirVisible == 1 && isset($PeleksirUserBagArrAll[$num - 5]) && isset($PeleksirUserBagArrAll[$num - 5]['koll']) && $PeleksirUserBagArrAll[$num - 5]['koll'] < 99) {
//попытка определить был ли применен элексир ранее
        //проверяем в автоэффектах
        $used = 0;
        if (is_array($PAlwaysEffect)) {
            for ($i = 0; $i < count($PAlwaysEffect); $i++) {
                if (array_key_exists(3, $PAlwaysEffect[$i][0]) && $PAlwaysEffect[$i][0][3] == $PeleksirShopArrAll[$num - 5]['id']) {
                    $used = 1;
                    break;
                }
            }
        }
        if ($used == 0 && is_array($Phodeffect)) {
            for ($i = 0; $i < count($Phodeffect); $i++) {
                if (array_key_exists(3, $Phodeffect[$i][0]) && $Phodeffect[$i][0][3] == $PeleksirShopArrAll[$num - 5]['id']) {
                    $used = 1;
                    break;
                }
            }
        }
        if ($used == 0 && is_array($Pmhodeffect)) {
            for ($i = 0; $i < count($Pmhodeffect); $i++) {
                if (array_key_exists(3, $Pmhodeffect[$i][0]) && $Pmhodeffect[$i][0][3] == $PeleksirShopArrAll[$num - 5]['id']) {
                    $used = 1;
                    break;
                }
            }
        }

        if ($PA['Mid'] == 1 && $used == 1) {
            //вывод данных
            echo json_encode(array(
                "error" => 5,
                "Buttonvisible" => $Buttonvisible,
                "ButtonBattleColorCount" => $ButtonBattleColorCount,
                "BattleResult" => 5,
                "Pname" => $Pname,
                "Plife" => $Plife,
                "Pweapon" => (int) $Pweaponico,
                "Pico" => $Pico,
                "Ptype" => $Ptype,
                "Pvisible" => $Pvisible,
                "PeleksirVisible" => $PeleksirVisible,
                "Panimation" => $Panimation,
                "Pshield" => $Pshieldonoff,
                "PshieldNC" => $Pshieldnum,
                "PeleksirNCarr" => $PeleksirNCarr,
                "Pentityarr" => $PentityArr,
                "lost_mob_id" => (int) $PA['lost_mob_id'],
                "Mname" => $MPname,
                "Mlife" => $MPlife,
                "Mweapon" => $MPweaponico,
                "Mico" => $MPico,
                "Mtype" => $MPtype,
                "Mvisible" => $MPvisible,
                "Manimation" => $PManimation,
                "Mshield" => $MPshieldonoff,
                "Mentityarr" => $PMentityArr
            ));
            exit(0);
        }
        $PeleksirVisible = 0;
        //уменьшение количества элексира на 1
        $PeleksirUserBagArrAll[$num - 5]['koll'] -= 1;
        $arrEel = json_decode_nice($PeleksirShopArrAll[$num - 5]['effects']);
        //допишем айдишник элексира в эффекты
        for ($i = 0; is_array($arrEel) && $i < count($arrEel); $i++) {
            if (!array_key_exists(4, $arrEel[$i])) {
                $arrEel[$i][4] = '0';
            }

            if ($arrEel[$i][0] == 0) {
                for ($i1 = 0; $i1 < count($arrEel[$i][3]); $i1++) {
                    for ($i2 = 0; $i2 < count($arrEel[$i][3][$i1]); $i2++) {
                        $arrEel[$i][3][$i1][$i2][3] = $PeleksirShopArrAll[$num - 5]['id'];
                    }
                }
            }
        }
        if ($PeleksirShopArrAll[$num - 5]['elexvar'] == 0) {
            $PeleksirUsed[] = $PeleksirShopArrAll[$num - 5]['id'];
        }
        //прячем элексиры
        $mc->query("UPDATE `battle` SET `PeleksirVisible`='0' WHERE `id`='" . $PA['id'] . "' ");

        //уменьшим количество элексиров
        $mc->query("UPDATE `userbag` SET `koll` = '" . $PeleksirUserBagArrAll[$num - 5]['koll'] . "' WHERE `id` = '" . $PeleksirUserBagArrAll[$num - 5]['id'] . "'");

        for ($i = 0; is_array($arrEel) && $i < count($arrEel); $i++) {
            if (!isset($arrEel[$i][5]) || $arrEel[$i][5] == 0) {
                $arrEel[$i][1] -= 1;
            }
            //применится к герою
            if ($arrEel[$i][0] == 0) {
                $arrEel[$i][0] = 0;
                //если массив эффектов оружия hero есть массив то прибавим к нему эффект элексира игрока
                if (is_array($PweaponEffect)) {
                    $PweaponEffect = array_merge([$arrEel[$i]], $PweaponEffect);
                } else {
                    //или прибавим к нему эффект элексира mob
                    $PweaponEffect = [$arrEel[$i]];
                }
                for ($i0 = 0; is_array($arrEel[$i][3]) && $i0 < count($arrEel[$i][3]) && (!isset($arrEel[$i][5]) || $arrEel[$i][5] == 0); $i0++) {
                    //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                    if (is_array($PentityArr)) {
                        $PentityArr = array_merge(array([$arrEel[$i][3][$i0][0][0], $arrEel[$i][3][$i0][0][2]]), $PentityArr);
                    } else {
                        //или сделаем ему действие элексира
                        $PentityArr = array([$arrEel[$i][3][$i0][0][0], $arrEel[$i][3][$i0][0][2]]);
                    }
                }
                if (is_array($arrEel[$i][3]) && (!isset($arrEel[$i][5]) || 0 == $arrEel[$i][5])) {
                    $temp = $arrEel[$i][3];
                    for ($i1 = 0; $i1 < count($temp) && (!isset($arrEel[$i][5]) || 0 == $arrEel[$i][5]); $i1++) {
                        $temp[$i1][0][1] -= 1;
                    }
                    if (!isset($arrEel[$i][4]) || 0 == $arrEel[$i][4]) {
                        //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
                        if (is_array($PAlwaysEffect)) {
                            $PAlwaysEffect = array_merge($temp, $PAlwaysEffect);
                        } else {
                            $PAlwaysEffect = $temp;
                        }
                        //или при ходе героя
                    } else if (1 == $arrEel[$i][4]) {
                        //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
                        if (is_array($Phodeffect)) {
                            $Phodeffect = array_merge($temp, $Phodeffect);
                        } else {
                            $Phodeffect = $temp;
                        }
                        //или при ходе противника
                    } else if (2 == $arrEel[$i][4]) {
                        //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
                        if (is_array($Pmhodeffect)) {
                            $Pmhodeffect = array_merge($temp, $Pmhodeffect);
                        } else {
                            $Pmhodeffect = $temp;
                        }
                    }
                }
            }
            //применится к противнику
            if ($arrEel[$i][0] == 1) {
                $arrEel[$i][0] = 0;
                if (is_array($MPweaponEffect)) {
                    $MPweaponEffect = array_merge([$arrEel[$i]], $MPweaponEffect);
                } else {
                    //или прибавим к нему эффект элексира игрока
                    $MPweaponEffect = [$arrEel[$i]];
                }
                for ($i0 = 0; is_array($arrEel[$i][3]) && $i0 < count($arrEel[$i][3]) && (!isset($arrEel[$i][5]) || $arrEel[$i][5] == 0); $i0++) {
                    //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                    if (is_array($PMentityArr)) {
                        $PMentityArr = array_merge(array([$arrEel[$i][3][$i0][0][0], $arrEel[$i][3][$i0][0][2]]), $PMentityArr);
                    } else {
                        //или сделаем ему действие элексира
                        $PMentityArr = array([$arrEel[$i][3][$i0][0][0], $arrEel[$i][3][$i0][0][2]]);
                    }
                }
                $temp = $arrEel[$i][3];
                for ($i1 = 0; $i1 < count($temp) && (!isset($arrEel[$i][5]) || 0 == $arrEel[$i][5]); $i1++) {
                    $temp[$i1][0][1] -= 1;
                }
                if (is_array($arrEel[$i][3]) && (!isset($arrEel[$i][5]) || 0 == $arrEel[$i][5])) {
                    if (!isset($arrEel[$i][4]) || 0 == $arrEel[$i][4]) {
                        //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
                        if (is_array($MPAlwaysEffect)) {
                            $MPAlwaysEffect = array_merge($temp, $MPAlwaysEffect);
                        } else {
                            $MPAlwaysEffect = $temp;
                        }
                        //или при ходе героя
                    } else if (1 == $arrEel[$i][4]) {
                        //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
                        if (is_array($MPhodeffect)) {
                            $MPhodeffect = array_merge($temp, $MPhodeffect);
                        } else {
                            $MPhodeffect = $temp;
                        }
                        //или при ходе противника
                    } else if (2 == $arrEel[$i][4]) {
                        //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
                        if (is_array($MPmhodeffect)) {
                            $MPmhodeffect = array_merge($temp, $MPmhodeffect);
                        } else {
                            $MPmhodeffect = $temp;
                        }
                    }
                }
            }
        }
        $mc->query("DELETE FROM `userbag` WHERE `id_user`='" . $PA['Mid'] . "' && `id_punct` = '9' && (`koll` = '0' || `koll` < '-1') ");
    }
    /*
      PweaponEffect [
      [0:кому, 1:кол штук, 2:когда ,
      [
      [[9, 1, -100]]
      ] ],]
      0:0-герою
      0:1-противнику
      2:0-при попадании
      2:1-всегда
      2:2-по завершении количества-при попадании
      2:3-по завершении количества-всегда
      2:4-каждый * удар при попадании
      2:5-каждый * удар при попадании-всегда
     */
    //обработка эффектов на оружие при попадании 
    //если нажаты кнопки
    if ($num < 5) {
        
        for ($i = 0; is_array($PweaponEffect) && $i < count($PweaponEffect); $i++) {
            if (is_array($PweaponEffect[$i][3]) && 
                    isset($PweaponEffect[$i][3]) && 
                    count($PweaponEffect[$i][3]) && 
                    $PweaponEffect[$i][1] > 0) {

                if (!isset($PweaponEffect[$i][6])) {
                    $PweaponEffect[$i][6] = 1;
                }
                if (!isset($PweaponEffect[$i][7])) {
                    $PweaponEffect[$i][7] = 1;
                } else if ($PweaponEffect[$i][2] === 4 && ($PManimation === 8 || $PManimation === 9)) {
                    $PweaponEffect[$i][7] ++;
                } else if ($PweaponEffect[$i][2] === 5) {
                    $PweaponEffect[$i][7] ++;
                }


//обработается лишь при попадании
                if ($PManimation == 8 && $PweaponEffect[$i][2] == 0 || $PManimation == 9 && $PweaponEffect[$i][2] == 0) {
                    $PweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($PweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PentityArr)) {
                                $PentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playerSetEffect($i);
                    }
                    //применится к противнику
                    if ($PweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PMentityArr)) {
                                $PMentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PMentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playermSetEffect($i);
                    }
                }
//обработается всегда
                if ($PweaponEffect[$i][2] === 1) {
                    $PweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($PweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PentityArr)) {
                                $PentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playerSetEffect($i);
                    }
                    //применится к противнику
                    if ($PweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PMentityArr)) {
                                $PMentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PMentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playermSetEffect($i);
                    }
                }
//обработается при колличестве 1
                if ($PManimation == 8 && $PweaponEffect[$i][2] == 2 && $PweaponEffect[$i][1] == 1 || $PManimation == 9 && $PweaponEffect[$i][2] == 2 && $PweaponEffect[$i][1] == 1) {
                    $PweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($PweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PentityArr)) {
                                $PentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playerSetEffect($i);
                    }
                    //применится к противнику
                    if ($PweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PMentityArr)) {
                                $PMentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PMentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playermSetEffect($i);
                    }
                } else if ($PManimation == 8 && $PweaponEffect[$i][2] == 2 && $PweaponEffect[$i][1] > 1 || $PManimation == 9 && $PweaponEffect[$i][2] === 2 && $PweaponEffect[$i][1] > 1) {
                    $PweaponEffect[$i][1] -= 1;
                }
//обработается при колличестве 1 в любом случае
                if ($PweaponEffect[$i][2] === 3 && $PweaponEffect[$i][1] === 1) {
                    $PweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($PweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PentityArr)) {
                                $PentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playerSetEffect($i);
                    }
                    //применится к противнику
                    if ($PweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PMentityArr)) {
                                $PMentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PMentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playermSetEffect($i);
                    }
                } else if ($PweaponEffect[$i][2] === 3 && $PweaponEffect[$i][1] > 1) {
                    $PweaponEffect[$i][1] -= 1;
                }


                //обработается лишь при попадании каждый * ход
                if ($PManimation == 8 && $PweaponEffect[$i][2] == 4 && $PweaponEffect[$i][7] % $PweaponEffect[$i][6] == 0 ||
                        $PManimation == 9 && $PweaponEffect[$i][2] == 4 && $PweaponEffect[$i][7] % $PweaponEffect[$i][6] == 0) {
                    $PweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($PweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PentityArr)) {
                                $PentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playerSetEffect($i);
                    }
                    //применится к противнику
                    if ($PweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PMentityArr)) {
                                $PMentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PMentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playermSetEffect($i);
                    }
                }



                //обработается каждый * удар всегда
                if ($PweaponEffect[$i][2] === 5 && $PweaponEffect[$i][7] % $PweaponEffect[$i][6] == 0) {
                    $PweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($PweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PentityArr)) {
                                $PentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playerSetEffect($i);
                    }
                    //применится к противнику
                    if ($PweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($PweaponEffect[$i][3]) && $i0 < count($PweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие ОРУЖИЯ
                            if (is_array($PMentityArr)) {
                                $PMentityArr = array_merge(array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]), $PMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $PMentityArr = array([$PweaponEffect[$i][3][$i0][0][0], $PweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        playermSetEffect($i);
                    }
                }
            }
        }
        //удаление отработанных
        for ($i00 = 0; is_array($PweaponEffect) && $i00 < count($PweaponEffect); $i00++) {
            if (count($PweaponEffect) && $PweaponEffect[$i00][1] <= 0) {
                unset($PweaponEffect[$i00]);
                $PweaponEffect = indexaTArr($PweaponEffect);
                $i00 = -1;
            }
        }
    }
    //применение всего к статам героя
    for ($ii = 0; is_array($PentityArr) && $ii < count($PentityArr); $ii++) {
        switch ($PentityArr[$ii][0]) {
            case 2:
                $Ptochnost += $PentityArr[$ii][1];
                break;
            case 3:
                $Pblock += $PentityArr[$ii][1];
                break;
            case 4:
                $Puron += $PentityArr[$ii][1];
                break;
            case 5:
                $Pbronia += $PentityArr[$ii][1];
                break;
            case 6:
                $Poglushenie += $PentityArr[$ii][1];
                break;
            case 7:
                $Puvorot += $PentityArr[$ii][1];
                break;
        }
    }

    //применение всего к статам противника
    for ($ii = 0; is_array($PMentityArr) && $ii < count($PMentityArr); $ii++) {
        switch ($PMentityArr[$ii][0]) {
            case 2:
                $MPtochnost += $PMentityArr[$ii][1];
                break;
            case 3:
                $MPblock += $PMentityArr[$ii][1];
                break;
            case 4:
                $MPuron += $PMentityArr[$ii][1];
                break;
            case 5:
                $MPbronia += $PMentityArr[$ii][1];
                break;
            case 6:
                $MPoglushenie += $PMentityArr[$ii][1];
                break;
            case 7:
                $MPuvorot += $PMentityArr[$ii][1];
                break;
        }
    }
//обработка попаданий если попал
    if ($PManimation == 8 && $num > 1 && $num < 5 || $PManimation == 9 && $num > 1 && $num < 5) {
        //если противник уже был под щитом м оглушен или по нему попали
        if ($MPA['Pshieldonoff'] == 1 && $MPA['Panimation'] == 8 && $PManimation == 8 ||
                $MPA['Pshieldonoff'] == 0 && $PManimation == 8 ||
                $PManimation == 9) {
            $temp = 0;
            //не супер удар
            if ($suhero != 1) {
                //урон игрока
                $a = rand($Puron, $Puron + $Plevel);
                //если стиль уворот и уже был оглушен
                if ($prazgon > 1 && $PA['stil'] == 2) {
                    //делим броню на количество оглушений подряд
                    $b = ceil($MPbronia / $prazgon);
                } else {
                    //или просто броня противника
                    $b = $MPbronia;
                }
                //супер удар . 
            } else {
                //формула сушки
                $a = (($Puron * 2) + $Plevel ) + (rand(0, $Plevel * 2) * 2);
                //игнорируем броню
                $b = 0;
            }
            //если урон больше брони
            if ($a > $b) {
                //вычислим наносимый урон
                $temp = $a - $b;
                //если герой под щитом и не супер удар
                if ($Pshieldonoff == 1 && $suhero != 1) {
                    //поделим наносим урон на 2 и округлим в большую сторону
                    $temp = ceil($temp / 2);
                }
                //если наносимый урон больше хп врага 
                if ($temp > $MPlife) {
                    //сравняем наносимый урон с хп врага
                    $temp = $MPlife;
                }
                //или запишем наносимый урон 1
            } else {
                $temp = 1;
            }
            if (is_array($PMentityArr)) {
                $PMentityArr = array_merge(array([10, -($temp)]), $PMentityArr);
            } else {
                $PMentityArr = array([10, -($temp)]);
            }
        }
    }
    //применение всего к статам героя
    for ($ii = 0; is_array($PentityArr) && $ii < count($PentityArr); $ii++) {
        if ($PentityArr[$ii][1] < 0) {
            switch ($PentityArr[$ii][0]) {
                case 10:
                    $Plife += $PentityArr[$ii][1];
                    if ($PentityArr[$ii][1] < 0) {
                        $MRuron += abs($PentityArr[$ii][1]);
                    }
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 0:
                    $Plife += $PentityArr[$ii][1];
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 1:
                    $Pflife += $PentityArr[$ii][1];
                    $Plife += $PentityArr[$ii][1];
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 8:
                    $Pflife += $PentityArr[$ii][1];
                    $Plife += $PentityArr[$ii][1];
                    if ($PentityArr[$ii][1] < 0) {
                        $MRuron += abs($PentityArr[$ii][1]);
                    }
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 9:
                    $Plife += $PentityArr[$ii][1];
                    if ($PentityArr[$ii][1] < 0) {
                        $MRuron += abs($PentityArr[$ii][1]);
                    }
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
            }
        }
    }
    if ($Plife > 0) {
        for ($ii = 0; is_array($PentityArr) && $ii < count($PentityArr); $ii++) {
            if ($PentityArr[$ii][1] > 0) {
                switch ($PentityArr[$ii][0]) {
                    case 10:
                        $Plife += $PentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 0:
                        $Plife += $PentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 1:
                        $Pflife += $PentityArr[$ii][1];
                        $Plife += $PentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 8:
                        $Pflife += $PentityArr[$ii][1];
                        $Plife += $PentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 9:
                        $Plife += $PentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                }
            }
        }
    }
    //применение всего к статам противника
    for ($ii = 0; is_array($PMentityArr) && $ii < count($PMentityArr); $ii++) {
        if ($PMentityArr[$ii][1] < 0) {
            switch ($PMentityArr[$ii][0]) {
                case 10:
                    $MPlife += $PMentityArr[$ii][1];
                    if ($PMentityArr[$ii][1] < 0) {
                        $PRuron += abs($PMentityArr[$ii][1]);
                    }
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 0:
                    $MPlife += $PMentityArr[$ii][1];
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 1:
                    $MPflife += $PMentityArr[$ii][1];
                    $MPlife += $PMentityArr[$ii][1];
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 8:
                    $MPflife += $PMentityArr[$ii][1];
                    $MPlife += $PMentityArr[$ii][1];
                    if ($PMentityArr[$ii][1] < 0) {
                        $PRuron += abs($PMentityArr[$ii][1]);
                    }
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 9:
                    $MPlife += $PMentityArr[$ii][1];
                    if ($PMentityArr[$ii][1] < 0) {
                        $PRuron += abs($PMentityArr[$ii][1]);
                    }
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
            }
        }
    }
    if ($MPlife > 0) {
        for ($ii = 0; is_array($PMentityArr) && $ii < count($PMentityArr); $ii++) {
            if ($PMentityArr[$ii][1] > 0) {
                switch ($PMentityArr[$ii][0]) {
                    case 10:
                        $MPlife += $PMentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 0:
                        $MPlife += $PMentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 1:
                        $MPflife += $PMentityArr[$ii][1];
                        $MPlife += $PMentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 8:
                        $MPflife += $PMentityArr[$ii][1];
                        $MPlife += $PMentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 9:
                        $MPlife += $PMentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                }
            }
        }
    }
    //сложение параметров энтити игрока
    for ($i = 0; is_array($PentityArr) && $i < count($PentityArr); $i++) {
        for ($i0 = 1; is_array($PentityArr) && $i0 < count($PentityArr); $i0++) {
            if ($i0 + $i < count($PentityArr)) {
                if ($PentityArr[$i][0] === $PentityArr[$i0 + $i][0]) {
                    $PentityArr[$i][1] += $PentityArr[$i0 + $i][1];
                    unset($PentityArr[$i0 + $i]);
                    $PentityArr = indexaTArr($PentityArr);
                    $i0 -= 1;
                }
            }
        }
    }
    //очистка от 0
    for ($i = 0; is_array($PentityArr) && $i < count($PentityArr); $i++) {
        if ($i < count($PentityArr)) {
            if ($PentityArr[$i][1] === 0) {
                unset($PentityArr[$i]);
                $PentityArr = indexaTArr($PentityArr);
                $i = -1;
            }
        }
    }
    //сложение параметров энтити противника
    for ($i = 0; is_array($PMentityArr) && $i < count($PMentityArr); $i++) {
        for ($i0 = 1; is_array($PMentityArr) && $i0 < count($PMentityArr); $i0++) {
            if ($i0 + $i < count($PMentityArr)) {
                if ($PMentityArr[$i][0] === $PMentityArr[$i0 + $i][0]) {
                    $PMentityArr[$i][1] += $PMentityArr[$i0 + $i][1];
                    unset($PMentityArr[$i0 + $i]);
                    $PMentityArr = indexaTArr($PMentityArr);
                    $i0 -= 1;
                }
            }
        }
    }
    //очистка от 0
    for ($i = 0; is_array($PMentityArr) && $i < count($PMentityArr); $i++) {
        if ($i < count($PMentityArr)) {
            if ($PMentityArr[$i][1] === 0) {
                unset($PMentityArr[$i]);
                $PMentityArr = indexaTArr($PMentityArr);
                $i = -1;
            }
        }
    }

    if (is_array($PMentityArr)) {
        if (is_array($MPentityEffect)) {
            $MPentityEffect = array_merge($MPentityEffect, $PMentityArr);
        } else {
            $MPentityEffect = $PMentityArr;
        }

        $PMentityArr = json_encode($PMentityArr);
    }

    if (is_array($PentityArr)) {
        if (is_array($MMentityEffect)) {
            $MMentityEffect = array_merge($MMentityEffect, $PentityArr);
        } else {
            $MMentityEffect = $PentityArr;
        }

        $PentityArr = json_encode($PentityArr);
    }
    if (!is_array($PAlwaysEffect)) {
        $PAlwaysEffect = [];
    }
    if (!is_array($Phodeffect)) {
        $Phodeffect = [];
    }
    if (!is_array($Pmhodeffect)) {
        $Pmhodeffect = [];
    }
    if (!is_array($PweaponEffect)) {
        $PweaponEffect = [];
    }
    $mc->query("UPDATE `battle` SET "
            . "`Pflife` = '" . $Pflife . "',"
            . "`Plife` = '" . $Plife . "',"
            . "`Ptochnost` = '" . $Ptochnost . "',"
            . "`Pblock` = '" . $Pblock . "',"
            . "`Puron` = '" . $Puron . "',"
            . "`Pbronia` = '" . $Pbronia . "',"
            . "`Poglushenie` = '" . $Poglushenie . "',"
            . "`Puvorot` = '" . $Puvorot . "',"
            . "`Pshieldnum` = '" . $Pshieldnum . "',"
            . "`PAlwaysEffect` = '" . json_encode($PAlwaysEffect) . "',"
            . "`Phodeffect` = '" . json_encode($Phodeffect) . "',"
            . "`Pmhodeffect` = '" . json_encode($Pmhodeffect) . "',"
            . "`PeleksirVisible` = '" . $PeleksirVisible . "',"
            . "`PeleksirUsed` = '" . json_encode($PeleksirUsed) . "',"
            . "`PweaponEffect` = '" . json_encode($PweaponEffect) . "',"
            . "`Ruron` = `Ruron`+'" . $PRuron . "',"
            . "`numudar` = '$Pnumudar',"
            . "`super` = '$Psuper'"
            . " WHERE `id`='" . $PA['id'] . "' ");
    if (!is_array($MPAlwaysEffect)) {
        $MPAlwaysEffect = [];
    }
    if (!is_array($MPhodeffect)) {
        $MPhodeffect = [];
    }
    if (!is_array($MPmhodeffect)) {
        $MPmhodeffect = [];
    }
    if (!is_array($MPweaponEffect)) {
        $MPweaponEffect = [];
    }
    if (!is_array($MPentityEffect)) {
        $MPentityEffect = [];
    }
    if (!is_array($MMentityEffect)) {
        $MMentityEffect = [];
    }
    $mc->query("UPDATE `battle` SET "
            . "`Pflife` = '" . $MPflife . "',"
            . "`Plife` = '" . $MPlife . "',"
            . "`Ptochnost` = '" . $MPtochnost . "',"
            . "`Pblock` = '" . $MPblock . "',"
            . "`Puron` = '" . $MPuron . "',"
            . "`Pbronia` = '" . $MPbronia . "',"
            . "`Poglushenie` = '" . $MPoglushenie . "',"
            . "`Puvorot` = '" . $MPuvorot . "',"
            . "`Pshieldnum` = '" . $MPshieldnum . "',"
            . "`PAlwaysEffect` = '" . json_encode($MPAlwaysEffect) . "',"
            . "`Phodeffect` = '" . json_encode($MPhodeffect) . "',"
            . "`Pmhodeffect` = '" . json_encode($MPmhodeffect) . "',"
            . "`PweaponEffect` = '" . json_encode($MPweaponEffect) . "',"
            . "`PentityEffect` = '" . json_encode($MPentityEffect) . "',"
            . "`MentityEffect` = '" . json_encode($MMentityEffect) . "',"
            . "`Ruron` = `Ruron`+'" . $MRuron . "'"
            . " WHERE `id`='" . $MPA['id'] . "' ");
}

function Mturn($num) {
    global $MPauto;
    global $MPid;
    global $mrazgon;
    global $mc;
    global $PA;
    global $MPA;


    global $MPlevel;
    global $PManimation;
    global $MPAlwaysEffect;
    global $MPhodeffect;
    global $MPmhodeffect;
    global $MPeleksirVisible;
    global $MPweaponEffect;
    global $MPentityArr;
    global $MPMentityArr;



    global $Plevel;
    global $Panimation;
    global $PAlwaysEffect;
    global $Phodeffect;
    global $Pmhodeffect;
    global $PweaponEffect;
    global $PentityEffect;
    global $PMentityEffect;

//обработка параметров героев при ударе
    global $MPflife;
    global $MPlife;
    global $MPtochnost;
    global $MPblock;
    global $MPuron;
    global $MPbronia;
    global $MPoglushenie;
    global $MPuvorot;
    global $MPshieldnum;
    global $MPshieldonoff;
    global $MRuron;
    global $MPnumudar;
    global $MPsuper;

    global $Pflife;
    global $Plife;
    global $Ptochnost;
    global $Pblock;
    global $Puron;
    global $PRuron;
    global $Pbronia;
    global $Poglushenie;
    global $Puvorot;
    global $Pshieldnum;
    global $Pshieldonoff;

    global $Phodc;
    global $MPhodc;

    if ($num > 1 && $num < 5) {
        $MPhodc = $Phodc + 1;
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '0',`Phodc` = '$MPhodc'  WHERE `id`='" . $MPA['id'] . "' ");
        $mc->query("UPDATE `battle` SET `counter`='" . time() . "',`Phodtime` = '" . time() . "',`Phod` = '1',`PeleksirVisible`='1' WHERE `id`='" . $PA['id'] . "' ");

        $PManimation = $num;
        //супер удары противника
        $suhero = 0;
        if ($MPsuper != "" && $MPauto == 0 || $MPsuper != "" && $MPid == -1) {
            $arrsuhero = explode(",", $MPsuper);
            for ($i = 0; is_array($arrsuhero) && $i < count($arrsuhero); $i++) {
                if (strstr($MPnumudar, $arrsuhero[$i])) {
                    unset($arrsuhero[$i]);
                    $arrsuhero = indexaTArr($arrsuhero);
                    $PManimation = 5;
                    $MPsuper = implode(",", $arrsuhero);
                    $suhero = 1;
                    $MPnumudar = "";
                    break;
                }
            }
        }
        //генерация попаданий
        //точность героя
        $t0 = rand($MPtochnost, $MPtochnost + $MPlevel);
        //уворот противника
        $u0 = rand($Puvorot, $Puvorot + $Plevel);
        //флаг попадания
        $popal = 0;
        $popal2 = 0;
        $glush = rand($MPoglushenie, $MPoglushenie + $MPlevel) > rand($Pbronia, $Pbronia + ($Plevel * 12)) && !udarpopalbool();
        //если точность больше уворота то попал
        if ($t0 > $u0) {
            $popal = 1;
        } else {
            //или проверим по второй формуле
            if (rand($t0, $u0 * 1.1) > $u0) {
                $popal = 1;
            } else if (udarpopalbool()) {
                $popal = 1;
            }
        }
        //если точность больше уворота то попал
        if ($t0 > $Pblock) {
            $popal2 = 1;
        }
        if ($Pshieldonoff === 0) {
            if ($popal) {
                //глушение
                if ($glush) {
                    $Panimation = 8;
                } else {
                    $Panimation = 9;
                }
            } else if ($Panimation === 8) {
                $Panimation = 9;
            } else {
                $Panimation = 6;
            }
        }
        //точка больше блока противника в щите
        if ($Pshieldonoff === 1) {
            if ($popal) {
                if ($popal2 || $glush) {
                    //глушение
                    if ($glush) {
                        $Panimation = 8;
                    } else {
                        $Panimation = 9;
                    }
                } else if ($Panimation == 8) {
                    $Panimation = 9;
                } else {
                    $Pshieldnum--;
                    if ($Pshieldnum < 1) {
                        $Pshieldonoff = 0;
                    }
                    $Panimation = $Pshieldonoff;
                }
            } else {
                $Panimation = 6;
            }
        }

        //сброс анимаций игрока в бд
        if ($Panimation < 7 || $Panimation > 8) {
            $mc->query("UPDATE `battle` SET `Panimation` = '" . $Pshieldonoff . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        } else {
            $mc->query("UPDATE `battle` SET `Panimation` = '" . $Panimation . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        }
        if ($PManimation < 7 || $PManimation > 8) {
            $mc->query("UPDATE `battle` SET `Manimation` = '" . $MPshieldonoff . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        } else {
            $mc->query("UPDATE `battle` SET `Manimation` = '" . $PManimation . "',`Plife`='" . $Plife . "' WHERE `id`='" . $PA['id'] . "' ");
        }
        $mc->query("UPDATE `battle` SET `Panimation` = '" . $PManimation . "',`Manimation` = '" . $Panimation . "',`Plife`='" . $MPlife . "' WHERE `id`='" . $MPA['id'] . "' ");
    }

//arr:[0:[0:[0:2,1:5,2:1]]]
    if ($num > 1 && $num < 5) {
        //удаление отработанных моба при ходе обоих
        for ($i0 = 0; is_array($MPAlwaysEffect) && $i0 < count($MPAlwaysEffect); $i0++) {
            if (!count($MPAlwaysEffect[$i0])) {
                unset($MPAlwaysEffect[$i0]);
                $MPAlwaysEffect = indexaTArr($MPAlwaysEffect);
                $i0 = -1;
            } else if (count($MPAlwaysEffect[$i0]) && $MPAlwaysEffect[$i0][0][1] <= 0) {
                unset($MPAlwaysEffect[$i0][0]);
                $MPAlwaysEffect = indexaTArr($MPAlwaysEffect);
                $i0 = -1;
            }
        }
        //применение эффектов моба
        for ($i = 0; is_array($MPAlwaysEffect) && $i < count($MPAlwaysEffect); $i++) {
            if (count($MPAlwaysEffect[$i][0]) && $MPAlwaysEffect[$i][0][1] > 0) {
                $MPAlwaysEffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($MPentityArr)) {
                    $MPentityArr = array_merge(array([$MPAlwaysEffect[$i][0][0], $MPAlwaysEffect[$i][0][2]]), $MPentityArr);
                } else {
                    $MPentityArr = array([$MPAlwaysEffect[$i][0][0], $MPAlwaysEffect[$i][0][2]]);
                }
            }
        }
        //удаление отработанных моба при ходе моба
        for ($i0 = 0; is_array($MPhodeffect) && $i0 < count($MPhodeffect); $i0++) {
            if (!count($MPhodeffect[$i0])) {
                unset($MPhodeffect[$i0]);
                $MPhodeffect = indexaTArr($MPhodeffect);
                $i0 = -1;
            } else if (count($MPhodeffect[$i0]) && $MPhodeffect[$i0][0][1] <= 0) {
                unset($MPhodeffect[$i0][0]);
                $MPhodeffect = indexaTArr($MPhodeffect);
                $i0 = -1;
            }
        }
        //применение эффектов игрока
        for ($i = 0; is_array($MPhodeffect) && $i < count($MPhodeffect); $i++) {
            if (count($MPhodeffect[$i][0]) && $MPhodeffect[$i][0][1] > 0) {
                $MPhodeffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($MPentityArr)) {
                    $MPentityArr = array_merge(array([$MPhodeffect[$i][0][0], $MPhodeffect[$i][0][2]]), $MPentityArr);
                } else {
                    $MPentityArr = array([$MPhodeffect[$i][0][0], $MPhodeffect[$i][0][2]]);
                }
            }
        }





        //удаление отработанных эффектов героя при ходе обоих
        for ($i0 = 0; is_array($PAlwaysEffect) && $i0 < count($PAlwaysEffect); $i0++) {
            if (!count($PAlwaysEffect[$i0])) {
                unset($PAlwaysEffect[$i0]);
                $PAlwaysEffect = indexaTArr($PAlwaysEffect);
                $i0 = -1;
            } else if (count($PAlwaysEffect[$i0]) && $PAlwaysEffect[$i0][0][1] <= 0) {
                unset($PAlwaysEffect[$i0][0]);
                $PAlwaysEffect = indexaTArr($PAlwaysEffect);
                $i0 = -1;
            }
        }
        //применение эффектов героя при ходе обоих
        for ($i = 0; is_array($PAlwaysEffect) && $i < count($PAlwaysEffect); $i++) {
            if (count($PAlwaysEffect[$i][0]) && $PAlwaysEffect[$i][0][1] > 0) {
                $PAlwaysEffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($MPMentityArr)) {
                    $MPMentityArr = array_merge(array([$PAlwaysEffect[$i][0][0], $PAlwaysEffect[$i][0][2]]), $MPMentityArr);
                } else {
                    $MPMentityArr = array([$PAlwaysEffect[$i][0][0], $PAlwaysEffect[$i][0][2]]);
                }
            }
        }
        //удаление отработанных эффектов героя при ходе моба
        for ($i0 = 0; is_array($Pmhodeffect) && $i0 < count($Pmhodeffect); $i0++) {
            if (!count($Pmhodeffect[$i0])) {
                unset($Pmhodeffect[$i0]);
                $Pmhodeffect = indexaTArr($Pmhodeffect);
                $i0 = -1;
            } else if (count($Pmhodeffect[$i0]) && $Pmhodeffect[$i0][0][1] <= 0) {
                unset($Pmhodeffect[$i0][0]);
                $Pmhodeffect = indexaTArr($Pmhodeffect);
                $i0 = -1;
            }
        }
        //применение эффектов эффектов героя при ходе моба
        for ($i = 0; is_array($Pmhodeffect) && $i < count($Pmhodeffect); $i++) {
            if (count($Pmhodeffect[$i][0]) && $Pmhodeffect[$i][0][1] > 0) {
                $Pmhodeffect[$i][0][1] -= 1;
                //отправка на отрисоку энтити
                if (is_array($MPMentityArr)) {
                    $MPMentityArr = array_merge(array([$Pmhodeffect[$i][0][0], $Pmhodeffect[$i][0][2]]), $MPMentityArr);
                } else {
                    $MPMentityArr = array([$Pmhodeffect[$i][0][0], $Pmhodeffect[$i][0][2]]);
                }
            }
        }
    }


    /*
      PweaponEffect [
      [0:кому, 1:кол штук, 2:когда ,
      [
      [[9, 1, -100]]
      ] ],]
      0-герою 1-противнику
      2:0-при попадании 2:1-всегда 2:2-по завершении количества-при попадании 3:-по завершении количества-всегда */
    //обработка эффектов на оружие при попадании 
    //если нажаты кнопки
    if ($num < 5) {
        for ($i = 0; is_array($MPweaponEffect) && $i < count($MPweaponEffect); $i++) {
            if (is_array($MPweaponEffect[$i][3]) && count($MPweaponEffect[$i][3]) && $MPweaponEffect[$i][1] > 0) {

                if (!isset($MPweaponEffect[$i][6])) {
                    $MPweaponEffect[$i][6] = 1;
                }
                if (!isset($MPweaponEffect[$i][7])) {
                    $MPweaponEffect[$i][7] = 1;
                } else if ($MPweaponEffect[$i][2] === 4 && ($Panimation === 8 || $Panimation === 9)) {
                    $MPweaponEffect[$i][7] ++;
                } else if ($MPweaponEffect[$i][2] === 5) {
                    $MPweaponEffect[$i][7] ++;
                }



//обработается лишь при попадании
                if ($Panimation === 8 && $MPweaponEffect[$i][2] === 0 || $Panimation === 9 && $MPweaponEffect[$i][2] === 0) {
                    $MPweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($MPweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPentityArr)) {
                                $MPentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayerSetEffect($i);
                    }
                    //применится к противнику
                    if ($MPweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPMentityArr)) {
                                $MPMentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPMentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayermSetEffect($i);
                    }
                }
                //обработается всегда
                if ($MPweaponEffect[$i][2] === 1) {
                    $MPweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($MPweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPentityArr)) {
                                $MPentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayerSetEffect($i);
                    }
                    //применится к противнику
                    if ($MPweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPMentityArr)) {
                                $MPMentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPMentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayermSetEffect($i);
                    }
                }
                //обработается при колличестве 1
                if ($Panimation === 8 && $MPweaponEffect[$i][2] === 2 && $MPweaponEffect[$i][1] === 1 || $Panimation === 9 && $MPweaponEffect[$i][2] === 2 && $MPweaponEffect[$i][1] === 1) {
                    $MPweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($MPweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPentityArr)) {
                                $MPentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayerSetEffect($i);
                    }
                    //применится к противнику
                    if ($MPweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPMentityArr)) {
                                $MPMentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPMentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayermSetEffect($i);
                    }
                } else if ($Panimation === 8 && $MPweaponEffect[$i][2] === 2 && $MPweaponEffect[$i][1] > 1 || $Panimation === 9 && $MPweaponEffect[$i][2] === 2 && $MPweaponEffect[$i][1] > 1) {
                    $MPweaponEffect[$i][1] -= 1;
                }
                //обработается при колличестве 1 в любом случае
                if ($MPweaponEffect[$i][2] === 3 && $MPweaponEffect[$i][1] === 1) {
                    $MPweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($MPweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPentityArr)) {
                                $MPentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayerSetEffect($i);
                    }
                    //применится к противнику
                    if ($MPweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPMentityArr)) {
                                $MPMentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPMentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayermSetEffect($i);
                    }
                } else if ($MPweaponEffect[$i][2] === 3 && $MPweaponEffect[$i][1] > 1) {
                    $MPweaponEffect[$i][1] -= 1;
                }


                //обработается каждый * удар при попаданиях
                if ($Panimation === 8 && $MPweaponEffect[$i][2] === 4 && $MPweaponEffect[$i][7] % $MPweaponEffect[$i][6] == 0 ||
                        $Panimation === 9 && $MPweaponEffect[$i][2] === 4 && $MPweaponEffect[$i][7] % $MPweaponEffect[$i][6] == 0) {
                    $MPweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($MPweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPentityArr)) {
                                $MPentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayerSetEffect($i);
                    }
                    //применится к противнику
                    if ($MPweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPMentityArr)) {
                                $MPMentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPMentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayermSetEffect($i);
                    }
                }
                //обработается каждый * удар всегда
                if ($MPweaponEffect[$i][2] === 5 && $MPweaponEffect[$i][7] % $MPweaponEffect[$i][6] == 0) {
                    $MPweaponEffect[$i][1] -= 1;
                    //применится к герою
                    if ($MPweaponEffect[$i][0] === 0) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPentityArr)) {
                                $MPentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayerSetEffect($i);
                    }
                    //применится к противнику
                    if ($MPweaponEffect[$i][0] === 1) {
                        for ($i0 = 0; is_array($MPweaponEffect[$i][3]) && $i0 < count($MPweaponEffect[$i][3]); $i0++) {
                            //если массив энтити игрока есть массив то прибавим к нему действие элексира
                            if (is_array($MPMentityArr)) {
                                $MPMentityArr = array_merge(array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]), $MPMentityArr);
                            } else {
                                //или сделаем ему действие элексира
                                $MPMentityArr = array([$MPweaponEffect[$i][3][$i0][0][0], $MPweaponEffect[$i][3][$i0][0][2]]);
                            }
                        }
                        mplayermSetEffect($i);
                    }
                }
            }
        }
        //удаление отработанных
        for ($i00 = 0; is_array($MPweaponEffect) && $i00 < count($MPweaponEffect); $i00++) {
            if (count($MPweaponEffect) && $MPweaponEffect[$i00][1] <= 0) {
                unset($MPweaponEffect[$i00]);
                $MPweaponEffect = indexaTArr($MPweaponEffect);
                $i00 = -1;
            }
        }
    }
    //применение всего к статам героя
    for ($ii = 0; is_array($MPentityArr) && $ii < count($MPentityArr); $ii++) {
        switch ($MPentityArr[$ii][0]) {
            case 2:
                $MPtochnost += $MPentityArr[$ii][1];
                break;
            case 3:
                $MPblock += $MPentityArr[$ii][1];
                break;
            case 4:
                $MPuron += $MPentityArr[$ii][1];
                break;
            case 5:
                $MPbronia += $MPentityArr[$ii][1];
                break;
            case 6:
                $MPoglushenie += $MPentityArr[$ii][1];
                break;
            case 7:
                $MPuvorot += $MPentityArr[$ii][1];
                break;
        }
    }
    //применение всего к статам противника
    for ($ii = 0; is_array($MPMentityArr) && $ii < count($MPMentityArr); $ii++) {
        switch ($MPMentityArr[$ii][0]) {
            case 2:
                $Ptochnost += $MPMentityArr[$ii][1];
                break;
            case 3:
                $Pblock += $MPMentityArr[$ii][1];
                break;
            case 4:
                $Puron += $MPMentityArr[$ii][1];
                break;
            case 5:
                $Pbronia += $MPMentityArr[$ii][1];
                break;
            case 6:
                $Poglushenie += $MPMentityArr[$ii][1];
                break;
            case 7:
                $Puvorot += $MPMentityArr[$ii][1];
                break;
        }
    }
//обработка попаданий если попал&& $MPshieldonoff === 0
    if ($Panimation == 8 && $num > 1 && $num < 5 || $Panimation == 9 && $num > 1 && $num < 5) {
        //если противник уже был под щитом м оглушен или по нему попали
        if ($PA['Pshieldonoff'] == 1 && $PA['Panimation'] == 8 && $Panimation == 8 ||
                $PA['Pshieldonoff'] == 0 && $Panimation == 8 ||
                $Panimation == 9) {
            $temp = 0;
            if ($suhero != 1) {
                $a = rand($MPuron, $MPuron + $MPlevel);
                if ($mrazgon > 1 && $MPA['stil'] == 2) {
                    $b = ceil($Pbronia / $mrazgon);
                } else {
                    $b = $Pbronia;
                }
            } else {
                $a = (($MPuron * 2) + $MPlevel ) + (rand(0, $MPlevel * 2) * 2);
                $b = 0;
            }
            if ($a > $b) {
                $temp = $a - $b;
                if ($MPshieldonoff == 1 && $suhero != 1) {
                    $temp = ceil($temp / 2);
                }
                if ($temp > $Plife) {
                    $temp = $Plife;
                }
            } else {
                $temp = 1;
            }
            if (is_array($MPMentityArr)) {
                $MPMentityArr = array_merge(array([10, -($temp)]), $MPMentityArr);
            } else {
                $MPMentityArr = array([10, -($temp)]);
            }
        }
    }
    //применение всего к статам героя
    for ($ii = 0; is_array($MPentityArr) && $ii < count($MPentityArr); $ii++) {
        if ($MPentityArr[$ii][1] < 0) {
            switch ($MPentityArr[$ii][0]) {
                case 10:
                    $MPlife += $MPentityArr[$ii][1];
                    if ($MPentityArr[$ii][1] < 0) {
                        $PRuron += abs($MPentityArr[$ii][1]);
                    }
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 0:
                    $MPlife += $MPentityArr[$ii][1];
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 1:
                    $MPflife += $MPentityArr[$ii][1];
                    $MPlife += $MPentityArr[$ii][1];
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 8:
                    $MPflife += $MPentityArr[$ii][1];
                    $MPlife += $MPentityArr[$ii][1];
                    if ($MPentityArr[$ii][1] < 0) {
                        $PRuron += abs($MPentityArr[$ii][1]);
                    }
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
                case 9:
                    $MPlife += $MPentityArr[$ii][1];
                    if ($MPentityArr[$ii][1] < 0) {
                        $PRuron += abs($MPentityArr[$ii][1]);
                    }
                    if ($MPlife > $MPflife) {
                        $MPlife = $MPflife;
                    }
                    break;
            }
        }
    }
    if ($MPlife > 0) {
        for ($ii = 0; is_array($MPentityArr) && $ii < count($MPentityArr); $ii++) {
            if ($MPentityArr[$ii][1] > 0) {
                switch ($MPentityArr[$ii][0]) {
                    case 10:
                        $MPlife += $MPentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 0:
                        $MPlife += $MPentityArr[$ii][1];
                        break;
                    case 1:
                        $MPflife += $MPentityArr[$ii][1];
                        $MPlife += $MPentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 8:
                        $MPflife += $MPentityArr[$ii][1];
                        $MPlife += $MPentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                    case 9:
                        $MPlife += $MPentityArr[$ii][1];
                        if ($MPlife > $MPflife) {
                            $MPlife = $MPflife;
                        }
                        break;
                }
            }
        }
    }
    //применение всего к статам противника
    for ($ii = 0; is_array($MPMentityArr) && $ii < count($MPMentityArr); $ii++) {
        if ($MPMentityArr[$ii][1] < 0) {
            switch ($MPMentityArr[$ii][0]) {
                case 10:
                    $Plife += $MPMentityArr[$ii][1];
                    if ($MPMentityArr[$ii][1] < 0) {
                        $MRuron += abs($MPMentityArr[$ii][1]);
                    }
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 0:
                    $Plife += $MPMentityArr[$ii][1];
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 1:
                    $Pflife += $MPMentityArr[$ii][1];
                    $Plife += $MPMentityArr[$ii][1];
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 8:
                    $Pflife += $MPMentityArr[$ii][1];
                    $Plife += $MPMentityArr[$ii][1];
                    if ($MPMentityArr[$ii][1] < 0) {
                        $MRuron += abs($MPMentityArr[$ii][1]);
                    }
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
                case 9:
                    $Plife += $MPMentityArr[$ii][1];
                    if ($MPMentityArr[$ii][1] < 0) {
                        $MRuron += abs($MPMentityArr[$ii][1]);
                    }
                    if ($Plife > $Pflife) {
                        $Plife = $Pflife;
                    }
                    break;
            }
        }
    }
    if ($Plife > 0) {
        for ($ii = 0; is_array($MPMentityArr) && $ii < count($MPMentityArr); $ii++) {
            if ($MPMentityArr[$ii][1] > 0) {
                switch ($MPMentityArr[$ii][0]) {
                    case 10:
                        $Plife += $MPMentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 0:
                        $Plife += $MPMentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 1:
                        $Pflife += $MPMentityArr[$ii][1];
                        $Plife += $MPMentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 8:
                        $Pflife += $MPMentityArr[$ii][1];
                        $Plife += $MPMentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                    case 9:
                        $Plife += $MPMentityArr[$ii][1];
                        if ($Plife > $Pflife) {
                            $Plife = $Pflife;
                        }
                        break;
                }
            }
        }
    }
    //сложение параметров энтити игрока
    for ($i = 0; is_array($MPentityArr) && $i < count($MPentityArr); $i++) {
        for ($i0 = 1; is_array($MPentityArr) && $i0 < count($MPentityArr); $i0++) {
            if ($i0 + $i < count($MPentityArr)) {
                if ($MPentityArr[$i][0] === $MPentityArr[$i0 + $i][0]) {
                    $MPentityArr[$i][1] += $MPentityArr[$i0 + $i][1];
                    unset($MPentityArr[$i0 + $i]);
                    $MPentityArr = indexaTArr($MPentityArr);
                    $i0 -= 1;
                }
            }
        }
    }
    //очистка от 0
    for ($i = 0; is_array($MPentityArr) && $i < count($MPentityArr); $i++) {
        if ($i < count($MPentityArr)) {
            if ($MPentityArr[$i][1] === 0) {
                unset($MPentityArr[$i]);
                $MPentityArr = indexaTArr($MPentityArr);
                $i = -1;
            }
        }
    }
    //сложение параметров энтити противника
    for ($i = 0; is_array($MPMentityArr) && $i < count($MPMentityArr); $i++) {
        for ($i0 = 1; is_array($MPMentityArr) && $i0 < count($MPMentityArr); $i0++) {
            if ($i0 + $i < count($MPMentityArr)) {
                if ($MPMentityArr[$i][0] === $MPMentityArr[$i0 + $i][0]) {
                    $MPMentityArr[$i][1] += $MPMentityArr[$i0 + $i][1];
                    unset($MPMentityArr[$i0 + $i]);
                    $MPMentityArr = indexaTArr($MPMentityArr);
                    $i0 -= 1;
                }
            }
        }
    }
    //очистка от 0
    for ($i = 0; is_array($MPMentityArr) && $i < count($MPMentityArr); $i++) {
        if ($i < count($MPMentityArr)) {
            if ($MPMentityArr[$i][1] === 0) {
                unset($MPMentityArr[$i]);
                $MPMentityArr = indexaTArr($MPMentityArr);
                $i = -1;
            }
        }
    }

    if (is_array($MPMentityArr)) {
        if (is_array($PentityEffect)) {
            $PentityEffect = array_merge($PentityEffect, $MPMentityArr);
        } else {
            $PentityEffect = $MPMentityArr;
        }
        $MPMentityArr = json_encode($MPMentityArr);
    }
    if (is_array($MPentityArr)) {
        if (is_array($PMentityEffect)) {
            $PMentityEffect = array_merge($PMentityEffect, $MPentityArr);
        } else {
            $PMentityEffect = $MPentityArr;
        }
        $MPentityArr = json_encode($MPentityArr);
    }
    if (!is_array($MPAlwaysEffect)) {
        $MPAlwaysEffect = [];
    }
    if (!is_array($MPhodeffect)) {
        $MPhodeffect = [];
    }
    if (!is_array($MPmhodeffect)) {
        $MPmhodeffect = [];
    }
    if (!is_array($MPweaponEffect)) {
        $MPweaponEffect = [];
    }
    $mc->query("UPDATE `battle` SET "
            . "`Pflife` = '" . $MPflife . "',"
            . "`Plife` = '" . $MPlife . "',"
            . "`Ptochnost` = '" . $MPtochnost . "',"
            . "`Pblock` = '" . $MPblock . "',"
            . "`Puron` = '" . $MPuron . "',"
            . "`Pbronia` = '" . $MPbronia . "',"
            . "`Poglushenie` = '" . $MPoglushenie . "',"
            . "`Puvorot` = '" . $MPuvorot . "',"
            . "`Pshieldnum` = '" . $MPshieldnum . "',"
            . "`PAlwaysEffect` = '" . json_encode($MPAlwaysEffect) . "',"
            . "`Phodeffect` = '" . json_encode($MPhodeffect) . "',"
            . "`Pmhodeffect` = '" . json_encode($MPmhodeffect) . "',"
            . "`PeleksirVisible` = '" . $MPeleksirVisible . "',"
            . "`PweaponEffect` = '" . json_encode($MPweaponEffect) . "',"
            . "`Ruron` = `Ruron`+'" . $MRuron . "',"
            . "`numudar` = '" . $MPnumudar . "',"
            . "`super` = '" . $MPsuper . "'"
            . " WHERE `id`='" . $MPA['id'] . "' ");
    if (!is_array($PAlwaysEffect)) {
        $PAlwaysEffect = [];
    }
    if (!is_array($Phodeffect)) {
        $Phodeffect = [];
    }
    if (!is_array($Pmhodeffect)) {
        $Pmhodeffect = [];
    }
    if (!is_array($PweaponEffect)) {
        $PweaponEffect = [];
    }
    $mc->query("UPDATE `battle` SET "
            . "`Pflife` = '" . $Pflife . "',"
            . "`Plife` = '" . $Plife . "',"
            . "`Ptochnost` = '" . $Ptochnost . "',"
            . "`Pblock` = '" . $Pblock . "',"
            . "`Puron` = '" . $Puron . "',"
            . "`Pbronia` = '" . $Pbronia . "',"
            . "`Poglushenie` = '" . $Poglushenie . "',"
            . "`Puvorot` = '" . $Puvorot . "',"
            . "`Pshieldnum` = '" . $Pshieldnum . "',"
            . "`PAlwaysEffect` = '" . json_encode($PAlwaysEffect) . "',"
            . "`Phodeffect` = '" . json_encode($Phodeffect) . "',"
            . "`Pmhodeffect` = '" . json_encode($Pmhodeffect) . "',"
            . "`PweaponEffect` = '" . json_encode($PweaponEffect) . "',"
            . "`Ruron` = `Ruron`+'" . $PRuron . "'"
            . " WHERE `id`='" . $PA['id'] . "' ");
}

function chekCommand() {
    global $mc;
    global $PA;
    global $PlayerArr;
    //получения инфы о окончании боя
    $resAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "'")->num_rows;
    $resAllCommandend = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `end_battle`='1'")->num_rows;

    //получения инфы о командах
    $resUserAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `command`='" . $PA['command'] . "' ORDER BY `Ruron` DESC");
    $arrUserAllCommand = $resUserAllCommand->fetch_all(MYSQLI_ASSOC);
    $countUserAllCommand = count($arrUserAllCommand);
    $countUserDeadCommand = count($mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `command`='" . $PA['command'] . "' AND `player_activ`='0'")->fetch_all(MYSQLI_ASSOC));
    //проверка поражения команды противника
    $resMobAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `command`!='" . $PA['command'] . "' ORDER BY `Ruron` DESC");
    $arrMobAllCommand = $resMobAllCommand->fetch_all(MYSQLI_ASSOC);
    $countMobAllCommand = count($arrMobAllCommand);
    $countMobDeadCommand = count($mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `command`!='" . $PA['command'] . "' AND `player_activ`='0'")->fetch_all(MYSQLI_ASSOC));

    //проверка что все живые мертвы а боты и мобы победили
    $vjvalluserall = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `Ptype`='0' AND `Mid`>'-1'")->num_rows;
    $vjvalluserdead = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $PA['battle_id'] . "' AND `Ptype`='0' AND `Mid`>'-1' AND `player_activ`='0'")->num_rows;


//ПОБЕЖДЕННЫЕ ГЕРОИ
    if ($countUserAllCommand == $countUserDeadCommand && $resAllCommand != $resAllCommandend &&
            $PA['type_battle'] != 3 && $PA['type_battle'] != 4 ||
            $vjvalluserall == $vjvalluserdead && $PA['type_battle'] >= 3 && $PA['type_battle'] <= 4) {
        //составляем список побежденных и победителей
        $looser = [];
        for ($il = 0; $il < $countUserAllCommand; $il++) {
            $looser = array_merge($looser, array([urlencode($arrUserAllCommand[$il]['Pname']), $arrUserAllCommand[$il]['Ruron'], round((int) $arrUserAllCommand[$il]['Pflife'] / 10), $arrUserAllCommand[$il]['Mid'], $arrUserAllCommand[$il]['Ptype']]));
        }
        $winner = [];
        for ($iw = 0; $iw < $countMobAllCommand; $iw++) {
            $winner = array_merge($winner, array([urlencode($arrMobAllCommand[$iw]['Pname']), $arrMobAllCommand[$iw]['Ruron'], round((int) $arrMobAllCommand[$iw]['Pflife'] / 10), $arrMobAllCommand[$iw]['Mid'], $arrMobAllCommand[$iw]['Ptype']]));
        }
        //заносим в таблицу результаты проигравшим
        for ($il = 0; $il < $countUserAllCommand; $il++) {
            if ($arrUserAllCommand[$il]['Ptype'] != 1 && $arrUserAllCommand[$il]['Mid'] > 0) {
                $mc->query("INSERT INTO `resultbattle` ("
                        . " `id`,"
                        . " `id_user`,"
                        . " `winner`,"
                        . " `looser`,"
                        . " `loose`,"
                        . " `type`"
                        . ") VALUES ("
                        . "NULL,"
                        . " '" . $arrUserAllCommand[$il]['Mid'] . "',"
                        . " '" . json_encode($winner) . "',"
                        . " '" . json_encode($looser) . "',"
                        . " '1',"
                        . "'" . $PA['type_battle'] . "'"
                        . ");");
            }
        }
        //победителям
        for ($iw = 0; $iw < $countMobAllCommand; $iw++) {
            if ($arrMobAllCommand[$iw]['Ptype'] != 1 && $arrMobAllCommand[$iw]['Mid'] > 0) {
                $mc->query("INSERT INTO `resultbattle` ("
                        . " `id`,"
                        . " `id_user`,"
                        . " `winner`,"
                        . " `looser`,"
                        . " `loose`,"
                        . " `type`"
                        . ") VALUES ("
                        . "NULL,"
                        . " '" . $arrMobAllCommand[$iw]['Mid'] . "',"
                        . " '" . json_encode($winner) . "',"
                        . " '" . json_encode($looser) . "',"
                        . " '0',"
                        . "'" . $PA['type_battle'] . "'"
                        . ");");
            }
        }
        $mc->query("UPDATE `battle` SET `end_battle`='1' WHERE `battle_id`='" . $PA['battle_id'] . "'");
        //запись hp в бд
        $mc->query("UPDATE `users` SET "
                . "`temp_health`='" . $PA['Plife'] . "'"
                . " WHERE `id`='" . $PlayerArr['id'] . "'");
        $mc->query("DELETE FROM `battle` WHERE `end_battle` = '1' && `battle_start_time` < '" . (time() - 60) . "'");
        //ПОБЕЖДЕННЫЕ ПРОТИВНИКИ
    } else if ($countMobAllCommand == $countMobDeadCommand && $resAllCommand != $resAllCommandend) {
        //составляем список побежденных и победителей
        $looser = [];
        for ($il = 0; $il < $countMobAllCommand; $il++) {
            $looser = array_merge($looser, array([urlencode($arrMobAllCommand[$il]['Pname']), $arrMobAllCommand[$il]['Ruron'], round((int) $arrMobAllCommand[$il]['Pflife'] / 10), $arrMobAllCommand[$il]['Mid'], $arrMobAllCommand[$il]['Ptype']]));
        }
        $winner = [];
        for ($iw = 0; $iw < $countUserAllCommand; $iw++) {
            $winner = array_merge($winner, array([urlencode($arrUserAllCommand[$iw]['Pname']), $arrUserAllCommand[$iw]['Ruron'], round((int) $arrUserAllCommand[$iw]['Pflife'] / 10), $arrUserAllCommand[$iw]['Mid'], $arrUserAllCommand[$iw]['Ptype']]));
        }
        //заносим в таблицу результаты проигравшим
        for ($il = 0; $il < $countUserAllCommand; $il++) {
            if ($arrUserAllCommand[$il]['Ptype'] != 1 && $arrUserAllCommand[$il]['Mid'] > 0) {
                $mc->query("INSERT INTO `resultbattle` ("
                        . " `id`,"
                        . " `id_user`,"
                        . " `winner`,"
                        . " `looser`,"
                        . " `loose`,"
                        . " `type`"
                        . ") VALUES ("
                        . "NULL,"
                        . " '" . $arrUserAllCommand[$il]['Mid'] . "',"
                        . " '" . json_encode($winner) . "',"
                        . " '" . json_encode($looser) . "',"
                        . " '0',"
                        . "'" . $PA['type_battle'] . "'"
                        . ");");
            }
        }
        //победителям
        for ($iw = 0; $iw < $countMobAllCommand; $iw++) {
            if ($arrMobAllCommand[$iw]['Ptype'] != 1 && $arrMobAllCommand[$iw]['Mid'] > 0) {
                $mc->query("INSERT INTO `resultbattle` ("
                        . " `id`,"
                        . " `id_user`,"
                        . " `winner`,"
                        . " `looser`,"
                        . " `loose`,"
                        . " `type`"
                        . ") VALUES ("
                        . "NULL,"
                        . " '" . $arrMobAllCommand[$iw]['Mid'] . "',"
                        . " '" . json_encode($winner) . "',"
                        . " '" . json_encode($looser) . "',"
                        . " '1',"
                        . "'" . $PA['type_battle'] . "'"
                        . ");");
            }
        }
        $mc->query("UPDATE `battle` SET `end_battle`='1' WHERE `battle_id`='" . $PA['battle_id'] . "'");
        //запись hp в бд
        $mc->query("UPDATE `users` SET "
                . "`temp_health`='" . $PA['Plife'] . "'"
                . " WHERE `id`='" . $PlayerArr['id'] . "'");
        $mc->query("DELETE FROM `battle` WHERE `end_battle` = '1' && `battle_start_time` < '" . (time() - 60) . "'");
    }
}

function playerSetEffect($i) {
    global $PweaponEffect;
    global $PAlwaysEffect;
    global $Phodeffect;
    global $Pmhodeffect;
    //если при ходе обоих
    if (is_array($PweaponEffect[$i][3])) {
        $temp = $PweaponEffect[$i][3];
        for ($i1 = 0; $i1 < count($temp); $i1++) {
            $temp[$i1][0][1] -= 1;
        }
        if (!isset($PweaponEffect[$i][4]) || 0 == $PweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($PAlwaysEffect)) {
                $PAlwaysEffect = array_merge($temp, $PAlwaysEffect);
            } else {
                $PAlwaysEffect = $temp;
            }
            //или при ходе героя
        } else if (1 == $PweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($Phodeffect)) {
                $Phodeffect = array_merge($temp, $Phodeffect);
            } else {
                $Phodeffect = $temp;
            }
            //или при ходе противника
        } else if (2 == $PweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($Pmhodeffect)) {
                $Pmhodeffect = array_merge($temp, $Pmhodeffect);
            } else {
                $Pmhodeffect = $temp;
            }
        }
    }
}

function playermSetEffect($i) {
    global $PweaponEffect;
    global $MPAlwaysEffect;
    global $MPhodeffect;
    global $MPmhodeffect;
    //если при ходе обоих
    if (is_array($PweaponEffect[$i][3])) {
        $temp = $PweaponEffect[$i][3];
        for ($i1 = 0; $i1 < count($temp); $i1++) {
            $temp[$i1][0][1] -= 1;
        }
        if (!isset($PweaponEffect[$i][4]) || 0 == $PweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($MPAlwaysEffect)) {
                $MPAlwaysEffect = array_merge($temp, $MPAlwaysEffect);
            } else {
                $MPAlwaysEffect = $temp;
            }
            //или при ходе героя
        } else if (1 == $PweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($MPhodeffect)) {
                $MPhodeffect = array_merge($temp, $MPhodeffect);
            } else {
                $MPhodeffect = $temp;
            }
            //или при ходе противника
        } else if (2 == $PweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($MPmhodeffect)) {
                $MPmhodeffect = array_merge($temp, $MPmhodeffect);
            } else {
                $MPmhodeffect = $temp;
            }
        }
    }
}

function mplayerSetEffect($i) {
    global $MPweaponEffect;
    global $MPAlwaysEffect;
    global $MPhodeffect;
    global $MPmhodeffect;
    //если при ходе обоих
    if (is_array($MPweaponEffect[$i][3])) {
        $temp = $MPweaponEffect[$i][3];
        for ($i1 = 0; $i1 < count($temp); $i1++) {
            $temp[$i1][0][1] -= 1;
        }
        if (!isset($MPweaponEffect[$i][4]) || 0 == $MPweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($MPAlwaysEffect)) {
                $MPAlwaysEffect = array_merge($temp, $MPAlwaysEffect);
            } else {
                $MPAlwaysEffect = $temp;
            }
            //или при ходе героя
        } else if (1 == $MPweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($MPhodeffect)) {
                $MPhodeffect = array_merge($temp, $MPhodeffect);
            } else {
                $MPhodeffect = $temp;
            }
            //или при ходе противника
        } else if (2 == $MPweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($MPmhodeffect)) {
                $MPmhodeffect = array_merge($temp, $MPmhodeffect);
            } else {
                $MPmhodeffect = $temp;
            }
        }
    }
}

function mplayermSetEffect($i) {
    global $MPweaponEffect;
    global $PAlwaysEffect;
    global $Phodeffect;
    global $Pmhodeffect;
    //если при ходе обоих
    if (is_array($MPweaponEffect[$i][3])) {
        $temp = $MPweaponEffect[$i][3];
        for ($i1 = 0; $i1 < count($temp); $i1++) {
            $temp[$i1][0][1] -= 1;
        }
        if (!isset($MPweaponEffect[$i][4]) || 0 == $MPweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($PAlwaysEffect)) {
                $PAlwaysEffect = array_merge($temp, $PAlwaysEffect);
            } else {
                $PAlwaysEffect = $temp;
            }
            //или при ходе героя
        } else if (1 == $MPweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($Phodeffect)) {
                $Phodeffect = array_merge($temp, $Phodeffect);
            } else {
                $Phodeffect = $temp;
            }
            //или при ходе противника
        } else if (2 == $MPweaponEffect[$i][4]) {
            //если массив автоэффектов игрока есть массив то прибавим к нему действие ОРУЖИЯ
            if (is_array($Pmhodeffect)) {
                $Pmhodeffect = array_merge($temp, $Pmhodeffect);
            } else {
                $Pmhodeffect = $temp;
            }
        }
    }
}

function indexaTArr($arr) {
    $arr2 = [];
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $arr2[] = indexaTArr($value);
        } else {
            $arr2[] = $value;
        }
    }
    return $arr2;
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json);
}
