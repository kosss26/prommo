<?php

$AppVersion = "2.11140";
if (!isset($_COOKIE['AppVersion']) || $_COOKIE['AppVersion'] != $AppVersion) {
    setcookie('AppVersion', $AppVersion, time() + 2592000, '/');
    ?>
    <script>
       location.reload(true);
    </script>
    <?php

    exit(0);
}

