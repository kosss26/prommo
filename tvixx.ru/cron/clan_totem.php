<?php
require_once 'bd.php';
require_once 'slava.php';

$clans1 = $mc->query("SELECT * FROM `clan`");
while ($clans = $clans1->fetch_array(MYSQLI_ASSOC)) {
    $goldminus = $clans['gold'] - ($clans['totemtec'] * (10));
    if ($goldminus >= 0) {
        $mc->query("UPDATE `clan` SET `gold`='" . $goldminus . "',`totem`='" . $clans['totemtec'] . "' WHERE `id`='" . $clans['id'] . "'");
    } else {
        $mc->query("UPDATE `clan` SET `totem`='0' WHERE `id`='" . $clans['id'] . "'");
    }
}

////reid
$mc->query("UPDATE `clan` SET `idreid`='0',`reidmob`='' WHERE 1");
$mc->query("UPDATE `users` SET `reid`='0' WHERE 1");

///chat
$mc->query("DELETE FROM `chat` WHERE `id` IN (SELECT `id` FROM (SELECT `id` FROM `chat` WHERE `chat_room` != '5' ORDER BY `id` DESC LIMIT 10, 10000000) abc)");

//dhd
$mc->query("UPDATE `users` SET `dhdenter` = 1 WHERE 1");

// Сброс рейтинга, полученного более 120 часов назад
$expiration_time = time() - (120 * 3600); // 120 часов = 120 * 3600 секунд

// Получаем просроченные записи о рейтинге
$expired_ratings = $mc->query("SELECT * FROM `clan_rating_history` WHERE `timestamp` < '$expiration_time'");

// Если есть просроченные записи
if ($expired_ratings && $expired_ratings->num_rows > 0) {
    while ($rating = $expired_ratings->fetch_assoc()) {
        // Снижаем рейтинг клана
        if ($rating['clan_rating'] > 0) {
            $mc->query("UPDATE `clan` SET `reit` = GREATEST(0, `reit` - " . $rating['clan_rating'] . ") 
                        WHERE `id` = '" . $rating['clan_id'] . "'");
        }
        
        // Снижаем рейтинг пользователя
        if ($rating['user_rating'] > 0) {
            $mc->query("UPDATE `users` SET `reit` = GREATEST(0, `reit` - " . $rating['user_rating'] . ") 
                        WHERE `id` = '" . $rating['user_id'] . "' AND `id_clan` = '" . $rating['clan_id'] . "'");
        }
    }
    
    // Удаляем просроченные записи
    $mc->query("DELETE FROM `clan_rating_history` WHERE `timestamp` < '$expiration_time'");
}

?>