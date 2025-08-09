<?php
$par1 = "localhost";
$par2 = "u2992855_kosoy"; //login
$par3 = "01061981AAa."; //pass
$par4 = "u2992855_game"; //db
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$mc = new mysqli($par1, $par2, $par3) or die('Сайт не доступен');
$mc->set_charset("utf8mb4");
$mc->select_db($par4) or die('Указаная таблица не найдена');

?>