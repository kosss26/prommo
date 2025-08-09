<?php
require_once '../system/func.php';
require_once '../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}
if (isset($_GET['ticket'])) {
    $r_query = $mc->query("SELECT * FROM  `ticket` ");
    while ($result = $r_query->fetch_array(MYSQLI_ASSOC)) {
        ?>
        <div class="ticket-item">
            <span class="ticket-user"><?= $result['user']; ?></span>
            <span class="ticket-text"><?= $result['text']; ?></span>
        </div>
        <?php
    }
}
$donat = 0;
$rebootEv = 0;

$dnt1 = $mc->query("SELECT * FROM `buyplata` WHERE `status`='1'");
$eventReboot = $mc->query("SELECT COUNT(*) as `pay` FROM `buyplata` WHERE `status` = 1 AND `event` = 1")->fetch_array(MYSQLI_ASSOC);
while ($dnt = $dnt1->fetch_array(MYSQLI_ASSOC)) {
    $donat += (int) $dnt['colvo'];
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
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .admin-panel {
        max-width: 900px;
        margin: 0 auto;
    }
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .stats {
        background: var(--card-bg);
        padding: 20px;
        border-radius: var(--radius);
        margin-bottom: 25px;
        text-align: center;
        border: 1px solid var(--glass-border);
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        overflow: hidden;
    }
    
    .stats::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .section {
        margin-bottom: 30px;
    }
    
    .section-title {
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        padding: 15px;
        border-radius: var(--radius);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #111;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            transparent,
            rgba(255, 255, 255, 0.1),
            transparent
        );
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }
    
    @keyframes shine {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .button-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
    }
    
    .button_alt_01 {
        background: var(--glass-bg);
        color: var(--text);
        padding: 15px;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        text-align: center;
        cursor: pointer;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 50px;
    }
    
    .button_alt_01:hover {
        background: var(--item-hover);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border-color: var(--accent);
    }
    
    .button_alt_01[style*="color: cyan"] {
        background: linear-gradient(135deg, #00a8b5, #00627a);
        color: #fff !important;
        border: none;
        font-weight: 600;
    }
    
    .button_alt_01[style*="color: cyan"]:hover {
        background: linear-gradient(135deg, #00b9c8, #00718c);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 6, 22, 0.3);
    }
    
    .ticket-item {
        background: var(--secondary-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .ticket-user {
        font-weight: 600;
        color: var(--accent);
    }
    
    .ticket-text {
        color: var(--text);
    }
    
    .search-container {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding: 20px;
        background: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }
    
    #ids_thing {
        width: 100%;
        max-width: 200px;
        padding: 15px;
        background: var(--secondary-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    #ids_thing:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .search-btn {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #111;
        border: none;
        padding: 15px 20px;
        font-size: 15px;
        border-radius: var(--radius);
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        flex-grow: 1;
        max-width: 200px;
    }
    
    .search-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    @media (max-width: 768px) {
        .button-grid {
            grid-template-columns: 1fr 1fr;
            justify-items: center;
            gap: 10px;
        }
        
        .admin-panel {
            padding: 0 10px;
        }
        
        .button_alt_01 {
            width: 100%;
        }
    }
    
    @media (max-width: 600px) {
        .button-grid {
            grid-template-columns: 1fr;
            justify-items: center;
            gap: 10px;
        }
        
        .search-container {
            flex-direction: column;
            align-items: center;
        }
        
        #ids_thing, .search-btn {
            width: 100%;
            max-width: none;
            margin: 5px 0;
        }
        
        .stats {
            padding: 15px;
            text-align: center;
        }
        
        h2 {
            font-size: 24px;
        }
        
        .admin-panel {
            width: 100%;
            max-width: 100%;
            padding: 10px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: 16px;
            padding: 12px 8px;
            text-align: center;
        }
        
        .button_alt_01 {
            min-height: 45px;
            width: 100%;
            max-width: 280px;
            margin: 0 auto;
            padding: 10px;
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
    }

    a {
        text-decoration: none;
        color: inherit;
    }
</style>

<div class="admin-panel">
    <h2>Админ-панель</h2>
    <div class="stats">
        <div>Донат: <?php echo $donat; ?> руб.</div>
        <div>REBOOT Купили: <?php echo $eventReboot['pay']; ?> человек. На сумму: <?php echo $eventReboot['pay'] * 150 * 0.9; ?> руб. (С учетом НДС)</div>
    </div>

    <div class="section">
        <div class="section-title">Управление игроками</div>
        <div class="button-grid">
            <div class="button_alt_01" onclick="showContent('/admin/admin.php?id=<?php echo $user['id']; ?>')">Редактор игроков</div>
            <div class="button_alt_01" onclick="showContent('/admin/heroes.php')">Редактор персонажей</div>
            <div class="button_alt_01" onclick="showContent('/admin/reputation.php')">Управление репутацией</div>
            <div class="button_alt_01" onclick="showContent('/admin/lvl.php?create=add')">Редактор уровней</div>
            <div class="button_alt_01" onclick="showContent('/admin/zvanie.php')">Управление званиями</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Управление контентом</div>
        <div class="button-grid">
            <div class="button_alt_01" onclick="showContent('/admin/quest/quest.php')">Редактор квестов</div>
            <div class="button_alt_01" onclick="showContent('/admin/shop.php?shop=add')">Создать предмет</div>
            <div class="button_alt_01" onclick="showContent('/admin/shop_equip/index.php')">Управление магазинами</div>
            <div class="button_alt_01" onclick="showContent('/admin/hunt.php?mob=add')">Редактор монстров</div>
            <div class="button_alt_01" onclick="showContent('/admin/hunt_equip/index.php')">Управление охотой</div>
            <div class="button_alt_01" onclick="showContent('/admin/location/')">Управление локациями</div>
            <div class="button_alt_01" onclick="showContent('/admin/battle/')">Управление боями</div>
            <div class="button_alt_01" onclick="showContent('/admin/auk/index.php')">Управление аукционом</div>
            <div class="button_alt_01" onclick="showContent('/admin/new.php')">Управление новостями</div>
            <div class="button_alt_01" onclick="showContent('/admin/gift.php')">Управление подарками</div>
            <div class="button_alt_01" onclick="showContent('/admin/holidays/index.php')">Праздничные квесты</div>
            <div class="button_alt_01" onclick="showContent('/admin/shop_view.php')">Эпическое снаряжение</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Системные действия</div>
        <div class="button-grid">
            <div class="button_alt_01" onclick="showContent('/admin/index.php?vitashitsboya')">Вывести всех из боев</div>
            <div class="button_alt_01" onclick="showContent('/admin/index.php?vilechitvseh')">Восстановить здоровье всем</div>
            <div class="button_alt_01" onclick="showContent('/admin/money.php')">Денежные переводы</div>
            <div class="button_alt_01" onclick="showContent('/admin/index.php?ticket')">Просмотр тикетов</div>
            <div class="button_alt_01" onclick="showContent('/admin/keygen.php')">Управление ключами ЗБТ</div>
            <div class="button_alt_01" onclick="showContent('/admin/index.php?testDonat')">Тест донат-бота ВК</div>
            <div class="button_alt_01" onclick="showContent('/admin/AlllUserMessages.php')">Сообщения пользователей</div>
            <div class="button_alt_01" onclick="showContent('/admin/index.php?deportation')">Депортировать всех на базу</div>
            <div class="button_alt_01" onclick="showContent('/admin/zarplata/zarplata.php')" style="color: cyan; font-size: 16px;">Выдача зарплат</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Поддержка</div>
        <div class="button-grid">
            <div class="button_alt_01" onclick="showContent('/admin/support.php')">Создать раздел помощи</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Скачать</div>
        <div class="button-grid">
            <a href="../mobitva2.apk" download>
                <div class="button_alt_01">Скачать APK клиент</div>
            </a>
        </div>
    </div>

    <div class="search-container">
        <input type="number" id="ids_thing" value="0" placeholder="ID предмета">
        <button class="search-btn butt1">Найти предмет</button>
    </div>
</div>

<script>
    $(".butt1").click(function () {
        showContent("/admin/index.php?findThing=" + $("#ids_thing").val());
    });
</script>

<?php
if (isset($_GET['vitashitsboya'])) {
    $mc->query("UPDATE `battle` SET `end_battle` = '1'");
    message('Все спасены, красаучик');
}
if (isset($_GET['vilechitvseh'])) {
    $mc->query("UPDATE `users` SET `temp_health` = `max_health`");
    message('Все здоровы, ты классный пацан!');
}
if (isset($_GET['testDonat'])) {
    $_GET['name_donaters'] = "Нефтяной Магнат";
    $_GET['donat'] = "1000000";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/vk.com/bot.php";
}
if (isset($_GET['deportation'])) {
    $mc->query("UPDATE `users` SET `location`='2' , `location_list` = '[]' WHERE`location`!='98' && `side`>='2' && `side`<='3' ");
    $mc->query("UPDATE `users` SET `location`='1' , `location_list` = '[]' WHERE`location`!='98' && `side`>='0' && `side`<='1' ");
    message('Все негры депортированы, ты классный пацан!');
}
if (isset($_GET['findThing']) && $_GET['findThing'] > 0) {
    message('вещи найдены!');
    $arr = $mc->query("SELECT COUNT(*),`id_user` FROM `userbag` WHERE `id_shop`='" . $_GET['findThing'] . "' GROUP BY `id_user` ORDER BY COUNT(*) DESC")->fetch_all(MYSQLI_ASSOC);
    for ($i = 0; $i < count($arr); $i++) {
        $tmp = $mc->query("SELECT * FROM `users` WHERE `id`='" . $arr[$i]['id_user'] . "'")->fetch_array(MYSQLI_ASSOC);
        echo "<div class='ticket-item'><span class='ticket-user'>" . $arr[$i]['COUNT(*)'] . " .</span><a onclick=\"showContent('/profile/" . $tmp['id'] . "')\"><span class='ticket-text'>" . $tmp['name'] . "</span></a></div>";
    }
}

function arrayValuesToInt($arr) {
    $arr2 = [];
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $arr2[] = arrayValuesToInt($value);
        } else {
            $arr2[] = intval($value);
        }
    }
    return $arr2;
}
?>

<?php
$footval = 'adminindex';
include '../system/foot/foot.php';
?>

<script>
// Код для обнаружения источника параметра attempt в URL
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.href.includes('attempt=')) {
        console.log('URL содержит параметр attempt. Стек вызовов:', new Error().stack);
        console.log('Реферер:', document.referrer);
        
        // Попытка найти JS код, который мог добавить этот параметр
        var scripts = document.querySelectorAll('script');
        scripts.forEach(function(script) {
            if (script.textContent && script.textContent.includes('attempt=')) {
                console.log('Найден скрипт с упоминанием attempt=', script.textContent);
            }
        });
        
        // Очистка URL от параметра attempt
        if (window.history && window.history.replaceState) {
            var newUrl = window.location.href.replace(/[?&]attempt=\d+/g, '');
            newUrl = newUrl.replace(/\?$/, ''); // Удаляем ? в конце URL если остался только он
            window.history.replaceState({}, document.title, newUrl);
        }
    }
});
</script>