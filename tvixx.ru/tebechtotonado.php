<?php

require_once ('system/func.php');

$megaArr = $mc->query("SELECT * FROM `shop` ")->fetch_all(MYSQLI_ASSOC);
// 
for ($i = 0; $i < count($megaArr); $i++) {
    $BattleFlag = 0;
    if ($megaArr[$i]['block'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['health'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['toch'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['strength'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['lov'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['kd'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['bron'] != 0) {
        $BattleFlag = 1;
    } else if ($megaArr[$i]['effects'] != "[]" && $megaArr[$i]['id_punct'] != 9) {
        $BattleFlag = 1;
    }
    if ($BattleFlag == 1) {
        $mc->query("UPDATE `userbag` SET `BattleFlag` = '1' WHERE `id_shop` = '" . $megaArr[$i]['id'] . "'");
        $mc->query("UPDATE `shop` SET `BattleFlag` = '1' WHERE `id` = '" . $megaArr[$i]['id'] . "' ");
    }
}
echo " ";
