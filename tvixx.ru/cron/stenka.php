<?php

require_once 'bd.php';

$arr_lvl = [
    [1, 2],
    [3, 4],
    [5, 6],
    [7, 8],
    [9, 10],
    [11, 12],
    [13, 14],
    [15, 16],
    [17, 18],
    [19, 20],
    [21, 22],
    [23, 24],
    [25, 26],
    [27, 28],
    [29, 30],
    [31, 32],
    [33, 34],
    [35, 36],
    [37, 38],
    [39, 40],
    [41, 42],
    [43, 44],
    [45, 46],
    [47, 48],
    [49, 50]
];
$names = [
    [ // Мужские имена
        "Темный_Рыцарь", "Призрачный_Воин", "Мастер_Клинка", "Хранитель_Севера", 
        "Воин_Света", "Мститель", "Странник_Пустошей", "Кровавый_Охотник",
        "Повелитель_Бури", "Ледяной_Волк", "Стальной_Кулак", "Огненный_Феникс",
        "Черный_Дракон", "Мудрый_Монах", "Бесстрашный_Воин", "Горный_Титан",
        "Морской_Волк", "Небесный_Страж", "Лесной_Следопыт", "Степной_Ветер",
        "Каменный_Страж", "Воин_Теней", "Мастер_Стихий", "Хранитель_Врат",
        "Вечный_Странник", "Повелитель_Мечей", "Железный_Воин", "Молниеносный",
        "Непобедимый", "Бессмертный", "Воин_Судьбы", "Мастер_Битвы",
        "Легендарный_Воин", "Хранитель_Мудрости", "Повелитель_Стали",
        "Ночной_Охотник", "Мастер_Войны", "Воин_Рассвета", "Страж_Границ",
        "Хранитель_Клинка", "Воин_Чести", "Мастер_Тактики", "Горный_Орел",
        "Степной_Сокол", "Морской_Дракон", "Огненный_Воин", "Ледяной_Страж",
        "Повелитель_Грома", "Хранитель_Времени", "Мастер_Стали"
    ],
    [ // Женские имена
        "Лунная_Дева", "Хранительница_Света", "Повелительница_Льда", "Дочь_Бури",
        "Огненная_Леди", "Лесная_Нимфа", "Морская_Дева", "Небесная_Воительница",
        "Хранительница_Мудрости", "Повелительница_Теней", "Дева_Щита",
        "Мастер_Клинка", "Степная_Охотница", "Горная_Воительница",
        "Хранительница_Врат", "Повелительница_Стихий", "Дочь_Молний",
        "Ледяная_Королева", "Огненная_Валькирия", "Лесная_Охотница",
        "Морская_Воительница", "Небесная_Дева", "Хранительница_Тайн",
        "Повелительница_Судьбы", "Дева_Войны", "Мастер_Битвы",
        "Степная_Воительница", "Горная_Дева", "Хранительница_Клинка",
        "Повелительница_Мечей", "Дочь_Стали", "Ледяная_Воительница",
        "Огненная_Дева", "Лесная_Воительница", "Морская_Охотница",
        "Небесная_Королева", "Хранительница_Севера", "Повелительница_Бури",
        "Дева_Рассвета", "Мастер_Стрельбы", "Степная_Королева",
        "Горная_Охотница", "Хранительница_Времени", "Повелительница_Грома",
        "Дочь_Дракона", "Ледяная_Охотница", "Огненная_Воительница",
        "Лесная_Королева", "Морская_Воительница", "Небесная_Дева"
    ]
];

$timeThis = time();
$battle_start_time = $timeThis;
$commax = 15;
$commin1 = 1;
$commin2 = 1;


for ($i0 = 0; $i0 < count($arr_lvl); $i0++) {
    //текущий уровень мин макс
    $minLevel = $arr_lvl[$i0][0];
    $maxLevel = $arr_lvl[$i0][1];
    $battle_id = rand(0, $timeThis) . rand(0, $timeThis) . rand(0, $timeThis);

    $arrU1 = $mc->query("SELECT * FROM `huntb_list` WHERE `level` >= '$minLevel' && `level` <= '$maxLevel' && `type`='5' && `rasa`='0'")->fetch_all(MYSQLI_ASSOC);
    $arrU2 = $mc->query("SELECT * FROM `huntb_list` WHERE `level` >= '$minLevel' && `level` <= '$maxLevel' && `type`='5' && `rasa`='1'")->fetch_all(MYSQLI_ASSOC);

    if (count($arrU1) >= $commin1 || count($arrU2) >= $commin2) {
        if (count($arrU1) > count($arrU2)) {
            $botNum = count($arrU1) - count($arrU2);
            $botside = 1;
            $arrbotpar = genbotpar($names, $botNum);
            for ($i = 0; $i < count($arrU1); $i++) {
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrU1[$i]['user_id'] . "'")->fetch_array(MYSQLI_ASSOC);
                if ($i == 0) {
                    hero_add($arrU1[$i]['rasa'], "стенка", $users, $battle_id, $battle_start_time, 5);
                } else {
                    hero_add($arrU1[$i]['rasa'], "",  $users, $battle_id, $battle_start_time, 5);
                }
            }
            for ($i = 0; $i < count($arrU2); $i++) {
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrU2[$i]['user_id'] . "'")->fetch_array(MYSQLI_ASSOC);
                hero_add($arrU2[$i]['rasa'], "",  $users, $battle_id, $battle_start_time, 5);
            }
            for ($i = 0; $i < count($arrbotpar[0]); $i++) {
                if ($arrbotpar[1][$i] == 0) {
                    $arrbotpar[1][$i] = 2;
                }
                if ($arrbotpar[1][$i] == 1) {
                    $arrbotpar[1][$i] = 3;
                }
                bot_add($arrbotpar[0][$i] . "[БОТ]", $botside, $arrbotpar[1][$i], $arrbotpar[2][$i], $users['level'],  $battle_id, $battle_start_time, 5);
            }
        } elseif (count($arrU1) < count($arrU2)) {
            $botNum = count($arrU2) - count($arrU1);
            $botside = 0;
            $arrbotpar = genbotpar($names, $botNum);
            for ($i = 0; $i < count($arrU1); $i++) {
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrU1[$i]['user_id'] . "'")->fetch_array(MYSQLI_ASSOC);
                hero_add($arrU1[$i]['rasa'], "",  $users, $battle_id, $battle_start_time, 5);
            }
            for ($i = 0; $i < count($arrU2); $i++) {
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrU2[$i]['user_id'] . "'")->fetch_array(MYSQLI_ASSOC);
                if ($i == 0) {
                    hero_add($arrU2[$i]['rasa'], "стенка",  $users, $battle_id, $battle_start_time, 5);
                } else {
                    hero_add($arrU2[$i]['rasa'], "",  $users, $battle_id, $battle_start_time, 5);
                }
            }
            for ($i = 0; $i < count($arrbotpar[0]); $i++) {
                if ($arrbotpar[1][$i] == 2) {
                    $arrbotpar[1][$i] = 0;
                }
                if ($arrbotpar[1][$i] == 3) {
                    $arrbotpar[1][$i] = 1;
                }
                bot_add($arrbotpar[0][$i] . "[БОТ]", $botside, $arrbotpar[1][$i], $arrbotpar[2][$i], $users['level'],  $battle_id, $battle_start_time, 5);
            }
        } else {
            for ($i = 0; $i < count($arrU1); $i++) {
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrU1[$i]['user_id'] . "'")->fetch_array(MYSQLI_ASSOC);
                if ($i == 0) {
                    hero_add($arrU1[$i]['rasa'], "стенка",  $users, $battle_id, $battle_start_time, 5);
                } else {
                    hero_add($arrU1[$i]['rasa'], "",  $users, $battle_id, $battle_start_time, 5);
                }
            }
            for ($i = 0; $i < count($arrU2); $i++) {
                $users = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrU2[$i]['user_id'] . "'")->fetch_array(MYSQLI_ASSOC);
                hero_add($arrU2[$i]['rasa'], "",  $users, $battle_id, $battle_start_time, 5);
            }
        }
        $mc->query("DELETE FROM `huntb_list` WHERE `level` >= '$minLevel' && `level` <= '$maxLevel' && `type`='5'");
    }
}

function genbotpar($names, $z) {
    $n = [];
    $si = [];
    $st = [];
    $i = 0;
    while ($i < $z) {
        $command2 = rand(0, 1);
        if ($command2 == 1) {
            $side = rand(2, 3);
        } else if ($command2 == 0) {
            $side = rand(0, 1);
        }
        if ($side == 0 || $side == 2) {
            $pol = 0;
        } else if ($side == 1 || $side == 3) {
            $pol = 1;
        }
        $temp = $names[$pol][array_rand($names[$pol])];
        if (!in_array($temp, $n)) {
            $n[] = $temp;
            $si[] = $side;
            $st[] = rand(0, 4);
            $i++;
        }
    }
    return [$n, $si, $st];
}

function hero_add($command, $type_battle,  $userjuhg8, $battle_id, $battle_start_time, $type) {
    global $mc;
    global $timeThis;
    $PA = $userjuhg8;
    $PA['weaponico'] = 0;
    $PA['Pshieldnum'] = 0;
    $PweaponEffect = array();

    //pl 1
    $arr1 = [];
    $shops_ids = [];
    $arr1['temp_health'] = $PA['temp_health'];
    $arr1['max_health'] = $PA['health'];
    $arr1['strength'] = $PA['strength'];
    $arr1['toch'] = $PA['toch'];
    $arr1['lov'] = $PA['lov'];
    $arr1['kd'] = $PA['kd'];
    $arr1['block'] = $PA['block'];
    $arr1['bron'] = $PA['bron'];
    //пересчет параметров игрока
    //получаем список одетых вещей героя
    $result221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $PA['id'] . "' AND `dress`='1' && `BattleFlag`='1' || `id_user` = '" . $PA['id'] . "' AND `id_punct`>'9' && `BattleFlag`='1'");
    $myrow221 = $result221->fetch_all(MYSQLI_ASSOC);
    //перебираем параметры вещей

    for ($i = 0; $i < count($myrow221); $i++) {
        //read thing
        $result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221[$i]['id_shop'] . "'");
        if ($result1->num_rows) {
            //thing to arr par
            $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
            $shops_ids[] = [addslashes($infoshop['name']), $infoshop['id']];
            $arr1['max_health'] += $infoshop['health'];
            $arr1['strength'] += $infoshop['strength'];
            $arr1['toch'] += $infoshop['toch'];
            $arr1['lov'] += $infoshop['lov'];
            $arr1['kd'] += $infoshop['kd'];
            $arr1['block'] += $infoshop['block'];
            $arr1['bron'] += $infoshop['bron'];
            //переводим в иконку оружия
            if ((int) $infoshop['id_punct'] == 1) {
                if ($infoshop['id_image'] <= 36 || $infoshop['id_image'] >= 279 && $infoshop['id_image'] <= 298) {
                    $PA['weaponico'] = $infoshop['id_image'];
                } else {
                    $PA['weaponico'] = 0;
                }
            }
            //получаем количество щита
            if ((int) $infoshop['id_punct'] == 2) {
                $PA['Pshieldnum'] = $infoshop['koll'];
            }
            if ($PA['stil'] >= 0 && $PA['stil'] < 5) {
                //запись эффектов оружия
                if (is_array(json_decode_nice($infoshop['effects']))) {
                    $PweaponEffect = array_merge($PweaponEffect, json_decode_nice($infoshop['effects']));
                }
            }
        }
    }
    $mc->query("INSERT INTO`battle`"
            . "("
            . "`id`,"
            . "`Pname`,"
            . "`Pnamevs`,"
            . "`Pvsname`,"
            . "`level`,"
            . "`Pico`,"
            . "`Pflife`,"
            . "`Plife` ,"
            . "`Ptochnost`,"
            . "`Pblock`,"
            . "`Puron`,"
            . "`Pbronia`,"
            . "`Poglushenie`,"
            . "`Puvorot`,"
            . "`Pweaponico`,"
            . "`Pshieldnum`,"
            . "`Pshieldonoff`,"
            . "`Ptype`,"
            . "`Pvisible`,"
            . "`Mvisible`,"
            . "`Panimation`,"
            . "`Manimation`,"
            . "`Phod`,"
            . "`Phodtime`,"
            . "`Pauto`,"
            . "`PAlwaysEffect`,"
            . "`PeleksirVisible`,"
            . "`PweaponEffect`,"
            . "`PentityEffect`,"
            . "`MentityEffect`,"
            . "`super`,"
            . "`Mid`,"
            . "`location`,"
            . "`type_battle`,"
            . "`battle_id`,"
            . "`battle_start_time`,"
            . "`command`,"
            . "`lost_mob_id`,"
            . "`player_activ`,"
            . "`end_battle`,"
            . "`counter`,"
            . "`stil`,"
            . "`shops_ids`"
            . ")VALUES("
            . "NULL,"
            . "'" . $PA['name'] . "',"
            . "'$type_battle',"
            . "'',"
            . "'" . $PA['level'] . "',"
            . "'" . $PA['side'] . "',"
            . "'" . $arr1['max_health'] . "',"
            . "'" . $PA['temp_health'] . "',"
            . "'" . $arr1['toch'] . "',"
            . "'" . $arr1['block'] . "',"
            . "'" . $arr1['strength'] . "',"
            . "'" . $arr1['bron'] . "',"
            . "'" . $arr1['kd'] . "',"
            . "'" . $arr1['lov'] . "',"
            . "'" . $PA['weaponico'] . "',"
            . "'" . $PA['Pshieldnum'] . "',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'$timeThis',"
            . "'0',"
            . "'[]',"
            . "'1',"
            . "'" . json_encode($PweaponEffect) . "',"
            . "'[]',"
            . "'[]',"
            . "'" . $PA['superudar'] . "',"
            . "'" . $PA['id'] . "',"
            . "'" . $PA['location'] . "',"
            . "'$type',"
            . "'" . $battle_id . "',"
            . "'" . $battle_start_time . "',"
            . "'" . $command . "',"
            . "'0',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'" . $PA['stil'] . "',"
            . "'" . json_encode($shops_ids, JSON_UNESCAPED_UNICODE) . "'"
            . ")");
}

function bot_add($name, $command, $side, $stil, $level, $battle_id, $battle_start_time, $type) {
    global $mc;
    global $timeThis;
    $arr2 = [];
    $shops_ids = [];
    $arr2['weaponico'] = 0;
    $arr2['Pshieldnum'] = 0;
    $arrSuperLevel = [
        "",
        "",
        "22",
        "22",
        "22,222",
        "22,222",
        "22,222",
        "22,222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
    ];
    $arr2['superudar'] = $arrSuperLevel[$level];
    $arr2['max_health'] = 10 + (5 * $level);
    $arr2['strength'] = 1 + (2 * $level) - 2;
    $arr2['toch'] = 8 + (2 * $level) - 2;
    $arr2['lov'] = 3 + (2 * $level) - 2;
    $arr2['kd'] = 2 + (2 * $level) - 2;
    $arr2['block'] = 0 + (2 * $level) - 2;
    $arr2['bron'] = 0 + (2 * $level) - 2;

    $MPweaponEffect = array();
    if ($level > 20) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9, 9, 9,
            10, 10, 10, 10,
            11, 11, 11, 11
        ];
    } else if ($level > 14) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9, 9,
            10, 10, 10,
            11, 11, 11
        ];
    } else if ($level > 11) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9,
            10, 10,
            11, 11
        ];
    } else if ($level > 10) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9,
            10, 10
        ];
    } else if ($level > 9) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9,
        ];
    } else {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8
        ];
    }
    if ($level > 20) {
        $minlvl = 10;
    } else if ($level > 19) {
        $minlvl = 10;
    } else if ($level > 18) {
        $minlvl = 10;
    } else if ($level > 17) {
        $minlvl = 10;
    } else if ($level > 16) {
        $minlvl = 10;
    } else if ($level > 15) {
        $minlvl = 10;
    } else if ($level > 14) {
        $minlvl = 5;
    } else if ($level > 13) {
        $minlvl = 5;
    } else if ($level > 12) {
        $minlvl = 5;
    } else if ($level > 11) {
        $minlvl = 5;
    } else if ($level > 10) {
        $minlvl = 5;
    } else if ($level > 9) {
        $minlvl = 0;
    } else if ($level > 8) {
        $minlvl = 0;
    } else if ($level > 7) {
        $minlvl = 0;
    } else if ($level > 6) {
        $minlvl = 0;
    } else if ($level > 5) {
        $minlvl = 0;
    } else if ($level > 4) {
        $minlvl = 0;
    } else {
        $minlvl = 0;
    }
    //получение случайных вещей бота
    $myrow221 = [];
    for ($i = 0; $i < count($arrcount); $i++) {
        if ($rndc = rand(0, $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '$stil' && `level` <= '$level' && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`)")->num_rows)) {
            $tmpRes0 = $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '$stil' && `level` <= '$level' && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`) ORDER BY `level` ASC LIMIT " . $rndc . ",1");
            if ($tmpRes0->num_rows > 0) {
                $myrow221 [] = $tmpRes0->fetch_array(MYSQLI_ASSOC);
            }
        } else if ($rndc = rand(0, $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '0' && `level` <= '$level' && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`)")->num_rows)) {
            $tmpRes1 = $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '0' && `level` <= '$level'  && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`) ORDER BY `level` ASC LIMIT " . $rndc . ",1");
            if ($tmpRes1->num_rows > 0) {
                $myrow221 [] = $tmpRes1->fetch_array(MYSQLI_ASSOC);
            }
        }
    }
    //перебираем параметры вещей
    for ($i = 0; $i < count($myrow221); $i++) {
        $shops_ids[] = [addslashes($myrow221[$i]['name']), $myrow221[$i]['id']];
        $arr2['max_health'] += $myrow221[$i]['health'];
        $arr2['strength'] += $myrow221[$i]['strength'];
        $arr2['toch'] += $myrow221[$i]['toch'];
        $arr2['lov'] += $myrow221[$i]['lov'];
        $arr2['kd'] += $myrow221[$i]['kd'];
        $arr2['block'] += $myrow221[$i]['block'];
        $arr2['bron'] += $myrow221[$i]['bron'];
        //переводим в иконку оружия
        if ((int) $myrow221[$i]['id_punct'] == 1) {
            if ($myrow221[$i]['id_image'] <= 36 || $myrow221[$i]['id_image'] >= 279 && $myrow221[$i]['id_image'] <= 298) {
                $arr2['weaponico'] = $myrow221[$i]['id_image'];
            } else {
                $arr2['weaponico'] = 0;
            }
        }
        //получаем количество щита
        if ((int) $myrow221[$i]['id_punct'] == 2) {
            $arr2['Pshieldnum'] = $myrow221[$i]['koll'];
        }
        if ($stil >= 0 && $stil < 5) {
            //запись эффектов оружия
            if (is_array(json_decode_nice($myrow221[$i]['effects']))) {
                $MPweaponEffect = array_merge($MPweaponEffect, json_decode_nice($myrow221[$i]['effects']));
            }
        }
    }
    $mc->query("INSERT INTO`battle`"
            . "("
            . "`id`,"
            . "`Pname`,"
            . "`Pnamevs`,"
            . "`Pvsname`,"
            . "`level`,"
            . "`Pico`,"
            . "`Pflife`,"
            . "`Plife` ,"
            . "`Ptochnost`,"
            . "`Pblock`,"
            . "`Puron`,"
            . "`Pbronia`,"
            . "`Poglushenie`,"
            . "`Puvorot`,"
            . "`Pweaponico`,"
            . "`Pshieldnum`,"
            . "`Pshieldonoff`,"
            . "`Ptype`,"
            . "`Pvisible`,"
            . "`Mvisible`,"
            . "`Panimation`,"
            . "`Manimation`,"
            . "`Phod`,"
            . "`Phodtime`,"
            . "`Pauto`,"
            . "`PAlwaysEffect`,"
            . "`PeleksirVisible`,"
            . "`PweaponEffect`,"
            . "`PentityEffect`,"
            . "`MentityEffect`,"
            . "`super`,"
            . "`Mid`,"
            . "`location`,"
            . "`type_battle`,"
            . "`battle_id`,"
            . "`battle_start_time`,"
            . "`command`,"
            . "`lost_mob_id`,"
            . "`player_activ`,"
            . "`end_battle`,"
            . "`counter`,"
            . "`stil`,"
            . "`shops_ids`"
            . ")VALUES("
            . "NULL,"
            . "'$name',"
            . "'',"
            . "'',"
            . "'$level',"
            . "'$side',"
            . "'" . $arr2['max_health'] . "',"
            . "'" . $arr2['max_health'] . "',"
            . "'" . $arr2['toch'] . "',"
            . "'" . $arr2['block'] . "',"
            . "'" . $arr2['strength'] . "',"
            . "'" . $arr2['bron'] . "',"
            . "'" . $arr2['kd'] . "',"
            . "'" . $arr2['lov'] . "',"
            . "'" . $arr2['weaponico'] . "',"
            . "'" . $arr2['Pshieldnum'] . "',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'0',"
            . "'$timeThis',"
            . "'1',"
            . "'[]',"
            . "'1',"
            . "'" . json_encode($MPweaponEffect) . "',"
            . "'[]',"
            . "'[]',"
            . "'" . $arr2['superudar'] . "',"
            . "'-1',"
            . "'0',"
            . "'$type',"
            . "'" . $battle_id . "',"
            . "'" . $battle_start_time . "',"
            . "'" . $command . "',"
            . "'0',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'" . $stil . "',"
            . "'" . json_encode($shops_ids, JSON_UNESCAPED_UNICODE) . "'"
            . ")");
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json);
}
