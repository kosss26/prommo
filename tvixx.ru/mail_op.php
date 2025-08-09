<?php
require_once ('system/func.php');
require_once ('system/header.php');
//$mc->query("UPDATE `users` SET `mail_op` = '0/1/0' WHERE `id` = '464'");
$mail_op = explode("/", $user['mail_op']);
if(isset($_GET['message']) && isset($_GET['punct'])){
	if($_GET['punct'] > 3){
		message("введите другой пункт");
	}else{
		$mail_op[1] = intval($_GET['punct']);
		$mail = implode('/',$mail_op);
		if($mc->query("UPDATE `users` SET `mail_op` = '".$mail."' WHERE `id` = '".$user['id']."'")){
			message("успешно");
			//var_dump($mail_op);
		}
	}
}
?><center>--опции--</center>
<table class="table_block2">
            <tr>
                <td class="block101" style="width: 2%"></td>
                <td class="block102" style="width: 96%"></td>
                <td class="block103" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block104" style="width: 2%"></td>
                <td class="block105" style="width: 96%;text-align: center;">
            	<center>
                	Помогать в охоте могут<br>
        <?php if($mail_op[0] == 0){?>
                	<a><b>Все</b></a><br>
                    <a onclick="showContent()">Друзья</a>
        <?php   }else{?>
        	        <a onclick="showContent()">Все</a><br>
        	        <a><b>Друзья</b></a>
          <?php } ?><br><br>
          	Приглашать в друзья могут<br>
         <?php if($mail_op[2] == 0){?>
                	<a><b>Все</b></a><br>
                    <a onclick="showContent()">Друзья друзей</a><br>
                    <a onclick="showContent()">Никто</a>
        <?php   }else if($mail_op[2] == 1){?>
        	        <a onclick="showContent()">Все</a><br>
        	        <a><b>Друзья друзей</b></a><br>
                    <a onclick="showContent()">Никто</a>
          <?php }else if($mail_op[2] == 2){?>
                	<a onclick="showContent()">Все</a><br>
        	        <a onclick="showContent()">Друзья друзей</a><br>
                    <a><b>Никто</b></a>
          <?php } ?><br><br>
          	     Отправлять сообщения могут<br>
          <?php if($mail_op[1] == 0){?>
                	<a><b>Все</b></a><br>
                    <a onclick="showContent('/mail_op.php?message&punct=3')">Друзья друзей</a><br>
                    <a onclick="showContent('/mail_op.php?message&punct=2')">Друзья</a><br>
                    <a onclick="showContent('/mail_op.php?message&punct=1')">Никто</a>
        <?php   }else if($mail_op[1] == 1){?>
        	        <a onclick="showContent('/mail_op.php?message&punct=0')">Все</a><br>
        	        <a onclick="showContent('/mail_op.php?message&punct=3')">Друзья друзей</a><br>
                    <a onclick="showContent('/mail_op.php?message&punct=2')">Друзья</a><br>
                    <a><b>Никто</b></a>
          <?php }else if($mail_op[1] == 2){?>
                	<a onclick="showContent('/mail_op.php?message&punct=0')"Все</a><br>
        	        <a><b>Друзья друзей</b></a><br>
        	        <a onclick="showContent('/mail_op.php?message&punct=2')">Друзья</a><br>
                    <a onclick="showContent('/mail_op.php?message&punct=1')">Никто</a>
          <?php }else if($mail_op[1] == 3){ ?>
          	      <a onclick="showContent('/mail_op.php?message&punct=0')">Все</a><br>
        	        <a onclick="showContent('/mail_op.php?message&punct=3')">Друзья друзей</a><br>
        	        <a><b>Друзья</b></a><br>
                    <a onclick="showContent('/mail_op.php?message&punct=1')">Никто</a>
          <?php } ?><br><br>
                </center>
               <td class="block106" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table><?php
$footval = 'tomail';
require_once 'system/foot/foot.php';