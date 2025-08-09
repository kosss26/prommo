<?php

require_once ('system/func.php');
if(isset($_GET['newName'])){
$nik = htmlspecialchars($_GET['newName']);
$countN = $mc->query("SELECT COUNT(*) FROM `users` WHERE `name` = '".$nik."'")->fetch_array(MYSQLI_ASSOC);
$newn = $user['newNames']."";
  if($user['platinum'] >= 100){
  	if($countN['COUNT(*)'] <= 0){
	    if($mc->query("UPDATE `users` SET `name` = '".$nik."',`platinum` = `platinum` - 100 WHERE `id` = '".$user['id']."'")){
		  message("Вы сменил свой ник на <b>{$nik}</b> но потеряли 100<img src='/images/icons/plata.png'>");
		  $newn.= $user['name']."";
		  $mc->query("UPDATE `users` SET `newNames` = '".$newn."' WHERE `id` = '".$user['id']."'");
		  $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>Персонаж " . $user['name'] . "</font></a><font color='#0033cc'> изменил ник на </font><font color='#0033cc'>" . $nik . "</font>");
          $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','0', '" . $chatmsg . " " . date('H:i:s') . "','','','' )");
	}
  }else{
	message("Данные ник уже существует");
  }
  }else{
  	message("Недостаточно средств");
  }
}
if (isset($_GET['setName'])) {
    //форма смены имени
    ?>
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
              <p>Смена ника обойдется вам в 100<img src="/images/icons/plata.png"></p>
            <input style="text-align: center;" class="buttonregInput" id="name" type="text" value="" placeholder="Ник максимум 16 символов" maxlength="16" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
            <div class="button_alt_01" onclick="showContent('/changeParams.php?newName='+ $('#name').val());">Сменить ник</div>
            </center>
            <td class="block106" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table>
        ваши ники:<br>
        <?php
        $nikk = explode("",$user['newNames']);
        for($i = 0; $i < count($nikk); $i++){
          echo "{$nikk[$i]}<br>";
        }
  
    $footval = "changeParamsName";
} else {
    //выводим варианты выбора
    ?>
    <center>
        <br>
        <div class="button_alt_01" onclick="showContent('/changeParams.php?setName=<?= urlencode($user['name'])?>')">Смена ника</div>
    </center>



    <?php

    $footval = "changeParams";
}


require_once ('system/foot/foot.php');
