<?php

$fd = fopen("account.txt", 'r') or die("не удалось открыть файл");
while(!feof($fd))
{
    $str = htmlentities(fgets($fd));
    echo $str;
}
fclose($fd);

function zero()
{
	$b = "<br>test";
	if(1 > 0){
	return $b;
}
}
echo zero();
?>