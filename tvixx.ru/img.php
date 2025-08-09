<?php 
$dir = "http://mobitva.ru/imgs_/176/heroes/"; 
$files = scandir($dir); 
foreach ($files as $file): 
 echo '"http://mobitva.ru/imgs_/176/heroes/'.$file .'.png",<br>'; 
endforeach; 
?>
