<?php

function shop_snyat($id) {
    global $mc;
    global $user;
    
    if (!isset($user) || !$id) {
        error_log("Недостаточно данных для снятия предмета");
        return false;
    }

    // Проверяем есть ли такая вещь у пользователя
    $query = "SELECT u.*, s.name FROM `userbag` u 
              LEFT JOIN `shop` s ON s.id = u.id_shop
              WHERE u.id_user = '" . $mc->real_escape_string($user['id']) . "' 
              AND u.dress = '1' AND u.id = '" . $mc->real_escape_string($id) . "' LIMIT 1";
    $result = $mc->query($query);
    if (!$result) {
        error_log("MySQL Error (shop_snyat select): " . $mc->error);
        return false;
    }
    
    $dresssnyat = $result->fetch_array(MYSQLI_ASSOC);
    if (!$dresssnyat) {
        error_log("Предмет не найден или уже снят, ID: " . $id);
        return false;
    }

    // Проверяем, не в процессе ли боя пользователь
    if (isset($user['in_battle']) && $user['in_battle'] == 1) {
        error_log("Попытка снять предмет во время боя, ID: " . $id);
        return false;
    }

    // Снимаем вещь - создаем новую запись с dress = 0
    $query = "INSERT INTO `userbag` SET 
              `id_user` = '" . $mc->real_escape_string($dresssnyat['id_user']) . "',
              `id_shop` = '" . $mc->real_escape_string($dresssnyat['id_shop']) . "',
              `id_punct` = '" . $mc->real_escape_string($dresssnyat['id_punct']) . "',
              `dress` = '0',
              `iznos` = '" . $mc->real_escape_string($dresssnyat['iznos']) . "',
              `id_quests` = '" . $mc->real_escape_string($dresssnyat['id_quests']) . "',
              `koll` = '" . $mc->real_escape_string($dresssnyat['koll']) . "',
              `max_hc` = '" . $mc->real_escape_string($dresssnyat['max_hc']) . "',
              `time_end` = '" . $mc->real_escape_string($dresssnyat['time_end']) . "',
              `stil` = '" . $mc->real_escape_string($dresssnyat['stil']) . "',
              `BattleFlag` = '" . $mc->real_escape_string($dresssnyat['BattleFlag']) . "'";
              
    if (!$mc->query($query)) {
        error_log("MySQL Error (shop_snyat insert): " . $mc->error);
        return false;
    }
    
    $new_id = $mc->insert_id;
    if (!$new_id) {
        error_log("MySQL Error (shop_snyat insert_id): Не удалось получить ID новой записи");
        return false;
    }

    // Удаляем старую запись
    $query = "DELETE FROM `userbag` WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
              AND `id` = '" . $mc->real_escape_string($id) . "'";
    if (!$mc->query($query)) {
        // В случае ошибки удаляем новую запись, чтобы избежать дублирования
        $mc->query("DELETE FROM `userbag` WHERE `id` = '" . $mc->real_escape_string($new_id) . "'");
        
        error_log("MySQL Error (shop_snyat delete): " . $mc->error);
        return false;
    }
    
    if ($mc->affected_rows == 0) {
        // Если запись не удалена, удаляем новую запись, чтобы избежать дублирования
        $mc->query("DELETE FROM `userbag` WHERE `id` = '" . $mc->real_escape_string($new_id) . "'");
        
        error_log("Предмет не был снят. ID: " . $id);
        return false;
    }

    // Обновляем стиль
    $query = "SELECT `stil` FROM `userbag` WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
              AND `id_punct` < '10' AND `dress` = '1' GROUP BY `stil` ASC";
    $result = $mc->query($query);
    if (!$result) {
        error_log("MySQL Error (shop_snyat style select): " . $mc->error);
        // Не показываем ошибку пользователю, т.к. предмет уже снят
    } else {
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
            error_log("MySQL Error (shop_snyat update style): " . $mc->error);
            // Не показываем ошибку пользователю, т.к. предмет уже снят
        }
    }

    // Пересчитываем здоровье
    if (function_exists('health_rechange')) {
        health_rechange();
    } else if (function_exists('get_user_stats')) {
        get_user_stats($user['id']);
    }
    
    return true;
}

// Оставляем функции на случай, если они понадобятся, но с проверкой существования
if (!function_exists('show_error_message')) {
    function show_error_message($message) {
        echo '<script>
        $(document).ready(function() {
            // Создаем уведомление, если контейнер не существует
            if ($("#notification-container").length === 0) {
                $("body").append("<div id=\"notification-container\" style=\"position: fixed; top: 20px; right: 20px; z-index: 9999;\"></div>");
            }
            
            // Добавляем уведомление
            var notification = $("<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\" style=\"max-width: 300px;\">" + 
                "<strong>Ошибка!</strong> " + 
                "' . addslashes($message) . '" +
                "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>" +
                "</div>");
            
            $("#notification-container").append(notification);
            
            // Автоматически скрываем через 5 секунд
            setTimeout(function() {
                notification.alert("close");
            }, 5000);
        });
        </script>';
    }
}

if (!function_exists('show_success_message')) {
    function show_success_message($message) {
        echo '<script>
        $(document).ready(function() {
            // Создаем уведомление, если контейнер не существует
            if ($("#notification-container").length === 0) {
                $("body").append("<div id=\"notification-container\" style=\"position: fixed; top: 20px; right: 20px; z-index: 9999;\"></div>");
            }
            
            // Добавляем уведомление
            var notification = $("<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\" style=\"max-width: 300px;\">" + 
                "<strong>Успех!</strong> " + 
                "' . addslashes($message) . '" +
                "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>" +
                "</div>");
            
            $("#notification-container").append(notification);
            
            // Автоматически скрываем через 5 секунд
            setTimeout(function() {
                notification.alert("close");
            }, 3000);
        });
        </script>';
    }
}
?>