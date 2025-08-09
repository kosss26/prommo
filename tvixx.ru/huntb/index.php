<?php
require_once '../system/func.php';
ob_start();
$mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "' && (`type`='1'||`type`='2')");
if (isset($user['level']) && $user['level'] < 2) {
    ?><script>/*nextshowcontemt*/showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script><?php
    exit(0);
}
//если герой зарегистрирован на турниры то кинуть в нужный турнир
if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "'")->num_rows > 0) {
    $arrUserToor = $mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
    if ($arrUserToor['type'] == 1) {
        //1x1 showContent('/huntb/1x1/search.php')
        ?><script>/*nextshowcontemt*/showContent('/huntb/1x1/search.php');</script><?php
        exit(0);
    }
    if ($arrUserToor['type'] == 2) {
        //1x1 open
        ?><script>/*nextshowcontemt*/showContent('/huntb/1x1_open/search.php');</script><?php
        exit(0);
    }
    if ($arrUserToor['type'] == 3) {
        //vjv gold
        ?><script>/*nextshowcontemt*/showContent('/huntb/tur/vjv/in.php');</script><?php
        exit(0);
    }
    if ($arrUserToor['type'] == 4) {
        //vjv plat
        ?><script>/*nextshowcontemt*/showContent('/huntb/tur/vjv/in.php');</script><?php
        exit(0);
    }
    if ($arrUserToor['type'] == 5) {
        //stenka gold
        ?><script>/*nextshowcontemt*/showContent('/huntb/tur/stenka/in.php');</script><?php
        exit(0);
    }
    if ($arrUserToor['type'] == 6) {
        //stenka plat
        ?><script>/*nextshowcontemt*/showContent('/huntb/tur/stenka/in.php');</script><?php
        exit(0);
    }
}
?>
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

.battle-arena {
    max-width: 600px;
    margin: 0 auto;
    padding: 15px;
}

.battle-menu {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.battle-button {
    background: var(--glass-bg);
    color: var(--text);
    padding: 14px 20px;
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    font-size: 15px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.battle-button:hover {
    background: var(--item-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    color: var(--accent);
}

.battle-button:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.battle-button.primary {
    background: var(--primary-button);
    color: #111;
    font-weight: 600;
    border: none;
}

.battle-button.primary:hover {
    background: var(--primary-button-hover);
    color: #111;
}

.battle-info {
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.battle-info-section {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--glass-border);
}

.battle-info-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.battle-info-title {
    color: var(--accent);
    font-size: 17px;
    font-weight: 600;
    margin-bottom: 10px;
}

.battle-info-text {
    color: var(--muted);
    line-height: 1.5;
    font-size: 14px;
}

.battle-header {
    text-align: center;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 15px;
    backdrop-filter: blur(8px);
}

.battle-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 5px;
}

.battle-subtitle {
    font-size: 14px;
    color: var(--muted);
}

@media (max-width: 768px) {
    .battle-arena {
        padding: 10px;
    }
    
    .battle-menu {
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .battle-button {
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .battle-info {
        padding: 15px;
    }
    
    .battle-title {
        font-size: 17px;
    }
    
    .battle-subtitle {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .battle-menu {
        grid-template-columns: 1fr;
    }
    
    .battle-button {
        padding: 10px 15px;
        font-size: 13px;
    }
    
    .battle-info-title {
        font-size: 16px;
    }
    
    .battle-info-text {
        font-size: 13px;
    }
}
</style>
<div class="battle-arena">
    <div class="battle-header">
        <div class="battle-title">Боевая арена</div>
        <div class="battle-subtitle">Выберите тип сражения</div>
    </div>

    <div class="battle-menu">
        <div class="battle-button primary" onclick="showContent('/huntb/1x1/search.php')">
            <i class="fas fa-swords"></i> Начать поединок
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/1x1_open/search.php')">
            <i class="fas fa-users"></i> Открытый бой
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/1x1_tec/tec.php')">
            <i class="fas fa-history"></i> Текущие бои
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/tur/index.php')">
            <i class="fas fa-trophy"></i> Турнир
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/grab/index.php')">
            <i class="fas fa-coins"></i> Грабежи
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/zem/index.php')">
            <i class="fas fa-mountain"></i> Земля
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/clantur/index.php')">
            <i class="fas fa-flag"></i> Турниры кланов
        </div>
        
        <div class="battle-button" onclick="showContent('/huntb/luntur/index.php')">
            <i class="fas fa-moon"></i> Турнир полной луны
        </div>
    </div>

    <div class="battle-info">
        <div class="battle-info-section">
            <div class="battle-info-title">Начать поединок</div>
            <div class="battle-info-text">
                Бой один на один с равным противником. Победитель получает награду и рейтинговые очки.
            </div>
        </div>
        
        <div class="battle-info-section">
            <div class="battle-info-title">Открытый бой</div>
            <div class="battle-info-text">
                Бой с равным противником, открытый для вмешательства других бойцов, на три уровня старше или младше включительно. Более высокая награда за победу!
            </div>
        </div>
        
        <div class="battle-info-section">
            <div class="battle-info-title">Турниры</div>
            <div class="battle-info-text">
                Участвуйте в турнирах для получения эксклюзивных наград и титулов. Турниры проводятся регулярно, следите за расписанием.
            </div>
        </div>
        
        <div class="battle-info-section">
            <div class="battle-info-title">Грабежи</div>
            <div class="battle-info-text">
                Нападайте на других игроков в локациях и получайте часть их ресурсов в случае победы.
            </div>
        </div>
    </div>
</div>
<?php
$footval = "huntindex";
require_once ('../system/foot/foot.php');
ob_end_flush();
?>