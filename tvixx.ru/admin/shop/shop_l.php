<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo+.php';
require_once '../../system/func.php';
require_once '../../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (isset($_POST['strdata'])) {
    $megaArr = json_decode(urldecode($_POST['strdata']), TRUE);

    if (isset($megaArr)) {
        $BattleFlag = $megaArr['BattleFlag'];
        $arr_id_punct_shop_replacer = [1, 1, 2, 2, 2, 2, 4, 4, 3, 10, 11, 12, 5];
        $id_punct_shop = $arr_id_punct_shop_replacer[$megaArr['id_punct'] - 1];
        //добавим в бд
        $stilname = addslashes($megaArr['name']);
        $megaArr['opisanie'] = addslashes($megaArr['opisanie']);
        $Jsonencoder = json_encode(arrayValuesToInt($megaArr['Effect']));
        $NameEffect = isset($megaArr['NameEffect']) ? addslashes(implode("|", $megaArr['NameEffect'])) : "";
        $money = moneyplus((int) $megaArr['zolo'], (int) $megaArr['serebro'], (int) $megaArr['med']);

        if ($megaArr['id'] == "") {
            $Timearr_s = explode(':', $megaArr['time_s']);
            $Time_s = (((((($Timearr_s[0] * 24) + $Timearr_s[1]) * 60) + $Timearr_s[2]) * 60) + $Timearr_s[3]);
            $mc->query("INSERT INTO `shop`("
                    . "`platinum`,"
                    . " `id_punct`,"
                    . " `id_punct_shop`,"
                    . " `name`,"
                    . " `stil`,"
                    . " `opisanie`,"
                    . " `money`,"
                    . " `level`,"
                    . " `max_hc`,"
                    . " `iznos`,"
                    . " `time_s`,"
                    . " `koll`,"
                    . " `id_image`,"
                    . " `block`,"
                    . " `health`,"
                    . " `toch`,"
                    . " `strength`,"
                    . " `lov`,"
                    . " `kd`,"
                    . " `effects`,"
                    . "`nameeffects`,"
                    . "`bron`,"
                    . " `chatSend`,"
                    . " `elexvar`,"
                    . " `id_quests`,"
                    . " `drop_min_level`,"
                    . " `drop_max_level`,"
                    . " `BattleFlag`"
                    . ") VALUES ("
                    . "'" . $megaArr['platinum'] . "',"
                    . "'" . $megaArr['id_punct'] . "',"
                    . "'" . $id_punct_shop . "',"
                    . "'" . $stilname . "',"
                    . "'" . $megaArr['stil'] . "',"
                    . "'" . $megaArr['opisanie'] . "',"
                    . "'" . $money . "',"
                    . "'" . $megaArr['lvl'] . "',"
                    . "'" . $megaArr['max_hc'] . "',"
                    . "'" . $megaArr['iznos'] . "',"
                    . "'" . $Time_s . "',"
                    . "'" . $megaArr['koll'] . "',"
                    . "'" . $megaArr['img'] . "',"
                    . "'" . $megaArr['block'] . "',"
                    . "'" . $megaArr['health'] . "',"
                    . "'" . $megaArr['toch'] . "',"
                    . "'" . $megaArr['strength'] . "',"
                    . "'" . $megaArr['lov'] . "',"
                    . "'" . $megaArr['kd'] . "',"
                    . "'" . $Jsonencoder . "',"
                    . "'" . $NameEffect . "',"
                    . "'" . $megaArr['bron'] . "',"
                    . "'" . $megaArr['chatSend'] . "',"
                    . "'" . $megaArr['elexvar'] . "',"
                    . "'" . $megaArr['id_quests'] . "',"
                    . "'" . $megaArr['drop_min_level'] . "',"
                    . "'" . $megaArr['drop_max_level'] . "',"
                    . "'" . $BattleFlag . "'"
                    . ")");
            echo json_encode(array(
                "otvet" => 1,
                "new_id" => $mc->insert_id
            ));
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> создал вещь </font><a onclick=\"showContent('/admin/shop.php?shop=edit&id=" . $mc->insert_id . "')\"><font color='#0033cc'>" . $megaArr['name'] . "</font></a><font color='#0033cc'> !</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','5', '" . $chatmsg . "','','','' )");
            exit(0);
        } else if ($megaArr['id'] != "") {
            $Timearr_s = explode(':', $megaArr['time_s']);
            $Time_s = (((((($Timearr_s[0] * 24) + $Timearr_s[1]) * 60) + $Timearr_s[2]) * 60) + $Timearr_s[3]);
            $mc->query("UPDATE `shop` SET "
                    . "`platinum`='" . $megaArr['platinum'] . "',"
                    . "`id_punct`='" . $megaArr['id_punct'] . "',"
                    . "`id_punct_shop`='" . $id_punct_shop . "',"
                    . "`name`='" . $stilname . "',"
                    . "`stil`='" . $megaArr['stil'] . "',"
                    . "`opisanie`= '" . $megaArr['opisanie'] . "',"
                    . "`money`='" . $money . "',"
                    . "`level`='" . $megaArr['lvl'] . "',"
                    . "`max_hc`='" . $megaArr['max_hc'] . "',"
                    . "`iznos`='" . $megaArr['iznos'] . "',"
                    . "`time_s`='" . $Time_s . "',"
                    . "`koll`='" . $megaArr['koll'] . "',"
                    . "`id_image`='" . $megaArr['img'] . "',"
                    . "`block`='" . $megaArr['block'] . "',"
                    . "`health`='" . $megaArr['health'] . "',"
                    . "`toch`='" . $megaArr['toch'] . "',"
                    . "`strength`='" . $megaArr['strength'] . "',"
                    . "`lov`='" . $megaArr['lov'] . "',"
                    . "`kd`='" . $megaArr['kd'] . "',"
                    . "`effects`='" . $Jsonencoder . "',"
                    . "`nameeffects`='" . $NameEffect . "',"
                    . "`bron`='" . $megaArr['bron'] . "',"
                    . "`chatSend`='" . $megaArr['chatSend'] . "',"
                    . "`elexvar`='" . $megaArr['elexvar'] . "',"
                    . "`id_quests`='" . $megaArr['id_quests'] . "',"
                    . "`drop_min_level`='" . $megaArr['drop_min_level'] . "',"
                    . "`drop_max_level`='" . $megaArr['drop_max_level'] . "',"
                    . "`BattleFlag`='" . $BattleFlag . "'"
                    . " WHERE id='" . $megaArr['id'] . "'");
            //дата истечения в unix
            if ($Time_s > 0) {
                $time_the_lapse = $Time_s + time();
            } else {
                $time_the_lapse = 0;
            }
            //обновляем все вещи игрокам
            $mc->query("UPDATE `userbag` SET "
                    . " `id_punct` = '" . $megaArr['id_punct'] . "',"
                    . " `iznos` = '" . $megaArr['iznos'] . "',"
                    . " `koll` = '" . $megaArr['koll'] . "',"
                    . " `max_hc` = '" . $megaArr['max_hc'] . "',"
                    . " `time_end` = '$time_the_lapse',"
                    . " `id_quests` = '" . $megaArr['id_quests'] . "',"
                    . " `stil` = '" . $megaArr['stil'] . "',"
                    . " `BattleFlag` = '$BattleFlag'"
                    . " WHERE `id_shop` = '" . $megaArr['id'] . "'");
            echo json_encode(array(
                "otvet" => 2,
                "new_id" => $megaArr['id']
            ));
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> изменил вещь </font><a onclick=\"showContent('/admin/shop.php?shop=edit&id=" . $megaArr['id'] . "')\"><font color='#0033cc'>" . $megaArr['name'] . "</font></a><font color='#0033cc'> !</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','5', '" . $chatmsg . "','','','' )");
            exit(0);
        }
    } else {
        echo json_encode(array(
            "otvet" => 4,
            "new_id" => ""
        ));
        exit(0);
    }
} else {
    echo json_encode(array(
        "otvet" => 0,
        "new_id" => ""
    ));
}

function arrayValuesToInt($arr) {
    $arr2 = [];
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $arr2[] = arrayValuesToInt($value);
        } else {
            $arr2[] = intval($value);
        }
    }
    return $arr2;
}
