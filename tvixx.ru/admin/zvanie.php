<?php
require_once ('../system/func.php');
require_once ('../system/header.php');

if (!isset($user) || $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
if (isset($_GET['save']) && isset($_GET['id']) && isset($_GET['lvl']) && isset($_GET['slava']) && isset($_GET['name'])) {
    if ($mc->query("UPDATE `slava` SET "
                    . "`lvl` = '" . $_GET['lvl'] . "' ,"
                    . "`slava` = '" . $_GET['slava'] . "' ,"
                    . "`name` = '" . $_GET['name'] . "'"
                    . " WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("сохранено"));
    } else {
        message(urlencode("<font style='color:red'>не сохранено</font>"));
    }
}
if (isset($_GET['dell']) && isset($_GET['id'])) {
    if ($mc->query("DELETE FROM `slava` WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("удалено"));
    } else {
        message(urlencode("<font style='color:red'>не удалено</font>"));
    }
}
if (isset($_GET['add']) && isset($_GET['lvl']) && isset($_GET['slava']) && isset($_GET['name'])) {
    if ($mc->query("INSERT INTO `slava`("
                    . "`id`,"
                    . "`lvl`,"
                    . "`slava`,"
                    . "`name`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'" . $_GET['lvl'] . "',"
                    . "'" . $_GET['slava'] . "',"
                    . "'" . $_GET['name'] . "'"
                    . ")")
            ) {
        message(urlencode("создано"));
    } else {
        message(urlencode("<font style='color:red'>не создано</font>"));
    }
}
$slavaArrAll = $mc->query("SELECT * FROM `slava` ORDER BY `lvl` ASC")->fetch_all(MYSQLI_ASSOC);
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
    
    .zvanie-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .zvanie-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .zvanie-card {
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
    
    .zvanie-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .hr_01 {
        border: 0;
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .zvanie-form {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .zvanie-form-full {
        grid-column: 1 / -1;
    }
    
    .zvanie-table-header {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr;
        gap: 10px;
        padding: 10px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        font-weight: 600;
        text-align: center;
        color: var(--accent);
        margin-bottom: 15px;
    }
    
    .zvanie-item {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .zvanie-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--accent);
        background: var(--item-hover);
    }
    
    .zvanie-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .zvanie-buttons {
        display: grid;
        grid-template-columns: auto auto;
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
        box-sizing: border-box;
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
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        height: 45px;
        box-sizing: border-box;
    }
    
    .add-btn {
        background: var(--primary-gradient);
        color: #111;
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
    
    @media (max-width: 768px) {
        .zvanie-form,
        .zvanie-table-header,
        .zvanie-inputs {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 500px) {
        .zvanie-form,
        .zvanie-table-header,
        .zvanie-inputs,
        .zvanie-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="zvanie-container">
    <h2 class="zvanie-header">Редактор званий</h2>
    
    <div class="zvanie-card">
        <!-- Форма для добавления нового звания -->
        <div class="zvanie-form">
            <input type="text" id="lvl_new" placeholder="Уровень" value="">
            <input type="text" id="slava_new" placeholder="Слава" value="">
            <input type="text" id="name_new" placeholder="Название" value="">
        </div>
        
        <div class="zvanie-form">
            <div class="zvanie-form-full">
                <button onclick="showContent('/admin/zvanie.php?add&lvl=' + $('#lvl_new').val() + '&slava=' + $('#slava_new').val() + '&name=' + $('#name_new').val())" class="button add-btn">Добавить новое звание</button>
            </div>
        </div>
        
        <div class="hr_01"></div>
        
        <!-- Заголовок таблицы званий -->
        <div class="zvanie-table-header">
            <div>Уровень</div>
            <div>Слава</div>
            <div>Название</div>
        </div>
        
        <!-- Список существующих званий -->
        <?php for ($i = 0; $i < count($slavaArrAll); $i++) { ?>
            <div class="zvanie-item">
                <input type="text" id="id_<?= $i; ?>" value="<?= $slavaArrAll[$i]['id']; ?>" hidden>
                
                <div class="zvanie-inputs">
                    <input type="text" id="lvl_<?= $i; ?>" placeholder="Уровень" value="<?= $slavaArrAll[$i]['lvl']; ?>">
                    <input type="text" id="slava_<?= $i; ?>" placeholder="Слава" value="<?= $slavaArrAll[$i]['slava']; ?>">
                    <input type="text" id="name_<?= $i; ?>" placeholder="Название" value="<?= $slavaArrAll[$i]['name']; ?>">
                </div>
                
                <div class="zvanie-buttons">
                    <button onclick="showContent('/admin/zvanie.php?save&id=' + $('#id_<?= $i; ?>').val() + '&lvl=' + $('#lvl_<?= $i; ?>').val() + '&slava=' + $('#slava_<?= $i; ?>').val() + '&name=' + $('#name_<?= $i; ?>').val())" class="button save-btn">Сохранить</button>
                    <button onclick="showContent('/admin/zvanie.php?dell&id=' + $('#id_<?= $i; ?>').val())" class="button delete-btn">Удалить</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
$footval = 'adminmoney';
include '../system/foot/foot.php';
?>