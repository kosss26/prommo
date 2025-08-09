<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';

require_once ('../system/func.php');
require_once ('../system/dbc.php');


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$result = $mc->query("SELECT * FROM `resultbattle` WHERE `id_user`='" . $user['id'] . "' ORDER BY `id` DESC LIMIT 1");
if ($result->num_rows) {
    $resarr = $result->fetch_array(MYSQLI_ASSOC);
    $mc->query("DELETE FROM `resultbattle` WHERE `resultbattle`.`id`='" . $resarr['id'] . "'");
} else {
    ?><script>/*nextshowcontemt*/showContent("/main.php");</script><?php
    exit(0);
}
$loose = (int) $resarr['loose']; //0-win 1-loose
//[
//0[0 0 name,uron,exp,...],
//1[1 0 name,uron,exp,...],...
//]
$ARRwinner = json_decode($resarr['winner']);
$ARRlooser = json_decode($resarr['looser']);
if ($resarr['type'] == 3) {
    $ARRwinner = array_merge(json_decode($resarr['winner']), json_decode($resarr['looser']));
    $ARRlooser = array_merge(json_decode($resarr['winner']), json_decode($resarr['looser']));
}
///Трофеи при победе
$newmoney = 0;
$newplata = 0;
$zolo = 0;
$med = 0;
$serebro = 0;
$plata = 0;
$goldPredmArr = [];
$platPredmArr = [];
$thingPredmArr = [];
$questPredmArr = [];
$goldPredmArrIds = [];
$platPredmArrIds = [];
$thingPredmArrIds = [];
$questPredmArrIds = [];
$moneys = 0;
$expres = 0;
$ourUron = 0;
$ourOpit = 0;
$ourGold = 0;
$ourPlatina = 0;
$ourArrOpit = [];
$user0Exp = 0;
$user0Uron = 0;
$bagCount = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_punct` < '10'")->num_rows;
$botplat = 0;
$botgold = 0;
$PremProc = 0;
$PremProcDrop = 0;
$marauder = 0;
$marauderDrop = 0;
$eyepieces = 0;
$eyepiecesDrop = 0;

///ПРЕМИУМ
if ($user['prem'] == '1') {
    $PremProc = 25;
    $PremProcDrop = 10;
}
//друг мародера id 507
if ($mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_shop`='507' AND `dress` = '1'")) {
    $marauder = 14;
    $marauderDrop = 7;
}
//окуляры id 506
if ($mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_shop`='506' AND `dress` = '1'")) {
    $eyepieces = 10;
    $eyepiecesDrop = 5;
}
if ($loose == 1 || $resarr['type'] == 3) {
    $plusreit = 0;
} else {
    $plusreit = 1;
}



//получение общего урона
for ($itr = 0; $itr < count($ARRwinner); $itr++) {
    $ourUron += $ARRwinner[$itr][1];
}
//получаем все взятые кв игрока и их базовые части
$quests_count_res = $mc->query("SELECT * FROM `quests_count` WHERE (`id_quests`,`count`) IN (SELECT `id_quests`,`count` FROM `quests_users` WHERE `id_user` ='" . $user['id'] . "')");
$quests_counts = [];
if ($quests_count_res->num_rows > 0) {
    $quests_counts = $quests_count_res->fetch_all(MYSQLI_ASSOC);
}
//id моб,[[id вещи,%]],[золото],[платина]
//[[2,[[778,100]],[0,0],[0,0]]]
//получение общего опыта золота платины
//$ARRlooser=[["name","uron",xz,"id","type"]]type = 0-user 1-monster
for ($itr = 0; $itr < count($ARRlooser); $itr++) {
    //если это бот
    if ($ARRlooser[$itr][4] == 1 && $ARRlooser[$itr][3] > 0) {

        $infmob = $mc->query("SELECT * FROM `hunt` WHERE `id`='" . $ARRlooser[$itr][3] . "'")->fetch_array(MYSQLI_ASSOC);

        if ($infmob['ids_shopG'] != "" && $infmob['ids_shopG_rand'] != "") {
            $tmparrG_rand = genRandArrVal(json_decode($infmob['ids_shopG_rand']), $infmob['ids_shopG_num']);
            $tmparrG_rand = is_array($tmparrG_rand) ? $tmparrG_rand : [];
            $tmparrG = json_decode($infmob['ids_shopG']);
            $tmparrG = is_array($tmparrG) ? $tmparrG : [];
            if (count($tmparrG_rand) > 0) {
                $tmparrG = array_merge($tmparrG, $tmparrG_rand);
            }
            if (count($tmparrG) > 0) {
                //перебераем массив вещей [[778,100],[id,%шанс]]
                for ($i = 0; $i < count($tmparrG); $i++) {
                    //получаем количество имеющихся вещей
                    $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $tmparrG[$i][0] . "'");
                    $shopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $tmparrG[$i][0] . "'");
                    if ($shopRes->num_rows > 0) {
                        $shopThis = $shopRes->fetch_array(MYSQLI_ASSOC);
                        if ($user['level'] < $shopThis['drop_min_level'] || $user['level'] > $shopThis['drop_max_level']) {
                            continue;
                        }
                        if (!in_array($tmparrG[$i][0], $goldPredmArrIds)) {
                            if ($countBagDropRes->num_rows > 0) {
                                $countBagDrop = $countBagDropRes->fetch_array(MYSQLI_ASSOC);
                                //определяем добавить в дроп или нет
                                if ($shopThis['max_hc'] > 0 && $countBagDropRes->num_rows < $shopThis['max_hc']) {
                                    $goldPredmArrIds[] = $tmparrG[$i][0];
                                    $goldPredmArr[] = [$tmparrG[$i][0], $tmparrG[$i][1]];
                                } else if ($shopThis['max_hc'] < 1) {
                                    $goldPredmArrIds[] = $tmparrG[$i][0];
                                    $goldPredmArr[] = [$tmparrG[$i][0], $tmparrG[$i][1]];
                                }
                            } else if ($shopRes->num_rows > 0) {
                                $goldPredmArrIds[] = $tmparrG[$i][0];
                                $goldPredmArr[] = [$tmparrG[$i][0], $tmparrG[$i][1]];
                            }
                        }
                    }
                }
            }
        }
        if ($infmob['ids_shopP'] != "" && $infmob['ids_shopP_rand'] != "") {
            $tmparrP_rand = genRandArrVal(json_decode($infmob['ids_shopP_rand']), $infmob['ids_shopP_num']);
            $tmparrP_rand = is_array($tmparrP_rand) ? $tmparrP_rand : [];
            $tmparrP = json_decode($infmob['ids_shopP']);
            $tmparrP = is_array($tmparrP) ? $tmparrP : [];
            if (count($tmparrP_rand) > 0) {
                $tmparrP = array_merge($tmparrP, $tmparrP_rand);
            }
            if (count($tmparrP) > 0) {
                //перебераем массив вещей [[778,100],[id,%шанс]]
                for ($i = 0; $i < count($tmparrP); $i++) {
                    //получаем количество имеющихся вещей
                    $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $tmparrP[$i][0] . "'");
                    $shopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $tmparrP[$i][0] . "'");
                    if ($shopRes->num_rows > 0) {
                        $shopThis = $shopRes->fetch_array(MYSQLI_ASSOC);
                        if ($user['level'] < $shopThis['drop_min_level'] || $user['level'] > $shopThis['drop_max_level']) {
                            continue;
                        }
                        if (!in_array($tmparrP[$i][0], $platPredmArrIds)) {
                            if ($countBagDropRes->num_rows > 0) {
                                $countBagDrop = $countBagDropRes->fetch_array(MYSQLI_ASSOC);
                                //определяем добавить в дроп или нет
                                if ($shopThis['max_hc'] > 0 && $countBagDropRes->num_rows < $shopThis['max_hc']) {
                                    $platPredmArrIds[] = $tmparrP[$i][0];
                                    $platPredmArr[] = [$tmparrP[$i][0], $tmparrP[$i][1]];
                                } else if ($shopThis['max_hc'] < 1) {
                                    $platPredmArrIds[] = $tmparrP[$i][0];
                                    $platPredmArr[] = [$tmparrP[$i][0], $tmparrP[$i][1]];
                                }
                            } else if ($shopRes->num_rows > 0) {
                                $platPredmArrIds[] = $tmparrP[$i][0];
                                $platPredmArr[] = [$tmparrP[$i][0], $tmparrP[$i][1]];
                            }
                        }
                    }
                }
            }
        }
        if ($infmob['ids_shopT'] != "" && $infmob['ids_shopT_rand'] != "") {
            $tmparrT_rand = genRandArrVal(json_decode($infmob['ids_shopT_rand']), $infmob['ids_shopT_num']);
            $tmparrT_rand = is_array($tmparrT_rand) ? $tmparrT_rand : [];
            $tmparrT = json_decode($infmob['ids_shopT']);
            $tmparrT = is_array($tmparrT) ? $tmparrT : [];
            if (count($tmparrT_rand) > 0) {
                $tmparrT = array_merge($tmparrT, $tmparrT_rand);
            }
            if (count($tmparrT) > 0) {
                //[[id вещи при которой выпадет ,id вещи которая выпадет,количество боев до выпадения ],[703,890,500]]
                for ($i = 0; $i < count($tmparrT); $i++) {
                    //проверяем наличие предмета для дропа
                    if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $tmparrT[$i][0] . "'")->num_rows > 0) {
                        //получаем количество имеющихся вещей
                        $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $tmparrT[$i][1] . "'");
                        $shopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $tmparrT[$i][1] . "'");
                        if ($shopRes->num_rows > 0) {
                            $shopThis = $shopRes->fetch_array(MYSQLI_ASSOC);
                            if ($user['level'] < $shopThis['drop_min_level'] || $user['level'] > $shopThis['drop_max_level']) {
                                continue;
                            }
                            if (!in_array($tmparrT[$i][1], $thingPredmArrIds)) {
                                if ($countBagDropRes->num_rows > 0) {
                                    $countBagDrop = $countBagDropRes->fetch_array(MYSQLI_ASSOC);
                                    //определяем добавить в дроп или нет
                                    if ($shopThis['max_hc'] > 0 && $countBagDropRes->num_rows < $shopThis['max_hc']) {
                                        $thingPredmArrIds[] = $tmparrT[$i][1];
                                        $thingPredmArr[] = [$tmparrT[$i][1], $tmparrT[$i][2]];
                                    } else if ($shopThis['max_hc'] < 1) {
                                        $thingPredmArrIds[] = $tmparrT[$i][1];
                                        $thingPredmArr[] = [$tmparrT[$i][1], $tmparrT[$i][2]];
                                    }
                                } else if ($shopRes->num_rows > 0) {
                                    $thingPredmArrIds[] = $tmparrT[$i][1];
                                    $thingPredmArr[] = [$tmparrT[$i][1], $tmparrT[$i][2]];
                                }
                            }
                        }
                    }
                }
            }
        }
        //для квестодропа
        //создадим массив дропа по квесту
        for ($i = 0; $i < count($quests_counts); $i++) {
            //получаем дроп лист шмоток
            $arrTemp0 = json_decode(urldecode($quests_counts[$i]['mob_idandvesh']));
            for ($i1 = 0; $i1 < count($arrTemp0); $i1++) {
                //сравниваем айдишник монстра с айдишником в кв
                if ($ARRlooser[$itr][3] == $arrTemp0[$i1][0]) {
                    $botgold += rand($arrTemp0[$i1][2][0], $arrTemp0[$i1][2][1]);
                    $botplat += rand($arrTemp0[$i1][3][0], $arrTemp0[$i1][3][1]);
                    //перебераем массив вещей [[778,100],[id,%шанс]]
                    for ($i2 = 0; $i2 < count($arrTemp0[$i1][1]); $i2++) {
                        //получаем количество имеющихся вещей
                        $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp0[$i1][1][$i2][0] . "'");
                        $shopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $arrTemp0[$i1][1][$i2][0] . "'");
                        if ($shopRes->num_rows > 0) {
                            $shopThis = $shopRes->fetch_array(MYSQLI_ASSOC);
                            if ($user['level'] < $shopThis['drop_min_level'] || $user['level'] > $shopThis['drop_max_level']) {
                                continue;
                            }
                            if (!in_array($arrTemp0[$i1][1][$i2][0], $questPredmArrIds)) {
                                if ($countBagDropRes->num_rows > 0) {
                                    $countBagDrop = $countBagDropRes->fetch_array(MYSQLI_ASSOC);
                                    //определяем добавить в дроп или нет
                                    if ($shopThis['max_hc'] > 0 && $countBagDropRes->num_rows < $shopThis['max_hc']) {
                                        $questPredmArrIds[] = $arrTemp0[$i1][1][$i2][0];
                                        $questPredmArr[] = [$arrTemp0[$i1][1][$i2][0], $arrTemp0[$i1][1][$i2][1]];
                                    } else if ($shopThis['max_hc'] < 1) {
                                        $questPredmArrIds[] = $arrTemp0[$i1][1][$i2][0];
                                        $questPredmArr[] = [$arrTemp0[$i1][1][$i2][0], $arrTemp0[$i1][1][$i2][1]];
                                    }
                                } else if ($shopRes->num_rows > 0) {
                                    $questPredmArrIds[] = $arrTemp0[$i1][1][$i2][0];
                                    $questPredmArr[] = [$arrTemp0[$i1][1][$i2][0], $arrTemp0[$i1][1][$i2][1]];
                                }
                            }
                        }
                    }
                }
            }
        }
        $ourOpit += $infmob['exp'];
        $ourGold += rand($infmob['minmoney'], $infmob['maxmoney']);
        $ourPlatina += rand($infmob['minplatina'], $infmob['maxplatina']);
    } else {
        $ourOpit += $ARRlooser[$itr][2];
    }

    if ($resarr['type'] == 3) {
        $ourGold += 10000;
    }
    if ($resarr['type'] == 5) {
        $ourGold += 20000;
    }
}

//создание массива опыта игроков
for ($itr = 0; $itr < count($ARRwinner); $itr++) {
    if (!empty($ARRwinner[$itr][1])) {
        $ourArrOpit[$itr] = round(($ourOpit / 100) * ($ARRwinner[$itr][1] / ($ourUron / 100)));
        if ($ARRwinner[$itr][3] == $user['id']) {
            $user0Uron = $ARRwinner[$itr][1];
            //получение голда игрока
            $user0Exp = round(($ourOpit / 100) * ($ARRwinner[$itr][1] / ($ourUron / 100)));
            $user0Exp = round($user0Exp * (1 + ($PremProc / 100)));
            $moneys = round(($ourGold / 100) * ($ARRwinner[$itr][1] / ($ourUron / 100)));
            $moneys = round($moneys * (1 + (($PremProc + $marauder) / 100)));
            $plata = round(($ourPlatina / 100) * ($ARRwinner[$itr][1] / ($ourUron / 100)));
            $plata = round($plata * (1 + (($PremProc + $eyepieces) / 100)));
        }
    }
}
$moneys += $botgold;
$plata += $botplat;

function dropRand($a) {
    if ($a < 1) {
        $a = 1;
    }
    $a = 100000 / $a;
    $a = round($a);
    $b = rand(1, 100000);
    $c = $a + $b;
    if ($c > 100000) {
        return 1;
    } else {
        return 0;
    }
}

function genRandArrVal($array, $a) {
    if ($a > 0 && count($array) > 0) {
        $newarr = [];
        if ($a > count($array)) {
            $a = count($array);
        }
        $keys = array_rand($array, $a);
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        for ($i = 0; $i < count($keys); $i++) {
            $newarr[] = $array[$keys[$i]];
        }
        return $newarr;
    } else {
        return $array;
    }
}

/* function dropRand($a) {
  $a*=1000;
  $a=round($a);
  $b = rand(1, 100000);
  $c=$a+$b;
  if ($c > 100000) {
  return 1;
  } else {
  return 0;
  }
  } */
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

:root {
  --bg-grad-start: #111;
  --bg-grad-end: #1a1a1a;
  --accent: #f5c15d;
  --accent-2: #ff8452;
  --card-bg: rgba(255,255,255,0.05);
  --glass-bg: rgba(255,255,255,0.08);
  --glass-border: rgba(255,255,255,0.12);
  --text: #fff;
  --muted: #c2c2c2;
  --radius: 16px;
  --positive: #2ecc71;
  --negative: #e74c3c;
  --warning: #f39c12;
  --shadow: 0 8px 24px rgba(0,0,0,0.35);
}

body {
  font-family: 'Inter', Arial, sans-serif;
  color: var(--text);
  background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
  margin: 0;
  padding: 15px;
}

.result-wrapper {
  width: 100%;
  max-width: 600px;
  margin: auto;
  padding: clamp(8px, 2vw, 18px);
}

.result-header {
  text-align: center;
  margin-bottom: 20px;
  font-weight: 700;
  font-size: clamp(16px, 3vw, 22px);
  color: var(--accent);
}

.result-card {
  background: var(--card-bg);
  border-radius: var(--radius);
  border: 1px solid var(--glass-border);
  overflow: hidden;
  margin-bottom: 20px;
  box-shadow: var(--shadow);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

.section-header {
  background: linear-gradient(90deg, rgba(0,0,0,0.2), transparent);
  padding: 10px 15px;
  font-weight: 600;
  color: var(--accent);
  border-bottom: 1px solid var(--glass-border);
}

.winners-header {
  color: var(--positive);
  border-left: 4px solid var(--positive);
}

.losers-header {
  color: var(--negative);
  border-left: 4px solid var(--negative);
}

.loot-header {
  color: #6ab04c;
  border-left: 4px solid #6ab04c;
}

.result-table {
  width: 100%;
  border-collapse: collapse;
}

.result-table-head {
  background: rgba(0,0,0,0.2);
  color: var(--muted);
}

.result-table tr {
  transition: background 0.3s ease;
}

.result-table tr:hover {
  background: var(--glass-bg);
}

.result-table td, .result-table th {
  padding: 10px 12px;
  text-align: left;
  border-bottom: 1px solid rgba(255,255,255,0.05);
}

.result-table tr:last-child td {
  border-bottom: none;
}

.player-name {
  max-width: 100%;
  display: flex;
  align-items: center;
  gap: 6px;
}

.current-player {
  font-weight: bold;
  position: relative;
}

.rating-controls {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.rating-btn {
  display: inline-block;
  padding: 0 5px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.rating-btn:hover {
  color: var(--accent);
}

.rating-minus {
  color: var(--negative);
}

.rating-plus {
  color: var(--positive);
}

.rating-active {
  transform: scale(1.2);
  font-weight: bold;
}

.loot-item {
  padding: 8px 15px;
  border-bottom: 1px solid rgba(255,255,255,0.05);
}

.loot-item:last-child {
  border-bottom: none;
}

.loot-resources {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  padding: 10px 15px;
  align-items: center;
  color: #2ecc71;
  font-weight: bold;
}

.resource-item {
  display: flex;
  align-items: center;
  gap: 5px;
}

.resource-icon {
  width: 16px;
  height: 16px;
}

.divider {
  height: 1px;
  background: linear-gradient(to right, transparent, var(--glass-border), transparent);
  margin: 10px 0;
}

.btn-next {
  display: inline-block;
  background: linear-gradient(135deg, var(--accent), var(--accent-2));
  color: var(--bg-grad-start);
  border: none;
  border-radius: 20px;
  padding: 10px 25px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.btn-next:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}

.btn-next:active {
  transform: translateY(1px);
}

/* Адаптивность */
@media (max-width: 480px) {
  .result-table td, .result-table th {
    padding: 8px;
    font-size: 13px;
  }
}
</style>

<div class="result-wrapper">
    <div class="result-header">Результаты боя</div>
    
    <?php if ($resarr['type'] < 3 || $resarr['type'] > 4) { ?>
        <!-- Победители -->
        <div class="result-card">
            <div class="section-header winners-header">Победители</div>
            <table class="result-table">
                <thead class="result-table-head">
                    <tr>
                        <th>Имя</th>
                        <th style="width: 60px; text-align: center;">Урон</th>
                        <th style="width: 60px; text-align: center;">Опыт</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($iwin = 0; $iwin < count($ARRwinner); $iwin++) {
                        ?>
                        <tr>
                            <?php if ($ARRwinner[$iwin][3] == $user['id'] && $ARRwinner[$iwin][4] == 0) { ?>
                                <td class="player-name current-player"><?= urldecode($ARRwinner[$iwin][0]); ?></td>
                                <td style="width: 60px; text-align: center;"><?= $ARRwinner[$iwin][1]; ?></td>
                                <td style="width: 60px; text-align: center;"><?= $user0Exp; ?></td>
                                <?php $Usmunreid = $ARRwinner[$iwin][1]; ?>
                            <?php } else { ?>
                                <td class="player-name">
                                    <?php if ($ARRwinner[$iwin][3] != -1 && $ARRwinner[$iwin][4] != 1) { ?>
                                        <?php
                                        if ($user['side'] == 0 || $user['side'] == 1) {
                                            $rasa = 0;
                                        } else {
                                            $rasa = 1;
                                        }
                                        $user2 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $ARRwinner[$iwin][3] . "'")->fetch_array(MYSQLI_ASSOC);
                                        $rep_arr = $mc->query("SELECT * FROM `rep_list` WHERE `user1_id` = '" . $user['id'] . "' && `user2_id` = '" . $user2['id'] . "' ")->fetch_array(MYSQLI_ASSOC);
                                        $rep_num = 0;
                                        if (isset($rep_arr)) {
                                            $rep_num = $rep_arr['num'];
                                        }
                                        if ($user2['side'] == 0 || $user2['side'] == 1) {
                                            $rasa2 = 0;
                                        } else {
                                            $rasa2 = 1;
                                        }
                                        ?>
                                        <?php if ($rasa == $rasa2) { ?>
                                            <div class="rating-controls">
                                                <span onclick="rep(<?= $ARRwinner[$iwin][3]; ?>,<?= $iwin; ?>, 0, 'w');" class="rating-btn rating-minus repw<?= $iwin; ?> minusw<?= $iwin; ?> <?= $rep_num == -1 ? 'rating-active' : ''; ?>">-</span>
                                                <?= urldecode($ARRwinner[$iwin][0]); ?>
                                                <span onclick="rep(<?= $ARRwinner[$iwin][3]; ?>,<?= $iwin; ?>, 1, 'w');" class="rating-btn rating-plus repw<?= $iwin; ?> plusw<?= $iwin; ?> <?= $rep_num == 1 ? 'rating-active' : ''; ?>">+</span>
                                            </div>
                                        <?php } else { ?>
                                            <?= urldecode($ARRwinner[$iwin][0]); ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?= urldecode($ARRwinner[$iwin][0]); ?>
                                    <?php } ?>
                                </td>
                                <td style="width: 60px; text-align: center;"><?= $ARRwinner[$iwin][1]; ?></td>
                                <td style="width: 60px; text-align: center;"><?php
                                    if (!empty($ourArrOpit[$iwin])) {
                                        echo $ourArrOpit[$iwin] += $ARRwinner[$iwin][2];
                                    } else {
                                        echo $ARRwinner[$iwin][2];
                                    }
                                    ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Побежденные -->
        <div class="result-card">
            <div class="section-header losers-header">Побежденные</div>
            <table class="result-table">
                <thead class="result-table-head">
                    <tr>
                        <th>Имя</th>
                        <th style="width: 60px; text-align: center;">Урон</th>
                        <th style="width: 60px; text-align: center;">Опыт</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($iwin = 0; $iwin < count($ARRlooser); $iwin++) {
                        ?>
                        <tr>
                            <?php if ($ARRlooser[$iwin][3] == $user['id'] && $ARRlooser[$iwin][4] == 0) { ?>
                                <td class="player-name current-player"><?= $user['name']; ?></td>
                                <td style="width: 60px; text-align: center;"><?= $ARRlooser[$iwin][1]; ?></td>
                                <td style="width: 60px; text-align: center;">0</td>
                                <?php $Usmunreid = $ARRlooser[$iwin][1]; ?>
                            <?php } else { ?>
                                <td class="player-name">
                                    <?php if ($ARRlooser[$iwin][3] != -1 && $ARRlooser[$iwin][4] != 1) { ?>
                                        <?php
                                        if ($user['side'] == 0 || $user['side'] == 1) {
                                            $rasa = 0;
                                        } else {
                                            $rasa = 1;
                                        }
                                        $user2 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $ARRlooser[$iwin][3] . "'")->fetch_array(MYSQLI_ASSOC);
                                        $rep_arr = $mc->query("SELECT * FROM `rep_list` WHERE `user1_id` = '" . $user['id'] . "' && `user2_id` = '" . $user2['id'] . "' ")->fetch_array(MYSQLI_ASSOC);
                                        $rep_num = 0;
                                        if (isset($rep_arr)) {
                                            $rep_num = $rep_arr['num'];
                                        }
                                        if ($user2['side'] == 0 || $user2['side'] == 1) {
                                            $rasa2 = 0;
                                        } else {
                                            $rasa2 = 1;
                                        }
                                        ?>
                                        <?php if ($rasa == $rasa2) { ?>
                                            <div class="rating-controls">
                                                <span onclick="rep(<?= $ARRlooser[$iwin][3]; ?>,<?= $iwin; ?>, 0, 'l');" class="rating-btn rating-minus repl<?= $iwin; ?> minusl<?= $iwin; ?> <?= $rep_num == -1 ? 'rating-active' : ''; ?>">-</span>
                                                <?= urldecode($ARRlooser[$iwin][0]); ?>
                                                <span onclick="rep(<?= $ARRlooser[$iwin][3]; ?>,<?= $iwin; ?>, 1, 'l');" class="rating-btn rating-plus repl<?= $iwin; ?> plusl<?= $iwin; ?> <?= $rep_num == 1 ? 'rating-active' : ''; ?>">+</span>
                                            </div>
                                        <?php } else { ?>
                                            <?= urldecode($ARRlooser[$iwin][0]); ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?= urldecode($ARRlooser[$iwin][0]); ?>
                                    <?php } ?>
                                </td>
                                <td style="width: 60px; text-align: center;"><?= $ARRlooser[$iwin][1]; ?></td>
                                <td style="width: 60px; text-align: center;">0</td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } elseif ($resarr['type'] == 3) { ?>
        <!-- Турнир выживания -->
        <div class="result-card">
            <div class="section-header">Участники турнира</div>
            <table class="result-table">
                <thead class="result-table-head">
                    <tr>
                        <th>Имя</th>
                        <th style="width: 60px; text-align: center;">Урон</th>
                        <th style="width: 60px; text-align: center;">Опыт</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ARRtoor = $ARRwinner;
                    for ($iwin = 0; $iwin < count($ARRtoor); $iwin++) {
                        ?>
                        <tr>
                            <?php if ($ARRtoor[$iwin][3] == $user['id'] && $ARRtoor[$iwin][4] == 0) { ?>
                                <td class="player-name current-player"><?= $user['name']; ?></td>
                                <td style="width: 60px; text-align: center;"><?= $ARRtoor[$iwin][1]; ?></td>
                                <td style="width: 60px; text-align: center;"><?= $user0Exp; ?></td>
                                <?php $Usmunreid = $ARRtoor[$iwin][1]; ?>
                            <?php } else { ?>
                                <td class="player-name">
                                    <?php if ($ARRtoor[$iwin][3] != -1 && $ARRtoor[$iwin][4] != 1) { ?>
                                        <?php
                                        if ($user['side'] == 0 || $user['side'] == 1) {
                                            $rasa = 0;
                                        } else {
                                            $rasa = 1;
                                        }
                                        $user2 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $ARRtoor[$iwin][3] . "'")->fetch_array(MYSQLI_ASSOC);
                                        $rep_arr = $mc->query("SELECT * FROM `rep_list` WHERE `user1_id` = '" . $user['id'] . "' && `user2_id` = '" . $user2['id'] . "' ")->fetch_array(MYSQLI_ASSOC);
                                        $rep_num = 0;
                                        if (isset($rep_arr)) {
                                            $rep_num = $rep_arr['num'];
                                        }
                                        if ($user2['side'] == 0 || $user2['side'] == 1) {
                                            $rasa2 = 0;
                                        } else {
                                            $rasa2 = 1;
                                        }
                                        ?>
                                        <?php if ($rasa == $rasa2) { ?>
                                            <div class="rating-controls">
                                                <span onclick="rep(<?= $ARRtoor[$iwin][3]; ?>,<?= $iwin; ?>, 0, 'w');" class="rating-btn rating-minus repw<?= $iwin; ?> minusw<?= $iwin; ?> <?= $rep_num == -1 ? 'rating-active' : ''; ?>">-</span>
                                                <?= urldecode($ARRtoor[$iwin][0]); ?>
                                                <span onclick="rep(<?= $ARRtoor[$iwin][3]; ?>,<?= $iwin; ?>, 1, 'w');" class="rating-btn rating-plus repw<?= $iwin; ?> plusw<?= $iwin; ?> <?= $rep_num == 1 ? 'rating-active' : ''; ?>">+</span>
                                            </div>
                                        <?php } else { ?>
                                            <?= urldecode($ARRtoor[$iwin][0]); ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?= urldecode($ARRtoor[$iwin][0]); ?>
                                    <?php } ?>
                                </td>
                                <td style="width: 60px; text-align: center;"><?= $ARRtoor[$iwin][1]; ?></td>
                                <td style="width: 60px; text-align: center;"><?php
                                    if (!empty($ourArrOpit[$iwin])) {
                                        echo $ourArrOpit[$iwin] += $ARRtoor[$iwin][2];
                                    } else {
                                        echo $ARRtoor[$iwin][2];
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <!-- Трофеи -->
    <div class="result-card">
        <div class="section-header loot-header">Трофеи</div>
        <div class="result-content">
            <?php
            //проверяем победил или выживание
            if ($loose == 0 || $resarr['type'] == 3) {
                //если это не выживание за зол или платину и не земли то снять 1 выноса
                if ($resarr['type'] != 3 && $resarr['type'] != 4 && $resarr['type'] != 7 && $resarr['type'] != 8) {
                    if ($user['vinos_t'] > 0 && $user['vinos_t'] <= 5) {
                        $user['vinos_t'] --;
                        $mc->query("UPDATE `users` SET "
                                . "`vinos_t`='" . $user['vinos_t'] . "',"
                                . "`vinos_rt`='" . (time() + 60) . "'"
                                . " WHERE `id`='" . $user['id'] . "'");
                        to_msg_main("Внимание критический уровень выносливости ! При достижении отметки в 0 начнет уменьшаться максимальный уровень выносливости !");
                    } elseif ($user['vinos_t'] <= 0) {
                        if ($user['vinos_m'] > 0) {
                            $user['vinos_m'] --;
                        } else {
                            $user['vinos_m'] = 0;
                        }
                        $mc->query("UPDATE `users` SET "
                                . "`vinos_t`='0',"
                                . "`vinos_m`='" . $user['vinos_m'] . "',"
                                . "`vinos_rt`='" . (time() + 60) . "'"
                                . " WHERE `id`='" . $user['id'] . "'");
                        to_msg_main("Максимальный уровень выносливости был понижен !");
                    } else {
                        $user['vinos_t'] --;
                        $mc->query("UPDATE `users` SET "
                                . "`vinos_t`='" . $user['vinos_t'] . "',"
                                . "`vinos_rt`='" . (time() + 60) . "'"
                                . " WHERE `id`='" . $user['id'] . "'");
                    }
                    //или если это выживание и первый игрок юзер и опыт набрал больше 0 то показать поздравления
                } elseif ($resarr['type'] >= 3 && $resarr['type'] <= 4 && $ARRtoor[0][3] == $user['id']) {
                    $mc->query("UPDATE `users` SET "
                            . "`slava`=`slava`+'" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.03)) . " ',"
                            . "`tur_reit`=`tur_reit`+'" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.03)) . "',"
                            . "`vinos_m`=`vinos_m`+'1',"
                            . "`vinos_rt`='" . (time() + 60) . "'"
                            . " WHERE `id`='" . $user['id'] . "'");
                    $plusreit += ceil(($user0Uron / 100) * (1 + $PremProc * 0.03));
                    to_msg_main("За победу в турнире вы получаете славу +" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.03)) . " , рейтинг турнира +" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.03)) . " и повышение максимальной выносливости +1 !");
                    //или просто поздравить 
                } elseif ($resarr['type'] >= 3 && $resarr['type'] <= 4 && $user0Uron > 100) {
                    $mc->query("UPDATE `users` SET "
                            . "`slava`=`slava`+'" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.02)) . " ',"
                            . "`tur_reit`=`tur_reit`+'" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.02)) . "'"
                            . " WHERE `id`='" . $user['id'] . "'");
                    $plusreit += ceil(($user0Uron / 100) * (1 + $PremProc * 0.02));
                    to_msg_main("За участие в турнире вы получаете славу +" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.02)) . " и рейтинг турнира +" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.02)));
                    //стенка на стенку
                } elseif ($resarr['type'] >= 5 && $resarr['type'] <= 6 && $ARRtoor[0][3] == $user['id']) {
                    $mc->query("UPDATE `users` SET "
                            . "`slava`=`slava`+'" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.03)) . " ',"
                            . "`tur_reit`=`tur_reit`+'" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.03)) . "'"
                            . " WHERE `id`='" . $user['id'] . "'");
                    $plusreit += ceil(($user0Uron / 100) * (1 + $PremProc * 0.03));
                    to_msg_main("За победу в турнире вы получаете славу +" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.03)) . " , рейтинг турнира +" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.03)) . " !");
                    //или просто поздравить 
                } elseif ($resarr['type'] >= 5 && $resarr['type'] <= 6 && $user0Uron > 100) {
                    $mc->query("UPDATE `users` SET "
                            . "`slava`=`slava`+'" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.02)) . " ',"
                            . "`tur_reit`=`tur_reit`+'" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.02)) . "'"
                            . " WHERE `id`='" . $user['id'] . "'");
                    $plusreit += ceil(($user0Uron / 100) * (1 + $PremProc * 0.02));
                    to_msg_main("За участие в турнире вы получаете славу +" . ceil(($user0Uron / 200) * (1 + $PremProc * 0.02)) . " и рейтинг турнира +" . ceil(($user0Uron / 100) * (1 + $PremProc * 0.02)));
                    //
                } else if ($resarr['type'] == 7 && $ARRwinner[0][3] == $user['id']) {
                    //Если клан прошел отбор
                    //Ищем победителя в бою и его локу подготавливаемся к 8 часовому бою и удаляем бой
                    $Huntbtype7 = $mc->query("SELECT * FROM `huntb_list` WHERE `type` = 7 AND `user_id` = " . $user['id'] . "")->fetch_array(MYSQLI_ASSOC);
                    $nextZahvattime = mktime(19, 50, 0, date("m"), date("d"), date("Y"));
                    $mc->query("UPDATE `location` SET `idNextClan` = '" . $user['id_clan'] . "', `nextZahvat`='" . $nextZahvattime . "' WHERE `id` = " . $Huntbtype7['location'] . "");
                    $mc->query("DELETE FROM `huntb_list` WHERE `type` = 7 AND `location` = " . $Huntbtype7['location'] . "");

                    //а теперь отсылаем всему клану инфу о том, что они идиоты
                    $Nameloca = $mc->query("SELECT `Name` FROM `location` WHERE `id`=" . $Huntbtype7['location'] . "")->fetch_array(MYSQLI_ASSOC);
                    $usersinclan = $mc->query("SELECT `id` FROM `users` WHERE `id_clan` = " . $user['id_clan'] . "")->fetch_all(MYSQLI_ASSOC);
                    for ($i = 0; $i < count($usersinclan); $i++) {
                        $smsclan = "Ваш клан добился права на бой за " . $Nameloca['Name'] . "! К 8-ми часам по Московскому времени собирайте всех из Вашего клана и идите в бой на локацию";
                        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usersinclan[$i]['id'] . "','" . $smsclan . "','" . time() . "','msg')");
                    }
                    // to_msg_main("Соберай братву для клана, мудак!");
                } else if ($resarr['type'] == 8 && $ARRwinner[0][3] == $user['id']) {

                    $Huntbtype7 = $mc->query("SELECT * FROM `huntb_list` WHERE `type` = 8 AND `user_id` = " . $user['id'] . "")->fetch_array(MYSQLI_ASSOC);
                    $nexttime2 = mktime(17, 50, 0, date("m"), date("d") + 2, date("Y"));


                    $mc->query("UPDATE `location` SET `idClan` = '" . $user['id_clan'] . "', `idNextClan` = '0', `nextZahvat`='" . $nexttime2 . "' WHERE `id` = " . $Huntbtype7['location'] . "");
                    $mc->query("DELETE FROM `huntb_list` WHERE `location` ='" . $Huntbtype7['location'] . "' AND `type`=8");

                    //а теперь отсылаем всему клану инфу о том, что они идиоты
                    $Nameloca = $mc->query("SELECT `Name` FROM `location` WHERE `id`=" . $Huntbtype7['location'] . "")->fetch_array(MYSQLI_ASSOC);
                    $usersinclan = $mc->query("SELECT `id` FROM `users` WHERE `id_clan` = " . $user['id_clan'] . "")->fetch_all(MYSQLI_ASSOC);
                    for ($v = 0; $v < count($usersinclan); $v++) {
                        $smsclan = "Ваш клан захватил " . $Nameloca['Name'] . "! ";
                        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usersinclan[$v]['id'] . "','" . $smsclan . "','" . time() . "','msg')");
                    }
                }
                $mc->query("UPDATE `users` SET `reit`=`reit`+'$plusreit' WHERE `id`='" . $user['id'] . "'");
                $mc->query("UPDATE `clan` SET `reit`=`reit`+'$plusreit' WHERE `id`='" . $user['id_clan'] . "'");
                //шмот с мародером
                if (is_array($goldPredmArr)) {
                    for ($ig0 = 0; $ig0 < count($goldPredmArr); $ig0++) {
                        if (dropRand($goldPredmArr[$ig0][1] * ($marauderDrop / 100)) == 1) {
                            if ($As = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $goldPredmArr[$ig0][0] . "' ")->fetch_array(MYSQLI_ASSOC)) {
                                if ($As['id_punct'] < 12) {
                                    if ($As['id_punct'] < 10) {
                                        if ($bagCount < $user['max_bag_count']) {
                                            addItemToBag($As['id']);
                                            echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                        } else {
                                            echo "<div style='padding-left:10px;'>Недостаточно места</div>";
                                        }
                                    } else {
                                        addItemToBag($As['id']);
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                    }
                                } else if ($As['id_punct'] > 11) {
                                    addItemToBag($As['id']);
                                    if ($user['access'] > 1) {
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . " <font style='color:red;'>Скрытые</font></div>";
                                    }
                                }
                            }
                        }
                    }
                }

                //шмот с окулярами
                if (is_array($platPredmArr)) {
                    for ($ip0 = 0; $ip0 < count($platPredmArr); $ip0++) {
                        if (dropRand($platPredmArr[$ip0][1] * ($eyepiecesDrop / 100)) == 1) {
                            if ($As = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $platPredmArr[$ip0][0] . "' ")->fetch_array(MYSQLI_ASSOC)) {
                                if ($As['id_punct'] < 12) {
                                    if ($As['id_punct'] < 10) {
                                        if ($bagCount < $user['max_bag_count']) {
                                            addItemToBag($As['id']);
                                            echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                        } else {
                                            echo "<div style='padding-left:10px;'>Недостаточно места</div>";
                                        }
                                    } else {
                                        addItemToBag($As['id']);
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                    }
                                } else if ($As['id_punct'] > 11) {
                                    addItemToBag($As['id']);
                                    if ($user['access'] > 1) {
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . " <font style='color:red;'>Скрытые</font></div>";
                                    }
                                }
                            }
                        }
                    }
                }

                //шмот падающий при наличии другого шмота
                if (is_array($thingPredmArr)) {
                    for ($it0 = 0; $it0 < count($thingPredmArr); $it0++) {
                        if (dropRand($thingPredmArr[$it0][1] * ($eyepiecesDrop / 100)) == 1) {
                            if ($As = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $thingPredmArr[$it0][0] . "' ")->fetch_array(MYSQLI_ASSOC)) {
                                if ($As['id_punct'] < 12) {
                                    if ($As['id_punct'] < 10) {
                                        if ($bagCount < $user['max_bag_count']) {
                                            addItemToBag($As['id']);
                                            echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                        } else {
                                            echo "<div style='padding-left:10px;'>Недостаточно места</div>";
                                        }
                                    } else {
                                        addItemToBag($As['id']);
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                    }
                                } else if ($As['id_punct'] > 11) {
                                    addItemToBag($As['id']);
                                    if ($user['access'] > 1) {
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . " <font style='color:red;'>Скрытые</font></div>";
                                    }
                                }
                            }
                        }
                    }
                }

                //шмот по квесту
                if (is_array($questPredmArr)) {
                    for ($ip0 = 0; $ip0 < count($questPredmArr); $ip0++) {
                        if (dropRand($questPredmArr[$ip0][1]) == 1) {
                            if ($As = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $questPredmArr[$ip0][0] . "' ")->fetch_array(MYSQLI_ASSOC)) {
                                if ($As['id_punct'] < 12) {
                                    if ($As['id_punct'] < 10) {
                                        if ($bagCount < $user['max_bag_count']) {
                                            addItemToBag($As['id']);
                                            echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                        } else {
                                            echo "<div class='loot-item' style='color:var(--warning);'>Недостаточно места</div>";
                                        }
                                    } else {
                                        addItemToBag($As['id']);
                                        echo "<div class='loot-item'>" . $As['name'] . "</div>";
                                    }
                                } else if ($As['id_punct'] > 11) {
                                    addItemToBag($As['id']);
                                    if ($user['access'] > 1) {
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . " <font style='color:red;'>Скрытые</font></div>";
                                    }
                                }
                            }
                        }
                    }
                }
                $mesto = 33;
                for ($ipd = 0; $ipd < count($ARRwinner); $ipd++) {
                    if ($ARRwinner[$ipd][3] == $user['id']) {
                        $mesto = $ipd + 1;
                        break;
                    }
                }
                if ($resarr['type'] > 0) {
                    //платинка
                    $arr50 = [
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                        2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                        2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                        2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                        2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
                        3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3,
                        3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3,
                        3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3,
                        3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3,
                        4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
                        4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
                        4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
                        5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5,
                        5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5,
                        10, 10, 10, 10, 10, 10, 10, 10,
                        20, 20, 20, 30, 30, 60, 80, 90
                    ];
                    if ($PremProcDrop > 0) {
                        if (dropRand(150)) {
                            $plata = $arr50[array_rand($arr50)];
                            $mesto = 99;
                        }
                    } else {
                        if (dropRand(1000)) {
                            $plata = $arr50[array_rand($arr50)];
                            $mesto = 99;
                        }
                    }
                }
                if ($PremProcDrop > 0 && $resarr['type'] > 0) {

                    //статы егеря
                    $arr00 = [1381, 1382, 1383, 1384, 1385, 1386, 1387];
                    //спец вещи
                    $arr01 = [851, 1373, 1374, 1375, 1376, 1377, 1378, 1379, 1380];
                    //зелья из верхнего
                    $arr02 = [1031, 1032, 1034, 1035, 1036, 1047, 1107, 1108, 1109, 1110, 1111, 1242, 1279, 1113, 1114, 1115, 1116, 1366, 1117, 1367, 1118, 1251, 1119, 1368, 1282, 1369, 1283, 1285, 1257, 1288, 1295, 1258, 1370, 1297, 1298, 1299];
                    //зелья из стагорода
                    $arr03 = [1000, 1001, 1013, 1024, 1026, 1031, 1032, 1034, 1035, 1036, 1047, 1107, 1108, 1109, 1110, 1111, 1242, 1113, 1114, 1115, 1116, 1118, 1251, 1119, 1257, 1258];
                    $arr04 = [];
                    $arr05 = [];



                    if ($user['level'] > 16 && $mesto == 1 && dropRand(150)) {
                        $arr04[] = $arr00[array_rand($arr00)];
                        $mesto = 99;
                    }
                    if (($mesto == 1 || $mesto == 2) && dropRand(150)) {
                        $arr04[] = $arr01[array_rand($arr01)];
                        $mesto = 99;
                    }
                    if (($mesto == 1 || $mesto == 2 || $mesto == 3) && dropRand(150)) {
                        $arr04[] = $arr02[array_rand($arr02)];
                        $mesto = 99;
                    }
                    if (($mesto == 2 || $mesto == 3 || $mesto == 4) && dropRand(150)) {
                        $arr04[] = $arr03[array_rand($arr03)];
                        $mesto = 99;
                    }
                    $premPredmArrIds = [];
                    for ($i = 0; $i < count($arr04); $i++) {
                        //получаем количество имеющихся вещей
                        $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arr04[$i] . "'");
                        $shopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $arr04[$i] . "'");
                        if ($shopRes->num_rows > 0) {
                            $shopThis = $shopRes->fetch_array(MYSQLI_ASSOC);
                            if ($user['level'] < $shopThis['drop_min_level'] || $user['level'] > $shopThis['drop_max_level']) {
                                continue;
                            }
                            if (!in_array($arr04[$i], $premPredmArrIds)) {
                                if ($countBagDropRes->num_rows > 0) {
                                    $countBagDrop = $countBagDropRes->fetch_array(MYSQLI_ASSOC);
                                    //определяем добавить в дроп или нет
                                    if ($shopThis['max_hc'] > 0 && $countBagDropRes->num_rows < $shopThis['max_hc']) {
                                        $premPredmArrIds[] = $arr04[$i];
                                        $arr05[] = $arr04[$i];
                                    } else if ($shopThis['max_hc'] < 1) {
                                        $premPredmArrIds[] = $arr04[$i];
                                        $arr05[] = $arr04[$i];
                                    }
                                } else if ($shopRes->num_rows > 0) {
                                    $premPredmArrIds[] = $arr04[$i];
                                    $arr05[] = $arr04[$i];
                                }
                            }
                        }
                    }
                    for ($ith = 0; $ith < count($arr05); $ith++) {
                        if ($As = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $arr05[$ith] . "' ")->fetch_array(MYSQLI_ASSOC)) {
                            if ($As['id_punct'] < 12) {
                                if ($As['id_punct'] < 10) {
                                    if ($bagCount < $user['max_bag_count']) {
                                        addItemToBag($As['id']);
                                        echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                    } else {
                                        echo "<div style='padding-left:10px;'>Недостаточно места</div>";
                                    }
                                } else {
                                    addItemToBag($As['id']);
                                    echo "<div style='padding-left:10px;'>" . $As['name'] . "</div>";
                                }
                            } else if ($As['id_punct'] > 11) {
                                addItemToBag($As['id']);
                                if ($user['access'] > 1) {
                                    echo "<div style='padding-left:10px;'>" . $As['name'] . " <font style='color:red;'>Скрытые</font></div>";
                                }
                            }
                        }
                    }
                }
                if ($resarr['type'] == 1 || $resarr['type'] == 2) {
                    $moneys += rand(10, 10000);
                }
                $zolo = money($moneys, "zoloto");
                $med = money($moneys, "med");
                $serebro = money($moneys, "serebro");
                ?>
                <div class="loot-resources">
                    <?php if ($plata != 0) { ?>
                        <div class="resource-item">
                            <img class="resource-icon" src="/images/icons/plata.png">
                            <span><?= $plata; ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($zolo != 0) { ?>
                        <div class="resource-item">
                            <img class="resource-icon" src="/images/icons/zoloto.png">
                            <span><?= $zolo; ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($serebro != 0) { ?>
                        <div class="resource-item">
                            <img class="resource-icon" src="/images/icons/serebro.png">
                            <span><?= $serebro; ?></span>
                        </div>
                    <?php } ?>
                    <?php if ($med != 0) { ?>
                        <div class="resource-item">
                            <img class="resource-icon" src="/images/icons/med.png">
                            <span><?= $med; ?></span>
                        </div>
                    <?php } ?>
                </div>
                <?php
                // Остальной код логики без изменений
                $userexp = $user0Exp + $user['exp'];
                $usermoney = $moneys + $user['money'];
                $userplatinum = $plata + $user['platinum'];
                if ($moneys > 100000 || $plata > 0) {
                    $uortext = "";
                    if ($moneys > 100000) {
                        $uortext .= " , юники +" . $moneys;
                    }
                    if ($plata > 0) {
                        $uortext .= " , ПЛАТИНА +" . $plata;
                    }
                    $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> дроп </font><font color='#0033cc'>" . $uortext . "</font>");
                    $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Логи дропа боя','','4', '" . $chatmsg . " " . date('H:i:s') . "','','','' )");
                }
                $mc->query("UPDATE `users` SET `exp`='$userexp',`money`='$usermoney',`platinum`='$userplatinum' WHERE `id`='" . $user['id'] . "'");

                $bon_exp = ceil($user0Exp * 0.05);
                $bon_slava = 0;
                $bon_clan_reit = 0;
                $ref = $user['ref'];
                if ($ref > 0) {
                    if ($resarr['type'] == 3 || $resarr['type'] == 4) {
                        $bon_slava = ceil(((($user0Exp / 2) / 10) * 0.05) * (1 + ($PremProc / 100) * 2));
                        $bon_clan_reit = ceil(($user0Exp / 10) * 0.05);
                    }

                    if ($mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num` = '$ref'")->num_rows > 0) {
                        //обновить
                        $mc->query("UPDATE `ref_bonus` SET "
                                . "`exp`=`exp`+'$bon_exp',"
                                . "`slava`=`slava`+'$bon_slava',"
                                . "`clan_reit`=`clan_reit`+'$bon_clan_reit'"
                                . "WHERE `ref_num` = '$ref'");
                    } else {
                        //или создать если записи бонусов нет
                        $mc->query("INSERT INTO `ref_bonus` ("
                                . "`id`,"
                                . "`ref_num`,"
                                . "`exp`,"
                                . "`slava`,"
                                . "`clan_reit`"
                                . ") VALUES ("
                                . "NULL,"
                                . "'$ref',"
                                . "'$bon_exp',"
                                . "'$bon_slava',"
                                . "'$bon_clan_reit'"
                                . ")");
                    }
                }
            } else {
                //портим шмотки
                $mc->query("UPDATE `userbag` SET `iznos` = `iznos`-'1' WHERE `id_user` = '" . $user['id'] . "' && `id_punct` < '9' && `dress` = '1' && `iznos` > '0'");
            }
            ?>
        </div>
    </div>

    <!-- Кнопка продолжения -->
    <div style="text-align: center;">
        <button onclick="showContent('/main.php')" class="btn-next">Далее</button>
    </div>
</div>

<script>
    function rep(uid, num, type, pre) {
        try {
            $("body").prepend("<img class='loading' src='" + imgLoading.src + "' alt='loading'>" +
                    "<div class='linefooter sizeFooterH'></div>");
            $.ajax({
                type: "POST",
                url: "/php/rep_set.php",
                dataType: "json",
                data: {
                    uid: uid,
                    type: type,
                    Login: getCookie('login'),
                    Password: getCookie('password')
                },
                success: function (data) {
                    if (data.type == -1) {
                        $(".rep" + pre + num).removeClass('rating-active');
                        $(".minus" + pre + num).addClass('rating-active');
                    }
                    if (data.type == 0) {
                        $(".rep" + pre + num).removeClass('rating-active');
                    }
                    if (data.type == 1) {
                        $(".rep" + pre + num).removeClass('rating-active');
                        $(".plus" + pre + num).addClass('rating-active');
                    }
                    $(".loading").remove();
                }
            });
        } catch (e) {
        }
    }
</script>

<?php
// ... existing code ...

//***выдача вещей герою 
function addItemToBag($itemId) {
    global $mc;
    global $user;
    //смотрим на новую вещь
    $infoShopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='$itemId'");
    if ($infoShopRes->num_rows > 0) {
        $infoshop1 = $infoShopRes->fetch_array(MYSQLI_ASSOC);
        //дата истечения в unix
        if ($infoshop1['time_s'] > 0) {
            $time_the_lapse = $infoshop1['time_s'] + time();
        } else {
            $time_the_lapse = 0;
        }
        
        $mc->query("INSERT INTO `userbag`("
                . "`id_user`,"
                . " `id_shop`,"
                . " `id_punct`,"
                . " `dress`,"
                . " `iznos`,"
                . " `time_end`,"
                . " `id_quests`,"
                . " `koll`,"
                . " `max_hc`,"
                . " `stil`,"
                . " `BattleFlag`"
                . ") VALUES ("
                . "'" . $user['id'] . "',"
                . "'" . $infoshop1['id'] . "',"
                . "'" . $infoshop1['id_punct'] . "',"
                . "'0',"
                . "'" . $infoshop1['iznos'] . "',"
                . "'$time_the_lapse',"
                . "'" . $infoshop1['id_quests'] . "',"
                . "'" . $infoshop1['koll'] . "',"
                . "'" . $infoshop1['max_hc'] . "',"
                . "'" . $infoshop1['stil'] . "',"
                . "'" . $infoshop1['BattleFlag'] . "'"
                . ")");
        $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> получил </font><font color='#0033cc'>" . $infoshop1['name'] . "</font>");
        $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'Логи дропа боя','','4', '" . $chatmsg . " " . date('H:i:s') . "','','','' )");
        if ($infoshop1['chatSend']) {
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','0', '" . $chatmsg . "','','','' )");
            $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','1', '" . $chatmsg . "','','','' )");
        }
    }
}

function to_msg_main($text) {
    global $mc;
    global $user;
    $mc->query("INSERT INTO `msg`("
            . " `id`,"
            . " `id_user`,"
            . " `message`,"
            . " `date`,"
            . " `type`"
            . ")VALUES("
            . "NULL,"
            . "'" . $user['id'] . "',"
            . "'" . urldecode($text) . "',"
            . "'" . time() . "',"
            . "'msg'"
            . ")");
}

$footval = 'huntresult';
require_once '../system/foot/foot.php';
