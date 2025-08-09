<?php
require_once '../system/func.php';
require_once '../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}
$auk = $mc->query("SELECT * FROM `auk_shop` ")->fetch_all(MYSQLI_ASSOC);
$auk1 = $mc->query("SELECT * FROM `auk` ")->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['save']) && isset($_GET['id']) && isset($_GET['minLevel']) && isset($_GET['maxLevel']) && isset($_GET['minPlata']) && isset($_GET['shop'])) {
	if ($mc->query("UPDATE `auk_shop` SET `id_shop` = '".$_GET['shop']."',`minLevel` = '".$_GET['minLevel']."',`maxLevel` = '".$_GET['maxLevel']."',`minPlata` = '".$_GET['minPlata']."' WHERE `id` = '".$_GET['id']."'")) {
        message(urlencode("сохранено"));
    } else {
        message(urlencode("<font style='color:red'>не сохранено</font>"));
    }
}

if (isset($_GET['saveAll']) && isset($_GET['id']) && isset($_GET['minLevel']) && isset($_GET['minPlata']) && isset($_GET['shop'])) {
	if ($mc->query("UPDATE `auk` SET `id_shop` = '".$_GET['shop']."',`level` = '".$_GET['minLevel']."',`min_plata` = '".$_GET['minPlata']."'  WHERE `id` = '".$_GET['id']."'")) {
        message(urlencode("сохранено"));
    } else {
        message(urlencode("<font style='color:red'>не сохранено</font>"));
    }
}

if (isset($_GET['dell']) && isset($_GET['id']) && isset($_GET['minLevel']) && isset($_GET['maxLevel']) && isset($_GET['minPlata']) && isset($_GET['shop'])) {
	if ($mc->query("DELETE FROM `auk_shop` WHERE `id` = '".$_GET['id']."'")) {
        message(urlencode("Удалил"));
    } else {
        message(urlencode("<font style='color:red'>не удалил</font>"));
    }
}

if (isset($_GET['dellAll']) && isset($_GET['id'])) {
	if ($mc->query("DELETE FROM `auk` WHERE `id` = '".$_GET['id']."'")) {
        message(urlencode("Удалил"));
    } else {
        message(urlencode("<font style='color:red'>не удалил</font>"));
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
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .auk-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .auk-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    details {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 20px;
    }
    
    details::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    summary {
        padding: 16px;
        font-size: 20px;
        font-weight: 600;
        cursor: pointer;
        color: var(--accent);
        position: relative;
        list-style: none;
        text-align: center;
    }
    
    summary::-webkit-details-marker {
        display: none;
    }
    
    details[open] summary {
        border-bottom: 1px solid var(--glass-border);
    }
    
    details > div {
        padding: 20px;
    }
    
    .hr_01 {
        border: 0;
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .table-container {
        width: 98%;
        margin: 10px auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    
    .buttons-container {
        width: 98%;
        margin: 0 auto 15px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
    }
    
    input[type="text"] {
        width: 100%;
        padding: 12px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    input[type="text"]:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .button {
        padding: 12px;
        border: none;
        border-radius: var(--radius);
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
    }
    
    .save-btn {
        background: var(--success-gradient);
    }
    
    .delete-btn {
        background: var(--danger-gradient);
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .help-text {
        padding: 12px;
        margin-bottom: 16px;
        font-size: 13px;
        color: var(--muted);
    }
    
    /* Стили для иконок предметов */
    .shopicobg {
        position: relative;
        width: 80px;
        height: 80px;
        background-image: url("/images/shopico.png?136.1114");
        background-repeat: no-repeat;
        box-sizing: content-box !important; 
        min-width: 80px !important;
        max-width: 80px !important;
        min-height: 80px !important;
        max-height: 80px !important;
        padding: 0 !important;
        margin: 0 !important;
        flex: 0 0 80px !important;
        display: inline-block;
    }
    
    /* Базовые позиции для спрайта иконок (по 9 иконок в ряду, размером 80x80px) */
    .shopico1{background-position:-0px -0px;}
    .shopico2{background-position:-80px -0px;}
    .shopico3{background-position:-160px -0px;}
    .shopico4{background-position:-240px -0px;}
    .shopico5{background-position:-320px -0px;}
    .shopico6{background-position:-400px -0px;}
    .shopico7{background-position:-480px -0px;}
    .shopico8{background-position:-560px -0px;}
    .shopico9{background-position:-640px -0px;}
    .shopico10{background-position:0px -80px;}
    .shopico11{background-position:-80px -80px;}
    .shopico12{background-position:-160px -80px;}
    .shopico13{background-position:-240px -80px;}
    .shopico14{background-position:-320px -80px;}
    .shopico15{background-position:-400px -80px;}
    .shopico16{background-position:-480px -80px;}
    .shopico17{background-position:-560px -80px;}
    .shopico18{background-position:-640px -80px;}
    .shopico19{background-position:0px -160px;}
    .shopico20{background-position:-80px -160px;}
    /* Дополнительные позиции для иконок */
    .shopico21{background-position:-160px -160px;}
    .shopico22{background-position:-240px -160px;}
    .shopico23{background-position:-320px -160px;}
    .shopico24{background-position:-400px -160px;}
    .shopico25{background-position:-480px -160px;}
    .shopico26{background-position:-560px -160px;}
    .shopico27{background-position:-640px -160px;}
    .shopico28{background-position:0px -240px;}
    .shopico29{background-position:-80px -240px;}
    .shopico30{background-position:-160px -240px;}
    
    .shop-item-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .shop-item-info {
        flex: 1;
    }
    
    @media (max-width: 768px) {
        .table-container {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 500px) {
        .table-container,
        .buttons-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="auk-container">
    <h2 class="auk-header">Управление аукционом</h2>
    
    <details open>
        <summary>Редактор лотов</summary>
        <div>
            <div class="help-text">
                1) Минимальный уровень<br>
                2) Минимальная платина<br>
                3) ID вещей<br>
                4) Максимальный уровень
            </div>
            <hr class="hr_01"/>
            
            <?php for($i = 0; $i < count($auk); $i++){ 
                // Получаем данные о предмете для отображения иконки
                $shop_info = $mc->query("SELECT * FROM `shop` WHERE `id`='".$auk[$i]['id_shop']."'")->fetch_array(MYSQLI_ASSOC);
                $item_icon = $shop_info ? $shop_info['id_image'] : 1; // Если не найдено, используем икону по умолчанию
            ?>
                <input type="text" id="id_<?= $i; ?>" value="<?= $auk[$i]['id']; ?>" hidden>
                
                <div class="shop-item-wrapper">
                    <div class="shopicobg shopico<?= $item_icon; ?>"></div>
                    <div class="shop-item-info">
                        <div class="table-container">
                            <input type="text" id="minLevel_<?= $i; ?>" placeholder="минимал-уровень" value="<?= $auk[$i]['minLevel']; ?>">
                            <input type="text" id="minPlata_<?= $i; ?>" placeholder="минимал-платина" value="<?= $auk[$i]['minPlata']; ?>">
                            <input type="text" id="shop_<?= $i; ?>" placeholder="id вещей" value="<?= $auk[$i]['id_shop']; ?>">
                            <input type="text" id="maxLevel_<?= $i; ?>" placeholder="максимал-уровнь" value="<?= $auk[$i]['maxLevel']; ?>">
                        </div>
                        <div class="buttons-container">
                            <button onclick="showContent('/admin/auk.php?save&id=' + $('#id_<?= $i; ?>').val() + '&maxLevel=' + $('#maxLevel_<?= $i; ?>').val() + '&minLevel=' + $('#minLevel_<?= $i; ?>').val() + '&minPlata=' + $('#minPlata_<?=$i;?>').val() + '&shop=' + $('#shop_<?=$i;?>').val())" class="button save-btn">Сохранить</button>
                            <button onclick="showContent('/admin/auk.php?dell&id=' + $('#id_<?= $i; ?>').val() + '&maxLevel=' + $('#maxLevel_<?= $i; ?>').val() + '&minLevel=' + $('#minLevel_<?= $i; ?>').val() + '&minPlata=' + $('#minPlata_<?=$i;?>').val() + '&shop=' + $('#shop_<?=$i;?>').val())" class="button delete-btn">Удалить</button>
                        </div>
                    </div>
                </div>
                <hr class="hr_01"/>
            <?php } ?>
        </div>
    </details>

    <details open>
        <summary>Активные лоты</summary>
        <div>
            <div class="help-text">
                1) Минимальный уровень<br>
                2) ID вещи<br>
                3) Максимальная платина
            </div>
            <hr class="hr_01"/>
            
            <?php for($i = 0; $i < count($auk1); $i++){ 
                // Получаем данные о предмете для отображения иконки
                $shop_info = $mc->query("SELECT * FROM `shop` WHERE `id`='".$auk1[$i]['id_shop']."'")->fetch_array(MYSQLI_ASSOC);
                $item_icon = $shop_info ? $shop_info['id_image'] : 1; // Если не найдено, используем икону по умолчанию
            ?>
                <input type="text" id="id_a_<?= $i; ?>" value="<?= $auk1[$i]['id']; ?>" hidden>
                
                <div class="shop-item-wrapper">
                    <div class="shopicobg shopico<?= $item_icon; ?>"></div>
                    <div class="shop-item-info">
                        <div class="table-container">
                            <input type="text" id="minLevel_a_<?= $i; ?>" placeholder="минимал-уровень" value="<?= $auk1[$i]['level']; ?>">
                            <input type="text" id="shop_a_<?= $i; ?>" placeholder="id вещи" value="<?= $auk1[$i]['id_shop']; ?>">
                            <input type="text" id="minPlata_a_<?= $i; ?>" placeholder="минимальная плата" value="<?= $auk1[$i]['min_plata']; ?>">
                        </div>
                        <div class="buttons-container">
                            <button onclick="showContent('/admin/auk.php?saveAll&id=' + $('#id_a_<?= $i; ?>').val() + '&minLevel=' + $('#minLevel_a_<?= $i; ?>').val() + '&minPlata=' + $('#minPlata_a_<?= $i; ?>').val() + '&shop=' + $('#shop_a_<?=$i;?>').val())" class="button save-btn">Сохранить</button>
                            <button onclick="showContent('/admin/auk.php?dellAll&id=' + $('#id_a_<?= $i; ?>').val())" class="button delete-btn">Удалить</button>
                        </div>
                    </div>
                </div>
                <hr class="hr_01"/>
            <?php } ?>
        </div>
    </details>
</div>

<?php
$footval = 'adminindex';
include '../system/foot/foot.php';
?>

