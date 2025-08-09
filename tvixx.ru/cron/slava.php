<?php
require_once 'bd.php';

$slava = [
"500/1391",350,250,150,75,25,25,25,25,25
];
$textShop = "Вы признаны  лучшим турнирным бойцом дня! Вы признаны Звездой Турниров! (Вы получили предмет Звезда Турнира, узнать его показатели Вы можете в разделе снаряжение Для Заданий";
$textSlava = "За успехи во вчерашних турнирах Вы получили %50 славы!";

$arrall = $mc->query("SELECT * FROM `users` WHERE `tur_reit`>'0' ORDER BY `tur_reit` DESC LIMIT 150")->fetch_all(MYSQLI_ASSOC);
$shopall = explode("/",$slava[0]);

$shop = $mc->query("SELECT * FROM `shop` WHERE `id` = '".$shopall[1]."'")->fetch_array(MYSQLI_ASSOC);

for($i = 0; $i < count($arrall); $i++){
	if($i >= count($slava)){
		$slava[$i] = 25;
	}
	$textSlava2 = str_replace("%50",$shopall[0],$textSlava);
	$textSlava3 = str_replace("%50",$slava[$i],$textSlava);
	if($i == 0){
      //  echo "<span style='border: 2px solid black'>".$arrall[$i]['name']." получил ".$shopall[0]." и ".$shop['name']."</span><br>";
        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('".$arrall[$i]['id']."','".$textSlava2."','".time()."','tur')");
        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('".$arrall[$i]['id']."','".$textShop."','".time()."','tur')");
        
       if ($shop['time_s'] > 0) {
            $time_the_lapse = $shop['time_s'] + time();
        } else {
            $time_the_lapse = 0;
        }
        
    $mc->query("INSERT INTO `userbag`("
                        . "`id_user`,"
                        . " `id_shop`,"
                        . " `id_punct`,"
                        . " `dress`,"
                        . " `iznos`,"
                        . " `time_end`,"
                        . " `id_quests`,"
                        . " `koll`,"
                        . " `max_hc`,"
                        . " `stil`,"
                        . " `BattleFlag`"
                        . ") VALUES ("
                        . "'" . $arrall[$i]['id'] . "',"
                        . "'" . $shop['id'] . "',"
                        . "'" . $shop['id_punct'] . "',"
                        . "'0',"
                        . "'" . $shop['iznos'] . "',"
                        . "'$time_the_lapse',"
                        . "'" . $shop['id_quests'] . "',"
                        . "'" . $shop['koll'] . "',"
                        . "'" . $shop['max_hc'] . "',"
                        . "'" . $shop['stil'] . "',"
                        . "'" . $shop['BattleFlag'] . "'"
                        . ")");
                        
             if ($shop['chatSend']) {
            $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $arrall[$i]['id'] . "')\"><font color='#0033cc'>" . $arrall[$i]['name'] . "</font></a><font color='#0033cc'> получил </font><font color='#0033cc'>" . $shop['name'] . "</font>");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','0', '" . $chatmsg . "','','','' )");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','1', '" . $chatmsg . "','','','' )");
            $mc->query("UPDATE `users` SET `slava` = `slava` + '".$shopall[0]."' WHERE `id` = '".$arrall[$i]['id']."'");
        }
    }else{
    	if($i >= count($slava)){
    	$slava[$i] = 25;
    }
    //	echo "<span style='border: 2px solid black'>".$arrall[$i]['name']." получил ".$slava[$i]." </span><br>";
            $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('".$arrall[$i]['id']."','".$textSlava3."','".time()."','tur')");
            $mc->query("UPDATE `users` SET `slava` = `slava` + '".$slava[$i]."' WHERE `id` = '".$arrall[$i]['id']."'");
    }
}
