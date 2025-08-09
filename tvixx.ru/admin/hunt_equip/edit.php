<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}

$id_loc = 0;
if (isset($_GET['id_loc'])) {
    $id_loc = $_GET['id_loc'];
}
$thisloc = $mc->query("SELECT * FROM `location` WHERE `id` = '$id_loc'")->fetch_array(MYSQLI_ASSOC);
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $mob = $mc->query("SELECT * FROM `hunt_equip` WHERE `id` = '" . $_GET['delete'] . "'")->fetch_array(MYSQLI_ASSOC);
    $mc->query("DELETE FROM `hunt_equip` WHERE `id` = '" . $_GET['delete'] . "' LIMIT 1");
    $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> убрал моба </font><a onclick=\\'showContent(\\\"/admin/hunt_equip/edit.php?id_loc=" . $id_loc . "\\\")\\'><font color=\\'#0033cc\\'>" . $mob['name'] . " из " . $thisloc['Name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
    $mc->query("INSERT INTO `chat`("
            . "`id`,"
            . "`name`,"
            . "`id_user`,"
            . "`chat_room`,"
            . "`msg`,"
            . "`msg2`,"
            . "`time`,"
            . " `unix_time"
            . "`) VALUES ("
            . "NULL,"
            . "'АДМИНИСТРИРОВАНИЕ',"
            . "'',"
            . "'5',"
            . " '" . $chatmsg . "',"
            . "'',"
            . "'',"
            . "''"
            . " )");
}
if (isset($_GET['add']) && $_GET['add'] > 0) {
    $mob = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . $_GET['add'] . "'")->fetch_array(MYSQLI_ASSOC);
    $mc->query("INSERT INTO `hunt_equip` "
            . "("
            . " `id`,"
            . " `id_hunt`,"
            . " `id_loc`,"
            . " `name`,"
            . " `level`"
            . ")VALUES ("
            . "NULL,"
            . " '" . $_GET['add'] . "',"
            . " '$id_loc',"
            . " '" . $mob['name'] . "',"
            . " '" . $mob['level'] . "'"
            . ");");
    if (empty($mob['name'])) {
        $mob['name'] = "???";
    }

    $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> добавил моба </font><a onclick=\\'showContent(\\\"/admin/hunt_equip/edit.php?id_loc=" . $id_loc . "\\\")\\'><font color=\\'#0033cc\\'>" . $mob['name'] . " в " . $thisloc['Name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
    $mc->query("INSERT INTO `chat`("
            . "`id`,"
            . "`name`,"
            . "`id_user`,"
            . "`chat_room`,"
            . "`msg`,"
            . "`msg2`,"
            . "`time`,"
            . " `unix_time"
            . "`) VALUES ("
            . "NULL,"
            . "'АДМИНИСТРИРОВАНИЕ',"
            . "'',"
            . "'5',"
            . " '" . $chatmsg . "',"
            . "'',"
            . "'',"
            . "''"
            . " )");
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
    
    .hunt-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .hunt-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .mob-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .mob-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        background: var(--item-hover);
    }
    
    .mob-item {
        display: flex;
        align-items: center;
        padding: 15px;
    }
    
    .mob-number {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--glass-bg);
        border-radius: 50%;
        margin-right: 15px;
        font-weight: 600;
        color: var(--accent);
        flex-shrink: 0;
    }
    
    .mob-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
        gap: 8px;
    }
    
    .mob-actions {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1;
        min-height: 40px;
        min-width: 90px;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .button.delete {
        background: var(--danger-gradient);
    }
    
    .button.add {
        background: var(--success-gradient);
    }
    
    .mob-link {
        color: var(--accent);
        text-decoration: underline;
        cursor: pointer;
        transition: color 0.3s;
    }
    
    .mob-link:hover {
        color: var(--accent-2);
    }
    
    .mob-quest-icon {
        display: flex;
        align-items: center;
        margin-right: 5px;
    }
    
    .mob-icon {
        display: flex;
        align-items: center;
        margin-right: 5px;
    }
    
    .mob-name {
        font-weight: 500;
    }
    
    .mob-level {
        color: var(--muted);
        font-size: 14px;
        margin-left: 5px;
    }
    
    .mob-id {
        color: var(--muted);
        font-size: 12px;
        margin-left: 5px;
    }
    
    .search-panel {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-top: 20px;
        padding: 20px;
    }
    
    .search-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .search-input {
        flex-grow: 1;
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        height: 40px;
        box-sizing: border-box;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .search-id {
        width: 80px;
        flex-shrink: 0;
    }
    
    .search-results {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .search-result-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        background: var(--secondary-bg);
        border-radius: var(--radius);
        margin-bottom: 10px;
    }
    
    .search-result-item:hover {
        background: var(--item-hover);
    }
    
    .search-result-name {
        flex-grow: 1;
        text-align: center;
    }
    
    .divider {
        height: 1px;
        background: var(--glass-border);
        margin: 20px 0;
    }
    
    @media (max-width: 768px) {
        .mob-item, .search-form {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .mob-info {
            margin: 10px 0;
        }
        
        .mob-actions {
            margin-left: 0;
            width: 100%;
        }
        
        .button {
            width: 100%;
        }
        
        .search-id {
            width: 100%;
        }
    }
</style>

<div class="hunt-container">
    <h2 class="hunt-header">Редактор охоты: <?= $thisloc['Name']; ?></h2>
    
    <div class="mob-list">
        <?php
        $arrMobIdAll = $mc->query("SELECT * FROM `hunt_equip` WHERE `id_loc` = '$id_loc' ORDER BY `level`")->fetch_all(MYSQLI_ASSOC);
        for ($i = 0; $i < count($arrMobIdAll); $i++) {
            $mob = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . $arrMobIdAll[$i]['id_hunt'] . "'")->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="mob-card">
                <div class="mob-item">
                    <div class="mob-number"><?= $i + 1; ?></div>
                    <div class="mob-info">
                        <?php if($mob['quests']==1){ ?>
                        <div class="mob-quest-icon">
                            <img src="/img/quest.png?136.2231" alt="Квест" title="Квестовый моб">
                        </div>
                        <?php } ?>
                        <div class="mob-icon">
                            <img height=15 src="/img/icon/mob/<?= $mob['iconid']; ?>.png" width=15 alt="Моб">
                        </div>
                        <div class="mob-name">
                            <?= $mob['name']; ?>
                            <span class="mob-level">[<?= $mob['level']; ?>]</span>
                            <span class="mob-id">id:<?= $arrMobIdAll[$i]['id_hunt']; ?></span>
                        </div>
                    </div>
                    <div class="mob-actions">
                        <span class="mob-link" onclick="showContent('/admin/hunt.php?mob=edit&id=<?= $arrMobIdAll[$i]['id_hunt']; ?>')">
                            Изменить
                        </span>
                        <button onclick="showContent('/admin/hunt_equip/edit.php?id_loc=<?= $id_loc; ?>&delete=<?= $arrMobIdAll[$i]['id']; ?>')" class="button delete">Убрать</button>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    
    <div class="search-panel">
        <div class="search-form">
            <input class="search-input search-id id_monster" type="number" value="0" placeholder="ID моба">
            <input class="search-input name_monster" type="text" placeholder="Поиск по имени" onkeyup="search(this.value)">
            <button onclick="add();" class="button add">Добавить</button>
        </div>
        
        <div class="search search-results"></div>
    </div>
</div>

<script>
    function add() {
        showContent('/admin/hunt_equip/edit.php?id_loc=<?= $id_loc; ?>&add=' + $(".id_monster").val());
    }
    function add2(a) {
        showContent('/admin/hunt_equip/edit.php?id_loc=<?= $id_loc; ?>&add=' + a);
    }
    function search(etext) {
        var arr;
        $.ajax({
            type: "POST",
            url: "/admin/hunt_equip/search.php?etext=" + etext,
            dataType: "text",
            success: function (data) {
                $(".search").html("");
                if (data != "") {
                    arr = JSON.parse(data);
                    for (var i = 0; i < arr.length; i++) {
                        addMonsterSearched(arr[i].name, arr[i].level, arr[i].id);
                    }
                }
            },
            error: function () {
                $(".search").html("error");
            }
        });
    }
    function addMonsterSearched(name, level, id) {
        $(".search").append(
            '<div class="search-result-item">' +
            '<div class="search-result-name">' + name + ' <span class="mob-level">[' + level + ']</span> <span class="mob-id">id:' + id + '</span></div>' +
            '<button onclick="add2(' + id + ');" class="button add">Добавить</button>' +
            '</div>'
        );
    }
</script>

<?php
$footval = 'hunt_edit';
include '../../system/foot/foot.php';
?>