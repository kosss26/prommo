<?php

require_once '../../../system/func.php';

if (!isset($user)) {
    ?>
    <script>
        showContent('/main.php?msg=' + encodeURIComponent('Необходима авторизация'));
    </script>
    <?php
    exit;
}

// Проверяем, был ли пользователь зарегистрирован на турнир
$registered = $mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "' && `type`='3'")->fetch_array(MYSQLI_ASSOC);

if ($registered) {
    // Возвращаем взнос
    $mc->query("UPDATE `users` SET `money`=`money`+'10000' WHERE `id`= '" . $user['id'] . "'");
    // Удаляем запись об участии
    $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
    ?>
    <script>
        showContent('/huntb/tur/vjv/in.php?msg=' + encodeURIComponent('Участие отменено. Взнос возвращен.'));
    </script>
    <?php
} else {
    ?>
    <script>
        showContent('/huntb/tur/vjv/in.php?msg=' + encodeURIComponent('Вы не были зарегистрированы на турнир.'));
    </script>
    <?php
}
