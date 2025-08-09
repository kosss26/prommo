<?php

include 'dbc.php';
//получаем
if (isset($_POST["nick"]) && isset($_POST["pass"]) && isset($_POST["lastId"]) && isset($_POST["chat"])) {
    $nick = $_POST["nick"];
    $pass = $_POST["pass"];
    $lastid = (int) $_POST["lastId"];
    $chat = (int) $_POST["chat"];
    $msg = array();
    $arr = array();
    $dta = time();

    //удалим из таблицы банов прошедшие баны а здесь потому что это общий доступ к чатам
    $mc->query("DELETE FROM `chatban` WHERE `chatban`.`time` < '$dta'");

    //получить параметры героя 1 запись взять
    $result = $mc->query("SELECT * FROM `users` WHERE `login` = '" . $nick . "' AND `password` = '" . $pass . "' ORDER BY `id` DESC LIMIT 1");
    if ($result->num_rows) {
        //создать массив параметров героя если есть результат ил выход
        $user = $result->fetch_array(MYSQLI_ASSOC);
        $uid = (int) $user["id"];
        $access = (int) $user['access'];
        //проверка доступа прав если ломится в мд с правами 0 или клана нет у него или чат 2 не сущ или чат меньше 0 то нах ничего не покажем
        if ($access == 1 && $chat == 4 ||
                $access == 1 && $chat == 5 ||
                $access == 0 && $chat == 3 ||
                $access == 0 && $chat == 4 ||
                $access == 0 && $chat == 5 ||
                $chat != $user['id_clan']+5 && $chat > 5 || $chat == 2 || $chat < 0) {
            echo json_encode(array()); //заглушка
            exit(0);
        }
        //обновить время онлайна и записать комнату чата
        $mc->query("UPDATE `users` SET `online`='" . $dta . "',`onlinechat`='" . $dta . "',`room`=$chat WHERE `id`='$uid'");
        $ip = $_SERVER["REMOTE_ADDR"];
        $mc->query("UPDATE `users` SET `ip`='" . $ip . "',`online`='" . $dta . "' WHERE `id`='" . $user["id"] . "'");
    } else {
        echo json_encode(array()); //заглушка
        exit(0);
    }
    //пытаемся прочесть чат если пусто или 0 последний ид то пробуем прочесть 30 записей определенного чата
    if (empty($_POST["lastId"])) {
        $chatbd2 = $mc->query("SELECT * FROM `chat` WHERE `chat_room`='$chat' AND `id`>'$lastid' ORDER BY `id` DESC LIMIT 40");
    } else {
        //или читаем последние от ид не более 30 записей за раз
        $chatbd2 = $mc->query("SELECT * FROM `chat` WHERE `chat_room`='$chat' AND `id`>'$lastid' ORDER BY `id` DESC LIMIT 40");
    }
    //если есть записи то переведем их в массив или выход
    if ($chatbd2->num_rows) {
        $arr = $chatbd2->fetch_all(MYSQLI_ASSOC);
    } else {
        echo json_encode(array()); //заглушка
        exit(0);
    }
    //перегоняем массив
    for ($i = 0; $i < count($arr); $i++) {
        //cмайлы
        $msg[$i][0] = (int) $arr[$i]['id'];
        $msg[$i][1] = $arr[$i]['msg'];
        $msg[$i][2] = (int) $arr[$i]['id_user'];
        $msg[$i][1] = str_replace("XD", "<img src='/img/smile/smile_1.png?272.0' width='22px' alt='XD'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":)", "<img src='/img/smile/smile_2.png?272.0' width='22px' alt=':)'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-)", "<img src='/img/smile/smile_2.png?272.0' width='22px' alt=':-)'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":(", "<img src='/img/smile/smile_3.png?272.0' width='22px' alt=':('>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-(", "<img src='/img/smile/smile_3.png?272.0' width='22px' alt=':-('>", $msg[$i][1]);
        $msg[$i][1] = str_replace(";)", "<img src='/img/smile/smile_4.png?272.0' width='22px' alt=';)'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(";-)", "<img src='/img/smile/smile_4.png?272.0' width='22px' alt=';-)'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":[", "<img src='/img/smile/0.png?272.0' width='22px' alt=':['>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-*", "<img src='/img/smile/1.png?272.0' width='22px' alt=':-*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-0", "<img src='/img/smile/2.png?272.0' width='22px' alt=':-0'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-D", "<img src='/img/smile/3.png?272.0' width='22px' alt=':-D'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":!", "<img src='/img/smile/4.png?272.0' width='22px' alt=':!'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":_)", "<img src='/img/smile/5.png?272.0' width='22px' alt=':_)'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("8[*", "<img src='/img/smile/6.png?272.0' width='22px' alt='8[*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":#", "<img src='/img/smile/7.png?272.0' width='22px' alt=':#'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(")))", "<img src='/img/smile/8.png?272.0' width='22px' alt=')))'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-|", "<img src='/img/smile/9.png?272.0' width='22px' alt=':-|'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("3|", "<img src='/img/smile/10.png?272.0' width='22px' alt='3|'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(";|", "<img src='/img/smile/11.png?272.0' width='22px' alt=';|'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*dntknw*", "<img src='/img/smile/12.png?272.0' width='22px' alt='*dntknw*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*ermm*", "<img src='/img/smile/13.png?272.0' width='22px' alt='*ermm*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*hm*", "<img src='/img/smile/14.png?272.0' width='22px' alt='*hm*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":o)", "<img src='/img/smile/15.png?272.0' width='22px' alt=':o)'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("8|", "<img src='/img/smile/16.png?272.0' width='22px' alt='8|'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*nirvana*", "<img src='/img/smile/17.png?272.0' width='22px' alt='*nirvana*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*zawtoroj*", "<img src='/img/smile/18.png?272.0' width='22px' alt='*zawtoroj*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*LICK*", "<img src='/img/smile/19.png?272.0' width='22px' alt='*LICK*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*happy*", "<img src='/img/smile/20.png?272.0' width='22px' alt='*happy*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*yes*", "<img src='/img/smile/21.png?272.0' width='22px' alt='*yes*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":-s", "<img src='/img/smile/22.png?272.0' width='22px' alt=':-s'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":@", "<img src='/img/smile/24.png?272.0' width='22px' alt=':@'>", $msg[$i][1]);

        $msg[$i][1] = str_replace("*secret*", "<img src='/img/smile/25.png?272.0' width='22px' alt=':пиво:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*botan*", "<img src='/img/smile/26.png?272.0' width='22px' alt=':пиво:'>", $msg[$i][1]);

        $msg[$i][1] = str_replace("*ninja*", "<img src='/img/smile/27.png?272.0' width='22px' alt='*ninja*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("*bravo*", "<img src='/img/smile/28.png?272.0' width='22px' alt='*bravo*'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("[F]", "<img src='/img/smile/29.png?272.0' width='22px' alt='[F]'>", $msg[$i][1]);
        $msg[$i][1] = str_replace("[L]", "<img src='/img/smile/30.png?272.0' width='22px' alt='[L]'>", $msg[$i][1]);
        //пиво - :пиво:
        $msg[$i][1] = str_replace(":пиво:", "<img src='/img/smile/pivo.png?272.0' width='22px' alt=':пиво:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(";(", "<img src='/img/smile/pivo.png?272.0' width='22px' alt=';('>", $msg[$i][1]);

        $msg[$i][1] = str_replace(":100:", "<img src='img/smiles/100.png?272.0' width='22px' alt=':100:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":101:", "<img src='img/smiles/101.png?272.0' width='22px' alt=':101:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":102:", "<img src='img/smiles/102.png?272.0' width='22px' alt=':102:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":103:", "<img src='img/smiles/103.png?272.0' width='22px' alt=':103:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":104:", "<img src='img/smiles/104.png?272.0' width='22px' alt=':104:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":105:", "<img src='img/smiles/105.png?272.0' width='22px' alt=':105:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":106:", "<img src='img/smiles/106.png?272.0' width='22px' alt=':106:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":107:", "<img src='img/smiles/107.png?272.0' width='22px' alt=':107:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":108:", "<img src='img/smiles/108.png?272.0' width='22px' alt=':108:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":109:", "<img src='img/smiles/109.png?272.0' width='22px' alt=':109:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":110:", "<img src='img/smiles/110.png?272.0' width='22px' alt=':110:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":111:", "<img src='img/smiles/111.png?272.0' width='22px' alt=':111:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":112:", "<img src='img/smiles/112.png?272.0' width='22px' alt=':112:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":113:", "<img src='img/smiles/113.png?272.0' width='22px' alt=':113:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":114:", "<img src='img/smiles/114.png?272.0' width='22px' alt=':114:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":115:", "<img src='img/smiles/115.png?272.0' width='22px' alt=':115:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":116:", "<img src='img/smiles/116.png?272.0' width='22px' alt=':116:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":117:", "<img src='img/smiles/117.png?272.0' width='22px' alt=':117:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":118:", "<img src='img/smiles/118.png?272.0' width='22px' alt=':118:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":119:", "<img src='img/smiles/119.png?272.0' width='22px' alt=':119:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":120:", "<img src='img/smiles/120.png?272.0' width='22px' alt=':120:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":121:", "<img src='img/smiles/121.png?272.0' width='22px' alt=':121:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":122:", "<img src='img/smiles/122.png?272.0' width='22px' alt=':122:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":123:", "<img src='img/smiles/123.png?272.0' width='22px' alt=':123:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":124:", "<img src='img/smiles/124.png?272.0' width='22px' alt=':124:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":125:", "<img src='img/smiles/125.png?272.0' width='22px' alt=':125:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":126:", "<img src='img/smiles/126.png?272.0' width='22px' alt=':126:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":127:", "<img src='img/smiles/127.png?272.0' width='22px' alt=':127:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":128:", "<img src='img/smiles/128.png?272.0' width='22px' alt=':128:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":129:", "<img src='img/smiles/129.png?272.0' width='22px' alt=':129:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":130:", "<img src='img/smiles/130.png?272.0' width='22px' alt=':130:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":131:", "<img src='img/smiles/131.png?272.0' width='22px' alt=':131:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":132:", "<img src='img/smiles/132.png?272.0' width='22px' alt=':132:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":133:", "<img src='img/smiles/133.png?272.0' width='22px' alt=':133:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":134:", "<img src='img/smiles/134.png?272.0' width='22px' alt=':134:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":135:", "<img src='img/smiles/135.png?272.0' width='22px' alt=':135:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":136:", "<img src='img/smiles/136.png?272.0' width='22px' alt=':136:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":137:", "<img src='img/smiles/137.png?272.0' width='22px' alt=':137:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":138:", "<img src='img/smiles/138.png?272.0' width='22px' alt=':138:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":139:", "<img src='img/smiles/139.png?272.0' width='22px' alt=':139:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":140:", "<img src='img/smiles/140.png?272.0' width='22px' alt=':140:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":141:", "<img src='img/smiles/141.png?272.0' width='22px' alt=':141:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":142:", "<img src='img/smiles/142.png?272.0' width='22px' alt=':142:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":143:", "<img src='img/smiles/143.png?272.0' width='22px' alt=':143:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":144:", "<img src='img/smiles/144.png?272.0' width='22px' alt=':144:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":145:", "<img src='img/smiles/145.png?272.0' width='22px' alt=':145:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":146:", "<img src='img/smiles/146.png?272.0' width='22px' alt=':146:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":147:", "<img src='img/smiles/147.png?272.0' width='22px' alt=':147:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":148:", "<img src='img/smiles/148.png?272.0' width='22px' alt=':148:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":149:", "<img src='img/smiles/149.png?272.0' width='22px' alt=':149:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":150:", "<img src='img/smiles/150.png?272.0' width='22px' alt=':150:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":151:", "<img src='img/smiles/151.png?272.0' width='22px' alt=':151:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":152:", "<img src='img/smiles/152.png?272.0' width='22px' alt=':152:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":153:", "<img src='img/smiles/153.png?272.0' width='22px' alt=':153:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":154:", "<img src='img/smiles/154.png?272.0' width='22px' alt=':154:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":155:", "<img src='img/smiles/155.png?272.0' width='22px' alt=':155:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":156:", "<img src='img/smiles/156.png?272.0' width='22px' alt=':156:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":157:", "<img src='img/smiles/157.png?272.0' width='22px' alt=':157:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":158:", "<img src='img/smiles/158.png?272.0' width='22px' alt=':158:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":159:", "<img src='img/smiles/159.png?272.0' width='22px' alt=':159:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":160:", "<img src='img/smiles/160.png?272.0' width='22px' alt=':160:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":161:", "<img src='img/smiles/161.png?272.0' width='22px' alt=':161:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":162:", "<img src='img/smiles/162.png?272.0' width='22px' alt=':162:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":163:", "<img src='img/smiles/163.png?272.0' width='22px' alt=':163:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":164:", "<img src='img/smiles/164.png?272.0' width='22px' alt=':164:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":165:", "<img src='img/smiles/165.png?272.0' width='22px' alt=':165:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":166:", "<img src='img/smiles/166.png?272.0' width='22px' alt=':166:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":167:", "<img src='img/smiles/167.png?272.0' width='22px' alt=':167:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":168:", "<img src='img/smiles/168.png?272.0' width='22px' alt=':168:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":169:", "<img src='img/smiles/169.png?272.0' width='22px' alt=':169:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":170:", "<img src='img/smiles/170.png?272.0' width='22px' alt=':170:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":171:", "<img src='img/smiles/171.png?272.0' width='22px' alt=':171:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":172:", "<img src='img/smiles/172.png?272.0' width='22px' alt=':172:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":173:", "<img src='img/smiles/173.png?272.0' width='22px' alt=':173:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":174:", "<img src='img/smiles/174.png?272.0' width='22px' alt=':174:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":175:", "<img src='img/smiles/175.png?272.0' width='22px' alt=':175:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":176:", "<img src='img/smiles/176.png?272.0' width='22px' alt=':176:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":177:", "<img src='img/smiles/177.png?272.0' width='22px' alt=':177:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":178:", "<img src='img/smiles/178.png?272.0' width='22px' alt=':178:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":179:", "<img src='img/smiles/179.png?272.0' width='22px' alt=':179:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":180:", "<img src='img/smiles/180.png?272.0' width='22px' alt=':180:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":181:", "<img src='img/smiles/181.png?272.0' width='22px' alt=':181:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":182:", "<img src='img/smiles/182.png?272.0' width='22px' alt=':182:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":183:", "<img src='img/smiles/183.png?272.0' width='22px' alt=':183:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":184:", "<img src='img/smiles/184.png?272.0' width='22px' alt=':184:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":185:", "<img src='img/smiles/185.png?272.0' width='22px' alt=':185:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":186:", "<img src='img/smiles/186.png?272.0' width='22px' alt=':186:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":187:", "<img src='img/smiles/187.png?272.0' width='22px' alt=':187:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":188:", "<img src='img/smiles/188.png?272.0' width='22px' alt=':188:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":189:", "<img src='img/smiles/189.png?272.0' width='22px' alt=':189:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":190:", "<img src='img/smiles/190.png?272.0' width='22px' alt=':190:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":191:", "<img src='img/smiles/191.png?272.0' width='22px' alt=':191:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":192:", "<img src='img/smiles/192.png?272.0' width='22px' alt=':192:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":193:", "<img src='img/smiles/193.png?272.0' width='22px' alt=':193:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":194:", "<img src='img/smiles/194.png?272.0' width='22px' alt=':194:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":195:", "<img src='img/smiles/195.png?272.0' width='22px' alt=':195:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":196:", "<img src='img/smiles/196.png?272.0' width='22px' alt=':196:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":197:", "<img src='img/smiles/197.png?272.0' width='22px' alt=':197:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":198:", "<img src='img/smiles/198.png?272.0' width='22px' alt=':198:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":199:", "<img src='img/smiles/199.png?272.0' width='22px' alt=':199:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":200:", "<img src='img/smiles/200.png?272.0' width='22px' alt=':200:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":201:", "<img src='img/smiles/201.png?272.0' width='22px' alt=':201:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":202:", "<img src='img/smiles/202.png?272.0' width='22px' alt=':202:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":203:", "<img src='img/smiles/203.jpg?272.0' width='22px' alt=':203:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":204:", "<img src='img/smiles/204.png?272.0' width='22px' alt=':204:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":205:", "<img src='img/smiles/205.png?272.0' width='22px' alt=':205:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":206:", "<img src='img/smiles/206.png?272.0' width='22px' alt=':206:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":207:", "<img src='img/smiles/207.png?272.0' width='22px' alt=':207:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":208:", "<img src='img/smiles/208.png?272.0' width='22px' alt=':208:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":209:", "<img src='img/smiles/209.png?272.0' width='22px' alt=':209:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":210:", "<img src='img/smiles/210.png?272.0' width='22px' alt=':210:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":211:", "<img src='img/smiles/211.png?272.0' width='22px' alt=':211:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":212:", "<img src='img/smiles/212.png?272.0' width='22px' alt=':212:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":213:", "<img src='img/smiles/213.png?272.0' width='22px' alt=':213:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":214:", "<img src='img/smiles/214.png?272.0' width='22px' alt=':214:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":215:", "<img src='img/smiles/215.png?272.0' width='22px' alt=':215:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":216:", "<img src='img/smiles/216.png?272.0' width='22px' alt=':216:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":217:", "<img src='img/smiles/217.png?272.0' width='22px' alt=':217:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":218:", "<img src='img/smiles/218.png?272.0' width='22px' alt=':218:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":219:", "<img src='img/smiles/219.png?272.0' width='22px' alt=':219:'>", $msg[$i][1]);
        $msg[$i][1] = str_replace(":220:", "<img src='img/smiles/220.png?272.0' width='22px' alt=':220:'>", $msg[$i][1]);
    }
    //выводим массив
    echo json_encode($msg);
    exit(0);
}
echo json_encode(array()); //заглушка
exit(0);
