<?php

include ('system/func.php');
include ('system/header.php');




 $i4 =300;
 while ($i4 <= 434){
 $i4++;
  echo "<img src='http://mobitva.ru/images_?id=".$i4."' name='images_".$i4.".png'>";
 }
 echo "<br><br><br><br>";

$footval='allimages';
include ('system/foot/foot.php');
?>