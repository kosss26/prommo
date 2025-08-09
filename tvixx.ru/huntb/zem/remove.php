<?php

require_once '../../system/func.php';

if (isset($user)) {

    $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
    ?>
    <script>
        showContent('huntb/zem/index.php?msg=' + encodeURIComponent('участие отменено.'));
    </script>
    <?php

} else {
    ?>
    <script>
        showContent('main.php?msg=' + encodeURIComponent('невозможно отказаться ммм.'));
    </script>
    <?php

}
