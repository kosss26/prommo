<?php
require_once ('../system/func.php');
require_once ('../system/dbc.php');

//проверяем что герой не в бою
if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script><?php
    exit(0);
}
//проверяем результаты если есть то перекинем туда чтобы обработало монстров
if ($mc->query("SELECT * FROM `resultbattle` WHERE `id_user`='" . $user['id'] . "' ORDER BY `id` DESC LIMIT 1")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/result.php");</script><?php
    exit(0);
}
//command arr
$arrCommand = ["Шейване", "Нармасцы", "Монстры"];
$arrCommandIco = [
    "<img height=\"15\" src=\"/img/icon/icoevil.png\" width=\"15\" alt=\"\">",
    "<img height=\"15\" src=\"/img/icon/icogood.png\" width=\"15\" alt=\"\">",
    ""
];

//проверяем параметры юсера , есть ли они вообще и если есть то продолжаем
if (isset($user)) {

//если айди боя не прилетел то выведем список боев по лвл и расе 
//определяем команду игрока
    $command = 0;
    if ($user['side'] == 1 || $user['side'] == 0) {
        $command = 0;
    }
    if ($user['side'] == 2 || $user['side'] == 3) {
        $command = 1;
    }
    if (!isset($_GET['battle_id'])) {
//определяем минимальный и максимальный лвл игрока 
        $minlvl = $user['level'] - 2;
        $maxlvl = $user['level'] + 2;
//получаем информацию о текущих боях по уровню и команде атакующих

        $resUserAllCommand = $mc->query("SELECT * FROM `battle` WHERE "
                . "`Pnamevs`!='' AND"
                . " `Pvsname`!='' AND"
                . " `level`>='$minlvl' AND"
                . " `level`<='$maxlvl' AND"
                . " `command`='$command' AND"
                . " `location`='" . $user['location'] . "' AND"
                . " `end_battle`='0' AND"
                . " `type_battle`='0' "
                . " ORDER BY `id` DESC");
        if ($resUserAllCommand->num_rows) {
            $arrUserAllCommand = $resUserAllCommand->fetch_all(MYSQLI_ASSOC);
//выводим список ссылок с айдишниками боев
            ?>
            <center>-Текущие бои-</center>
            <hr style="background-color: #e7cd96"><br>
            <?php
            for ($i = 0; $i < count($arrUserAllCommand); $i++) {
                ?>
                <center>
                    <a onclick="showContent('/hunt/tec.php?battle_id=<?php echo $arrUserAllCommand[$i]['battle_id']; ?>')">
                        <?php if ($arrUserAllCommand[$i]['Pnamevs'] != "") { ?>
                            <?= $arrCommandIco[$arrUserAllCommand[$i]['command']] . " " . $arrUserAllCommand[$i]['Pnamevs']; ?>
                            <?= " vs " ?>
                            <?= $arrCommandIco[$arrUserAllCommand[$i]['command'] ? 0 : 1] . " " . $arrUserAllCommand[$i]['Pvsname']; ?>
                        <?php } ?>
                    </a>
                </center>
                <br>               
                <?php
            }
            ?><br>
            <br>
            <br>
            <br>
            <br>
            <br><?php
        } else {
//или пишем что боев нет
            ?>
            <center>нет боёв</center>
            <?php
        }

//если айди боя указан
    } else if (isset($_GET['battle_id'])) {

//получаем команду героя и противника
        $resUserAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $_GET['battle_id'] . "' AND `command`='$command' ORDER BY `Ruron` DESC");
        $resMobAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $_GET['battle_id'] . "' AND `command`!='$command' ORDER BY `Ruron` DESC");
        if ($resUserAllCommand->num_rows && $resMobAllCommand->num_rows) {
            $arrUserAllCommand = $resUserAllCommand->fetch_all(MYSQLI_ASSOC);
            $arrMobAllCommand = $resMobAllCommand->fetch_all(MYSQLI_ASSOC);

            // Добавим функцию для подсчета общей силы команды
            function getTeamStrength($battleMembers) {
                $totalStrength = 0;
                foreach ($battleMembers as $member) {
                    // Учитываем только живых участников
                    if ($member['Plife'] > 0) {
                        $totalStrength += $member['Plife'];
                    }
                }
                return $totalStrength;
            }

            // Проверяем силу команд
            $allyStrength = getTeamStrength($arrUserAllCommand);
            $enemyStrength = getTeamStrength($arrMobAllCommand);

            // Проверяем уровни участников
            $validLevels = true;
            foreach ($arrMobAllCommand as $enemy) {
                if ($enemy['level'] < $minlvl || $enemy['level'] > $maxlvl) {
                    $validLevels = false;
                    break;
                }
            }

            // Выводим информацию о бое только если уровни подходящие
            if ($validLevels) {
                ?>
                <?php
                for ($i = 0; $i < count($arrUserAllCommand); $i++) {
                    ?>
                    <?php if ($arrUserAllCommand[$i]['Pnamevs'] != "") { ?>
                        <center><?php echo $arrCommandIco[$arrUserAllCommand[0]['command']] . " " . $arrUserAllCommand[$i]['Pnamevs'] . " vs " . $arrCommandIco[$arrMobAllCommand[0]['command']] . " " . $arrUserAllCommand[$i]['Pvsname']; ?></center>
                    <?php } ?>
                <?php } ?>
                <?php
                for ($i = 0; $i < count($arrMobAllCommand); $i++) {
                    ?>
                    <?php if ($arrMobAllCommand[$i]['Pnamevs'] != "") { ?>
                        <center><?php echo $arrCommandIco[$arrMobAllCommand[0]['command']] . " " . $arrMobAllCommand[$i]['Pnamevs'] . " vs " . $arrCommandIco[$arrUserAllCommand[0]['command']] . " " . $arrMobAllCommand[$i]['Pvsname']; ?></center>
                    <?php } ?>
                <?php } ?>
                <hr style="background-color: #e7cd96">
                <center><?php echo $arrCommandIco[$arrUserAllCommand[0]['command']] . " " . $arrCommand[$arrUserAllCommand[0]['command']]; ?></center>
                <table class="table_block2">
                    <?php
                    for ($i = 0; $i < count($arrUserAllCommand); $i++) {
                        ?>
                        <?php if ($arrUserAllCommand[$i]['Mid'] == $user['id']) { ?>
                            <tr>
                                <td style="width: 70%"><b><?= $arrUserAllCommand[$i]['Pname']; ?>[<?= $arrUserAllCommand[$i]['level']; ?>]</b></td>
                                <?php if ($arrUserAllCommand[$i]['Plife'] <= 0) { ?>
                                    <td style="width: 30%"><b>Убит</b></td>
                                <?php } else { ?>
                                    <td style="width: 30%"><b><?= $arrUserAllCommand[$i]['Plife']; ?></b></td>
                                <?php } ?>
                            </tr>   
                        <?php } else { ?>
                            <tr>
                                <td style="width: 70%"><?= $arrUserAllCommand[$i]['Pname']; ?>[<?= $arrUserAllCommand[$i]['level']; ?>]</td>
                                <?php if ($arrUserAllCommand[$i]['Plife'] <= 0) { ?>
                                    <td style="width: 30%">Убит</td>
                                <?php } else { ?>
                                    <td style="width: 30%"><?= $arrUserAllCommand[$i]['Plife']; ?></td>
                                <?php } ?>
                            </tr>  
                        <?php } ?>
                    <?php } ?>
                </table>
                <hr style="background-color: #e7cd96">
                <center><?= $arrCommandIco[$arrMobAllCommand[0]['command']] . " " . $arrCommand[$arrMobAllCommand[0]['command']]; ?></center>
                <table class="table_block2">
                    <?php
                    for ($i = 0; $i < count($arrMobAllCommand); $i++) {
                        ?>
                        <?php if ($arrMobAllCommand[$i]['Mid'] == $user['id']) { ?>
                            <tr>
                                <td style="width: 70%"><b><?= $arrMobAllCommand[$i]['Pname']; ?>[<?= $arrMobAllCommand[$i]['level']; ?>]</b></td>
                                <?php if ($arrMobAllCommand[$i]['Plife'] <= 0) { ?>
                                    <td style="width: 30%">Убит</td>
                                <?php } else { ?>
                                    <td style="width: 30%"><?= $arrMobAllCommand[$i]['Plife']; ?></td>
                                <?php } ?>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td style="width: 70%"><?= $arrMobAllCommand[$i]['Pname']; ?>[<?= $arrMobAllCommand[$i]['level']; ?>]</td>
                                <?php if ($arrMobAllCommand[$i]['Plife'] <= 0) { ?>
                                    <td style="width: 30%">Убит</td>
                                <?php } else { ?>
                                    <td style="width: 30%"><?= $arrMobAllCommand[$i]['Plife']; ?></td>
                                <?php } ?>
                            </tr>  
                        <?php } ?>
                    <?php } ?>
                </table>
                <hr style="background-color: #e7cd96">
                <center>
                    <div class="button_alt_00" onclick="HuntMobBattleConnect('<?= $_GET['battle_id'] ?>')">
                        Вмешаться
                    </div>
                </center>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <?php
            } else {
                ?>
                <center>Уровень противников не подходит</center>
                <?php
            }
        } else {
//или пишем что бой завершен
            ?>
            <center>бой завершен</center>
            <?php
        }
    }
}
$footval = 'tec_hunt';
require_once ('../system/foot/foot.php');
