<?php
$par1 = "a241414.mysql.mchost.ru";
$par2 = "mobitvabb_a"; //login
$par3 = "h248gFnhsKud"; //pass
$par4 = "mobitvabb_a"; //db

$mc = new mysqli($par1, $par2, $par3, $par4);
$mc->set_charset("utf8mb4");
if ($mc->connect_error) {
    die("Connection failed: " . $mc->connect_error);
}

	$mc->query("INSERT INTO `equest`(`ide`, `namee`, `captione`, `captionStartede`, `captionEndede`, `msgClosee`, `startDenye`, `repeatDenye`, `locationIde`, `blocke`, `blockFinishe`, `endMsge`, `questQivere`, `invisiblee`,`packet`) 
									VALUES ('".$_GET['id']."','".$_GET['name']."','".$_GET['caption']."','".$_GET['captionStarted']."','".$_GET['captionEnded']."','".$_GET['msgClose']."','".$_GET['startDeny']."','".$_GET['repeatDeny']."','".$_GET['locationId']."','".$_GET['block']."','".$_GET['blockFinish']."','".$_GET['endMsg']."','".$_GET['questQiver']."','".$_GET['invisible']."','".$_GET['packetQuest']."')");
	echo "Otpravil";
	

?>