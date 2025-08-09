<?php
require_once 'system/func.php';
require_once 'system/header.php';
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
    
    .support-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
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
        position: relative;
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
    
    .card p {
        line-height: 1.6;
        margin-bottom: 20px;
        color: var(--muted);
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
    
    input[type="text"] {
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
        min-height: 100px;
    }
    
    input[type="text"]:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
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
    
    @media (max-width: 600px) {
        input[type="text"] {
            font-size: 16px; /* Prevent zoom on focus in iOS */
        }
    }
</style>

<div class="support-container">
    <h2>Оставить отзыв</h2>
    
    <div class="card">
        <p>Пожалуйста, оставьте свой отзыв об игре, найденных ошибках. Напишите, что бы Вы хотели видеть в игре в дальнейшем.</p>
        
        <div class="form-row">
            <label>Сообщение:</label>
            <input type='text' class="input_real chat_input" name='text' id='text'>
        </div>
        
        <div class="button_alt_01" id="btn_sup">
            Отправить
        </div>
    </div>
</div>

<script>
    $('#btn_sup').click(function () {
        var t = $('input[name="text"]').val();
        $.ajax({
            url: "/vk.com/bot.php?name=<?= $user['name']; ?>&&support=" + t,
            success: function (data) {

            }
        });
        showContent('/support.php?' + $('#text').serialize());
    });
</script>

<?php
if (isset($_GET['text']) && $text = $_GET['text'])
    if (isset($_GET['text']) && isset($user['name'])) {
        $mc->query("INSERT INTO `ticket`("
                . "`id`,"
                . "`text`,"
                . "`user`,"
                . "`userid`"
                . ") VALUES ("
                . "'NULL',"
                . "'" . $_GET['text'] . "',"
                . "'" . $user['name'] . "',"
                . "'" . $user['id'] . "'"
                . ")");
        ?><script>showContent("/");</script><?php
    }
?>
<?php
$footval = "help";
require_once ('system/foot/foot.php');
?>