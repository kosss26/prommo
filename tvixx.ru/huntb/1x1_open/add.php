<?php

require_once '../../system/func.php';

if (isset($_POST['type']) && isset($user) && $user['level'] > 1) {
    if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows == 0) {
        if ($user['side'] == 0 || $user['side'] == 1) {
            $user_rasa = 0;
        } else {
            $user_rasa = 1;
        }
        $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
        $mc->query("INSERT INTO `huntb_list`("
                . "`id`,"
                . " `user_id`,"
                . " `level`,"
                . " `rasa`,"
                . " `time_start`,"
                . " `type`"
                . ") VALUES ("
                . "'NULL',"
                . "'" . $user['id'] . "',"
                . "'" . $user['level'] . "',"
                . "'$user_rasa',"
                . "'" . time() . "',"
                . "'" . $_POST['type'] . "'"
                . ")");
        echo json_encode(array(
            "result" => 1,
            "error" => $mc->error
        ));
    } else {
        echo json_encode(array(
            "result" => 0,
            "error" => $mc->error
        ));
    }
} else {
    echo json_encode(array(
        "result" => 0,
        "error" => $mc->error
    ));
}