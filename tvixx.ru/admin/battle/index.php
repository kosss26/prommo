<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
if (isset($_GET['end_battle']) && $_GET['end_battle'] > 0 && isset($_GET['end_flag']) && $_GET['end_flag'] == 1) {
    $mc->query("DELETE FROM `battle` WHERE `battle_id` = '" . $_GET['end_battle'] . "'");
}
if (isset($_GET['end_battle']) && $_GET['end_battle'] > 0 && !isset($_GET['end_flag'])) {
    message_yn(
            "Ты точно хочешь завершить этот бой ?", "/admin/battle/index.php?end_battle=" . $_GET['end_battle'] . "&end_flag=1", "/admin/battle/index.php?end_battle=" . $_GET['end_battle'] . "&end_flag=0", "Да я смелый", "Не хочу пиздюлей"
    );
}

if (isset($_GET['this_battle'])) {
    $this_resBattle = $mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'");
    if ($this_resBattle->num_rows > 0) {
        $this_Battle = $this_resBattle->fetch_array(MYSQLI_ASSOC);
        $_GET['view_battle'] = $this_Battle['battle_id'];
    }
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

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
        --panel-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        --success-gradient: linear-gradient(135deg, #2ecc71, #27ae60);
        --danger-gradient: linear-gradient(135deg, #e74c3c, #c0392b);
        --primary-gradient: linear-gradient(135deg, var(--accent), var(--accent-2));
        --battle-stat-bg: rgba(240, 230, 140, 0.15);
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .battle-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .battle-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 18px;
        padding: 10px;
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .battle-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .battle-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.3);
    }
    
    .battle-info {
        padding: 15px;
        display: flex;
        flex-direction: column;
    }
    
    .battle-delete {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--danger-gradient);
        border-radius: 50%;
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        transition: transform 0.2s, opacity 0.2s;
    }
    
    .battle-delete:hover {
        transform: scale(1.1);
    }
    
    .battle-delete img {
        width: 20px;
        height: 20px;
        filter: brightness(10);
    }
    
    .battle-details {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .player-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        margin-bottom: 15px;
        padding: 15px;
    }
    
    .player-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--accent);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .stats-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 4px;
        margin-bottom: 15px;
    }
    
    .stats-table td {
        padding: 8px;
        text-align: center;
        background: var(--battle-stat-bg);
        border-radius: 8px;
    }
    
    .stats-table img {
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }
    
    .items-list {
        margin-top: 15px;
    }
    
    .items-list summary {
        cursor: pointer;
        padding: 10px;
        font-weight: 600;
        color: var(--accent);
        background: var(--secondary-bg);
        border-radius: 8px;
        outline: none;
    }
    
    .items-list summary:hover {
        background: var(--item-hover);
    }
    
    .item-card {
        background: var(--secondary-bg);
        border-radius: 8px;
        padding: 10px;
        margin: 8px 0;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .item-card:hover {
        background: var(--item-hover);
    }
    
    .battle-time {
        color: var(--muted);
        font-size: 14px;
        margin-top: 5px;
    }
    
    .delete-confirmation {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 10px;
    }
    
    .confirmation-btn {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 8px 16px;
        border-radius: var(--radius);
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .confirmation-btn:hover {
        transform: translateY(-2px);
    }
    
    .cancel-btn {
        background: var(--danger-gradient);
    }
</style>

<div class="battle-container">
<?php
if (!isset($_GET['view_battle'])) {
    $resDBattle = $mc->query("SELECT `battle_id`,`Pname`,`Pnamevs`,`Pvsname`,`battle_start_time`,`level` FROM `battle` WHERE `Pnamevs` !='' && `type_battle` > '0' ORDER BY `battle_start_time` DESC");
    if ($resDBattle->num_rows > 0) {
        ?>
        <div class="battle-header">
            Дуэли
        </div>
        <?php
        $BattleDAll = $resDBattle->fetch_all(MYSQLI_ASSOC);
        foreach ($BattleDAll as $value) {
            ?>
            <div class="battle-card" onclick="showContent('/admin/battle/index.php?view_battle=<?= $value['battle_id']; ?>')">
                <div class="battle-info">
                    <div class="player-name"><?= $value['Pname']; ?> [<?= $value['level']; ?>]</div>
                    <div><?= $value['Pnamevs'] . " " . ($value['Pvsname'] != "" ? " vs " . $value['Pvsname'] : ""); ?></div>
                    <div class="battle-time"><?= date("d.m H:i:s", $value['battle_start_time']); ?></div>
                </div>
                <div class="battle-delete" onclick="event.stopPropagation(); showContent('/admin/battle/index.php?end_battle=<?= $value['battle_id']; ?>')">
                    <img src="../../img/button/btnno.png" alt="delete">
                </div>
            </div>
            <?php
        }
    }
    $resHBattle = $mc->query("SELECT `battle_id`,`Pname`,`Pnamevs`,`Pvsname`,`battle_start_time`,`level` FROM `battle` WHERE `Pnamevs` !='' && `type_battle` = '0' ORDER BY `battle_start_time` DESC");
    if ($resHBattle->num_rows > 0) {
        ?>
        <div class="battle-header">
            Охоты
        </div>
        <?php
        $BattleHAll = $resHBattle->fetch_all(MYSQLI_ASSOC);
        foreach ($BattleHAll as $value) {
            ?>
            <div class="battle-card" onclick="showContent('/admin/battle/index.php?view_battle=<?= $value['battle_id']; ?>')">
                <div class="battle-info">
                    <div class="player-name"><?= $value['Pname']; ?> [<?= $value['level']; ?>]</div>
                    <div><?= $value['Pnamevs'] . " " . ($value['Pvsname'] != "" ? " vs " . $value['Pvsname'] : ""); ?></div>
                    <div class="battle-time"><?= date("d.m H:i:s", $value['battle_start_time']); ?></div>
                </div>
                <div class="battle-delete" onclick="event.stopPropagation(); showContent('/admin/battle/index.php?end_battle=<?= $value['battle_id']; ?>')">
                    <img src="../../img/button/btnno.png" alt="delete">
                </div>
            </div>
            <?php
        }
    }
    $footval = 'adminadmin';
} else if (isset($_GET['view_battle']) && $_GET['view_battle'] > 0) {
    $resThisBattle = $mc->query("SELECT * FROM `battle` WHERE `battle_id` ='" . $_GET['view_battle'] . "'");
    if ($resThisBattle->num_rows > 0) {
        $arrIco = [];
        $arrIco[0] = "<img src='/img/icon/icoevil.png' width='19'>";
        $arrIco[1] = "<img src='/img/icon/icoevil.png' width='19'>";
        $arrIco[2] = "<img src='/img/icon/icogood.png' width='19'>";
        $arrIco[3] = "<img src='/img/icon/icogood.png' width='19'>";
        $BattleThisAll = $resThisBattle->fetch_all(MYSQLI_ASSOC);
        foreach ($BattleThisAll as $value) {
            if ($value['Pnamevs'] != "") {
                ?>
                <div class="battle-header">
                    <?= $value['Pnamevs'] . " " . ($value['Pvsname'] != "" ? " vs " . $value['Pvsname'] : ""); ?>
                    <div class="battle-time"><?= date("d.m H:i:s", $value['battle_start_time']); ?></div>
                    <div class="battle-delete" style="position: relative; margin: 10px auto; width: 30px; height: 30px;" onclick="showContent('/admin/battle/index.php?end_battle=<?= $value['battle_id']; ?>')">
                        <img src="../../img/button/btnno.png" alt="delete">
                    </div>
                </div>
                <?php
            }
        }
        foreach ($BattleThisAll as $value) {
            $icons = "";

            if ($value['Ptype'] == 0) {
                $icons = $arrIco[$value['Pico']];
            } else if ($value['Ptype'] == 1) {
                $icons = '<img src="/img/icon/mob/' . $value['Pico'] . '.png" width="19">';
            }
            ?>
            <div class="player-card">
                <div class="player-name">
                    <?= $icons ?> <?= $value['Pname']; ?> [<?= $value['level']; ?>]
                </div>
                
                <table class="stats-table">
                    <tr>
                        <td><img src="../../images/icons/hp.png" alt="fhp"></td>
                        <td><img src="../../images/icons/health.png" alt="hp"></td>
                        <td><img src="../../images/icons/toch.png" alt="toch"></td>
                        <td><img src="../../images/icons/shit.png" alt="blok"></td>
                    </tr>
                    <tr>
                        <td><?= $value['Pflife']; ?></td>
                        <td><?= $value['Plife']; ?></td>
                        <td><?= $value['Ptochnost']; ?></td>
                        <td><?= $value['Pblock']; ?></td>
                    </tr>
                    <tr>
                        <td><img src="../../images/icons/power.jpg" alt="uron"></td>
                        <td><img src="../../images/icons/bron.png" alt="bronia"></td>
                        <td><img src="../../images/icons/kd.png" alt="oglushenie"></td>
                        <td><img src="../../images/icons/img235.png" alt="uvorot"></td>
                    </tr>
                    <tr>
                        <td><?= $value['Puron']; ?></td>
                        <td><?= $value['Pbronia']; ?></td>
                        <td><?= $value['Poglushenie']; ?></td>
                        <td><?= $value['Puvorot']; ?></td>
                    </tr>
                </table>
                
                <?php if ($value['shops_ids'] != "") { ?>
                    <details class="items-list">
                        <summary>Список вещей</summary>
                        <?php
                        $this_shops_arr = json_decode($value['shops_ids']);
                        for ($i = 0; $i < count($this_shops_arr); $i++) {
                            ?>
                            <div class="item-card" onclick="showContent('/admin/shop.php?shop=edit&id=<?= $this_shops_arr[$i][1]; ?>')">
                                <?= ($i + 1) . ". " . $this_shops_arr[$i][0]; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </details>
                <?php } ?>
            </div>
            <?php
        }
    }
    $footval = 'adminbattle';
}
?>
</div>

<?php
require_once '../../system/foot/foot.php';
?>
