<?php
require_once '../system/func.php';
$clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC);

$tur_list = $mc->query("SELECT * FROM `tur_list` ")->fetch_all(MYSQLI_ASSOC);

// Стили для сообщений в едином стиле
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
}

.clan_header {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
    color: var(--accent);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.clan_card {
    position: relative;
    padding: 20px;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    color: var(--text);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
    text-align: center;
}

.clan_gold {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 15px;
    font-size: 16px;
    color: var(--accent);
}

.clan_rating {
    color: var(--muted);
    font-size: 15px;
}

.clan_list {
    display: grid;
    gap: 15px;
}

.tournament_item {
    position: relative;
    padding: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.tournament_item:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    background: var(--item-hover);
    border-color: var(--accent-2);
}

.tournament_cost {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 12px;
    color: var(--accent);
    font-weight: 500;
}

.tournament_threshold {
    color: var(--muted);
    font-size: 15px;
}

.gold_icon {
    width: 18px;
    height: 18px;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
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

@media (max-width: 480px) {
    .clan_container {
        padding: 0 10px;
        margin: 10px auto;
    }
    
    .clan_header {
        font-size: 20px;
    }
    
    .clan_card {
        padding: 15px;
    }
    
    .tournament_item {
        padding: 15px;
    }
    
    .clan_gold {
        font-size: 14px;
    }
    
    .tournament_threshold {
        font-size: 13px;
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
function showClanModal($message, $buttonText = 'Согласиться', $redirectUrl = '/clan/reg_tur.php') {
    echo '<div class="clan_modal">
        <div class="clan_modal_content">
            <div class="clan_modal_text">'.$message.'</div>
            <button class="clan_modal_button" onclick="showContent(\''.$redirectUrl.'\')">'.$buttonText.'</button>
        </div>
    </div>';
    exit(0);
}

// Функция сообщений без использования JavaScript alert()
function message($text, $isError = false) {
    showClanModal($text, 'Согласиться');
}

if(isset($_GET['go']) && isset($_GET['id'])) {
	$tur = $mc->query("SELECT * FROM `tur_list` WHERE `id` = '".intval($_GET['id'])."'")->fetch_array(MYSQLI_ASSOC);
	if($user['des'] == 3) {
		if(!$mc->query("SELECT `id_clan` FROM `req_tur` WHERE `id_clan` = '".$user['id_clan']."'")->fetch_array(MYSQLI_ASSOC)) {
			if($clan['gold'] >= $tur['gold']) {
				if($mc->query("INSERT INTO `req_tur` (`id_clan`,`id_tur`) VALUES ('".$user['id_clan']."','".intval($tur['id'])."')")) {
					$mc->query("UPDATE `clan` SET `gold` = `gold` - '".$tur['gold']."' WHERE `id` = '".$user['id_clan']."'");
					message("Турнир успешно организован");
				} else {
					message("Произошла неизвестная ошибка", true);
				}
			} else {
				message("В казне клана недостаточно средств", true);
			}
		} else {
			message("У вас уже есть организованный турнир", true);
		}
	} else {
		message("Организовывать турниры может только вождь клана", true);
	}
}
?>

<div class="clan_container">
    <div class="clan_header">
        <i class="fas fa-trophy"></i> Организация турнира
    </div>
    
    <div class="clan_card">
        <div class="clan_gold">
            <i class="fas fa-coins" style="color: var(--accent);"></i>
            Казна клана: 
            <img class="gold_icon" src="/images/icons/zoloto.png" alt="gold">
            <?= $clan['gold']; ?>
        </div>
        <div class="clan_rating">
            <i class="fas fa-star"></i> Порог рейтинга: 0
        </div>
    </div>

    <div class="clan_list">
        <?php foreach($tur_list as $tur): ?>
            <div class="tournament_item" onclick="showContent('/clan/reg_tur.php?go&id=<?= $tur['id']; ?>')">
                <div class="tournament_cost">
                    <i class="fas fa-coins" style="color: var(--accent);"></i>
                    Стоимость: 
                    <img class="gold_icon" src="/images/icons/zoloto.png" alt="gold">
                    <?= $tur['gold']; ?>
                </div>
                <div class="tournament_threshold">
                    <i class="fas fa-award"></i> Порог рейтинга клана: +<?= $tur['porog']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$footval = "kazna";
require_once '../system/foot/foot.php';
?>

    
	