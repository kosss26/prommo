<?php
require_once '../system/func.php';
require_once '../functions/bablo.php';
require_once '../functions/bablo+.php';
$a="<b>";
$b="</b>";
$clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC);
if (isset($_GET['put_gold']) && isset($clan)) {
    $_GET['put_gold'] = floor($_GET['put_gold']);
    if (money($user['money'], 'zoloto') >= $_GET['put_gold']) {
        if ($_GET['put_gold'] > 0 && $_GET['put_gold'] != 0) {
            $GoldMoney = $clan['gold'] + $_GET['put_gold'];
            $myMoney = moneyplus(money($user['money'], 'zoloto') - $_GET['put_gold'], money($user['money'], 'serebro'), money($user['money'], 'med'));
            $mylog = '<a onclick=showContent("/profile/' . $user['id'] . '")><font color="#000000">' . $user['name'] . " [" . $user['level'] . "] " . ' </font></a>перевел <img src="/images/icons/zoloto.png">' . $_GET['put_gold'] . ' <br>' . $clan['goldlog'];
            $mc->query("UPDATE `clan` SET `reit`=`reit`+'".$_GET['put_gold']."',`gold`='$GoldMoney',`goldlog`='" . $mylog . "' WHERE `id`='" . $clan['id'] . "'");
            $mc->query("UPDATE `users` SET `reit`=`reit`+'".($_GET['put_gold']/2)."', `money`='" . $myMoney . "' WHERE `id`='" . $user['id'] . "'");
            message("Казна успешно пополнена на " . $_GET['put_gold'] . "<img src='/images/icons/zoloto.png'>.");
        } else {
            message("Не достаточно средств , перейдите в банк для обмена <img src='/images/icons/plata.png'> на <img src='/images/icons/zoloto.png'>.");
        }
    } else {
        message("Не достаточно средств , перейдите в банк для обмена <img src='/images/icons/plata.png'> на <img src='/images/icons/zoloto.png'>.");
    }
}


if ($clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC)) {
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

    .treasury_container {
        max-width: 800px;
        margin: 15px auto;
        padding: 0 15px;
        animation: fadeIn 0.5s ease-out;
    }

    .treasury_card {
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

    .treasury_card:hover {
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        transform: translateY(-2px);
        background: var(--item-hover);
    }

    .treasury_header {
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

    .treasury_header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 1px;
        background: linear-gradient(to right, transparent, var(--glass-border), transparent);
    }

    .treasury_stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border-bottom: 1px solid var(--glass-border);
        transition: all 0.3s;
    }

    .treasury_stat:last-child {
        border-bottom: none;
    }

    .treasury_stat:hover {
        background: var(--secondary-bg);
        transform: translateX(5px);
        border-radius: 8px;
    }

    .treasury_stat_label {
        color: var(--text);
        font-weight: 500;
        font-size: 15px;
        display: flex;
        align-items: center;
    }

    .treasury_stat_label i {
        margin-right: 8px;
        color: var(--accent);
    }

    .treasury_stat_value {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        font-weight: 600;
    }

    .treasury_stat_value img {
        width: 20px;
        height: 20px;
        filter: brightness(1.2);
        transition: transform 0.3s;
    }

    .treasury_stat:hover img {
        transform: scale(1.15);
    }

    .treasury_stat_value.positive {
        color: var(--positive-color);
        font-weight: 600;
    }

    .treasury_stat_value.negative {
        color: var(--danger-color);
        font-weight: 600;
    }

    .treasury_form {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .treasury_input {
        padding: 12px 18px;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        font-size: 15px;
        transition: all 0.3s;
        background: var(--secondary-bg);
        color: var(--text);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        font-family: 'Inter', sans-serif;
    }

    .treasury_input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
        background: var(--item-hover);
    }

    .treasury_button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 15px auto 0;
        padding: 12px 24px;
        background: var(--accent-2);
        color: #111;
        border: none;
        border-radius: var(--radius);
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 220px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .treasury_button i {
        margin-right: 10px;
    }

    .treasury_button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        background: #ff6a33;
    }

    .treasury_button:active {
        transform: translateY(1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .treasury_log {
        margin-top: 5px;
        padding: 20px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        color: var(--muted);
        line-height: 1.7;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(8px);
        font-size: 14px;
        max-height: 300px;
        overflow-y: auto;
    }

    .treasury_log a {
        transition: all 0.3s;
        text-decoration: none;
    }

    .treasury_log a:hover {
        color: var(--accent) !important;
        text-decoration: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
        .treasury_container {
            padding: 0 10px;
            margin: 10px auto;
        }
        
        .treasury_card {
            padding: 15px;
        }
        
        .treasury_header {
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .treasury_button {
            padding: 10px 20px;
            font-size: 14px;
            min-width: 180px;
        }
        
        .treasury_stat {
            padding: 12px 8px;
        }
        
        .treasury_stat_label {
            font-size: 14px;
        }
        
        .treasury_input {
            padding: 10px 15px;
            font-size: 14px;
        }
        
        .treasury_log {
            padding: 15px;
            font-size: 13px;
        }
    }
    </style>

    <div class="treasury_container">
        <div class="treasury_header">
            <i class="fas fa-coins"></i>&nbsp;Казна клана
        </div>
        
        <div class="treasury_card">
            <div class="treasury_stat">
                <div class="treasury_stat_label">
                    <i class="fas fa-coins"></i>Золото в казне:
                </div>
                <div class="treasury_stat_value">
                    <?= $clan['gold']; ?> <img src="/images/icons/zoloto.png" alt="Золото">
                </div>
            </div>
            
            <div class="treasury_stat">
                <div class="treasury_stat_label">
                    <i class="fas fa-magic"></i>Поддержка тотема:
                </div>
                <div class="treasury_stat_value">
                    <span class="<?= ($clan['totemtec']*(-10) > 0) ? 'positive' : 'negative' ?>">
                        <?= $clan['totemtec'] * (-10); ?>
                    </span> <img src="/images/icons/zoloto.png" alt="Золото">
                </div>
            </div>
            
            <div class="treasury_stat">
                <div class="treasury_stat_label">
                    <i class="fas fa-hand-holding-usd"></i>Доход с владений:
                </div>
                <div class="treasury_stat_value">
                    <?php
                    $summdhd = $mc->query("SELECT sum(`dhdClan`) as money FROM `location` WHERE `idClan`='" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC);
                    echo money($summdhd['money'], 'zoloto');
                    ?> <img src="/images/icons/zoloto.png" alt="Золото">
                </div>
            </div>
        </div>

        <div class="treasury_card">
            <div class="treasury_stat">
                <div class="treasury_stat_label">
                    <i class="fas fa-wallet"></i>Ваше золото:
                </div>
                <div class="treasury_stat_value">
                    <?= money($user['money'], 'zoloto'); ?> <img src="/images/icons/zoloto.png" alt="Золото">
                </div>
            </div>
            
            <div class="treasury_form">
                <input type="number" id="money" class="treasury_input" 
                       placeholder="Введите сумму" maxlength="10">
                <button type="button" class="treasury_button" 
                        onclick="showContent('/clan/kazna.php?put_gold=' + $('#money').val())">
                    <i class="fas fa-exchange-alt"></i>Перевести в казну
                </button>
            </div>
        </div>

        <div class="treasury_header">
            <i class="fas fa-history"></i>&nbsp;История пополнений
        </div>
        <div class="treasury_log">
            <?php if (!isset($clan['goldlog'])): ?>
                <div style="text-align: center">Переводов в казну еще не было</div>
            <?php else: ?>
                <?= $clan['goldlog']; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php
}

$footval = "kazna";
require_once '../system/foot/foot.php';
?>
