<?php
ob_start();
require_once 'system/func.php';

if (!$user) {
    ?><script>showContent("/");</script><?php
    exit;
}

$newlevel = $user['level'];
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
    
    .level-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
    }
    
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .level-congratulation {
        text-align: center;
        font-size: 22px;
        margin-bottom: 30px;
        line-height: 1.5;
    }
    
    .level-number {
        color: var(--accent);
        font-size: 32px;
        font-weight: 700;
        display: inline-block;
        padding: 5px 15px;
        margin: 0 5px;
        background: rgba(0,0,0,0.2);
        border-radius: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .locations-title {
        font-size: 18px;
        color: var(--accent);
        margin: 20px 0;
        text-align: center;
        font-weight: 600;
    }
    
    .location-block {
        background: var(--secondary-bg);
        border-radius: calc(var(--radius) - 4px);
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid var(--glass-border);
    }
    
    .location-name {
        font-weight: 600;
        color: var(--accent);
        font-size: 18px;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid var(--glass-border);
    }
    
    .item-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 10px;
    }
    
    .item-entry {
        padding: 8px 12px;
        background: rgba(0,0,0,0.2);
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .item-entry:hover {
        background: rgba(0,0,0,0.3);
        transform: translateX(5px);
    }
    
    .button_alt_01 {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px 30px;
        border: none;
        border-radius: var(--radius);
        text-align: center;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 10px auto;
        display: inline-block;
        box-shadow: var(--panel-shadow);
    }
    
    .button_alt_01:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    @media (max-width: 600px) {
        .item-list {
            grid-template-columns: 1fr;
        }
        
        .level-container {
            padding: 0 10px;
        }
    }
</style>

<div class="level-container">
    <div class="card">
        <h2>Новый уровень</h2>
        
        <div class="level-congratulation">
            Поздравляем!<br>
            Вы достигли <span class="level-number"><?php echo $user['level']; ?></span> уровня
        </div>
        
        <div class="locations-title">В магазинах локаций Вам доступны новые вещи:</div>

        <?php
        // Получаем все локации, доступные по уровню
        $locationlevel1 = $mc->query("SELECT `id`,`Name`,`access`,(SELECT COUNT(*) FROM `shop_equip` WHERE `id_location`=`location`.`id` AND `level`='" . $newlevel . "') as `countshop` FROM `location` WHERE `accesslevel` <= '" . $newlevel . "' AND `id`!=23 AND `id`!=53 AND`id`!=0 ORDER BY `countshop` DESC");
        
        while ($locationlevel = $locationlevel1->fetch_array(MYSQLI_ASSOC)) {
            // Проверяем доступность локации по расе и уровню прав
            $canAccess = true;
            
            // Проверка на расу
            if ($locationlevel['access'] == 1 && ($user['side'] == 0 || $user['side'] == 1)) {
                // Локация только для Нормасцев, а игрок - Шейван
                $canAccess = false;
            } elseif ($locationlevel['access'] == 2 && ($user['side'] == 2 || $user['side'] == 3)) {
                // Локация только для Шейванов, а игрок - Нормасец
                $canAccess = false;
            }
            
            // Проверка на админ-локации (предполагаем, что они имеют специальный id или признак)
            // Если есть специальный признак для админ-локаций, тут нужно добавить проверку
            
            // Локация должна быть доступна и в ней должны быть предметы
            if ($canAccess && $locationlevel['countshop'] != 0) {
                $LocationName = $locationlevel['Name'];
                if($locationlevel['id'] == "102") {
                    if ($user['side'] == 2 || $user['side'] == 3) {
                        $LocationName = "Кремнёв";
                    } else {
                        $LocationName = "Вирастоль";
                    }
                }
                ?>
                <div class="location-block">
                    <div class="location-name"><?php echo $LocationName; ?></div>
                    <div class="item-list">
                    <?php
                    $shoplevel1 = $mc->query("SELECT `name` FROM `shop_equip` WHERE `id_location`='" . $locationlevel['id'] . "' AND `level`='" . $newlevel . "'");
                    while ($shoplevel = $shoplevel1->fetch_array(MYSQLI_ASSOC)) {
                        $shoplevel['name'] = str_replace("green", "5", $shoplevel['name']);
                        $shoplevel['name'] = str_replace("yellow", "5", $shoplevel['name']);
                        $shoplevel['name'] = str_replace("red", "5", $shoplevel['name']);
                        $shoplevel['name'] = str_replace("blue", "5", $shoplevel['name']);
                        echo '<div class="item-entry">• ' . $shoplevel['name'] . '</div>';
                    }
                    ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    
    <center>
        <a onclick="showContent('/main.php')">
            <div class="button_alt_01">Далее</div>
        </a>
    </center>
</div>

<?php
$footval = "profile";
require_once ('system/foot/foot.php');
ob_end_flush();
?>
