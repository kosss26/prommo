<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>/*nextshowcontemt*/showContent("/");</script><?php
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
    
    .auk-panel {
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
    
    h3, h4 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 20px;
    }
    
    .section {
        margin-bottom: 25px;
    }
    
    .section-title {
        color: var(--accent);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--glass-border);
        text-align: left;
    }
    
    .button_alt_01 {
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
    }
    
    .button_alt_01:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 15px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    td {
        padding: 10px;
        color: var(--text);
        background-color: transparent;
        border: none;
    }
    
    input, select, textarea {
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
    
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    hr {
        border: none;
        height: 1px;
        background-color: var(--glass-border);
        margin: 20px 0;
    }
    
    .msg {
        z-index: 9999;
        background-color: rgba(0,0,0,0.7);
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        display: none;
    }
    
    .text_msg {
        color: #111;
        font-weight: 500;
    }
    
    .msg-content {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 20px;
        width: 90%;
        max-width: 400px;
        color: var(--text);
    }
    
    .msg-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .shop2icobg {
        display: inline-block;
        vertical-align: middle;
        width: 40px;
        height: 40px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    
    .element-controls {
        margin-right: 10px;
    }
    
    .element-controls button {
        display: block;
        width: 30px;
        height: 30px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        margin-bottom: 2px;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .element-controls button:hover {
        background: var(--item-hover);
    }
    
    .search-block {
        margin-top: 20px;
    }
    
    .search-input {
        width: 100%;
        margin-bottom: 10px;
    }
    
    @media (max-width: 768px) {
        .button_alt_01 {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<?php
if (isset($_GET['add_new']) && isset($_GET['auk_lvl_min']) && isset($_GET['auk_lvl_max'])) {
    $mc->query("INSERT INTO `auk_list` (`id`, `lvl_min`, `lvl_max`, `date`) VALUES (NULL, '" . $_GET['auk_lvl_min'] . "', '" . $_GET['auk_lvl_max'] . "', CURRENT_TIMESTAMP)");
    message("Добавлено");
}
if (isset($_GET['id_auk']) && $_GET['id_auk'] > 0) {
    //список аукционов
    $auk_list_Res = $mc->query("SELECT * FROM `auk_list` WHERE `id`='" . $_GET['id_auk'] . "'");
    if ($auk_list_Res->num_rows > 0) {
        $auk_list = $auk_list_Res->fetch_array(MYSQLI_ASSOC);
        ?>
        <div class="auk-panel">
            <h2>Редактор аукциона</h2>
            
            <div class="card">
                <div class="card-body">
                    <div class="section-title">Аукцион <?= $auk_list['lvl_min']; ?> - <?= $auk_list['lvl_max']; ?></div>
                    <div style="text-align: center; margin-bottom: 15px;">
                        <div class="button_alt_01" onclick="showContent('/admin/auk/index.php');">← Назад к списку аукционов</div>
                    </div>
                </div>
            </div>

            <form id="lots" class="section">
                <div class="card">
                    <div class="card-body">
                        <div class="section-title">Список лотов</div>
                        <?php
                        //список лотов
                        $auk_lots_Res = $mc->query("SELECT * FROM `auk_lots` WHERE `id_auk_list` = '" . $_GET['id_auk'] . "' ORDER BY `count` ASC");
                        if ($auk_lots_Res->num_rows > 0) {
                            $auk_lots = $auk_lots_Res->fetch_all(MYSQLI_ASSOC);
                            foreach ($auk_lots as $value) {
                                $shopThing = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $value['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
                                ?>
                                <div class="card element">
                                    <div class="card-body">
                                        <table style="width: 100%; margin-bottom: 10px;">
                                            <tr>
                                                <td style="width: 40px; vertical-align: top;">
                                                    <div class="element-controls">
                                                        <button type="button" onclick="element_up($(this).closest('.element'))">▲</button>
                                                        <button type="button" onclick="element_down($(this).closest('.element'))">▼</button>
                                                    </div>
                                                </td>
                                                <td style="width: 50px;">
                                                    <div class="shop2icobg shop2ico<?= $shopThing['id_image']; ?>"></div>
                                                </td>
                                                <td>
                                                    <div style="font-weight: bold; margin-bottom: 5px;">
                                                        <?= $shopThing['name']; ?> [<?= $shopThing['level']; ?>]
                                                    </div>
                                                </td>
                                                <td style="width: 100px; text-align: right;">
                                                    <div class="button_alt_01" style="width: 100px;" onclick="dellLots($(this).closest('.element'));">Убрать</div>
                                                </td>
                                            </tr>
                                        </table>
                                        <input name="elements[][id_shop]" type="number" value="<?= $value['id_shop']; ?>" hidden>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="width: 50%; text-align: center;">
                                                    Открытые дни
                                                </td>
                                                <td style="width: 50%; text-align: center;">
                                                    <input name="elements[][open_day]" type="number" value="<?= $value['open_day']; ?>" min="1" max="99999999999">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%; text-align: center;">
                                                    Закрытые дни
                                                </td>
                                                <td style="width: 50%; text-align: center;">
                                                    <input name="elements[][close_day]" type="number" value="<?= $value['close_day']; ?>" min="1" max="99999999999">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%; text-align: center;">
                                                    Мин. ставка <img src="/images/icons/plata.png" width="16px">
                                                </td>
                                                <td style="width: 50%; text-align: center;">
                                                    <input name="elements[][min_platina]" type="number" value="<?= $value['min_platina']; ?>" min="0" max="99999999999">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%; text-align: center;">
                                                    Стоп ставка <img src="/images/icons/plata.png" width="16px">
                                                </td>
                                                <td style="width: 50%; text-align: center;">
                                                    <input name="elements[][stop_platina]" type="number" value="<?= $value['stop_platina']; ?>" min="0" max="99999999999">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </form>
            
            <div class="section" style="text-align: center;">
                <div class="button_alt_01" onclick="auk_save_lots_msg();">Сохранить</div>
            </div>

            <div class="msg">
                <table style="margin: auto;width: 100%;height: 100%">
                    <tr>
                        <td style="vertical-align: middle;text-align: center;background-color: transparent;">
                            <div class="msg-content">
                                <div class="text_msg">
                                    <b>Внимание!</b><br>
                                    Данный аукцион будет прекращен и сброшен вместе со всем списком лотов, а участникам возвращены ставки.
                                </div>
                                <br>
                                <div class="button_alt_01" onclick="close_auk_lots_msg_save(1);" style="margin: auto;margin-bottom: 5px;" >Подтверждаю</div>
                                <div class="button_alt_01" onclick="close_auk_lots_msg_save(0);" style="margin: auto;margin-bottom: 5px;">Отмена</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="templateLots" style="display: none;">
                <div class="card element">
                    <div class="card-body">
                        <table style="width: 100%; margin-bottom: 10px;">
                            <tr>
                                <td style="width: 40px; vertical-align: top;">
                                    <div class="element-controls">
                                        <button type="button" onclick="element_up($(this).closest('.element'))">▲</button>
                                        <button type="button" onclick="element_down($(this).closest('.element'))">▼</button>
                                    </div>
                                </td>
                                <td style="width: 50px;">
                                    <div id="new_image_class_tmp" class="shop2icobg"></div>
                                </td>
                                <td>
                                    <div id="name_lvl_tmp_lots" style="font-weight: bold; margin-bottom: 5px;"></div>
                                </td>
                                <td style="width: 100px; text-align: right;">
                                    <div class="button_alt_01" style="width: 100px;" onclick="dellLots($(this).closest('.element'));">Убрать</div>
                                </td>
                            </tr>
                        </table>
                        <input name="elements[][id_shop]" id="id_shop_tmp_lots" type="number" value="" hidden>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%; text-align: center;">
                                    Открытые дни
                                </td>
                                <td style="width: 50%; text-align: center;">
                                    <input name="elements[][open_day]" type="number" value="1" min="1" max="99999999999">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; text-align: center;">
                                    Закрытые дни
                                </td>
                                <td style="width: 50%; text-align: center;">
                                    <input name="elements[][close_day]" type="number" value="1" min="1" max="99999999999">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; text-align: center;">
                                    Мин. ставка <img src="/images/icons/plata.png" width="16px">
                                </td>
                                <td style="width: 50%; text-align: center;">
                                    <input name="elements[][min_platina]" type="number" value="0" min="0" max="99999999999">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; text-align: center;">
                                    Стоп ставка <img src="/images/icons/plata.png" width="16px">
                                </td>
                                <td style="width: 50%; text-align: center;">
                                    <input name="elements[][stop_platina]" type="number" value="1000000" min="0" max="99999999999">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="card">
                    <div class="card-body">
                        <div class="section-title">Добавить вещи</div>
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: center;">
                                    <input onkeyup="search(this.value)" type="text" placeholder="Поиск по названию" style="width: 100%;">
                                    <span id="searchLoading" style="display: none; margin-left: 10px;">
                                        <img src="/img/loading.gif" width="20px" height="20px" alt="Загрузка...">
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <div class="search" style="margin-top: 10px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            MyLib.auk_lots_msg_save = $('.msg');
            MyLib.auk_id =<?= $_GET['id_auk']; ?>;
            if (typeof dellLots !== 'function') {
                function add_lots(ico, id_shop, name, lvl) {
                    var tmp3533 = $(".templateLots").find(".element").clone();
                    $(tmp3533).find("#name_lvl_tmp_lots").html(name + "[" + lvl + "]");
                    $(tmp3533).find("#id_shop_tmp_lots").val(id_shop);
                    $(tmp3533).find("#new_image_class_tmp").removeClass().addClass("shop2icobg shop2ico" + ico);
                    $("#lots").append(tmp3533);
                }
                function dellLots(e) {
                    e.closest('.element').remove();
                }
                function element_up(e) {
                    e.closest(".element").insertBefore(e.closest(".element").prev());
                }
                function element_down(e) {
                    e.closest(".element").insertAfter(e.closest(".element").next());
                }
                var auk_save_lots_msg = function () {
                    $.fn.serializeObject = FormSerializer.serializeObject;
                    MyLib.auk_lots_array = $('#lots').serializeObject();
                    $('.msg').css({display: "block"});
                    $('.msg').animate({'opacity': '1'}, 300);
                };
                close_auk_lots_msg_save = function (e) {
                    $('.msg').animate({'opacity': '0'}, 300);
                    MyLib.setTimeid[250] = setTimeout(function () {
                        $('.msg').css({display: "none"});
                    }, 300);
                    if (e == 1) {
                        $.ajax({
                            url: "/admin/auk/save_auk_lots.php",
                            type: 'GET',
                            data: {
                                auk_lots_array: MyLib.auk_lots_array,
                                auk_id: MyLib.auk_id
                            },
                            success: function (data) {
                                msg(data);
                            },
                            error: function (e) {
                                msg('Произошла ошибка');
                            }
                        });
                    }
                };
                function search(etext) {
                    var arr;
                    $('#searchLoading').show();
                    $.ajax({
                        type: "POST",
                        url: "/admin/auk/search.php?etext=" + etext,
                        dataType: "text",
                        success: function (data) {
                            $(".search").html("");
                            if (data != "") {
                                arr = JSON.parse(data);
                                for (var i = 0; i < arr.length; i++) {
                                    addShopSearched(arr[i].id_image, arr[i].id, arr[i].name, arr[i].level);
                                }
                            }
                            $('#searchLoading').hide();
                        },
                        error: function () {
                            $(".search").html("error");
                            $('#searchLoading').hide();
                        }
                    });
                }
                function addShopSearched(ico, id_shop, name, lvl) {
                    $(".search").append(
                        '<div class="card">' +
                        '<div class="card-body">' +
                        '<table style="width: 100%;">' +
                        '<tr>' +
                        '<td style="width: 50px;">' +
                        '<div class="shop2icobg shop2ico' + ico + '"></div>' +
                        '</td>' +
                        '<td>' + 
                        '<div style="font-weight: bold;">' + name + ' [' + lvl + ']</div>' +
                        '<small style="color: var(--muted);">ID: ' + id_shop + '</small>' +
                        '</td>' +
                        '<td style="width: 120px; text-align: right;">' +
                        '<div class="button_alt_01" style="width: 120px; margin: 0;" onclick="add_lots(' + ico + ',' + id_shop + ',\'' + htmlspecialchars(name) + '\',' + lvl + ');">Добавить</div>' +
                        '</td>' +
                        '</tr>' +
                        '</table>' +
                        '</div>' +
                        '</div>'
                    );
                }
                function htmlspecialchars(str) {
                    if (typeof (str) == "string") {
                        str = str.replace(/&/g, "&amp;");
                        str = str.replace(/"/g, "&quot;");
                        str = str.replace(/'/g, "&#039;");
                        str = str.replace(/</g, "&lt;");
                        str = str.replace(/>/g, "&gt;");
                    }
                    return str;
                }
                function msg(e) {
                    $('.text_msg').html(e);
                    $('.msg').css({display: 'block'});
                    $('.msg').animate({'opacity': '1'}, 300);
                    MyLib.setTimeid[251] = setTimeout(function () {
                        $('.msg').animate({'opacity': '0'}, 300);
                        MyLib.setTimeid[252] = setTimeout(function () {
                            $('.msg').css({display: 'none'});
                        }, 300);
                    }, 3000);
                }
            }
        </script>
        <?php
    }
} else {
    ?>
    <script>/*nextshowcontemt*/showContent("/main?msg=error_50432220");</script>
    <?php
    exit(0);
}

$footval = 'auk_edit';
require_once '../../system/foot/foot.php';
