<?php

include "config.php";
include "../system/connect.php";

$config = new Config_vk;
$confirmationToken = 'f459d8e3';
$data = json_decode(file_get_contents("php://input"));
if (strcmp($data->secret, $config->privat) !== 0) {
    exit(0);
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

switch ($data->type) {
    case 'message_new':
        $userID = $data->object->from_id;
        $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids=" . $userID . "&v=5.50&access_token=" . $config->key));
        $userName = $userInfo->response[0]->first_name;
        $peer_id = $data->object->peer_id ?: $data->object->from_id;
        $u = $mc->query("SELECT * FROM `users` LIMIT 70")->fetch_all(MYSQLI_ASSOC);
        $alt = ["классно", "ты молодец", "спасибо", "удачи", "давай", "я против", "сколько", "зачем", "хватит", "тише будь", "сам такой", "иногда", "возможено", "может", "нет", "да", "ага", "согласен", "хорошо", "отлично", "понятно", "интересно", "я тоже", "подумаешь", "делов то", "ну бывает", "ну и что", "когда это было", "почему", "где", "что", "кто", "ок", "ладно", "и чё", "ну",];
        $arr = [
            "привет" => [
                "Привет", "Здорова", "Ку", "Хай", "привет", "здрасте", "хеллёу", "дратуте"
            ],
            "как" => [
                "норм", "збс", "отлично", "круто", "хорошо", "замечательно", "великолепно", "это просто шедевр братан"
            ],
            "дела" => [
                "норм", "збс", "отлично", "круто", "хорошо", "замечательно", "великолепно", "это просто шедевр братан"
            ],
            "бот" => [
                "классно", "ты молодец", "спасибо", "удачи", "давай", "я против", "сколько", "зачем", "хватит", "тише будь", "сам такой", "иногда", "возможено", "может", "нет", "да", "ага", "согласен", "хорошо", "отлично", "понятно", "интересно", "я тоже", "подумаешь", "делов то", "ну бывает", "ну и что", "когда это было", "почему", "где", "что", "кто", "ок", "ладно", "и чё", "ну", "кто бот то", "бот не бот"
            ],
            "?" => [
                "хм надо подумать", "а в гугле пробовал смотреть", "надо загуглить", "странный вопрос"
            ]
        ];
        $xz = "";
        $text = $data->object->text;
        foreach ($arr as $key => $item) {
            if (preg_match("/" . preg_quote($key, '/') . "/iu", $text)) {
                $xz .= "  " . $arr[$key][array_rand($item)] . " . ";
            }
        }

        if ($xz == "") {
            $xz = " " . $alt[array_rand($alt)] . " .";
        }
        $d = "";
         /*if(json_decode(file_get_contents('user.json'),TRUE) == null{
         	send("gooooogle",$peer_id);
    }*/
   $file = json_decode(file_get_contents('user.json'),TRUE);
   //регистрация авто
   for($i = 0; $i < count($file); $i++){
   	if((int) $file[$i]->id == $userID){
   	send("55555",$peer_id);
   	}else{
   	$id = count($file) + 1;
   	$file[$id] = array(
    'id' => $userID,
    'name' => $userName,
    'bablo' => 1500);
    file_put_contents('user.json',json_encode($file[$id]));
    send("Добро пожаловать {$userName}",$peer_id);
   }
  }
   $t = "";
   	if($text == "user"){
   	for($i = 0; $i < count($file); $i++){
   	if($file[$i]->id == $userID){
   	$t = "Пользователь:\n Имя: {$file[$i]->name} \n 💵Деньги: {$file[$i]->bablo} \n Айди: {$file[$i]->id}";
   	}
   }
   	send("{$t}",$peer_id);
   }
   
        if ($text == "result") {
            for ($i = 0; $i < count($u); $i++) {
                $ad;
                if ($u[$i]['access'] == 0) {
                    $ad = "И";
                } else if ($u[$i]['access'] == 1) {
                    $ad = "M";
                } else if ($u[$i]['access'] == 2) {
                    $ad = "Ad";
                } else if ($u[$i]['access'] > 3) {
                    $ad = "👑";
                }
                $d .= "{$ad}-{$u[$i]['name']} [{$u[$i]['level']}]\n";
            }
            send($d . "\n И-игрок \n M-модератор \n Ad-администратор \n 👑-разработчик", $peer_id);
        } elseif ($text == "getDate" && $text != $xz) {
            send("Серверное время: " . date("h:i:s"), $peer_id);
        } elseif ($text == "info" && $text != $xz) {
            send("mobitva2 online (android,web)", $peer_id);
        } elseif ($text == "getAdmin" && $text != $xz) {
            send("👑" . $u[2]['name'] . " [{$u[2]['level']}]\n 👑" . $u[1]['name'] . " [{$u[1]['level']}]", $peer_id);
        } elseif (preg_match('/rand(.*?)\((.*?),(.*?)\)/iu', $text, $arr)) {
            $u1 = rand($arr[2], $arr[3]);
            $b1 = rand($arr[2], $arr[3]);
            $t1 = " Ничья !";
            if ($u1 > $b1) {
                $t1 = " Вы Победили ! ";
            }
            if ($u1 < $b1) {
                $t1 = " Вы Проиграли ! ";
            }
            send($userName . "," . $t1 . " Счёт " . $u1 . " : " . $b1 . " .", $peer_id);
        } elseif (preg_match("/" . preg_quote("бот", "/") . "/iu", $text)) {
            send($userName . "," . $xz, $peer_id);
        }
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
?>