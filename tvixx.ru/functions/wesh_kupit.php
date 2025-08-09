<?php

function shop_buy($id_shop, $odet) {
    global $mc;
    global $user;
    
    if (!isset($user) || !$id_shop) {
        return false;
    }

    // Получаем количество вещей в сумке
    $query = "SELECT COUNT(*) as count FROM `userbag` 
              WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
              AND `id_punct` > '0' AND `id_punct` < '10'";
    $result = $mc->query($query);
    if (!$result) {
        error_log("MySQL Error: " . $mc->error);
        return false;
    }
    $eqcount = $result->fetch_assoc()['count'];

    // Проверяем место в сумке
    if ($eqcount >= $user['max_bag_count']) {
        return false;
    }

    // Получаем информацию о вещи
    $query = "SELECT * FROM `shop` WHERE `id` = '" . $mc->real_escape_string($id_shop) . "' LIMIT 1";
    $result = $mc->query($query);
    if (!$result) {
        error_log("MySQL Error: " . $mc->error);
        return false;
    }
    $infoshop1 = $result->fetch_array(MYSQLI_ASSOC);
    if (!$infoshop1) {
        return false;
    }

    // Если нужно одеть сразу
    if ($odet == 'y') {
        // Проверяем есть ли уже одетая вещь такого типа
        if ($infoshop1['id_punct'] != 11) { // Не бонусы
            $query = "SELECT * FROM `userbag` 
                     WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
                     AND `dress` = '1' 
                     AND `id_punct` = '" . $mc->real_escape_string($infoshop1['id_punct']) . "'";
            $result = $mc->query($query);
            if (!$result) {
                error_log("MySQL Error: " . $mc->error);
                return false;
            }
            $dresssnyat = $result->fetch_array(MYSQLI_ASSOC);

            // Снимаем старую вещь если нужно
            if ($dresssnyat) {
                if ($infoshop1['id_punct'] == 9 && $dresssnyat['COUNT(1)'] > 6) {
                    shop_snyat($dresssnyat['id']);
                } elseif ($infoshop1['id_punct'] == 8 && $dresssnyat['COUNT(1)'] > 1) {
                    shop_snyat($dresssnyat['id']);
                } elseif ($infoshop1['id_punct'] != 8 && $infoshop1['id_punct'] != 9) {
                    shop_snyat($dresssnyat['id']);
                }
            }
        }
    }

    // Вычисляем время истечения
    $time_the_lapse = $infoshop1['time_s'] > 0 ? $infoshop1['time_s'] + time() : 0;

    // Добавляем вещь в инвентарь
    $dress_value = ($odet == 'y' || $infoshop1['id_punct'] == 11) ? 1 : 0;
    
    $query = "INSERT INTO `userbag` SET 
              `id_user` = '" . $mc->real_escape_string($user['id']) . "',
              `id_shop` = '" . $mc->real_escape_string($infoshop1['id']) . "',
              `id_punct` = '" . $mc->real_escape_string($infoshop1['id_punct']) . "',
              `dress` = '" . $mc->real_escape_string($dress_value) . "',
              `iznos` = '" . $mc->real_escape_string($infoshop1['iznos']) . "',
              `time_end` = '" . $mc->real_escape_string($time_the_lapse) . "',
              `id_quests` = '" . $mc->real_escape_string($infoshop1['id_quests']) . "',
              `koll` = '" . $mc->real_escape_string($infoshop1['koll']) . "',
              `max_hc` = '" . $mc->real_escape_string($infoshop1['max_hc']) . "',
              `stil` = '" . $mc->real_escape_string($infoshop1['stil']) . "',
              `BattleFlag` = '" . $mc->real_escape_string($infoshop1['BattleFlag']) . "'";

    if (!$mc->query($query)) {
        error_log("MySQL Error: " . $mc->error);
        return false;
    }

    // Обновляем стиль
    $query = "SELECT `stil` FROM `userbag` 
              WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
              AND `id_punct` < '10' AND `dress` = '1' GROUP BY `stil` ASC";
    $result = $mc->query($query);
    if (!$result) {
        error_log("MySQL Error: " . $mc->error);
        return false;
    }
    $arr = $result->fetch_all(MYSQLI_ASSOC);

    if (count($arr) == 2) {
        $stil = $arr[1]['stil'];
    } elseif (count($arr) == 1 && $arr[0]['stil'] != 0) {
        $stil = $arr[0]['stil'];
    } elseif (count($arr) < 2) {
        $stil = 0;
    } else {
        $stil = 5;
    }

    // Обновляем стиль пользователя
    $query = "UPDATE `users` SET `stil` = '" . $mc->real_escape_string($stil) . "' 
              WHERE `id` = '" . $mc->real_escape_string($user['id']) . "'";
    if (!$mc->query($query)) {
        error_log("MySQL Error: " . $mc->error);
        return false;
    }

    // Пересчитываем здоровье если вещь одета
    if ($dress_value == 1) {
        if (function_exists('health_rechange')) {
            health_rechange();
        }
    }

    return true;
}

?>