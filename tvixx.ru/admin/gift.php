<?php
require_once ('../system/func.php');
require_once ('../system/header.php');

if (!isset($user) || $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
if (isset($_GET['save']) && isset($_GET['id']) && isset($_GET['img']) && isset($_GET['plata']) && isset($_GET['name'])) {
    if ($mc->query("UPDATE `shop_gift` SET "
                    . "`img` = '" . $_GET['img'] . "' ,"
                    . "`plata` = '" . $_GET['plata'] . "' ,"
                    . "`name` = '" . $_GET['name'] . "'"
                    . " WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("Сохранено"));
    } else {
        message(urlencode("<font style='color:red'>Не сохранено</font>"));
    }
}
if (isset($_GET['dell']) && isset($_GET['id'])) {
    if ($mc->query("DELETE FROM `shop_gift` WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("Удалено"));
    } else {
        message(urlencode("<font style='color:red'>Не удалено</font>"));
    }
}
if (isset($_GET['add']) && isset($_GET['img']) && isset($_GET['plata']) && isset($_GET['name'])) {
    if ($mc->query("INSERT INTO `shop_gift`("
                    . "`id`,"
                    . "`img`,"
                    . "`plata`,"
                    . "`name`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'" . $_GET['img'] . "',"
                    . "'" . $_GET['plata'] . "',"
                    . "'" . $_GET['name'] . "'"
                    . ")")
    ) {
        message(urlencode("Создано"));
    } else {
        message(urlencode("<font style='color:red'>Не создано</font>"));
    }
}
$giftArrAll = $mc->query("SELECT * FROM `shop_gift` ORDER BY `plata` ASC")->fetch_all(MYSQLI_ASSOC);
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
    
    .gift-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .gift-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .gift-card {
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
    
    .gift-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .add-gift-form {
        display: grid;
        grid-template-columns: 1fr 120px 1fr;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .gift-input {
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        width: 100%;
    }
    
    .gift-input:focus {
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        text-align: center;
        line-height: 1;
        height: 45px;
        box-sizing: border-box;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .button.delete {
        background: var(--danger-gradient);
    }
    
    .button.save {
        background: var(--success-gradient);
    }
    
    .gift-divider {
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .gift-table-header {
        display: grid;
        grid-template-columns: 30% 120px 1fr;
        gap: 10px;
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--accent);
        padding: 0 10px;
    }
    
    .gift-item {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        padding: 15px;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        display: grid;
        grid-template-columns: 60px 1fr;
        gap: 15px;
    }
    
    .gift-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .gift-image {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .gift-image img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: contain;
        background: var(--glass-bg);
        padding: 5px;
        border: 1px solid var(--glass-border);
    }
    
    .gift-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .gift-form {
        display: grid;
        grid-template-columns: 30% 120px 1fr;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .gift-actions {
        display: grid;
        grid-template-columns: 1fr 120px;
        gap: 10px;
    }
    
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
        margin-top: 30px;
    }
    
    .image-gallery-header {
        grid-column: 1 / -1;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 10px;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 10px;
    }
    
    .image-item {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
    }
    
    .image-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .image-item img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }
    
    .image-item-name {
        font-size: 10px;
        color: var(--muted);
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .add-gift-form,
        .gift-form {
            grid-template-columns: 1fr;
        }
        
        .gift-table-header {
            display: none;
        }
        
        .gift-actions {
            grid-template-columns: 1fr;
        }
        
        .gift-item {
            grid-template-columns: 1fr;
        }
        
        .gift-image {
            justify-content: flex-start;
        }
    }
</style>

<div class="gift-container">
    <h2 class="gift-header">Редактор Подарков</h2>
    
    <div class="gift-card">
        <div class="add-gift-form">
            <input type="text" id="img_new" class="gift-input" placeholder="Имя картинки" value="">
            <input type="number" id="plata_new" class="gift-input" placeholder="Платина" value="">
            <input type="text" id="name_new" class="gift-input" placeholder="Название" value="">
        </div>
        
        <button onclick="showContent('/admin/gift.php?add&img=' + $('#img_new').val() + '&plata=' + $('#plata_new').val() + '&name=' + $('#name_new').val())" class="button">Добавить подарок</button>
        
        <div class="gift-divider"></div>
        
        <div class="gift-table-header">
            <div>Изображение</div>
            <div>Стоимость</div>
            <div>Название</div>
        </div>
        
        <?php for ($i = 0; $i < count($giftArrAll); $i++) { ?>
            <div class="gift-item">
                <div class="gift-image">
                    <img src="../images/gifts/<?= $giftArrAll[$i]['img']; ?>.png" alt="<?= $giftArrAll[$i]['name']; ?>">
                </div>
                
                <div class="gift-details">
                    <div class="gift-form">
                        <input type="text" id="id_<?= $i; ?>" value="<?= $giftArrAll[$i]['id']; ?>" hidden>
                        <input type="text" id="img_<?= $i; ?>" class="gift-input" placeholder="Имя картинки" value="<?= $giftArrAll[$i]['img']; ?>">
                        <input type="number" id="plata_<?= $i; ?>" class="gift-input" placeholder="Платина" value="<?= $giftArrAll[$i]['plata']; ?>">
                        <input type="text" id="name_<?= $i; ?>" class="gift-input" placeholder="Название" value="<?= $giftArrAll[$i]['name']; ?>">
                    </div>
                    
                    <div class="gift-actions">
                        <button onclick="showContent('/admin/gift.php?save&id=' + $('#id_<?= $i; ?>').val() + '&img=' + $('#img_<?= $i; ?>').val() + '&plata=' + $('#plata_<?= $i; ?>').val() + '&name=' + $('#name_<?= $i; ?>').val())" class="button save">Сохранить</button>
                        <button onclick="showContent('/admin/gift.php?dell&id=' + $('#id_<?= $i; ?>').val())" class="button delete">Удалить</button>
                    </div>
                </div>
            </div>
        <?php } ?>
        
        <div class="gift-divider"></div>
        
        <div class="image-gallery">
            <div class="image-gallery-header">Доступные изображения (нажмите, чтобы выбрать)</div>
            <div id="images_all"></div>
        </div>
    </div>
</div>

<?php
$dir = "../images/gifts";
$files = scandir($dir);
$list = [];
foreach ($files as $file):
    $list[] = $file;
endforeach;
?>

<script>
    var list = <?= json_encode($list); ?>;
    var i = 0;
    list.sort(naturalCompare);
    MyLib.setTimeid[100] = setTimeout(function () {
        load();
    }, 1000);
    function load() {
        if (i < list.length - 1) {
            if (list[i] != "." && list[i] != "..") {
                $("#images_all").append(
                    '<div class="image-item" onclick="$(\'#img_new\').val(\''+list[i].replace(/.png/g,"")+'\');$(document).scrollTop(0)">' +
                    '<img src="../images/gifts/' + list[i] + '" alt="' + list[i] + '">' +
                    '<div class="image-item-name">' + list[i] + '</div>' +
                    '</div>'
                );
            }
            i++;
            load();
        }
    }

    function naturalCompare(a, b) {
        var ax = [], bx = [];

        a.replace(/(\d+)|(\D+)/g, function (_, $1, $2) {
            ax.push([$1 || Infinity, $2 || ""]);
        });
        b.replace(/(\d+)|(\D+)/g, function (_, $1, $2) {
            bx.push([$1 || Infinity, $2 || ""]);
        });

        while (ax.length && bx.length) {
            var an = ax.shift();
            var bn = bx.shift();
            var nn = (an[0] - bn[0]) || an[1].localeCompare(bn[1]);
            if (nn)
                return nn;
        }

        return ax.length - bx.length;
    }
</script>

<?php
$footval = 'adminmoney';
include '../system/foot/foot.php';
?>

