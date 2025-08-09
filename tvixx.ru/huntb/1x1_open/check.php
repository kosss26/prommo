<?php
require_once '../../system/func.php';
require_once '../../cron/duel1_1.php';

$time_wait = 3;

// Функция для генерации параметров бота
function genbotpar($command2, $names, $z) {
    $n = [];
    $si = [];
    $st = [];
    $i = 0;
    while ($i < $z) {
        if ($command2 == 1) {
            $side = rand(2, 3);
        } else if ($command2 == 0) {
            $side = rand(0, 1);
        }
        if ($side == 0 || $side == 2) {
            $pol = 0;
        } else if ($side == 1 || $side == 3) {
            $pol = 1;
        }
        $temp = $names[$pol][array_rand($names[$pol])];
        if (!in_array($temp, $n)) {
            $n[] = $temp;
            $si[] = $side;
            $st[] = rand(0, 4);
            $i++;
        }
    }
    return [$n, $si, $st];
}

// Массив имен для ботов
$names = [
    [ // Мужские имена
        "Темный_Рыцарь", "Призрачный_Воин", "Мастер_Клинка", "Хранитель_Севера", 
        "Воин_Света", "Мститель", "Странник_Пустошей", "Кровавый_Охотник"
    ],
    [ // Женские имена
        "Лунная_Дева", "Хранительница_Света", "Повелительница_Льда", "Дочь_Бури",
        "Огненная_Леди", "Лесная_Нимфа", "Морская_Дева", "Небесная_Воительница"
    ]
];

if (isset($user)) {
    // Проверяем текущий бой
    $stmt = $mc->prepare("SELECT * FROM `battle` WHERE `Mid` = ? AND `player_activ` = '1' AND `end_battle` = '0'");
    $stmt->bind_param('i', $user['id']);
    $stmt->execute();
    $battleResult = $stmt->get_result();
    
    if ($battleResult->num_rows > 0) {
        $battle = $battleResult->fetch_assoc();
        echo json_encode(array(
            "result" => 1,
            "battle_id" => $battle['battle_id'],
            "error" => null
        ));
        exit;
    }
    
    // Проверяем запись в списке поиска
    $stmt = $mc->prepare("SELECT * FROM `huntb_list` WHERE `user_id` = ? AND `type` = '2'");
    $stmt->bind_param('i', $user['id']);
    $stmt->execute();
    $huntResult = $stmt->get_result();
    
    if ($huntResult->num_rows > 0) {
        $hunt = $huntResult->fetch_assoc();
        $waitTime = $time_wait - (time() - $hunt['time_start']);
        
        if ($waitTime <= 0) {
            // Создаем бой с ботом
            $battle_id = time() . rand(1000, 9999);
            $battle_start_time = time();
            $command1 = ($user['side'] == 0 || $user['side'] == 1) ? 0 : 1;
            $command2 = ($command1 == 0) ? 1 : 0;
            
            // Используем функцию из duel1_1.php
            $arrbotpar = genbotpar($command2, $names, 1);
            
            if ($arrbotpar && isset($arrbotpar[0][0])) {
                try {
                    // Проверяем не создан ли уже бой для этого игрока
                    $existingBattle = $mc->query("SELECT * FROM `battle` WHERE `Mid` = '" . $user['id'] . "' AND `player_activ` = '1' AND `end_battle` = '0'");
                    
                    if ($existingBattle->num_rows == 0) {
                        // Проверяем, что игрок все еще в списке ожидания
                        $stillWaiting = $mc->query("SELECT * FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "' AND `type` = '" . $hunt['type'] . "'");
                        
                        if ($stillWaiting->num_rows > 0) {
                            // Проверяем, что бой еще не создан
                            $checkExisting = $mc->query("SELECT * FROM `battle` WHERE `battle_id` = '" . $battle_id . "'");
                            if ($checkExisting->num_rows == 0) {
                                if (hero1_add($command1, $user, $arrbotpar[0][0] . "[БОТ]", $battle_id, $battle_start_time, $hunt['type'])) {
                                    if (bot_add($arrbotpar[0][0] . "[БОТ]", $command2, $arrbotpar[1][0], $arrbotpar[2][0], $user['level'], $battle_id, $battle_start_time, $hunt['type'])) {
                                        // Удаляем из списка поиска
                                        $stmt = $mc->prepare("DELETE FROM `huntb_list` WHERE `user_id` = ?");
                                        $stmt->bind_param('i', $user['id']);
                                        $stmt->execute();
                                        
                                        echo json_encode(array(
                                            "result" => 1,
                                            "battle_id" => $battle_id,
                                            "error" => null
                                        ));
                                        exit;
                                    }
                                }
                            }
                        }
                    }
                    
                    error_log("Failed to create battle for user " . $user['id']);
                    echo json_encode(array(
                        "result" => 0,
                        "error" => "Ошибка создания боя",
                        "waiting_for_bot" => true
                    ));
                    
                } catch (Exception $e) {
                    error_log("Error creating battle: " . $e->getMessage());
                    echo json_encode(array(
                        "result" => 0,
                        "error" => "Внутренняя ошибка сервера",
                        "waiting_for_bot" => true
                    ));
                }
            } else {
                error_log("Failed to generate bot parameters for user " . $user['id']);
                echo json_encode(array(
                    "result" => 0,
                    "error" => "Ошибка генерации бота",
                    "waiting_for_bot" => true
                ));
            }
            
        } else {
            echo json_encode(array(
                "result" => 0,
                "time" => $waitTime,
                "error" => null
            ));
        }
    } else {
        echo json_encode(array(
            "result" => 0,
            "error" => "Не найдена запись в списке поиска"
        ));
    }
} else {
    echo json_encode(array(
        "result" => 0,
        "error" => "Пользователь не авторизован"
    ));
} 