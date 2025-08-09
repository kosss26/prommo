<?php

require_once '../../../system/func.php';

// Проверка авторизации и уровня
if (!isset($user) || $user['level'] <= 1) {
    ?>
    <script>
        showContent('/main.php?msg=' + encodeURIComponent('Необходим 2-й уровень для участия в турнире'));
    </script>
    <?php
    exit;
}

// Определение расы пользователя
$user_rasa = ($user['side'] == 0 || $user['side'] == 1) ? 0 : 1;

// Проверка активных боев
$activeBattle = $mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows;

if ($activeBattle > 0) {
    ?>
    <script>
        showContent("/hunt/battle.php");
    </script>
    <?php
    exit;
}

// Проверка наличия золота
if ($user['money'] < 10000) {
    ?>
    <script>
        showContent('/main.php?msg=' + encodeURIComponent('Недостаточно золота для участия в турнире'));
    </script>
    <?php
    exit;
}

// Регистрация на турнир
$mc->query("UPDATE `users` SET `money`=`money`-'10000' WHERE `id`= '" . $user['id'] . "'");
$mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");

$mc->query("INSERT INTO `huntb_list` (
    `user_id`,
    `level`,
    `rasa`,
    `time_start`,
    `type`
) VALUES (
    '" . $user['id'] . "',
    '" . $user['level'] . "',
    '$user_rasa',
    '" . time() . "',
    '3'
)");

?>
<script>
    showContent('/huntb/tur/vjv/in.php?msg=' + encodeURIComponent('Вы успешно зарегистрированы на турнир'));
</script>