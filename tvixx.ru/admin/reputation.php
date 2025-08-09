<?php
require_once ('../system/func.php');
require_once ('../system/header.php');

if (!isset($user) || $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
if (isset($_GET['save']) && isset($_GET['id']) && isset($_GET['rep']) && isset($_GET['name'])) {
    if ($mc->query("UPDATE `reputation` SET "
                    . "`rep` = '" . $_GET['rep'] . "' ,"
                    . "`name` = '" . $_GET['name'] . "'"
                    . " WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("Сохранено"));
    } else {
        message(urlencode("<font style='color:red'>Не сохранено</font>"));
    }
}
if (isset($_GET['dell']) && isset($_GET['id'])) {
    if ($mc->query("DELETE FROM `reputation` WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("Удалено"));
    } else {
        message(urlencode("<font style='color:red'>Не удалено</font>"));
    }
}
if (isset($_GET['add']) && isset($_GET['rep']) && isset($_GET['name'])) {
    if ($mc->query("INSERT INTO `reputation`("
                    . "`id`,"
                    . "`rep`,"
                    . "`name`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'" . $_GET['rep'] . "',"
                    . "'" . $_GET['name'] . "'"
                    . ")")
            ) {
        message(urlencode("Создано"));
    } else {
        message(urlencode("<font style='color:red'>Не создано</font>"));
    }
}
$repArrAll = $mc->query("SELECT * FROM `reputation` ORDER BY `rep` ASC")->fetch_all(MYSQLI_ASSOC);
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
    
    .reputation-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .reputation-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .reputation-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 20px;
        padding: 20px;
    }
    
    .reputation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .add-reputation-form {
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 10px;
        margin-bottom: 25px;
        align-items: center;
    }
    
    .reputation-input {
        width: 100%;
        padding: 12px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        height: 45px;
        box-sizing: border-box;
    }
    
    .reputation-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 45px;
        min-width: 120px;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .button.delete {
        background: var(--danger-gradient);
    }
    
    .reputation-table-header {
        display: grid;
        grid-template-columns: 120px 1fr auto auto;
        gap: 10px;
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--accent);
        padding: 0 10px;
    }
    
    .reputation-divider {
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .reputation-item {
        display: grid;
        grid-template-columns: 120px 1fr auto auto;
        gap: 10px;
        align-items: center;
        padding: 10px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .reputation-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    @media (max-width: 768px) {
        .add-reputation-form {
            grid-template-columns: 1fr;
        }
        
        .reputation-table-header {
            grid-template-columns: 80px 1fr auto;
        }
        
        .reputation-item {
            grid-template-columns: 80px 1fr auto;
            gap: 8px;
        }
        
        .action-buttons {
            grid-column: span 3;
            justify-content: flex-end;
        }
    }
    
    @media (max-width: 480px) {
        .reputation-table-header {
            display: none;
        }
        
        .reputation-item {
            grid-template-columns: 1fr;
            gap: 8px;
            padding: 15px;
        }
        
        .action-buttons {
            grid-column: 1;
            justify-content: space-between;
        }
    }
</style>

<div class="reputation-container">
    <h2 class="reputation-header">Редактор Репутаций</h2>
    
    <div class="reputation-card">
        <div class="add-reputation-form">
            <input type="text" id="rep_new" class="reputation-input" placeholder="Очки репутации" value="">
            <input type="text" id="name_new" class="reputation-input" placeholder="Название" value="">
            <button onclick="showContent('/admin/reputation.php?add&rep=' + $('#rep_new').val() + '&name=' + $('#name_new').val())" class="button">Добавить</button>
        </div>
        
        <div class="reputation-divider"></div>
        
        <div class="reputation-table-header">
            <div>Репутация</div>
            <div>Название</div>
            <div></div>
            <div></div>
        </div>
        
        <div class="reputation-items-list">
            <?php for ($i = 0; $i < count($repArrAll); $i++) { ?>
                <div class="reputation-item">
                    <input type="hidden" id="id_<?= $i; ?>" value="<?= $repArrAll[$i]['id']; ?>">
                    <input type="text" id="rep_<?= $i; ?>" class="reputation-input" placeholder="Очки репутации" value="<?= $repArrAll[$i]['rep']; ?>">
                    <input type="text" id="name_<?= $i; ?>" class="reputation-input" placeholder="Название" value="<?= $repArrAll[$i]['name']; ?>">
                    <div class="action-buttons">
                        <button onclick="showContent('/admin/reputation.php?save&id=' + $('#id_<?= $i; ?>').val() + '&rep=' + $('#rep_<?= $i; ?>').val() + '&name=' + $('#name_<?= $i; ?>').val())" class="button">Сохранить</button>
                        <button onclick="showContent('/admin/reputation.php?dell&id=' + $('#id_<?= $i; ?>').val())" class="button delete">Удалить</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
$footval = 'adminmoney';
include '../system/foot/foot.php';
?>