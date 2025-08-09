<?php

require_once '../../system/func.php';
require_once '../../system/dbc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo+.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (isset($_POST['strdata'])) {
    $megaArr = json_decode(urldecode($_POST['strdata']), TRUE);

    if (isset($megaArr)) {
        //добавим в бд
        $Jsonencoder = json_encode(arrayValuesToInt($megaArr['Effect']));
        $NameEffect = isset($megaArr['NameEffect']) ? addslashes(implode("|", $megaArr['NameEffect'])) : "";
        $Timearr_s = explode(':', $megaArr['intervalTime']);
        $Time_s = (((((($Timearr_s[0] * 24) + $Timearr_s[1]) * 60) + $Timearr_s[2]) * 60) + $Timearr_s[3]);
        $moneymin = moneyplus($megaArr['minzolo'], $megaArr['minserebro'], $megaArr['minmed']);
        $moneymax = moneyplus($megaArr['maxzolo'], $megaArr['maxserebro'], $megaArr['maxmed'], $megaArr['maxplatina']);
        
        if ($megaArr['id'] == "") {
            $mc->query("INSERT INTO `hunt`("
                    . " `name`,"
                    . " `level`,"
                    . " `max_hp`,"
                    . " `hp`,"
                    . " `toch`,"
                    . " `lov`,"
                    . " `kd`,"
                    . " `block`,"
                    . " `bron`,"
                    . " `damage`,"
                    . " `iconid`,"
                    . " `maxplatina`,"
                    . " `minplatina`,"
                    . " `maxmoney`,"
                    . " `minmoney`,"
                    . " `exp`,"
                    . " `effects`,"
                    . "`nameeffects`,"
                    . "`ids_shopG_num`,"
                    . "`ids_shopG_rand`,"
                    . "`ids_shopG`,"
                    . "`ids_shopP_num`,"
                    . "`ids_shopP_rand`,"
                    . "`ids_shopP`,"
                    . "`ids_shopT_num`,"
                    . "`ids_shopT_rand`,"
                    . "`ids_shopT`,"
                    . "`intervalTime`,"
                    . "`stil`,"
                    . "`quests`"
                    . ") VALUES ("
                    . "'" . $megaArr['name'] . "',"
                    . "'" . $megaArr['level'] . "',"
                    . "'" . $megaArr['max_hp'] . "',"
                    . "'" . $megaArr['hp'] . "',"
                    . "'" . $megaArr['toch'] . "',"
                    . "'" . $megaArr['lov'] . "',"
                    . "'" . $megaArr['kd'] . "',"
                    . "'" . $megaArr['block'] . "',"
                    . "'" . $megaArr['bron'] . "',"
                    . "'" . $megaArr['damage'] . "',"
                    . "'" . $megaArr['iconid'] . "',"
                    . "'" . $megaArr['maxplatina'] . "',"
                    . "'" . $megaArr['minplatina'] . "',"
                    . "'" . $moneymax . "',"
                    . "'" . $moneymin . "',"
                    . "'" . $megaArr['exp'] . "',"
                    . "'" . $Jsonencoder . "',"
                    . "'" . $NameEffect . "',"
                    . "'" . $megaArr['ids_shopG_num'] . "',"
                    . "'" . $megaArr['ids_shopG_rand'] . "',"
                    . "'" . $megaArr['ids_shopG'] . "',"
                    . "'" . $megaArr['ids_shopP_num'] . "',"
                    . "'" . $megaArr['ids_shopP_rand'] . "',"
                    . "'" . $megaArr['ids_shopP'] . "',"
                    . "'" . $megaArr['ids_shopT_num'] . "',"
                    . "'" . $megaArr['ids_shopT_rand'] . "',"
                    . "'" . $megaArr['ids_shopT'] . "',"
                    . "'$Time_s',"
                    . "'" . $megaArr['stil'] . "',"
                    . "'" . $megaArr['quests'] . "'"
                    . ")");
            echo json_encode(array(
                "otvet" => 1,
                "new_id" => $mc->insert_id
            ));
            $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> создал моба </font><a onclick=\\'showContent(\\\"/admin/hunt.php?mob=edit&id=" . $mc->insert_id . "\\\")\\'><font color=\\'#0033cc\\'>" . $megaArr['name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
            $mc->query("INSERT INTO `chat`("
                    . "`id`,"
                    . "`name`,"
                    . "`id_user`,"
                    . "`chat_room`,"
                    . "`msg`,"
                    . "`msg2`,"
                    . "`time`,"
                    . " `unix_time"
                    . "`) VALUES ("
                    . "NULL,"
                    . "'АДМИНИСТРИРОВАНИЕ',"
                    . "'',"
                    . "'5',"
                    . " '" . $chatmsg . "',"
                    . "'',"
                    . "'',"
                    . "''"
                    . " )");
            exit(0);
        } else if ($megaArr['id'] != "") {
            $mc->query("UPDATE `hunt` SET "
                    . "`name`='" . $megaArr['name'] . "',"
                    . "`level`='" . $megaArr['level'] . "',"
                    . "`max_hp`='" . $megaArr['max_hp'] . "',"
                    . "`hp`='" . $megaArr['hp'] . "',"
                    . "`toch`='" . $megaArr['toch'] . "',"
                    . "`lov`='" . $megaArr['lov'] . "',"
                    . "`kd`='" . $megaArr['kd'] . "',"
                    . "`block`='" . $megaArr['block'] . "',"
                    . "`bron`='" . $megaArr['bron'] . "',"
                    . "`damage`='" . $megaArr['damage'] . "',"
                    . "`iconid`='" . $megaArr['iconid'] . "',"
                    . "`maxplatina`='" . $megaArr['maxplatina'] . "',"
                    . "`minplatina`='" . $megaArr['minplatina'] . "',"
                    . "`maxmoney`='" . $moneymax . "',"
                    . "`minmoney`='" . $moneymin . "',"
                    . "`exp`='" . $megaArr['exp'] . "',"
                    . "`effects`='" . $Jsonencoder . "',"
                    . "`nameeffects`='" . $NameEffect . "',"
                    . "`ids_shopG_num`='" . $megaArr['ids_shopG_num'] . "',"
                    . "`ids_shopG_rand`='" . $megaArr['ids_shopG_rand'] . "',"
                    . "`ids_shopG`='" . $megaArr['ids_shopG'] . "',"
                    . "`ids_shopP_num`='" . $megaArr['ids_shopP_num'] . "',"
                    . "`ids_shopP_rand`='" . $megaArr['ids_shopP_rand'] . "',"
                    . "`ids_shopP`='" . $megaArr['ids_shopP'] . "',"
                    . "`ids_shopT_num`='" . $megaArr['ids_shopT_num'] . "',"
                    . "`ids_shopT_rand`='" . $megaArr['ids_shopT_rand'] . "',"
                    . "`ids_shopT`='" . $megaArr['ids_shopT'] . "',"
                    . "`intervalTime`='$Time_s',"
                    . "`stil`='" . $megaArr['stil'] . "',"
                    . "`quests`='" . $megaArr['quests'] . "'"
                    . " WHERE "
                    . "`id`='" . $megaArr['id'] . "'"
                    . "");
            $mc->query("UPDATE `userHuntNotActiveMob` SET `time_end` = '" . ($Time_s + time()) . "' WHERE `id_mob` = '".$megaArr['id']."'");
            echo json_encode(array(
                "otvet" => 2,
                "new_id" => $megaArr['id']
            ));
            $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> изменил моба </font><a onclick=\\'showContent(\\\"/admin/hunt.php?mob=edit&id=" . $megaArr['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $megaArr['name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
            $mc->query("INSERT INTO `chat`("
                    . "`id`,"
                    . "`name`,"
                    . "`id_user`,"
                    . "`chat_room`,"
                    . "`msg`,"
                    . "`msg2`,"
                    . "`time`,"
                    . " `unix_time`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'АДМИНИСТРИРОВАНИЕ',"
                    . "'',"
                    . "'5',"
                    . " '" . $chatmsg . "',"
                    . "'',"
                    . "'',"
                    . "''"
                    . " )");
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
