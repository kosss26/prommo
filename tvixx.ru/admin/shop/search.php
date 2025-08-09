<?php

require_once '../../system/connect.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if (isset($_GET['etext']) && !empty($_GET['etext'])) {
    $etext = addslashes($_GET['etext']);
    if ($mc->query("SELECT `name`,`level`,`id` FROM `shop` WHERE `name` LIKE '%$etext%'")->num_rows > 0) {
        $result = $mc->query("SELECT `name`,`level`,`id` FROM `shop` WHERE `name` LIKE '%$etext%'")->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result);
    } else {
        echo json_encode(array());
    }
}