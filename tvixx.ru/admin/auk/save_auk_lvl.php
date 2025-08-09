<?php

require_once '../../system/connect.php';
if (isset($user) && $user['access'] > 3) {
    if (isset($_GET['auk_lvl_min']) && isset($_GET['auk_lvl_max']) && isset($_GET['auk_id'])) {
        $mc->query("UPDATE `auk_list` SET `lvl_min` = '" . $_GET['auk_lvl_min'] . "' , `lvl_max` = '" . $_GET['auk_lvl_max'] . "' WHERE `id` = '" . $_GET['auk_id'] . "'");
        echo 'Сохранено';
    } else {
        echo 'Ошибка, недостаточно параметров';
    }
} else {
    echo 'Недостаточный уровень доступа';
}

