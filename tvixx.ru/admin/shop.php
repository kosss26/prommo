<?php
require_once '../system/header.php';
require_once '../system/func.php';
require_once '../system/dbc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo+.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$footval = 'adminshop';
require_once '../system/foot/foot.php';

if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
$allloc = $mc->query("SELECT * FROM `location`")->fetch_all(MYSQLI_ASSOC);
$allquest = $mc->query("SELECT * FROM `quests`")->fetch_all(MYSQLI_ASSOC);

$arrqueestsonlock = [];
for ($i = 0; $i < count($allquest); $i++) {
    $arrqueestsonlock['loc' . $allquest[$i]['locId']][] = $allquest[$i];
}
$arrqueestsonlock = indexFirstIndexArr($arrqueestsonlock);
$arrstat = [
    "лечение",
    "амок + ",
    "точность",
    "блок",
    "урон",
    "броня",
    "оглушение",
    "уворот",
    "амок - ",
    "отравление",
    "здоровье"
];
$arrPunct = [
    "Оружие", "Защита", "Шлем", "Перчатки", "Доспехи",
    "Обувь", "Амулет", "Кольца", "Зелья/Свитки", "Для заданий", "Бонусы", "Скрытые", "Разное"
];
?>
<style>
    td{
        background-color: #faffbd;
    }
    textarea{
        min-height: 60px;
    }
</style>
<div class="msg" style="z-index: 9999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;display: none">
    <table style="margin: auto;width: 240px;height: 100%">
        <tr>
            <td style="vertical-align: middle;text-align: center;background-color: rgba(0,0,0,0);">
                <div style="width: 90%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                    <br>
                    <div class="text_msg">sssssssssss</div>
                    <br>
                    <div class="button_alt_01" style="margin: auto;" onclick="$('.msg').css({display: 'none'})">Ок</div>
                    <br>
                </div>
            </td>
        </tr>
    </table>
</div>
<div id="hiddenEffect" hidden>
    <hr style="background-color: red;">
    <div style='text-align: center;font-size: 20px;font-weight: bold;' class="counts">-- образец --</div>
    <div style = 'width: 100%;text-align: center'>
        <font style="font-size: 30px" onclick="up1($(this));">▲&nbsp;&nbsp;&nbsp;&nbsp;</font>
        <font style="font-size: 30px" onclick="down1($(this));">&nbsp;&nbsp;&nbsp;&nbsp;▼</font>
    </div>
    <table style="width: 100%;margin: auto;text-align: center">
        <tr>
            <td style='width: 90%;'>
                название:
            </td>
        </tr> 
    </table>
    <table style="width: 100%;margin: auto;text-align: center">
        <tr>
            <td style='width: 90%;'>
                <textarea  style='width: 90%;' name='NameEffect[]'></textarea>
            </td>
        </tr> 
    </table>
    <table style="width: 100%;margin: auto;text-align: center">
        <tr>
            <td style='width:45%;'>
                Кому:
            </td>
            <td style='width:45%;'>
                <select style='width: 100%' name='Effect[0]'>
                    <option value='0'>Герою</option>
                    <option value='1'>Противнику</option>
                </select>
            </td>
        </tr>  
        <tr>
            <td>
                Колличество:
            </td>
            <td>
                <input type='number' style='width: 90%' name='Effect[1]' value='10'>
            </td>
        </tr>
        <tr>
            <td>
                Когда:
            </td>
            <td>
                <select style='width: 100%' name='Effect[2]'>
                    <option  value='0'>При попадании</option>
                    <option  value='1'>Всегда</option>
                    <option  value='2'>По завершении количества-при попадании</option>
                    <option  value='3'>По завершению колличества - всегда</option> 
                    <option  value='4'>каждый * раз - при попадании</option>
                    <option  value='5'>каждый * раз - всегда</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                каждый * раз, применится каждый * ход:
            </td>
            <td>
                <input type='number' style='width: 90%' name='Effect[6]' value='1'>
                <input type='number' style='width: 90%' name='Effect[7]' value='0' hidden>
            </td>
        </tr>
        <tr>
            <td>
                когда использовать ЭФФЕКТ
            </td>
            <td> 
                <select style='width: 100%' name='Effect[4]'>
                    <option value='0'>ПРИ ХОДЕ ОБОИХ</option>
                    <option value='1'>КОГДА ОН БЬЁТ</option>
                    <option value='2'>КОГДА ЕГО БЬЮТ</option>
                </select>
            </td>
        </tr> 
        <tr>
            <td>
                шарахнуть сразу или нееее ?
            </td>
            <td> 
                <select style='width: 100%' name='Effect[5]'>
                    <option value='0'>сразу</option>
                    <option value='1'>нееее</option>
                </select>
            </td>
        </tr>
    </table>

    Статs:
    <div class="allStats"></div>
    <br>
    <div style='width: 100%;text-align: center'>
        <input class='button_alt_01' style='width:90%' type='button' onclick="addStats($(this));" value='Добавить stats'>
    </div>
    <br>
    <div style='width: 100%;text-align: center'>
        <input class='button_alt_01' style='width:90%' type='button' onclick="dellEffects($(this));" value='Удалить этот эффект'>
    </div>
    <hr style="background-color: black;">
</div>
<div id="hiddenStats" hidden>
    <hr style="background-color: #7f00ff;">
    <div style = 'width: 100%;text-align: center'>
        <font style="font-size: 30px" onclick="up($(this));">▲&nbsp;&nbsp;&nbsp;&nbsp;</font>
        <font style="font-size: 30px" onclick="down($(this));">&nbsp;&nbsp;&nbsp;&nbsp;▼</font>
    </div>
    <table style="width: 100%;margin: auto;text-align: center">
        <tr>
            <td style='width: 25%;text-align: center'>название</td>
            <td style='width: 25%;text-align: center'>на ходов</td>
            <td style='width: 25%;text-align: center'>значение</td>
            <td style='width: 25%;text-align: center'>++разгон</td>
        </tr>
        <?php for ($i01 = 0; $i01 < count($arrstat); $i01++) { ?>
            <tr>
                <td ><?= $arrstat[$i01]; ?><input type="number" name="Effect[3][<?= $i01; ?>][][0]" value="<?= $i01; ?>" hidden></td>
                <td ><input type="number" style="width: 90%" name="Effect[3][<?= $i01; ?>][][1]" value="0"></td>
                <td ><input type="number" style="width: 90%" name="Effect[3][<?= $i01; ?>][][2]" value="0"></td>
                <td ><input type="number" style="width: 90%" name="Effect[3][<?= $i01; ?>][][3]" value="0"></td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <div style = 'width: 100%;text-align: center'>
        <input class = 'button_alt_01' style='width:90%' type='button' onclick="dellStats($(this));" value='Удалить Statas'>
    </div>
    <br>
</div>
<script>
    function up(e) {
        e.closest(".stats").insertBefore(e.closest(".stats").prev());
        renamecounts();
    }
    function down(e) {
        e.closest(".stats").insertAfter(e.closest(".stats").next());
        renamecounts();
    }
    function up1(e) {
        e.closest(".effects").insertBefore(e.closest(".effects").prev());
        renamecounts();
    }
    function down1(e) {
        e.closest(".effects").insertAfter(e.closest(".effects").next());
        renamecounts();
    }
    function addEffect() {
        $(".visualEffects").append("<div class='effects'>" + $("#hiddenEffect").html() + "</div>");
        renamecounts();
    }
    function addStats(e) {
        e.closest('.effects').find(".allStats").append("<div class='stats'>" + $("#hiddenStats").html() + "</div>");
        renamecounts();
    }
    function dellEffects(e) {
        e.closest('.effects').remove();
        renamecounts();
    }
    function delAllEffect() {
        $('.effects').remove();
    }
    function dellStats(e) {
        e.closest('.stats').remove();
        renamecounts();
    }
    function renamecounts() {
        for (var i = 0; i < $(".effects").length; i++) {
            $(".effects:eq(" + i + ")").find(".counts").text("-- Эффект " + (i + 1) + " --");
        }
    }
    function savePredmet() {
        $.fn.serializeObject = FormSerializer.serializeObject;
        var arr = $("#form").serializeObject();
        arr.Effect = [];
        arr.NameEffect = [];
        for (var i = 0; i < $(".effects").length; i++) {
            var temp = $(".effects:eq(" + i + ")").find('select, textarea, input').serializeObject();
            arr.Effect.push(temp.Effect);
            arr.NameEffect.push(temp.NameEffect[0]);
        }
        senddatas(encodeURIComponent(JSON.stringify(arr)));
    }
    function senddatas(e) {
        $("body").prepend("<img class='loading' src='" + imgLoading.src + "' alt='loading'>" +
                "<div class='linefooter sizeFooterH'></div>");
        $.ajax({
            type: "POST",
            url: "/admin/shop/shop_l.php",
            data: {
                strdata: e
            },
            dataType: "json",
            success: function (el) {
                $(".loading").remove();
                var data = el;
                if (data.otvet == 1) {
                    $('#id').val(data.new_id);
                    msg("предмет добавлен успешно");
                } else if (data.otvet == 2) {
                    msg("предмет успешно обновлен");
                } else {
                    msg("ошибка внесения изменений в бд");
                }
            },
            error: function () {
                $(".loading").remove();
                msg("ошибка соединения данные не сохранены");
            }
        });
    }
    function msg(e) {
        $('.text_msg').html(e);
        $('.msg').css({display: 'block'});
    }
    $(function () {
        //  changes mouse cursor when highlighting loawer right of box
        $(document).on('mousemove', 'textarea', function (e) {
            var a = $(this).offset().top + $(this).outerHeight() - 16, //	top border of bottom-right-corner-box area
                    b = $(this).offset().left + $(this).outerWidth() - 16;	//	left border of bottom-right-corner-box area
            $(this).css({
                cursor: e.pageY > a && e.pageX > b ? 'nw-resize' : ''
            });
        })
                //  the following simple make the textbox "Auto-Expand" as it is typed in
                .on('keyup', 'textarea', function (e) {
                    //  the following will help the text expand as typing takes place
                    while ($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
                        $(this).height($(this).height() + 1);
                    }
                    ;
                });
    });
    function getsetstyle() {
        console.log(1);
        for (var i = 0; i < $("option").length; i++) {
            try {
                if ($("option:eq(" + i + ")").attr("style")) {
                } else {
                    $("option:eq(" + i + ")").attr("style", /style=\"(.+?)\"/.exec($("option:eq(" + i + ")").text())[1]);
                }
            } catch (e) {
                $("option:eq(" + i + ")").attr("style", "color: #000000;font-size: auto;");
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
                $('select:eq(' + i + ')').css({'color': 'black'});
            }
        }
    }
    MyLib.setTimeid[100] = setTimeout(function () {
        getsetstyle();
    }, 200);
</script>
<?php
//создать
if (isset($_GET['shop']) && $_GET['shop'] == 'add') {
    ?>
    <form id="form">
        id :<br>
        <input id='id' name='id' class='text' type='text' value='' style='width: 90%'><br>
        <table style="width: 100%;margin: auto;text-align: center">

            <tr>
                <td>
                    Тип:
                </td>
                <td>
                    <select style='width: 90%' name='id_punct'>
                        <option selected value='1'>Оружие</option>
                        <option  value='2'>Защита</option>
                        <option value='3'>Шлем</option>
                        <option value='4'>Перчатки</option>
                        <option value='5'>Доспехи</option>
                        <option value='6'>Обувь</option>
                        <option value='7'>Амулет</option>
                        <option value='8'>Кольца</option>
                        <option value='9'>Зелье/свиток</option>
                        <option value='10'>Для заданий</option>
                        <option value='11'>Бонусы</option>
                        <option value='12'>Скрытые</option>
                        <option value='13'>Разное</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>
                    Стиль:
                </td>
                <td> 
                    <select style='width: 100%' name='stil'>
                        <option selected value='0'>Нет</option>
                        <option  value='1'>Урон</option>
                        <option value='2'>Уворот</option>
                        <option value='3'>Броня</option>
                        <option value='4'>Элита</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    статы и эффекты: 
                </td>
                <td>
                    <select style='width: 100%;' name='BattleFlag'>
                        <option value='1'>Реальные</option>
                        <option value='0'>Визуальные</option>
                    </select>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 100%;'>
                    Название:
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 90%;'>
                    <textarea  style='width: 90%;' name='name'></textarea>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 100%;'>
                    Описание:
                </td>
            </tr> 
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 90%;'>
                    <textarea  style='width: 90%' name='opisanie'></textarea>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 45%;'>
                    Уровень:
                </td>
                <td style='width: 45%;'>
                    <input name='lvl' class='text' type='number' value='0' style='width: 90%'>
                </td>
            </tr>
            <tr>
                <td>
                    id изображения:
                </td>
                <td>
                    <input name='img' class='text' type='number' style='width: 90%' value='272'>
                </td>
            </tr>
            <tr>
                <td>
                    Плата:
                </td>
                <td>
                    <input name='platinum' class='text' type='number' value='0' style='width: 90%'>
                </td>
            </tr>
            <tr>
                <td>
                    Золото:
                </td>
                <td>
                    <input name='zolo' class='text' type='number' value='0' style='width: 90%'>
                </td>
            </tr>
            <tr>
                <td>
                    Серебро:
                </td>
                <td>
                    <input name='serebro' class='text' type='number' value='0' style='width: 90%'>
                </td>
            </tr>
            <tr>
                <td >
                    Медь:
                </td>
                <td>
                    <input name='med' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
        </table>
        <div style='width: 100%;text-align: center'>-- 0 ЕСЛИ НЕ ИСПОЛЬЗУЕТСЯ --</div>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style="width:45%">
                    макс у героя шт:
                </td>
                <td style="width:45%">
                    <input name='max_hc' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Количество:
                </td>
                <td>
                    <input name='koll' class='text' type='number' value='-1' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Износ:
                </td>
                <td>
                    <input name='iznos' class='text' type='number' value='-1' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Годность время:
                </td>
                <td>
                    <input name='time_s' class='text' type='text' value='00:00:00:00' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Точность:
                </td>
                <td>
                    <input name='toch' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Урон:
                </td>
                <td>
                    <input name='strength' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Блок:
                </td>
                <td>
                    <input name='block' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Оглушение:
                </td>
                <td>
                    <input name='kd' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Уворот:
                </td>
                <td>
                    <input name='lov' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Броня:
                </td>
                <td>
                    <input name='bron' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Здоровье:
                </td>
                <td>
                    <input name='health' class='text' type='number' value='0' style='width: 90%' >
                </td>
            </tr>
            <tr>
                <td>
                    Писать в чате ?:
                </td>
                <td> 
                    <select style='width: 100%' name='chatSend'>
                        <option selected value='0'>Нет</option>
                        <option  value='1'>Да</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Зелье одноразовое ?:
                </td>
                <td> 
                    <select style='width: 100%' name='elexvar'>
                        <option selected value='0'>ДА</option>
                        <option  value='1'>НЕТ</option>
                    </select>
                </td>
            </tr> 
            <tr>
                <td>
                    запустить квест:
                </td>
                <td> 
                    <select name='id_quests' style='width: 100%'>
                        <option value='0'>квест не выбран</option>
                        <?php for ($i = 0; $i < count($arrqueestsonlock); $i++) { ?>
                            <?php $loc_name = $mc->query("SELECT `Name` FROM `location` WHERE `id` = '" . $arrqueestsonlock[$i][0]['locId'] . "'")->fetch_array(MYSQLI_ASSOC); ?>
                            <optgroup label="<?= htmlspecialchars(urldecode($loc_name['Name'])); ?>">
                                <?php for ($i2 = 0; $i2 < count($arrqueestsonlock[$i]); $i2++) { ?>
                                    <?php
                                    $icon = "";
                                    if ($arrqueestsonlock[$i][$i2]['rasa'] == 1) {
                                        $icon = "Н->";
                                    } elseif ($arrqueestsonlock[$i][$i2]['rasa'] == 2) {
                                        $icon = "Ш->";
                                    }
                                    ?>
                                    <option value='<?= $arrqueestsonlock[$i][$i2]['id']; ?>'>
                                        <?= $icon . htmlspecialchars(urldecode($arrqueestsonlock[$i][$i2]['name'])); ?>
                                        <?= urldecode($arrqueestsonlock[$i][$i2]['comment']) != '' ? "//" . urldecode($arrqueestsonlock[$i][$i2]['comment']) : ""; ?>
                                    </option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <div style='width: 100%;text-align: center'>-- диапазон дропа + магаз вывод --</div>
        <table style="width: 98%;margin: auto;text-align: center">
            <tr>
                <td style="width: 18%">
                    мин:
                </td>
                <td style="width: 30%"> 
                    <input name='drop_min_level' class='text' type='number' value='1' style='width: 90%;'>
                </td>
                <td style="width: 18%">
                    макс:
                </td>
                <td style="width: 30%"> 
                    <input name='drop_max_level' class='text' type='number' value='999' style='width: 90%;'>
                </td>
            </tr>
        </table>
        <br>
        <div class="visualEffects"></div>
    </form>
    <div style='width: 100%;text-align: center'>
        <input class='button_alt_01' style='width:90%' type="button" onclick="addEffect();" value="Добавить эффект">
    </div>
    <br>    
    <div style='width: 100%;text-align: center'>
        <input class='button_alt_01' style='width:90%' type='button' onclick="delAllEffect();" value='Удалить все Эффекты'>
    </div>
    <br>
    <div style='width: 100%;text-align: center'>
        <input class='button_alt_01' style='width:90%' type='button' onclick="savePredmet();" value='Сохранить предмет !'>
    </div>
    <br>
    <?php
}
//Редактировать
if (isset($_GET['shop']) && $_GET['shop'] == 'edit' && $_GET['id'] != '') {
    $infpredmet = $mc->query("SELECT * FROM `shop` WHERE `id` ='" . $_GET['id'] . "'")->fetch_array(MYSQLI_ASSOC);
    ?>
    <form id="form">
        id :<br>
        <input id='id' name='id' class='text' type='text' value='<?= $_GET['id']; ?>' style='width: 90%'><br>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td >
                    Тип:
                </td>
                <td>
                    <select style='width: 100%;' name='id_punct'>
                        <?php for ($i = 1; $i <= count($arrPunct); $i++) { ?>
                            <option value='<?= $i; ?>' <?= $infpredmet['id_punct'] == $i ? 'selected' : ''; ?>><?= $arrPunct[$i - 1]; ?></option>
                        <?php } ?>
                    </select>
                </td> 
            </tr>
            <tr>
                <td>
                    Стиль: 
                </td>
                <td>
                    <select style='width: 100%;' name='stil'>
                        <option value='0' <?= $infpredmet['stil'] == 0 ? 'selected' : ''; ?>>Нет</option>
                        <option value='1' <?= $infpredmet['stil'] == 1 ? 'selected' : ''; ?>>Урон</option>
                        <option value='2' <?= $infpredmet['stil'] == 2 ? 'selected' : ''; ?>>Уворот</option>
                        <option value='3' <?= $infpredmet['stil'] == 3 ? 'selected' : ''; ?>>Броня</option>
                        <option value='4' <?= $infpredmet['stil'] == 4 ? 'selected' : ''; ?>>Элита</option>
                    </select>
                </td>
            </tr> 
            <tr>
                <td>
                    статы и эффекты: 
                </td>
                <td>
                    <select style='width: 100%;' name='BattleFlag'>
                        <option value='1' <?= $infpredmet['BattleFlag'] == 1 ? 'selected' : ''; ?>>Реальные</option>
                        <option value='0' <?= $infpredmet['BattleFlag'] == 0 ? 'selected' : ''; ?>>Визуальные</option>
                    </select>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 100%;'>
                    Название:<br>
                </td>
            </tr> 
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 90%;'>
                    <textarea  style='width: 90%;' name='name'><?= $infpredmet['name']; ?></textarea>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr style='width: 100%;'>
                <td>
                    Описание:<br>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 90%;'>
                    <textarea  style='width: 90%;' name='opisanie'><?= $infpredmet['opisanie']; ?></textarea>
                </td>
            </tr>
        </table>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style='width: 45%;'>
                    Уровень:
                </td>
                <td style='width: 45%;'>
                    <input name='lvl' class='text' type='number' value='<?= $infpredmet['level']; ?>' style='width: 90%;'>
                </td>
            </tr>
            <tr>
                <td>
                    id изображения:
                </td>
                <td>
                    <input name='img' class='text' type='number' value='<?= $infpredmet['id_image']; ?>' style='width: 90%;'>
                </td>
            </tr>
            <?php
            $money = $infpredmet['money'];
            $med = $money % 100; ///медь
            $serebro = ($money - $med) / 100 % 100;
            $zoloto = floor(((($money - $med) / 100) - $serebro) / 100);
            ?>
            <tr>
                <td>
                    Плата:
                </td>
                <td>
                    <input name='platinum' class='text' type='number' value='<?= $infpredmet['platinum']; ?>' style='width: 90%;'>
                </td>
            </tr>
            <tr>
                <td>
                    Золото:
                </td>
                <td>
                    <input name='zolo' class='text' type='number' value='<?= $zoloto; ?>' style='width: 90%;'>
                </td>
            </tr>
            <tr>
                <td>
                    Серебро:
                </td>
                <td>
                    <input name='serebro' class='text' type='number' value='<?= $serebro; ?>' style='width: 90%;'>
                </td>
            </tr>
            <tr>
                <td>
                    Медь:
                </td>
                <td>
                    <input name='med' class='text' type='number' value='<?= $med; ?>' style='width: 90%;' >
                </td>
            </tr>
        </table>
        <div style='width: 100%;text-align: center'>-- 0 ЕСЛИ НЕ ИСПОЛЬЗУЕТСЯ --</div>
        <table style="width: 100%;margin: auto;text-align: center">
            <tr>
                <td style="width:45%">
                    макс у героя шт:
                </td>
                <td style="width:45%">
                    <input name='max_hc' class='text' type='number' value='<?= $infpredmet['max_hc']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td> 
                    Количество:
                </td>
                <td>
                    <input name='koll' class='text' type='number' value='<?= $infpredmet['koll']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Износ:
                </td>
                <td>
                    <input name='iznos' class='text' type='number' value='<?= $infpredmet['iznos']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Годность время:
                </td>
                <td>
                    <input name='time_s' class='text' type='text' value='<?= $time1 = sprintf("%02d:%02d:%02d:%02d", ($infpredmet['time_s'] / 3600) / 24, ($infpredmet['time_s'] / 3600) % 24, ($infpredmet['time_s'] % 3600) / 60, ($infpredmet['time_s'] % 3600) % 60); ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Точность:
                </td>
                <td>
                    <input name='toch' class='text' type='number' value='<?= $infpredmet['toch']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Урон:
                </td>
                <td>
                    <input name='strength' class='text' type='number' value='<?= $infpredmet['strength']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Блок:
                </td>
                <td>
                    <input name='block' class='text' type='number' value='<?= $infpredmet['block']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Оглушение:
                </td>
                <td>
                    <input name='kd' class='text' type='number' value='<?= $infpredmet['kd']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Уворот:
                </td>
                <td>
                    <input name='lov' class='text' type='number' value='<?= $infpredmet['lov']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Броня:
                </td>
                <td>
                    <input name='bron' class='text' type='number' value='<?= $infpredmet['bron']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Здоровье:
                </td>
                <td>
                    <input name='health' class='text' type='number' value='<?= $infpredmet['health']; ?>' style='width: 90%;' >
                </td>
            </tr>
            <tr>
                <td>
                    Писать в чате ?:
                </td>
                <td> 
                    <select style='width: 100%' name='chatSend'>
                        <option  value='0' <?= $infpredmet['chatSend'] == 0 ? 'selected' : ''; ?>>Нет</option>
                        <option  value='1' <?= $infpredmet['chatSend'] == 1 ? 'selected' : ''; ?>>Да</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Зелье одноразовое ?:
                </td>
                <td> 
                    <select style='width: 100%' name='elexvar'>
                        <option value='0' <?= $infpredmet['elexvar'] == 0 ? 'selected' : ''; ?>>ДА</option>
                        <option value='1' <?= $infpredmet['elexvar'] == 1 ? 'selected' : ''; ?>>НЕТ</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    запустить квест:
                </td>
                <td> 
                    <select name='id_quests' style='width: 100%'>
                        <option value='0' <?= $infpredmet['id_quests'] == 0 ? 'selected' : ''; ?>>квест не выбран</option>
                        <?php for ($i = 0; $i < count($arrqueestsonlock); $i++) { ?>
                            <?php $loc_name = $mc->query("SELECT `Name` FROM `location` WHERE `id` = '" . $arrqueestsonlock[$i][0]['locId'] . "'")->fetch_array(MYSQLI_ASSOC); ?>
                            <optgroup label="<?= htmlspecialchars(urldecode($loc_name['Name'])); ?>">
                                <?php for ($i2 = 0; $i2 < count($arrqueestsonlock[$i]); $i2++) { ?>
                                    <?php
                                    $icon = "";
                                    if ($arrqueestsonlock[$i][$i2]['rasa'] == 1) {
                                        $icon = "Н->";
                                    } elseif ($arrqueestsonlock[$i][$i2]['rasa'] == 2) {
                                        $icon = "Ш->";
                                    }
                                    ?>
                                    <option value='<?= $arrqueestsonlock[$i][$i2]['id']; ?>' <?= $infpredmet['id_quests'] == $arrqueestsonlock[$i][$i2]['id'] ? 'selected' : ''; ?>>
                                        <?= $icon . htmlspecialchars(urldecode($arrqueestsonlock[$i][$i2]['name'])); ?>
                                        <?= urldecode($arrqueestsonlock[$i][$i2]['comment']) != '' ? "//" . urldecode($arrqueestsonlock[$i][$i2]['comment']) : ""; ?>
                                    </option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <div style='width: 100%;text-align: center'>-- диапазон дропа + магаз вывод --</div>
        <table style="width: 98%;margin: auto;text-align: center">
            <tr>
                <td style="width: 18%">
                    мин:
                </td>
                <td style="width: 30%"> 
                    <input name='drop_min_level' class='text' type='number' value='<?= $infpredmet['drop_min_level'] ?>' style='width: 90%;'>
                </td>
                <td style="width: 18%">
                    макс:
                </td>
                <td style="width: 30%"> 
                    <input name='drop_max_level' class='text' type='number' value='<?= $infpredmet['drop_max_level'] ?>' style='width: 90%;'>
                </td>
            </tr>
        </table>
        <br>
        <div class="visualEffects">
            <?php
            $JsonEffects = isset($infpredmet['effects']) && $infpredmet['effects'] != "" ? json_decode($infpredmet['effects']) : [];
            $nameeffects = isset($infpredmet['nameeffects']) && $infpredmet['nameeffects'] != "" ? explode("|", $infpredmet['nameeffects']) : [];
            for ($i = 0; count($JsonEffects) > $i; $i++) {
                ?>
                <div class="effects">
                    <hr style="background-color: red;">
                    <div style='text-align: center;font-size: 20px;font-weight: bold;' class="counts">-- образец --</div>
                    <div style = 'width: 100%;text-align: center'>
                        <font style="font-size: 30px" onclick="up1($(this));">▲&nbsp;&nbsp;&nbsp;&nbsp;</font>
                        <font style="font-size: 30px" onclick="down1($(this));">&nbsp;&nbsp;&nbsp;&nbsp;▼</font>
                    </div>
                    <table style="width: 100%;margin: auto;text-align: center">
                        <tr>
                            <td style='width: 90%;'>
                                название:
                            </td>
                        </tr> 
                    </table>
                    <table style="width: 100%;margin: auto;text-align: center">
                        <tr>
                            <td style='width: 90%;'>
                                <textarea  style='width: 90%;' name='NameEffect[]'><?= isset($nameeffects[$i]) ? $nameeffects[$i] : ""; ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%;margin: auto;text-align: center">
                        <tr>
                            <td style='width:45%;'>
                                Кому: 
                            </td>
                            <td style='width:45%;'>
                                <select style='width: 100%' name='Effect[0]'>
                                    <option value='0' <?= $JsonEffects[$i][0] == 0 ? 'selected' : ''; ?>>Герою</option>
                                    <option value='1' <?= $JsonEffects[$i][0] == 1 ? 'selected' : ''; ?>>Противнику</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Колличество: 
                            </td>
                            <td>
                                <input type="number" style='width: 90%' name="Effect[1]" value="<?= $JsonEffects[$i][1]; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Когда: 
                            </td>
                            <td>
                                <select style='width: 100%' name='Effect[2]'>
                                    <option value='0' <?= $JsonEffects[$i][2] == 0 ? 'selected' : ''; ?>>При попадании</option>
                                    <option value='1' <?= $JsonEffects[$i][2] == 1 ? 'selected' : ''; ?>>Всегда</option>
                                    <option value='2' <?= $JsonEffects[$i][2] == 2 ? 'selected' : ''; ?>>По завершении количества-при попадании</option>
                                    <option value='3' <?= $JsonEffects[$i][2] == 3 ? 'selected' : ''; ?>>По завершению колличества - всегда</option>
                                    <option value='4' <?= $JsonEffects[$i][2] == 4 ? 'selected' : ''; ?>>каждый * раз - при попадании</option>
                                    <option value='5' <?= $JsonEffects[$i][2] == 5 ? 'selected' : ''; ?>>каждый * раз - всегда</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                каждый * раз, применится каждый * ход:
                            </td>
                            <td>
                                <input type='number' style='width: 90%' name='Effect[6]' value='<?= isset($JsonEffects[$i][6])?$JsonEffects[$i][6]:1; ?>'>
                                <input type='number' style='width: 90%' name='Effect[7]' value='<?= isset($JsonEffects[$i][7])?$JsonEffects[$i][7]:0; ?>' hidden>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                когда использовать ЭФФЕКТ
                            </td>
                            <td> 
                                <select style='width: 100%' name='Effect[4]'>
                                    <option value='0' <?= isset($JsonEffects[$i][4]) && $JsonEffects[$i][4] == 0 ? 'selected' : ''; ?>>ПРИ ХОДЕ ОБОИХ</option>
                                    <option value='1' <?= isset($JsonEffects[$i][4]) && $JsonEffects[$i][4] == 1 ? 'selected' : ''; ?>>КОГДА ОН БЬЁТ</option>
                                    <option value='2' <?= isset($JsonEffects[$i][4]) && $JsonEffects[$i][4] == 2 ? 'selected' : ''; ?>>КОГДА ЕГО БЬЮТ</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                шарахнуть сразу или нееее ?
                            </td>
                            <td> 
                                <select style='width: 100%' name='Effect[5]'>
                                    <option value='0' <?= isset($JsonEffects[$i][5]) && $JsonEffects[$i][5] == 0 ? 'selected' : ''; ?>>сразу</option>
                                    <option value='1' <?= isset($JsonEffects[$i][5]) && $JsonEffects[$i][5] == 1 ? 'selected' : ''; ?>>нееее</option>
                                </select>
                            </td>
                        </tr>
                    </table>


                    Статs:
                    <div class="allStats">
                        <?php for ($i03 = 0; isset($JsonEffects[$i][3][0]) && $i03 < count($JsonEffects[$i][3][0]); $i03++) { ?>
                            <div class='stats'>
                                <hr style="background-color: #7f00ff;">
                                <div style = 'width: 100%;text-align: center'>
                                    <font style="font-size: 30px" onclick="up($(this));">▲&nbsp;&nbsp;&nbsp;&nbsp;</font>
                                    <font style="font-size: 30px" onclick="down($(this));">&nbsp;&nbsp;&nbsp;&nbsp;▼</font>
                                </div>
                                <table  style="width: 100%;margin: auto;text-align: center">
                                    <tr>
                                        <td style="width: 25%;text-align: center">название</td>
                                        <td style="width: 25%;text-align: center">на ходов</td>
                                        <td style="width: 25%;text-align: center">значение</td>
                                        <td style="width: 25%;text-align: center">++разгон</td>
                                    </tr>
                                    <?php for ($i01 = 0; $i01 < count($arrstat); $i01++) { ?>
                                        <tr>
                                            <td ><?= $arrstat[$i01]; ?><input type="number" name="Effect[3][<?= $i01; ?>][][0]" value="<?= $i01; ?>" hidden></td>
                                            <td ><input type="number" style="width: 90%" name="Effect[3][<?= $i01; ?>][][1]" value="<?= isset($JsonEffects[$i][3][$i01][$i03][1])?$JsonEffects[$i][3][$i01][$i03][1]:0; ?>"></td>
                                            <td ><input type="number" style="width: 90%" name="Effect[3][<?= $i01; ?>][][2]" value="<?= isset($JsonEffects[$i][3][$i01][$i03][2])?$JsonEffects[$i][3][$i01][$i03][2]:0; ?>"></td>
                                            <td ><input type="number" style="width: 90%" name="Effect[3][<?= $i01; ?>][][3]" value="<?= isset($JsonEffects[$i][3][$i01][$i03][3])?$JsonEffects[$i][3][$i01][$i03][3]:0; ?>"></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                <br>
                                <div style = 'width: 100%;text-align: center'>
                                    <input class = 'button_alt_01' style='width:90%' type='button' onclick="dellStats($(this));" value='Удалить Statas'>
                                </div>
                                <br>
                            </div>
                        <?php } ?>
                    </div>
                    <br>
                    <div style='width: 100%;text-align: center'>
                        <input class='button_alt_01' style='width:90%' type='button' onclick="addStats($(this));" value='Добавить stats'>
                    </div>
                    <br>
                    <div style='width: 100%;text-align: center'>
                        <input class='button_alt_01' style='width:90%' type='button' onclick="dellEffects($(this));" value='Удалить этот эффект'>
                    </div>
                    <hr style="background-color: black;">
                </div>
            <?php } ?>
        </div>
        <div style='width: 100%;text-align: center'>
            <input class='button_alt_01' style='width:90%' type="button" onclick="addEffect();" value="Добавить эффект">
        </div>
        <br>    
        <div style='width: 100%;text-align: center'>
            <input class='button_alt_01' style='width:90%' type='button' onclick="delAllEffect();" value='Удалить все Эффекты'>
        </div>
        <br>
        <div style='width: 100%;text-align: center'>
            <input class='button_alt_01' style='width:90%' type='button' onclick="savePredmet();" value='Сохранить предмет !'>
        </div>
        <br>
        <div style='width: 100%;text-align: center'>
            <input class='button_alt_01' style='width:90%' type='button' onclick="$('#id').val('');savePredmet();" value='Копирнуть !'>
        </div>
        <br>
    </form>
    <script>renamecounts();</script>
    <?php
}
$i = 0;
?>
<br> 
Айди изображений:
<div>id icons:</div>
<?php
while ($i < 298) {
    $i++;
    ?>
    <center style='line-height: 0px;text-align: left;color: greenyellow;font-weight: bold;-webkit-text-stroke: 1px red;font-size:  18px;display:inline-block;' class='shop2icobg shop2ico<?= $i; ?>'><?= $i; ?></center>
    <?php
}

function indexFirstIndexArr($arr) {
    $arr2 = [];
    foreach ($arr as $key => $value) {
        $arr2[] = $value;
    }
    return $arr2;
}
