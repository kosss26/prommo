<?php

if (isset($user) && isset($mc)) {
    if ($slavaarr = $mc->query("SELECT * FROM `slava` WHERE `slava` <= '" . $user['slava'] . "' && `lvl` <= '" . $user['level'] . "' ORDER BY `slava` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC)) {
        if ($user['zvanie'] != $slavaarr['id']) {
            $user['zvanie'] = $slavaarr['id'];
            message(
                    urlencode("Новое достижение !<br> Звание <b>" . $slavaarr['name'] . "</b>")
            );
            $mc->query("UPDATE `users` SET `zvanie` = '" . $slavaarr['id'] . "' WHERE `id` = '" . $user['id'] . "'");
        }
        $voin_propusk = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '1419'")->num_rows;
        $voin_arr = $mc->query("SELECT * FROM `slava` WHERE `name` = 'Воин' ")->fetch_array(MYSQLI_ASSOC);
        $vitiaz_propusk = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '1420'")->num_rows;
        $vitiaz_arr = $mc->query("SELECT * FROM `slava` WHERE `name` = 'Витязь' ")->fetch_array(MYSQLI_ASSOC);

        //пропуск в лавку воина
        if ($voin_propusk == 0 && $user['zvanie'] >= $voin_arr['id']) {
            message("Вы получили доступ в лавку Воина !");
            addItemToBag(1419);
        } else if ($voin_propusk > 0 && $user['zvanie'] < $voin_arr['id']) {
            message("Вы утратили доступ в лавку Воина !");
            $mc->query("DELETE FROM `userbag` WHERE `id_shop` = '1419' &&  `id_user` = '" . $user['id'] . "'");
        }
        //пропуск в лавку витязя
        if ($vitiaz_propusk == 0 && $user['zvanie'] >= $vitiaz_arr['id']) {
            message("Вы получили доступ в лавку Витязя !");
            addItemToBag(1420);
        } else if ($vitiaz_propusk > 0 && $user['zvanie'] < $vitiaz_arr['id']) {
            message("Вы утратили доступ в лавку Витязя !");
            $mc->query("DELETE FROM `userbag` WHERE `id_shop` = '1420' &&  `id_user` = '" . $user['id'] . "'");
        }
    }
}

//***выдача вещей герою 
function addItemToBag($itemId) {
    global $mc;
    global $user;
    //смотрим на новую вещь
    $infoShopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='$itemId'");
    if ($infoShopRes->num_rows > 0) {
        $infoshop1 = $infoShopRes->fetch_array(MYSQLI_ASSOC);
        //дата истечения в unix
        if ($infoshop1['time_s'] > 0) {
            $time_the_lapse = $infoshop1['time_s'] + time();
        } else {
            $time_the_lapse = 0;
        }

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
        if ($infoshop1['chatSend']) {
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> получил </font><font color='#0033cc'>" . $infoshop1['name'] . "</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','0', '" . $chatmsg . "','','','' )");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','1', '" . $chatmsg . "','','','' )");
        }
    }
}
