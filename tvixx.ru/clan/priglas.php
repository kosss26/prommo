<?php

require_once('../system/func.php');


$clanI = $mc->query("SELECT COUNT(*) FROM `users` WHERE `id_clan` = '".$user['id_clan']."'")->fetch_array(MYSQLI_ASSOC);

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

.form_input {
    position: relative;
    margin-bottom: 20px;
}

.form_input input {
    width: 100%;
    padding: 12px 15px;
    background: var(--secondary-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    color: var(--text);
    font-size: 15px;
    transition: all 0.3s;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form_input input:focus {
    border-color: var(--accent);
    outline: none;
    box-shadow: 0 0 0 2px rgba(245, 193, 93, 0.2);
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
    
    .form_input input {
        padding: 10px;
        font-size: 14px;
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

if (isset($_POST['add']) && !empty($_POST['name'])) {
    if ($user['des'] > 1) {
        // Получаем ID игрока по его имени
        $userInvite = $mc->query("SELECT * FROM `users` WHERE `name` LIKE '%" . urlencode($_POST['name']) . "%'")->fetch_array(MYSQLI_ASSOC);
        
        if (empty($userInvite)) {
            showClanModal('Игрок с указанным именем не найден', 'Вернуться', '/clan/priglas.php');
        } else if ($userInvite['id_clan'] > 0) {
            showClanModal('Этот игрок уже состоит в клане', 'Вернуться', '/clan/priglas.php');
        } else if ($clanI['COUNT(*)'] > 19) {
            showClanModal('В клане уже 20 игроков, новых приглашать нельзя', 'Вернуться', '/clan/priglas.php');
        } else {
            // Добавляем приглашение, если оно еще не существует
            if (!$mc->query("SELECT * FROM `clan_prig` WHERE `id_clan` = '" . $user['id_clan'] . "' AND `id_user` = '" . $userInvite['id'] . "'")->fetch_array(MYSQLI_ASSOC)) {
                if ($mc->query("INSERT INTO `clan_prig` (`id_clan`,`id_user`) VALUES ('" . $user['id_clan'] . "','" . $userInvite['id'] . "')")) {
                    // Добавляем сообщение игроку о приглашении
                    $clan = $mc->query("SELECT * FROM `clan` WHERE `id` = '" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`type`,`date`) VALUES ('" . $userInvite['id'] . "','<a onclick=\"showContent(\'/clan/accept.php?id=" . $user['id_clan'] . "\')\">Вы приглашены в клан " . $clan['name'] . "</a>','clan','" . time() . "')");
                    
                    showClanModal('Приглашение успешно отправлено', 'Согласиться', 'main.php');
                } else {
                    showClanModal('Ошибка при отправке приглашения', 'Вернуться', '/clan/priglas.php');
                }
            } else {
                showClanModal('Вы уже отправляли приглашение этому игроку', 'Вернуться', '/clan/priglas.php');
            }
        }
    } else {
        showClanModal('У вас недостаточно прав для приглашения в клан', 'Вернуться', 'main.php');
    }
}
?>

<div class="clan_container">
    <h2 style="color: var(--accent); text-align: center;">Пригласить игрока в клан</h2>
    
    <form method="POST" action="/clan/priglas.php">
        <div class="form_input">
            <input type="text" name="name" placeholder="Введите имя игрока" required>
        </div>
        
        <button type="submit" name="add" class="clan_button">
            <i class="fas fa-user-plus"></i> Пригласить
        </button>
    </form>
</div>
