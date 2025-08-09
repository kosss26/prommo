MyLib.htmlMainBattle = $(document.getElementById("tmpBattle"));
MyLib.htmlButtonsBattle = $(document.getElementById("button_visible"));
MyLib.cnv = {};
MyLib.cnv.w = 480;
MyLib.cnv.h = 328;

MyLib.bttl.realBattleEntity = $(document.getElementById("sfyEntity")).get(0);
MyLib.bttl.ctxRBE = MyLib.bttl.realBattleEntity.getContext("2d");
MyLib.bttl.ctxRBE.webkitImageSmoothingEnabled = !1;
MyLib.bttl.ctxRBE.msImageSmoothingEnabled = !1;
MyLib.bttl.ctxRBE.imageSmoothingEnabled = !1;

MyLib.bttl.heroBuffer = document.createElement("canvas");
MyLib.bttl.ctxheroBuffer = MyLib.bttl.heroBuffer.getContext("2d");
MyLib.bttl.heroBuffer.width = MyLib.cnv.w;
MyLib.bttl.heroBuffer.height = MyLib.cnv.h;
MyLib.bttl.ctxheroBuffer.webkitImageSmoothingEnabled = !1;
MyLib.bttl.ctxheroBuffer.msImageSmoothingEnabled = !1;
MyLib.bttl.ctxheroBuffer.imageSmoothingEnabled = !1;


MyLib.bttl.mobBuffer = document.createElement("canvas");
MyLib.bttl.ctxmobBuffer = MyLib.bttl.mobBuffer.getContext("2d");
MyLib.bttl.mobBuffer.width = MyLib.cnv.w;
MyLib.bttl.mobBuffer.height = MyLib.cnv.h;
MyLib.bttl.ctxmobBuffer.webkitImageSmoothingEnabled = !1;
MyLib.bttl.ctxmobBuffer.msImageSmoothingEnabled = !1;
MyLib.bttl.ctxmobBuffer.imageSmoothingEnabled = !1;


MyLib.bttl.buffBattleEntity = document.createElement("canvas");
MyLib.bttl.ctxBattleEntity = MyLib.bttl.buffBattleEntity.getContext("2d");
MyLib.bttl.buffBattleEntity.width = MyLib.cnv.w;
MyLib.bttl.buffBattleEntity.height = MyLib.cnv.h;
MyLib.bttl.ctxBattleEntity.webkitImageSmoothingEnabled = !1;
MyLib.bttl.ctxBattleEntity.msImageSmoothingEnabled = !1;
MyLib.bttl.ctxBattleEntity.imageSmoothingEnabled = !1;


MyLib.bttl.animationtime = 150;
MyLib.bttl.speedFlyEntity = 5;
MyLib.bttl.entytyspeedcount = 20;
MyLib.bttl.posPWidhtEntity = 200;
MyLib.bttl.posMWidhtEntity = 340;
MyLib.bttl.posHegthEntity = 220;

MyLib.bttl.speedInOut = 650;
MyLib.bttl.arrObjCanv = {};
MyLib.bttl.jsonCounter = 0;

MyLib.bttl.ii = 0;
battleJsonLoad = function () {
    MyLib.bttl.imageIcodata = [];
    MyLib.bttl.IcoSprite;
    $.ajax({
        url: "./json/icons/icons.json?136.91111", dataType: "json", success: function (e) {
            MyLib.bttl.imageIcodata = e;
            MyLib.bttl.IcoSprite = new Image;
            MyLib.bttl.IcoSprite.onload = function () {
                MyLib.bttl.jsonCounter++;
            };
            MyLib.bttl.IcoSprite.src = MyLib.bttl.imageIcodata.img[0];
        }
    });
    MyLib.bttl.weaponData = [];
    MyLib.bttl.imageweapon;
    $.ajax({
        url: "./json/weapon/weapon_new.json?139.1114", dataType: "json", success: function (a) {
            MyLib.bttl.weaponData = JSON.parse(JSON.stringify(a));
            MyLib.bttl.imageweapon = new Image;
            MyLib.bttl.imageweapon.onload = function () {
                MyLib.bttl.jsonCounter++;
            };
            MyLib.bttl.imageweapon.src = MyLib.bttl.weaponData.img;
        }
    });
    MyLib.bttl.dressData = [];
    MyLib.bttl.imagedress;
    $.ajax({
        url: "./json/dress/dress.json?129", dataType: "json", success: function (data) {
            MyLib.bttl.dressData = JSON.parse(JSON.stringify(data));
            MyLib.bttl.imagedress = new Image();
            MyLib.bttl.imagedress.onload = function () {
                MyLib.bttl.jsonCounter++;
            };
            MyLib.bttl.imagedress.src = MyLib.bttl.dressData.img;
        }
    });

    MyLib.bttl.tempspriteData = [];
    MyLib.bttl.spriteData = [];
    MyLib.bttl.tempspriteImage = [];
    MyLib.bttl.spriteImage = [];
    $.ajax({
        url: "./json/Player/animation.json?129", dataType: "json", success: function (a) {
            MyLib.bttl.tempspriteData = JSON.parse(JSON.stringify(a));
            MyLib.bttl.spriteData = JSON.parse(JSON.stringify(a));
            MyLib.bttl.img1counter = 0;
            MyLib.bttl.img1counterend = MyLib.bttl.spriteData.img.length * 2;
            for (a = 0; a < MyLib.bttl.spriteData.img.length; a++) {
                var thistmpval = a;
                MyLib.bttl.tempspriteImage[thistmpval] = new Image;
                MyLib.bttl.tempspriteImage[thistmpval].onload = function () {
                    MyLib.bttl.img1counter++;
                };
                MyLib.bttl.tempspriteImage[thistmpval].src = MyLib.bttl.tempspriteData.img[thistmpval];

                MyLib.bttl.spriteImage[thistmpval] = new Image;
                MyLib.bttl.spriteImage[thistmpval].onload = function () {
                    MyLib.bttl.img1counter++;
                };
                MyLib.bttl.spriteImage[thistmpval].src = MyLib.bttl.spriteData.img[thistmpval];
            }
            var interval1 = setInterval(function () {
                if (MyLib.bttl.img1counter === MyLib.bttl.img1counterend) {
                    MyLib.bttl.jsonCounter++;
                    clearInterval(interval1);
                }
            }, 100);
        }
    });
    MyLib.bttl.tempspriteDataMob = [];
    MyLib.bttl.spriteDataMob = [];
    MyLib.bttl.tempspriteImageMob = [];
    MyLib.bttl.spriteImageMob = [];
    $.ajax({
        url: "./json/Mob/animation.json?136.91", dataType: "json", success: function (a) {
            MyLib.bttl.spriteDataMob = JSON.parse(JSON.stringify(a));
            MyLib.bttl.newJson = {};
            for (a = 1; a <= MyLib.bttl.spriteDataMob.AnimCount; a++) {
                MyLib.bttl.newJson[a] = MyLib.bttl.spriteDataMob[MyLib.bttl.spriteDataMob.keyToAnim[a]];
            }
            MyLib.bttl.newJson.img = MyLib.bttl.spriteDataMob.img;
            MyLib.bttl.spriteDataMob = MyLib.bttl.newJson;
            MyLib.bttl.tempspriteDataMob = MyLib.bttl.newJson;

            MyLib.bttl.img2counter = 0;
            MyLib.bttl.img2counterend = MyLib.bttl.spriteDataMob.img.length * 2;
            for (a = 1; a < MyLib.bttl.spriteDataMob.img.length + 1; a++) {
                var thistmpval = a;
                MyLib.bttl.tempspriteImageMob[thistmpval] = new Image;
                MyLib.bttl.tempspriteImageMob[thistmpval].onload = function () {
                    MyLib.bttl.img2counter++;
                };
                MyLib.bttl.tempspriteImageMob[thistmpval].src = MyLib.bttl.tempspriteDataMob.img[thistmpval - 1];

                MyLib.bttl.spriteImageMob[thistmpval] = new Image;
                MyLib.bttl.spriteImageMob[thistmpval].onload = function () {
                    MyLib.bttl.img2counter++;
                };
                MyLib.bttl.spriteImageMob[thistmpval].src = MyLib.bttl.spriteDataMob.img[thistmpval - 1];
            }
            var interval2 = setInterval(function () {
                if (MyLib.bttl.img2counter === MyLib.bttl.img2counterend) {
                    MyLib.bttl.jsonCounter++;
                    clearInterval(interval2);
                }
            }, 100);
        }
    });

};
battleJsonLoad();
MyLib.bttl.icoStatC = {
    0: 12, 1: 13, 2: 14, 3: 15, 4: 16, 5: 17, 6: 18, 7: 19, 8: 20, 9: 21, 10: 22
};
MyLib.bttl.icofontcount = {
    0: 48, 1: 49, 2: 50, 3: 51, 4: 52, 5: 53, 6: 54, 7: 55, 8: 56, 9: 57, "+": 58, "-": 59
};
mcb = function (elem) {
    $(elem).fadeTo(300, 0.5, function () {
        $(elem).fadeTo(0, 1);
    });
};

//отталкиваясь от высоты задать ширину но если ширина меньше то задать ширину а если больше 480 то 480
resizeBattle = function () {
    var wWidth = $(window).width();
    var wHeight = $(window).height() - $(".timefooter").height();
    var maxWidth = 480 * (wHeight / 730);
    var resultW = 0;
    if (maxWidth > wWidth) {
        resultW = wWidth;
    } else {
        resultW = maxWidth;
    }
    $(".null_480_666").width(resultW);
    $(".btfs").css({fontSize: resultW / 20 + "px"});
    $(".number_pos").css({lineHeight: (resultW / 8) + "px"});
    $(".label_name_l,.label_name_r").width($(".label_name_c").height() / 2);
};
setName = function (id, text) {
    $(document.getElementById(id)).html(text);
    $(".label_name_l,.label_name_r").width($(".label_name_c").height() / 2);
};
resizeBattle();
addSlice = function (toIdName, ClassName, name, dataURL) {
    MyLib.bttl.arrObjCanv[name] = {};
//записываем этот элемент в массив
    MyLib.bttl.arrObjCanv[name].img = new Image();
    MyLib.bttl.arrObjCanv[name].img.src = dataURL;
//добавляем класс идентификатор этого кадра
    MyLib.bttl.arrObjCanv[name].img.id = name;
    MyLib.bttl.arrObjCanv[name].img.classList.add(ClassName);
//название управляющего класса 
    MyLib.bttl.arrObjCanv[name].ClassName = ClassName;
//название контейнера класса 
    MyLib.bttl.arrObjCanv[name].toIdName = toIdName;
//создаем элемент на странице
    $(document.getElementById(toIdName)).append(MyLib.bttl.arrObjCanv[name].img);
    showSlice(name);
};
showSlice = function (name) {
    $("." + MyLib.bttl.arrObjCanv[name].ClassName).css({visibility: "collapse"});
    if ($(document.getElementById(name)).length > 0) {
        $(document.getElementById(name)).css({visibility: "visible"});
    } else {
        $(document.getElementById(MyLib.bttl.arrObjCanv[name].toIdName)).append(MyLib.bttl.arrObjCanv[name].img);
        $(document.getElementById(name)).css({visibility: "visible"});
    }
};
numToImgNum = function (n) {
    n += "";
    var output = '';
    var images = [
        'n0.png', 'n1.png', 'n2.png',
        'n3.png', 'n4.png', 'n5.png',
        'n6.png', 'n7.png', 'n8.png',
        'n9.png'
    ];
    for (var i = 0; i < n.length; i++) {
        output += '<img src="img/number/' + images[n[i]] + '" alt="' + n[i] + '" style="display: block;float: left;height: 100%;">';
    }
    return output;
};
addSprite = function (e, f, g, h) {
    try {
        e.drawImage(MyLib.bttl.IcoSprite, MyLib.bttl.imageIcodata.imgC[0][f], MyLib.bttl.imageIcodata.imgC[1][f], MyLib.bttl.imageIcodata.imgC[2][f], MyLib.bttl.imageIcodata.imgC[3][f], g, h, MyLib.bttl.imageIcodata.imgC[2][f], MyLib.bttl.imageIcodata.imgC[3][f]);
    } catch (e) {

    }
};
battleLoad = function () {
    if (MyLib.bttl.jsonCounter === 5) {
        MyLib.battleIntervalTimer.forEach(clearInterval);
        MyLib.battleSetTimeid.forEach(clearTimeout);
        BattlePlayer();
        BattleMob();
        readBattleInfo();
    } else {
        MyLib.battleSetTimeid[9004] = setTimeout(function () {
            battleLoad();
        }, 200);
    }
};

BattlePlayer = function () {
    MyLib.battleIntervalTimer.push(setInterval(function () {
        try {
//если герой видим
            if (1 === MyLib.bttl.Pvisible) {
//синхронизация 
                if (MyLib.bttl.Panimationcount === 2 && MyLib.bttl.Panimation > 1 && MyLib.bttl.Panimation < 6 && MyLib.bttl.tmpMobanim > 5) {
                    MyLib.bttl.Mlife = MyLib.bttl.tempMlife;
                    $(document.getElementById("HeroLifeR")).html(numToImgNum(MyLib.bttl.Mlife));
                    MyLib.bttl.setmobanim = 1;
                    MyLib.bttl.Manimationcount = 99;
                } else if (MyLib.bttl.Panimationcount === 2 && MyLib.bttl.tmpEntityM.length > 0) {
                    MyLib.bttl.Mlife = MyLib.bttl.tempMlife;
                    $(document.getElementById("HeroLifeR")).html(numToImgNum(MyLib.bttl.Mlife));
                    MyLib.bttl.arrEntityM = MyLib.bttl.arrEntityM.concat(MyLib.bttl.tmpEntityM);
                    drawEntity();
                    MyLib.bttl.tmpEntityM = [];
                } else if (MyLib.bttl.Panimationcount === 2 && MyLib.bttl.Mlife !== MyLib.bttl.tempMlife && MyLib.bttl.tmpEntityM.length === 0) {
                    MyLib.bttl.Mlife = MyLib.bttl.tempMlife;
                    $(document.getElementById("HeroLifeR")).html(numToImgNum(MyLib.bttl.Mlife));
                }
//если номер кадров равен длине массива кадров
                if (MyLib.bttl.Panimationcount >= MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation].length) {
//зануление номера кадра
                    MyLib.bttl.Panimationcount = 0;
//после моба 
                    if (MyLib.bttl.setPanim === 1) {
                        MyLib.bttl.setPanim = 0;
//игрок пригнул
                        if (MyLib.bttl.tmpPanim === 6) {
//приглнул
                            MyLib.bttl.Panimation = 6;
//здох
                        } else if (MyLib.bttl.tmpPanim === 7) {
//дадим по башке сначала
                            MyLib.bttl.arrEntityP = MyLib.bttl.arrEntityP.concat(MyLib.bttl.tmpEntityP);
                            drawEntity();
                            MyLib.bttl.tmpEntityP = [];
                            MyLib.bttl.Panimation = 9;
                        } else {
//или просто дадим по башке
                            MyLib.bttl.arrEntityP = MyLib.bttl.arrEntityP.concat(MyLib.bttl.tmpEntityP);
                            drawEntity();
                            MyLib.bttl.tmpEntityP = [];
                            MyLib.bttl.Panimation = 9;
                        }
                        MyLib.battleSetTimeid[9000] = setTimeout(function () {
//запомним значение анимации для нового потока таймера во временную переменную чтоб не перезаписалась вот
                            MyLib.bttl.tempzP = MyLib.bttl.tmpPanim;
//сбросим переменную
                            MyLib.bttl.tmpPanim = MyLib.bttl.Pshield;
//огулиш
                            if (MyLib.bttl.tempzP === 8) {
//да оглушил
                                MyLib.bttl.Panimation = 8;
//сбросим переменную
                                MyLib.bttl.tmpPanim = MyLib.bttl.Pshield;
//дал по башке
                            }
                            if (MyLib.bttl.tempzP === 9) {
//сбросим анимацию на начальную
                                MyLib.bttl.Panimation = MyLib.bttl.Pshield;
                                MyLib.bttl.tmpPanim = MyLib.bttl.Pshield;
//убил
                            } else if (MyLib.bttl.tempzP === 7) {
//да убил
                                MyLib.bttl.Panimation = 7;
                                MyLib.bttl.tmpPanim = MyLib.bttl.Pshield;
                            }
//через 300 миллисекунд
                        }, 300);
                    } else if (7 > MyLib.bttl.Panimation || 8 < MyLib.bttl.Panimation) {
//если номер анимации меньше 7 или больше 8 то анимация первичная в щите или без
                        MyLib.bttl.Panimation = MyLib.bttl.Pshield;
                    }
                }
//если количество щитов 0 то отключить щит 
                if (0 >= MyLib.bttl.PshieldNC) {
                    MyLib.bttl.PshieldNC = 0;
                    MyLib.bttl.Pshield = 0;
                }

                if (typeof MyLib.bttl.arrObjCanv["frame" + MyLib.bttl.Ptype + MyLib.bttl.Pico + MyLib.bttl.Pweapon + MyLib.bttl.PDress + MyLib.bttl.Panimation + MyLib.bttl.Panimationcount] === "undefined") {
//сброс канвы
                    MyLib.bttl.heroBuffer.width = MyLib.cnv.w;
                    MyLib.bttl.heroBuffer.height = MyLib.cnv.h;
                    for (MyLib.bttl.a01 = 0; MyLib.bttl.a01 < MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount].length; MyLib.bttl.a01++) {
                        MyLib.bttl.typeP = parseInt(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][9]);
                        MyLib.bttl.typeStrP = MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][9];
                        if (MyLib.bttl.typeP === -1) {
                            MyLib.bttl.ctxheroBuffer.save();
                            MyLib.bttl.ctxheroBuffer.translate(
                                    Math.round(
                                            MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][4] +
                                            MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][6] / 2
                                            ),
                                    Math.round(
                                            MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][5] +
                                            MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][7] / 2
                                            )
                                    );
                            MyLib.bttl.ctxheroBuffer.rotate(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][8] * Math.PI / 180);
                            MyLib.bttl.ctxheroBuffer.drawImage(
                                    MyLib.bttl.imageweapon,
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][0],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][1],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][2],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][3],
                                    Math.round(-MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][6] / 2) - MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][4],
                                    -MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][5],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][2],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Pweapon][3]
                                    );
                            MyLib.bttl.ctxheroBuffer.restore();
                        } else if (MyLib.bttl.typeP > 99) {
                            MyLib.bttl.typeP = MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][9];
                            MyLib.bttl.ctxheroBuffer.save();
                            MyLib.bttl.ctxheroBuffer.translate(Math.round(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][4] + MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][6] / 2), Math.round(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][5] + MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][7] / 2));
                            MyLib.bttl.ctxheroBuffer.rotate(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][8] * Math.PI / 180);
                            MyLib.bttl.ctxheroBuffer.drawImage(MyLib.bttl.imagedress, MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][0], MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][1], MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][2], MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][3], Math.round(-MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][2] / 2), Math.round(-MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][3] / 2), MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][2], MyLib.bttl.dressData[MyLib.bttl.typeStrP][MyLib.bttl.Pico][MyLib.bttl.PDress][3]
                                    );
                            MyLib.bttl.ctxheroBuffer.restore();
                        } else {
                            MyLib.bttl.ctxheroBuffer.save();
                            MyLib.bttl.ctxheroBuffer.translate(Math.round(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][4] + MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][6] / 2), Math.round(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][5] + MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][7] / 2));
                            MyLib.bttl.ctxheroBuffer.rotate(MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][8] * Math.PI / 180);
                            MyLib.bttl.ctxheroBuffer.drawImage(MyLib.bttl.spriteImage[MyLib.bttl.Pico], MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][0], MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][1], MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][2], MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][3], Math.round(-MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][6] / 2), Math.round(-MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][7] / 2), MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][6], MyLib.bttl.spriteData[MyLib.bttl.Pico][MyLib.bttl.Panimation][MyLib.bttl.Panimationcount][MyLib.bttl.a01][7]);
                            MyLib.bttl.ctxheroBuffer.restore();
                        }

                    }
                    addSlice("layer1", "layer1_1", "frame" + MyLib.bttl.Ptype + MyLib.bttl.Pico + MyLib.bttl.Pweapon + MyLib.bttl.PDress + MyLib.bttl.Panimation + MyLib.bttl.Panimationcount, MyLib.bttl.heroBuffer.toDataURL("image/png"));
                } else {
                    showSlice("frame" + MyLib.bttl.Ptype + MyLib.bttl.Pico + MyLib.bttl.Pweapon + MyLib.bttl.PDress + MyLib.bttl.Panimation + MyLib.bttl.Panimationcount);
                }
                MyLib.bttl.Panimationcount++;
            }
        } catch (e) {

        }
    }, MyLib.bttl.animationtime));
};

BattleMob = function () {
    MyLib.battleIntervalTimer.push(setInterval(function () {
        try {
            if (1 === MyLib.bttl.Mvisible) {
//синхронизация 
                if (MyLib.bttl.Manimationcount === 2 && MyLib.bttl.Manimation > 1 && MyLib.bttl.Manimation < 6 && MyLib.bttl.tmpPanim > 5) {
                    MyLib.bttl.Plife = MyLib.bttl.tempPlife;
                    $(document.getElementById("HeroLifeL")).html(numToImgNum(MyLib.bttl.Plife));
                    MyLib.bttl.setPanim = 1;
                    MyLib.bttl.Panimationcount = 99;
                } else if (MyLib.bttl.Manimationcount === 2 && MyLib.bttl.tmpEntityP.length > 0) {
                    MyLib.bttl.Plife = MyLib.bttl.tempPlife;
                    $(document.getElementById("HeroLifeL")).html(numToImgNum(MyLib.bttl.Plife));
                    MyLib.bttl.arrEntityP = MyLib.bttl.arrEntityP.concat(MyLib.bttl.tmpEntityP);
                    drawEntity();
                    MyLib.bttl.tmpEntityP = [];
                } else if (MyLib.bttl.Manimationcount === 2 && MyLib.bttl.Plife !== MyLib.bttl.tempPlife && MyLib.bttl.tmpEntityP.length === 0) {
                    MyLib.bttl.Plife = MyLib.bttl.tempPlife;
                    $(document.getElementById("HeroLifeL")).html(numToImgNum(MyLib.bttl.Plife));
                }
                if (MyLib.bttl.Manimationcount >= MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation].length) {
                    MyLib.bttl.Manimationcount = 0;
                    if (MyLib.bttl.setmobanim === 1) {
                        MyLib.bttl.setmobanim = 0;
                        if (MyLib.bttl.tmpMobanim === 6) {
                            MyLib.bttl.Manimation = 6;
                        } else if (MyLib.bttl.tmpMobanim === 7) {
                            MyLib.bttl.arrEntityM = MyLib.bttl.arrEntityM.concat(MyLib.bttl.tmpEntityM);
                            drawEntity();
                            MyLib.bttl.tmpEntityM = [];
                            MyLib.bttl.Manimation = 9;
                        } else {
                            MyLib.bttl.arrEntityM = MyLib.bttl.arrEntityM.concat(MyLib.bttl.tmpEntityM);
                            drawEntity();
                            MyLib.bttl.tmpEntityM = [];
                            MyLib.bttl.Manimation = 9;
                        }

                        MyLib.battleSetTimeid[9001] = setTimeout(function () {
                            MyLib.bttl.tempzM = MyLib.bttl.tmpMobanim;
                            MyLib.bttl.tmpMobanim = MyLib.bttl.Mshield;
                            if (MyLib.bttl.tempzM === 8) {
                                MyLib.bttl.Manimation = 8;
                                MyLib.bttl.tmpMobanim = MyLib.bttl.Mshield;
                            } else if (MyLib.bttl.tempzM === 9) {
                                MyLib.bttl.Manimation = MyLib.bttl.Mshield;
                                MyLib.bttl.tmpMobanim = MyLib.bttl.Mshield;
                            } else if (MyLib.bttl.tempzM === 7) {
                                MyLib.bttl.Manimation = 7;
                                MyLib.bttl.tmpMobanim = MyLib.bttl.Mshield;
                            }
                        }, 300);
                    } else if (7 > MyLib.bttl.Manimation || 8 < MyLib.bttl.Manimation) {
                        MyLib.bttl.Manimation = MyLib.bttl.Mshield;
                    }
                }
                if (typeof MyLib.bttl.arrObjCanv["frame" + MyLib.bttl.Mtype + MyLib.bttl.Mico + MyLib.bttl.Mweapon + MyLib.bttl.MDress + MyLib.bttl.Manimation + MyLib.bttl.Manimationcount] === "undefined") {
                    MyLib.bttl.mobBuffer.width = MyLib.cnv.w;
                    MyLib.bttl.mobBuffer.height = MyLib.cnv.h;
                    MyLib.bttl.ctxmobBuffer.translate(MyLib.cnv.w, 0);
                    MyLib.bttl.ctxmobBuffer.scale(-1, 1);
                    for (MyLib.bttl.a02 = 0; MyLib.bttl.a02 < MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount].length; MyLib.bttl.a02++) {
                        MyLib.bttl.typeM = parseInt(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][9]);
                        MyLib.bttl.typeStrM = MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][9];
                        if (MyLib.bttl.typeM === -1) {
                            MyLib.bttl.ctxmobBuffer.save();
                            MyLib.bttl.ctxmobBuffer.translate(
                                    Math.round(
                                            MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][4] +
                                            MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][6] / 2
                                            ),
                                    Math.round(
                                            MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][5] +
                                            MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][7] / 2
                                            )
                                    );
                            MyLib.bttl.ctxmobBuffer.rotate(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][8] * Math.PI / 180);
                            MyLib.bttl.ctxmobBuffer.drawImage(
                                    MyLib.bttl.imageweapon,
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][0],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][1],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][2],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][3],
                                    Math.round(-MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][6] / 2)-MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][4],
                                    -MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][5],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][2],
                                    MyLib.bttl.weaponData.imgC[MyLib.bttl.Mweapon][3]
                                    );
                            MyLib.bttl.ctxmobBuffer.restore();
                        } else if (MyLib.bttl.typeM > 99) {
                            MyLib.bttl.typeM = MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][9];
                            MyLib.bttl.ctxmobBuffer.save();
                            MyLib.bttl.ctxmobBuffer.translate(Math.round(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][4] + MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][6] / 2), Math.round(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][5] + MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][7] / 2));
                            MyLib.bttl.ctxmobBuffer.rotate(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][8] * Math.PI / 180);
                            MyLib.bttl.ctxmobBuffer.drawImage(MyLib.bttl.imagedress, MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][0], MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][1], MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][2], MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][3], Math.round(-MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][2] / 2), Math.round(-MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][3] / 2), MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][2], MyLib.bttl.dressData[MyLib.bttl.typeStrM][MyLib.bttl.Mico][MyLib.bttl.MDress][3]
                                    );
                            MyLib.bttl.ctxmobBuffer.restore();
                        } else {
                            MyLib.bttl.ctxmobBuffer.save();
                            MyLib.bttl.ctxmobBuffer.translate(Math.round(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][4] + MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][6] / 2), Math.round(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][5] + MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][7] / 2));
                            MyLib.bttl.ctxmobBuffer.rotate(MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][8] * Math.PI / 180);
                            MyLib.bttl.ctxmobBuffer.drawImage(MyLib.bttl.spriteImageMob[MyLib.bttl.Mico], MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][0], MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][1], MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][2], MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][3], Math.round(-MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][6] / 2), Math.round(-MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][7] / 2), MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][6], MyLib.bttl.spriteDataMob[MyLib.bttl.Mico][MyLib.bttl.Manimation][MyLib.bttl.Manimationcount][MyLib.bttl.a02][7]);
                            MyLib.bttl.ctxmobBuffer.restore();
                        }
                    }
                    addSlice("layer2", "layer2_1", "frame" + MyLib.bttl.Mtype + MyLib.bttl.Mico + MyLib.bttl.Mweapon + MyLib.bttl.MDress + MyLib.bttl.Manimation + MyLib.bttl.Manimationcount, MyLib.bttl.mobBuffer.toDataURL("image/png"));
                } else {
                    showSlice("frame" + MyLib.bttl.Mtype + MyLib.bttl.Mico + MyLib.bttl.Mweapon + MyLib.bttl.MDress + MyLib.bttl.Manimation + MyLib.bttl.Manimationcount);
                }
                MyLib.bttl.Manimationcount++;
            }
        } catch (e) {

        }
    }, MyLib.bttl.animationtime + 5));
};

restartAnimation = function (c, a) {
    if (c !== "") {
        if (c > 5) {
            MyLib.bttl.tmpPanim = c;
            if (c === 7 && a === 0) {
                MyLib.bttl.Panimation = c;
            }
        } else {
            MyLib.bttl.Panimationcount = 0;
            MyLib.bttl.Panimation = c;
        }
    }
    if (a !== "") {
        if (a > 5) {
            MyLib.bttl.tmpMobanim = a;
            if (c === 0 && a === 7) {
                MyLib.bttl.Manimation = a;
            }
        } else {
            MyLib.bttl.Manimationcount = 0;
            MyLib.bttl.Manimation = a;
        }
    }
};

mobout = function (a) {
    $(document.getElementById("layer2")).animate({right: MyLib.bttl.MposX + "%"}, MyLib.bttl.speedInOut, "linear", function () {
//если 1 то моб
        if (a.Ptype === 1) {
            MyLib.bttl.Ptype = "M";
            MyLib.bttl.spriteData = MyLib.bttl.tempspriteDataMob;
            MyLib.bttl.spriteImage = MyLib.bttl.tempspriteImageMob;
        } else if (a.Ptype === 0) {
            MyLib.bttl.Ptype = "P";
            MyLib.bttl.spriteData = MyLib.bttl.tempspriteData;
            MyLib.bttl.spriteImage = MyLib.bttl.tempspriteImage;
        }
        if (a.Mtype === 1) {
            MyLib.bttl.Mtype = "M";
            MyLib.bttl.spriteDataMob = MyLib.bttl.tempspriteDataMob;
            MyLib.bttl.spriteImageMob = MyLib.bttl.tempspriteImageMob;
        } else if (a.Mtype === 0) {
            MyLib.bttl.Mtype = "P";
            MyLib.bttl.spriteDataMob = MyLib.bttl.tempspriteData;
            MyLib.bttl.spriteImageMob = MyLib.bttl.tempspriteImage;
        }
        if (a.Pico !== "") {
            MyLib.bttl.Pico = a.Pico;
        }
        if (a.Pweapon !== "") {
            MyLib.bttl.Pweapon = a.Pweapon;
        }
        if (a.Pvisible !== "") {
            MyLib.bttl.Pvisible = a.Pvisible;
        }
        if (a.Mico !== "") {
            MyLib.bttl.Mico = a.Mico;
        }
        if (a.Mweapon !== "") {
            MyLib.bttl.Mweapon = a.Mweapon;
        }
        if (a.Mvisible !== "") {
            MyLib.bttl.Mvisible = a.Mvisible;
        }

        MyLib.bttl.Mobname = a.Mname;
        MyLib.bttl.Mlife = a.Mlife;
        MyLib.bttl.tempMlife = a.Mlife;
        MyLib.bttl.Pshield = 0;
        MyLib.bttl.Panimationcount = 0;
        MyLib.bttl.Mshield = 0;
        MyLib.bttl.Manimationcount = 0;
        MyLib.bttl.tmpMobanim = 0;
        MyLib.bttl.tmpPanim = 0;
        MyLib.bttl.Manimation = 0;
        $(document.getElementById("HeroLifeR")).html(numToImgNum(""));
        setName("name2", "");
        mobin(a);
        if (MyLib.bttl.Pload === 0) {
            MyLib.bttl.Pload = 1;
            Playerin(a);
        }
        if (MyLib.bttl.BattleResult === 1) {
            MyLib.bttl.end = 1;
            MyLib.footName = "huntresult";
            showContent("/hunt/result.php");
        }
    });
};

Playerin = function (a) {
    $(document.getElementById("layer1")).css({left: MyLib.bttl.PposX + "%"});
    $(document.getElementById("layer1")).animate({left: MyLib.bttl.PposL + "%"}, MyLib.bttl.speedInOut, "linear", function () {
        $(document.getElementById("HeroLifeL")).html(numToImgNum(MyLib.bttl.Plife));
        setName("name1", MyLib.bttl.Playername);
    });
};

mobin = function (a) {
    if (MyLib.bttl.lost_mob_id !== -1) {
        $(document.getElementById("layer2")).animate({right: MyLib.bttl.MposR + "%"}, MyLib.bttl.speedInOut, "linear", function () {
            MyLib.bttl.movemob = 0;
            if (a.Pname !== "") {
                MyLib.bttl.Playername = a.Pname;
            }
            if (a.Mname !== "") {
                MyLib.bttl.Mobname = a.Mname;
                setName("name2", MyLib.bttl.Mobname);
            }
            if (a.Plife !== "") {
                MyLib.bttl.tempPlife = a.Plife;
            }
            if (a.Mlife !== "") {
                MyLib.bttl.tempMlife = a.Mlife;
                $(document.getElementById("HeroLifeR")).html(numToImgNum(MyLib.bttl.tempMlife));
            }
            if (a.Pico !== "") {
                MyLib.bttl.Pico = a.Pico;
            }
            if (a.Pweapon !== "") {
                MyLib.bttl.Pweapon = a.Pweapon;
            }
            if (a.Pshield !== "") {
                MyLib.bttl.Pshield = a.Pshield;
            }
            if (a.Mico !== "") {
                MyLib.bttl.Mico = a.Mico;
            }
            if (a.Mweapon !== "") {
                MyLib.bttl.Mweapon = a.Mweapon;
            }
            if (a.Mshield !== "") {
                MyLib.bttl.Mshield = a.Mshield;
            }
            if (a.Pvisible !== "") {
                MyLib.bttl.Pvisible = a.Pvisible;
            }
            if (a.PshieldNC !== "") {
                MyLib.bttl.PshieldNC = a.PshieldNC;
            }
            if (a.PeleksirNCarr !== "") {
                MyLib.bttl.PeleksirdNC = a.PeleksirNCarr;
            }
            if (a.PeleksirVisible !== "") {
                MyLib.bttl.PeleksirVisible = a.PeleksirVisible;
            }
            if (a.Mvisible !== "") {
                MyLib.bttl.Mvisible = a.Mvisible;
            }
            if (a.Buttonvisible !== "") {
                MyLib.bttl.butbatVisible = a.Buttonvisible;
                drawButtonBattle();
            }
            if (a.ButtonBattleColorCount !== "") {
                MyLib.bttl.ButtonBattleColorCount = a.ButtonBattleColorCount;
            }
            MyLib.bttl.BattleResult = 0;
            if (a.BattleResult !== "") {
                MyLib.bttl.BattleResult = a.BattleResult;
            }
            restartAnimation(a.Panimation, a.Manimation);
            MyLib.battleSetTimeid[9003] = setTimeout(function () {
                readBattleInfo();
            }, 2000);
        });
    } else {
        MyLib.bttl.movemob = 0;
        MyLib.battleSetTimeid[9003] = setTimeout(function () {
            readBattleInfo();
        }, 3000);
    }
};

readBattleInfo = function (c) {
    if (c != null) {
        MyLib.bttl.loading = 0;
        if (c < 3) {
            MyLib.bttl.butbatVisible = 0;
        }
        if (c > 3) {
            MyLib.bttl.PeleksirVisible = 0;
        }
        drawButtonBattle();
    }
    resizeBattle();
    if (MyLib.bttl.end === 0 && MyLib.bttl.loading === 0) {
        MyLib.bttl.loading = 1;
        if (!$("img").is(".loading") && c >= 0 && c < 11) {
            document.body.appendChild(imgLoading);
        }
        clearTimeout(MyLib.battleSetTimeid[9003]);
        $.ajax({
            type: "POST",
            url: "./php/battle.php",
            dataType: "json",
            data: {
                numClick: 1 + c
            }, success: function (a) {
                if (a.BattleResult === 5) {
                    Eleksirmsg();
                }
                drawEntity();
                if (a.PshieldNC <= 0) {
                    MyLib.bttl.PshieldNC = 0;
                    a.PshieldNC = 0;
                }
                MyLib.bttl.loading = 0;
                $(".loading").remove();
                c = null;
                if (MyLib.bttl.Plife === "") {
                    if (a.Pname !== "") {
                        MyLib.bttl.Playername = a.Pname;
                    }
                    if (a.Plife !== "") {
                        MyLib.bttl.Plife = MyLib.bttl.tempPlife = a.Plife;
                    }
                    if (a.Pvisible !== "") {
                        MyLib.bttl.Pvisible = a.Pvisible;
                    }
                    if (a.Pico !== "") {
                        MyLib.bttl.Pico = a.Pico;
                    }
                    if (a.Pweapon !== "") {
                        MyLib.bttl.Pweapon = a.Pweapon;
                    }
                    if (a.Pshield !== "") {
                        MyLib.bttl.Pshield = a.Pshield;
                    }
                }

                if (MyLib.bttl.Pload === 0) {
                    MyLib.bttl.Pload = 1;
                    Playerin(a);
                }
                if (MyLib.bttl.movemob === 0 && a.lost_mob_id > 0 && MyLib.bttl.lost_mob_id !== a.lost_mob_id || MyLib.bttl.movemob === 0 && a.lost_mob_id < 0 && MyLib.bttl.lost_mob_id !== a.lost_mob_id) {
                    MyLib.bttl.BattleResult = 0;
                    if (a.BattleResult !== "") {
                        MyLib.bttl.BattleResult = a.BattleResult;
                    }
                    MyLib.bttl.lost_mob_id = a.lost_mob_id;
                    MyLib.bttl.Mlife = "";
                    MyLib.bttl.tempMlife = "";
                    MyLib.bttl.Mobname = "";
                    MyLib.bttl.movemob = 1;
                    mobout(a);
                } else {
//очередность наложения при ударах
                    if (2 === a.Manimation || 3 === a.Manimation || 4 === a.Manimation || 5 === a.Manimation) {
                        $(document.getElementById("layer1")).css({zIndex: 1});
                        $(document.getElementById("layer2")).css({zIndex: 2});
                    } else {
                        $(document.getElementById("layer1")).css({zIndex: 2});
                        $(document.getElementById("layer2")).css({zIndex: 1});
                    }
                    if (a.Mentityarr.length > 1) {
//если попал
                        if (a.Manimation > 6) {
                            MyLib.bttl.tmpEntityM = JSON.parse(a.Mentityarr).reverse();
                        } else {
                            MyLib.bttl.arrEntityM = MyLib.bttl.arrEntityM.concat(JSON.parse(a.Mentityarr).reverse());
                            drawEntity();
                        }
                    }
                    if (a.Pentityarr.length > 1) {
//если попал
                        if (a.Panimation > 6) {
                            MyLib.bttl.tmpEntityP = JSON.parse(a.Pentityarr).reverse();
                        } else {
                            MyLib.bttl.arrEntityP = MyLib.bttl.arrEntityP.concat(JSON.parse(a.Pentityarr).reverse());
                            drawEntity();
                        }
                    }
                    if (a.Pname !== "") {
                        MyLib.bttl.Playername = a.Pname;
                    }
                    if (a.Mname !== "") {
                        MyLib.bttl.Mobname = a.Mname;
                    }
                    if (a.Plife !== "") {
                        MyLib.bttl.tempPlife = a.Plife;
                    }
                    if (a.Mlife !== "") {
                        MyLib.bttl.tempMlife = a.Mlife;
                    }
                    if (a.Pico !== "") {
                        MyLib.bttl.Pico = a.Pico;
                    }
                    if (a.Pweapon !== "") {
                        MyLib.bttl.Pweapon = a.Pweapon;
                    }
                    if (a.Pshield !== "") {
                        MyLib.bttl.Pshield = a.Pshield;
                    }
                    if (a.Mico !== "") {
                        MyLib.bttl.Mico = a.Mico;
                    }
                    if (a.Mweapon !== "") {
                        MyLib.bttl.Mweapon = a.Mweapon;
                    }
                    if (a.Mshield !== "") {
                        MyLib.bttl.Mshield = a.Mshield;
                    }
                    if (a.Pvisible !== "") {
                        MyLib.bttl.Pvisible = a.Pvisible;
                    }
                    if (a.PshieldNC !== "") {
                        MyLib.bttl.PshieldNC = a.PshieldNC;
                    }
                    if (a.PeleksirNCarr !== "") {
                        MyLib.bttl.PeleksirdNC = a.PeleksirNCarr;
                    }
                    if (a.PeleksirVisible !== "") {
                        MyLib.bttl.PeleksirVisible = a.PeleksirVisible;
                    }
                    if (a.Mvisible !== "") {
                        MyLib.bttl.Mvisible = a.Mvisible;
                    }
                    if (a.Buttonvisible !== "") {
                        MyLib.bttl.butbatVisible = a.Buttonvisible;
                        drawButtonBattle();
                    }
                    if (a.ButtonBattleColorCount !== "") {
                        MyLib.bttl.ButtonBattleColorCount = a.ButtonBattleColorCount;
                    }
                    MyLib.bttl.BattleResult = 0;
                    if (a.BattleResult !== "") {
                        MyLib.bttl.BattleResult = a.BattleResult;
                    }
                    restartAnimation(a.Panimation, a.Manimation);
                    if (MyLib.bttl.BattleResult === 1 && MyLib.bttl.tmpMobanim === a.Manimation && MyLib.bttl.tmpPanim === a.Panimation) {
                        MyLib.bttl.end = 1;
                        MyLib.footName = "huntresult";
                        showContent("/hunt/result.php");
                    }
                    MyLib.battleSetTimeid[9003] = setTimeout(function () {
                        readBattleInfo();
                    }, 2000);
                }
            },
            error: function () {
                MyLib.battleSetTimeid[9003] = setTimeout(function () {
                    MyLib.bttl.loading = 1;
                    readBattleInfo();
                }, 2500);
            }
        });
    }
};

drawButtonBattle = function () {
    if (1 === MyLib.bttl.butbatVisible) {
        if (MyLib.bttl.ButtonBattleColorCount === 0) {
            $(document.getElementById("button_green")).show();
            $(document.getElementById("button_yellow")).hide();
            $(document.getElementById("button_red")).hide();
        } else if (MyLib.bttl.ButtonBattleColorCount === 1) {
            $(document.getElementById("button_green")).hide();
            $(document.getElementById("button_yellow")).show();
            $(document.getElementById("button_red")).hide();
        } else if (MyLib.bttl.ButtonBattleColorCount === 2) {
            $(document.getElementById("button_green")).hide();
            $(document.getElementById("button_yellow")).hide();
            $(document.getElementById("button_red")).show();
        }
        if (MyLib.bttl.Pshield === 1) {
            $(document.getElementById("shield_1")).css({backgroundPosition: "right 0px"});
        } else {
            $(document.getElementById("shield_1")).css({backgroundPosition: "left 0px"});
        }
        $(document.getElementById("ico_shield_num")).html(MyLib.bttl.PshieldNC);
        for (var i = 0; i < 7; i++) {
            if (MyLib.bttl.PeleksirVisible === 1 && i < MyLib.bttl.PeleksirdNC.length) {
                $(document.getElementById("ico_poyas" + i)).addClass("shopicobattlebg shopicobattle" + MyLib.bttl.PeleksirdNC[i][1]);
                $(document.getElementById("num_poyas" + i)).html(MyLib.bttl.PeleksirdNC[i][0]);
            } else {
                $(document.getElementById("ico_poyas" + i)).removeClass();
                $(document.getElementById("num_poyas" + i)).html("");
            }
        }
        $(document.getElementById("button_visible")).show();
    } else {
        $(document.getElementById("button_visible")).hide();
    }
};

drawEntity = function () {
    clearTimeout(MyLib.battleSetTimeid[9002]);
    MyLib.bttl.buffBattleEntity.width = MyLib.cnv.w * 1.5;
    MyLib.bttl.buffBattleEntity.height = MyLib.cnv.h * 1.5;
    drawIconEntity(22, 5, 5, 0);
    drawIconEntity(22, 5, 5, 1);
    if (MyLib.bttl.arrEntityP.length > 0 || MyLib.bttl.arrEntityM.length > 0 || MyLib.bttl.EntityCoordP.length > 0 || MyLib.bttl.EntityCoordM.length > 0) {
        if (0 < MyLib.bttl.arrEntityP.length && 1 > MyLib.bttl.EntityCoordP.length) {
            MyLib.bttl.EntityCoordP.push(MyLib.bttl.arrEntityP.pop());
            MyLib.bttl.EntityCoordP[MyLib.bttl.EntityCoordP.length - 1][2] = MyLib.bttl.posPWidhtEntity;
            MyLib.bttl.EntityCoordP[MyLib.bttl.EntityCoordP.length - 1][3] = MyLib.bttl.posHegthEntity;
        }
        if (0 < MyLib.bttl.arrEntityP.length && MyLib.bttl.EntityCoordP[MyLib.bttl.EntityCoordP.length - 1][3] < MyLib.bttl.posHegthEntity - 28) {
            MyLib.bttl.EntityCoordP.push(MyLib.bttl.arrEntityP.pop());
            MyLib.bttl.EntityCoordP[MyLib.bttl.EntityCoordP.length - 1][2] = MyLib.bttl.posPWidhtEntity;
            MyLib.bttl.EntityCoordP[MyLib.bttl.EntityCoordP.length - 1][3] = MyLib.bttl.posHegthEntity;
        }
        for (MyLib.bttl.a04 = 0; MyLib.bttl.a04 < MyLib.bttl.EntityCoordP.length; MyLib.bttl.a04++) {
            MyLib.bttl.EntityCoordP[MyLib.bttl.a04][3] -= MyLib.bttl.speedFlyEntity + (MyLib.bttl.arrEntityP.length * 2);
            drawIconEntity(MyLib.bttl.icoStatC[MyLib.bttl.EntityCoordP[MyLib.bttl.a04][0]], MyLib.bttl.EntityCoordP[MyLib.bttl.a04][2], MyLib.bttl.EntityCoordP[MyLib.bttl.a04][3], 0);
            textToEntity(30 + MyLib.bttl.EntityCoordP[MyLib.bttl.a04][2], MyLib.bttl.EntityCoordP[MyLib.bttl.a04][3], MyLib.bttl.EntityCoordP[MyLib.bttl.a04][1], 0);
            if (-100 > MyLib.bttl.EntityCoordP[MyLib.bttl.a04][3]) {
                MyLib.bttl.EntityCoordP.shift();
            }
        }
        if (0 < MyLib.bttl.arrEntityM.length && 1 > MyLib.bttl.EntityCoordM.length) {
            MyLib.bttl.EntityCoordM.push(MyLib.bttl.arrEntityM.pop());
            MyLib.bttl.EntityCoordM[MyLib.bttl.EntityCoordM.length - 1][2] = MyLib.bttl.posMWidhtEntity;
            MyLib.bttl.EntityCoordM[MyLib.bttl.EntityCoordM.length - 1][3] = MyLib.bttl.posHegthEntity;
        }
        if (0 < MyLib.bttl.arrEntityM.length && MyLib.bttl.EntityCoordM[MyLib.bttl.EntityCoordM.length - 1][3] < MyLib.bttl.posHegthEntity - 28) {
            MyLib.bttl.EntityCoordM.push(MyLib.bttl.arrEntityM.pop());
            MyLib.bttl.EntityCoordM[MyLib.bttl.EntityCoordM.length - 1][2] = MyLib.bttl.posMWidhtEntity;
            MyLib.bttl.EntityCoordM[MyLib.bttl.EntityCoordM.length - 1][3] = MyLib.bttl.posHegthEntity;
        }
        for (a = 0; a < MyLib.bttl.EntityCoordM.length; a++) {
            MyLib.bttl.EntityCoordM[a][3] -= MyLib.bttl.speedFlyEntity + (MyLib.bttl.arrEntityM.length * 2);
            drawIconEntity(MyLib.bttl.icoStatC[MyLib.bttl.EntityCoordM[a][0]], MyLib.bttl.EntityCoordM[a][2], MyLib.bttl.EntityCoordM[a][3], 0);
            textToEntity(30 + MyLib.bttl.EntityCoordM[a][2], MyLib.bttl.EntityCoordM[a][3], MyLib.bttl.EntityCoordM[a][1], 0);
            if (-100 > MyLib.bttl.EntityCoordM[a][3]) {
                MyLib.bttl.EntityCoordM.shift();
            }
        }
        MyLib.bttl.realBattleEntity.width = MyLib.bttl.realBattleEntity.offsetWidth;
        MyLib.bttl.realBattleEntity.height = MyLib.bttl.realBattleEntity.offsetHeight;
        MyLib.bttl.ctxRBE.drawImage(MyLib.bttl.buffBattleEntity, 0, 0, MyLib.bttl.realBattleEntity.width, MyLib.bttl.realBattleEntity.height);
    }

    if (MyLib.bttl.arrEntityP.length > 0 || MyLib.bttl.arrEntityM.length > 0 || MyLib.bttl.EntityCoordP.length > 0 || MyLib.bttl.EntityCoordM.length > 0) {
        MyLib.battleSetTimeid[9002] = setTimeout(function () {
            drawEntity();
        }, 1000 / MyLib.bttl.entytyspeedcount);
    }
};

drawIconEntity = function (a, b, c, e) {
    if (0 === e) {
        addSprite(MyLib.bttl.ctxBattleEntity, a, b, c);
    }
    if (1 === e) {
        addSprite(MyLib.bttl.ctxBattleEntity, a, MyLib.bttl.buffBattleEntity.width - b - 30, c);
    }
};

textToEntity = function (a, b, c, e) {
    c = (c + "").split("");
    if (0 === e)
        for (MyLib.bttl.d01 = 0; MyLib.bttl.d01 < c.length; MyLib.bttl.d01++) {
            drawIconEntity(MyLib.bttl.icofontcount[c[MyLib.bttl.d01]], a + 25 * MyLib.bttl.d01, b, 0);
        }
    if (1 === e) {
        for (MyLib.bttl.ii = 0, d = c.length - 1; 0 <= d; d--) {
            drawIconEntity(MyLib.bttl.icofontcount[c[MyLib.bttl.ii]], a + 25 * d, b, 1);
            MyLib.bttl.ii++;
        }
    }
};