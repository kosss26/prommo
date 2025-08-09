<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connect.php';

if (!isset($_GET['clan_new_y']) && !isset($_GET['clan_new_n']) && isset($user) &&
        $user['id_clan'] != $user['id_new_clan']&&$user['id_new_clan']!=0) {
    $name = $mc->query("SELECT `name` FROM `clan` WHERE `id`='" . $user['id_new_clan'] . "'")->fetch_array(MYSQLI_ASSOC)['name'];
    message_yn(
            urlencode("Вас приглашают в клан <b>" . $name . "</b> !"), urlencode("main.php?clan_new_y"), urlencode("main.php?clan_new_n"), urlencode("Согласиться"), urlencode("Отказаться")
    );
}
if (isset($_GET['clan_new_y']) && !isset($_GET['clan_new_n']) && isset($user)) {
    $mc->query("UPDATE `users` SET `des`='0',`reit`='0',`id_clan`=`id_new_clan`,`id_new_clan`='0' WHERE `id`= '" . $user['id'] . "'");
        ?>
        <script>
            showContent('main.php?msg=' + encodeURIComponent('Приглашение принято .<br>Классно Ага .'));
        </script>
        <?php

        exit(0);
}
if (!isset($_GET['clan_new_y']) && isset($_GET['clan_new_n']) && isset($user)) {
    $mc->query("UPDATE `users` SET `id_new_clan`='0' WHERE `id`= '" . $user['id'] . "'");
        ?>
        <script>
            showContent('main.php?msg=' + encodeURIComponent('Приглашение отклонено .<br> нууу .'));
        </script>
        <?php

        exit(0);
}
