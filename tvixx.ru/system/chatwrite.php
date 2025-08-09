<?php

include 'dbc.php';
if (isset($_POST["nick"]) && isset($_POST["pass"]) && isset($_POST["msg"]) && isset($_POST["chat"])) {
    $nick = $_POST["nick"];
    $pass = $_POST["pass"];
    $msg = htmlspecialchars(preg_replace('/[^\S\r\n]+/', ' ', $_POST['msg']), ENT_QUOTES);
    $is_bot = isset($_POST["is_bot"]) ? $_POST["is_bot"] : false;
    $bot_name = isset($_POST["bot_name"]) ? $_POST["bot_name"] : '';
    /*    if (isset($_POST['pip']) && $_POST['pip'] == "false") {

      } else {
      $mms = $mc->real_escape_string("<font color='$colorA'>$msg</font>");
      $mc->query("INSERT INTO `chat` (`msg`) VALUES ('" . $mms . "')");
      }
     */
    $chat = (int) $_POST["chat"];
    if ($msg != "" && $msg != " ") {
        $date = date("Y-m-d H:i:s");
        $colortime = "#000000";  // - цвет времени
        $colornick = "#000000";    // - цвет ника
        $colormsg = "#693401";    // - цвет текста
        $bgcolor = "#C8AC70";   // - цвет фона
        $colorA = "#FF1400";
        $colorbot = "#0067FF";
        $colorbattle = "#747039";
        $star = "";
        $sec = 5;       // секунд на 1
        $num = 5;      // - Кол-во сообщений после которого произойдет полная очистка чата
        $size = "<font style='font-size:19px;'>";
        $size2 = "</font>";

        $result = $mc->query("SELECT * FROM `users` WHERE `login` = '$nick' AND `password` = '$pass' ORDER BY `id` DESC LIMIT 1");
        if ($result->num_rows) {
            $user = $result->fetch_array(MYSQLI_ASSOC);
            $uid = (int) $user["id"];
            $access = (int) $user['access'];
            $name_1 = $user['name'];
            //проверка прав доступа
            if ($access == 1 && $chat == 4 ||
                    $access == 1 && $chat == 5 ||
                    $access == 0 && $chat == 3 ||
                    $access == 0 && $chat == 4 ||
                    $access == 0 && $chat == 5 ||
                    $chat != $user['id_clan'] + 5 && $chat > 5 || $chat < 0 || $user['level'] < 1) {
                echo "ошибка доступа обратитесь к администратору";
                exit(0);
            }
            $time = time();
            $result11 = $mc->query("SELECT * FROM `chatban` WHERE `username` = '$name_1' AND `time`>'$time' ORDER BY `id` DESC LIMIT 1");
            if ($result11->num_rows && $chat < 2) {
                $userbanchat = $result11->fetch_array(MYSQLI_ASSOC);
                echo "отправка недоступна до ", date("Y-m-d H:i:s", $userbanchat['time']);
                exit(0);
            }
            $result0 = $mc->query("SELECT * FROM `chat` WHERE `name` = '$name_1' AND `chat_room`='$chat' ORDER BY `id` DESC LIMIT 1");
            if (isset($result0->num_rows)) {
                if ($result0->num_rows) {
                    $lastmsgarr = $result0->fetch_array(MYSQLI_ASSOC);
                    if ($lastmsgarr['msg2'] == $msg) {
                        echo "Повторное сообщение .";
                        exit(0);
                    }
                }
            }
            $lvl = $user['level'];
            if ($user['side'] == 2 || $user['side'] == 3) {
                $icon = "<img height='17' src='/img/icon/icogood.png' width='17' alt=''>";
            } else {
                $icon = "<img height='17' src='/img/icon/icoevil.png' width='17' alt=''>";
            }
            if ($access == 1) {
                $star = "<img height='15' src='/img/icon/star.png' width='15' alt=''>";
            } else if ($access == 2) {
                $star = "<img height='15' src='/img/icon/star2.png' width='15' alt=''>";
            } else if ($access > 2) {
                $star = "<img height='15' src='/img/icon/star3.png' width='15' alt=''>";
            }
            //создадим текст сообщения
            $resultBattleInfo = $mc->query("SELECT * FROM `battle` WHERE `Pname`='" . $user['name'] . "' AND `Plife`>'0' AND `player_activ`='1' AND `end_battle`='0'");
            $flagbattle = 0;
            if ($resultBattleInfo->num_rows) {
                $flagbattle = 1;
                $chattext = $mc->real_escape_string("<font color='$colortime'>" . date("H:i", time()) . "$icon$star<a onclick=\"showContent('/profile/$uid')\"><font style='font-size:17px;' color='$colornick'><u>$name_1</u></font></a> [$lvl] <font style='font-size:15px;' color='$colorbattle'>" . $msg . "</font><br>");
            } else {
                if (isset($_POST['pip']) && $_POST['pip'] == "true") {
                    $chattext = $mc->real_escape_string("<font style='font-size:10px;'  <font style='font-size:1px;' color='red'>" . $msg . "</font><br>");
                } else {
                    $chattext = $mc->real_escape_string("<font color='$colortime'>" . date("H:i", time()) . "$icon$star<a onclick=\"showContent('/profile/$uid')\"><font style='font-size:17px;' color='$colornick'><u>$name_1</u></font></a> [$lvl] <font style='font-size:15px;' color='$colormsg'>" . $msg . "</font><br>");
                }
            }
            //прочитаем сообщения игрока
            $result1 = $mc->query("SELECT * FROM `chat` WHERE `name` = '$name_1' AND `chat_room`='$chat' ORDER BY `id` DESC LIMIT " . $num);
            if (isset($result1->num_rows)) {
                if ($result1->num_rows) {
                    $row = $result1->fetch_all(MYSQLI_ASSOC);
                    $counter = 0;
                    //переберем их время
                    for ($i = 0; $i < count($row); $i++) {
                        if ((int) $row[$i]['unix_time'] > (time() - ($sec * count($row)))) {
                            $counter++;
                        }
                    }
                    //если время сходится то удалим и вернем в чат предупреждение
                    if ($counter == count($row)) {
                        $ids = 0;
                        for ($i = 0; $i < count($row); $i++) {
                            $ids = (int) $row[$i]['id'];
                            $mc->query("DELETE FROM `chat` WHERE `id` = '$ids'  AND `chat_room`='$chat'");
                        }
                        echo "Спам ! Прекратите иначе последует наказание в виде блокировки отправки сообщений .";
                        exit(0);
                    }
                }
            }
            //или запишем в бд
            $mc->query("INSERT INTO`chat`("
                    . "`id`,"
                    . "`name`,"
                    . "`id_user`,"
                    . "`chat_room`,"
                    . "`msg`,"
                    . "`msg2`,"
                    . "`time`,"
                    . "`unix_time`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'$name_1',"
                    . "'$uid',"
                    . "'$chat',"
                    . "'$chattext',"
                    . "'$msg',"
                    . "'$date',"
                    . "'" . time() . "'"
                    . ")");
            function chat_bot($bottt){
            	$botN = rand(0,count($bottt) - 1);
                return $bottt[$botN];
            }
            if (preg_match('/Воевода/iu',$msg)) {
                $bot = "Если вам нужна помощь войдите в раздел помощь";
                $chattext2 = $mc->real_escape_string("<font color='$colortime'>" . date("H:i") . "$icon$star<a onclick=\"showContent('/profile/0')\"><font color='$colornick'>Воевода [1] </font></a></font> <font color='$colorbot'>$bot </font>");
                $mc->query("INSERT INTO `chat` (`name`,`id_user`,`msg`,`msg2`,`time`) VALUES ('Воевода','0','$chattext2','$bot','$date')");
            }else
            if (preg_match('/Воевода,привет/iu',$msg)) {
                $bot = array("Привет" . $name_1 . " как тебе игра?", "Здравствуй дорогой игрок", "Привет" . $name_1 . " я могу чем-то помочь?", "Доброго времини суток " . $name_1 . "");
                $botA = chat_bot($bot);

                $chattext2 = $mc->real_escape_string("<font color='$colortime'>" . date("H:i") . "$icon$star<a onclick=\"showContent('/profile/0')\"><font color='$colornick'>Воевода [1] </font></a></font> <font color='$colorbot'>$botA</font>");
                $mc->query("INSERT INTO `chat` (`name`,`id_user`,`msg`,`msg2`,`time`) VALUES ('Воевода','0','$chattext2','" .$botA. "','$date')");
            }else
            if (preg_match('/Воевода,как дела?/iu',$msg)) {
                $bot = array("Я не человек я бот слежу за чатом", "Отлично все");
                $botA = chat_bot($bot);
                $chattext2 = $mc->real_escape_string("<font color='$colortime'>" . date("H:i") . "$icon$star<a onclick=\"showContent('/profile/0')\"><font color='$colornick'>Воевода [1] </font></a></font> <font color='$colorbot'>$botA </font>");
                $mc->query("INSERT INTO `chat` (`name`,`id_user`,`msg`,`msg2`,`time`) VALUES ('Воевода','0','$chattext2','$botA','$date')");
            } else if (preg_match('/Залим,привет/iu',$msg)) {
                $bot = array("Я не человек я бот я брат воеводы 😂😂😂😂");
                $botA = chat_bot($bot);
                $chattext2 = $mc->real_escape_string("<font color='$colortime'>" . date("H:i") . "$icon$star<a onclick=\"showContent('/profile/388')\"><font color='$colornick'>Хочу в 095 [14] </font></a></font> <font color='$colorbot'>$bot[$botA] </font>");
                $mc->query("INSERT INTO `chat` (`name`,`id_user`,`msg`,`msg2`,`time`) VALUES ('Хочу в 095','388','$chattext2','$botA','$date')");
            }

            // Специальная обработка для бота
            if ($is_bot && $bot_name === 'Liya') {
                $chattext = $mc->real_escape_string("<font color='$colortime'>" . date("H:i", time()) . 
                    "$icon<a onclick=\"showContent('/profile/3111')\"><font style='font-size:17px;' color='$colornick'><u>$bot_name</u></font></a> [99] " .
                    "<font style='font-size:15px;' color='$colormsg'>" . $msg . "</font><br>");
            }

            echo "0";
            exit(0);
        }
        echo "0";
        exit(0);
    } else {
        echo "Сообщение введено не корректно !";
        exit(0);
    }
}