<?php
require_once ('system/func.php');
require_once ('system/header.php');
// Закроем от неавторизированых
auth();

if (!isset($_GET['sort'])) {
    $_GET['sort'] = 0;
}
if (!isset($_GET['page'])) {
    $_GET['page'] = 0;
}
//запись сообщения type 0 исходящие 1 входящие . прочтено 1 , 0 нет
if (isset($_GET['write']) && $_GET['write'] != '' && isset($_GET['id_2']) && $_GET['id_2'] > 0) {
    $_GET['write'] = urlencode(htmlspecialchars($_GET['write']));

    $date = date("d/m/Y H:i");
    //запишем себе с типом исходящии
    $mc->query("INSERT INTO `mail`("
            . " `id_1`,"
            . " `id_2`,"
            . " `type`,"
            . " `message`,"
            . " `date`,"
            . " `reading`,"
            . " `lastMsgTimeUnix`"
            . ") VALUES ("
            . "'" . $user['id'] . "',"
            . "'" . $_GET['id_2'] . "',"
            . "'0',"
            . "'" . $_GET['write'] . "',"
            . "'$date',"
            . "'0',"
            . "'" . time() . "'"
            . ")");
    //запишем ему с типом входящи
    $mc->query("INSERT INTO `mail`("
            . " `id_1`,"
            . " `id_2`,"
            . " `type`,"
            . " `message`,"
            . " `date`,"
            . " `reading`,"
            . " `lastMsgTimeUnix`"
            . ") VALUES ("
            . "'" . $_GET['id_2'] . "',"
            . "'" . $user['id'] . "',"
            . "'1',"
            . "'" . $_GET['write'] . "',"
            . "'$date',"
            . "'0',"
            . "'" . time() . "'"
            . ")");
    //запишем ему оповещение
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $_GET['id_2'] . "','Новое сообщение!','" . time() . "','mail')");
}
if(isset($_GET['setOption'])){
	echo "errrror444504";
}
//вывод списка контактов
if (!isset($_GET['id_2']) && isset($_GET['sort'])) {
	$contentgen = "sort";
    $arrMsgAll = [];
    //сообщения
    $arrMsgRes = $mc->query("SELECT * FROM `mail` WHERE `id_2` = '" . $user['id'] . "' GROUP BY `id_1`,`lastMsgTimeUnix` ORDER BY`reading`,`lastMsgTimeUnix` DESC");
   
    $arrMsg = [];
    if ($arrMsgRes->num_rows > 0) {
        $arrMsg = $arrMsgRes->fetch_all(MYSQLI_ASSOC);
        for ($i = 0; $i < count($arrMsg); $i++) {
            if (!isset($arrMsgAll["" + $arrMsg[$i]['id_1']])) {
                $tmpThisAll = $mc->query("SELECT * FROM `mail` WHERE `id_1`='" . $arrMsg[$i]['id_1'] . "' && `id_2` = '" . $arrMsg[$i]['id_2'] . "'")->num_rows;
                $tmpThisNotRead = $mc->query("SELECT * FROM `mail` WHERE `id_1`='" . $arrMsg[$i]['id_1'] . "' && `id_2` = '" . $arrMsg[$i]['id_2'] . "' && `reading` = '0' ")->num_rows;
                $user2Res = $mc->query("SELECT `name`,`online` FROM `users` WHERE `id`='" . $arrMsg[$i]['id_1'] . "'");
                $drugs = $mc->query("SELECT * FROM `friends` WHERE `id_user2` = '" . $arrMsg[$i]['id_2'] . "' && `id_user` = '" . $arrMsg[$i]['id_1'] . "' || `id_user2` = '" . $arrMsg[$i]['id_1'] . "' && `id_user` = '" . $arrMsg[$i]['id_2'] . "'")->num_rows;
                if ($user2Res->num_rows > 0) {
                    $user2 = $user2Res->fetch_array(MYSQLI_ASSOC);
                    //ключ айди  = ["айди","имя","онлайн","колл всех смс","непрочитанных колл","друг>0 или нет=0"]
                    $arrMsgAll["" + $arrMsg[$i]['id_1']] = [$arrMsg[$i]['id_1'], $user2['name'], $user2['online'], $tmpThisAll, $tmpThisNotRead, $drugs];
                }
            }
        }
    }
    
    ?>
    <table style="font-size: 16px;padding-left: 2px;padding-right: 2px;width: 100%;margin: auto;text-align: center;">
        <tr>
            <td id="btn_5" class="shopminiblock sort1 allminia" style="width: 30%;">Онлайн</td>
            <td id="btn_6" class="shopminiblock sort2 allminia" style="width: 30%;">Все</td>
            <td id="btn_7" class="shopminiblock sort3 allminia" style="width: 30%;">Друзья</td>
            <td id="btn_8" class="shopminiblock sort3 allminia" style="width: 30%;">Опции</td>
        </tr>
    </table>

    <script>
        $('#btn_5').click(function () {
            //	$('ofline').remove();
            $('ofline').hide();
        });
        $('#btn_6').click(function () {
            //$('ofline').remove();
            $('ofline').show();
            $('online').show();
        });
        $('#btn_8').click(function () {
            $('online').hide();
            $('ofline').hide();
            showContent('/mail.php?setOption');
        });
        $('#btn_7').click(function () {
            $('online').each(function () {
                $('divs').each(function () {
                    $('online').attr("id") == $('divs').attr("ids") ? $('online').hide() : $('ofline').hide();
                });
            });
        });

    </script>
    
    <div class="clanturblock" onclick="showContent('new.php')">
        Новости
    </div>
    <?php
    $friends = $mc->query("SELECT * FROM `friends` WHERE (`id_user` = '" . $user['id'] . "' OR `id_user2`= '" . $user['id'] . "') AND `red`=0");
    while ($friendsAll = $friends->fetch_array(MYSQLI_ASSOC)) {
        if ($friendsAll['id_user'] != $user['id']) {
            $friendsName = $mc->query("SELECT `name`,`id` FROM `users` WHERE `id` = '" . $friendsAll['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
            $friendsId = $friendsAll['id_user'];
        }
        if ($friendsAll['id_user2'] != $user['id']) {
            $friendsName = $mc->query("SELECT `name`,`id` FROM `users` WHERE `id` = '" . $friendsAll['id_user2'] . "'")->fetch_array(MYSQLI_ASSOC);
            $friendsId = $friendsAll['id_user2'];
        }
        ?>
        <divs style="display:none" id="23" idu="<?= $friendsName['id']; ?>"></divs>
        <?php
    }
    //["айди","имя","онлайн","колл всех смс","непрочитанных колл","друг>0 или нет=0"]
    foreach ($arrMsgAll as $value) {
        if ($value[2] > time() - 60) {
            ?>
            <online id="<?= $value[0]; ?>">
                <div class="clanturblock" id="onn" style="color:green" onclick="showContent('mail.php?id_2=<?= $value[0]; ?>')">
                    <?= $value[1] . "(" . $value[3] . ")"; ?><?= $value[4] > 0 ? "<font style='color:#107010'> + " . $value[4] . "</font>" : ""; ?>
                </div>
            </online>
        <?php } else { ?>
            <ofline id="<?= $value[0]; ?>">
                <div class="clanturblock" id="noo" onclick="showContent('mail.php?id_2=<?= $value[0]; ?>')">
                    <?= $value[1] . "(" . $value[3] . ")"; ?><?= $value[4] > 0 ? "<font style='color:#107010'> + " . $value[4] . "</font>" : ""; ?>
                </div>
            </ofline>
            <?php
            
        }
    }
    $footval = 'mailtomain';
}
//вывод сообщений
if (isset($_GET['id_2'])) {
    //просмотрим ему его сообщения чтоб он знал что я заходил в переписку
    $mc->query("UPDATE `mail` SET `reading`='1'  WHERE `id_1`='" . $_GET['id_2'] . "' && `id_2`='" . $user['id'] . "'  ");
    //получаем второго юзера
    $user2Res = $mc->query("SELECT `name`,`mail_op` FROM `users` WHERE `id`='" . $_GET['id_2'] . "'");
    if ($user2Res->num_rows > 0) {
        $user2 = $user2Res->fetch_array(MYSQLI_ASSOC);
        //получаем переписку
        $arrMsgRes = $mc->query("SELECT * FROM `mail` WHERE `id_1`='" . $user['id'] . "' && `id_2` = '" . $_GET['id_2'] . "' ORDER BY `id` DESC LIMIT 0,10");
        $arrMsg = [];
        if ($arrMsgRes->num_rows > 0) {
            $arrMsg = $arrMsgRes->fetch_all(MYSQLI_ASSOC);
        }
        ?>
        <table style="width:98%;margin: auto;">
            <tr>
                <td style="text-align: center;width:100%;">
                    Диалог с 
                    <a onclick="showContent('/profile/<?= $_GET['id_2']; ?>')"><?= $user2['name']; ?></a>
                </td>
            </tr>
        </table>
        <?php $op = explode("/",$user2['mail_op']);?>
        <?php if($op[1] == '0'){?>
        <form id='form'>
            <table style="width:98%;margin: auto;">
                <tr>
                    <td style="text-align: center;width:100%;">
                        <input  type='number' name='id_2' value='<?= $_GET['id_2']; ?>' hidden>
                        <input  type='text' class='input_real chat_input' name='write'  value=''  style='width:90%;'>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;width:100%;">
                        <input  class='button_alt_01 butt01' style='width:80%;' type='button' value='Отправить' >
                    </td>
                </tr>
            </table>
        </form>
        <?php }else{?>
        <center><p style="border: 1px solid red;">Отправка заблокирована</p></center>
        <?php } ?>
        <script>
            $(".butt01").click(function () {
                showContent("/mail.php?" + $("#form").serialize());
            });
            var msgg = $('p[l="1"]').html();
            $('p[l="1"]').text(msgg);

            var msgg1 = $('p[l="0"]').text();
            $('p[l="0"]').text(msgg1);
        </script>
        <div style="width:98%;margin: auto;">
            <?php for ($i = 0; $i < count($arrMsg); $i++) { ?>
                <?php if ($arrMsg[$i]['type'] == 0) {
                    ?>
                    <font color='#336600'>Вы ( <?= $arrMsg[$i]['date']; ?> ):</font><br>
                    <?= $arrMsg[$i]['reading'] == 0 ? "......" : ""; ?><img height='15' width='15' src='/img/icon/GOL_app_mess_out.png'>
                    <font color='#336600'><b l=0" style="word-break: break-all;"><?= urldecode($arrMsg[$i]['message']); ?></b></font><br>
                <?php } else if ($arrMsg[$i]['type'] == 1) { ?>
                    <font color='#0033CC'><?= $user2['name']; ?> (<?= $arrMsg[$i]['date']; ?>):</font><br>
                    <img height='15' width='15' src='/img/icon/GOL_app_mess_in.png'>
                    <font color='#0033CC'><b l="1" style="word-break: break-all;"><?= urldecode($arrMsg[$i]['message']); ?></b></font><br>
                <?php } ?>
            <?php } ?>
        </div>
        <?php
    } else {
        ?>
        <center>Переписка не найдена</center>
        <?php
    }
    $footval = 'tomail';
}

require_once 'system/foot/foot.php';
