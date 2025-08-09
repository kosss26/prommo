<?php

require_once 'bd.php';

$time = strtotime("+22 hour", time());

//получаем все аукционы
if ($auk = $mc->query("SELECT * FROM `auk`")->fetch_all(MYSQLI_ASSOC)) {
    //перебираем аукционы
    for ($i = 0; $i < count($auk); $i++) {
        //если время вышло и торг закрытый
        if ($auk[$i]['time'] <= time() && $auk[$i]['torg'] == 1) {
            //если есть участники
            if ($auk_user = $mc->query("SELECT * FROM `auk_user` WHERE `id_lot`='" . $auk[$i]['id'] . "' ORDER BY `plata` DESC ")->fetch_all(MYSQLI_ASSOC)) {
                //переберем участников
                for ($iu = 0; $iu < count($auk_user); $iu++) {
                    //если 0 пользователь то выдадим ему награду 
                    if ($iu == 0) {
                        //получаем параметры вещи
                        $infoshop1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $auk[$i]['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
                        //дата истечения в unix
                        if ($infoshop1['time_s'] > 0) {
                            $time_the_lapse = $infoshop1['time_s'] + time();
                        } else {
                            $time_the_lapse = 0;
                        }
                        //добавляем в снаряжение
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
                                . " `BattleFlag`"
                                . ") VALUES ("
                                . "'" . $auk_user[$iu]['id_user'] . "',"
                                . "'" . $infoshop1['id'] . "',"
                                . "'" . $infoshop1['id_punct'] . "',"
                                . "'0',"
                                . "'" . $infoshop1['iznos'] . "',"
                                . "'$time_the_lapse',"
                                . "'" . $infoshop1['id_quests'] . "',"
                                . "'" . $infoshop1['koll'] . "',"
                                . "'" . $infoshop1['max_hc'] . "',"
                                . "'" . $infoshop1['BattleFlag'] . "'"
                                . ")");
                        $mc->query("INSERT INTO `msg`("
                                . "`id`,"
                                . " `id_user`,"
                                . " `message`,"
                                . " `date`"
                                . ")VALUES("
                                . "NULL,"
                                . "'" . $auk_user[$iu]['id_user'] . "',"
                                . "'" . urldecode('Поздравляем вы выиграли в аукционе !!! Вам досталось ' . $infoshop1['name'] . ' .') . "',"
                                . "'" . time() . "'"
                                . ")");
                        //или вернем ставки остальным
                        
                    } else {
                        //возвращаем платину
                        $mc->query("UPDATE `users` SET `platinum` = `platinum`+'" . $auk_user[$iu]['plata'] . "' WHERE `id` = '" . $auk_user[$iu]['id_user'] . "'");
                        $mc->query("INSERT INTO `msg`("
                                . "`id`,"
                                . " `id_user`,"
                                . " `message`,"
                                . " `date`"
                                . ")VALUES("
                                . "NULL,"
                                . "'" . $auk_user[$iu]['id_user'] . "',"
                                . "'" . urldecode('Ваша ставка не выйграла и была возвращена') . "',"
                                . "'" . time() . "'"
                                . ")");
                    }
                }
                $newAuk = $mc->query("SELECT * FROM `auk_shop`")->fetch_all(MYSQLI_ASSOC);
                  for($j = 0; $j < count($newAuk); $j++){
                  	$nshop = explode(",",$newAuk[$j]['id_shop']);
                  	  if($newAuk[$j]['num'] < count($nshop) -1){
                        	$mc->query("UPDATE `auk_shop` SET `num` = `num` + '1' WHERE `id` = '".$newAuk[$j]['id']."' ");
                        }else if($newAuk[$j]['num'] >= count($nshop)-1){
                        	$mc->query("UPDATE `auk_shop` SET `num` = '0' WHERE `id` = '".$newAuk[$j]['id']."' ");
                        }
                   }
                //удалим аукцион и участников его
                $mc->query("DELETE FROM `auk` WHERE `id` = '" . $auk[$i]['id'] . "'");
                $mc->query("DELETE FROM `auk_user` WHERE `id_lot`='" . $auk[$i]['id'] . "'");
            }
            //если торги открытые и время вышло
        } elseif ($auk[$i]['time'] <= time() && $auk[$i]['torg'] == 0) {
            if ($auk_user = $mc->query("SELECT * FROM `auk_user` WHERE `id_lot`='" . $auk[$i]['id'] . "' ORDER BY `plata` DESC ")->fetch_array(MYSQLI_ASSOC)) {
                //установим текущий аукцион закрытые торги
                $mc->query("UPDATE `auk` SET `time` = '$time',`torg` = '1',`id_lider`='" . $auk_user['id_user'] . "',`bet_lider`='" . $auk_user['plata'] . "' WHERE `id` = '" . $auk[$i]['id'] . "'");
            } else {
                $mc->query("UPDATE `auk` SET `time` = '$time',`torg` = '1',`id_lider`='0',`bet_lider`='" . ($auk[$i]['min_plata']+1) . "' WHERE `id` = '" . $auk[$i]['id'] . "'");
            }
        }
    }
}