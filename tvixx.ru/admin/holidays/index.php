<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
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
    
    .holidays-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .holidays-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .holidays-card {
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
    
    .holidays-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .holidays-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .holidays-table tr:not(:last-child) td {
        padding-bottom: 10px;
    }
    
    .holidays-table td {
        padding: 8px;
        vertical-align: middle;
    }
    
    .holidays-table th {
        color: var(--accent);
        font-weight: 600;
        text-align: center;
        padding: 0 8px 15px 8px;
        font-size: 14px;
    }
    
    input[type="text"], 
    input[type="number"],
    select {
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        width: 100%;
    }
    
    input[type="text"]:focus, 
    input[type="number"]:focus,
    select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f5c15d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
    }
    
    optgroup {
        background-color: var(--secondary-bg);
        color: var(--accent);
        font-weight: 600;
    }
    
    option {
        background-color: var(--glass-bg);
        color: var(--text);
        padding: 8px;
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 12px 20px;
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
        box-sizing: border-box;
        width: 100%;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .button.save {
        background: var(--success-gradient);
    }
    
    .button.delete {
        background: var(--danger-gradient);
    }
    
    .notes {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        padding: 15px;
        margin: 20px 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.5;
    }
    
    .notes strong {
        color: var(--accent);
    }
    
    @media (max-width: 768px) {
        .holidays-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<div class="holidays-container">
    <h2 class="holidays-header">Управление праздниками</h2>

    <?php
    if (isset($_GET['create']) && $_GET['create'] == "addbd") {
        $mc->query("INSERT INTO `holidays`("
                . "`id`,"
                . " `month`,"
                . " `days`,"
                . " `quests_id`,"
                . " `povtor`"
                . ") VALUES ("
                . "'NULL',"
                . "'" . $_GET['month'] . "',"
                . "'" . $_GET['days'] . "',"
                . "'" . $_GET['quests_id'] . "',"
                . "'" . $_GET['povtor'] . "'"
                . ")");
        ?><script>/*nextshowcontemt*/showContent("/admin/holidays/index.php?msg=" + encodeURIComponent("added"));</script><?php
        exit(0);
    }
    if (isset($_GET['create']) && $_GET['create'] == "save") {
        $mc->query("UPDATE `holidays` SET "
                . "`month`='" . $_GET['month'] . "',"
                . "`days`='" . $_GET['days'] . "',"
                . "`quests_id`='" . $_GET['quests_id'] . "',"
                . "`povtor`='" . $_GET['povtor'] . "' WHERE `id`='" . $_GET['id'] . "'");
        ?><script>/*nextshowcontemt*/showContent("/admin/holidays/index.php?msg=" + encodeURIComponent("saved"));</script><?php
        exit(0);
    }
    if (isset($_GET['create']) && $_GET['create'] == "del") {
        $mc->query("DELETE FROM `holidays` WHERE `id` = '" . $_GET['id'] . "'");
        ?><script>/*nextshowcontemt*/showContent("/admin/holidays/index.php?msg=" + encodeURIComponent("deleted"));</script><?php
        exit(0);
    }



    $allloc = $mc->query("SELECT * FROM `location`")->fetch_all(MYSQLI_ASSOC);
    $allquest = $mc->query("SELECT * FROM `quests`")->fetch_all(MYSQLI_ASSOC);
    $arrqueestsonlock = [];
    for ($i = 0; $i < count($allquest); $i++) {
        $arrqueestsonlock['loc' . $allquest[$i]['locId']][] = $allquest[$i];
    }
    $arrqueestsonlock = indexFirstIndexArr($arrqueestsonlock);
    ?>

    <div class="holidays-card">
        <form id="tabl99999999999999">
            <table class="holidays-table">
                <tr>
                    <th style="width:7%;">Ч Мес</th>
                    <th style="width:7%;">Ч Дн</th>
                    <th style="width:36%;">КВ</th>
                    <th style="width:30%;">Повт Дн</th>
                    <th style="width:20%;"></th>
                </tr>
                <tr>
                    <td><input type="number" name='month' value="0" min="0" max="12"></td>
                    <td><input type="number" name='days' value="0" min="0" max="31"></td>
                    <td>
                        <select name='quests_id'>
                            <option value='0'>квест не выбран</option>
                            <?php for ($i123 = 0; $i123 < count($arrqueestsonlock); $i123++) { ?>
                                <?php $loc_name = $mc->query("SELECT `Name` FROM `location` WHERE `id` = '" . $arrqueestsonlock[$i123][0]['locId'] . "'")->fetch_array(MYSQLI_ASSOC); ?>
                                <optgroup label="<?= htmlspecialchars(urldecode($loc_name['Name'])); ?>">
                                    <?php for ($i2 = 0; $i2 < count($arrqueestsonlock[$i123]); $i2++) { ?>
                                        <?php
                                        $icon = "";
                                        if ($arrqueestsonlock[$i123][$i2]['rasa'] == 1) {
                                            $icon = "Н->";
                                        } elseif ($arrqueestsonlock[$i123][$i2]['rasa'] == 2) {
                                            $icon = "Ш->";
                                        }
                                        ?>
                                        <option value='<?= $arrqueestsonlock[$i123][$i2]['id']; ?>'>
                                            <?= $icon . htmlspecialchars(urldecode($arrqueestsonlock[$i123][$i2]['name'])); ?>
                                            <?= urldecode($arrqueestsonlock[$i123][$i2]['comment']) != '' ? "//" . urldecode($arrqueestsonlock[$i123][$i2]['comment']) : ""; ?>
                                        </option>
                                    <?php } ?>
                                </optgroup>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select name='povtor'>
                            <option value=''>не выбрано</option>
                            <option value='Mon'>Понедельник</option>
                            <option value='Tue'>Вторник</option>
                            <option value='Wed'>Среда</option>
                            <option value='Thu'>Четверг</option>
                            <option value='Fri'>Пятница</option>
                            <option value='Sat'>Суббота</option>
                            <option value='Sun'>Воскресенье</option>
                        </select>
                    </td>
                    <td><button type="button" class="button butt999999999999999999">Добавить</button></td>
                </tr>
            </table>
        </form>
    </div>

    <script>
        $(".butt999999999999999999").click(function () {
            showContent("/admin/holidays/index.php?create=addbd" + "&" + $("#tabl99999999999999").serialize());
        });
    </script>

    <?php
    //`id`, `month`, `days`, `how_days`, `quests_id`, `povtor`
    $allHoliRes = $mc->query("SELECT * FROM `holidays` ORDER BY `month`,`days` ASC");
    if ($allHoliRes->num_rows > 0) {
        $allHoli = $allHoliRes->fetch_all(MYSQLI_ASSOC);
        foreach ($allHoli as $holiday) {
            ?>
            <div class="holidays-card">
                <form id="tabl<?= $holiday['id']; ?>">
                    <table class="holidays-table">
                        <tr>
                            <input type="hidden" name='id' value="<?= $holiday['id']; ?>">
                            <td style="width:7%;"><input type="number" name='month' value="<?= $holiday['month']; ?>" min="0" max="12"></td>
                            <td style="width:7%;"><input type="number" name='days' value="<?= $holiday['days']; ?>" min="0" max="31"></td>
                            <td style="width:36%;">
                                <select name='quests_id'>
                                    <option value='0' <?= $holiday['quests_id'] == 0 ? 'selected' : ''; ?>>квест не выбран</option>
                                    <?php for ($i123 = 0; $i123 < count($arrqueestsonlock); $i123++) { ?>
                                        <?php $loc_name = $mc->query("SELECT `Name` FROM `location` WHERE `id` = '" . $arrqueestsonlock[$i123][0]['locId'] . "'")->fetch_array(MYSQLI_ASSOC); ?>
                                        <optgroup label="<?= htmlspecialchars(urldecode($loc_name['Name'])); ?>">
                                            <?php for ($i2 = 0; $i2 < count($arrqueestsonlock[$i123]); $i2++) { ?>
                                                <?php
                                                $icon = "";
                                                if ($arrqueestsonlock[$i123][$i2]['rasa'] == 1) {
                                                    $icon = "Н->";
                                                } elseif ($arrqueestsonlock[$i123][$i2]['rasa'] == 2) {
                                                    $icon = "Ш->";
                                                }
                                                ?>
                                                <option value='<?= $arrqueestsonlock[$i123][$i2]['id']; ?>' <?= $holiday['quests_id'] == $arrqueestsonlock[$i123][$i2]['id'] ? 'selected' : ''; ?>>
                                                    <?= $icon . htmlspecialchars(urldecode($arrqueestsonlock[$i123][$i2]['name'])); ?>
                                                    <?= urldecode($arrqueestsonlock[$i123][$i2]['comment']) != '' ? "//" . urldecode($arrqueestsonlock[$i123][$i2]['comment']) : ""; ?>
                                                </option>
                                            <?php } ?>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="width:30%;">
                                <select name='povtor'>
                                    <option value='' <?= $holiday['povtor'] == '' ? 'selected' : ''; ?>>не выбрано</option>
                                    <option value='Mon' <?= $holiday['povtor'] == 'Mon' ? 'selected' : ''; ?>>Понедельник</option>
                                    <option value='Tue' <?= $holiday['povtor'] == 'Tue' ? 'selected' : ''; ?>>Вторник</option>
                                    <option value='Wed' <?= $holiday['povtor'] == 'Wed' ? 'selected' : ''; ?>>Среда</option>
                                    <option value='Thu' <?= $holiday['povtor'] == 'Thu' ? 'selected' : ''; ?>>Четверг</option>
                                    <option value='Fri' <?= $holiday['povtor'] == 'Fri' ? 'selected' : ''; ?>>Пятница</option>
                                    <option value='Sat' <?= $holiday['povtor'] == 'Sat' ? 'selected' : ''; ?>>Суббота</option>
                                    <option value='Sun' <?= $holiday['povtor'] == 'Sun' ? 'selected' : ''; ?>>Воскресенье</option>
                                </select>
                            </td>
                            <td style="width:20%;">
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="button delete butt2<?= $holiday['id']; ?>">Удалить</button>
                                    <button type="button" class="button save butt1<?= $holiday['id']; ?>">Сохранить</button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <script>
                $(".butt1<?= $holiday['id']; ?>").click(function () {
                    showContent("/admin/holidays/index.php?create=save" + "&" + $("#tabl<?= $holiday['id']; ?>").serialize());
                });
                $(".butt2<?= $holiday['id']; ?>").click(function () {
                    showContent("/admin/holidays/index.php?create=del" + "&" + $("#tabl<?= $holiday['id']; ?>").serialize());
                });
            </script>
            <?php
        }
    }
    ?>

    <div class="notes">
        <strong>Важные примечания:</strong><br>
        • Квест будет записываться во взятые. Первая часть его не важна, в какой он находится локации.<br>
        • За автоматический показ отвечает флаг автозапуска первой части кв.<br>
        • <strong>Ч Мес</strong> - число месяц при котором произойдет запуск квеста.<br>
        • <strong>Ч Дн</strong> - число день при котором произойдет запуск квеста.<br>
        • <strong>Повт Дн</strong> - повтор квеста по дням недели. Только если Ч Мес = 0 и Ч Дн = 0. Повторяется в любом случае.<br>
        • Квест также доступен согласно условиям самого квеста.
    </div>
</div>

<script>
    function getsetstyle() {
        console.log(1);
        for (var i = 0; i < $("option").length; i++) {
            try {
                if ($("option:eq(" + i + ")").attr("style")) {
                } else {
                    $("option:eq(" + i + ")").attr("style", /style=\"(.+?)\"/.exec($("option:eq(" + i + ")").text())[1]);
                }
            } catch (e) {
                $("option:eq(" + i + ")").attr("style", "color: #fff;font-size: auto;");
            }
            $("option:eq(" + i + ")").html($("option:eq(" + i + ")").text());
        }
        recolorselect();
        for (var i = 0; i < $("optgroup").length; i++) {
            try {
                if ($(".optgroup:eq(" + i + ")").attr("style")) {
                } else {
                    $("optgroup:eq(" + i + ")").attr("style", /style=\"(.+?)\"/.exec($("optgroup:eq(" + i + ")").attr("label"))[1]);
                }
            } catch (e) {
            }
            $("optgroup:eq(" + i + ")").attr("label", $("optgroup:eq(" + i + ")").attr("label").replace(/<[^>]+>/g, ''));
        }
    }
    function recolorselect() {
        for (var i = 0; i < $("select").length; i++) {
            try {
                var color = $('option:selected', $('select:eq(' + i + ')')).css('color');
                $('select:eq(' + i + ')').css({'color': color});
            } catch (e) {
                $('select:eq(' + i + ')').css({'color': 'white'});
            }
        }
    }
    MyLib.setTimeid[100] = setTimeout(function () {
        getsetstyle();
    }, 200);
</script>

<?php
function indexFirstIndexArr($arr) {
    $arr2 = [];
    foreach ($arr as $key => $value) {
        $arr2[] = $value;
    }
    return $arr2;
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json, true);
}

$footval = 'adminlocindex';
include '../../system/foot/foot.php';
?>