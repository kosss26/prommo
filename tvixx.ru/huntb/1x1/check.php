<?php

require_once '../../system/func.php';
$time_wait = 5;
if (isset($user)) {
    // Логирование
    error_log("Check.php - Start. User ID: " . $user['id']);
    
    $resultBattleInfo = $mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'");
    $huntMyRes = $mc->query("SELECT * FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "' && (`type`='1'||`type`='2')");
    
    if ($huntMyRes->num_rows > 0) {
        $huntMy = $huntMyRes->fetch_array(MYSQLI_ASSOC);
        $remaining_time = $time_wait - (time() - $huntMy['time_start']);
        
        // Убедитесь, что значение времени не меньше нуля
        $remaining_time = max(0, $remaining_time);
        
        echo json_encode(array(
            "result" => 0,
            "error" => 0,
            "time" => $remaining_time
        ));
    } elseif ($resultBattleInfo->num_rows > 0) {
        // Проверяем, создан ли бой
        $battle = $resultBattleInfo->fetch_array(MYSQLI_ASSOC);
        error_log("Check.php - Battle found: " . $battle['id']);
        
        echo json_encode(array(
            "result" => 1,
            "error" => 0,
            "time" => 0
        ));
    } else {
        // Ищем противника в очереди
        $opponent = $mc->query("SELECT user_id FROM `huntb_list` 
            WHERE user_id != '" . $user['id'] . "' 
            AND type = '1' 
            LIMIT 1");
            
        if ($opponent && $opponent->num_rows > 0) {
            $opponent_data = $opponent->fetch_array(MYSQLI_ASSOC);
            
            // Начинаем транзакцию
            $mc->query("START TRANSACTION");
            
            try {
                // Создаем новый бой
                $result = $mc->query("INSERT INTO `battle` 
                    (`Mid`, `player_activ`, `end_battle`, `created_at`) 
                    VALUES ('" . $user['id'] . "', '1', '0', NOW())");
                    
                if (!$result) {
                    throw new Exception("Failed to create battle");
                }
                
                $battle_id = $mc->insert_id;
                
                // Добавляем противника
                $result = $mc->query("UPDATE `battle` 
                    SET `opponent_id` = '" . $opponent_data['user_id'] . "', 
                        `player_activ` = '1'
                    WHERE `id` = '" . $battle_id . "'");
                    
                if (!$result) {
                    throw new Exception("Failed to add opponent");
                }
                
                // Удаляем из очереди
                $result = $mc->query("DELETE FROM `huntb_list` 
                    WHERE user_id IN ('" . $user['id'] . "', '" . $opponent_data['user_id'] . "')");
                    
                if (!$result) {
                    throw new Exception("Failed to remove from queue");
                }
                
                $mc->query("COMMIT");
                error_log("Check.php - New battle created: " . $battle_id);
                
                echo json_encode(array(
                    "result" => 1,
                    "error" => 0,
                    "time" => 0
                ));
                
            } catch (Exception $e) {
                $mc->query("ROLLBACK");
                error_log("Check.php - Error creating battle: " . $e->getMessage());
                echo json_encode(array(
                    "result" => 0,
                    "error" => 1,
                    "message" => "Failed to create battle"
                ));
            }
        } else {
            echo json_encode(array(
                "result" => 0,
                "error" => 0,
                "time" => $time_wait
            ));
        }
    }
}
