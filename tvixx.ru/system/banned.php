<?php

require_once ('dbc.php');
//получаем
if (!empty($_POST["nick"]) && !empty($_POST["pass"]) &&
        !empty($_POST["user_2_id"]) && !empty($_POST["msgid"]) &&
        !empty($_POST["time"]) && !empty($_POST["msg"]) && isset($_POST["chat"])) {
    $nick = $mc->real_escape_string($_POST['nick']);
    $pass = $mc->real_escape_string($_POST['pass']);
    $user_2_id = (int) $_POST['user_2_id'];
    $msgid = (int) $_POST['msgid'];
    $chat = (int) $_POST['chat'];
    $time = $mc->real_escape_string($_POST['time']);
    $msg = htmlspecialchars(preg_replace('/[^\S\r\n]+/', ' ', $_POST['msg']), ENT_QUOTES);
    $ban_Time = "0";
    //получаем параметры и определяем доступ
    $result = $mc->query("SELECT * FROM `users` WHERE `login` = '" . $nick . "' AND `password` = '" . $pass . "' ORDER BY `id` DESC LIMIT 1");
    if ($result->num_rows && $chat == 0 || $result->num_rows && $chat == 1) {
        $user = $result->fetch_array(MYSQLI_ASSOC);
        $user_1_id = (int) $user['id'];
        $access = (int) $user['access'];
        $name_1 = $user['name'];
        $time2 = time();
        $banned = 0;
        //получаем чатбан где есть юзверь и проверяем бан
        $result11 = $mc->query("SELECT * FROM `chatban` WHERE `user` = '$user_1_id' AND `time`>'$time2' ORDER BY `id` DESC LIMIT 1");
        if ($result11->num_rows) {
            $userbanchat = $result11->fetch_array(MYSQLI_ASSOC);
            $banned = 1;
        }
        //проверка прав доступа если мд пытается под баном записать то нах пшол . админ проходите пжлст
        if ($access > 0 && $banned == 0 || $access > 1) {
            //user2
            $result2 = $mc->query("SELECT * FROM `users` WHERE `id` = '$user_2_id' ORDER BY `id` DESC LIMIT 1");
            if ($result2->num_rows) {
                $user_2 = $result2->fetch_array(MYSQLI_ASSOC);
                $user_2_name = $user_2['name'];
                $user_2_access = $user_2['access'];
                //разрешим банить на вечку только низших по правам
                if ($user_2_access > $access && $time > 9 && $time != 11) {
                    $time = 9;
                }
                //ну и тут перебьм все в секунды
                switch ($time) {
                    //Предупреждение
                    case 1: $msgban = 'Предупреждение';
                        $ban_Time = 0;
                        break; //1 минута
                    case 2: $msgban = '10 минут';
                        $ban_Time = 60 * 10;
                        break; //10 минут
                    case 3: $msgban = '30 минут';
                        $ban_Time = 60 * 30;
                        break; //30 минут
                    case 4: $msgban = '45 минут';
                        $ban_Time = 60 * 45;
                        break; //45 минут
                    case 5: $msgban = '1 час';
                        $ban_Time = 60 * 60;
                        break; //1 час
                    case 6: $msgban = '2 часа';
                        $ban_Time = 60 * 60 * 2;
                        break; //2 часа
                    case 7: $msgban = '8 часов';
                        $ban_Time = 60 * 60 * 8;
                        break; //8 часов
                    case 8: $msgban = '24 часа';
                        $ban_Time = 60 * 60 * 24;
                        break; //1 сутки
                    case 9: $msgban = '48 часов';
                        $ban_Time = 60 * 60 * 48;
                        break; //2 сутоки
                    case 10: $msgban = 'вечно';
                        $ban_Time = 60 * 60 * 24 * 36500;
                        break; //100 лет
                    case 11: $msgban = 'снят';
                        $ban_Time = 0;
                        break; //снят
                }
                $msghow = $msgban;
                //добавим unix так как обратно через date(формат,уникс) переведем
                $ban_Time += time();
                //проверим если запись есть то обновим иначе создадим
                $result3 = $mc->query("SELECT * FROM `chatban` WHERE `user` = '" . $user_2_id . "' ORDER BY `id` DESC LIMIT 1");
                if ($result3->num_rows) {
                    $mc->query("UPDATE `chatban` SET `how`='" . $msghow . "',`msgid`='$msgid',`time`='" . $ban_Time . "',`msg`='" . $msg . "',`user2`='$user_1_id' WHERE `chatban`.`user` = '$user_2_id';");
                } else {
                    $mc->query("INSERT INTO`chatban`("
                            . "`id`,"
                            . "`user`,"
                            . "`username`,"
                            . "`msgid`,"
                            . "`time`,"
                            . "`msg`,"
                            . "`user2`,"
                            . "`user2name`,"
                            . "`how`"
                            . ") VALUES ("
                            . "NULL,"
                            . "'$user_2_id',"
                            . "'" . $user_2_name . "',"
                            . "'$msgid',"
                            . "'" . $ban_Time . "',"
                            . "'" . $msg . "',"
                            . "'$user_1_id',"
                            . "'" . $name_1 . "',"
                            . "'" . $msghow . "'"
                            . ")");
                }
                //варианты блоков
                switch ($time) {
    case 1:
        $date2 = date("H:i");
        $msgban = '<span style="color: #ff6b6b; font-weight: bold;"><i class="fa fa-exclamation-triangle"></i> ' . $user_2_name  . ' получил(а) предупреждение от ' . $name_1 . '</span>';
        break;
    case 11:
        $date2 = date("H:i");
        $msgban = '<span style="color: #4cd137; font-weight: bold;"><i class="fa fa-unlock"></i> ' . $name_1 . ' снял бан с ' . $user_2_name . '</span>';
        break;
    default:
        if ($time > 1 && $time < 11) {
            $date2 = date("H:i");
            $msgban = '<span style="color: #e74c3c; font-weight: bold;"><i class="fa fa-ban"></i> ' . $name_1 . ' забанил(а) ' . $user_2_name . ' на ' . $msghow . '</span><br><a href=\'javascript:void(0);\' onclick=\'showContent("/knock.php?id_msg=' . $msgid . '")\'><ins>Обжаловать</ins></a>';
        }
        break;
}

                //в какой чат записать
                $date = date("Y-m-d H:i:s");
                //0
                if ($chat == 0) {
                    $mc->query("INSERT INTO`chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`,`unix_time`) VALUES (NULL,'Бан','','0','" . $msgban . "','" . $msg . "','" . $date . "','" . time() . "')");
                } else if ($chat == 1) {
                    $mc->query("INSERT INTO`chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`,`unix_time`) VALUES (NULL,'Бан','','1','" . $msgban . "','" . $msg . "','" . $date . "','" . time() . "')");
                }
            } else {
                echo "ошибка доступа 4 обратитесь к администратору";
                exit(0);
            }
        } else {
            echo "ошибка доступа 3 обратитесь к администратору";
            exit(0);
        }
    } else {
        echo "ошибка доступа 2 обратитесь к администратору";
        exit(0);
    }
} else {
    echo "ошибка доступа 1 обратитесь к администратору";
    exit(0);
}
