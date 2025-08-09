<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
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
    
    .location-panel {
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
    
    .section {
        margin-bottom: 25px;
    }
    
    .section-title {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px;
        border-radius: var(--radius);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .button_alt_01 {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px 20px;
        border: none;
        border-radius: var(--radius);
        text-align: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 10px auto;
        display: block;
        width: 200px;
        box-shadow: var(--panel-shadow);
    }
    
    .button_alt_01:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    .clanturblock {
        background: var(--card-bg);
        border-radius: var(--radius);
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 6px;
    }
    
    .clanturblock:hover {
        transform: translateY(-2px);
        background: var(--item-hover);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    td {
        padding: 12px;
        color: var(--text);
        background-color: transparent;
        border: none;
    }
    
    input, select, textarea {
        width: 98%;
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    textarea {
        min-height: 60px;
    }
    
    hr {
        border: none;
        height: 1px;
        background-color: var(--glass-border);
        margin: 15px 0;
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
        background: var(--primary-gradient);
        border-radius: var(--radius);
        padding: 15px;
        box-shadow: var(--panel-shadow);
    }
    
    @media (max-width: 600px) {
        input[name^='dhdClan'], input[name^='dhdUser'] {
            width: 25%;
        }
    }
</style>

<div class="location-panel">
    <h2>Редактор локации</h2>

<?php
$allloc = $mc->query("SELECT * FROM `location` ORDER BY `location`.`id` ASC")->fetch_all(MYSQLI_ASSOC);
///////////////////////////////Все локации
if (isset($_GET['func']) && $_GET['func'] == "allloc") {

    for ($i = 0; $i < count($allloc); $i++) {
        $icon = "";
        if ($allloc[$i]['access'] == 1) {
            $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
        } elseif ($allloc[$i]['access'] == 2) {
            $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
        }
        ?>
        <div class="clanturblock" onclick="showContent('/admin/location/edit.php?func=infloc&locid=<?= $allloc[$i]['id']; ?>')">
            <table style="width: 100%;margin: auto;">
                <tr>
                    <td style="width: 50px;text-align: center"><?= $allloc[$i]['id'] . " . "; ?></td>
                    <td style="max-width: 100%;text-align: left">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 40%;text-align: left;">
                                    <?= $icon . $allloc[$i]['Name'] . "[" . $allloc[$i]['accesslevel'] . "]"; ?>
                                </td>
                                <td style="width: 60%;text-align: center;">
                                    ДК [<img width="10px" src="/images/icons/zoloto.png"><?= money($allloc[$i]['dhdClan'], 'zoloto'); ?><img width="10px" src="/images/icons/serebro.png"><?= money($allloc[$i]['dhdClan'], 'serebro'); ?><img width="10px" src="/images/icons/med.png"><?= money($allloc[$i]['dhdClan'], 'med'); ?>]
                                    <br>
                                    ДЛ [<img width="10px" src="/images/icons/zoloto.png"><?= money($allloc[$i]['dhdUser'], 'zoloto'); ?><img width="10px" src="/images/icons/serebro.png"><?= money($allloc[$i]['dhdUser'], 'serebro'); ?><img width="10px" src="/images/icons/med.png"><?= money($allloc[$i]['dhdUser'], 'med'); ?>]
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div> 
        <?php
    }
} else {
    if (isset($_GET['func']) && $_GET['func'] == "add") {
        ?>
        <div class="section-title">Новая локация</div>
        <form id="form">
            <div class="section">
                <div class="section-title">Основные параметры</div>
                <table style="width: 100%;">
                    <tr>
                        <td style='width:50%;text-align:center'>
                            ID локации
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input id="id" name='locid' type='number' value=''>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Имя локации
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='locname' type='text' value='Новая'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Коэффициент покупки шмота
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='coef_buy' type='number' value='0'>
                        </td>    
                    </tr>  
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Коэффициент продажи шмота
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='coef_sell' type='number' value='0'>
                        </td>    
                    </tr>   
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Коэффициент ремонта шмота
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='coef_repair' type='number' value='0'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            ID фото
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input id="fotoid" name='locimgid' type='number' value='0'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Доступно с уровня
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='loclvl' type='number' value='0'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Раса Доступно
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='locaccess'>
                                <option value='3'>Всем</option>
                                <option value='2'>Только Шейванам</option>
                                <option value='1'>только Нормасцам</option> 
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Снег
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='snow'>
                                <option value='0'>Выключен</option>
                                <option value='1'>Включен</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            По Квесту
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='quests'>
                                <option value='0'>НЕТ</option>
                                <option value='1'>ДА</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Доступно по ID вещи
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='thingid' type='number' value='0'>
                        </td>    
                    </tr> 
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Доступно по ID локации у клана
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='id_loc_dostup_sk'>
                                <?php for ($i1 = 0; $i1 < count($allloc); $i1++) { ?>
                                    <option value='<?= $allloc[$i1]['id']; ?>'><?= htmlspecialchars(urldecode($allloc[$i1]['Name'])); ?></option>
                                <?php } ?>
                            </select>      
                        </td>    
                    </tr> 
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Фото
                        </td>
                        <td style='width:50%;text-align:center'>
                            <div>
                                <select id="ico">
                                </select>
                            </div>
                        </td>    
                    </tr>    
                </table>
            </div>

            <div class="section">
                <div class="section-title">Связанные локации</div>
                <table style="width: 100%;">
                    <?php for ($i = 1; $i < 11; $i++) { ?>
                        <tr>
                            <td style='width:30%;text-align:center'>
                                <?= $i; ?>
                            </td>
                            <td style='width:70%;text-align:center'>
                                <select name='IdLoc<?= $i; ?>'>
                                    <?php for ($i1 = 0; $i1 < count($allloc); $i1++) { ?>
                                        <option value='<?= $allloc[$i1]['id']; ?>'><?= htmlspecialchars(urldecode($allloc[$i1]['Name'])); ?></option>
                                    <?php } ?>
                                </select>
                            </td>    
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Экономика локации</div>
                <table style="width: 100%;">
                    <tr>
                        <td style='width:30%;text-align:center'>
                            Уровень
                        </td>
                        <td style='width:70%;text-align:center'>
                            <input name='dhdLevel' type='number' value='1'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:30%;text-align:center'>
                            Доход казны:
                        </td>
                        <td style='width:70%;text-align:center'>
                            <img width="10px" src="/images/icons/zoloto.png">
                            <input name='dhdClanzolo' type='number' style='width:20%' value='0'>
                            <img width="10px" src="/images/icons/serebro.png">
                            <input name='dhdClanserebro' type='number' style='width:20%' value='0'>
                            <img width="10px" src="/images/icons/med.png">
                            <input name='dhdClanmed' type='number' style='width:20%' value='0'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:30%;text-align:center'>
                            Личный доход:
                        </td>
                        <td style='width:70%;text-align:center'>
                            <img width="10px" src="/images/icons/zoloto.png">
                            <input name='dhdUserzolo' type='number' style='width:20%' value='0'>
                            <img width="10px" src="/images/icons/serebro.png">
                            <input name='dhdUserserebro' type='number' style='width:20%' value='0'>
                            <img width="10px" src="/images/icons/med.png">
                            <input name='dhdUsermed' type='number' style='width:20%' value='0'>
                        </td>     
                    </tr>
                </table>
            </div>
        </form>
        <?php
    } else if (isset($_GET['func']) && $_GET['func'] == "infloc") {
        $infloc = $mc->query("SELECT * FROM `location` WHERE `id`='" . $_GET['locid'] . "'")->fetch_array(MYSQLI_ASSOC);
        ?>
        <div class="section-title"><?= $infloc['Name']; ?></div>
        <form id="form">
            <div class="section">
                <div class="section-title">Основные параметры</div>
                <table style="width: 100%;">
                    <tr>
                        <td style='width:50%;text-align:center'>
                            ID локации
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input id="id" name='locid' type='number' value='<?= $infloc['id']; ?>'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Имя локации
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='locname' type='text' value='<?= $infloc['Name']; ?>'>
                        </td>    
                    </tr> 
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Коэффициент покупки шмота
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='coef_buy' type='number' value='<?= $infloc['coef_buy']; ?>'>
                        </td>    
                    </tr>  
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Коэффициент продажи шмота
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='coef_sell' type='number' value='<?= $infloc['coef_sell']; ?>'>
                        </td>    
                    </tr>   
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Коэффициент ремонта шмота
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='coef_repair' type='number' value='<?= $infloc['coef_repair']; ?>'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            ID фото
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input id="fotoid" name='locimgid' type='number' value='<?= $infloc['IdImage']; ?>'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Доступно с уровня
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='loclvl' type='number' value='<?= $infloc['accesslevel']; ?>'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Раса Доступно
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='locaccess'>
                                <option <?= $infloc['access'] == 3 ? "selected" : ""; ?> value='3'>Всем</option>
                                <option <?= $infloc['access'] == 2 ? "selected" : ""; ?> value='2'>Только Шейванам</option>
                                <option <?= $infloc['access'] == 1 ? "selected" : ""; ?> value='1'>только Нормасцам</option> 
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Снег
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='snow'>
                                <option <?= $infloc['snow'] == 0 ? "selected" : ""; ?> value='0'>Выключен</option>
                                <option <?= $infloc['snow'] == 1 ? "selected" : ""; ?> value='1'>Включен</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            По Квесту
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='quests'>
                                <option <?= $infloc['quests'] == 0 ? "selected" : ""; ?> value='0'>НЕТ</option>
                                <option <?= $infloc['quests'] == 1 ? "selected" : ""; ?> value='1'>ДА</option>
                            </select>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Доступно по ID вещи
                        </td>
                        <td style='width:50%;text-align:center'>
                            <input name='thingid' type='number' value='<?= $infloc['thingid']; ?>'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Доступно по ID локации у клана
                        </td>
                        <td style='width:50%;text-align:center'>
                            <select name='id_loc_dostup_sk'>
                                <?php for ($i1 = 0; $i1 < count($allloc); $i1++) { ?>
                                    <option value='<?= $allloc[$i1]['id']; ?>' <?= $infloc['id_loc_dostup_sk'] == $allloc[$i1]['id'] ? 'selected' : ''; ?>><?= htmlspecialchars(urldecode($allloc[$i1]['Name'])); ?></option>
                                <?php } ?>
                            </select>      
                        </td>    
                    </tr> 
                    <tr>
                        <td style='width:50%;text-align:center'>
                            Фото
                        </td>
                        <td style='width:50%;text-align:center'>
                            <div>
                                <select id="ico">
                                </select>
                            </div>
                        </td>    
                    </tr>    
                </table>
            </div>

            <div class="section">
                <div class="section-title">Связанные локации</div>
                <table style="width: 100%;">
                    <?php for ($i = 1; $i < 11; $i++) { ?>
                        <tr>
                            <td style='width:30%;text-align:center'>
                                <?= $i; ?>
                            </td>
                            <td style='width:70%;text-align:center'>
                                <select name='IdLoc<?= $i; ?>'>
                                    <?php for ($i1 = 0; $i1 < count($allloc); $i1++) { ?>
                                        <option value='<?= $allloc[$i1]['id']; ?>' <?= $infloc["IdLoc" . $i] == $allloc[$i1]['id'] ? 'selected' : ''; ?>><?= htmlspecialchars(urldecode($allloc[$i1]['Name'])); ?></option>
                                    <?php } ?>
                                </select>
                            </td>    
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Экономика локации</div>
                <table style="width: 100%;">
                    <tr>
                        <td style='width:30%;text-align:center'>
                            Уровень
                        </td>
                        <td style='width:70%;text-align:center'>
                            <input name='dhdLevel' type='number' value='1'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:30%;text-align:center'>
                            Доход казны:
                        </td>
                        <td style='width:70%;text-align:center'>
                            <img width="10px" src="/images/icons/zoloto.png">
                            <input name='dhdClanzolo' type='number' style='width:20%' value='<?= money($infloc['dhdClan'], 'zoloto'); ?>'>
                            <img width="10px" src="/images/icons/serebro.png">
                            <input name='dhdClanserebro' type='number' style='width:20%' value='<?= money($infloc['dhdClan'], 'serebro'); ?>'>
                            <img width="10px" src="/images/icons/med.png">
                            <input name='dhdClanmed' type='number' style='width:20%' value='<?= money($infloc['dhdClan'], 'med'); ?>'>
                        </td>    
                    </tr>
                    <tr>
                        <td style='width:30%;text-align:center'>
                            Личный доход:
                        </td>
                        <td style='width:70%;text-align:center'>
                            <img width="10px" src="/images/icons/zoloto.png">
                            <input name='dhdUserzolo' type='number' style='width:20%' value='<?= money($infloc['dhdUser'], 'zoloto'); ?>'>
                            <img width="10px" src="/images/icons/serebro.png">
                            <input name='dhdUserserebro' type='number' style='width:20%' value='<?= money($infloc['dhdUser'], 'serebro'); ?>'>
                            <img width="10px" src="/images/icons/med.png">
                            <input name='dhdUsermed' type='number' style='width:20%' value='<?= money($infloc['dhdUser'], 'med'); ?>'>
                        </td>     
                    </tr>
                </table>
            </div>
        </form>
    <?php } ?>

    <div class="section">
        <div class="button_alt_01" onclick="savePredmet();">Сохранить!</div>
        <div class="button_alt_01" onclick="$('#id').val('');savePredmet();">Копировать!</div>
    </div>

    <div class="msg">
        <table style="margin: auto;width: 240px;height: 100%">
            <tr>
                <td style="vertical-align: middle;text-align: center;background-color: rgba(0,0,0,0);">
                    <div class="msg-content">
                        <div class="text_msg">Сообщение</div>
                        <br>
                        <div class="button_alt_01" style="margin: auto;" onclick="$('.msg').css({display: 'none'})">Ок</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <script>
        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");
        canvas.width = 100;
        canvas.height = 75;
        var img = [];
        $.ajax({
            url: "./json/battle/battle1.json?136.12311",
            dataType: "json",
            success: function (data) {
                for (var i = 0; i < data.img.length; i++) {
                    img[i] = new Image();
                    img[i].src = data.img[i];
                }
                MyLib.setTimeid[100] = setTimeout(function () {
                    for (var i1 = 1; i1 < data.img.length; i1++) {
                        ctx.drawImage(img[i1],
                                0,
                                0,
                                img[i1].width,
                                img[i1].height,
                                0, 0, canvas.width, canvas.height
                                );
                        $("#ico").append($("<option></option>")
                                .attr("title", canvas.toDataURL())
                                .attr("value", i1)
                                );
                    }
                    $("#ico").msDropDown();
                    $('#ico').msDropDown().data("dd").set("selectedIndex", <?= isset($infloc['IdImage']) ? $infloc['IdImage'] : 0; ?> - 1);
                }, 2000);
            }});
        $("#ico").change(function () {
            $("#fotoid").val($('#ico option:selected').val());
        });
        function savePredmet() {
            $.fn.serializeObject = FormSerializer.serializeObject;
            var arr = $("#form").serializeObject();
            senddatas(encodeURIComponent(JSON.stringify(arr)));
        }
        function senddatas(e) {
            $("body").prepend("<img class='loading' src='" + imgLoading.src + "' alt='loading'>" +
                    "<div class='linefooter sizeFooterH'></div>");
            $.ajax({
                type: "POST",
                url: "/admin/location/location_l.php",
                data: {
                    strdata: e
                },
                dataType: "json",
                success: function (el) {
                    $(".loading").remove();
                    var data = el;
                    if (data.otvet == 1) {
                        $('#id').val(data.new_id);
                        msg("Локация создана");
                    } else if (data.otvet == 2) {
                        msg("Локация обновлена");
                    } else {
                        msg("Ошибка внесения изменений в БД");
                    }
                },
                error: function () {
                    $(".loading").remove();
                    msg("Ошибка соединения, данные не сохранены");
                }
            });
        }
        function msg(e) {
            $('.text_msg').html(e);
            $('.msg').css({display: 'block'});
        }
    </script>
<?php
}
?>
</div>

<?php
$footval = 'adminlocedit';
include '../../system/foot/foot.php';
?>