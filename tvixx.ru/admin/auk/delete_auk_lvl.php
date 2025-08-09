<?php

require_once '../../system/connect.php';
if (isset($user) && $user['access'] > 3) {
    if (isset($_GET['auk_id'])&&$_GET['auk_id']>0) {
        $mc->query("DELETE FROM `auk_list` WHERE `auk_list`.`id` = '".$_GET['auk_id']."'");
        echo 'Удалено';
    } else {
        echo 'Ошибка, недостаточно параметров';
    }
} else {
    echo 'Недостаточный уровень доступа';
}

