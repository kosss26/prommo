<?php

require_once '../../../system/func.php';
if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script>
    <?php

}

if (isset($user)) {
    if ($user['side'] == 0 || $user['side'] == 1) {
        $user_rasa = 0;
    } else {
        $user_rasa = 1;
    }
    if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows == 0) {

        if ($user['money'] >= 10000) {
            $mc->query("UPDATE `users` SET `money`=`money`-'10000' WHERE `id`= '" . $user['id'] . "'");
            $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
            $mc->query("INSERT INTO `huntb_list`("
                    . "`id`,"
                    . " `user_id`,"
                    . " `level`,"
                    . " `rasa`,"
                    . " `time_start`,"
                    . " `type`"
                    . ") VALUES ("
                    . "'NULL',"
                    . "'" . $user['id'] . "',"
                    . "'" . $user['level'] . "',"
                    . "'$user_rasa',"
                    . "'" . time() . "',"
                    . "'5'"
                    . ")");
            ?>
            <script>
                showContent('huntb/tur/stenka/in.php?msg=' + encodeURIComponent('Заявка принята.'));
            </script>
        <?php } else { ?>
            <script>
                showContent('main.php?msg=' + encodeURIComponent('недостаточно золота.'));
            </script>
            <?php

        }
    } else {
        ?>
        <script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script>
        <?php

    }
} else {
    ?>
    <script>
        showContent('main.php?msg=' + encodeURIComponent('невозможно зарегистрироваться.'));
    </script>
    <?php

}