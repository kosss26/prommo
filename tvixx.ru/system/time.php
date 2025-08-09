<?php

require_once 'dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$timeregenhp = 5; // Уменьшаем время между тиками
$regenpercent = 15; // Увеличиваем процент восстановления
$timeregenvinos = 59;
if (!empty($_POST["Login"]) && !empty($_POST["Password"])) {
    $LOGIN = urldecode($_POST["Login"]);
    $PASS = $_POST["Password"];
    
    // Используем более старый синтаксис для совместимости
    $Pres = $mc->query("SELECT * FROM `users` WHERE `login` = '" . $mc->real_escape_string($LOGIN) . "' AND `password` = '" . $mc->real_escape_string($PASS) . "'");
    
    if ($Pres->num_rows) {
        $userx = $Pres->fetch_array(MYSQLI_ASSOC);
        $currentTime = time();
        
        $mc->query("UPDATE `users` SET `online`='" . $currentTime . "' WHERE `id`='" . $userx['id'] . "'");
        
        // Проверяем бой
        $resultBattleInfo = $mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $userx['id'] . "' AND `player_activ`='1' AND `end_battle`='0'");
        $flagbattle = ($resultBattleInfo->num_rows > 0) ? 1 : 0;
        
        // Проверяем результаты
        $resultResult = $mc->query("SELECT * FROM `resultbattle` WHERE `id_user`='" . $userx['id'] . "'");
        $flagResult = ($resultResult->num_rows > 0) ? 1 : 0;
        
        $hp = $userx['temp_health'];
        $mhp = $userx['max_health'];

        // Если не в бою и нет результатов - восстанавливаем HP
        if (!$flagbattle && !$flagResult) {
            // Восстанавливаем HP до максимума
            $hp = $mhp;
            
            // Обновляем HP в базе
            $mc->query("UPDATE `users` SET 
                `temp_health` = '" . $hp . "',
                `hp_rt` = '" . $currentTime . "'
                WHERE `id` = '" . $userx['id'] . "'");
        }
        
        // Обработка выносливости
        if ($userx['vinos_rt'] < $currentTime) {
            $timervinos = ($currentTime - $userx['vinos_rt']) / $timeregenvinos;
            $vinosinc = floor(1 + $timervinos);
            $userx['vinos_t'] += $vinosinc;
            if ($userx['vinos_t'] >= $userx['vinos_m']) {
                $userx['vinos_t'] = $userx['vinos_m'];
            }
            
            $mc->query("UPDATE `users` SET 
                `vinos_t`='" . $userx['vinos_t'] . "',
                `vinos_rt`='" . ($currentTime + $timeregenvinos) . "'
                WHERE `id`='" . $userx['id'] . "'");
        }
        
        $mymsg = $mc->query("SELECT * FROM `msg` WHERE `id_user` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
        echo json_encode(array(
            "onbattle" => $flagbattle,
            "result" => $flagResult,
            "hp" => $hp,
            "vinos" => $userx['vinos_t'],
            "msg" => array(
                "message" => $mymsg['message'],
                "error" => "0",
                "type" => $mymsg['type'],
                "id" => $mymsg['id']
            )
        ));
        exit(0);
    }
    echo json_encode(array(
        "onbattle" => 0,
        "result" => 0,
        "hp" => 0,
        "vinos" => 0,
        "msg" => array("message" => "", "error" => "0", "type" => "", "id" => "")
    ));
    exit(0);
}
echo json_encode(array(
    "onbattle" => 0,
    "result" => 0,
    "hp" => 0,
    "vinos" => 0,
    "msg" => array("message" => "", "error" => "0", "type" => "", "id" => "")
));
exit(0);




