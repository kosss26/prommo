<?php
require_once ('system/func.php');
require_once ('system/dbc.php');
if (isset($_GET['text']) && $text = $_GET['text']) {
    if (isset($_GET['text']) && isset($user['name'])) {
        $mc->query("INSERT INTO `ticket`("
                . "`id`,"
                . "`text`,"
                . "`user`,"
                . "`userid`"
                . ") VALUES ("
                . "'NULL',"
                . "'" . $_GET['text'] . "',"
                . "'" . $user['name'] . "',"
                . "'" . $user['id'] . "'"
                . ")");
        ?><script>/*nextshowcontemt*/showContent("/chat.php?msg=" + encodeURI("Жалоба отправлена. Спасибо!"));</script><?php
        exit(0);
    }
}



$footval = "knock";
require_once ('system/foot/foot.php');
?>
<html>
    <head>
        <title>Mobitva v1.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
        <meta name="theme-color" content="#C8AC70">
        <link rel="shortcut icon" href="favicon.ico" />
        <meta name="author" content="Kalashnikov"/>
    </head>
    <body>
        <?php
        if (isset($_GET['id_msg'])) {
            $id_msg = $_GET['id_msg'];
            $result = $mc->query("SELECT * FROM `chatban` WHERE `msgid` = '$id_msg'");
            if ($result->num_rows > 0) {
                $res = $result->fetch_array(MYSQLI_ASSOC);
            } else {
                ?><script>/*nextshowcontemt*/showContent("/chat.php?msg=" + encodeURI("Уже поздно!"));</script><?php
                exit(0);
            }
        } else {
            ?><script>/*nextshowcontemt*/showContent("/chat.php?msg=" + encodeURI("Ошибка!"));</script><?php
            exit(0);
        }
        ?>
    <center>
        <font class="font_1">-Жалоба на модератора-</font>
        <table class="table_block2" cellspacing="0" cellpadding="0">
            <tr>
                <td class="block101"></td>
                <td class="block102"></td>
                <td class="block103"></td>
            </tr>
            <tr>
                <td class="block104"></td>
                <td class="block105">
                    <br>
            <center>
                <input id='id' type=hidden style='width: 50%' value='<?php echo $id_msg; ?>'>
                <div style='width: 200px' id="id_text">
                    <font class="font_1">
                    Модератор <?php echo $res['user2name'] ?> забанил(а) <?php echo $res['username'] ?>
                    на <?php echo $res['how'] ?> за :
                    </font>
                </div>
                <div style='width: 280px' id="id_text_2">
                    <hr class="hr_01" style="margin: 2;"/>
                    <font class="font_1">
                    <?php echo htmlspecialchars_decode($res['msg']) ?>
                    </font>
                    <hr class="hr_01" style="margin: 2;"/>
                </div>
                <div id="id_text_3">
                    <font class="font_1">
                    Текст жалобы :
                    </font>
                </div>
                <div>
                    <input  type="text" class="input_real chat_input"  id="id_text_4"  value="" maxlength="200" autocomplete="off" style='height: 30px;width: 296px;' placeholder="минимум 20 символов">
                    <input type="hidden" id="id_max_text" value="Модератор <?= $res['user2name'] ?> забанил(а) <?= $res['username'] ?> на <?= $res['how'] ?> за : <br> <?= htmlspecialchars_decode($res['msg']) ?> . <br> Текст жалобы : ">
                </div>

            </center></td>
            <td class="block106"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table>
        <center>
            <div>
                <div class="button_alt_01" onclick="jaba_msg();">
                    Отправить
                </div>
            </div>
        </center>
    </center>
    <div class="jaba_msg" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;display: none;">
        <table style="margin: auto;width: 240px;height: 100%">
            <tr>
                <td style="vertical-align: middle;text-align: center;">
                    <div style="width:100%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                        <div style="margin: 10px;">
                            Внимание!!!<br>
                            Данная жалоба будет доставлена всем Администраторам и Модераторам в их беседы где будет рассмотрена и обсуждена!<br>
                            Предупреждаем!!!<br>
                            Если в тексте данной жалобы будут содержаться маты или оскорбления, то вы рискуете получить блок персонажа даже если бан выдан был не вам! Помните что это жалоба адресуется не конкретному человеку а группе людей .
                        </div>
                        <div class="button_alt_01" onclick="close_jaba_msg(1);" style="margin: auto;margin-bottom: 5px;" >Отправить</div>
                        <div class="button_alt_01" onclick="close_jaba_msg(0);" style="margin: auto;margin-bottom: 5px;">Отмена</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <script>
        MyLib.jaba_nameUser = encodeURIComponent("<?= $user['name'] ?>");
        MyLib.jaba_msg = $('.jaba_msg');
        if (typeof jaba_msg !== 'function') {
            jaba_msg = function () {
                $("mobitva").append(MyLib.jaba_msg);
                $('.jaba_msg:eq(-1)').css({display: "block"});
                $('.jaba_msg:eq(-1)').animate({'opacity': '1'}, 300);
            };
            close_jaba_msg = function (e) {
                $('.jaba_msg:eq(-1)').animate({'opacity': '0'}, 300);
                MyLib.setTimeid[250] = setTimeout(function () {
                    $('.jaba_msg:eq(-1)').css({display: "none"});
                    $('.jaba_msg:eq(-1)').remove();
                }, 300);
                if (e == 1) {
                    MyLib.jaba_jaloba = encodeURIComponent($('#id_max_text').val() + $('#id_text_4').val());
                    if ($('#id_text_4').val().length >= 20) {
                        $.ajax({
                            url: "/vk.com/bot.php?name=" + MyLib.jaba_nameUser + "&jaloba=" + MyLib.jaba_jaloba,
                            success: function () {

                            }
                        });
                        showContent('/knock.php?text=' + $('#id_max_text').val() + $('#id_text_4').val());
                    } else {
                        questmsg('Текст жалобы должен содержать не менее 20 символов');
                    }
                }
            };
        }
    </script>
</body>
</html>

