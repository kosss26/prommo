<?php
require '../system/func.php';
if (isset($_GET['ban']) && isset($_GET['id'])) {
    if (!$user OR $user['access'] < 3) {
        ?><script>showContent("/");</script><?php
        exit(0);
    } else {
        $date = date("Y-m-d H:i:s");
        $ires = $mc->query("SELECT * FROM `users` WHERE `id`='".$_GET['id']."'");
        if ($ires->num_rows) {
            $i = $ires->fetch_array(MYSQLI_ASSOC);
        } else {
            message('пользователь с id = ' . $i['id'] . ' не найден');
            exit(0);
        }
        $mc->query("UPDATE `users` SET `game_ban`='1' WHERE `id` = '" . $_GET['id'] . "'");

        $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> заблокировал героя </font><a onclick=\\'showContent(\\\"/profile.php?id=" . $i['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $i['name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
        $chatmsg3 = "id : " . $user['id'] . " name :" . $user['name'] . " заблокировал героя -> id : " . $i['id'] . " name " . $i['name'];
        $mc->query("INSERT INTO `chat`("
                . "`id`,"
                . "`name`,"
                . "`id_user`,"
                . "`chat_room`,"
                . "`msg`,"
                . "`msg2`,"
                . "`msg3`,"
                . "`time`,"
                . " `unix_time`"
                . ") VALUES ("
                . "NULL,"
                . "'АДМИНИСТРИРОВАНИЕ',"
                . "'" . $user['id'] . "',"
                . "'5',"
                . " '$chatmsg',"
                . "'" . $i['id'] . "',"
                . "'$chatmsg3',"
                . "'" . $date . "',"
                . "'" . time() . "'"
                . " )");
        ?><script>
            showContent('/main?msg=' + encodeURIComponent('персонаж успешно заблокирован'));
        </script>
        <?php
    }
}
if (isset($_GET['upban']) && isset($_GET['id'])) {
    if (!$user OR $user['access'] < 3) {
        ?><script>showContent("/");</script><?php
        exit(0);
    } else {
        $date = date("Y-m-d H:i:s");
        $ires = $mc->query("SELECT * FROM `users` WHERE `id`='".$_GET['id']."'");
        if ($ires->num_rows) {
            $i = $ires->fetch_array(MYSQLI_ASSOC);
        } else {
            message('пользователь с id = ' . $i['id'] . ' не найден');
            exit(0);
        }
        $mc->query("UPDATE `users` SET `game_ban`='0' WHERE `id` = '" . $_GET['id'] . "'");

        $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> разблокировал героя </font><a onclick=\\'showContent(\\\"/profile.php?id=" . $i['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $i['name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
        $chatmsg3 = "id : " . $user['id'] . " name :" . $user['name'] . " разблокировал героя -> id : " . $i['id'] . " name " . $i['name'];
        $mc->query("INSERT INTO `chat`("
                . "`id`,"
                . "`name`,"
                . "`id_user`,"
                . "`chat_room`,"
                . "`msg`,"
                . "`msg2`,"
                . "`msg3`,"
                . "`time`,"
                . " `unix_time`"
                . ") VALUES ("
                . "NULL,"
                . "'АДМИНИСТРИРОВАНИЕ',"
                . "'" . $user['id'] . "',"
                . "'5',"
                . " '$chatmsg',"
                . "'" . $i['id'] . "',"
                . "'$chatmsg3',"
                . "'" . $date . "',"
                . "'" . time() . "'"
                . " )");
        ?><script>
            showContent('/main?msg=' + encodeURIComponent('персонаж успешно разблокирован'));
        </script>
        <?php
    }
}
?>