<?php
require_once ('../../system/func.php');
require_once ('../../system/dbc.php');
if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script>
    <?php
}
//command arr
$arrCommand = ["Шейване", "Нармасцы", "Монстры"];
$arrCommandIco = [
    "<img height=\"15\" src=\"/img/icon/icoevil.png\" width=\"15\" alt=\"\">",
    "<img height=\"15\" src=\"/img/icon/icogood.png\" width=\"15\" alt=\"\">",
    ""
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Текущие бои - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111">
    <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
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
    --secondary-bg: rgba(255,255,255,0.03);
    --item-hover: rgba(255,255,255,0.15);
    --primary-button: #ff8452;
    --primary-button-hover: #ff6a33;
    --alert-color: #ff4c4c;
    --team1-color: #e74c3c;
    --team2-color: #3498db;
}

body {
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100%;
    color: var(--text);
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
}

.current-battles {
    max-width: 600px;
    margin: 15px auto;
    padding: 0 15px;
    animation: fadeIn 0.5s ease-out;
}

.battle-header {
    text-align: center;
    padding: 15px;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    font-size: 18px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.battle-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
    margin: 15px 0;
    border: none;
}

.battle-link {
    display: block;
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 12px 15px;
    margin-bottom: 12px;
    color: var(--text);
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
}

.battle-link:hover {
    background: var(--item-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    color: var(--accent);
}

.battle-team {
    text-align: center;
    font-weight: 600;
    color: var(--accent);
    margin: 15px 0 10px;
    font-size: 16px;
}

.battle-team-evil {
    color: var(--team1-color);
}

.battle-team-good {
    color: var(--team2-color);
}

.battle-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
    background: var(--card-bg);
    border-radius: var(--radius);
    overflow: hidden;
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(8px);
}

.battle-table td {
    padding: 10px 15px;
    border-bottom: 1px solid var(--glass-border);
}

.battle-table tr:last-child td {
    border-bottom: none;
}

.battle-player {
    width: 70%;
}

.battle-health {
    width: 30%;
    text-align: right;
}

.battle-player-dead {
    color: var(--alert-color);
}

.battle-player-self {
    color: var(--accent);
    font-weight: 600;
}

.button-intervene {
    display: inline-block;
    padding: 12px 25px;
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 15px;
    color: var(--text);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border: 1px solid var(--glass-border);
    background: var(--primary-button);
    color: #111;
    text-transform: uppercase;
    text-align: center;
    margin: 15px auto;
}

.button-intervene:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    background: var(--primary-button-hover);
}

.no-battles {
    text-align: center;
    padding: 30px 15px;
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    color: var(--muted);
    margin: 20px 0;
    backdrop-filter: blur(8px);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .current-battles {
        padding: 0 10px;
    }
    
    .battle-header {
        font-size: 16px;
        padding: 12px;
    }
    
    .battle-link {
        padding: 10px;
    }
    
    .battle-table td {
        padding: 8px 12px;
    }
    
    .button-intervene {
        padding: 10px 20px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .battle-header {
        font-size: 14px;
        padding: 10px;
    }
    
    .battle-team {
        font-size: 14px;
    }
    
    .battle-link {
        font-size: 13px;
    }
    
    .battle-table td {
        padding: 6px 10px;
        font-size: 13px;
    }
    
    .button-intervene {
        padding: 8px 16px;
        font-size: 13px;
    }
}
</style>
</head>
<body>

<div class="current-battles">
<?php
//проверяем параметры юсера , есть ли они вообще и если есть то продолжаем
if (isset($user)) {
//если айди боя не прилетел то выведем список боев по лвл и расе 
    if (!isset($_GET['battle_id'])) {
//определяем минимальный и максимальный лвл игрока 
        $minlvl = $user['level'] - 3;
        $maxlvl = $user['level'] + 3;
//получаем информацию о текущих боях по уровню и команде атакующих
        $resUserAllCommand = $mc->query("SELECT * FROM `battle` WHERE "
                . "`Pnamevs`!='' AND"
                . " `Pvsname`!='' AND"
                . " `level`>='$minlvl' AND"
                . " `level`<='$maxlvl' AND"
                . " `end_battle`='0' AND"
                . " `type_battle`='2' "
                . " ORDER BY `id` DESC");
        if ($resUserAllCommand->num_rows) {
            $arrUserAllCommand = $resUserAllCommand->fetch_all(MYSQLI_ASSOC);
//выводим список ссылок с айдишниками боев
            ?>
            <div class="battle-header">
                <i class="fas fa-history"></i> Текущие бои
            </div>
            <div class="battle-divider"></div>
            <?php
            for ($i = 0; $i < count($arrUserAllCommand); $i++) {
                ?>
                <a class="battle-link" onclick="showContent('/huntb/1x1_tec/tec.php?battle_id=<?= $arrUserAllCommand[$i]['battle_id']; ?>')">
                    <?php if ($arrUserAllCommand[$i]['Pnamevs'] != "") { ?>
                        <?= $arrCommandIco[$arrUserAllCommand[$i]['command']] . " " . $arrUserAllCommand[$i]['Pnamevs']; ?>
                        <i class="fas fa-exchange-alt mx-2"></i>
                        <?= $arrCommandIco[$arrUserAllCommand[$i]['command'] ? 0 : 1] . " " . $arrUserAllCommand[$i]['Pvsname']; ?>
                    <?php } ?>
                </a>
                <?php
            }
        } else {
//или пишем что боев нет
            ?>
            <div class="battle-header">
                <i class="fas fa-history"></i> Текущие бои
            </div>
            <div class="no-battles">
                <i class="fas fa-peace fa-2x mb-3"></i><br>
                Нет активных боев в данный момент
            </div>
            <?php
        }
//если айди боя указан
    } else if (isset($_GET['battle_id'])) {
        $arr1 = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $_GET['battle_id'] . "' AND `Pnamevs`!='' LIMIT 1")->fetch_array(MYSQLI_ASSOC);
//получаем команду героя и противника
        $resUserAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $_GET['battle_id'] . "' AND `command`='".$arr1['command']."' ORDER BY `Ruron` DESC");
        $resMobAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $_GET['battle_id'] . "' AND `command`!='".$arr1['command']."' ORDER BY `Ruron` DESC");
        if ($resUserAllCommand->num_rows && $resMobAllCommand->num_rows) {
            $arrUserAllCommand = $resUserAllCommand->fetch_all(MYSQLI_ASSOC);
            $arrMobAllCommand = $resMobAllCommand->fetch_all(MYSQLI_ASSOC);
            ?>
            <div class="battle-header">
                <i class="fas fa-swords"></i> Детали боя
            </div>
            <?php
            for ($i = 0; $i < count($arrUserAllCommand); $i++) {
                ?>
                <?php if ($arrUserAllCommand[$i]['Pnamevs'] != "") { ?>
                    <div class="battle-link">
                        <?= $arrCommandIco[$arrUserAllCommand[0]['command']] . " " . $arrUserAllCommand[$i]['Pnamevs']; ?>
                        <i class="fas fa-exchange-alt mx-2"></i>
                        <?= $arrCommandIco[$arrMobAllCommand[0]['command']] . " " . $arrUserAllCommand[$i]['Pvsname']; ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php
            for ($i = 0; $i < count($arrMobAllCommand); $i++) {
                ?>
                <?php if ($arrMobAllCommand[$i]['Pnamevs'] != "") { ?>
                    <div class="battle-link">
                        <?= $arrCommandIco[$arrMobAllCommand[0]['command']] . " " . $arrMobAllCommand[$i]['Pnamevs']; ?>
                        <i class="fas fa-exchange-alt mx-2"></i>
                        <?= $arrCommandIco[$arrUserAllCommand[0]['command']] . " " . $arrMobAllCommand[$i]['Pvsname']; ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="battle-divider"></div>
        <?php
        if($arrUserAllCommand[0]['command']==1&&$arrMobAllCommand[0]['command']==0) {
            echo_command($arrUserAllCommand);
            echo_command($arrMobAllCommand);
        } else if($arrUserAllCommand[0]['command']==1&&$arrMobAllCommand[0]['command']==2){
            echo_command($arrUserAllCommand);
            echo_command($arrMobAllCommand);
        } else if($arrUserAllCommand[0]['command']==2&&$arrMobAllCommand[0]['command']==0){
            echo_command($arrUserAllCommand);
            echo_command($arrMobAllCommand);
        } else if($arrUserAllCommand[0]['command']==2&&$arrMobAllCommand[0]['command']==1){
            echo_command($arrUserAllCommand);
            echo_command($arrMobAllCommand);
        } else if($arrUserAllCommand[0]['command']==0&&$arrMobAllCommand[0]['command']==1){
            echo_command($arrUserAllCommand);
            echo_command($arrMobAllCommand);
        } else if($arrUserAllCommand[0]['command']==0&&$arrMobAllCommand[0]['command']==2){
            echo_command($arrUserAllCommand);
            echo_command($arrMobAllCommand);
        }?>
            <div class="text-center">
                <div class="button-intervene" onclick="HuntMobBattleConnect('<?= $_GET['battle_id']; ?>')">
                    <i class="fas fa-sign-in-alt"></i> Вмешаться
                </div>
            </div>
            <?php
        } else {
//или пишем что бой завершен
            ?>
            <div class="battle-header">
                <i class="fas fa-swords"></i> Детали боя
            </div>
            <div class="no-battles">
                <i class="fas fa-flag-checkered fa-2x mb-3"></i><br>
                Бой завершен
            </div>
            <?php
        }
    }
}
?>
</div>
<?php
function echo_command($allCommand) {
    global $arrCommandIco;
    global $arrCommand;
    global $user;
    $teamClass = ($allCommand[0]['command'] == 0) ? 'battle-team-evil' : (($allCommand[0]['command'] == 1) ? 'battle-team-good' : '');
    ?>
    <div class="battle-team <?= $teamClass ?>">
        <?php echo $arrCommandIco[$allCommand[0]['command']] . " " . $arrCommand[$allCommand[0]['command']]; ?>
    </div>
    <table class="battle-table">
        <?php
        for ($i = 0; $i < count($allCommand); $i++) {
            $isCurrentPlayer = ($allCommand[$i]['Mid'] == $user['id']);
            $isPlayerDead = ($allCommand[$i]['Plife'] <= 0);
            ?>
            <tr>
                <td class="battle-player <?= $isCurrentPlayer ? 'battle-player-self' : '' ?>">
                    <?= $allCommand[$i]['Pname']; ?> [<?= $allCommand[$i]['level']; ?>]
                </td>
                <td class="battle-health <?= $isPlayerDead ? 'battle-player-dead' : '' ?>">
                    <?= $isPlayerDead ? 'Убит' : $allCommand[$i]['Plife']; ?>
                </td>
            </tr>   
        <?php } ?>
    </table>
    <div class="battle-divider"></div>
    <?php
}
$footval = 'tec_huntb';
require_once ('../../system/foot/foot.php');
?>
