<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>🍍Аниматор🍍</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="jquery-3.3.1.min.js"></script>
        <style>
            body{
                text-align: center;
                background-color: #C9D3E2;
            }
            #canvas_1{
                background-color: #C0C0C0;
            }
        </style>
    </head>
    <body >
        <div style="position:fixed;left:0px;top:0px;height:200%;width:26%;background-color:#b0b0b0;outline: 2px solid #000000;"></div>
        <div style="position:absolute;left:0px;top:0px;height:100%;width:26%;">
            <!--left-->
            <h2 style="color:#FFFF33"><b>🍍Ananas Animator🍍</b></h2>
            <div id="leftheader">
                Имя анимации: <input type="text" value="Name" onchange="spriteData.type[nanim] = $(this).val();Animations();" id="nameAnim"><br>
                <br><input type="button" value="Копировать кадр" onclick="AddCount();" id="colcount">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <input type="button" value="Удалить кадр" onclick="DellCount();" id="colcount"><br><br>
                Кадр<input type="button" onclick = "CountStep(-1);" value="<<"> <b id="CadrCount">0</b> <input  onclick = "CountStep(1);" type="button" value=">>">
                <input id="StartStopAnims" onclick="StartStopAnim();" type="button" value="Запустить анимацию">
                <hr>
                <div id ="elements"></div>



                <center><input type="button" value="Добавить элемент" id="btnAddEl" onclick = "AddElement();"></center>
                <center><input type="button" value="Сохранить проект" onclick = "SaveProject();"></center>
            </div>
            <div id="enterproj"></div>
        </div>

        <div style="position:absolute;right:19.5%;top:0px;width:54%;">
            <div><canvas  id="canvas_1"></canvas></div>
            <div><canvas  id="canvas_2" style="background-color: #C0C0C0"></canvas></div>
        </div>



        <div style="position:fixed;right:0px;top:0px;height:200%;width:20%;background-color:#b0b0b0;outline: 2px solid #000000;"> </div>
        <div style="position:fixed;right:0px;top:0px;height:60%;width:20%;background-color:#b0b0b0;outline: 2px solid #000000;overflow-y: scroll;" id="MaskAndPos">



        </div>
        <div style="position:fixed;right:0px;top:60%;height:40%;width:20%;background-color:#b0b0b0;outline: 2px solid #000000;overflow-y: scroll;">
            <div id="rightheader">
                Изображение:<input type="text" onchange="JsonReload(this.value);" id="imgpersontxt" value="GOL_app_hero_goblin.png"><br>
                Раса:<input type="text" onchange="spriteData.rasa[0] = this.value;" id="rassatxt" value="красный"><br>
                <b>АНИМАЦИИ:</b>
                <div id="animations"></div>
            </div>
        </div>



        <script>
            ////////////////////////////////////////////////////////Редактор
            var jsonstring = '{"0":[[[],[]]],"type":["Name"],"rasa":["красный"],"img":"GOL_app_hero_goblin.png"}';
            var cadr = "stop";
            var shag = 1;
            var Nameproj = "";
            var imgbg = new Image();
            var ishodnyicod = document.getElementById("div");
            var maskId = 0;
            var spriteData = [];
            StartFiles();

            function StartFiles() {
                leftheader.style.display = "none";
                rightheader.style.display = "none";
                enterproj.innerHTML = "<h3>Выберите недавний проект: <h3>";

                $.ajax({
                    type: 'post',
                    url: 'jsonfiles.php',
                    data: {'files': 'true'},
                    response: 'text',
                    success: function (data) {
                        enterproj.innerHTML += data;
                        enterproj.innerHTML += "<input type='button' value='Создать новый проект' onclick='NewProject(1,1);'>";
                    }
                });

            }

            function DownloadJson(namefile) {
                $.ajax({
                    type: 'post',
                    url: 'jsonfiles.php',
                    data: {'open': namefile},
                    response: 'text',
                    success: function (data) {
                        Nameproj = namefile;
                        jsonstring = data;
                        leftheader.style.display = "block";
                        rightheader.style.display = "block";
                        enterproj.innerHTML = "";
                        JsonStart();

                        setInterval(function () {
                            //автосохраниение каждую минуту
                            AutoSaveProject();
                        }, 60000);
                    }

                });
            }

            function NewProject(StepNP, NameNP) {
                if (StepNP === 1) {
                    enterproj.innerHTML = '<h3>Введите имя проекта:</h3><br><input type="text" id="txtnewprogect"><br><input type="button" value="Создать" onclick=NewProject(2,txtnewprogect.value);>';
                }

                if (StepNP === 2) {
                    $.ajax({
                        type: 'post',
                        url: 'jsonfiles.php',
                        data: {'save': NameNP + '.json', 'code': '{"0":[[[],[]]],"type":["Name"],"rasa":["красный"],"img":"GOL_app_hero_goblin.png"}'},
                        response: 'text',
                        success: function (data)
                        {
                            if (data === 'Сохранил') {
                                DownloadJson(NameNP + '.json');
                            } else {
                                alert(data);
                            }
                        }
                    });
                }
            }

            function SaveProject() {
                $.ajax({
                    type: 'post',
                    url: 'jsonfiles.php',
                    data: {'save': Nameproj, 'code': JSON.stringify(spriteData)},
                    response: 'text',
                    success: function (data)
                    {
                        alert(data);
                    }
                });
            }

            function AutoSaveProject() {
                $.ajax({
                    type: 'post',
                    url: 'jsonfiles.php',
                    data: {'save': Nameproj, 'code': JSON.stringify(spriteData)},
                    response: 'text',
                    success: function (data)
                    {

                    }
                });
            }

            function OpenJsonCode() {
                nameAnim.value = spriteData.type[nanim];
                rassatxt.value = spriteData.rasa[0];
                MaskAndPos.innerHTML = "";
                redrawElements();
            }
            function CountStep(Cs) {
                console.log("CountStep " + Cs);
                Cs = Cs + count;

                if (Cs < 0) {
                    count = 0;
                } else if (Cs < spriteData[0][nanim].length) {
                    count = Cs;
                }

                $("#CadrCount").text(count);
                EditMask(maskId);
                redrawElements();
            }
            function AddCount() {
                spriteData[0][nanim].push(JSON.parse(JSON.stringify(spriteData[0][nanim][count])));
            }
            function DellCount() {
                if (parseInt($("#CadrCount").text()) > 0) {
                    spriteData[0][nanim].splice(parseInt($("#CadrCount").text()), 1);
                    redrawElements();
                    CountStep(-1);
                }
            }
            function SloyUpDown(Sloy, Sloyid) {
                if (Sloy === 'up') {
                    if (Sloyid > 0) {
                        var predSloy = spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid - 1].slice();
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid - 1] = [];
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid - 1] = spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid].slice();
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid] = [];
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid] = predSloy.slice();
                    }

                }

                if (Sloy === 'down') {
                    if (Sloyid < spriteData[0][nanim][parseInt($("#CadrCount").text())].length - 1) {
                        var predSloy = spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid + 1].slice();
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid + 1] = [];
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid + 1] = spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid].slice();
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid] = [];
                        spriteData[0][nanim][parseInt($("#CadrCount").text())][Sloyid] = predSloy.slice();
                    }
                }

                OpenJsonCode();
            }

            function Animations() {
                redrawElements();
                animations.innerHTML = "";
                for (var i = 0; i < spriteData.type.length; i++) {
                    animations.innerHTML += "<input type='button' value='" + spriteData.type[i] + "'onclick = 'nanim = " + i + ";OpenJsonCode();count = 0;$(\"#CadrCount\").text(0);'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type='button' onclick='DeleteAnims(" + i + ");' value='удалить'><br>";
                }
                animations.innerHTML += "<input type='button' value='+Добавить анимацию+' onclick = 'NewAnims();'><br>";
            }

            function NewAnims() {
                spriteData[0].push([
                    [
                        [158, 0, 70, 70, 170, 136, 70, 70, 0, "щитОтк"],
                        [90, 180, 80, 20, 140, 161, 80, 20, 0, "Лрука"],
                        [1, 235, 44, 67, 85, 130, 44, 67, 0, "флаг"],
                        [0, 56, 47, 56, 110, 154, 47, 56, 0, "торс"],
                        [0, 112, 54, 41, 100, 200, 54, 41, 0, "ноги"],
                        [0, 0, 30, 55, 123, 127, 30, 55, 0, "голова"],
                        [0, 0, 102, 22, 95, 197, 102, 22, 0, "оружие 8-rotate"],
                        [0, 184, 30, 50, 90, 165, 30, 50, 0, "Прука"],
                        [0, 310, 158, 42, 90, 205, 158, 42, 0, "Вспышка"]
                    ],
                    [
                        [158, 0, 70, 70, 170, 136, 70, 70, 0, "щитОтк"],
                        [90, 180, 80, 20, 140, 161, 80, 20, 0, "Лрука"],
                        [1, 235, 44, 67, 85, 130, 44, 67, 0, "флаг"],
                        [0, 56, 47, 56, 110, 154, 47, 56, 0, "торс"],
                        [0, 112, 54, 41, 100, 200, 54, 41, 0, "ноги"],
                        [0, 0, 30, 55, 123, 127, 30, 55, 0, "голова"],
                        [0, 0, 102, 22, 95, 197, 102, 22, 0, "оружие 8-rotate"],
                        [0, 184, 30, 50, 90, 165, 30, 50, 0, "Прука"],
                        [0, 310, 158, 42, 90, 205, 158, 42, 0, "Вспышка"]
                    ],
                    [
                        [158, 0, 70, 70, 170, 136, 70, 70, 0, "щитОтк"],
                        [90, 180, 80, 20, 140, 161, 80, 20, 0, "Лрука"],
                        [1, 235, 44, 67, 85, 130, 44, 67, 0, "флаг"],
                        [0, 56, 47, 56, 110, 154, 47, 56, 0, "торс"],
                        [0, 112, 54, 41, 100, 200, 54, 41, 0, "ноги"],
                        [0, 0, 30, 55, 123, 127, 30, 55, 0, "голова"],
                        [0, 0, 102, 22, 95, 197, 102, 22, 0, "оружие 8-rotate"],
                        [0, 184, 30, 50, 90, 165, 30, 50, 0, "Прука"],
                        [0, 310, 158, 42, 90, 205, 158, 42, 0, "Вспышка"]
                    ]
                ]);
                spriteData.type[spriteData.type.length] = "Name";
                Animations();
            }
            function DeleteAnims(e) {
                console.log("DeleteAnims " + e);
                //удалить анимацию
                if (spriteData[0].length > 1) {
                    delete spriteData[0].splice(e, 1)
                    ;
                    delete spriteData.type.splice(e, 1)
                    ;
                    Animations();
                }
            }
            function StartStopAnim() {
                $("#CadrCount").text(count);
                if (cadr === "start") {
                    cadr = "stop";
                    StartStopAnims.value = "Запустить анимацию";
                } else {
                    cadr = "start";
                    StartStopAnims.value = "Остановить анимацию";
                }
            }

            function AddElement() {
                    spriteData[0][nanim][parseInt($("#CadrCount").text())].push([50, 100, 50, 50, 50, 50, 50, 50, 0, "Имя"]);
                redrawElements();
            }
            function DeleteElement(e) {
                    delete spriteData[0][nanim][parseInt($("#CadrCount").text())].splice(e, 1)
                redrawElements();
            }
            function redrawElements() {
                //очищаем див элементс
                $("#elements").empty();
                //создаем новые элементы управления
                for (var i = 0; i < spriteData[0][nanim][parseInt($("#CadrCount").text())].length; i++) {
                    $("#elements").append('<div><input type="text" onchange="setName(' + i + ',$(this).val());" value="' + spriteData[0][nanim][parseInt($("#CadrCount").text())][i][9] + '">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" onclick="DeleteElement(' + i + ');" value="удолить"><br><input type="button" onclick="EditMask(' + i + ');" value="Маска"><input type="button" onclick="EditPos(' + i + ');" value="Позиция"><input type="button" onclick="SloyUpDown(\'up\',' + i + ');" value="&#9650"><input type="button" onclick="SloyUpDown(\'down\',' + i + ');" value="&#9660">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</div><hr>');
                }
            }
            function setName(elem, name) {
                console.log(elem, name);
                spriteData[0][nanim][parseInt($("#CadrCount").text())][elem][9] = name;
            }
            function EditMask(EMaskid) {
                maskId = EMaskid;
                MaskAndPos.innerHTML = 'Маска ' + EMaskid + ' <hr>X:<input type="number" class="maskX" value = "' + spriteData[0][nanim][parseInt($("#CadrCount").text())][EMaskid][0] + '" onchange="alterNewMask(' + EMaskid + ');"><br>Y:<input type="number" class="maskY" value = "' + spriteData[0][nanim][parseInt($("#CadrCount").text())][EMaskid][1] + '" onchange="alterNewMask(' + EMaskid + ');"><br>Ширина:<input type="number" class="maskW" value = "' + spriteData[0][nanim][parseInt($("#CadrCount").text())][EMaskid][2] + '" onchange="alterNewMask(' + EMaskid + ');"><br>Высота:<input type="number" class="maskH" value = "' + spriteData[0][nanim][parseInt($("#CadrCount").text())][EMaskid][3] + '" onchange="alterNewMask(' + EMaskid + ');">';
            }
            function alterNewMask(EMaskid) {
                NewMask("x", EMaskid, $(".maskX").val());
                NewMask("y", EMaskid, $(".maskY").val());
                NewMask("w", EMaskid, $(".maskW").val());
                NewMask("h", EMaskid, $(".maskH").val());
            }
            function NewMask(NMM, EMaskid, MMid) {
                console.log("NewMask " + NMM + " : " + EMaskid + " : " + MMid);
                if (NMM === "x") {
                    spriteData[0][nanim][count][EMaskid][0] = MMid;
                }
                if (NMM === "y") {
                    spriteData[0][nanim][count][EMaskid][1] = MMid;
                }
                if (NMM === "w") {
                    spriteData[0][nanim][count][EMaskid][2] = MMid;
                    spriteData[0][nanim][count][EMaskid][6] = MMid;
                }
                if (NMM === "h") {
                    spriteData[0][nanim][count][EMaskid][3] = MMid;
                    spriteData[0][nanim][count][EMaskid][7] = MMid;
                }
            }

            function  NewPos(NPP, NPid) {
                console.log(NPP + " : " + NPid + " : " + count);
                if (NPP === "up") {
                    spriteData[0][nanim][count][NPid][5] -= shag; //если другой кадр не тронут,то копируем
                }

                if (NPP === "down") {
                    spriteData[0][nanim][count][NPid][5] += shag;
                }

                if (NPP === "left") {
                    spriteData[0][nanim][count][NPid][4] -= shag;
                }

                if (NPP === "right") {
                    spriteData[0][nanim][count][NPid][4] += shag;
                }
            }

            function EditPos(EPosid) {
                MaskAndPos.innerHTML = 'Позиция ' + spriteData[0][nanim][0][EPosid][9] + ' <hr> <input type="button" onclick=NewPos("up",' + EPosid + '); style = "width:40px; height:40px;" value="&#9650"><br><input type="button" onclick=NewPos("left",' + EPosid + '); style = "width:40px; height:40px;" value="&#9668"><input type="button" onclick=NewPos("down",' + EPosid + '); style = "width:40px; height:40px;" value="&#9660"><input type="button" onclick=NewPos("right",' + EPosid + '); style = "width:40px; height:40px;" value="&#9658"><br>Шаг: <input type="number" onchange="shag=parseInt($(this).val());" value="' + shag + '"><br>Угол поворота:<input type="number" value="' + spriteData[0][nanim][count][EPosid][8] + '" onchange="spriteData[0][' + nanim + '][' + count + '][' + EPosid + '][8]=Number(this.value);">';
            }





            //////////////////////////////////////////////////////Логика
            //////////////////////////////////////////////////////Логика
            //основной
            var Realcanvas = document.getElementById("canvas_1");
            var Realcontext = Realcanvas.getContext("2d");
            //основной2
            var Realcanvas2 = document.getElementById("canvas_2");
            var Realcontext2 = Realcanvas2.getContext("2d");
            //буффер0
            var canvas_0 = document.createElement("canvas");
            var buffcontext_0 = canvas_0.getContext("2d");
            //буффер1
            var canvas_1 = document.createElement("canvas");
            var buffcontext_1 = canvas_1.getContext("2d");
            //буфферам нужно размер задать
            Realcanvas.width = 450;
            Realcanvas.height = 275;
            canvas_0.width = Realcanvas.width;
            canvas_0.height = Realcanvas.height;
            canvas_1.width = Realcanvas.width;
            canvas_1.height = Realcanvas.height;
            //эти буфферы нужны для уберания мерцаний и правильной отрисовки и прочего
            var count = 0;/////кадры
            var nanim = 0; //////////тут анимации от 0 до 9
            var posx = -60;
            var posy = -40;
            var Pweapon = 0;///////// тут номер оружия

            var setkasize = 25;
            var spriteImage;
            var weaponData = [];
            var imageweapon;

            var PDress = 0;
            var PRasa = 2;
            var dressData = [];
            var imagedress;
            $.ajax({
                url: "weapon.json?<?php echo(microtime(true)); ?>",
                dataType: "json",
                success: function (data) {
                    weaponData = JSON.parse(JSON.stringify(data));
                    imageweapon = new Image();
                    imageweapon.src = weaponData.img;
                }
            });

            $.ajax({
                url: "dress.json?<?php echo(microtime(true)); ?>",
                dataType: "json",
                success: function (data) {
                    dressData = JSON.parse(JSON.stringify(data));
                    imagedress = new Image();
                    imagedress.src = dressData.img;
                }
            });

            function JsonStart() {
                spriteData = JSON.parse(jsonstring);
                Animations();
                OpenJsonCode();
                imgpersontxt.value = spriteData.img;
                imgbg.src = spriteData.img;
                spriteImage = new Image();
                spriteImage.src = spriteData.img;

                spriteImage.onload = function () {
                    Animation();

                };
            }
            function JsonReload(imgs) {
                Animations();
                OpenJsonCode();
                spriteData.img = imgs;
                imgbg.src = imgs;
                spriteImage.src = imgs;
                spriteImage.onload = function () {
                    Animation();
                    ishodnyicod.innerHTML = '';
                };
                spriteImage.onerror = function () {
                    ishodnyicod.innerHTML = '<h1 style="color:red;">Ошибка изображения</h1>';
                };
            }
            function Animation() {
                //это все рисуется в буффер 0
                try {
                    setInterval(function () {
                        //сброс канваса
                        canvas_0.width = canvas_0.width;
                        //переберем все координаты
                        for (var i = 0; i < spriteData[0][nanim][count].length; i++) {
                            buffcontext_0.save();
                            buffcontext_0.translate(
                                    Math.round(spriteData[0][nanim][count][i][4] + spriteData[0][nanim][count][i][6] / 2) + posx,
                                    Math.round(spriteData[0][nanim][count][i][5] + spriteData[0][nanim][count][i][7] / 2) + posy);
                            buffcontext_0.rotate(spriteData[0][nanim][count][i][8] * Math.PI / 180);
                            if (parseInt(spriteData[0][nanim][count][i][9]) !== -1) {
                                if (parseInt(spriteData[0][nanim][count][i][9]) > 99) {
                                    buffcontext_0.drawImage(imagedress,
                                            dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][0],
                                            dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][1],
                                            dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][2],
                                            dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][3],
                                            Math.round( - dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][2] / 2),
                                            Math.round( - dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][3] / 2),
                                            dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][2],
                                            dressData[parseInt(spriteData[0][nanim][count][i][9])][PRasa][PDress][3]
                                            );
                                /*} else if (parseInt(spriteData[0][nanim][count][i][9]) === 101) {
                                    buffcontext_0.drawImage(imagedress,
                                            dressData["101"][PRasa][PDress][0],
                                            dressData["101"][PRasa][PDress][1],
                                            dressData["101"][PRasa][PDress][2],
                                            dressData["101"][PRasa][PDress][3],
                                            Math.round( - dressData["101"][PRasa][PDress][2] / 2),
                                            Math.round( - dressData["101"][PRasa][PDress][3] / 2),
                                            dressData["101"][PRasa][PDress][2],
                                            dressData["101"][PRasa][PDress][3]
                                            );
                                } else if (parseInt(spriteData[0][nanim][count][i][9]) === 102) {
                                    buffcontext_0.drawImage(imagedress,
                                            dressData["102"][PRasa][PDress][0],
                                            dressData["102"][PRasa][PDress][1],
                                            dressData["102"][PRasa][PDress][2],
                                            dressData["102"][PRasa][PDress][3],
                                            Math.round( - dressData["102"][PRasa][PDress][2] / 2),
                                            Math.round( - dressData["102"][PRasa][PDress][3] / 2),
                                            dressData["102"][PRasa][PDress][2],
                                            dressData["102"][PRasa][PDress][3]
                                            );*/
                                } else {
                                    buffcontext_0.drawImage(
                                            spriteImage,
                                            spriteData[0][nanim][count][i][0],
                                            spriteData[0][nanim][count][i][1],
                                            spriteData[0][nanim][count][i][2],
                                            spriteData[0][nanim][count][i][3],
                                            Math.round(-spriteData[0][nanim][count][i][6] / 2),
                                            Math.round(-spriteData[0][nanim][count][i][7] / 2),
                                            spriteData[0][nanim][count][i][6],
                                            spriteData[0][nanim][count][i][7]
                                            );
                                }
                            } else {
                                buffcontext_0.drawImage(imageweapon,
                                        weaponData.imgC[Pweapon][0],
                                        weaponData.imgC[Pweapon][1],
                                        weaponData.imgC[Pweapon][2],
                                        weaponData.imgC[Pweapon][3],
                                        Math.round(-weaponData.imgC[Pweapon][2] / 2),
                                        Math.round(-weaponData.imgC[Pweapon][3] / 2),
                                        weaponData.imgC[Pweapon][2],
                                        weaponData.imgC[Pweapon][3]
                                        );
                            }
                            buffcontext_0.restore();
                        }
                        if (cadr === "start") {
                            $("#CadrCount").text(count);
                            if (count >= spriteData[0][nanim].length - 1) {
                                count = 0;
                            } else {
                                redrawElements();
                                count++;
                            }
                        }
                    }, 300);
                } catch (e) {
                    console.log(e);
                }

            }
            //а здесь вывод всего в видимый канвас
            setInterval(function () {
                //сброс канваса
                Realcanvas.width = Realcanvas.width;
                Realcontext.drawImage(canvas_1, 0, 0, canvas_1.width, canvas_1.height);
                Realcontext.drawImage(canvas_0, 0, 0, canvas_0.width, canvas_0.height);
            }, 550 / 30);//30fps


            //setka
            setInterval(function () {
                //сброс канваса
                canvas_1.width = canvas_1.width;
                for (var x = 0; x < canvas_1.height; x += setkasize) {
                    buffcontext_1.lineWidth = "1";
                    buffcontext_1.strokeStyle = "rgba(0,255,0,1)";
                    buffcontext_1.moveTo(0, x);
                    buffcontext_1.lineTo(canvas_1.width, x);
                }
                for (var y = 0; y < canvas_1.width; y += setkasize) {
                    buffcontext_1.moveTo(y, canvas_1.height);
                    buffcontext_1.lineTo(y, 0);
                }
                buffcontext_1.stroke();
            }, 200);

            //sprite draw
            setInterval(function () {
                //сброс канваса
                try {
                    Realcanvas2.width = spriteImage.width;
                    Realcanvas2.height = spriteImage.height;
                    Realcontext2.drawImage(spriteImage, 0, 0, Realcanvas2.width, Realcanvas2.height);
                    //class="maskX"
                    Realcontext2.lineWidth = "2";
                    Realcontext2.strokeStyle = "rgba(0,255,0,1)";
                    Realcontext2.rect($(".maskX").val(), $(".maskY").val(), $(".maskW").val(), $(".maskH").val());
                    Realcontext2.stroke();
                } catch (e) {

                }
            }, 200);

        </script>
    </body>
</html>
