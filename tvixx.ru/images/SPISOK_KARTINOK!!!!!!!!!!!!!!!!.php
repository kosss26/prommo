<?php
$dir  = "../images";
$files = scandir($dir);
foreach ($files as $file):
    echo '"../images/'.$file .'",<br>';
endforeach;
?>

