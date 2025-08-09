<?php

require_once '../../../system/func.php';

if (isset($user)) {
    if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "' && `type`='5'")->fetch_array(MYSQLI_ASSOC)) {
        $mc->query("UPDATE `users` SET `money`=`money`+'10000' WHERE `id`= '" . $user['id'] . "'");
    }
    $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
    ?>
    <script>
        showContent('huntb/tur/stenka/in.php?msg=' + encodeURIComponent('участие отменено.'));
    </script>
    <?php

} else {
    ?>
    <script>
        showContent('main.php?msg=' + encodeURIComponent('невозможно отказаться ммм.'));
    </script>
    <?php

}
