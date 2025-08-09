<?php

require_once('../system/func.php');
require_once('../system/dbc.php');
require_once('../system/header.php');
auth(); // Закроем от неавторизированых

if (isset($_GET['create']) && isset($_GET['name']) && $user['id_clan'] == 0) {
    if ($user['platinum'] >= 100) {
        if ($user['id_clan'] == 0) {
            $plata = $user['platinum'] - 100;
            $mc->query("INSERT INTO `clan`("
                    . "`id`,"
                    . "`name`,"
                    . "`max_user`,"
                    . "`gold`"
                    . ") VALUES ("
                    . "'NULL',"
                    . "'" . addslashes($_GET['name']). "',"
                    . "'10',"
                    . "'0'"
                    . ")");
            $mc->query("UPDATE `users` SET `platinum`='" . $plata . "',`id_clan`='" . $mc->insert_id . "',`des` = '3',`reit`='0' WHERE `id`='" . $user['id'] . "'");
            ?>
            <script>
                console.log("<?=$mc->error;?>");
                showContent('main.php?msg='+encodeURIComponent('Клан успешно создан'));
            </script>
            <?php
            exit(0);
        }
    }
}

// Проверяем, состоит ли пользователь уже в клане
if ($user['id_clan'] > 0) {
    ?>
    <script>
        showContent('main.php?msg='+encodeURIComponent('необходимо покинуть предыдущий клан для создания нового'));
    </script>
    <?php
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать клан - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111">
    <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
    --primary-button: #ff8452;
    --primary-button-hover: #ff6a33;
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

.create_container {
    max-width: 800px;
    margin: 15px auto;
    padding: 0 15px;
    animation: fadeIn 0.5s ease-out;
}

.create_content {
    position: relative;
    padding: 25px;
    margin-bottom: 20px;
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.create_content:hover {
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    transform: translateY(-2px);
    background: var(--item-hover);
}

.create_header {
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

.create_header i {
    margin-right: 10px;
}

.create_header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 1px;
    background: linear-gradient(to right, transparent, var(--glass-border), transparent);
}

.create_info {
    text-align: center;
    color: var(--muted);
    margin-bottom: 20px;
    line-height: 1.6;
    font-size: 15px;
}

.create_cost {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 25px;
    font-size: 16px;
    color: var(--text);
    font-weight: 600;
    background: var(--glass-bg);
    padding: 12px 20px;
    border-radius: var(--radius);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
    transition: all 0.3s;
    border: 1px solid var(--glass-border);
}

.create_cost:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    background: var(--item-hover);
}

.platinum_icon {
    color: var(--accent);
    transition: transform 0.3s;
}

.create_cost:hover .platinum_icon {
    transform: scale(1.15);
}

.create_form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.create_label {
    color: var(--accent);
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
}

.create_label i {
    margin-right: 8px;
}

.create_input {
    padding: 12px 18px;
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    font-size: 15px;
    transition: all 0.3s;
    background: var(--glass-bg);
    color: var(--text);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(8px);
    font-family: 'Inter', sans-serif;
}

.create_input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    background: var(--item-hover);
}

.create_button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 25px auto 0;
    padding: 12px 30px;
    background: var(--primary-button);
    color: #111;
    border: none;
    border-radius: var(--radius);
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s;
    min-width: 220px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.create_button i {
    margin-right: 10px;
}

.create_button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    background: var(--primary-button-hover);
}

.create_button:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .create_container {
        padding: 0 10px;
    }
    
    .create_content {
        padding: 20px;
    }
    
    .create_header {
        font-size: 20px;
    }
    
    .create_info {
        font-size: 14px;
    }
    
    .create_cost {
        padding: 10px 15px;
        font-size: 15px;
    }
    
    .create_button {
        padding: 10px 25px;
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .create_content {
        padding: 15px;
    }
    
    .create_header {
        font-size: 18px;
    }
    
    .create_info {
        font-size: 13px;
    }
    
    .create_cost {
        font-size: 14px;
    }
    
    .create_label {
        font-size: 14px;
    }
    
    .create_input {
        padding: 10px 15px;
        font-size: 14px;
    }
    
    .create_button {
        padding: 10px 20px;
        font-size: 14px;
        min-width: 200px;
    }
}
</style>
</head>
<body>

<div class="create_container">
    <div class="create_content">
        <div class="create_header">
            <i class="fas fa-users"></i> Создание клана
        </div>
        
        <div class="create_info">
            Создайте свой собственный клан, наберите сильных воинов и займите достойное место в рейтинге!
        </div>
        
        <div class="create_cost">
            <i class="fas fa-gem platinum_icon"></i> Стоимость создания: 100 платины
        </div>
        
        <form class="create_form" id="createClanForm" onsubmit="createClan(); return false;">
            <label class="create_label" for="clanNameInput">
                <i class="fas fa-tag"></i> Название клана:
            </label>
            <input class="create_input" type="text" id="clanNameInput" name="clanName" placeholder="Введите название клана" required maxlength="20">
            
            <button type="submit" class="create_button">
                <i class="fas fa-flag"></i> Создать клан
            </button>
        </form>
    </div>
</div>

<script>
    function createClan() {
        const clanName = document.getElementById('clanNameInput').value;
        if (clanName.trim() === '') {
            alert('Введите название клана');
            return;
        }
        
        showContent('/clan/create.php?create&name=' + encodeURIComponent(clanName));
    }
</script>
<?php
$footval = 'clan';
require_once ('../system/foot/foot.php');
?>
</body>
</html>
