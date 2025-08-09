<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/system/connect.php';

$mc = $mc;

health_rechange();

function health_rechange() {
    global $mc;
    global $user;
    if (!empty($user)) {
        // Проверяем, не в бою ли игрок
        $in_battle = $mc->query("SELECT 1 FROM `battle` 
            WHERE `Mid` = '" . $user['id'] . "' 
            AND `player_activ` = '1' 
            AND `end_battle` = '0' 
            LIMIT 1")->num_rows > 0;
            
        // Проверяем, есть ли незавершенные результаты боя
        $has_result = $mc->query("SELECT 1 FROM `resultbattle` 
            WHERE `id_user` = '" . $user['id'] . "' 
            LIMIT 1")->num_rows > 0;
            
        // Если не в бою и нет результатов - восстанавливаем здоровье до максимума
        if (!$in_battle && !$has_result) {
            // Получаем актуальные характеристики игрока с учетом снаряжения
            $stats = get_user_stats($user['id']);
            
            // Устанавливаем здоровье игрока на максимум с учетом бонусов от снаряжения
            $mc->query("UPDATE `users` SET 
                `temp_health` = '{$stats['max_health']}',
                `hp_rt` = '" . time() . "'
                WHERE `id` = '" . $user['id'] . "'");
                
            // Обновляем переменную пользователя
            $user['temp_health'] = $stats['max_health'];
        }
    }
}

function get_user_stats() {
    global $mc;
    global $user;
    if (!empty($user)) {
        $arr = [];
        //setzero
        $arr['temp_health'] = $user['temp_health'];
        $arr['max_health'] = $user['health'];
        $arr['strength'] = $user['strength'];
        $arr['toch'] = $user['toch'];
        $arr['lov'] = $user['lov'];
        $arr['kd'] = $user['kd'];
        $arr['block'] = $user['block'];
        $arr['bron'] = $user['bron'];
        //read dress
        $result = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `dress`='1' && `BattleFlag`='1' || `id_user` = '" . $user['id'] . "' AND `id_punct`>'9' && `BattleFlag`='1'");
        if ($result->num_rows) {
            //dress to arr all
            $dressarr = $result->fetch_all(MYSQLI_ASSOC);
            for ($i = 0; $i < count($dressarr); $i++) {
                //read thing
                $result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $dressarr[$i]['id_shop'] . "'");
                if ($result1->num_rows) {
                    //thing to arr par
                    $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
                    $arr['max_health'] += $infoshop['health'];
                    $arr['strength'] += $infoshop['strength'];
                    $arr['toch'] += $infoshop['toch'];
                    $arr['lov'] += $infoshop['lov'];
                    $arr['kd'] += $infoshop['kd'];
                    $arr['block'] += $infoshop['block'];
                    $arr['bron'] += $infoshop['bron'];
                }
            }
        }

        // Проверяем, не в бою ли игрок
        $in_battle = $mc->query("SELECT 1 FROM `battle` 
            WHERE `Mid` = '" . $user['id'] . "' 
            AND `player_activ` = '1' 
            AND `end_battle` = '0' 
            LIMIT 1")->num_rows > 0;
            
        // Обновляем максимальное здоровье в БД только если не в бою
        if (!$in_battle) {
            // Обновляем max_health в базе данных
            $mc->query("UPDATE `users` SET 
                `max_health` = '" . $arr['max_health'] . "'
                WHERE `id` = '" . $user['id'] . "'");
                
            // Если текущее здоровье больше нового максимального, уменьшаем его
            if ($user['temp_health'] > $arr['max_health']) {
                $mc->query("UPDATE `users` SET 
                    `temp_health` = '" . $arr['max_health'] . "'
                    WHERE `id` = '" . $user['id'] . "'");
                $arr['temp_health'] = $arr['max_health'];
            }
            
            // Если текущее здоровье меньше максимального и игрок не в бою,
            // восстанавливаем до максимума (если нужно)
            if (!isset($user['temp_health']) || $user['temp_health'] < $arr['max_health']) {
                $mc->query("UPDATE `users` SET 
                    `temp_health` = '" . $arr['max_health'] . "'
                    WHERE `id` = '" . $user['id'] . "'");
                $arr['temp_health'] = $arr['max_health'];
            }
            
            // Обновляем переменную пользователя
            $user['max_health'] = $arr['max_health'];
            $user['temp_health'] = $arr['temp_health'];
        }

        return $arr;
    }
}
