<?php
$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
$max=5;
$size=StrLen($chars)-1;
$pas=null;
while($max--)
$pas.=$chars[rand(0,$size)];
?>