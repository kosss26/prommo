<?php
// В начале файла добавим определение текущей страницы через JavaScript
?>
<script>
    // Функция для определения текущей страницы
    function isMainPage() {
        let currentPath = window.location.pathname;
        return currentPath === '/' || currentPath === '/main.php' || currentPath.startsWith('/main.php');
    }
</script>

<!DOCTYPE html>
<html>
    <head>
        <?php if (empty($_POST['glbool'])) { ?>  
            <title>ProMMO</title>
            <meta name="description" content="ProMMO - бесплатная онлайн игра в стиле MMORPG для мобильных телефонов. Защити свою расу, учавствуй в турнирах, докажи, что ты герой!" />
            <meta name="keywords" content="ProMMO, mmo, mmorpg, мобильные игры" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
            <meta name="theme-color" content="#C8AC70">
            <link rel="shortcut icon" href="/favicon.ico">
            <meta name="author" content="Kosoy">

            <link rel="stylesheet" href="/style/default_v_1_0.css?136.1114" type="text/css">
            <link rel="stylesheet" href="/style/dd.css" type="text/css">
            <style>
                /* Базовые стили для body */
                body {
                    margin: 0;
                    position: fixed;
                    width: 100%;
                    height: 100%;
                    overflow: hidden;
                    background: linear-gradient(135deg, #D4B886, #C8AC70) !important;
                }

                /* Базовый контейнер */
                mobitva {
                    display: block;
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 40px;
                    overflow-y: auto;
                    -webkit-overflow-scrolling: touch;
                    background: rgba(255, 255, 255, 0.05);
                    box-sizing: border-box;
                    z-index: 1;
                }

                /* Статус-бар */
                .status-bar {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 40px;
                    background: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
                    backdrop-filter: blur(5px);
                    padding: 8px 0;
                    z-index: 100;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                }

                /* Стили для главной страницы */
                body.main-page mobitva {
                    top: 40px;
                }

                body.main-page .status-bar {
                    display: block;
                }

                /* Футер */
                .footlinetime {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 40px;
                    background: rgba(0,0,0,0.8);
                    backdrop-filter: blur(5px);
                    z-index: 98;
                }

                .footlmenut,
                .footrmenut {
                    position: fixed;
                    bottom: 0;
                    height: 40px;
                    background: rgba(0,0,0,0.8);
                    backdrop-filter: blur(5px);
                    z-index: 99;
                }

                .footlmenut {
                    left: 0;
                    width: 50%;
                }

                .footrmenut {
                    right: 0;
                    width: 50%;
                }

                /* Мобильные устройства */
                @media (max-width: 480px) {
                    .status-bar {
                        height: 32px;
                    }
                    
                    body.main-page mobitva {
                        top: 32px;
                    }
                    
                    mobitva {
                        bottom: 32px;
                    }
                    
                    .footlinetime,
                    .footlmenut,
                    .footrmenut {
                        height: 32px;
                    }
                }

                /* iOS фиксы */
                @supports (-webkit-touch-callout: none) {
                    mobitva {
                        height: -webkit-fill-available;
                    }
                }

                /* Отключаем зум на тач-устройствах */
                * { 
                    touch-action: manipulation;
                }
            </style>
            <?php
        }
        require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';
        $merchant_id = '86179';
        $merchant_secret = 'or8flttb';
        $addevent = "";

        if (isset($_REQUEST['SIGN'])) {
            $oplativshiy = $mc->query("SELECT * FROM `buyplata` WHERE `id`=" . $_REQUEST['MERCHANT_ORDER_ID'] . "")->fetch_array(MYSQLI_ASSOC);
            $sign = md5($merchant_id . ':' . $oplativshiy['colvo'] . ':' . $merchant_secret . ':' . $_REQUEST['MERCHANT_ORDER_ID']);
            if ($sign == $_REQUEST['SIGN']) {
                //MERCHANT_ORDER_ID
                $mc->query("UPDATE `buyplata` SET `status` = '1' WHERE `buyplata`.`id` = " . $_REQUEST['MERCHANT_ORDER_ID'] . ";");
                $usrpl = $mc->query("SELECT * FROM `users` WHERE `id`=" . $oplativshiy['user'] . "")->fetch_array(MYSQLI_ASSOC);


                if($oplativshiy['event'] == 0)
                {
                    $newplat = $usrpl['platinum'] + ($_REQUEST['AMOUNT'] / 1.5);
                    $mc->query("UPDATE `users` SET `platinum` = '" . $newplat . "' WHERE `users`.`id` = " . $oplativshiy['user'] . "");
                    $kolplat = $_REQUEST['AMOUNT'] / 1.5;
                    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usrpl['id'] . "','Вам начиленно {$kolplat} <img class=\"ico_head_all\" src=\"/images/icons/plata.png\">','" . time() . "','donat')");
                }

                if($oplativshiy['event'] == 1)
                {
                    //REBOOT event
                    if(0 <= $usrpl['level'] && $usrpl['level'] <= 9)
                    {
                        $mc->query("INSERT INTO `userbag`(`id_user`, `id_shop`, `id_punct`, `BattleFlag`, `iznos`, `koll`, `max_hc`) VALUES ('". $usrpl['id'] ."', 1892, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1892, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1802, 10, 0, -1, -1, 1)");
                    }else if(10 <= $usrpl['level'] && $usrpl['level'] <= 14)
                    {
                        $mc->query("INSERT INTO `userbag`(`id_user`, `id_shop`, `id_punct`, `BattleFlag`, `iznos`, `koll`, `max_hc`) VALUES ('". $usrpl['id'] ."', 1893, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1892, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1802, 10, 0, -1, -1, 1)");
                    }else if(15 <= $usrpl['level'] && $usrpl['level'] <= 20)
                    {
                        $mc->query("INSERT INTO `userbag`(`id_user`, `id_shop`, `id_punct`, `BattleFlag`, `iznos`, `koll`, `max_hc`) VALUES ('". $usrpl['id'] ."', 1894, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1893, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1802, 10, 0, -1, -1, 1)");
                    }else {
                        $mc->query("INSERT INTO `userbag`(`id_user`, `id_shop`, `id_punct`, `BattleFlag`, `iznos`, `koll`, `max_hc`) VALUES ('". $usrpl['id'] ."', 1895, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1895, 1, 1, -1, -1, 0), ('". $usrpl['id'] ."', 1802, 10, 0, -1, -1, 1)");
                    }
                    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $usrpl['id'] . "','Вы оплатили предварительный заказ на глобальное событие - Reboot.-','" . time() . "','donat')");
                    $addevent = "(REBOOT) ";
                }



               
                $bonplat = round(($_REQUEST['AMOUNT'] / 1.5) / 10);

                //бонус платины рефералу
                //а пригласившему 1 славы и 1 опыта в ref_bonus
                //проверяем ссылку реф
                $ref = $usrpl['ref'];
                if ($ref > 0) {
                    if ($mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num` = '$ref'")->num_rows > 0) {
                        //обновить
                        $mc->query("UPDATE `ref_bonus` SET `platinum`=`platinum`+'$bonplat' WHERE `ref_num` = '$ref'");
                    } else {
                        //или создать если записи бонусов нет
                        $mc->query("INSERT INTO `ref_bonus` (`id`,`ref_num`,`platinum`) VALUES (NULL,'$ref','$bonplat')");
                    }
                }
                $_GET['name_donaters'] = $addevent.$usrpl['name'];
                $_GET['donat'] = $_REQUEST['AMOUNT'] * 0.9;
                require_once $_SERVER['DOCUMENT_ROOT'] . "/vk.com/bot.php";
            }
        }
        ?>

    </head>
    <body>
        <?php if (empty($_POST['glbool'])) { ?>  
            <script src="../javascript/jquery-3.3.1.min.js?136.123" type="text/javascript"></script>
            <script src="../javascript/jquery.serialize-object.min.js?136.123" type="text/javascript"></script>
            <script src="../javascript/jquery.dd.min.js?136.123" type="text/javascript"></script>
            <script src="../javascript/pixi.min.js?136.123" type="text/javascript"></script>
            <script src="../javascript/snow.js?136.576577456787" type="text/javascript"></script>
            <script src="../javascript/findPath.js?136.1234512364123" type="text/javascript"></script>
            
            <div class="msg msg1" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;display: none;">
                <table style="margin: auto;width: 240px;height: 100%">
                    <tr>
                        <td style="vertical-align: middle;text-align: center;">
                            <div style="width:100%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                                <div class="text_msg1" style="margin: 10px;"></div>
                                <div class="button_alt_01" onclick="closeMsg(1);" style="margin: auto;margin-bottom: 5px;" >Принять</div>
                                <div class="button_alt_01" onclick="closeMsg(2);" style="margin: auto;margin-bottom: 5px;">Отклонить</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="msg msg0" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;display: none;">
                <table style="margin: auto;width: 240px;height: 100%">
                    <tr>
                        <td style="vertical-align: middle;text-align: center;">
                            <div style="width:100%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                                <br>
                                <div class="text_msg"></div>
                                <br>
                                <div class="button_alt_01" style="margin: auto;" onclick="closeMsg(1);">Ок</div>
                                <br>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="msgQuest" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;display: none;">
                <table style="margin: auto;width: 100%;height: 100%;max-width: 480px;">
                    <tr>
                        <td style="vertical-align: middle;text-align: center;">
                            <div style="box-shadow: 0 0 10px rgba(0,0,0,0.7);margin: auto;width:80%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                                <div class="text_msg" style="padding: 6px;padding-bottom: 12px;"></div>
                                <table style="width: 100%;margin: auto;height: 50px;">
                                    <tr>
                                        <td><div class="button_alt_01" onclick="closeMsgQuest();" style="margin: auto;width:85%">Ок</div></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="msgEleksir" style="opacity: 0;z-index: 99999999;background-color: rgba(0,0,0,0.5);width: 100%;height: 100%;position: fixed;top: 0;left: 0;display: none;">
                <table style="margin: auto;width: 100%;height: 100%;max-width: 480px;">
                    <tr>
                        <td style="vertical-align: middle;text-align: center;">
                            <div style="box-shadow: 0 0 10px rgba(0,0,0,0.7);margin: auto;width:80%;background-color: #FFFFCC;border-color: black;border-style: solid;border-width: 2px;border-radius: 4px;">
                                <div style="padding: 6px;padding-bottom: 12px;">
                                    Подобные эффекты уже наложены!<br>
                                    Действовать будут только самые сильные эффекты!<br>
                                    Выпить зелье?<br>
                                </div>
                                <table style="width: 100%;margin: auto;height: 50px;">
                                    <tr>
                                        <td><div class="button_alt_01" onclick="closeMsgEleksir();readBattleInfo(11);" style="margin: auto;width:85%">Да</div></td>
                                    </tr>
                                </table>
                                <table style="width: 100%;margin: auto;height: 50px;">
                                    <tr>
                                        <td><div class="button_alt_01" onclick="closeMsgEleksir();" style="margin: auto;width:85%">Нет</div></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="templateBattle" style="display:none;">
                <div class="location_battle" id="tmpBattle">
                    <div class="location0 layer0">
                    </div> 
                    <div id="layer1">
                    </div>
                    <div id="layer2">
                    </div> 
                    <div class="layer3">
                        <canvas class="layer3_1" id="sfyEntity"></canvas>
                    </div>
                    <div class="layer4">
                        <div class="LifeL">
                            <font><img src="images/icons/hp.png" alt="hp" style="height: 100%;display: block;float: left;"></font><font id="HeroLifeL"></font>
                        </div> 
                        <div class="LifeR">
                            <font id="HeroLifeR"></font><font><img src="images/icons/hp.png" alt="hp" style="height: 100%;display: block;float: left;"></font>
                        </div>
                    </div>
                    <font class="snowConteiner layer5"></font>
                    <img class="layer6" src="img/location/GOL_app_location6.png">
                    <div class="layer7">
                        <table class="label_table">
                            <tr>
                                <td class="label_name_l"></td>
                                <td class="btfs label_name_c" id="name1"></td>
                                <td class="label_name_r"></td>
                            </tr>
                        </table>
                    </div>  
                    <div class="layer8">
                        <table class="label_table">
                            <tr>
                                <td class="label_name_l"></td>
                                <td class="btfs label_name_c" id="name2"></td>
                                <td class="label_name_r"></td>
                            </tr>
                        </table>
                    </div> 
                </div>
                <div id="button_visible" style="display: none;width: 100%;">
                    <table style="width: 100%;margin: auto;">
                        <tr id="button_green">
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(0);" id="img_battle_butt" src="img/button/g3.png"></td>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(1);" id="img_battle_butt" src="img/button/g1.png"></td>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(2);" id="img_battle_butt" src="img/button/g2.png"></td>
                        </tr>
                        <tr id="button_yellow" hidden>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(0);" id="img_battle_butt" src="img/button/y3.png"></td>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(1);" id="img_battle_butt" src="img/button/y1.png"></td>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(2);" id="img_battle_butt" src="img/button/y2.png"></td>
                        </tr>
                        <tr id="button_red" hidden>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(0);" id="img_battle_butt" src="img/button/r3.png"></td>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(1);" id="img_battle_butt" src="img/button/r1.png"></td>
                            <td id="battle_butt"><img onclick="mcb(this);readBattleInfo(2);" id="img_battle_butt" src="img/button/r2.png"></td>
                        </tr>
                    </table>
                    <table class="bagBorder_1">
                        <tr>
                            <td>
                                <table class="bagBorder_2">
                                    <tr>
                                        <td class="ico_poyas shield1">
                                            <img id="shield_1" style="width: 100%;" onclick="mcb(this);readBattleInfo(3);" src="images/GOL_app_pocket_null.png">
                                            <div class="btfs number_pos" id="ico_shield_num"></div>
                                        </td>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;height: 100%;">
                                                <div id="ico_poyas0" style="margin: auto;" onclick="mcb(this);readBattleInfo(4);">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas0"></div>
                                        </td>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;height: 100%;">
                                                <div id="ico_poyas1" style="margin: auto;" onclick="mcb(this);readBattleInfo(5);">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas1"></div>
                                        </td>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;height: 100%;">
                                                <div id="ico_poyas2" style="margin: auto;" onclick="mcb(this);readBattleInfo(6);">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas2"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;height: 100%;">
                                                <div id="ico_poyas3" style="margin: auto;" onclick="mcb(this);readBattleInfo(7);">
                                                    <img style="width: 100%;visibility: hidden;" src="images/GOL_app_butil.png">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas3"></div>
                                        </td>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;">
                                                <div id="ico_poyas4" style="margin: auto;" onclick="mcb(this);readBattleInfo(8);">
                                                    <img style="width: 100%;visibility: hidden;" src="images/GOL_app_butil.png">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas4"></div>
                                        </td>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;">
                                                <div id="ico_poyas5" style="margin: auto;" onclick="mcb(this);readBattleInfo(9);">
                                                    <img style="width: 100%;visibility: hidden;" src="images/GOL_app_butil.png">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas5"></div>
                                        </td>
                                        <td class="ico_poyas">
                                            <div style="position: absolute;width: 100%;">
                                                <div id="ico_poyas6" style="margin: auto;" onclick="mcb(this);readBattleInfo(10);">
                                                    <img style="width: 100%;visibility: hidden;" src="images/GOL_app_butil.png">
                                                </div>
                                            </div>
                                            <img style="width: 100%;" src="images/GOL_app_pocket.png">
                                            <div class="btfs number_pos" id="num_poyas6"></div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <script type="text/javascript">
                if (!imgLoading) {
                    var imgLoading = document.createElement("IMG");
                    imgLoading.alt = "loading";
                    imgLoading.setAttribute('class', 'loading');
                    imgLoading.src = "/img/loading.gif";
                }
                if (!MyLib) {
                    var MyLib = {};
                    MyLib.texts;
                    MyLib.window = {};
                    MyLib.msg = $('.msg0');
                    MyLib.msg1 = $('.msg1');
                    MyLib.msgQuest = $('.msgQuest');
                    MyLib.msgEleksir = $('.msgEleksir');
                    MyLib.msgType = "";
                    MyLib.msgId = 0;
                    MyLib.arrLoc = <?= json_encode($mc->query("SELECT `id`,`IdLoc1`,`IdLoc2`,`IdLoc3`,`IdLoc4`,`IdLoc5`,`IdLoc6`,`IdLoc7`,`IdLoc8`,`IdLoc9`,`IdLoc10`,`access`,`accesslevel` FROM `location` ")->fetch_all(MYSQLI_ASSOC)); ?>;
                    MyLib.findArr = [];
                    MyLib.findArrDrop = [];
                    MyLib.findArrBuy = [];
                    MyLib.findArrflag1 = 0;
                    MyLib.findArrflag2 = 0;
                    //a to b , side , level, duels, [[id monster, [[id_loc: "3"]]], [[id_punct, id, [[id_location: "3"]]]
                    findNewPath = function (a, b, s, level, d, v, k) {
                        MyLib.findArr = [];
                        MyLib.findArrDrop = [];
                        MyLib.findArrBuy = [];
                        MyLib.findArrflag1 = 0;
                        MyLib.findArrflag2 = 0;
                        if (v.length > 0) {
                            for (var i = 0; i < v.length; i++) {
                                for (var i1 = 0; i1 < v[i][1].length; i1++) {
                                    MyLib.findArrDrop.push(findPath(a, v[i][1][i1].id_loc, MyLib.arrLoc, s, level).slice());
                                    if (v[i][1][i1].id_loc == a) {
                                        $("mobitva:eq(-1)").find(".arrowHunt").css({position: "relative"});
                                        $("mobitva:eq(-1)").find(".arrowHunt").append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                                        for (var i2 = 0; i2 < v.length; i2++) {
                                            $("mobitva:eq(-1)").find(".arrowHunt" + v[i2][0]).css({position: "relative"});
                                            $("mobitva:eq(-1)").find(".arrowHunt" + v[i2][0]).append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                                        }
                                        MyLib.findArrDrop = [];
                                        MyLib.findArrflag1 = 1;
                                        break;
                                    }
                                    if (MyLib.findArrflag1 == 1) {
                                        break;
                                    }
                                }
                                if (MyLib.findArrflag1 == 1) {
                                    break;
                                }
                            }
                        }
                        if (k.length > 0) {
                            for (var i = 0; i < k.length; i++) {
                                for (var i1 = 0; i1 < k[i][2].length; i1++) {
                                    MyLib.findArrBuy.push(findPath(a, k[i][2][i1].id_location, MyLib.arrLoc, s, level));
                                    if (k[i][2][i1].id_location == a) {
                                        $("mobitva:eq(-1)").find(".arrowShop").css({position: "relative"});
                                        $("mobitva:eq(-1)").find(".arrowShop").append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                                        if ($("button").is(".arrowShop")) {
                                            $("mobitva:eq(-1)").find(".arrowMenu").css({position: "relative"});
                                            $("mobitva:eq(-1)").find(".arrowMenu").append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                                        }
                                        for (var i2 = 0; i2 < k.length; i2++) {
                                            $("mobitva:eq(-1)").find(".arrowPunctShop" + k[i2][0]).css({position: "relative"});
                                            $("mobitva:eq(-1)").find(".arrowPunctShop" + k[i2][0]).append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                                            $("mobitva:eq(-1)").find(".arrowShop" + k[i2][1]).css({position: "relative"});
                                            $("mobitva:eq(-1)").find(".arrowShop" + k[i2][1]).append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                                        }
                                        MyLib.findArrBuy = [];
                                        MyLib.findArrflag2 = 1;
                                        break;
                                    }
                                    if (MyLib.findArrflag2 == 1) {
                                        break;
                                    }
                                }
                                if (MyLib.findArrflag2 == 1) {
                                    break;
                                }
                            }
                        }
                        if (MyLib.findArrflag1 == 0 && MyLib.findArrflag2 == 0) {
                            MyLib.findArr = findPath(a, b, MyLib.arrLoc, s, level);
                        }
                        for (var i = 0; i < MyLib.findArrDrop.length; i++) {
                            for (var i0 = 0; i0 < MyLib.findArrDrop[i].length; i0++) {
                                MyLib.findArr.push(MyLib.findArrDrop[i][i0].slice());
                            }
                        }
                        for (var i = 0; i < MyLib.findArrBuy.length; i++) {
                            for (var i0 = 0; i0 < MyLib.findArrBuy[i].length; i0++) {
                                MyLib.findArr.push(MyLib.findArrBuy[i][i0].slice());
                            }
                        }
                        MyLib.findArr.ourSortAsc(MyLib.findArr);
                        if (d > 0) {
                            $("mobitva:eq(-1)").find(".arrowDuel").css({position: "relative"});
                            $("mobitva:eq(-1)").find(".arrowDuel").append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                        }
                        for (var i = 0; MyLib.findArr.length > 0 && i < MyLib.findArr[0].length; i++) {
                            $("mobitva:eq(-1)").find(".locArrow" + MyLib.findArr[0][i]).css({position: "relative"});
                            $("mobitva:eq(-1)").find(".locArrow" + MyLib.findArr[0][i]).append("<img src='images/arrow/harrow.gif' alt='<<' style='position: absolute;right: 0;'>");
                        }
                    };
                    MyLib.bttl = {};
                    MyLib.posx = [-100, 100];
                    MyLib.scrollTop = 0;
                    MyLib.ticks = 0;
                    MyLib.style = 5;
                    MyLib.userLevel = 0;
                    MyLib.userMoney = 0;
                    MyLib.userPlatina = 0;
                    MyLib.TimerFuckOff;
                    MyLib.setTimeoutFoot;
                    MyLib.setTimeoutHuntB;
                    MyLib.intervaltimer = [];
                    MyLib.setTimeid = [];
                    MyLib.battleIntervalTimer = [];
                    MyLib.battleSetTimeid = [];
                    MyLib.save = 0;
                    MyLib.restore = 0;
                    MyLib.startapp = 0;
                    MyLib.loaded = 0;
                    MyLib.loaded1 = 0;
                    MyLib.loadfoot = 0;
                    MyLib.time = "";
                    MyLib.timeToZBT = "";
                    MyLib.TempLink = "";
                    MyLib.footName = "";
                    MyLib.footLoad;
                    MyLib.GoToUrl = "https://mobitva2.online/";
                    MyLib.timeToZBT = Math.floor(new Date('2018-08-15T12:00:00.000Z').getTime() / 1000) - (<?php echo time(); ?> + 10800); //Задаем дату, к которой будет осуществляться обратный отсчет
                    MyLib.coeffFont = 2.4; //% 0...1
                    MyLib.time = <?= time() + 10800; ?>;

                    messages = function (t, id, type) {
                        MyLib.msgId = id;
                        MyLib.msgType = type;
                        $("mobitva").append(MyLib.msg);
                        $('.text_msg:eq(-1)').html(t);
                        $('.msg:eq(-1)').css({display: "block"});
                        $('.msg:eq(-1)').animate({'opacity': '1'}, 300);
                    };
                    questmsg = function (t) {
                        $("mobitva").append(MyLib.msgQuest);
                        $('.text_msg:eq(-1)').html(t);
                        $('.msgQuest:eq(-1)').css({display: "block"});
                        $('.msgQuest:eq(-1)').animate({'opacity': '1'}, 300);
                    };
                    closeMsgQuest = function () {
                        $('.msgQuest:eq(-1)').animate({'opacity': '0'}, 300);
                        MyLib.setTimeid[250] = setTimeout(function () {
                            $('.msgQuest:eq(-1)').css({display: "none"});
                            $('.msgQuest:eq(-1)').remove();
                        }, 300);
                    };
                    Eleksirmsg = function () {
                        $("mobitva").append(MyLib.msgEleksir);
                        $('.msgEleksir:eq(-1)').css({display: "block"});
                        $('.msgEleksir:eq(-1)').animate({'opacity': '1'}, 300);
                    };
                    closeMsgEleksir = function () {
                        $('.msgEleksir:eq(-1)').animate({'opacity': '0'}, 300);
                        MyLib.setTimeid[250] = setTimeout(function () {
                            $('.msgEleksir:eq(-1)').css({display: "none"});
                            $('.msgEleksir:eq(-1)').remove();
                        }, 300);
                    };

                    closeMsg = function (e) {
                        $.ajax({
                            url: "./functions/check_msg.php",
                            type: 'GET',
                            data: {
                                id_msg: MyLib.msgId,
                                otvet: e
                            },
                            success: function (data) {
                                $('.msg:eq(-1)').animate({'opacity': '0'}, 300);
                                MyLib.setTimeid[200] = setTimeout(function () {
                                    $('.msg:eq(-1)').css({display: "none"});
                                    $('.msg:eq(-1)').remove();
                                }, 300);
                            },
                            error: function (e) {
                                NewFuckOff();
                            }
                        });
                    };
                    dozbttimer = function () {
                        $("#dozbt").html(Math.floor(MyLib.timeToZBT / 60 / 60 / 24) + ":" + new Date(MyLib.timeToZBT * 1000).toISOString().slice(-13, -5));
                    };
                    setInterval(function () {
                        MyLib.timeToZBT--;
                        dozbttimer();
                    }, 1000);

                    showContent = function(link) {
                        MyLib.loaded1 = 1;
                        if (MyLib.startapp != 0 && !$("img").is(".imgLoading")) {
                            document.body.appendChild(imgLoading);
                        } else {
                            MyLib.startapp = 1;
                        }
                        
                        clearTimeout(MyLib.setTimeoutFoot);
                        clearTimeout(MyLib.setTimeoutHuntB);
                        for (var i = 0; i < MyLib.intervaltimer.length; i++) {
                            clearInterval(MyLib.intervaltimer[i]);
                        }
                        for (var i = 0; i < MyLib.setTimeid.length; i++) {
                            clearTimeout(MyLib.setTimeid[i]);
                        }
                        for (var i = 0; i < MyLib.battleIntervalTimer.length; i++) {
                            clearInterval(MyLib.battleIntervalTimer[i]);
                        }
                        for (var i = 0; i < MyLib.battleSetTimeid.length; i++) {
                            clearTimeout(MyLib.battleSetTimeid[i]);
                        }

                        $.ajax({
                            type: "POST",
                            url: link,
                            dataType: "text",
                            data: {
                                glbool: 1
                            },
                            success: function(data) {
                                // Проверяем существование элемента mobitva
                                if ($("mobitva").length === 0) {
                                    $("body").append("<mobitva></mobitva>");
                                }
                                
                                $("mobitva").html(data);
                                
                                // Убираем все индикаторы загрузки
                                $(".imgLoading").remove();
                                $(".loading").remove();
                                if ($("img").is(".imgLoading")) {
                                    imgLoading.remove();
                                }
                                
                                // Инициализируем обработчики после загрузки контента
                                if (typeof initHandlers === 'function') {
                                    initHandlers();
                                }
                            },
                            error: function() {
                                // Убираем все индикаторы загрузки при ошибке
                                $(".imgLoading").remove();
                                $(".loading").remove();
                                if ($("img").is(".imgLoading")) {
                                    imgLoading.remove();
                                }
                                NewFuckOff();
                            }
                        });
                    };
                    HuntMobBattleOne = function (mobid) {
                        if (MyLib.footName !== "huntbattle") {
                            if (!$("img").is(".imgLoading")) {
                                $("body").prepend("<img class='loading' src='" + imgLoading.src + "' alt='loading'>" +
                                        "<div class='linefooter sizeFooterH'></div>");
                            }
                            $.ajax({
                                type: "POST",
                                url: "php/HuntMobBattleOne.php",
                                dataType: "json",
                                data: {
                                    Login: getCookie('login'),
                                    Password: getCookie('password'),
                                    Mobid: "" + mobid
                                },
                                success: function (a) {
                                    if (a === 0) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Противник не доступен в данной локации"));
                                    } else if (a === 1) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Противник уже сражается ."));
                                    } else if (a === 2) {
                                        showContent("/main.php?msg=" + encodeURIComponent("У противника недостаточно здоровья ."));
                                    } else if (a === 97) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Недостаточно Здоровья ."));
                                    } else if (a === 98) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Недостаточно выносливости ."));
                                    } else {
                                        showContent("/hunt/battle.php");
                                    }
                                },
                                error: function () {
                                    NewFuckOff();
                                }
                            });
                        }
                    };
                    HuntMobBattleConnect = function (Btid) {
                        if (MyLib.footName !== "huntbattle") {
                            if (!$("img").is(".imgLoading")) {
                                $("body").prepend("<img class='loading' src='" + imgLoading.src + "' alt='loading'>" +
                                        "<div class='linefooter sizeFooterH'></div>");
                            }
                            $.ajax({
                                type: "POST",
                                url: "php/HuntMobBattleConnect.php",
                                dataType: "json",
                                data: {
                                    Login: getCookie('login'),
                                    Password: getCookie('password'),
                                    Btid: "" + Btid
                                },
                                success: function (a) {
                                    if (a === 0) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Бой не доступен в данной локации ."));
                                    } else if (a === 1) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Вы уже сражаетесь в этой битве ."));
                                    } else if (a === 2) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Противник слишком слаб ."));
                                    } else if (a === 97) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Недостаточно Здоровья ."));
                                    } else if (a === 98) {
                                        showContent("/main.php?msg=" + encodeURIComponent("Недостаточно выносливости ."));
                                    } else {
                                        showContent("/hunt/battle.php");
                                    }
                                },
                                error: function () {
                                    NewFuckOff();
                                }
                            });
                        }
                    };
                    var timerOff = 600000;
                    NewFuckOff = function () {
                        window.location = "disconnect.php";
                    };
                    setInterval(function () {
                        timerOff--;
                        if (timerOff <= 0) {
                            NewFuckOff();
                        }
                    }, 1000);
                    window.onmouseup = function (e) {
                        timerOff = 600000;
                    };
                }
                function arrrandval(arr) {
                    return arr[Math.floor(Math.random() * arr.length)];
                }
                // возвращает cookie с именем name, если есть, если нет, то undefined
                function getCookie(name) {
                    var matches = document.cookie.match(new RegExp(
                            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
                            ));
                    return matches ? decodeURIComponent(matches[1]) : undefined;
                }
                function setCookie(name, value, options) {
                    options = options || {};
                    var expires = options.expires;
                    if (typeof expires == "number" && expires) {
                        var d = new Date();
                        d.setTime(d.getTime() + expires * 1000);
                        expires = options.expires = d;
                    }
                    if (expires && expires.toUTCString) {
                        options.expires = expires.toUTCString();
                    }

                    value = encodeURIComponent(value);
                    var updatedCookie = name + "=" + value;
                    for (var propName in options) {
                        updatedCookie += "; " + propName;
                        var propValue = options[propName];
                        if (propValue !== true) {
                            updatedCookie += "=" + propValue;
                        }
                    }

                    document.cookie = updatedCookie;
                }
                function deleteCookie(name) {
                    setCookie(name, "", {
                        expires: -1
                    });
                }


                //************** footer js
                MyLib.window.innerHeight;
                MyLib.window.innerWidth;

                // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight

                if (typeof window.innerWidth !== 'undefined') {
                    MyLib.window.innerWidth = window.innerWidth;
                    MyLib.window.innerHeight = window.innerHeight;
                    // IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
                } else if (typeof document.documentElement !== 'undefined'
                        && typeof document.documentElement.clientWidth !==
                        'undefined' && document.documentElement.clientWidth !== 0) {
                    MyLib.window.innerWidth = document.documentElement.clientWidth;
                    MyLib.window.innerHeight = document.documentElement.clientHeight;
                    // older versions of IE
                } else {
                    MyLib.window.innerWidth = document.getElementsByTagName('body')[0].clientWidth;
                    MyLib.window.innerHeight = document.getElementsByTagName('body')[0].clientHeight;
                }
                window.onresize = function () {
                    MyLib.window.innerHeight = window.innerHeight;
                    MyLib.window.innerWidth = window.innerWidth;
                    resizer();
                    if (typeof resizeBattle !== 'undefined') {
                        resizeBattle();
                    }
                };
                resizer = function () {
                    $(".footbcs").css({height: (window.innerHeight * 0.06) + "px"});
                    $(".footbcs").css({fontSize: ($(".footbcs").height() * 0.4) + "px"});
                    $(imgLoading).height(window.innerHeight * 0.06 - 1);
                };
                resizer();
                timeFooterSet = function () {
                    $(".timefooter").text(new Date(MyLib.time * 1000).toISOString().slice(-13, -8));
                };

                menuOnOff = function (e) {
                    if (e === 0) {
                        $(".footlmenut").hide();
                        $(".footrmenut").hide();
                    }
                    if (e === 1) {
                        $(".footlmenut").show();
                        $(".footrmenut").show();
                    }
                };
                menuButtonOnOff = function (e) {
                    if (e === 0) {
                        $(".fblmenu").hide();
                        $(".fbrmenu").hide();
                    }
                    if (e === 1) {
                        $(".fblmenu").show();
                        $(".fbrmenu").show();
                    }
                };
                //создание обработчиков клика по классам .L_index_1 .R_index_1
                footGo = function () {
                    $(".fblmenu").hide();
                    $(".fbrmenu").hide();
                };
                newfootL = function () {
                    $(".fbrmenu").hide();
                    newinverter(".fblmenu");
                };
                newfootR = function () {
                    $(".fblmenu").hide();
                    newinverter(".fbrmenu");
                };
                newinverter = function (e) {
                    if ($(e).is(":visible")) {
                        $(e).hide();
                    } else {
                        $(e).show();
                    }
                };
                setInterval(function () {
                    MyLib.time += 1;
                    timeFooterSet();
                    resizer();
                }, 1000);

                MyLib.footLoad = 1;
                setTimeFoot = function () {
                    if (MyLib.loaded1 === 0) {
                        MyLib.loaded1 = 1;
                        try {
                            $.ajax({
                                type: "POST",
                                url: "./system/time.php",
                                dataType: "json",
                                data: {
                                    Login: getCookie('login'),
                                    Password: getCookie('password')
                                },
                                success: function (data) {
                                    if (data.onbattle === 1
                                            && MyLib.footName !== "huntbattle"
                                            && MyLib.footName !== "indexnone"
                                            && MyLib.footName !== "chat"
                                            && MyLib.footName !== "chatclan"
                                            && MyLib.footName !== "huntresult"
                                            && MyLib.footName !== "command"
                                            && MyLib.footName !== "ban"
                                            && MyLib.footName !== "chattav"
                                            && MyLib.footName !== "friends"
                                            && MyLib.footName !== "profile"
                                            && MyLib.footName !== "mailtomain"
                                            && MyLib.footName !== "tomail"
                                            && MyLib.footName !== "adminindex"
                                            && MyLib.footName !== "adminadmin"
                                            && MyLib.footName !== "online"
                                            && MyLib.footName !== "clan"
                                            && MyLib.footName !== "chatclan"
                                            && MyLib.footName !== "online"
                                            && MyLib.footName !== "adminbattle"
                                            && MyLib.loaded === 0
                                            ) {
                                        MyLib.footName = "huntbattle";
                                        showContent("/hunt/battle.php");
                                    } else if (data.result === 1
                                            && MyLib.footName !== "huntresult"
                                            && MyLib.footName !== "huntbattle"
                                            && MyLib.footName !== "ban"
                                            && MyLib.footName !== "chattav"
                                            && MyLib.footName !== "friends"
                                            && MyLib.footName !== "profile"
                                            && MyLib.footName !== "mailtomain"
                                            && MyLib.footName !== "tomail"
                                            && MyLib.footName !== "adminindex"
                                            && MyLib.footName !== "adminadmin"
                                            && MyLib.footName !== "online"
                                            && MyLib.footName !== "clan"
                                            && MyLib.footName !== "chatclan"
                                            && MyLib.footName !== "online"
                                            && MyLib.footName !== "adminbattle"
                                            ) {
                                        MyLib.footName = "huntresult";
                                        showContent("/hunt/result.php");
                                    } else {
                                        MyLib.loaded1 = 0;
                                        $(".hp").text(data.hp);
                                        $(".vinos").text(data.vinos);
                                    }
                                    if (!$("div").is(".msg") && data.msg.id > 0 && data.msg.id !== "") {
                                        messages(data.msg.message, data.msg.id, data.msg.type);
                                    }
                                }
                            });
                        } catch (e) {
                        }
                    }
                    resizer();
                };
                setInterval(function () {
                    setTimeFoot();
                }, 10000);

            </script>
        <script src="../javascript/battle.js?136.1114" type="text/javascript"></script>
        <?php } if (isset($_POST['glbool']) && isset($user['id'])) { ?>
            <div class="status-bar">
                <div class="status-bar__container">
                    <div class="status-bar__item">
                        <img src="/img/img23.png" alt="Level" class="status-bar__icon">
                        <span class="status-bar__value"><?= $user['level']; ?></span>
                    </div>
                    
                    <div class="status-bar__item status-bar__item--health">
                        <img src="/images/icons/hp.png" alt="HP" class="status-bar__icon">
                        <span class="status-bar__value hp"><?= $user['temp_health']; ?></span>
                    </div>

                    <?php if ($user['platinum'] != 0) { ?>
                        <div class="status-bar__item">
                            <img src="/images/icons/plata.png" alt="Platinum" class="status-bar__icon">
                            <span class="status-bar__value"><?= $user['platinum']; ?></span>
                        </div>
                    <?php } ?>

                    <?php if (money($user['money'], 'zoloto') != 0) { ?>
                        <div class="status-bar__item">
                            <img src="/images/icons/zoloto.png" alt="Gold" class="status-bar__icon">
                            <span class="status-bar__value"><?= money($user['money'], 'zoloto'); ?></span>
                        </div>
                    <?php } ?>

                    <?php if (money($user['money'], 'serebro') != 0) { ?>
                        <div class="status-bar__item">
                            <img src="/images/icons/serebro.png" alt="Silver" class="status-bar__icon">
                            <span class="status-bar__value"><?= money($user['money'], 'serebro'); ?></span>
                        </div>
                    <?php } ?>

                    <?php if (money($user['money'], 'med') != 0) { ?>
                        <div class="status-bar__item">
                            <img src="/images/icons/med.png" alt="Copper" class="status-bar__icon">
                            <span class="status-bar__value"><?= money($user['money'], 'med'); ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <style>
                .status-bar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    background: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
                    backdrop-filter: blur(5px);
                    padding: 8px 0;
                    z-index: 99999;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                }

                .status-bar__container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 16px;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 0 16px;
                }

                .status-bar__item {
                    display: flex;
                    align-items: center;
                    gap: 4px;
                    padding: 4px 8px;
                    border-radius: 12px;
                    background: rgba(255,255,255,0.1);
                    transition: all 0.2s ease;
                }

                .status-bar__item:hover {
                    background: rgba(255,255,255,0.15);
                    transform: translateY(-1px);
                }

                .status-bar__item--health {
                    background: rgba(220,53,69,0.2);
                }

                .status-bar__icon {
                    width: 20px;
                    height: 20px;
                    object-fit: contain;
                    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));
                }

                .status-bar__value {
                    color: #fff;
                    font-size: 14px;
                    font-weight: 500;
                    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
                    font-family: 'Arial', sans-serif;
                }

                @media (max-width: 480px) {
                    .status-bar__container {
                        gap: 8px;
                        flex-wrap: wrap;
                    }

                    .status-bar__item {
                        padding: 3px 6px;
                    }

                    .status-bar__icon {
                        width: 16px;
                        height: 16px;
                    }

                    .status-bar__value {
                        font-size: 12px;
                    }
                }
            </style>
        <?php } ?>           
    </body>
</html>

<script>
    // Определяем главную страницу и добавляем класс к body
    function updateMainPageClass() {
        const isMain = window.location.pathname === '/' || 
                       window.location.pathname === '/main.php' || 
                       window.location.pathname.startsWith('/main.php');
        
        document.body.classList.toggle('main-page', isMain);
    }

    // Вызываем при загрузке
    document.addEventListener('DOMContentLoaded', updateMainPageClass);
    
    // Обновляем при смене страницы через AJAX
    if (typeof showContent === 'function') {
        const originalShowContent = showContent;
        showContent = function(url) {
            originalShowContent(url);
            setTimeout(updateMainPageClass, 100);
        };
    }
</script>
