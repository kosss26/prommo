<?php
require_once 'bd.php';

$daniltest = $mc->query("UPDATE `users` SET `money` = `money` + 53265 WHERE `id` = 6;"); 
                