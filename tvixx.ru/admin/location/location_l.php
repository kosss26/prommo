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

        if ($megaArr['locid'] == "") {
            if(isset($megaArr['dhdClanzolo']))
            {
                $dhdClan = (((($megaArr['dhdClanzolo'] * 100) + $megaArr['dhdClanserebro']) * 100) + $megaArr['dhdClanmed']);
                $dhdUser = ((((($megaArr['dhdUserzolo'] * 100) + $megaArr['dhdUserserebro']) * 100) + $megaArr['dhdUsermed']) / $megaArr['dhdLevel']);
            }

             $mc->query("INSERT INTO `location`("
                    . "`Name`,"
                    . "`coef_buy`,"
                    . "`coef_sell`,"
                    . "`coef_repair`,"
                    . " `IdImage`,"
                    . " `access`,"
                    . " `accesslevel`,"
                    . " `snow`,"
                    . " `quests`,"
                    . " `IdLoc1`,"
                    . " `IdLoc2`,"
                    . " `IdLoc3`,"
                    . " `IdLoc4`,"
                    . " `IdLoc5`,"
                    . " `IdLoc6`,"
                    . " `IdLoc7`,"
                    . " `IdLoc8`,"
                    . " `IdLoc9`,"
                    . " `IdLoc10`,"
                    . " `dhdClan`,"
                    . " `dhdUser`,"
                    . " `thingid`,"
                    . " `id_loc_dostup_sk`"
                    . ") VALUES ("
                    . "'" . $megaArr['locname'] . "',"
                    . "'" . $megaArr['coef_buy'] . "',"
                    . "'" . $megaArr['coef_sell'] . "',"
                    . "'" . $megaArr['coef_repair'] . "',"
                    . "'" . $megaArr['locimgid'] . "',"
                    . "'" . $megaArr['locaccess'] . "',"
                    . "'" . $megaArr['loclvl'] . "',"
                    . "'" . $megaArr['snow'] . "',"
                    . "'" . $megaArr['quests'] . "',"
                    . "'" . $megaArr['IdLoc1'] . "',"
                    . "'" . $megaArr['IdLoc2'] . "',"
                    . "'" . $megaArr['IdLoc3'] . "',"
                    . "'" . $megaArr['IdLoc4'] . "',"
                    . "'" . $megaArr['IdLoc5'] . "',"
                    . "'" . $megaArr['IdLoc6'] . "',"
                    . "'" . $megaArr['IdLoc7'] . "',"
                    . "'" . $megaArr['IdLoc8'] . "',"
                    . "'" . $megaArr['IdLoc9'] . "',"
                    . "'" . $megaArr['IdLoc10'] . "',"
                    . "'$dhdClan',"
                    . "'$dhdUser',"
                    . "'" . $megaArr['thingid'] . "',"
                    . "'" . $megaArr['id_loc_dostup_sk'] . "'"
                    . ")");
            echo json_encode(array(
                "otvet" => 1,
                "new_id" => $mc->insert_id
            ));
            $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> создал локу </font><a onclick=\\'showContent(\\\"/admin/location/edit.php?func=infloc&locid=" . $mc->insert_id . "\\\")\\'><font color=\\'#0033cc\\'>" . $megaArr['locname'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','5', '" . $chatmsg . "','','','' )");
            exit(0);
        } else if ($megaArr['locid'] != "") {
            
            if(isset($megaArr['dhdClanzolo']))
            {
                $dhdClan = (((($megaArr['dhdClanzolo'] * 100) + $megaArr['dhdClanserebro']) * 100) + $megaArr['dhdClanmed']);
                $dhdUser = ((((($megaArr['dhdUserzolo'] * 100) + $megaArr['dhdUserserebro']) * 100) + $megaArr['dhdUsermed']) / $megaArr['dhdLevel']);
            }

            $mc->query("UPDATE `location` SET "
                    . "`Name` = '" . $megaArr['locname'] . "',"
                    . "`coef_buy` = '" . $megaArr['coef_buy'] . "',"
                    . "`coef_sell` = '" . $megaArr['coef_sell'] . "',"
                    . "`coef_repair` = '" . $megaArr['coef_repair'] . "',"
                    . "`IdImage` = '" . $megaArr['locimgid'] . "',"
                    . "`access` = '" . $megaArr['locaccess'] . "',"
                    . "`accesslevel` = '" . $megaArr['loclvl'] . "',"
                    . "`snow` = '" . $megaArr['snow'] . "',"
                    . "`quests` = '" . $megaArr['quests'] . "',"
                    . "`IdLoc1` = '" . $megaArr['IdLoc1'] . "',"
                    . "`IdLoc2` = '" . $megaArr['IdLoc2'] . "',"
                    . "`IdLoc3` = '" . $megaArr['IdLoc3'] . "',"
                    . "`IdLoc4` = '" . $megaArr['IdLoc4'] . "',"
                    . "`IdLoc5` = '" . $megaArr['IdLoc5'] . "',"
                    . "`IdLoc6` = '" . $megaArr['IdLoc6'] . "',"
                    . "`IdLoc7` = '" . $megaArr['IdLoc7'] . "',"
                    . "`IdLoc8` = '" . $megaArr['IdLoc8'] . "',"
                    . "`IdLoc9` = '" . $megaArr['IdLoc9'] . "',"
                    . "`IdLoc10` = '" . $megaArr['IdLoc10'] . "',"
                    . "`dhdClan` = '" . $dhdClan . "',"
                    . "`dhdUser` = '" . $dhdUser . "',"
                    . "`thingid` = '" . $megaArr['thingid'] . "',"
                    . "`id_loc_dostup_sk` = '" . $megaArr['id_loc_dostup_sk'] . "'"
                    . " WHERE `location`.`id` = '" . $megaArr['locid'] . "'");

            echo json_encode(array(
                "otvet" => 2,
                "new_id" => $megaArr['locid']
            ));
            $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> изменил локу </font><a onclick=\\'showContent(\\\"/admin/location/edit.php?func=infloc&locid=" . $megaArr['locid'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $megaArr['locname'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
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
