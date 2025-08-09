<?php

//в начале файла 
session_start();
if ((isset($_SESSION)) && ( $_SESSION["end"] == false)) {
    echo "error";
    exit(0);
}
$_SESSION["end"] = false;

echo "good";
//тут ваш код, который долго выполняется
//в конце файла 
$_SESSION["end"] = true;

