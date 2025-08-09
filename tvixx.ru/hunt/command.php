<?php
require '../system/func.php';
require '../system/dbc.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//получаем информацию о текущем бое героя
if (isset($user) && $UserArr = $mc->query("SELECT * FROM `battle` WHERE `Mid` = '" . $user['id'] . "' && `Ptype`='0'&& `Plife`>'0' &&`player_activ`='1' &&`end_battle`='0'")->fetch_array(MYSQLI_ASSOC)) {
    //command arr
    $arrCommand = ["Шейване", "Нармасцы", "Монстры"];
    $arrCommandIco = [
        "<img height=\"15\" src=\"/img/icon/icoevil.png\" width=\"15\" alt=\"\">",
        "<img height=\"15\" src=\"/img/icon/icogood.png\" width=\"15\" alt=\"\">",
        ""
    ];
    $arr1 = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $UserArr['battle_id'] . "' AND `Pnamevs`!='' LIMIT 1")->fetch_array(MYSQLI_ASSOC);
    $arr2 = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $UserArr['battle_id'] . "'ORDER BY `Ruron` DESC")->fetch_all(MYSQLI_ASSOC);
//получаем команду героя и противника
    $resUserAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $UserArr['battle_id'] . "' AND `command`='" . $arr1['command'] . "' ORDER BY `Ruron` DESC");
    $resMobAllCommand = $mc->query("SELECT * FROM `battle` WHERE `battle_id`='" . $UserArr['battle_id'] . "' AND `command`!='" . $arr1['command'] . "' ORDER BY `Ruron` DESC");
    if ($resUserAllCommand->num_rows && $resMobAllCommand->num_rows) {
        $arrUserAllCommand = $resUserAllCommand->fetch_all(MYSQLI_ASSOC);
        $arrMobAllCommand = $resMobAllCommand->fetch_all(MYSQLI_ASSOC);
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
  --team1-color: #e74c3c;
  --team2-color: #3498db;
  --monster-color: #8e44ad;
  --positive: #2ecc71;
  --negative: #e74c3c;
  --shadow: 0 8px 24px rgba(0,0,0,0.35);
}

body {
  font-family: 'Inter', Arial, sans-serif;
  color: var(--text);
  background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
  margin: 0;
  padding: 15px;
}

.command-wrapper {
  width: 100%;
  max-width: 600px;
  margin: auto;
  padding: clamp(8px, 2vw, 18px);
}

.battle-header {
  text-align: center;
  margin-bottom: 20px;
  font-weight: 700;
  font-size: clamp(16px, 3vw, 22px);
  color: var(--accent);
}

.vs-container {
  background: var(--card-bg);
  border-radius: var(--radius);
  border: 1px solid var(--glass-border);
  padding: 15px;
  text-align: center;
  margin-bottom: 20px;
  box-shadow: var(--shadow);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

.team-card {
  background: var(--card-bg);
  border-radius: var(--radius);
  border: 1px solid var(--glass-border);
  overflow: hidden;
  margin-bottom: 20px;
  box-shadow: var(--shadow);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

.team-header {
  background: linear-gradient(90deg, rgba(0,0,0,0.2), transparent);
  padding: 10px 15px;
  font-weight: 600;
  text-align: center;
  border-bottom: 1px solid var(--glass-border);
}

.team-sheyvan .team-header {
  color: var(--team1-color);
  border-left: 4px solid var(--team1-color);
}

.team-narmasc .team-header {
  color: var(--team2-color);
  border-left: 4px solid var(--team2-color);
}

.team-monster .team-header {
  color: var(--monster-color);
  border-left: 4px solid var(--monster-color);
}

.team-table {
  width: 100%;
  border-collapse: collapse;
}

.team-table tr {
  transition: background 0.3s ease;
}

.team-table tr:hover {
  background: var(--glass-bg);
}

.team-table td {
  padding: 12px 15px;
  border-bottom: 1px solid rgba(255,255,255,0.05);
}

.team-table tr:last-child td {
  border-bottom: none;
}

.player-name {
  width: 70%;
  font-size: 14px;
  display: flex;
  align-items: center;
}

.player-level {
  opacity: 0.7;
  font-size: 12px;
  margin-left: 5px;
}

.player-health {
  width: 30%;
  text-align: right;
  font-weight: 600;
}

.player-dead {
  color: var(--negative);
}

.current-player {
  font-weight: bold;
  position: relative;
}

.current-player::before {
  content: '►';
  position: absolute;
  left: -15px;
  color: var(--accent);
}

.divider {
  height: 1px;
  background: linear-gradient(to right, transparent, var(--glass-border), transparent);
  margin: 15px 0;
}

@media (max-width: 480px) {
  .team-table td {
    padding: 10px;
  }
  
  .player-name {
    font-size: 13px;
  }
}
</style>

<div class="command-wrapper">
    <!-- Заголовок битвы -->
    <div class="vs-container">
        <?php for ($i = 0; $i < count($arrUserAllCommand); $i++) { ?>
            <?php if ($arrUserAllCommand[$i]['Pnamevs'] != "") { ?>
                <div class="battle-header">
                    <?= $arrUserAllCommand[$i]['Pvsname'] != "" && $arrUserAllCommand[0]['command'] < 2 ? $arrCommandIco[$arrUserAllCommand[0]['command']] : ""; ?>
                    <?= $arrUserAllCommand[$i]['Pnamevs']; ?>
                    <?= $arrUserAllCommand[$i]['Pvsname'] != "" ? " vs " : ""; ?>
                    <?= $arrUserAllCommand[$i]['Pvsname'] != "" && $arrMobAllCommand[0]['command'] < 2 ? $arrCommandIco[$arrMobAllCommand[0]['command']] : ""; ?>
                    <?= $arrUserAllCommand[$i]['Pvsname']; ?>
                </div>
            <?php } ?>
        <?php } ?>
        
        <?php for ($i = 0; $i < count($arrMobAllCommand); $i++) { ?>
            <?php if ($arrMobAllCommand[$i]['Pnamevs'] != "") { ?>
                <div class="battle-header">
                    <?= $arrMobAllCommand[$i]['Pvsname'] != "" && $arrMobAllCommand[0]['command'] < 2 ? $arrCommandIco[$arrUserAllCommand[0]['command']] : ""; ?>
                    <?= $arrMobAllCommand[$i]['Pnamevs']; ?>
                    <?= $arrMobAllCommand[$i]['Pvsname'] != "" ? " vs " : ""; ?>
                    <?= $arrMobAllCommand[$i]['Pvsname'] != "" && $arrUserAllCommand[0]['command'] < 2 ? $arrCommandIco[$arrUserAllCommand[0]['command']] : ""; ?>
                    <?= $arrMobAllCommand[$i]['Pvsname']; ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- Карточки команд -->
    <?php
    if($arr1['Pnamevs']=="выживание"){
        echo_vjv_styled($arr2, $user['id']);
    }else if($arr1['Pnamevs']=="Отбор (Земля)"){
        echo_vjv_styled($arr2, $user['id']);
    }else if($arr1['Pnamevs']=="Земля"){
        echo_zemlya_styled($arrUserAllCommand, $user['id']);
        echo_zemlya_styled($arrMobAllCommand, $user['id']);
    }else if ($arrUserAllCommand[0]['command'] == 1 && $arrMobAllCommand[0]['command'] == 0) {
        echo_command_styled($arrUserAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'narmasc');
        echo_command_styled($arrMobAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'sheyvan');
    } else if ($arrUserAllCommand[0]['command'] == 1 && $arrMobAllCommand[0]['command'] == 2) {
        echo_command_styled($arrUserAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'narmasc');
        echo_command_styled($arrMobAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'monster');
    } else if ($arrUserAllCommand[0]['command'] == 2 && $arrMobAllCommand[0]['command'] == 0) {
        echo_command_styled($arrUserAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'monster');
        echo_command_styled($arrMobAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'sheyvan');
    } else if ($arrUserAllCommand[0]['command'] == 2 && $arrMobAllCommand[0]['command'] == 1) {
        echo_command_styled($arrUserAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'monster');
        echo_command_styled($arrMobAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'narmasc');
    } else if ($arrUserAllCommand[0]['command'] == 0 && $arrMobAllCommand[0]['command'] == 1) {
        echo_command_styled($arrUserAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'sheyvan');
        echo_command_styled($arrMobAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'narmasc');
    } else if ($arrUserAllCommand[0]['command'] == 0 && $arrMobAllCommand[0]['command'] == 2) {
        echo_command_styled($arrUserAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'sheyvan');
        echo_command_styled($arrMobAllCommand, $arrCommand, $arrCommandIco, $user['id'], 'monster');
    }
    ?>
</div>

<script>MyLib.footName = "command";</script>

    <?php
        $footval = 'command';
        require_once ('../system/foot/foot.php');
    } else {
        ?>
        <script>showContent("/main.php");</script>
        <?php
        exit(0);
    }
} else {
    ?>
    <script>showContent("/main.php");</script>
    <?php
    exit(0);
}

function echo_command_styled($allCommand, $arrCommand, $arrCommandIco, $user_id, $team_class) {
    ?>
    <div class="team-card team-<?= $team_class ?>">
        <div class="team-header">
            <?= $arrCommandIco[$allCommand[0]['command']] ?> <?= $arrCommand[$allCommand[0]['command']]; ?>
        </div>
        <table class="team-table">
            <?php
            for ($i = 0; $i < count($allCommand); $i++) {
                $is_current_player = ($allCommand[$i]['Mid'] == $user_id);
                ?>
                <tr>
                    <td class="player-name <?= $is_current_player ? 'current-player' : '' ?>">
                        <?= $allCommand[$i]['Pname']; ?><span class="player-level">[<?= $allCommand[$i]['level']; ?>]</span>
                    </td>
                    <?php if ($allCommand[$i]['Plife'] <= 0) { ?>
                        <td class="player-health player-dead"><?= $is_current_player ? '<strong>Убит</strong>' : 'Убит' ?></td>
                    <?php } else { ?>
                        <td class="player-health"><?= $is_current_player ? '<strong>' . $allCommand[$i]['Plife'] . '</strong>' : $allCommand[$i]['Plife'] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}

function echo_vjv_styled($allCommand, $user_id) {
    ?>
    <div class="team-card">
        <div class="team-header">
            Выживание
        </div>
        <table class="team-table">
            <?php
            for ($i = 0; $i < count($allCommand); $i++) {
                $is_current_player = ($allCommand[$i]['Mid'] == $user_id);
                ?>
                <tr>
                    <td class="player-name <?= $is_current_player ? 'current-player' : '' ?>">
                        <?= $allCommand[$i]['Pname']; ?><span class="player-level">[<?= $allCommand[$i]['level']; ?>]</span>
                    </td>
                    <?php if ($allCommand[$i]['Plife'] <= 0) { ?>
                        <td class="player-health player-dead"><?= $is_current_player ? '<strong>Убит</strong>' : 'Убит' ?></td>
                    <?php } else { ?>
                        <td class="player-health"><?= $is_current_player ? '<strong>' . $allCommand[$i]['Plife'] . '</strong>' : $allCommand[$i]['Plife'] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}

function echo_zemlya_styled($allCommand, $user_id) {
    global $mc;
    
    $nameclan = $mc->query("SELECT `name` FROM `clan` WHERE `id` = ". $allCommand[0]['command'] ."")->fetch_array(MYSQLI_ASSOC);
    ?>
    <div class="team-card">
        <div class="team-header">
            <?= $nameclan["name"]; ?>
        </div>
        <table class="team-table">
            <?php
            for ($i = 0; $i < count($allCommand); $i++) {
                $is_current_player = ($allCommand[$i]['Mid'] == $user_id);
                ?>
                <tr>
                    <td class="player-name <?= $is_current_player ? 'current-player' : '' ?>">
                        <?= $allCommand[$i]['Pname']; ?><span class="player-level">[<?= $allCommand[$i]['level']; ?>]</span>
                    </td>
                    <?php if ($allCommand[$i]['Plife'] <= 0) { ?>
                        <td class="player-health player-dead"><?= $is_current_player ? '<strong>Убит</strong>' : 'Убит' ?></td>
                    <?php } else { ?>
                        <td class="player-health"><?= $is_current_player ? '<strong>' . $allCommand[$i]['Plife'] . '</strong>' : $allCommand[$i]['Plife'] ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}
