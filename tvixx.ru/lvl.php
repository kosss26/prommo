<?php
require_once 'system/func.php';
//лвл определяется
$profile = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
$result = $mc->query("SELECT * FROM exp ORDER BY `exp`.`lvl` DESC");
$arrtablopit = array();
if(isset($user['uron'])&& $uron = $user['uron'] + 1);
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $arrtablopit[] = $row;
}
for ($i = 0; $i < count($arrtablopit); $i++) {
    If ($profile['exp'] >= $arrtablopit[$i]['exp'] && $profile['level'] < $arrtablopit[$i]['lvl']) {
        $mc->query("UPDATE `users` SET `level` = '" . $arrtablopit[$i]['lvl'] . "' ,`uron` = '".$uron."' WHERE `users`.`id` = '" . $user['id'] . "'");
?>
        <table class="table_block2" cellspacing="0" cellpadding="0">
<tr><td class="block01"></td><td class="block02"></td><td class="block03"></td></tr>
<tr>
<td class="block04"></td>
<left><td class="block05"><?php
print " <center><b>Поздравляем</b></center><br> вы достигли - " . $arrtablopit[$i]['lvl'] . " -Уровня ";
?>
</td></left>
<td class="block06"></td>
</tr>
<tr><td class="block07"></td><td class="block08"></td><td class="block09"></td></tr>
</table>
     <center><div class="button_alt_01" style="z-index: 1000003;width:200px">Ок</div></center>
    <script type='text/javascript'>
        $(document).ready(function () {

            Start();
            function Start() {
                $('#myfond_gris').fadeIn(300);
                var iddiv = 'box_1';
                $('#' + iddiv).fadeIn(300);
                $('#myfond_gris').attr('opendiv', iddiv);
                return false;
            }
            ;

            $('#myfond_gris, .button_alt_01').click(function () {
                var iddiv = $('#myfond_gris').attr('opendiv');
                $('#myfond_gris').fadeOut(300);
                $('#' + iddiv).fadeOut(300);
                $('.fon_opacity').fadeOut(300);
            });
        });
    </script>
<?php

        if ($arrtablopit[$i]['lvl'] > 99) {
            $chatmsg = "<font color=\\'#0033cc\\'>" . $profile['name'] . " достиг " . $arrtablopit[$i]['lvl'] . " уровня!</font>";
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Лвл ап','','0', '" . $chatmsg . "','','','' )");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Лвл ап','','1', '" . $chatmsg . "','','','' )");
        }
        ?><script>showContent("/main.php?msg=" + encodeURIComponent("<?php echo $msg; ?>"));</script><?php
        break;
    }
}
?>