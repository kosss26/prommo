<?php
require_once '../system/connect.php';
if (isset($_GET['id_msg']) && isset($user)) {
    // Защита от SQL инъекций
    $id_msg = intval($_GET['id_msg']);
    $user_id = intval($user['id']);
    
    // Получаем информацию о сообщении
    $thisMsgRes = $mc->query("SELECT * FROM `msg` WHERE `id` = '$id_msg' AND `id_user` = '$user_id'");
    
    if ($thisMsgRes && $thisMsgRes->num_rows > 0) {
        $thisMsg = $thisMsgRes->fetch_array(MYSQLI_ASSOC);
        
        // Для типа "mail" удаляем все уведомления этого типа, для других - только конкретное
        if ($thisMsg['type'] == "mail") {
            $mc->query("DELETE FROM `msg` WHERE `id_user` = '$user_id' AND `type` = 'mail'");
        } else {
            $mc->query("DELETE FROM `msg` WHERE `id` = '$id_msg'");
        }
        
        // Добавим запись в лог для отладки
        $log_file = $_SERVER['DOCUMENT_ROOT'] . '/logs/notification_log.txt';
        $log_message = date('Y-m-d H:i:s') . " - Удалено уведомление ID: $id_msg, Тип: {$thisMsg['type']}, Пользователь: $user_id\n";
        @file_put_contents($log_file, $log_message, FILE_APPEND);
    }
}
?>