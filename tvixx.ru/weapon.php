<?php
require_once ('system/func.php');
require_once ('system/header.php');
$As = $mc->query("SELECT * FROM `users` WHERE `level` = '".$user['level']."'+1 ")->fetch_array(MYSQLI_ASSOC);
?><span style="color:blue" id="box"><?= $user['name'];?> против <?= $As['name'];?></span><?php


$footval = "main";
require_once ('system/foot/foot.php');
?>