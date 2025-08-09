<?php

require_once '../../system/connect.php';
if (isset($user) && $user['access'] > 3) {
    if (isset($_GET['auk_id']) && isset($_GET['auk_lots_array'])) {
        $arr=$_GET['auk_lots_array'];
        $mc->query("DELETE FROM `auk_lots` WHERE `id_auk_list` = '".$_GET['auk_id']."'");
        $mc->query("UPDATE `auk_list` SET `counts` = '". count($arr['elements'])."' WHERE `id` = '".$_GET['auk_id']."'");
        $c=1;
        foreach ($arr['elements'] as $value) {
            $mc->query("INSERT INTO `auk_lots` ("
                    . "`id`,"
                    . " `id_auk_list`,"
                    . " `count`,"
                    . " `id_shop`,"
                    . " `min_platina`,"
                    . " `stop_platina`,"
                    . " `open_day`,"
                    . " `close_day`"
                    . ") VALUES ("
                    . "NULL,"
                    . " '".$_GET['auk_id']."',"
                    . " '$c',"
                    . " '".$value['id_shop']."',"
                    . " '".$value['min_platina']."',"
                    . " '".$value['stop_platina']."',"
                    . " '".$value['open_day']."',"
                    . " '".$value['close_day']."'"
                    . ")");
            $c++;
        }
        echo 'Сохранено';
    } else {
        echo 'Ошибка, недостаточно параметров';
    }
} else {
    echo 'Недостаточный уровень доступа';
}

