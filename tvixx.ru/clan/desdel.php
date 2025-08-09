<?php

require_once('../system/func.php');

// Общие стили вынесены в начало файла
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

.clan_container {
    max-width: 800px;
    margin: 15px auto;
    padding: 0 15px;
    animation: fadeIn 0.5s ease-out;
    text-align: center;
}

.clan_message {
    position: relative;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    color: var(--text);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
    font-size: 16px;
}

.clan_message.success {
    background: rgba(46, 204, 113, 0.1);
    border-color: rgba(46, 204, 113, 0.2);
    color: var(--positive-color);
}

.clan_message.error {
    background: rgba(255, 76, 76, 0.1);
    border-color: rgba(255, 76, 76, 0.2);
    color: var(--danger-color);
}

.clan_message::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
}

.clan_button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 20px auto;
    padding: 12px 24px;
    background: var(--accent-2);
    color: #111;
    border: none;
    border-radius: var(--radius);
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    min-width: 160px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.clan_button i {
    margin-right: 8px;
}

.clan_button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    background: #ff6a33;
}

.clan_button:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Модальное окно в стиле квестов */
.clan_modal {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
}

.clan_modal_content {
    width: 90%;
    max-width: 480px;
    background: rgba(15,32,39,0.93);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    color: var(--text);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    padding: 20px;
    text-align: center;
    animation: fadeIn 0.3s ease-out;
}

.clan_modal_text {
    color: #ffffff;
    font-size: 16px;
    line-height: 1.45;
    margin-bottom: 20px;
}

.clan_modal_button {
    display: inline-block;
    padding: 12px 24px;
    background: var(--accent-2);
    color: #111;
    border: none;
    border-radius: var(--radius);
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    min-width: 160px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    margin: 0 auto;
}

.clan_modal_button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    background: #ff6a33;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
    .clan_container {
        padding: 0 10px;
        margin: 10px auto;
    }
    
    .clan_message {
        padding: 15px;
        font-size: 15px;
    }
    
    .clan_button {
        padding: 10px 20px;
        font-size: 14px;
        min-width: 140px;
    }

    .clan_modal_content {
        padding: 15px;
    }

    .clan_modal_button {
        padding: 10px 20px;
        font-size: 14px;
    }
}
</style>

<?php
// Функция для показа модального окна
function showClanModal($message, $buttonText = 'Согласиться', $redirectUrl = 'main.php') {
    echo '<div class="clan_modal">
        <div class="clan_modal_content">
            <div class="clan_modal_text">'.$message.'</div>
            <button class="clan_modal_button" onclick="showContent(\''.$redirectUrl.'\')">'.$buttonText.'</button>
        </div>
    </div>';
    exit(0);
}

// Обработка снятия десятника
if (isset($_GET['desdel']) && $user['des'] > 2 && $user['id'] != $_GET['desdel']) {
    if ($mc->query("SELECT `id_clan` FROM `users` WHERE `id`='" . $_GET['desdel'] . "'")->fetch_array(MYSQLI_ASSOC)['id_clan'] == $user['id_clan']) {
        $mc->query("UPDATE `users` SET `des`='0' WHERE `id`= '" . $_GET['desdel'] . "'");
        
        // Показываем модальное окно вместо редиректа
        showClanModal('Десятник успешно снят с должности', 'Согласиться', 'main.php');
        
        exit(0);
    } else {
        ?>
        <div class="clan_container">
            <div class="clan_message error">
                <i class="fas fa-exclamation-circle"></i> Этот игрок не из вашего клана
            </div>
            <a class="clan_button" onclick="showContent('main.php')">
                <i class="fas fa-arrow-left"></i> Вернуться
            </a>
        </div>
        <?php
        exit(0);
    }
} elseif (isset($_GET['desdel']) && $user['des'] > 2 && $user['id'] == $_GET['desdel']) {
    ?>
    <div class="clan_container">
        <div class="clan_message error">
            <i class="fas fa-exclamation-circle"></i> Вы не можете снять с должности самого себя
        </div>
        <a class="clan_button" onclick="showContent('main.php')">
            <i class="fas fa-arrow-left"></i> Вернуться
        </a>
    </div>
    <?php
    exit(0);
} else {
    ?>
    <div class="clan_container">
        <div class="clan_message error">
            <i class="fas fa-exclamation-circle"></i> Недостаточно полномочий для снятия десятника
        </div>
        <a class="clan_button" onclick="showContent('main.php')">
            <i class="fas fa-arrow-left"></i> Вернуться
        </a>
    </div>
    <?php
    exit(0);
}

