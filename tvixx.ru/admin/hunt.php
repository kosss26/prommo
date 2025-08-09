<?php
require_once '../system/func.php';
require_once '../system/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo+.php';
if (!$user OR $user['access'] < 3) {
    ?>
    <script>showContent("/");</script>
    <?php
    exit;
}
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
?>
<html>
    <head>
        <title>Mobitva v1.0 - Редактор Монстров</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="theme-color" content="#C8AC70">
        <link rel="shortcut icon" href="../favicon.ico" />
        <meta name="author" content="Kosoy"/>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f0f0f0;
                margin: 0;
                padding: 10px;
            }
            .block {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 15px;
                margin: 0 auto 20px;
                max-width: 800px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            center {
                display: block;
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 10px 0;
            }
            td {
                padding: 8px;
                vertical-align: middle;
            }
            .text, input[type='text'], input[type='number'], textarea, select {
                width: 100%;
                padding: 6px;
                border: 1px solid #ccc;
                border-radius: 3px;
                box-sizing: border-box;
                font-size: 14px;
            }
            textarea {
                min-height: 50px;
                resize: vertical;
            }
            .button_alt_01 {
                background: #007bff;
                color: #fff;
                border: none;
                padding: 8px 15px;
                border-radius: 3px;
                cursor: pointer;
                font-size: 14px;
                margin: 5px 0;
                width: 100%;
                max-width: 200px;
            }
            .button_alt_01:hover {
                background: #0056b3;
            }
            hr {
                border: none;
                border-top: 1px solid #ccc;
                margin: 15px 0;
            }
            .hr_01 {
                border-top: 1px solid #999;
            }
            .msg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 9999;
                display: none;
            }
            .msg table {
                height: 100%;
            }
            .msg td {
                vertical-align: middle;
                text-align: center;
            }
            .msg div[style*="background-color"] {
                background: #fff;
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 15px;
                max-width: 90%;
                margin: 0 auto;
            }
            .effects, .stats {
                background: #f9f9f9;
                border: 1px solid #eee;
                border-radius: 3px;
                padding: 10px;
                margin: 10px 0;
            }
            .counts {
                font-size: 16px;
                font-weight: bold;
                margin: 10px 0;
            }
            font[onclick^="up"], font[onclick^="down"] {
                cursor: pointer;
                font-size: 24px;
                padding: 0 10px;
            }
            div[style*="float: left"] {
                float: left;
                border: 1px solid #ccc;
                border-radius: 3px;
                padding: 5px;
                margin: 5px;
                text-align: center;
                width: 60px;
            }
            .section-header {
                background: #4a6da7;
                color: white;
                padding: 10px;
                margin: 15px 0 10px 0;
                border-radius: 5px;
                font-weight: bold;
                text-align: center;
            }
            .tooltip {
                display: inline-block;
                position: relative;
                margin-left: 5px;
                cursor: help;
            }
            .tooltip .tooltiptext {
                visibility: hidden;
                width: 250px;
                background-color: #555;
                color: #fff;
                text-align: left;
                border-radius: 6px;
                padding: 10px;
                position: absolute;
                z-index: 1;
                bottom: 125%;
                left: 50%;
                transform: translateX(-50%);
                opacity: 0;
                transition: opacity 0.3s;
                font-size: 12px;
                line-height: 1.4;
            }
            .tooltip:hover .tooltiptext {
                visibility: visible;
                opacity: 1;
            }
            .field-group {
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
                margin-bottom: 15px;
                background: #f9f9f9;
            }
            .field-label {
                font-weight: bold;
                margin-bottom: 5px;
            }
            .preview-mob {
                background: #343a40;
                color: white;
                padding: 15px;
                border-radius: 5px;
                text-align: center;
                margin-bottom: 15px;
            }
            .preview-mob img {
                margin: 10px;
            }
            .tabs {
                display: flex;
                border-bottom: 1px solid #ccc;
                margin-bottom: 15px;
            }
            .tab {
                padding: 10px 20px;
                cursor: pointer;
                border: 1px solid transparent;
                border-radius: 5px 5px 0 0;
            }
            .tab.active {
                background: #f9f9f9;
                border: 1px solid #ccc;
                border-bottom-color: #f9f9f9;
            }
            .tab-content {
                display: none;
            }
            .tab-content.active {
                display: block;
            }
            .info-box {
                background: #e9f7ff;
                border-left: 4px solid #007bff;
                padding: 10px;
                margin: 10px 0;
                font-size: 13px;
            }
            @media (max-width: 600px) {
                .block {
                    padding: 10px;
                    margin: 0 5px 10px;
                }
                td {
                    display: block;
                    width: 100%;
                    padding: 5px;
                }
                .button_alt_01 {
                    width: 100%;
                }
                input, select, textarea {
                    font-size: 16px;
                }
            }
        </style>
    </head>
    <body>
        <div class="msg">
            <table>
                <tr>
                    <td>
                        <div>
                            <div class="text_msg">sssssssssss</div>
                            <br>
                            <button class="button_alt_01" onclick="$('.msg').css({display: 'none'})">Ок</button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="hiddenEffect" hidden>
            <hr>
            <div class="counts">-- образец --</div>
            <div style='text-align: center'>
                <font onclick="up1($(this));">▲</font>
                <font onclick="down1($(this));">▼</font>
            </div>
            <table>
                <tr><td>название:</td></tr>
                <tr><td><textarea name='NameEffect[]'></textarea></td></tr>
                <tr>
                    <td>Кому:</td>
                    <td>
                        <select name='Effect[0]'>
                            <option value='0'>Герою</option>
                            <option value='1'>Противнику</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Количество:</td>
                    <td><input type='number' name='Effect[1]' value='10'></td>
                </tr>
                <tr>
                    <td>Когда:</td>
                    <td>
                        <select name='Effect[2]'>
                            <option value='0'>При попадании</option>
                            <option value='1'>Всегда</option>
                            <option value='2'>По завершении количества-при попадании</option>
                            <option value='3'>По завершению количества - всегда</option>
                            <option value='4'>каждый * раз - при попадании</option>
                            <option value='5'>каждый * раз - всегда</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>каждый * раз, применится каждый * ход:</td>
                    <td>
                        <input type='number' name='Effect[6]' value='1'>
                        <input type='number' name='Effect[7]' value='0' hidden>
                    </td>
                </tr>
                <tr>
                    <td>когда использовать ЭФФЕКТ</td>
                    <td>
                        <select name='Effect[4]'>
                            <option value='0'>ПРИ ХОДЕ ОБОИХ</option>
                            <option value='1'>КОГДА ОН БЬЁТ</option>
                            <option value='2'>КОГДА ЕГО БЬЮТ</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>шарахнуть сразу или нет?</td>
                    <td>
                        <select name='Effect[5]'>
                            <option value='0'>сразу</option>
                            <option value='1'>нет</option>
                        </select>
                    </td>
                </tr>
            </table>
            Статы:
            <div class="allStats"></div>
            <center>
                <button class='button_alt_01' type='button' onclick="addStats($(this));">Добавить stats</button>
                <br>
                <button class='button_alt_01' type='button' onclick="dellEffects($(this));">Удалить этот эффект</button>
            </center>
            <hr>
        </div>

        <div id="hiddenStats" hidden>
            <hr class="hr_01">
            <div style='text-align: center'>
                <font onclick="up($(this));">▲</font>
                <font onclick="down($(this));">▼</font>
            </div>
            <table>
                <tr>
                    <td>название</td>
                    <td>на ходов</td>
                    <td>значение</td>
                    <td>++разгон</td>
                </tr>
                <?php for ($i01 = 0; $i01 < count($arrstat); $i01++) { ?>
                    <tr>
                        <td><?= $arrstat[$i01]; ?><input type="number" name="Effect[3][<?= $i01; ?>][][0]" value="<?= $i01; ?>" hidden></td>
                        <td><input type="number" name="Effect[3][<?= $i01; ?>][][1]" value="0"></td>
                        <td><input type="number" name="Effect[3][<?= $i01; ?>][][2]" value="0"></td>
                        <td><input type="number" name="Effect[3][<?= $i01; ?>][][3]" value="0"></td>
                    </tr>
                <?php } ?>
            </table>
            <center>
                <button class='button_alt_01' type='button' onclick="dellStats($(this));">Удалить Statas</button>
            </center>
            <br>
        </div>

        <script>
            function up(e) { e.closest(".stats").insertBefore(e.closest(".stats").prev()); renamecounts(); }
            function down(e) { e.closest(".stats").insertAfter(e.closest(".stats").next()); renamecounts(); }
            function up1(e) { e.closest(".effects").insertBefore(e.closest(".effects").prev()); renamecounts(); }
            function down1(e) { e.closest(".effects").insertAfter(e.closest(".effects").next()); renamecounts(); }
            function addEffect() { $(".visualEffects").append("<div class='effects'>" + $("#hiddenEffect").html() + "</div>"); renamecounts(); }
            function addStats(e) { e.closest('.effects').find(".allStats").append("<div class='stats'>" + $("#hiddenStats").html() + "</div>"); renamecounts(); }
            function dellEffects(e) { e.closest('.effects').remove(); renamecounts(); }
            function delAllEffect() { $('.effects').remove(); }
            function dellStats(e) { e.closest('.stats').remove(); renamecounts(); }
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
                $("body").prepend("<img class='loading' src='" + imgLoading.src + "' alt='loading'><div class='linefooter sizeFooterH'></div>");
                $.ajax({
                    type: "POST",
                    url: "/admin/hunt/hunt_l.php",
                    data: { strdata: e },
                    dataType: "json",
                    success: function (el) {
                        $(".loading").remove();
                        var data = el;
                        if (data.otvet == 1) {
                            $('#id').val(data.new_id);
                            msg("монстр создан");
                        } else if (data.otvet == 2) {
                            msg("монстр обновлен");
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
                $(document).on('mousemove', 'textarea', function (e) {
                    var a = $(this).offset().top + $(this).outerHeight() - 16,
                        b = $(this).offset().left + $(this).outerWidth() - 16;
                    $(this).css({ cursor: e.pageY > a && e.pageX > b ? 'nw-resize' : '' });
                }).on('keyup', 'textarea', function () {
                    while ($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
                        $(this).height($(this).height() + 1);
                    }
                });
                
                // Переключение вкладок
                $('.tab').click(function() {
                    $('.tab').removeClass('active');
                    $(this).addClass('active');
                    
                    var tabContent = $(this).data('tab');
                    $('.tab-content').removeClass('active');
                    $('#' + tabContent).addClass('active');
                });
                
                // Обновление предпросмотра
                function updatePreview() {
                    var name = $('textarea[name="name"]').val() || 'Новый монстр';
                    var level = $('input[name="level"]').val() || '0';
                    var hp = $('input[name="max_hp"]').val() || '0';
                    var damage = $('input[name="damage"]').val() || '0';
                    var iconId = $('input[name="iconid"]').val() || '1';
                    
                    $('.mob-name').text(name);
                    $('.mob-level').text('Уровень: ' + level);
                    $('.mob-hp').text('Здоровье: ' + hp);
                    $('.mob-damage').text('Урон: ' + damage);
                    $('.mob-icon').attr('src', '../img/icon/mob/' + iconId + '.png');
                }
                
                // Обновляем предпросмотр при изменении полей
                $('textarea[name="name"], input[name="level"], input[name="max_hp"], input[name="damage"], input[name="iconid"]').on('change keyup', updatePreview);
                
                // Инициализация предпросмотра
                updatePreview();
            });
        </script>

        <?php
        if (isset($_GET['mob']) && $_GET['mob'] == 'add') {
        ?>
            <div class='block'>
                <center>
                    <h2>РЕДАКТОР МОНСТРОВ</h2>
                    <div class="info-box">
                        Добро пожаловать в редактор монстров! Здесь вы можете создать нового монстра для вашей игры.
                        Используйте вкладки ниже для навигации по разным типам параметров.
                    </div>
                    
                    <div class="preview-mob">
                        <h3 class="mob-name">Новый монстр</h3>
                        <img class="mob-icon" src="../img/icon/mob/1.png" height="64">
                        <div class="mob-level">Уровень: 0</div>
                        <div class="mob-hp">Здоровье: 0</div>
                        <div class="mob-damage">Урон: 0</div>
                    </div>
                    
                    <div class="tabs">
                        <div class="tab active" data-tab="basic-tab">Основные параметры</div>
                        <div class="tab" data-tab="drops-tab">Добыча</div>
                        <div class="tab" data-tab="effects-tab">Эффекты</div>
                    </div>
                    
                    <form id="form">
                        <input id='id' name='id' class='text' type='text' value='' hidden>
                        <input name='mob' value='addvbd' type='hidden'>
                        
                        <div id="basic-tab" class="tab-content active">
                            <div class="section-header">Основная информация</div>
                            <div class="field-group">
                                <div class="field-label">
                                    Имя монстра:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Название монстра, которое будет отображаться игрокам</span>
                                    </div>
                                </div>
                                <textarea name='name' type='text'>New Mob</textarea>
                                
                                <div class="field-label">
                                    Уровень:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Определяет сложность монстра и влияет на выпадающие награды</span>
                                    </div>
                                </div>
                                <input name='level' type='number' value='0'>
                                
                                <div class="field-label">
                                    ID изображения:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Выберите номер изображения монстра из списка внизу страницы</span>
                                    </div>
                                </div>
                                <input name='iconid' type='number' value='1'>
                                
                                <div class="field-label">
                                    Стиль монстра:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Стиль определяет специализацию и базовое поведение монстра</span>
                                    </div>
                                </div>
                                <select name='stil'>
                                    <option selected value='0'>Обычный</option>
                                    <option value='1'>Урон (повышенный урон)</option>
                                    <option value='2'>Уворот (высокая ловкость)</option>
                                    <option value='3'>Броня (высокая защита)</option>
                                    <option value='4'>Элита (улучшенные все параметры)</option>
                                </select>
                            </div>
                            
                            <div class="section-header">Характеристики боя</div>
                            <div class="field-group">
                                <div class="field-label">
                                    Максимальное здоровье:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Количество жизней монстра</span>
                                    </div>
                                </div>
                                <input name='max_hp' type='number' value='0'>
                                
                                <div class="field-label">
                                    Текущее здоровье:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Обычно равно максимальному здоровью</span>
                                    </div>
                                </div>
                                <input name='hp' type='number' value='0'>
                                
                                <div class="field-label">
                                    Точность:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Определяет шанс попадания по противнику</span>
                                    </div>
                                </div>
                                <input name='toch' type='number' value='0'>
                                
                                <div class="field-label">
                                    Уворот:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Определяет шанс уклонения от атак противника</span>
                                    </div>
                                </div>
                                <input name='lov' type='number' value='0'>
                                
                                <div class="field-label">
                                    Оглушение:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Шанс оглушить противника</span>
                                    </div>
                                </div>
                                <input name='kd' type='number' value='0'>
                                
                                <div class="field-label">
                                    Блок:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Шанс заблокировать атаку противника</span>
                                    </div>
                                </div>
                                <input name='block' type='number' value='0'>
                                
                                <div class="field-label">
                                    Броня:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Уменьшает получаемый урон</span>
                                    </div>
                                </div>
                                <input name='bron' type='number' value='0'>
                                
                                <div class="field-label">
                                    Урон:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Базовый урон от атак монстра</span>
                                    </div>
                                </div>
                                <input name='damage' type='number' value='0'>
                            </div>
                            
                            <div class="section-header">Дополнительные параметры</div>
                            <div class="field-group">
                                <div class="field-label">
                                    Опыт с монстра:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Количество опыта, которое получит игрок за убийство</span>
                                    </div>
                                </div>
                                <input name='exp' type='number' value='0'>
                                
                                <div class="field-label">
                                    Интервал выхода:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Формат: ДД:ЧЧ:ММ:СС - определяет как часто монстр появляется</span>
                                    </div>
                                </div>
                                <input name='intervalTime' class='text' type='text' value='00:00:00:00'>
                                
                                <div class="field-label">
                                    Доступен по квесту:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Если ДА - монстр будет появляться только при выполнении квеста</span>
                                    </div>
                                </div>
                                <select name='quests'>
                                    <option selected value='0'>НЕТ</option>
                                    <option value='1'>ДА</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="drops-tab" class="tab-content">
                            <div class="section-header">Денежная награда</div>
                            <div class="field-group">
                                <div class="info-box">
                                    Настройте минимальное и максимальное количество валюты, которое будет выпадать с монстра. 
                                    Система выберет случайное значение между минимумом и максимумом.
                                </div>
                                
                                <div class="section-header">Минимум выпадания денег</div>
                                <div class="field-label">Платина:</div>
                                <input name='minplatina' type='number' value='0'>
                                <div class="field-label">Золото:</div>
                                <input name='minzolo' type='number' value='0'>
                                <div class="field-label">Серебро:</div>
                                <input name='minserebro' type='number' value='0'>
                                <div class="field-label">Медь:</div>
                                <input name='minmed' type='number' value='0'>
                                
                                <div class="section-header">Максимум выпадания денег</div>
                                <div class="field-label">Платина:</div>
                                <input name='maxplatina' type='number' value='0'>
                                <div class="field-label">Золото:</div>
                                <input name='maxzolo' type='number' value='0'>
                                <div class="field-label">Серебро:</div>
                                <input name='maxserebro' type='number' value='0'>
                                <div class="field-label">Медь:</div>
                                <input name='maxmed' type='number' value='0'>
                            </div>
                            
                            <div class="section-header">Выпадение предметов</div>
                            <div class="field-group">
                                <div class="info-box">
                                    Формат для списков предметов: [[id предмета, количество боев до выпадения]]<br>
                                    Например: [[890,500],[891,300]] - предмет с ID 890 выпадет через 500 боев, предмет с ID 891 через 300 боев
                                </div>
                                
                                <div class="section-header">Предметы за золото</div>
                                <div class="field-label">
                                    Количество случайных предметов:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Сколько случайных предметов выпадет из списка ниже (0 - выпадут все)</span>
                                    </div>
                                </div>
                                <input name='ids_shopG_num' type='number' value="0">
                                
                                <div class="field-label">
                                    Случайные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Список предметов, из которого будет выбрано случайное количество</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopG_rand' type='text'>[]</textarea>
                                
                                <div class="field-label">
                                    Гарантированные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Эти предметы выпадут гарантированно</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopG' type='text'>[]</textarea>
                                
                                <div class="section-header">Предметы за платину</div>
                                <div class="field-label">
                                    Количество случайных предметов:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Сколько случайных предметов выпадет из списка ниже (0 - выпадут все)</span>
                                    </div>
                                </div>
                                <input name='ids_shopP_num' type='number' value="0">
                                
                                <div class="field-label">
                                    Случайные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Список предметов, из которого будет выбрано случайное количество</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopP_rand' type='text'>[]</textarea>
                                
                                <div class="field-label">
                                    Гарантированные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Эти предметы выпадут гарантированно</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopP' type='text'>[]</textarea>
                                
                                <div class="section-header">Предметы при наличии ключевых предметов</div>
                                <div class="info-box">
                                    Формат: [[id требуемого предмета, id выпадающего предмета, количество боев до выпадения]]<br>
                                    Например: [[703,890,500]] - если у игрока есть предмет с ID 703, то через 500 боев выпадет предмет с ID 890
                                </div>
                                
                                <div class="field-label">
                                    Количество случайных предметов:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Сколько случайных предметов выпадет из списка ниже (0 - выпадут все)</span>
                                    </div>
                                </div>
                                <input name='ids_shopT_num' type='number' value="0">
                                
                                <div class="field-label">
                                    Случайные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Список предметов, из которого будет выбрано случайное количество</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopT_rand' type='text'>[]</textarea>
                                
                                <div class="field-label">
                                    Гарантированные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Эти предметы выпадут гарантированно при наличии ключевого предмета</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopT' type='text'>[]</textarea>
                            </div>
                        </div>
                        
                        <div id="effects-tab" class="tab-content">
                            <div class="section-header">Эффекты монстра</div>
                            <div class="info-box">
                                Эффекты позволяют монстру применять дополнительные способности во время боя.
                                Можно создать несколько эффектов, каждый из которых может влиять на разные характеристики.
                            </div>
                            <div class="visualEffects"></div>
                            <button class='button_alt_01' type="button" onclick="addEffect();">Добавить эффект</button><br>
                            <button class='button_alt_01' type='button' onclick="delAllEffect();">Удалить все эффекты</button>
                        </div>
                        
                        <div class="section-header">Действия с монстром</div>
                        <button class='button_alt_01' type='button' onclick="savePredmet();">Сохранить монстра</button><br>
                        <button class='button_alt_01' type='button' onclick="$('#id').val('');savePredmet();">Создать копию</button>
                    </form>
                </center>
            </div>
        <?php
        }

        if (isset($_GET['mob']) && $_GET['mob'] == 'edit' && $_GET['id'] != '') {
            $infmob = $mc->query("SELECT * FROM `hunt` WHERE `id` =" . $_GET['id'])->fetch_array(MYSQLI_ASSOC);
            $JsonEffects = isset($infmob['effects']) && $infmob['effects'] != "" ? json_decode($infmob['effects']) : [];
            $nameeffects = isset($infmob['nameeffects']) && $infmob['nameeffects'] != "" ? explode("|", $infmob['nameeffects']) : [];
        ?>
            <div class='block'>
                <center>
                    <h2>РЕДАКТОР МОНСТРОВ</h2>
                    <div class="info-box">
                        Здесь вы можете редактировать существующего монстра. Используйте вкладки ниже для навигации по разным типам параметров.
                    </div>
                    
                    <div class="preview-mob">
                        <h3 class="mob-name"><?= $infmob['name']; ?></h3>
                        <img class="mob-icon" src="../img/icon/mob/<?= $infmob['iconid']; ?>.png" height="64">
                        <div class="mob-level">Уровень: <?= $infmob['level']; ?></div>
                        <div class="mob-hp">Здоровье: <?= $infmob['max_hp']; ?></div>
                        <div class="mob-damage">Урон: <?= $infmob['damage']; ?></div>
                    </div>
                    
                    <div class="tabs">
                        <div class="tab active" data-tab="basic-tab">Основные параметры</div>
                        <div class="tab" data-tab="drops-tab">Добыча</div>
                        <div class="tab" data-tab="effects-tab">Эффекты</div>
                    </div>
                    
                    <form id='form'>
                        <input id='id' name='id' class='text' type='text' value='<?= $_GET['id']; ?>' hidden>
                        
                        <div id="basic-tab" class="tab-content active">
                            <div class="section-header">Основная информация</div>
                            <div class="field-group">
                                <div class="field-label">
                                    Имя монстра:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Название монстра, которое будет отображаться игрокам</span>
                                    </div>
                                </div>
                                <textarea name='name'><?= $infmob['name']; ?></textarea>
                                
                                <div class="field-label">
                                    Уровень:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Определяет сложность монстра и влияет на выпадающие награды</span>
                                    </div>
                                </div>
                                <input name='level' type='number' value='<?= $infmob['level']; ?>'>
                                
                                <div class="field-label">
                                    ID изображения:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Выберите номер изображения монстра из списка внизу страницы</span>
                                    </div>
                                </div>
                                <input name='iconid' type='number' value='<?= $infmob['iconid']; ?>'>
                                
                                <div class="field-label">
                                    Стиль монстра:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Стиль определяет специализацию и базовое поведение монстра</span>
                                    </div>
                                </div>
                                <select name='stil'>
                                    <option value='0' <?= $infmob['stil'] == 0 ? 'selected' : ''; ?>>Обычный</option>
                                    <option value='1' <?= $infmob['stil'] == 1 ? 'selected' : ''; ?>>Урон (повышенный урон)</option>
                                    <option value='2' <?= $infmob['stil'] == 2 ? 'selected' : ''; ?>>Уворот (высокая ловкость)</option>
                                    <option value='3' <?= $infmob['stil'] == 3 ? 'selected' : ''; ?>>Броня (высокая защита)</option>
                                    <option value='4' <?= $infmob['stil'] == 4 ? 'selected' : ''; ?>>Элита (улучшенные все параметры)</option>
                                </select>
                            </div>
                            
                            <div class="section-header">Характеристики боя</div>
                            <div class="field-group">
                                <div class="field-label">
                                    Максимальное здоровье:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Количество жизней монстра</span>
                                    </div>
                                </div>
                                <input name='max_hp' type='number' value='<?= $infmob['max_hp']; ?>'>
                                
                                <div class="field-label">
                                    Текущее здоровье:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Обычно равно максимальному здоровью</span>
                                    </div>
                                </div>
                                <input name='hp' type='number' value='<?= $infmob['hp']; ?>'>
                                
                                <div class="field-label">
                                    Точность:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Определяет шанс попадания по противнику</span>
                                    </div>
                                </div>
                                <input name='toch' type='number' value='<?= $infmob['toch']; ?>'>
                                
                                <div class="field-label">
                                    Уворот:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Определяет шанс уклонения от атак противника</span>
                                    </div>
                                </div>
                                <input name='lov' type='number' value='<?= $infmob['lov']; ?>'>
                                
                                <div class="field-label">
                                    Оглушение:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Шанс оглушить противника</span>
                                    </div>
                                </div>
                                <input name='kd' type='number' value='<?= $infmob['kd']; ?>'>
                                
                                <div class="field-label">
                                    Блок:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Шанс заблокировать атаку противника</span>
                                    </div>
                                </div>
                                <input name='block' type='number' value='<?= $infmob['block']; ?>'>
                                
                                <div class="field-label">
                                    Броня:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Уменьшает получаемый урон</span>
                                    </div>
                                </div>
                                <input name='bron' type='number' value='<?= $infmob['bron']; ?>'>
                                
                                <div class="field-label">
                                    Урон:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Базовый урон от атак монстра</span>
                                    </div>
                                </div>
                                <input name='damage' type='number' value='<?= $infmob['damage']; ?>'>
                            </div>
                            
                            <div class="section-header">Дополнительные параметры</div>
                            <div class="field-group">
                                <div class="field-label">
                                    Опыт с монстра:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Количество опыта, которое получит игрок за убийство</span>
                                    </div>
                                </div>
                                <input name='exp' type='number' value='<?= $infmob['exp']; ?>'>
                                
                                <div class="field-label">
                                    Интервал выхода:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Формат: ДД:ЧЧ:ММ:СС - определяет как часто монстр появляется</span>
                                    </div>
                                </div>
                                <input name='intervalTime' class='text' type='text' value='<?= sprintf("%02d:%02d:%02d:%02d", ($infmob['intervalTime'] / 3600) / 24, ($infmob['intervalTime'] / 3600) % 24, ($infmob['intervalTime'] % 3600) / 60, ($infmob['intervalTime'] % 3600) % 60); ?>'>
                                
                                <div class="field-label">
                                    Доступен по квесту:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Если ДА - монстр будет появляться только при выполнении квеста</span>
                                    </div>
                                </div>
                                <select name='quests'>
                                    <option value='0' <?= $infmob['quests'] == 0 ? 'selected' : ''; ?>>НЕТ</option>
                                    <option value='1' <?= $infmob['quests'] == 1 ? 'selected' : ''; ?>>ДА</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="drops-tab" class="tab-content">
                            <div class="section-header">Денежная награда</div>
                            <div class="field-group">
                                <div class="info-box">
                                    Настройте минимальное и максимальное количество валюты, которое будет выпадать с монстра. 
                                    Система выберет случайное значение между минимумом и максимумом.
                                </div>
                                
                                <div class="section-header">Минимум выпадания денег</div>
                                <?php
                                $moneymin = $infmob['minmoney'];
                                $medmin = $moneymin % 100;
                                $serebromin = ($moneymin - $medmin) / 100 % 100;
                                $zolotomin = (((($moneymin - $medmin) / 100) - $serebromin) / 100);
                                ?>
                                <div class="field-label">Платина:</div>
                                <input name='minplatina' type='number' value='<?= $infmob['minplatina']; ?>'>
                                <div class="field-label">Золото:</div>
                                <input name='minzolo' type='number' value='<?= $zolotomin; ?>'>
                                <div class="field-label">Серебро:</div>
                                <input name='minserebro' type='number' value='<?= $serebromin; ?>'>
                                <div class="field-label">Медь:</div>
                                <input name='minmed' type='number' value='<?= $medmin; ?>'>
                                
                                <div class="section-header">Максимум выпадания денег</div>
                                <?php
                                $moneymax = $infmob['maxmoney'];
                                $medmax = $moneymax % 100;
                                $serebromax = ($moneymax - $medmax) / 100 % 100;
                                $zolotomax = (((($moneymax - $medmax) / 100) - $serebromax) / 100);
                                ?>
                                <div class="field-label">Платина:</div>
                                <input name='maxplatina' type='number' value='<?= $infmob['maxplatina']; ?>'>
                                <div class="field-label">Золото:</div>
                                <input name='maxzolo' type='number' value='<?= $zolotomax; ?>'>
                                <div class="field-label">Серебро:</div>
                                <input name='maxserebro' type='number' value='<?= $serebromax; ?>'>
                                <div class="field-label">Медь:</div>
                                <input name='maxmed' type='number' value='<?= $medmax; ?>'>
                            </div>
                            
                            <div class="section-header">Выпадение предметов</div>
                            <div class="field-group">
                                <div class="info-box">
                                    Формат для списков предметов: [[id предмета, количество боев до выпадения]]<br>
                                    Например: [[890,500],[891,300]] - предмет с ID 890 выпадет через 500 боев, предмет с ID 891 через 300 боев
                                </div>
                                
                                <div class="section-header">Предметы за золото</div>
                                <div class="field-label">
                                    Количество случайных предметов:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Сколько случайных предметов выпадет из списка ниже (0 - выпадут все)</span>
                                    </div>
                                </div>
                                <input name='ids_shopG_num' type='number' value="<?= $infmob['ids_shopG_num']; ?>">
                                
                                <div class="field-label">
                                    Случайные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Список предметов, из которого будет выбрано случайное количество</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopG_rand'><?= $infmob['ids_shopG_rand']; ?></textarea>
                                
                                <div class="field-label">
                                    Гарантированные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Эти предметы выпадут гарантированно</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopG'><?= $infmob['ids_shopG']; ?></textarea>
                                
                                <div class="section-header">Предметы за платину</div>
                                <div class="field-label">
                                    Количество случайных предметов:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Сколько случайных предметов выпадет из списка ниже (0 - выпадут все)</span>
                                    </div>
                                </div>
                                <input name='ids_shopP_num' type='number' value="<?= $infmob['ids_shopP_num']; ?>">
                                
                                <div class="field-label">
                                    Случайные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Список предметов, из которого будет выбрано случайное количество</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopP_rand'><?= $infmob['ids_shopP_rand']; ?></textarea>
                                
                                <div class="field-label">
                                    Гарантированные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Эти предметы выпадут гарантированно</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopP'><?= $infmob['ids_shopP']; ?></textarea>
                                
                                <div class="section-header">Предметы при наличии ключевых предметов</div>
                                <div class="info-box">
                                    Формат: [[id требуемого предмета, id выпадающего предмета, количество боев до выпадения]]<br>
                                    Например: [[703,890,500]] - если у игрока есть предмет с ID 703, то через 500 боев выпадет предмет с ID 890
                                </div>
                                
                                <div class="field-label">
                                    Количество случайных предметов:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Сколько случайных предметов выпадет из списка ниже (0 - выпадут все)</span>
                                    </div>
                                </div>
                                <input name='ids_shopT_num' type='number' value="<?= $infmob['ids_shopT_num']; ?>">
                                
                                <div class="field-label">
                                    Случайные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Список предметов, из которого будет выбрано случайное количество</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopT_rand'><?= $infmob['ids_shopT_rand']; ?></textarea>
                                
                                <div class="field-label">
                                    Гарантированные предметы:
                                    <div class="tooltip">?
                                        <span class="tooltiptext">Эти предметы выпадут гарантированно при наличии ключевого предмета</span>
                                    </div>
                                </div>
                                <textarea name='ids_shopT'><?= $infmob['ids_shopT']; ?></textarea>
                            </div>
                        </div>
                        
                        <div id="effects-tab" class="tab-content">
                            <div class="section-header">Эффекты монстра</div>
                            <div class="info-box">
                                Эффекты позволяют монстру применять дополнительные способности во время боя.
                                Можно создать несколько эффектов, каждый из которых может влиять на разные характеристики.
                            </div>
                            <div class="visualEffects">
                                <?php for ($i = 0; count($JsonEffects) > $i; $i++) { ?>
                                    <div class="effects">
                                        <hr>
                                        <div class="counts">-- Эффект <?= ($i + 1); ?> --</div>
                                        <div style='text-align: center'>
                                            <font onclick="up1($(this));">▲</font>
                                            <font onclick="down1($(this));">▼</font>
                                        </div>
                                        <table>
                                            <tr><td>
                                                <div class="field-label">
                                                    Название эффекта:
                                                    <div class="tooltip">?
                                                        <span class="tooltiptext">Описательное название эффекта</span>
                                                    </div>
                                                </div>
                                            </td></tr>
                                            <tr><td><textarea name='NameEffect[]'><?= isset($nameeffects[$i]) ? $nameeffects[$i] : ""; ?></textarea></td></tr>
                                            <tr>
                                                <td>
                                                    <div class="field-label">
                                                        Кому применяется:
                                                        <div class="tooltip">?
                                                            <span class="tooltiptext">На кого будет воздействовать эффект</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name='Effect[0]'>
                                                        <option value='0' <?= $JsonEffects[$i][0] == 0 ? 'selected' : ''; ?>>Герою</option>
                                                        <option value='1' <?= $JsonEffects[$i][0] == 1 ? 'selected' : ''; ?>>Противнику</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="field-label">
                                                        Количество срабатываний:
                                                        <div class="tooltip">?
                                                            <span class="tooltiptext">Сколько раз будет применен эффект</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="number" name="Effect[1]" value="<?= $JsonEffects[$i][1]; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="field-label">
                                                        Условие срабатывания:
                                                        <div class="tooltip">?
                                                            <span class="tooltiptext">При каких условиях будет срабатывать эффект</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name='Effect[2]'>
                                                        <option value='0' <?= $JsonEffects[$i][2] == 0 ? 'selected' : ''; ?>>При попадании</option>
                                                        <option value='1' <?= $JsonEffects[$i][2] == 1 ? 'selected' : ''; ?>>Всегда</option>
                                                        <option value='2' <?= $JsonEffects[$i][2] == 2 ? 'selected' : ''; ?>>По завершении количества - при попадании</option>
                                                        <option value='3' <?= $JsonEffects[$i][2] == 3 ? 'selected' : ''; ?>>По завершению количества - всегда</option>
                                                        <option value='4' <?= $JsonEffects[$i][2] == 4 ? 'selected' : ''; ?>>Каждый N раз - при попадании</option>
                                                        <option value='5' <?= $JsonEffects[$i][2] == 5 ? 'selected' : ''; ?>>Каждый N раз - всегда</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="field-label">
                                                        Периодичность:
                                                        <div class="tooltip">?
                                                            <span class="tooltiptext">Для опций "Каждый N раз" - через сколько ходов срабатывает эффект</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type='number' name='Effect[6]' value='<?= isset($JsonEffects[$i][6]) ? $JsonEffects[$i][6] : 1; ?>'>
                                                    <input type='number' name='Effect[7]' value='<?= isset($JsonEffects[$i][7]) ? $JsonEffects[$i][7] : 0; ?>' hidden>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="field-label">
                                                        Момент срабатывания:
                                                        <div class="tooltip">?
                                                            <span class="tooltiptext">В какой момент боя применять эффект</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name='Effect[4]'>
                                                        <option value='0' <?= isset($JsonEffects[$i][4]) && $JsonEffects[$i][4] == 0 ? 'selected' : ''; ?>>При ходе обоих</option>
                                                        <option value='1' <?= isset($JsonEffects[$i][4]) && $JsonEffects[$i][4] == 1 ? 'selected' : ''; ?>>Когда монстр атакует</option>
                                                        <option value='2' <?= isset($JsonEffects[$i][4]) && $JsonEffects[$i][4] == 2 ? 'selected' : ''; ?>>Когда монстра атакуют</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="field-label">
                                                        Мгновенное применение:
                                                        <div class="tooltip">?
                                                            <span class="tooltiptext">Применить эффект сразу в начале боя или по условию</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name='Effect[5]'>
                                                        <option value='0' <?= isset($JsonEffects[$i][5]) && $JsonEffects[$i][5] == 0 ? 'selected' : ''; ?>>Да (сразу)</option>
                                                        <option value='1' <?= isset($JsonEffects[$i][5]) && $JsonEffects[$i][5] == 1 ? 'selected' : ''; ?>>Нет (по условию)</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="field-label">Влияние на характеристики:</div>
                                        <div class="allStats">
                                            <?php for ($i03 = 0; isset($JsonEffects[$i][3][0]) && $i03 < count($JsonEffects[$i][3][0]); $i03++) { ?>
                                                <div class='stats'>
                                                    <hr class="hr_01">
                                                    <div style='text-align: center'>
                                                        <font onclick="up($(this));">▲</font>
                                                        <font onclick="down($(this));">▼</font>
                                                    </div>
                                                    <div class="info-box">
                                                        Настройка влияния эффекта на характеристики. 
                                                        "На ходов" - длительность эффекта, "Значение" - величина изменения, "Разгон" - дополнительное увеличение за ход.
                                                    </div>
                                                    <table>
                                                        <tr>
                                                            <td>Характеристика</td>
                                                            <td>На ходов</td>
                                                            <td>Значение</td>
                                                            <td>Разгон</td>
                                                        </tr>
                                                        <?php for ($i01 = 0; $i01 < count($arrstat); $i01++) { ?>
                                                            <tr>
                                                                <td><?= $arrstat[$i01]; ?><input type="number" name="Effect[3][<?= $i01; ?>][][0]" value="<?= $i01; ?>" hidden></td>
                                                                <td><input type="number" name="Effect[3][<?= $i01; ?>][][1]" value="<?= isset($JsonEffects[$i][3][$i01][$i03][1]) ? $JsonEffects[$i][3][$i01][$i03][1] : 0; ?>"></td>
                                                                <td><input type="number" name="Effect[3][<?= $i01; ?>][][2]" value="<?= isset($JsonEffects[$i][3][$i01][$i03][2]) ? $JsonEffects[$i][3][$i01][$i03][2] : 0; ?>"></td>
                                                                <td><input type="number" name="Effect[3][<?= $i01; ?>][][3]" value="<?= isset($JsonEffects[$i][3][$i01][$i03][3]) ? $JsonEffects[$i][3][$i01][$i03][3] : 0; ?>"></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                    <center>
                                                        <button class='button_alt_01' type='button' onclick="dellStats($(this));">Удалить набор характеристик</button>
                                                    </center>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <center>
                                            <button class='button_alt_01' type='button' onclick="addStats($(this));">Добавить набор характеристик</button><br>
                                            <button class='button_alt_01' type='button' onclick="dellEffects($(this));">Удалить этот эффект</button>
                                        </center>
                                        <hr>
                                    </div>
                                <?php } ?>
                            </div>
                            <button class='button_alt_01' type="button" onclick="addEffect();">Добавить эффект</button><br>
                            <button class='button_alt_01' type='button' onclick="delAllEffect();">Удалить все эффекты</button>
                        </div>
                        
                        <div class="section-header">Действия с монстром</div>
                        <button class='button_alt_01' type='button' onclick="savePredmet();">Сохранить изменения</button><br>
                        <button class='button_alt_01' type='button' onclick="$('#id').val('');savePredmet();">Создать копию</button>
                    </form>
                </center>
            </div>
            <script>renamecounts();</script>
        <?php
        }
        ?>
        <div class='block'>
            <center>
                <h3>Доступные изображения монстров</h3>
                <div class="info-box">
                    Выберите ID изображения и используйте его номер в поле "ID изображения" при создании монстра.
                </div>
            </center>
            <div style='overflow: hidden'>
                <?php
                $i1 = 0;
                while ($i1 <= 69) {
                    $i1++;
                    echo "<div style='float: left'>" . $i1 . "<br><img height='32' src='../img/icon/mob/" . $i1 . ".png'></div>";
                }
                ?>
            </div>
        </div>
        <?php
        $footval = 'adminhunt';
        include '../system/foot/foot.php';
        ?>
    </body>
</html>