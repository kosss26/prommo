<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vk.com/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/system/connect.php";

$config = new Config_vk;
$confirmationToken = 'f459d8e3';

$data = json_decode(file_get_contents("php://input"));

function indexaTArr($arr) {
    $arr2 = [];
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $arr2[] = indexaTArr($value);
        } else {
            $arr2[] = $value;
        }
    }
    return $arr2;
}

function MyRand($e) {
    if (rand(0, 100) > 90) {
        return rand(0, $e);
    } else {
        return 0;
    }
}

function MyRand2($e) {
    if (rand(0, 100) > 10) {
        return rand(0, $e);
    } else {
        return 0;
    }
}

function GetLevelThis($exp) {
    $st = 100;
    $exp += $st * 2;
    for ($i = 1000; $i >= 0; $i--) {
        if ($exp >= ($st * $i + $st) * 2) {
            return $i + 1;
        }
    }
}

function send($text, $peer_id) {
    global $config;

    $reg_mes = [
        'message' => $text,
        'access_token' => $config->key,
        'v' => '5.80',
        'random_id' => rand(1111, 9999) . rand(1111, 9999) . rand(1111, 9999) . rand(1111, 9999) . rand(1111, 9999),
        'peer_id' => $peer_id
    ];
    $get = http_build_query($reg_mes);
    file_get_contents("https://api.vk.com/method/messages.send?" . $get);
}

if (isset($_GET['support']) && isset($_GET['name']) && !empty($_GET['support'])) {
    //массив адиинов для увидомления
    $arrr = array(277318898, 100785305, 235508674, 515483259, 83216598, 498446909);
    for ($i = 0; $i < count($arrr); $i++) {
        send("Новый отзыв от {$_GET['name']}\n {$_GET['support']}", $arrr[$i]);
    }
}
if (isset($_GET['jaloba']) && isset($_GET['name']) && !empty($_GET['jaloba'])) {
    //массив адиинов для увидомления
    $arrr = array(277318898, 100785305, 235508674, 515483259, 83216598, 498446909, 2000000005);
    for ($i = 0; $i < count($arrr); $i++) {
        send("Жалоба от " . $_GET['name'] . " \n" .$_GET['jaloba'], $arrr[$i]);
    }

}

if (isset($_GET['donat']) && isset($_GET['name_donaters'])) {
    //массив адиинов для увидомления
    $arrr = array(277318898, 100785305, 235508674, 515483259, 83216598, 498446909);
    for ($i = 0; $i < count($arrr); $i++) {
        send("ДОНАТ от {$_GET['name_donaters']} в размере {$_GET['donat']} руб", $arrr[$i]);
    }
}

if (isset($data->type)) {
    switch ($data->type) {
        case 'message_new':
            $peer_id = $data->object->peer_id ?: $data->object->from_id;
            $userID = $data->object->from_id;
//https://api.vk.com/method/users.get?user_ids=277318898&v=5.80&access_token=dc665c8978a8894475ce0af9aaa28903f03800e183a232aeceda7867d07cc2cb52ee114e3e0ae9764196c
            $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids=" . $userID . "&v=5.80&access_token=" . $config->key));
            /*     if ($userInfo->error->error_code == 6) {
              send($userInfo->error->error_msg, $peer_id);
              header("HTTP/1.1 200 OK");
              echo 'ok';
              exit(0);
              }
             * 
             */
            $userName = $userInfo->response[0]->first_name;
            $u = $mc->query("SELECT * FROM `users` ORDER BY `access` DESC LIMIT 70 ")->fetch_all(MYSQLI_ASSOC);
            $alt = ["классно", "ты молодец", "спасибо", "удачи", "давай", "я против", "сколько", "зачем", "хватит", "тише будь", "сам такой", "иногда", "возможно", "может", "нет", "да", "ага", "согласен", "хорошо", "отлично", "понятно", "интересно", "я тоже", "подумаешь", "делов то", "ну бывает", "ну и что", "когда это было", "почему", "где", "что", "кто", "ок", "ладно", "и чё", "ну",];
            $arr = [
                "привет" => [
                    "Привет", "Здорова", "Ку", "Хай", "привет", "здрасте", "хеллёу", "дратуте"
                ],
                "как" => [
                    "сложно сказать", "на самом деле все не так однозначно", "ну допустим", "то есть", "знаешь ты прав", "типа того", "это самое оно", "я бы не так спросил", "в общем-то да", "Как сказать", "о чем ты", "подробнее можно", "ну конечно", "что за вопрос", "хм надо подумать", "а в гугле пробовал смотреть", "надо загуглить", "странный вопрос", "норм", "збс", "отлично", "круто", "хорошо", "замечательно", "великолепно", "это просто шедевр братан"
                ],
                "дела" => [
                    "норм", "збс", "отлично", "круто", "хорошо", "замечательно", "великолепно", "это просто шедевр братан"
                ],
                "бот" => [
                    "ну допустим", "классно", "ты молодец", "спасибо ты крутой", "удачи тебе", "давай удачи", "я против этого", "сколько можно", "зачем так ", "хватит я больше так не могу", "тише будь", "сам такой", "иногда ", "возможно ты прав", "может и так", "нет", "да", "точно", "ага", "согласен", "хорошо", "отлично", "понятно", "интересно", "я тоже", "подумаешь ", "ясно", "делов то", "ну бывает", "ну и что", "когда это было", "почему", "где", "что", "кто", "ок", "ладно", "и чё", "ну", "кто бот то", "бот не бот"
                ],
                "?" => [
                    "сложно сказать", "на самом деле все не так однозначно", "ну допустим", "то есть", "знаешь ты прав", "типа того", "это самое оно", "я бы не так спросил", "в общем-то да", "Как сказать", "о чем ты", "подробнее можно", "ну конечно", "что за вопрос", "хм надо подумать", "а в гугле пробовал смотреть", "надо загуглить", "странный вопрос"
                ],
                "аха" => [
                    "чё ты ржешь ", " оч смешно ", " ахах ", " лол ", " )))", " мда "
                ]
            ];
            $xz = "";
            $text = $data->object->text;
            foreach ($arr as $key => $item) {
                if (preg_match("/" . preg_quote($key, '/') . "/iu", $text)) {
                    $xz .= "  " . $arr[$key][array_rand($item)] . " . ";
                }
            }


            $d = "";
            if ($mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $userID . "'")->fetch_array(MYSQLI_ASSOC)) {
                
            } else {
                if ($mc->query("INSERT INTO `vk.com` (`id_user`,`name`,`bablo`) VALUES ('" . $userID . "','" . $userName . "','1500')")) {
                    $xz .= "Спасибо за регистрацию";
                }
            }
            if (preg_match('/хел/iu', $text)) {
                $userM = [125481214, 126045713, 129343328, 131550865, 131910155, 132132494, 132380175, 132664711, 134665971, 136354323, 136815595, 137578175, 137916455, 138346887, 138930941, 139568088, 139743642, 141687844, 144058774, 145508451, 147018925, 147330804, 147581749, 148235560, 148437814, 151322691, 151406424, 151462000, 152071212, 152387809, 152426211, 152605922, 152864810, 153016003, 153663990, 153942931, 154074419, 154187968, 156586382, 157049026, 158547955, 158610308, 159326522, 159414551, 159425391, 160573605, 160795084, 160884605, 161555403, 162457895, 162477185, 165989972, 166415028, 167179304, 167264517, 167586139, 170214484, 171330440, 172095744, 172154011, 173573998, 173857771, 173928546, 174793836, 176207568, 176857077, 177816422, 178437811, 178907202, 179547385, 180600910, 183097508, 184562119, 184647661, 185191467, 185235168, 186210553, 189888161, 190652726, 190879980, 191607692, 192258785, 192489124, 193777142, 193789849, 194710351, 195109946, 195331787, 195362588, 195609297, 196335122, 196668413, 196916099, 197160598, 197209961, 198502215, 199030361, 200008033, 200361332, 200540332];
                for ($i = 0; $i < count($userM); $i++) {
                    send("Игра вышла!!! \n https://mobitva2.online/ \n внизу есть андроид клиент всём удачи", $userM[$i]);
                }
            }
            if (preg_match('/user/iu', $text)) {
                $users = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $userID . "' ")->fetch_array(MYSQLI_ASSOC);
                if ($users['id_user'] == $userID) {
                    $xz .= "👑профиль \n"
                            . " 😀Ник: {$users['name']} \n"
                            . " 🏅Айди: {$users['id_user']} \n"
                            . " Уровень: " . GetLevelThis($users['exp']) . " \n"
                            . " Слава: {$users['slava']} \n"
                            . " Опыт: {$users['exp']} \n"
                            . " 💵Деньги: {$users['bablo']} \n"
                            . " ❤️ Здоровье: {$users['hp']} \n"
                            . " ⚔️урон: {$users['uron']} \n"
                            . " 🧥бронь: {$users['bron']} \n"
                            . "💧уворот: {$users['lov']} \n"
                            . " победы pvp: {$users['pvp_w']} \n"
                            . " поражения pvp: {$users['pvp_l']} \n"
                            . " Тайный прием: {$users['super']} \n\n"
                            . "Kоманда атаки bat(тут айди удара 1,2,3) например bat(3).\n Для участия в pvp битве укажите команду pvp(тут айди противника) например: pvp(234567).";
                }
            }
            if ($mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $userID . "' ")->num_rows > 0) {
                $users = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $userID . "' ")->fetch_array(MYSQLI_ASSOC);
                if ($users['bablo'] < "0") {
                    $mc->query("UPDATE `vk.com` SET `bablo` = '0' WHERE `id_user` = '" . $userID . "'");
                }
            }
            if (preg_match('/id/iu', $text)) {
                //капа 84303404
                //Евгений 413840055
                // for($i = 0; $i <= 1000; $i++){
                //        	send("Привет {$userName}, я тебе все отдаю,даю🍉🍌🥕🍆🥑🍌🍌🥔🍆🥑🍑🍒🍒  ",84303404);
            }
            // }
            if (preg_match('/ddos(.*?)\((.*?),(.*?)\)/iu', $text, $arr)) {
                //	for($i = 0; $i < $arr[2]; $i++){
                send("ddos", $arr[3]);
                //  }
            }
            if (preg_match('/бой/iu', $text)) {
                $res = $mc->query("SELECT `id_user` FROM `vk.com` WHERE `id_user` != '" . $users['id_user'] . "' ORDER BY RAND() LIMIT 1");
                if ($res->num_rows > 0) {
                    $arrres = $res->fetch_array(MYSQLI_ASSOC);
                    $text = "pvp(" . $arrres['id_user'] . ")";
                } else {
                    $xz .= "Противников нет. \n";
                }
            }

            //бой пвп
            if (preg_match('/pvp(.*?)\((.*?)\)/iu', $text, $arr)) {
                //берем противника по айди
                if ($mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $arr[2] . "'")->num_rows > 0 && $u11 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $arr[2] . "'")->fetch_array(MYSQLI_ASSOC)) {
                    //если бой уже есть
                    if ($mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $users['id_user'] . "'")->num_rows > 0) {
                        $xz .= "Бой  уже создан , команда атаки bat(тут айди удара 1,2,3) например bat(3)\n";
                    } else {
                        //создаю бой (1)
                        $mc->query("INSERT INTO `vk_battle` (`id_user`,`id_user2`,`hp`,`uron`,`bron`,`lov`,`type`,`xod`,`super`) VALUES ('" . $users['id_user'] . "','" . $u11['id_user'] . "','" . $users['hp'] . "','" . $users['uron'] . "','" . $users['bron'] . "','" . $users['lov'] . "','1','1','" . $users['super'] . "')");
                        //бой (2)
                        $mc->query("INSERT INTO `vk_battle` (`id_user`,`id_user2`,`hp`,`uron`,`bron`,`lov`,`type`,`xod`,`super`) VALUES ('" . $u11['id_user'] . "','" . $users['id_user'] . "','" . $u11['hp'] . "','" . $u11['uron'] . "','" . $u11['bron'] . "','" . $u11['lov'] . "','1','0','" . $u11['super'] . "')");
                        $xz .= " {$users['name']}[{$users['hp']}] -VS- {$u11['name']}[{$u11['hp']}] . начали";
                    }
                } else {
                    $xz .= "Противник не найден, укажите точный айди противника из профиля\n";
                    $xz .= "Kоманда атаки bat(тут айди удара 1,2,3) например bat(3). \n";
                }
            }
            if ($text > "0" && $text < "4") {
                if ($mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $users['id_user'] . "'")->num_rows > 0) {
                    $text = "bat({$text})";
                }
            }

            //атака
            if (preg_match('/bat(.*?)\((.*?)\)/iu', $text, $arr)) {
                //если бой уже создан
                if ($battle = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $users['id_user'] . "'")->fetch_array(MYSQLI_ASSOC)) {
                    //я
                    $user1 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $battle['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $user11 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user1['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                    //противник
                    $user2 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $user1['id_user2'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $user22 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user2['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $p1 = ($user1['uron'] + MyRand2($user1['uron'] + GetLevelThis($user11['exp'])) + 1) - $user2['bron'];
                    if ($p1 <= 0) {
                        $p1 = 1;
                    }
                    $p2 = ($user2['uron'] + MyRand2($user2['uron'] + GetLevelThis($user22['exp'])) + 1) - $user1['bron'];
                    if ($p2 <= 0) {
                        $p2 = 1;
                    }
                    if ($arr[2] > "0" && $arr[2] < "4") {
                        $mc->query("UPDATE `vk_battle` SET `su` = CONCAT(`su`,'" . $arr[2] . "') WHERE `id_user` = '" . $user1['id_user'] . "'");
                        //я
                        $user1 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $battle['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                        $user11 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user1['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                        //противник
                        $user2 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $user1['id_user2'] . "'")->fetch_array(MYSQLI_ASSOC);
                        $user22 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user2['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                        $suhero = 0;
                        if ($user1['su'] != "" && $user1['super'] != "") {
                            $arrsuhero = explode(",", $user1['super']);
                            for ($i = 0; is_array($arrsuhero) && $i < count($arrsuhero); $i++) {
                                if (strstr($user1['su'], $arrsuhero[$i])) {
                                    unset($arrsuhero[$i]);
                                    $arrsuhero = indexaTArr($arrsuhero);
                                    $Psuper = implode(",", $arrsuhero);
                                    $suhero = 1;
                                    $mc->query("UPDATE `vk_battle` SET `super` = '" . $Psuper . "',`su`='' WHERE `id_user` = '" . $user1['id_user'] . "'");
                                    break;
                                }
                            }
                        }
                        $tstr = "ударил";
                        if ($suhero == 1) {
                            $p1 = $user1['uron'] * 2;
                            $tstr = "пустил су в";
                        }
                        $mc->query("UPDATE `vk_battle` SET `hp` = `hp` - '" . $p1 . "' WHERE `id_user` = '" . $user22['id_user'] . "'");
                        $mc->query("UPDATE `vk_battle` SET `hp` = `hp` - '" . $p2 . "' WHERE `id_user` = '" . $user11['id_user'] . "'");
                        //я
                        $user1 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $battle['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                        $user11 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user1['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                        //противник
                        $user2 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $user1['id_user2'] . "'")->fetch_array(MYSQLI_ASSOC);
                        $user22 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user2['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);

                        $xz .= "{$user11['name']} {$tstr} {$user22['name']} на {$p1} в ответ получил {$p2}\n \n {$user11['name']} - [{$user1['hp']}] \n {$user22['name']} - [{$user2['hp']}]  \n";
                    }
                    //я
                    $user1 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $battle['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $user11 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user1['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
                    //противник
                    $user2 = $mc->query("SELECT * FROM `vk_battle` WHERE `id_user` = '" . $user1['id_user2'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $user22 = $mc->query("SELECT * FROM `vk.com` WHERE `id_user` = '" . $user2['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);

                    if ($arr[2] == "4" && $user11['id_user'] == "277318898") {
                        $p1 = rand(10, 100);
                        if ($mc->query("UPDATE `vk_battle` SET `hp` = `hp` - '" . $p1 . "' WHERE `id_user` = '" . $user22['id_user'] . "'")) {
                            $xz .= "{$user11['name']} кинул су  {$user22['name']} на {$p1} \n \n {$user11['name']} - [{$user1['hp']}] \n {$user22['name']} - [{$user2['hp']}]  \n";
                        }
                    }

                    if ($user2['hp'] <= "0") {
                        //`bablo`, `uron`, `bron`, `lov`, `hp`, `slava`, `exp`
                        $bablo = MyRand(1000000);
                        $uron = MyRand(1);
                        $bron = MyRand(1);
                        $lov = MyRand(1);
                        $hp = MyRand(10);
                        $slava = MyRand(100);
                        $exp = MyRand(100) + 10;

                        $mc->query("DELETE FROM `vk_battle` WHERE `id_user` = '" . $user11['id_user'] . "'");
                        $mc->query("DELETE FROM `vk_battle` WHERE `id_user` = '" . $user22['id_user'] . "'");
                        $mc->query("UPDATE `vk.com` SET "
                                . "`bablo` = `bablo` + '" . $bablo . "',"
                                . "`uron` = `uron` + '" . $uron . "',"
                                . "`bron` = `bron` + '" . $bron . "',"
                                . "`lov` = `lov` + '" . $lov . "',"
                                . "`hp` = `hp` + '" . $hp . "',"
                                . "`slava` = `slava` + '" . $slava . "',"
                                . "`exp` = `exp` + '" . $exp . "',"
                                . "`pvp_w` = `pvp_w`+'1'"
                                . " WHERE `id_user` = '" . $user11['id_user'] . "'");
                        $mc->query("UPDATE `vk.com` SET `bablo` = `bablo` - '100',`pvp_l` = `pvp_l`+'1' WHERE `id_user` = '" . $user22['id_user'] . "'");
                        $xz .= "Победа , трофей \n";
                        if ($bablo > 0) {
                            $xz .= "Деньги +{$bablo}\n";
                        } if ($uron > 0) {
                            $xz .= "Урон +{$uron}\n";
                        } if ($bron > 0) {
                            $xz .= "Броня +{$bron}\n";
                        } if ($lov > 0) {
                            $xz .= "Блок +{$lov}\n";
                        } if ($hp > 0) {
                            $xz .= "Здоровье +{$hp}\n";
                        } if ($slava > 0) {
                            $xz .= "Слава +{$slava}\n";
                        } if ($exp > 0) {
                            $xz .= "Опыт +{$exp}\n";
                        }
                    } else if ($user1['hp'] <= "0") {
                        $bablo = MyRand(1000000);
                        $uron = MyRand(1);
                        $bron = MyRand(1);
                        $lov = MyRand(1);
                        $hp = MyRand(10);
                        $slava = MyRand(100);
                        $exp = MyRand(100) + 10;
                        $mc->query("DELETE FROM `vk_battle` WHERE `id_user` = '" . $user1['id_user'] . "'");
                        $mc->query("DELETE FROM `vk_battle` WHERE `id_user` = '" . $user2['id_user'] . "'");
                        $mc->query("UPDATE `vk.com` SET `bablo` = `bablo` - '100',`pvp_l` = `pvp_l`+'1' WHERE `id_user` = '" . $user11['id_user'] . "'");
                        $mc->query("UPDATE `vk.com` SET "
                                . "`bablo` = `bablo` + '" . $bablo . "',"
                                . "`uron` = `uron` + '" . $uron . "',"
                                . "`bron` = `bron` + '" . $bron . "',"
                                . "`lov` = `lov` + '" . $lov . "',"
                                . "`hp` = `hp` + '" . $hp . "',"
                                . "`slava` = `slava` + '" . $slava . "',"
                                . "`exp` = `exp` + '" . $exp . "',"
                                . "`pvp_w` = `pvp_w`+'1'"
                                . " WHERE `id_user` = '" . $user22['id_user'] . "'");
                        $xz .= "Поражение , потеряно 100\n";
                        $xz .= "Противнику досталось :\n";
                        if ($bablo > 0) {
                            $xz .= "Деньги +{$bablo}\n";
                        } if ($uron > 0) {
                            $xz .= "Урон +{$uron}\n";
                        } if ($bron > 0) {
                            $xz .= "Броня +{$bron}\n";
                        } if ($lov > 0) {
                            $xz .= "Блок +{$lov}\n";
                        } if ($hp > 0) {
                            $xz .= "Здоровье +{$hp}\n";
                        } if ($slava > 0) {
                            $xz .= "Слава +{$slava}\n";
                        } if ($exp > 0) {
                            $xz .= "Опыт +{$exp}\n";
                        }
                    }
                } else {
                    $xz .= "Бой окончен. \n";
                }
            }
            if (preg_match('/топ/iu', $text)) {
                $xz .= "\n --🎖️Топ игроки\n";
                $ui = $mc->query("SELECT * FROM `vk.com` ORDER BY `exp` DESC ")->fetch_all(MYSQLI_ASSOC);
                for ($i = 0; $i < count($ui); $i++) {
                    $xz .= " 🥇{$ui[$i]['name']}  🔥[" . GetLevelThis($ui[$i]['exp']) . "] \n";
                }
            }
            if (preg_match('/result/iu', $text)) {
                for ($i = 0; $i < count($u); $i++) {
                    $ad;
                    if ($u[$i]['access'] == 0) {
                        $ad = "И";
                    } else if ($u[$i]['access'] == 1) {
                        $ad = "M";
                    } else if ($u[$i]['access'] == 2) {
                        $ad = "Ad";
                    } else if ($u[$i]['access'] > 2) {
                        $ad = "👑";
                    }
                    $d .= "{$ad}-{$u[$i]['name']} [{$u[$i]['level']}]\n";
                }
                $xz .= $d . "\n И-игрок \n M-модератор \n Ad-администратор \n 👑-разработчик";
            } elseif (preg_match('/getdate/iu', $text) && $text != $xz) {
                $xz .= "Серверное время: " . date("h:i:s");
            } elseif (preg_match('/info/iu', $text) && $text != $xz) {
                $xz .= "бот версия T1000\n"
                        . "getAdmin - список админов\n"
                        . "user - личный профиль\n"
                        . "бой - случайный бой\n"
                        . "pvp(*******) - pvp(айди противника) создать бой\n"
                        . "bat(1-3) - удары по противнику\n"
                        . "или числа 1-3 после создания боя\n"
                        . "топ - список игроков\n"
                        . "result - информация статусов\n"
                        . "nik(*) - смена ника nik(новый ник)\n"
                        . "rand(*,*) - сыграть с ботом rand(число от,до)\n"
                        . "azino(*) - сыграть на бабло azino(сумма)\n";
            } elseif ($text == "getAdmin" && $text != $xz) {
                for ($i = 0; $i < count($u); $i++) {
                    if ($u[$i]['access'] == 1) {
                        $xz .= "M - " . $u[$i]['name'] . " [{$u[$i]['level']}]\n";
                    } else if ($u[$i]['access'] == 2) {
                        $xz .= "Ad - " . $u[$i]['name'] . " [{$u[$i]['level']}]\n";
                    } else if ($u[$i]['access'] > 2) {
                        $xz .= "👑 - " . $u[$i]['name'] . " [{$u[$i]['level']}]\n";
                    }
                }
            } elseif (preg_match('/azino(.*?)\((.*?)\)/iu', $text, $arr)) {
                //if ($users['bablo'] > $arr[2]) {
                $res = 70;
                if ($res > 50) {
                    $xz .= "Вы выйграли " . ($arr[2] * 2) . "\n";
                } else {
                    $xz .= "Вам не повезло 😣 вы потеряли " . ($arr[2] * 2) . "\n";
                }
                //   }
            } elseif (preg_match('/nik\((.*?)\)/iu', $text, $arr)) {

                if ($mc->query("UPDATE `vk.com` SET `name` = '" . $arr[1] . "' WHERE `id_user` = '" . $userID . "'")) {
                    $xz .= "Ваш ник изменён на {$arr[1]}";
                }
            } elseif (preg_match('/rand(.*?)\((.*?),(.*?)\)/iu', $text, $arr)) {
                $u1 = rand($arr[2], $arr[3]);
                $b1 = rand($arr[2], $arr[3]);
                $t1 = " Ничья !";
                if ($u1 > $b1) {
                    if ($mc->query("UPDATE `vk.com` SET `bablo` = `bablo` + '" . $u1 . "' WHERE `id_user` = '" . $userID . "'")) {
                        $t1 = " Вы Победили ! ";
                    }
                }
                if ($u1 < $b1) {
                    if ($mc->query("UPDATE `vk.com` SET `bablo` = `bablo` - '" . $u1 . "' WHERE `id_user` = '" . $userID . "'")) {
                        $t1 = " Вы Проиграли ! ";
                    }
                }
                if ($u1 != "" && $b1 != "") {
                    $xz .= $t1 . " Счёт " . $u1 . " : " . $b1 . " .";
                }
            }

         /*   if ($xz == "") {
                $xz = " " . $alt[array_rand($alt)] . " .";
            }
            if ($xz != "") {
                 $im = imagecreatetruecolor(120, 20);
                  $text_color = imagecolorallocate($im, 233, 14, 91);
                  imagestring($im, 1, 5, 5,  'ррроо', $text_color);
                  imagebmp($im, 'php.bmp');
                  imagedestroy($im); 
                send($userName . "," . $peer_id, $peer_id);
            }*/


            header("HTTP/1.1 200 OK");
            echo 'ok';
            break;

        case 'wall_post_new':

            send("новая запись \n {$data->object->text}", 277318898);
            header("HTTP/1.1 200 OK");
            echo 'ok';
            break;
        case 'confirmation':
            echo $confirmationToken;
            break;
        default :
            header("HTTP/1.1 200 OK");
            echo 'ok';
    }
}
?>