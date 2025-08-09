<?php
require_once '../system/func.php';
require_once '../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}

// Обработка сохранения
if (isset($_GET['save']) && !empty($_GET['name']) && !empty($_GET['text'])) {
    if ($mc->query("INSERT INTO `support` (`name`,`text`) VALUES ('" . $_GET['name'] . "','" . $_GET['text'] . "')")) {
        message(urlencode("Раздел помощи успешно добавлен"));
    } else {
        message(urlencode("<font style='color:red'>Ошибка при добавлении раздела!</font>"));
    }
}

// Получаем список разделов помощи
$helpSections = $mc->query("SELECT * FROM `support` ORDER BY `id` DESC");
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
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .support-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .support-card {
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
    
    .support-card::before {
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
    
    .support-form {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--accent);
        font-size: 14px;
    }
    
    .form-hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 5px;
    }
    
    input[type="text"], textarea {
        width: 100%;
        padding: 12px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }
    
    textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    input[type="text"]:focus, textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .button {
        padding: 14px;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        height: 50px;
        box-sizing: border-box;
    }
    
    .add-btn {
        background: var(--primary-gradient);
        color: #111;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .help-sections {
        margin-top: 30px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 15px;
        text-align: center;
    }
    
    .help-item {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .help-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--accent);
        background: var(--item-hover);
    }
    
    .help-name {
        font-size: 18px;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 10px;
    }
    
    .help-text {
        color: var(--text);
        font-size: 15px;
        line-height: 1.5;
    }
    
    .empty-message {
        text-align: center;
        padding: 30px;
        color: var(--muted);
        font-style: italic;
    }
    
    @media (max-width: 768px) {
        .support-form {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="support-container">
    <h2 class="support-header">Управление разделами помощи</h2>
    
    <div class="support-card">
        <!-- Форма для добавления нового раздела помощи -->
        <form id="supportForm" class="support-form">
            <div class="form-group">
                <label class="form-label" for="name">Название раздела</label>
                <input type="text" id="name" name="name" placeholder="Введите название раздела помощи">
                <div class="form-hint">Например: Правила игры, Часто задаваемые вопросы, Как начать игру и т.д.</div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="text">Содержание раздела</label>
                <textarea id="text" name="text" placeholder="Введите текст раздела помощи"></textarea>
                <div class="form-hint">Подробно опишите информацию, которая будет полезна игрокам</div>
            </div>
            
            <button id="saveButton" type="button" class="button add-btn">Добавить раздел помощи</button>
        </form>
        
        <!-- Список существующих разделов помощи -->
        <div class="help-sections">
            <div class="section-title">Существующие разделы помощи</div>
            <div class="hr_01"></div>
            
            <?php if ($helpSections && $helpSections->num_rows > 0): ?>
                <?php while ($section = $helpSections->fetch_assoc()): ?>
                    <div class="help-item">
                        <div class="help-name"><?= htmlspecialchars($section['name']) ?></div>
                        <div class="help-text"><?= nl2br(htmlspecialchars($section['text'])) ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-message">
                    Разделы помощи пока не созданы. Создайте первый раздел!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#saveButton").click(function () {
            // Проверка заполнения полей
            if ($("#name").val().trim() === "" || $("#text").val().trim() === "") {
                alert("Необходимо заполнить все поля!");
                return;
            }
            
            showContent("/admin/support.php?save=1&" + $("#supportForm").serialize());
        });
    });
</script>

<?php
$footval = 'adminindex';
include '../system/foot/foot.php';
?>