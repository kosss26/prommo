<?php
require_once ('../system/dbc.php');
require_once ('../system/func.php');
if (!$user OR $user['access'] < 3) {
    ?>
    <script>showContent("/");</script>
    <?php
    exit;
}
$drespers = [];
$persequip1 = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `dress`=1");
while ($persequip = $persequip1->fetch_array(MYSQLI_ASSOC)) {
    $namedress = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $persequip['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
    if ($namedress['stil'] > 0) {
        $colorStyle = ["black", "green", "blue", "red", "yellow"];
        $namedress['name'] = '<font style="color:' . $colorStyle[$namedress['stil']] . ';font-weight: bold;">' . $namedress['name'] . '</font>';
    }
    if (!isset($drespers[$persequip['id_punct']])) {
        $drespers[$persequip['id_punct']] = "";
    }
    if ($drespers[$persequip['id_punct']] != "") {
        if ($persequip['id_punct'] == 9) {
            if ($persequip['koll'] >= 0 && $persequip['koll'] < 99) {
                $drespers[$persequip['id_punct']] .= ' , ' . $namedress['name'] . '(' . $persequip['koll'] . ')';
            } else {
                $drespers[$persequip['id_punct']] .= ' , ' . $namedress['name'] . '(99)';
            }
        } else {
            if ($persequip['iznos'] >= 0 && $persequip['iznos'] < 99) {
                $drespers[$persequip['id_punct']] .= ' , ' . $namedress['name'];
            } else {
                $drespers[$persequip['id_punct']] .= ' , ' . $namedress['name'];
            }
        }
    } else {
        if ($persequip['id_punct'] == 9) {
            if ($persequip['koll'] >= 0 && $persequip['koll'] < 99) {
                $drespers[$persequip['id_punct']] .= $namedress['name'] . '(' . $persequip['koll'] . ')';
            } else {
                $drespers[$persequip['id_punct']] .= $namedress['name'] . '(99)';
            }
        } else {
            if ($persequip['iznos'] >= 0 && $persequip['iznos'] < 99) {
                $drespers[$persequip['id_punct']] .= $namedress['name'];
            } else {
                $drespers[$persequip['id_punct']] .= $namedress['name'];
            }
        }
    }
}

for ($i = 1; $i < 9; $i++) {
    if (!isset($drespers[$i])) {
        $drespers[$i] = "-";
    }
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
        --armor-color: #e74c3c;
        --damage-color: #2ecc71;
        --dodge-color: #3498db;
        --elite-color: #f5c15d;
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .shkaf-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .shkaf-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .shkaf-card {
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
    
    .shkaf-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .equipment-grid {
        display: grid;
        grid-template-columns: 1fr 3fr;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .equipment-slot {
        padding: 12px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        border: 1px solid var(--glass-border);
    }
    
    .slot-label {
        font-weight: 600;
        color: var(--accent);
        margin-right: 10px;
        min-width: 100px;
    }
    
    .slot-value {
        flex: 1;
    }
    
    .admin-section {
        margin-top: 30px;
        border-top: 1px solid var(--glass-border);
        padding-top: 20px;
    }
    
    .admin-header {
        text-align: center;
        color: var(--accent);
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
    }
    
    .admin-header::before,
    .admin-header::after {
        content: '';
        position: absolute;
        top: 50%;
        height: 1px;
        background: var(--glass-border);
        width: 30%;
    }
    
    .admin-header::before {
        left: 0;
    }
    
    .admin-header::after {
        right: 0;
    }
    
    .inventory-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 15px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .inventory-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .item-name {
        flex: 1;
        font-weight: 500;
    }
    
    .item-equipped {
        font-weight: 700;
    }
    
    .item-actions {
        display: flex;
        gap: 10px;
    }
    
    .item-action {
        color: var(--accent);
        cursor: pointer;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: var(--radius);
        background: var(--glass-bg);
        transition: all 0.3s ease;
    }
    
    .item-action:hover {
        background: var(--accent);
        color: #111;
    }
    
    .item-action.delete {
        color: var(--danger-color);
    }
    
    .item-action.delete:hover {
        background: var(--danger-color);
        color: #fff;
    }
    
    .search-form {
        display: grid;
        grid-template-columns: 50px 1fr auto;
        gap: 10px;
        margin: 20px 0;
    }
    
    .search-input,
    .id-input {
        width: 100%;
        padding: 12px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        height: 45px;
        box-sizing: border-box;
    }
    
    .search-input:focus,
    .id-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 12px 15px;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 45px;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .search-results {
        margin-top: 20px;
    }
    
    .search-item {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        padding: 12px 15px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .search-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .search-item-info {
        font-weight: 500;
    }
    
    /* Специфичные стили для разных типов предметов */
    .style-black { color: var(--text); }
    .style-green { color: var(--damage-color); }
    .style-blue { color: var(--dodge-color); }
    .style-red { color: var(--armor-color); }
    .style-yellow { color: var(--elite-color); }

    @media (max-width: 768px) {
        .equipment-grid {
            grid-template-columns: 1fr;
        }
        
        .search-form {
            grid-template-columns: 1fr;
        }
        
        .search-item {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }
</style>

<div class="shkaf-container">
    <h2 class="shkaf-header">Шкаф персонажа</h2>
    
    <div class="shkaf-card">
        <div class="equipment-grid">
            <div class="slot-label">Оружие:</div>
            <div class="slot-value"><?= isset($drespers[1]) ? $drespers[1] : "-"; ?></div>
            
            <div class="slot-label">Защита:</div>
            <div class="slot-value"><?= isset($drespers[2]) ? $drespers[2] : "-"; ?></div>
            
            <div class="slot-label">Шлем:</div>
            <div class="slot-value"><?= isset($drespers[3]) ? $drespers[3] : "-"; ?></div>
            
            <div class="slot-label">Перчатки:</div>
            <div class="slot-value"><?= isset($drespers[4]) ? $drespers[4] : "-"; ?></div>
            
            <div class="slot-label">Доспехи:</div>
            <div class="slot-value"><?= isset($drespers[5]) ? $drespers[5] : "-"; ?></div>
            
            <div class="slot-label">Обувь:</div>
            <div class="slot-value"><?= isset($drespers[6]) ? $drespers[6] : "-"; ?></div>
            
            <div class="slot-label">Амулет:</div>
            <div class="slot-value"><?= isset($drespers[7]) ? $drespers[7] : "-"; ?></div>
            
            <div class="slot-label">Кольца:</div>
            <div class="slot-value"><?= isset($drespers[8]) ? $drespers[8] : "-"; ?></div>
            
            <div class="slot-label">Пояс:</div>
            <div class="slot-value"><?= isset($drespers[9]) ? $drespers[9] : "-"; ?></div>
        </div>

        <?php if ($user['access'] > 2): ?>
            <div class="admin-section">
                <div class="admin-header">Управление инвентарём</div>
                
                <div class="inventory-list">
                    <?php 
                    $persequip3 = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' ORDER BY `userbag`.`id_punct` ASC, `id` ASC");
                    while ($persequip2 = $persequip3->fetch_array(MYSQLI_ASSOC)) {
                        $namedress2 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $persequip2['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
                    ?>
                        <div class="inventory-item">
                            <div class="item-name <?= $persequip2['dress'] == "1" ? 'item-equipped' : '' ?>">
                                <?= $namedress2["name"]; ?>
                            </div>
                            <div class="item-actions">
                                <?php if ($persequip2['dress'] == "1"): ?>
                                    <div class="item-action" onclick="showContent('/admin/shkaf.php?weshEdit=h&ids=<?= $persequip2["id"]; ?>')">Снять</div>
                                <?php else: ?>
                                    <div class="item-action" onclick="showContent('/admin/shkaf.php?weshEdit=s&ids=<?= $persequip2["id"]; ?>')">Одеть</div>
                                <?php endif; ?>
                                <div class="item-action delete" onclick="showContent('/admin/shkaf.php?weshEdit=d&ids=<?= $persequip2["id"]; ?>')">Удалить</div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="search-form">
                    <input type="number" class="id-input id_dress" value="0">
                    <input type="text" onkeyup="searchdress(this.value)" class="search-input name_dress" placeholder="Поиск предметов...">
                    <button onclick="add();" class="button">Добавить</button>
                </div>
                
                <div class="search-results search"></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function add() {
        showContent('/admin/shkaf.php?weshEdit=a&ids=' + $(".id_dress").val());
    }
    function add2(a) {
        showContent('/admin/shkaf.php?weshEdit=a&ids=' + a);
    }

    function searchdress(etext) {
        var arr;
        $.ajax({
            type: "POST",
            url: "/admin/shop/search.php?etext=" + etext,
            dataType: "text",
            success: function (data) {
                $(".search").html("");
                if (data != "") {
                    arr = JSON.parse(data);
                    for (var i = 0; i < arr.length; i++) {
                        addDressSearched(arr[i].name, arr[i].level, arr[i].id);
                    }
                }
            },
            error: function () {
                $(".search").html("<div class='search-item'>Ошибка при поиске</div>");
            }
        });
    }

    function addDressSearched(name, level, id) {
        $(".search").append(
            '<div class="search-item">' +
                '<div class="search-item-info">' + name + ' [' + level + '] id: ' + id + '</div>' +
                '<button onclick="add2(' + id + ');" class="button">Добавить</button>' +
            '</div>'
        );
    }
</script>

<?php
if (isset($_GET['weshEdit']) && isset($_GET['ids'])) {
    echo $_GET['weshEdit'];

    if ($_GET['weshEdit'] == "h" || $_GET['weshEdit'] == "s") {
        $hs = 0;
        if ($_GET['weshEdit'] == "s") {
            $hs = 1;
        }
        //hide - снять
        echo $_GET['ids'] . "Снять";
        $dresssnyatreid = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id`='" . $_GET['ids'] . "'")->fetch_array(MYSQLI_ASSOC);
        $mc->query("DELETE FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id`='" . $_GET['ids'] . "'");
        $mc->query("INSERT INTO `userbag`("
                . "`id_user`,"
                . " `id_shop`,"
                . " `id_punct`,"
                . " `dress`,"
                . " `iznos`,"
                . " `id_quests`,"
                . " `koll`,"
                . " `max_hc`,"
                . " `time_end`,"
                . " `stil`,"
                . " `BattleFlag`"
                . ") VALUES ("
                . "'" . $dresssnyatreid['id_user'] . "',"
                . "'" . $dresssnyatreid['id_shop'] . "',"
                . "'" . $dresssnyatreid['id_punct'] . "',"
                . "'" . $hs . "',"
                . "'" . $dresssnyatreid['iznos'] . "',"
                . "'" . $dresssnyatreid['id_quests'] . "',"
                . "'" . $dresssnyatreid['koll'] . "',"
                . "'" . $dresssnyatreid['max_hc'] . "',"
                . "'" . $dresssnyatreid['time_end'] . "',"
                . "'" . $dresssnyatreid['stil'] . "',"
                . "'" . $dresssnyatreid['BattleFlag'] . "'"
                . ")");
    }


    if ($_GET['weshEdit'] == "d") {
        //hide - удалить
        $mc->query("DELETE FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id`='" . $_GET['ids'] . "'");
    }

    if ($_GET['weshEdit'] == "a") {
        //add добавить
        //echo "Добавлю потом";
        $dresssnyatreid = $mc->query('SELECT * FROM `shop` WHERE `id`=' . $_GET['ids'])->fetch_array(MYSQLI_ASSOC);
        //дата истечения в unix
        if ($dresssnyatreid['time_s'] > 0) {
            $time_the_lapse = $dresssnyatreid['time_s'] + time();
        } else {
            $time_the_lapse = 0;
        }
        $mc->query("INSERT INTO `userbag`("
                . "`id_user`,"
                . " `id_shop`,"
                . " `id_punct`,"
                . " `dress`,"
                . " `iznos`,"
                . " `id_quests`,"
                . " `koll`,"
                . " `max_hc`,"
                . " `time_end`,"
                . " `stil`,"
                . " `BattleFlag`"
                . ") VALUES ("
                . "'" . $profile['id'] . "',"
                . "'" . $dresssnyatreid['id'] . "',"
                . "'" . $dresssnyatreid['id_punct'] . "',"
                . "'0',"
                . "'" . $dresssnyatreid['iznos'] . "',"
                . "'" . $dresssnyatreid['id_quests'] . "',"
                . "'" . $dresssnyatreid['koll'] . "',"
                . "'" . $dresssnyatreid['max_hc'] . "',"
                . "'$time_the_lapse',"
                . "'" . $dresssnyatreid['stil'] . "',"
                . "'" . $dresssnyatreid['BattleFlag'] . "'"
                . ")");
    }

    $arr = $mc->query("SELECT `stil` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` < '10' && `dress` ='1' GROUP BY `stil` ASC")->fetch_all(MYSQLI_ASSOC);
    if (count($arr) == 2) {
        $stil = $arr[1]['stil'];
    } elseif (count($arr) == 1 && $arr[0]['stil'] != 0) {
        $stil = $arr[0]['stil'];
    } elseif (count($arr) < 2) {
        $stil = 0;
    } else {
        $stil = 5;
    }
    $mc->query("UPDATE `users` SET `stil`='$stil' WHERE `id` = '" . $user['id'] . "'");
    ?> <script>showContent('/admin/shkaf.php?<?php echo $user['id']; ?>/1');</script> <?php
}

$footval = "main";
require_once ('../system/foot/foot.php');
?>