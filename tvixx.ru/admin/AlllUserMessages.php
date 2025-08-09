<?php
require_once ('../system/func.php');
require_once ('../system/header.php');

if (!isset($user) || $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}

$MyMsgs = $mc->query("SELECT * FROM `mailRoom` ORDER BY `id` DESC ");
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
        --primary-gradient: linear-gradient(135deg, var(--accent), var(--accent-2));
        --message-color: #0033CC;
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .messages-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .messages-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .messages-card {
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
    
    .messages-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .quote {
        font-style: italic;
        color: var(--muted);
        text-align: center;
        margin-bottom: 20px;
        padding: 15px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        font-size: 14px;
        line-height: 1.5;
    }
    
    .messages-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .message-item {
        padding: 15px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        transition: all 0.3s ease;
    }
    
    .message-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .message-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 14px;
        color: var(--message-color);
    }
    
    .message-info {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .message-content {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    
    .message-icon {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }
    
    .message-text {
        color: var(--text);
        word-break: break-all;
        font-weight: 500;
        font-size: 15px;
        line-height: 1.5;
    }
    
    @media (max-width: 768px) {
        .message-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .message-info {
            width: 100%;
        }
    }
</style>

<div class="messages-container">
    <h2 class="messages-header">Все сообщения пользователей</h2>
    
    <div class="messages-card">
        <div class="quote">
            "Каждый имеет право на тайну переписки, телефонных переговоров, почтовых, телеграфных и иных сообщений. Ограничение этого права допускается только на основании судебного решения, или админам MMORIA"
        </div>
        
        <div class="messages-list">
            <?php while ($MyMsg = $MyMsgs->fetch_array(MYSQLI_ASSOC)) { ?>
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-info">
                            <span>ID отправителя: <?= $MyMsg['id_sender']; ?></span>
                            <span>Комната: <?= $MyMsg['room_id']; ?></span>
                            <span>Дата: <?= $MyMsg['date']; ?></span>
                        </div>
                    </div>
                    
                    <div class="message-content">
                        <img class="message-icon" src="/img/icon/GOL_app_mess_in.png" alt="Входящее">
                        <div class="message-text"><?= urldecode($MyMsg['text']); ?></div>
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
