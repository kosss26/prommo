<?php
require_once '../system/func.php';
require_once '../system/header.php';
if(!$user OR $user['access']<2){
   ?><script>showContent("/");</script><?php
    exit;
}

	if(isset($_POST['vkpodtv']))
	{
		$mc->query("UPDATE `zbtkey` SET `vk`='".$_POST['vkpodtv']."' WHERE `id`='".$_POST['vkid']."'");
		exit();
		
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
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .keygen-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    h2, h3 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
    }
    
    h2 {
        font-size: 28px;
    }
    
    h3 {
        font-size: 22px;
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
    
    .button_alt_01 {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px 20px;
        border: none;
        border-radius: var(--radius);
        text-align: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 10px auto;
        display: block;
        width: 200px;
        box-shadow: var(--panel-shadow);
    }
    
    .button_alt_01:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    input[type="text"], 
    input[type="number"] {
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
    }
    
    input[type="text"]:focus, 
    input[type="number"]:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .keys-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    
    .keys-table th {
        text-align: left;
        padding: 12px;
        color: var(--accent);
        font-weight: 600;
        border-bottom: 1px solid var(--glass-border);
    }
    
    .keys-table td {
        padding: 12px;
        background: var(--secondary-bg);
        border: none;
    }
    
    .keys-table tr td:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    
    .keys-table tr td:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
        text-align: center;
    }
    
    .keys-table tr:hover td {
        background: var(--item-hover);
    }
    
    .form-row {
        margin-bottom: 15px;
    }
    
    .form-row label {
        display: block;
        margin-bottom: 8px;
        color: var(--muted);
        font-weight: 500;
    }
    
    .stats {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
        text-align: center;
    }
    
    .stats-item {
        padding: 10px 20px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        min-width: 100px;
    }
    
    .stats-value {
        font-size: 18px;
        font-weight: 600;
        color: var(--accent);
        margin-top: 5px;
    }
    
    .active-icon {
        color: #2ecc71;
        font-size: 18px;
    }
    
    .inactive-icon {
        color: #e74c3c;
        font-size: 18px;
    }
    
    @media (max-width: 600px) {
        .keys-table {
            display: block;
            overflow-x: auto;
        }
        
        input[type="text"], input[type="number"] {
            font-size: 16px; /* Prevent zoom on focus in iOS */
        }
    }
</style>

<div class="keygen-container">
    <h2>Управление ключами активации</h2>
    
    <?php if($user['id'] == '6' ||$user['id'] == '369'): ?>
    <div class="card">
        <h3>Генерация ключей</h3>
        <form id="form1">
            <div class="form-row">
                <label>ID пользователя для ключей:</label>
                <input type="number" name="uzver" value="">
            </div>
            <div class="form-row">
                <label>Количество ключей:</label>
                <input type="number" name="colvokeygen" value="">
            </div>
            <input name="Submit" class="button_alt_01 butt2" type="button" value="Генерация">
        </form>
    </div>
    <?php endif; ?>

    <?php
    if(isset($_GET['Submit'])) {
        for ($i=1; $i <= $_GET['colvokeygen']; $i++) {
            $mc->query("INSERT INTO `zbtkey`(`keygen`,`iduser`) VALUES ('".KeyGen($i)."','".$_GET['uzver']."')");
        }
    }
    
    error_reporting(E_ALL);
    function KeyGen($rndkey){
       $key = md5(mktime() * ($rndkey*rand()*rand()));
       $new_key = '';
       for($i=1; $i <= 25; $i++) {
           $new_key .= $key[$i];
           if ($i%5==0 && $i != 25) $new_key.='-';
       }
       return strtoupper($new_key);
    }
    ?>
    
    <div class="card">
        <h3>Список ключей</h3>
        <table class="keys-table">
            <thead>
                <tr>
                    <th>Ключ</th>
                    <th>ВК аккаунт</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                $activ = 0;
                $noactiv = 0;
                
                if($user['id'] == '6' ||$user['id'] == '3691111') {
                    $allkey1 = $mc->query("SELECT * FROM `zbtkey` ORDER BY `zbtkey`.`ispolzovan` ASC");
                } else {
                    $allkey1 = $mc->query("SELECT * FROM `zbtkey` WHERE `iduser`='".$user['id']."' ORDER BY `zbtkey`.`ispolzovan` ASC");
                }
                
                while ($allkey = $allkey1->fetch_array(MYSQLI_ASSOC)) {
                    $i++;
                    if($allkey['ispolzovan'] == 0) {
                        $noactiv++;
                    } else {
                        $activ++;
                    }
                    ?>
                    <tr>
                        <td>
                            <input type="text" name="key" value="<?php echo $allkey['keygen']; ?>">
                        </td>
                        <td>
                            <input type="text" name="vkakk" id="vkakk" onkeyup="keyvk(<?php echo $allkey['id'].', this.value'; ?>);" value="<?php echo $allkey['vk']; ?>">
                        </td>
                        <td>
                            <?php if($allkey['ispolzovan'] == 0) { ?>
                                <span class="inactive-icon">&#10008;</span>
                            <?php } else { ?>
                                <span class="active-icon">&#10004;</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <div class="stats">
            <div class="stats-item">
                <div>Всего</div>
                <div class="stats-value"><?php echo $activ+$noactiv; ?></div>
            </div>
            <div class="stats-item">
                <div>Активно</div>
                <div class="stats-value"><?php echo $activ; ?></div>
            </div>
            <div class="stats-item">
                <div>Неактивно</div>
                <div class="stats-value"><?php echo $noactiv; ?></div>
            </div>
        </div>
    </div>
</div>

<script>
function keyvk(idvk, vkval) {
    $.ajax({
        type: 'post',
        url: '/admin/keygen.php',
        data: {'vkid': idvk, 'vkpodtv': vkval},
        response: 'text',
        success: function (data) {
            // Успешное обновление
        }
    });
}

$(".butt2").click(function () {
    showContent("admin/keygen.php?Submit="+$(this).val()+"&"+ $("#form1").serialize());
});
</script>

<?php $footval='adminmoney'; include '../system/foot/foot.php'; ?>