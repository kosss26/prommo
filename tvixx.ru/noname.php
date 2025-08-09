<?require_once ('system/func.php'); ?>
<html>
    <head>
        <title>ProMMO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#C8AC70">
        <link rel="shortcut icon" href="/favicon.ico" />
        <meta name="author" content="Kosoy"/>
    </head>
    <body>
<?php


///////////////Само задание

echo'
<style>
.statlocpers{position: absolute;display:inline-block;  background-repeat: no-repeat;background-size: contain;}

.locpers3{background-image: url("/img/qestpers/GOL_app_quest-drunkard.png");height: 214px;width: 107px;top: 80px;left:100px;}/*бомж*/

.btnyescent{ float: center;background-image: url("/img/button/btnyes.png");height: 55px;width: 55px;right:-115px;position: relative;display:inline-block;  background-repeat: no-repeat;background-size: contain;}

</style>
';
$location = $mc->query("SELECT * FROM `location` WHERE `id`='".$user['location']."'")->fetch_array(MYSQLI_ASSOC);

echo '
<div class="top0_1"></div>
<div class="ramka_dvig">
    <div class="location'.$location['IdImage'].'">
        <div class="location">

                <center>
                   <div class="statlocpers locpers3">
				   </div>
                </center>

        </div>
    </div>
</div>
';
/* нельзя так делать .
if($_POST['name'] != ''){
$mc->query("UPDATE `users` SET `name`='".$_POST['name']."' WHERE `id`='".$user['id']."'"));
	?>
    <script>showContent("/main.php");</script>
        <?php
}
 * 
 */
echo '
<table class="table_block2" cellspacing="0" cellpadding="0">
<tr>
<td class="block11"></td>
<td class="block12"></td>
<td class="block13"></td>
</tr>

<tr>
<td class="block21"></td>
<td class="block22">';
echo "<center>Братиш, дело есть. <br> Я тут федералам сливал всю инфу о тебе и упустил одну важную деталь. <br> Дело в том, что ты забыл <b>указать имя своего персонажа</b>.<br>Будь добр, введи его в поле ниже или у тебя будут большие проблемы.</center>";
echo "<br>";
echo '<form action="/noname.php" method="post">
<center><div >Имя:</div></center>
	<center><div><input class="buttonregInput" type="text" name="name" maxlength="50" value="" /></div></center>
               <center><input type="submit" class="button" style="width: 240px" value="Продолжить" /></center>

	</form>';

echo'<td class="block23"></td>
</tr>


<tr>
<td class="block31"></td>
<td class="block32"></td>
<td class="block33"></td>
</tr>
</table> <br>';




?>
</body>
</html>
