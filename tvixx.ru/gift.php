<?php
require_once ('system/func.php');
require_once ('system/header.php');
if (isset($_GET['id'])) {
    $id = abs(intval($_GET['id']));
} else {
    $id = -1;
}

$footval = "gifts";
auth(); // Закроем от неавторизированых
$result = $mc->query("SELECT * FROM `users` WHERE `id` = '$id'");
$profil = $result->fetch_array(MYSQLI_ASSOC);

// Обработка отправки подарка
if (isset($_POST['submit']) && isset($_POST['gift_id']) && isset($_POST['text'])) {
    $gift_id = (int)$_POST['gift_id'];
    $text = htmlspecialchars($_POST['text']);
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;
    
    if ($gift_id >= 1 && $gift_id <= 12) { // Проверяем допустимый ID подарка
        $mc->query("INSERT INTO `gifts` (
            `id`,
            `text`,
            `id_1`,
            `id_2`,
            `id_img`,
            `name`,
            `anonymous`,
            `date_gifts`
        ) VALUES (
            NULL,
            '$text',
            '" . $user['id'] . "',
            '" . $profil['id'] . "',
            '$gift_id',
            NULL,
            '$anonymous',
            '" . date('d.m.Y H:i') . "'
        )");
        
        // Перенаправляем на профиль получателя
        ?>
        <script>showContent("/profile.php?id=<?= $profil['id'] ?>");</script>
        <?php
        exit;
    }
}

$gift = $mc->query("SELECT * FROM `gifts` ");
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
        --gold-gradient: linear-gradient(135deg, #ffd700, #ff8452);
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 8px;
    }
    
    .gift-container {
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        box-sizing: border-box;
    }
    
    .gift-card {
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
        width: 100%;
        box-sizing: border-box;
    }
    
    .gift-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .gift-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 24px;
    }

    .gift-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .gift-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 15px;
        padding: 10px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
    }

    .gift-item {
        position: relative;
        text-align: center;
    }

    .gift-item input[type="radio"] {
        display: none;
    }

    .gift-item label {
        display: block;
        cursor: pointer;
        padding: 8px;
        border: 2px solid transparent;
        border-radius: var(--radius);
        transition: all 0.3s ease;
        background: var(--glass-bg);
    }

    .gift-item label:hover {
        transform: translateY(-2px);
        background: var(--item-hover);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .gift-item input[type="radio"]:checked + label {
        border-color: var(--accent);
        background: rgba(245, 193, 93, 0.1);
        box-shadow: 0 0 15px rgba(245, 193, 93, 0.3);
    }

    .gift-item img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        filter: drop-shadow(0 0 3px rgba(255, 255, 255, 0.3));
    }

    .gift-message textarea {
        width: 100%;
        padding: 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        resize: vertical;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        min-height: 100px;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }
    
    .gift-message textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }

    .gift-options {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .anonymous-option {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text);
        cursor: pointer;
        background: var(--secondary-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .anonymous-option:hover {
        background: var(--item-hover);
    }
    
    .anonymous-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent);
    }

    .gift-button {
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
        white-space: nowrap;
        width: 100%;
    }

    .gift-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        body {
            padding: 0;
        }
        
        .gift-container {
            padding: 0;
        }
        
        .gift-card {
            border-radius: 0;
            margin-bottom: 0;
        }
        
        .gift-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .gift-item img {
            width: 60px;
            height: 60px;
        }
        
        .gift-item label {
            padding: 6px;
        }
    }
</style>

<div class="gift-container">
    <div class="gift-card">
        <div class="gift-header">
            Отправить подарок <?= $profil['name'] ?>
        </div>

        <form action="" method="post" class="gift-form">
            <div class="gift-selection">
                <div class="gift-grid">
                    <?php for($i = 1; $i <= 12; $i++) { ?>
                        <div class="gift-item">
                            <input type="radio" name="gift_id" value="<?= $i ?>" id="gift_<?= $i ?>" required>
                            <label for="gift_<?= $i ?>">
                                <img src="/images/gifts/<?= $i ?>.png" alt="Подарок <?= $i ?>">
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="gift-message">
                <textarea name="text" placeholder="Напишите сообщение..." required></textarea>
            </div>

            <div class="gift-options">
                <label class="anonymous-option">
                    <input type="checkbox" name="anonymous" value="1">
                    Отправить анонимно
                </label>
            </div>

            <div class="gift-submit">
                <button type="submit" name="submit" class="gift-button">Отправить подарок</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('system/foot/foot.php'); ?>