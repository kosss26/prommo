<?php
require_once '../system/func.php';
require_once '../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}
if(isset($_GET['saveShop']) && isset($_GET['idshop']) && isset($_GET['idhero']) && isset($_GET['punct'])){
	$id = intval($_GET['idhero']);
	$shop = intval($_GET['idshop']);
	$punct = intval($_GET['punct']);
	
	if(!empty($shop)){
		if($mc->query("INSERT INTO `shop_heroes` (`id_hero`,`id_shop`,`punct`) VALUES ('".$id."','".$shop."','".$punct."')")){
			    ?><script>showContent("/admin/heroes.php?shop&id=<?=$id;?>");</script><?php
		}
	}
}
if(isset($_GET['shop']) && isset($_GET['delete'])){
	$id = intval($_GET['delete']);
	if($mc->query("DELETE FROM `shop_heroes` WHERE `id` = '".$id."'")){
		?><script>showContent("/admin/heroes.php?shop&id=<?=$id;?>");</script><?php
	}
}
if (isset($_GET['save']) && isset($_GET['id']) && isset($_GET['level']) && isset($_GET['name']) && isset($_GET['platinum'])) {
	if ($mc->query("UPDATE `heroes` SET "
                    . "`level` = '" . $_GET['level'] . "' ,"
                    . "`platinum` = '" . $_GET['platinum'] . "' ,"
                    . "`name` = '" . $_GET['name'] . "'"
                    . " WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("Сохранено"));
    } else {
        message(urlencode("<font style='color:red'>Не сохранено</font>"));
    }
}
if (isset($_GET['dell']) && isset($_GET['id'])) {
    if ($mc->query("DELETE FROM `heroes` WHERE `id` = '" . $_GET['id'] . "'")) {
        message(urlencode("Удалено"));
    } else {
        message(urlencode("<font style='color:red'>Не удалено</font>"));
    }
}
if (isset($_GET['add']) && isset($_GET['level']) && isset($_GET['name']) && isset($_GET['platinum'])) {
    if ($mc->query("INSERT INTO `heroes`("
                    . "`id`,"
                    . "`level`,"
                    . "`platinum`,"
                    . "`name`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'" . $_GET['level'] . "',"
                    . "'" . $_GET['platinum'] . "',"
                    . "'" . $_GET['name'] . "'"
                    . ")")
            ) {
        message(urlencode("Создано"));
    } else {
        message(urlencode("<font style='color:red'>Не создано</font>"));
    }
}
$hero = $mc->query("SELECT * FROM `heroes` ")->fetch_all(MYSQLI_ASSOC);
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
    
    .heroes-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .heroes-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .heroes-card {
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
    
    .heroes-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .equipment-grid {
        display: grid;
        grid-template-columns: 1fr 3fr;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .equipment-slot {
        padding: 12px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        border: 1px solid var(--glass-border);
    }
    
    .slot-label {
        font-weight: 600;
        color: var(--accent);
        margin-right: 10px;
        min-width: 100px;
    }
    
    .slot-value {
        flex: 1;
    }
    
    .equipment-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
        margin-top: 20px;
    }
    
    .hero-input {
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
    
    .hero-input:focus {
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
    
    .hero-divider {
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .hero-items-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .hero-item {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        padding: 15px;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    
    .hero-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .hero-item-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
        align-items: center;
    }
    
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    .hero-section {
        margin-bottom: 20px;
    }
    
    .hero-section summary {
        padding: 15px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        cursor: pointer;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 15px;
        border: 1px solid var(--glass-border);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .hero-section summary:hover {
        background: var(--item-hover);
    }
    
    .hero-section[open] summary {
        margin-bottom: 15px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .hero-section-content {
        padding: 15px;
        background: var(--secondary-bg);
        border-radius: 0 0 var(--radius) var(--radius);
        border: 1px solid var(--glass-border);
        border-top: none;
        margin-top: -15px;
    }
    
    .hero-link {
        display: block;
        padding: 10px;
        background: var(--glass-bg);
        border-radius: var(--radius);
        color: var(--text);
        text-decoration: none;
        margin-bottom: 10px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid var(--glass-border);
    }
    
    .hero-link:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
    }
    
    .item-delete {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        margin-bottom: 10px;
        border: 1px solid var(--glass-border);
    }
    
    .item-delete a {
        color: var(--accent);
        cursor: pointer;
        text-decoration: none;
    }
    
    .item-delete a:hover {
        text-decoration: underline;
    }
    
    .add-item-form {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        border: 1px solid var(--glass-border);
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--accent);
    }
    
    @media (max-width: 768px) {
        .equipment-grid {
            grid-template-columns: 1fr;
        }
        
        .equipment-actions {
            grid-template-columns: 1fr;
        }
        
        .hero-item-form {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="heroes-container">
    <h2 class="heroes-header">Управление героями</h2>

<?php
if(isset($_GET['shop']) && isset($_GET['id'])){
    $hero = $mc->query("SELECT * FROM `heroes` WHERE `id` = '".intval($_GET['id'])."'")->fetch_array(MYSQLI_ASSOC);
    $shop_heroes = $mc->query("SELECT * FROM `shop_heroes` WHERE `id_hero` = '".intval($hero['id'])."'")->fetch_all(MYSQLI_ASSOC);
    $or;
    $shit;
    $shlem;
    $per;
    $dos;
    $ob;
    $am;
    $kol = [];
    $is = ",";
    for($i = 0; $i < count($shop_heroes); $i++){
      $shop = $mc->query("SELECT * FROM `shop` WHERE `id` = '".$shop_heroes[$i]['id_shop']."'")->fetch_array(MYSQLI_ASSOC);
      
      
      if($shop_heroes[$i]['punct'] == 1){
         $or = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 2){
         $shit = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 3){
         $shlem = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 4){
         $per = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 5){
         $dos = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 6){
         $ob = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 7){
         $am = $shop['name'];
      }else if($shop_heroes[$i]['punct'] == 8){
         $kol[] = $shop['name'];
         $is = ",";
        
      }
    }
    if(is_array($kol) && empty($kol)){
        $kol[0] = "";
        $is = "-";
        $kol[1] = "";
    }
    $countShop = $mc->query("SELECT COUNT(*) FROM `shop_heroes` WHERE `id_hero` = '".intval($hero['id'])."'")->fetch_array(MYSQLI_ASSOC);
    ?>
    <div class="heroes-card">
        <h3 style="text-align: center; margin-top: 0;"><?= $hero['name'] ?> - Снаряжение (<?=$countShop['COUNT(*)'];?>/56)</h3>
        
        <div class="equipment-grid">
            <div class="slot-label">Оружие:</div>
            <div class="slot-value"><?= isset($or) ? $or : "-"; ?></div>
            
            <div class="slot-label">Защита:</div>
            <div class="slot-value"><?= isset($shit) ? $shit : "-"; ?></div>
            
            <div class="slot-label">Шлем:</div>
            <div class="slot-value"><?= isset($shlem) ? $shlem : "-"; ?></div>
            
            <div class="slot-label">Перчатки:</div>
            <div class="slot-value"><?= isset($per) ? $per : "-"; ?></div>
            
            <div class="slot-label">Доспехи:</div>
            <div class="slot-value"><?= isset($dos) ? $dos : "-"; ?></div>
            
            <div class="slot-label">Обувь:</div>
            <div class="slot-value"><?= isset($ob) ? $ob : "-"; ?></div>
            
            <div class="slot-label">Амулет:</div>
            <div class="slot-value"><?= isset($am) ? $am : "-"; ?></div>
            
            <div class="slot-label">Кольца:</div>
            <div class="slot-value"><?= $kol[0].$is.$kol[1]; ?></div>
        </div>
        
        <div class="equipment-actions">
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=1')">Оружие</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=2')">Защита</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=3')">Шлем</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=4')">Перчатки</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=5')">Доспехи</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=6')">Обувь</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=7')">Амулет</a>
            <a class="button" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero['id'];?>&punct=8')">Кольца</a>
        </div>
    </div>
    <?php
}

if(isset($_GET['shop']) && isset($_GET['id']) && isset($_GET['punct'])){
    $name = "";
    $punct = $_GET['punct'];
    if($punct == 1){
        $name = "Оружие";
    }else if($punct == 2){
        $name = "Защита";
    }else if($punct == 3){
        $name = "Шлем";
    }else if($punct == 4){
        $name = "Перчатки";
    }else if($punct == 5){
        $name = "Доспехи";
    }else if($punct == 6){
        $name = "Обувь";
    }else if($punct == 7){
        $name = "Амулет";
    }else if($punct == 8){
        $name = "Кольца";
    }
    $shop = $mc->query("SELECT * FROM `shop_heroes` WHERE `id_hero` = '".intval($_GET['id'])."' AND `punct` = '".$punct."'")->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="heroes-card">
        <h3 style="text-align: center; margin-top: 0;">Редактирование: <?=$name;?></h3>
        
        <?php if(count($shop) > 0): ?>
            <div class="hero-items-list">
                <?php for($i = 0; $i < count($shop); $i++){
                    $shopS = $mc->query("SELECT * FROM `shop` WHERE `id` = '".$shop[$i]['id_shop']."'")->fetch_array(MYSQLI_ASSOC);
                ?>
                    <div class="item-delete">
                        <a onclick="showContent('admin/heroes.php?shop&delete=<?=$shop[$i]['id'];?>')">Удалить (<?=$shopS['name'];?>)</a>
                    </div>
                <?php } ?>
            </div>
            
            <div class="hero-divider"></div>
        <?php endif; ?>
        
        <div class="add-item-form">
            <div class="form-group">
                <label class="form-label">ВВЕДИТЕ АЙДИ ПРЕДМЕТА</label>
                <input class="hero-input" type="text" id="id" placeholder="ID предмета">
            </div>
            
            <button class="button" onclick="showContent('/admin/heroes.php?saveShop&idshop='+ $('#id').val()+'&idhero=<?=$_GET['id'];?>&punct=<?=$punct;?>');">Добавить</button>
        </div>
    </div>
    <?php
}

if(!isset($_GET['shop']) && !isset($_GET['id'])){
?>
    <div class="hero-section-container">
        <details class="hero-section">
            <summary>Редактор персонажей</summary>
            <div class="hero-section-content">
                <div class="hero-items-list">
                    <?php for($i = 0; $i < count($hero); $i++){ ?>
                        <div class="hero-item">
                            <div class="hero-item-form">
                                <input type='text' id='id_<?= $i; ?>' value="<?= $hero[$i]['id']; ?>" hidden>
                                <input type='text' id='platinum_<?= $i; ?>' class="hero-input" placeholder="Платина" value="<?= $hero[$i]['platinum']; ?>">
                                <input type='text' id='name_<?= $i; ?>' class="hero-input" placeholder="Имя героя" value="<?= $hero[$i]['name']; ?>">
                                <input type='text' id='level_<?= $i; ?>' class="hero-input" placeholder="Уровень" value="<?= $hero[$i]['level']; ?>">
                            </div>
                            
                            <div class="hero-divider"></div>
                            
                            <div class="action-buttons">
                                <button onclick="showContent('/admin/heroes.php?save&id=' + $('#id_<?= $i; ?>').val() + '&level=' + $('#level_<?= $i; ?>').val() + '&name=' + $('#name_<?= $i; ?>').val() + '&platinum=' + $('#platinum_<?=$i;?>').val())" class="button">Сохранить</button>
                                <button onclick="showContent('/admin/heroes.php?dell&id=' + $('#id_<?= $i; ?>').val())" class="button delete">Удалить</button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </details>
        
        <details class="hero-section">
            <summary>Создать персонажа</summary>
            <div class="hero-section-content">
                <div class="hero-item-form" style="margin-bottom: 15px;">
                    <input type='text' id='platinum_new' class="hero-input" placeholder="Платина" value="">
                    <input type='text' id='level_new' class="hero-input" placeholder="Уровень" value="">
                    <input type='text' id='name_new' class="hero-input" placeholder="Имя героя" value="">
                </div>
                
                <button onclick="showContent('/admin/heroes.php?add&level=' + $('#level_new').val() + '&name=' + $('#name_new').val() + '&platinum='+ $('#platinum_new').val())" class="button">Создать героя</button>
            </div>
        </details>
        
        <details class="hero-section">
            <summary>Снаряжение персонажа</summary>
            <div class="hero-section-content">
                <div class="hero-items-list">
                    <?php for($i = 0; $i < count($hero); $i++){ ?>
                        <a class="hero-link" onclick="showContent('/admin/heroes.php?shop&id=<?=$hero[$i]['id'];?>')">
                            <?= $hero[$i]['name'];?> [<?=$hero[$i]['level'];?>]
                        </a>
                    <?php } ?>
                </div>
            </div>
        </details>
        
        <details class="hero-section">
            <summary>Ещё что-то очень важное</summary>
            <div class="hero-section-content">
                <!-- Пустой блок для будущего функционала -->
            </div>
        </details>
    </div>
<?php
}
?>
</div>

<?php
$footval = 'adminindex';
include '../system/foot/foot.php';
?>
