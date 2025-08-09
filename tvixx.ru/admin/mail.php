<?php
require_once ('../system/func.php');
require_once ('../system/header.php');
if (!$user OR $user['access'] < 3) {
    ?>
    <script>showContent("/");</script>
    <?php
    exit;
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
    
    .mail-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .mail-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .mail-card {
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
    
    .mail-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .mail-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--accent);
        font-size: 16px;
    }
    
    .form-input {
        width: 100%;
        padding: 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 15px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        min-height: 100px;
        resize: vertical;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 15px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
        align-self: center;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .back-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
        margin-top: 20px;
        border-radius: var(--radius);
        padding: 10px 15px;
        background: var(--secondary-bg);
        border: 1px solid var(--glass-border);
        width: fit-content;
        transition: all 0.3s ease;
    }
    
    .back-link:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
    }
    
    .back-link img {
        width: 20px;
        height: 20px;
    }
    
    @media (max-width: 768px) {
        .mail-container {
            padding: 0 10px;
        }
    }
</style>

<div class="mail-container">
    <h2 class="mail-header">Массовая рассылка</h2>
    
    <div class="mail-card">
        <?php
        if (isset($_GET['submit'])) {
            $query = $mc->query("select * from `users`");
            while ($res = $query->fetch_array(MYSQLI_ASSOC)) {
                $mc->query("INSERT INTO `mail` SET `massage` = '" . addslashes($_GET['text']) . "', `time` = '" . date("H:i") . "', `out` = '" . $user . "', `in` = '" . $res['id'] . "', `read` = '1'");
            }
            $all = $mc->query("SELECT * FROM `users`")->num_rows;
            message('Сообщение отправлено ' . $all . ' игрокам');
        } else {
            ?>
            <div class="mail-form">
                <form id="form">
                    <div class="form-group">
                        <label class="form-label">Сообщение для всех игроков:</label>
                        <textarea name="text" class="form-input" placeholder="Введите текст сообщения..."></textarea>
                    </div>
                    
                    <button type="button" class="button butt1">Отправить всем</button>
                </form>
            </div>
            <script>
                $(".butt1").click(function () {
                    showContent("/admin/mail.php?submit=" + $(this).val() + "&" + $("#form").serialize());
                });
            </script>
            <?php
        }
        ?>
    </div>
    
    <a onclick="showContent('/admin/index.php')" class="back-link">
        <img src="../img/img23.png" alt="*">
        <span>В админку</span>
    </a>
</div>

<?php
$footval = 'adminmail';
include '../system/foot/foot.php';
?>