<?php

require '../system/dbc.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (isset($_POST["Login"]) && isset($_POST["Password"]) && isset($_POST["uid"]) && isset($_POST["type"])) {
    $LOGIN = urldecode($_POST["Login"]);
    $PASS = $_POST["Password"];
    $user1 = $mc->query("SELECT * FROM `users` WHERE `login` = '$LOGIN' AND `password` = '$PASS'")->fetch_array(MYSQLI_ASSOC);
    $user2 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $_POST["uid"] . "'")->fetch_array(MYSQLI_ASSOC);

    if ($rep_list = $mc->query("SELECT * FROM `rep_list` WHERE `user1_id` = '" . $user1["id"] . "' && `user2_id` = '" . $user2["id"] . "' ")->fetch_array(MYSQLI_ASSOC)) {
        if ($rep_list['num'] == 0) {
            if ($_POST["type"] == 0) {
                $mc->query("UPDATE `rep_list` SET `num`='-1' WHERE `id`='" . $rep_list['id'] . "'");
                $mc->query("UPDATE `users` SET `rep_m`=`rep_m`+'1' WHERE `id`='" . $user2['id'] . "'");
            }
            if ($_POST["type"] == 1) {
                $mc->query("UPDATE `rep_list` SET `num`='1' WHERE `id`='" . $rep_list['id'] . "'");
                $mc->query("UPDATE `users` SET `rep_p`=`rep_p`+'1' WHERE `id`='" . $user2['id'] . "'");
            }
        }
        if ($rep_list['num'] < 0) {
            if ($_POST["type"] == 1) {
                $mc->query("UPDATE `rep_list` SET `num`='0' WHERE `id`='" . $rep_list['id'] . "'");
                $mc->query("UPDATE `users` SET `rep_m`=`rep_m`-'1' WHERE `id`='" . $user2['id'] . "'");
            }
        }
        if ($rep_list['num'] > 0) {
            if ($_POST["type"] == 0) {
                $mc->query("UPDATE `rep_list` SET `num`='0' WHERE `id`='" . $rep_list['id'] . "'");
                $mc->query("UPDATE `users` SET `rep_p`=`rep_p`-'1' WHERE `id`='" . $user2['id'] . "'");
            }
        }
    } else {
        $typetemp=0;
        if($_POST["type"]==0){
            $typetemp=-1;
        }elseif ($_POST["type"]==1) {
            $typetemp=1;
        }
        $mc->query("INSERT INTO `rep_list`("
                . "`id`,"
                . " `user1_id`,"
                . " `user2_id`,"
                . " `num`"
                . ")VALUES("
                . "NULL,"
                . "'" . $user1['id'] . "',"
                . "'" . $user2['id'] . "',"
                . "'" . $typetemp . "'"
                . ")");
        if ($_POST["type"] == 1) {
            $mc->query("UPDATE `users` SET `rep_p`=`rep_p`+'1' WHERE `id`='" . $user2['id'] . "'");
        }
        if ($_POST["type"] == 0) {
            $mc->query("UPDATE `users` SET `rep_m`=`rep_m`+'1' WHERE `id`='" . $user2['id'] . "'");
        }
    }
    if ($rep_list_after = $mc->query("SELECT * FROM `rep_list` WHERE `user1_id` = '" . $user1["id"] . "' && `user2_id` = '" . $user2["id"] . "' ")->fetch_array(MYSQLI_ASSOC)) {
        echo json_encode(array("type" => $rep_list_after['num']));
    }
}