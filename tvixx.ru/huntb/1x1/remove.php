<?php

require_once '../../system/func.php';

if (isset($user)) {
    $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
    echo json_encode(array(
        "result" => 1,
        "error"=>$mc->error
    ));
} else {
    echo json_encode(array(
        "result" => 0,
        "error"=>$mc->error
    ));
}
