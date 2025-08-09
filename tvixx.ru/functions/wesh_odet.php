<?php

function wesh_odet($id) {
    global $mc;
    global $user;
    
    if (!isset($user) || !$id) {
        // Убрано уведомление, оставлено только логирование
        error_log("Ошибка: недостаточно данных для надевания предмета");
        return false;
    }

    // Получаем информацию о вещи с дополнительной проверкой
    $query = "SELECT u.*, s.level, s.name FROM `userbag` u 
              LEFT JOIN `shop` s ON s.id = u.id_shop 
              WHERE u.id = '" . $mc->real_escape_string($id) . "' 
              AND u.id_user = '" . $mc->real_escape_string($user['id']) . "' LIMIT 1";
    $result = $mc->query($query);
    if (!$result) {
        error_log("MySQL Error (wesh_odet): " . $mc->error);
        return false;
    }
    
    $infoshop1 = $result->fetch_array(MYSQLI_ASSOC);
    if (!$infoshop1) {
        error_log("Ошибка: предмет не найден при попытке надеть");
        return false;
    }

    // Проверяем уровень вещи
    if ($infoshop1['level'] > $user['level']) {
        error_log("Уровень пользователя недостаточен для предмета ID: " . $id);
        return false;
    }

    // Проверяем есть ли уже одетая вещь такого типа
    if ($infoshop1['id_punct'] != 11) { // Не бонусы
        $query = "SELECT * FROM `userbag` WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
                  AND `dress` = '1' AND `id_punct` = '" . $mc->real_escape_string($infoshop1['id_punct']) . "'";
        $result = $mc->query($query);
        if (!$result) {
            error_log("MySQL Error (wesh_odet check dressed): " . $mc->error);
            return false;
        }
        
        // Для колец и поясов проверяем максимальное количество
        if ($infoshop1['id_punct'] == 8) { // Кольца
            $count = $result->num_rows;
            if ($count >= 2) {
                error_log("Превышено количество колец при попытке надеть ID: " . $id);
                return false;
            }
        } elseif ($infoshop1['id_punct'] == 9) { // Пояса
            $count = $result->num_rows;
            if ($count >= 7) {
                error_log("Превышено количество предметов на поясе при попытке надеть ID: " . $id);
                return false;
            }
        } else {
            // Для других слотов снимаем старую вещь если есть
            $dresssnyat = $result->fetch_array(MYSQLI_ASSOC);
            if ($dresssnyat) {
                if (!shop_snyat($dresssnyat['id'])) {
                    error_log("Не удалось снять предмет ID: " . $dresssnyat['id'] . " при надевании нового");
                    return false;
                }
            }
        }
    }

    // Одеваем новую вещь с дополнительной проверкой
    $query = "UPDATE `userbag` SET `dress` = '1' 
              WHERE `id` = '" . $mc->real_escape_string($id) . "' 
              AND `id_user` = '" . $mc->real_escape_string($user['id']) . "'";
    if (!$mc->query($query)) {
        error_log("MySQL Error (wesh_odet update): " . $mc->error);
        return false;
    }
    
    // Проверяем, успешно ли обновлена запись
    if ($mc->affected_rows == 0) {
        error_log("Предмет не был экипирован ID: " . $id);
        return false;
    }

    // Обновляем стиль
    $query = "SELECT `stil` FROM `userbag` 
              WHERE `id_user` = '" . $mc->real_escape_string($user['id']) . "' 
              AND `id_punct` < '10' AND `dress` = '1' GROUP BY `stil` ASC";
    $result = $mc->query($query);
    
    // Инициализируем дефолтное значение стиля
    $stil = 0;
    
    if ($result && $result->num_rows > 0) {
        // Используем fetch_all только если результат запроса успешный
        $arr = $result->fetch_all(MYSQLI_ASSOC);

        if (count($arr) == 2) {
            $stil = $arr[1]['stil'];
        } elseif (count($arr) == 1 && $arr[0]['stil'] != 0) {
            $stil = $arr[0]['stil'];
        } elseif (count($arr) > 2) {
            $stil = 5;
        }
    } else if (!$result) {
        error_log("MySQL Error (wesh_odet style): " . $mc->error);
    }

    // Обновляем стиль пользователя
    $query = "UPDATE `users` SET `stil` = '" . $mc->real_escape_string($stil) . "' 
              WHERE `id` = '" . $mc->real_escape_string($user['id']) . "'";
    if (!$mc->query($query)) {
        error_log("MySQL Error (wesh_odet update style): " . $mc->error);
    }

    // Пересчитываем здоровье
    if (function_exists('health_rechange')) {
        health_rechange();
    } else if (function_exists('get_user_stats')) {
        get_user_stats($user['id']);
    }
    
    // Удалена строка с отображением сообщения об успехе
    return true;
}

// Функции для отображения сообщений оставляем на случай, если они понадобятся в других местах
if (!function_exists('show_error_message')) {
    /**
     * Функция для отображения сообщения об ошибке
     */
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
    /**
     * Функция для отображения сообщения об успехе
     */
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