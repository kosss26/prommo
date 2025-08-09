<?php
require_once '../system/func.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/date_functions.php';
?>

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
    --table-header: rgba(255,255,255,0.1);
    --table-row-alt: rgba(255,255,255,0.02);
    --table-row-hover: rgba(255,255,255,0.07);
    --team1-color: #e74c3c;
    --team2-color: #3498db;
    --danger-color: #ff4c4c;
    --positive-color: #2ecc71;
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

.holdings_container {
    max-width: 800px;
    margin: 15px auto;
    padding: 0 15px;
    animation: fadeIn 0.5s ease-out;
}

.holdings_header {
    font-size: 22px;
    font-weight: 600;
    color: var(--accent);
    margin-bottom: 20px;
    text-align: center;
    letter-spacing: 0.5px;
    position: relative;
    padding-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.holdings_header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
}

.holdings_header i {
    margin-right: 8px;
}

.holdings_empty {
    position: relative;
    padding: 25px;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    text-align: center;
    color: var(--muted);
    font-size: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.holding_card {
    position: relative;
    padding: 20px;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.holding_card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    background: var(--item-hover);
}

.holding_title {
    font-size: 18px;
    font-weight: 600;
    color: var(--accent);
    margin-bottom: 20px;
    text-align: center;
    letter-spacing: 0.5px;
    position: relative;
    padding-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.holding_title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
}

.holding_title i {
    margin-right: 8px;
}

.holding_info {
    display: grid;
    gap: 15px;
}

.holding_stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 5px;
    border-bottom: 1px solid var(--glass-border);
    transition: all 0.3s;
}

.holding_stat:last-child {
    border-bottom: none;
}

.holding_stat:hover {
    background: var(--secondary-bg);
    transform: translateX(5px);
    border-radius: 8px;
}

.holding_stat_label {
    color: var(--text);
    font-weight: 500;
    font-size: 15px;
    display: flex;
    align-items: center;
}

.holding_stat_label i {
    margin-right: 8px;
    color: var(--accent);
}

.holding_stat_value {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text);
    font-weight: 500;
}

.holding_stat_value.next_battle {
    color: var(--danger-color);
    font-weight: 600;
}

.income_icon {
    width: 20px;
    height: 20px;
    filter: brightness(1.2);
    transition: transform 0.3s;
}

.holding_stat:hover .income_icon {
    transform: scale(1.15);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
    .holdings_container {
        padding: 0 10px;
        margin: 10px auto;
    }
    
    .holdings_header {
        font-size: 18px;
        margin-bottom: 15px;
    }
    
    .holdings_empty {
        padding: 20px;
    }
    
    .holding_card {
        padding: 15px;
    }
    
    .holding_title {
        font-size: 16px;
        margin-bottom: 15px;
    }
    
    .holding_stat {
        padding: 10px 5px;
        font-size: 14px;
    }
    
    .holding_stat_label {
        font-size: 14px;
    }
    
    .income_icon {
        width: 16px;
        height: 16px;
    }
}
</style>

<div class="holdings_container">
    <div class="holdings_header">
        <i class="fas fa-map-marked-alt"></i> Владения клана
    </div>

    <?php 
    $vosemp = mktime(19, 50, 0, date("m"), date("d"), date("Y"));
    $shestp = mktime(17, 50, 0, date("m"), date("d"), date("Y"));

    $location = $mc->query("SELECT * FROM `location` WHERE `idClan`='". $user['id_clan'] ."'")->fetch_all(MYSQLI_ASSOC);
    
    if(count($location) > 0) {
        foreach($location as $loc) {
            $nextZahvat = formatNextBattleDate($loc['nextZahvat']);
            
            $dhdClan = $loc['dhdClan'];
            $dhdUser = $loc['dhdUser'] * $user['level'];
            ?>
            <div class="holding_card">
                <div class="holding_title">
                    <i class="fas fa-landmark"></i> <?= htmlspecialchars($loc['Name']); ?>
                </div>
                <div class="holding_info">
                    <div class="holding_stat">
                        <div class="holding_stat_label">
                            <i class="fas fa-shield-alt"></i> Следующее сражение:
                        </div>
                        <div class="holding_stat_value next_battle"><?= $nextZahvat; ?></div>
                    </div>
                    
                    <div class="holding_stat">
                        <div class="holding_stat_label">
                            <i class="fas fa-coins"></i> Доход казны:
                        </div>
                        <div class="holding_stat_value">
                            <img class="income_icon" src="/images/icons/zoloto.png" alt="gold">
                            <?= money($dhdClan, 'zoloto'); ?>
                            <img class="income_icon" src="/images/icons/serebro.png" alt="silver">
                            <?= money($dhdClan, 'serebro'); ?>
                            <img class="income_icon" src="/images/icons/med.png" alt="copper">
                            <?= money($dhdClan, 'med'); ?>
                        </div>
                    </div>
                    
                    <div class="holding_stat">
                        <div class="holding_stat_label">
                            <i class="fas fa-user-shield"></i> Личный доход:
                        </div>
                        <div class="holding_stat_value">
                            <img class="income_icon" src="/images/icons/zoloto.png" alt="gold">
                            <?= money($dhdUser, 'zoloto'); ?>
                            <img class="income_icon" src="/images/icons/serebro.png" alt="silver">
                            <?= money($dhdUser, 'serebro'); ?>
                            <img class="income_icon" src="/images/icons/med.png" alt="copper">
                            <?= money($dhdUser, 'med'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="holdings_empty">
            <i class="fas fa-exclamation-circle"></i> У вас нет захваченных локаций
        </div>
        <?php
    }
    ?>
</div>

<?php
$footval = "vladenia";
require_once '../system/foot/foot.php';