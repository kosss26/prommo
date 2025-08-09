<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>/*nextshowcontemt*/showContent("/");</script><?php
    exit(0);
}
if (isset($_GET['zp_flag']) && $_GET['zp_flag'] == 1) {
    $mdRes = $mc->query("SELECT * FROM `users` WHERE `access`='1' || `access`='2' || `access`='3' || `access`='4'");
    if ($mdRes->num_rows > 0) {
        $md = $mdRes->fetch_all(MYSQLI_ASSOC);
        foreach ($md as $value) {
            $plat_zp = 400 * $value['access'];
            $msgText = "Уважаемый " . $value['name'] . ", Вам <br>начислена зарплата размером <br>в " . $plat_zp . "<img style='width: 15px;' src='/images/icons/plata.png'> . <br>Мир Северного Нармаса и <br>Южного Шейвана благодарны <br>вам за ваши славные и <br>благородные деяния!";
                $mc->query("UPDATE `users` SET `platinum` = `platinum`+'" . $plat_zp . "' WHERE `users`.`id` = '" . $value['id'] . "'");
                //запишем ему оповещение
                $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $value['id'] . "','" . addslashes($msgText) . "','" . time() . "','zarplata')");
                //запишем zp в бд
                $mc->query("INSERT INTO `zarplataMd` (`id`, `id_user`, `level`, `name`, `platina`, `money`, `date`) VALUES (NULL, '" . $value['id'] . "', '" . $value['level'] . "', '" . $value['name'] . "', '$plat_zp', '0', CURRENT_TIMESTAMP)");
        }
        ?><script>/*nextshowcontemt*/showContent("/main.php?msg=" + encodeURI("Зарплаты выданы!"));</script><?php
        exit(0);
    } else {
        ?><script>/*nextshowcontemt*/showContent("/main.php?msg=" + encodeURI("Ошибка, некому платить."));</script><?php
        exit(0);
    }
}
if (!isset($_GET['zp_flag']) && isset($_GET['vidat_zp'])) {
    message_yn(
            "Выдать зарплату модераторам 400 плат, СМД 800 плат, админам 1200 плат?", "/admin/zarplata/zarplata.php?zp_flag=1", "/main.php", "Да", "Нет"
    );
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
    
    .zarplata-panel {
        max-width: 900px;
        margin: 0 auto;
    }
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .section {
        margin-bottom: 25px;
    }
    
    .section-title {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px;
        border-radius: var(--radius);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        text-align: center;
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
    
    .payment-item {
        background: var(--card-bg);
        border-radius: var(--radius);
        margin-bottom: 10px;
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 12px;
        transition: all 0.3s ease;
    }
    
    .payment-item:hover {
        transform: translateY(-2px);
        background: var(--item-hover);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    .payment-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .payment-table a {
        color: var(--accent);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .payment-table a:hover {
        color: var(--accent-2);
    }
    
    @media (max-width: 600px) {
        .zarplata-panel {
            padding: 10px;
        }
    }
</style>

<div class="zarplata-panel">
    <h2>Управление зарплатами</h2>

    <div class="section">
        <div class="section-title">Выплата зарплат</div>
        <button class='button_alt_01' type='button' onclick="showContent('/admin/zarplata/zarplata.php?vidat_zp')" >Выплатить</button>
    </div>

<?php
$resZP = $mc->query("SELECT * FROM `zarplataMd` ORDER BY `id` DESC LIMIT 20");
if ($resZP->num_rows > 0) {
    ?>
    <div class="section">
        <div class="section-title">Последние 20 выплат</div>
        <div class="payment-table">
            <?php
            $ZP = $resZP->fetch_all(MYSQLI_ASSOC);
            $izp = 1;
            foreach ($ZP as $value) {
            ?>
                <div class="payment-item">
                    <?= $izp; ?>.
                    <a onclick="showContent('/profile/<?= $value['id_user']; ?>')">
                        <?= $value['name']; ?>
                        [<?= $value['level']; ?>]
                    </a>
                    &nbsp;
                    <?= $value['platina']; ?>
                    <img style='width: 15px;' src='/images/icons/plata.png'>
                    <?= $value['date']; ?>
                </div>
            <?php
                $izp++;
            }
            ?>
        </div>
    </div>
    <?php
}
?>
</div>

<?php
$footval = 'adminadmin';
require_once '../../system/foot/foot.php';
?>
