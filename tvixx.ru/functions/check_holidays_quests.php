<?php

if (isset($user['id'])) {
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
    //`id`, `month`, `days`, `quests_id`, `povtor`, `ewery_times`
    $allHoliRes = $mc->query("SELECT * FROM `holidays`");
    if ($allHoliRes->num_rows > 0) {
        $allHoli = $allHoliRes->fetch_all(MYSQLI_ASSOC);

        for ($i = 0; $i < count($allHoli); $i++) {
            //проверяем дату
            if ($allHoli[$i]['month'] > 0) {
                if ($allHoli[$i]['days'] > 0) {
                    //сверяем дату
                    if ($allHoli[$i]['month'] == date('m') && $allHoli[$i]['days'] == date('d')) {
                        //выбираем квесты которые не взяты , не пройдены, доступны по уровню , по id
                        $questVziatRes = $mc->query("SELECT * FROM `quests` WHERE "
                                . "`id`='" . $allHoli[$i]['quests_id'] . "'"
                                . "&&`level_min`<='" . $user['level'] . "'"
                                . "&&`level_max`>='" . $user['level'] . "'"
                                . "&&(`rasa`='" . $accessloc . "' || `rasa`='0')"
                                . " && `id` NOT IN "
                                . "( SELECT `id_quests` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' )"
                                . " && `id` NOT IN "
                                . "( SELECT `id_quests` FROM `quests_notActive` WHERE `id_user` = '" . $user['id'] . "' )");
                        if ($questVziatRes->num_rows > 0) {
                            //проверим доступность квеста
                            $questVziat = $questVziatRes->fetch_array(MYSQLI_ASSOC);
                            if (chekDostypeQuest($questVziat) == FALSE) {
                                continue;
                            }
                        } else {
                            continue;
                        }
                        $arrCountQuestsNext = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $allHoli[$i]['quests_id'] . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
                        $time_ce = -1;
                        if ($arrCountQuestsNext['time_ce'] > 0) {
                            $time_ce = $arrCountQuestsNext['time_ce'] + time();
                        }
                        //вставляем в бд запись части квеста выбранному пользователю
                        $mc->query("INSERT INTO `quests_users` ("
                                . "`id`, `id_user`, `id_quests`, `count`, `time_view`, `time_ce`,`herowin_c`,`variant`"
                                . ") VALUES ("
                                . "NULL, '" . $user['id'] . "', '" . $allHoli[$i]['quests_id'] . "', '1', '" . time() . "', '$time_ce', '0' , '0'"
                                . ")");
                    }
                }
            } else if ($allHoli[$i]['povtor'] == date('D')) {
                //выбираем квесты которые не взяты , не пройдены, доступны по уровню , по id
                $questVziatRes = $mc->query("SELECT * FROM `quests` WHERE "
                        . "`id`='" . $allHoli[$i]['quests_id'] . "'"
                        . "&&`level_min`<='" . $user['level'] . "'"
                        . "&&`level_max`>='" . $user['level'] . "'"
                        . "&&(`rasa`='" . $accessloc . "' || `rasa`='0')"
                        . " && `id` NOT IN "
                        . "( SELECT `id_quests` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' )"
                        . " && `id` NOT IN "
                        . "( SELECT `id_quests` FROM `quests_notActive` WHERE `id_user` = '" . $user['id'] . "' )");
                if ($questVziatRes->num_rows > 0) {
                    //проверим доступность квеста
                    $questVziat = $questVziatRes->fetch_array(MYSQLI_ASSOC);
                    if (chekDostypeQuest($questVziat) == FALSE) {
                        continue;
                    }
                } else {
                    continue;
                }
                $arrCountQuestsNext = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $allHoli[$i]['quests_id'] . "' && `count`='1'")->fetch_array(MYSQLI_ASSOC);
                $time_ce = -1;
                if ($arrCountQuestsNext['time_ce'] > 0) {
                    $time_ce = $arrCountQuestsNext['time_ce'] + time();
                }
                //вставляем в бд запись части квеста выбранному пользователю
                $mc->query("INSERT INTO `quests_users` ("
                        . "`id`, `id_user`, `id_quests`, `count`, `time_view`, `time_ce`,`herowin_c`,`variant`"
                        . ") VALUES ("
                        . "NULL, '" . $user['id'] . "', '" . $allHoli[$i]['quests_id'] . "', '1', '" . time() . "', '$time_ce', '0' , '0'"
                        . ")");
            }
        }
    }
}

