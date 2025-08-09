<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}

$allloc = $mc->query("SELECT * FROM `location` ORDER BY `id`")->fetch_all(MYSQLI_ASSOC);
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
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .hunt-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .hunt-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .hunt-subheader {
        text-align: center;
        color: var(--muted);
        margin-bottom: 20px;
        font-weight: 500;
        font-size: 18px;
    }
    
    .location-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .location-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    
    .location-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        background: var(--item-hover);
    }
    
    .location-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .location-card-content {
        padding: 15px;
        display: flex;
        align-items: center;
    }
    
    .location-id {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--glass-bg);
        border-radius: 50%;
        margin-right: 15px;
        font-weight: 600;
        color: var(--accent);
    }
    
    .location-info {
        flex: 1;
    }
    
    .location-name {
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .location-level {
        font-size: 14px;
        color: var(--muted);
    }
    
    .location-count {
        background: var(--glass-bg);
        border-radius: var(--radius);
        min-width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--accent);
    }
    
    @media (max-width: 768px) {
        .location-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="hunt-container">
    <h2 class="hunt-header">Редактор охот</h2>
    <div class="hunt-subheader">Выберите город для редактирования</div>

    <div class="location-grid">
        <?php
        for ($i = 0; $i < count($allloc); $i++) {
            $counts = $mc->query("SELECT * FROM `hunt_equip` WHERE `id_loc` = '" . $allloc[$i]['id'] . "' ")->num_rows;
            $icon = "";
            if ($allloc[$i]['access'] == 1) {
                $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
            } elseif ($allloc[$i]['access'] == 2) {
                $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
            }
            ?>
            <div class="location-card" onclick="showContent('/admin/hunt_equip/edit.php?id_loc=<?= $allloc[$i]['id']; ?>')">
                <div class="location-card-content">
                    <div class="location-id"><?= $allloc[$i]['id']; ?></div>
                    <div class="location-info">
                        <div class="location-name">
                            <?= $icon ?> <?= $allloc[$i]['Name']; ?>
                        </div>
                        <div class="location-level">Уровень: <?= $allloc[$i]['accesslevel']; ?></div>
                    </div>
                    <div class="location-count"><?= $counts; ?></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
$footval = 'adminlocindex';
include '../../system/foot/foot.php';
?>