var update_bool = 0;
var imgSnowflakes = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABAAQMAAACQp+OdAAAC3HpUWHRSYXcgcHJvZmlsZSB0eXBlIGV4aWYAAHja7ZdJktwgE" +
        "EX3nMJHUE4kHAeBiPANfHx/EK0autwRHhZelKhCKEkS+C9Fd4Xjx/cevuGiLFtQ8xRzjBsuzZq5oJG28zrvtOms56WrC88P9nB1MEyCu5yP8Vj+BXa7DfA1gPZHe/C64qQV" +
        "aHV8BJQxM6Ox/NIKJHzaaT2HvMYVvdvO+vbKs9v2s+v5WR1iNEM84cCHkGyodcwiWIEkKagZNYsPJxG0ddX6WrtwNZ/Eu1pP2m1l2eVRirDF5RCfNFp2stfaTYXuV0S3mR8" +
        "6XK4pPmvXW+r9OHdXNEKpGNamPrYyW3CEnCpzWERxfA1tnyWjJGyxglgDzR2lBsrEULuTUqNCnY55r1SxROWDITczV5ZpS5A/c51QdBTq7JKlBTBiqaAmMPO1Fprz5jlfpYS" +
        "ZG8GTCcEG0U8lvDL+SbkC9T5Sl2hLl1ZYF4+cxjIGuVHDC0CoL01t6jtLuMub7Q6sgKBNmRM2WLb9DLEb3XJLJmeBn20a1utM3lYASIS5DYshAYEtkhhF2pzZiaBjAp+ClbMo" +
        "7yBAZtwodLARiYCTeMyNMU7Tl41PM44WgDCJ4kCTpQCWqiF/XBNyqJiYBjOL5pYsW4kSNVqM0eM4o4qLq5tHd0+evSRJmizF5CmlnErmLDjCLMfsIaeccymYtCB0wegCj1J23" +
        "mXX3fa4+572vJeK9KlarcbqNdVcS+MmDa9/i81DSy23ctCBVDr0sCMefqQjH6Uj17p07dZj95567uWitqg+UqMncl9To0VtENPp5zdqMLt/hKBxnNhgBmKsBOI+CCCheTDbEqn" +
        "yIDeYbXkcV8agRjbgNBrEQFAPYut0sbuR+5JbMP0tbvwrcmGg+xfkwkC3yH3m9oJaK/MvikxA4y0cmm7ScbDBqXDCB+fxn9/D3wZ4B3oHegd6B3oHegd6B/pvAknHPw/47Rh+A" +
        "mD/kdzEB9lcAAAABlBMVEVlc3T///+nz6zgAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAALiMAAC4jAXilP3YAAAAHdElNRQfiDBsVKyyL9d2nAAABCElEQVQ" +
        "oz52SQUoEMRBFf1Ngb4bJBcRcYZYuBnMVj+ABBtMwB/EqJb1w2UewYS4Q6IUNhsSqpOOgO82mHiRU/f8rAECMev4CN8SPBcz5NVQYxwr9MtVHXc6D1jv4hCeBPdyKUMCEDaShwK" +
        "HCDidT4AhfIcFPCkuEzQo5oK/AMqCA9PEDcRcVZuI+6eyV2Mp885mI88sMN+fzmPoID3eZ1i7hBJPzjCMOIJW6U7EitR67/oZ2tfm6FyEi4hbPsMsUpLjBj2OkKO6yDBWPpDKcP" +
        "s+B2GgDz8SkUpt4anZMM+iK5Y/1GsIWy8M1qO/oNMx5A9vibYGL1AKg5Z23Nb2Fn4vbt1X+4yd8AfEJnTIRHPG+AAAAAElFTkSuQmCC";

var snowflakes = [];
var maxNum,
        amount,
        renderer,
        container,
        counter_snow,
        currentTexture,
        cx_snow,
        min_Scale_px,
        max_Scale_px,
        min_Speed_px,
        max_Speed_px,
        min_Rotation_angle,
        max_Rotation_angle,
        min_Sleep_Add_ml,
        max_Sleep_Add_ml,
        max_Turn,
        offsetpos,
        minScale,
        maxScale,
        minSpeed,
        maxSpeed,
        minRs,
        maxRs,
        minTs,
        maxTs,
        maxTurn,
        snowflakeU,
        snowflakeA
        ;
window.addEventListener('resize', function () {
    resizesnow();
});
snowFlacesAdd(
        100,
        3,
        2,
        5,
        500,
        1000,
        -30,
        60,
        800,
        1000,
        0.5
        );
function snowFlacesAdd(
        max_Nums,
        amounts,
        minimum_Scale_px,
        maximum_Scale_px,
        minimum_Speed_px,
        maximum_Speed_px,
        minimum_Rotation_angle,
        maximum_Rotation_angle,
        minimum_Sleep_Add_ml,
        maximum_Sleep_Add_ml,
        maximum_Turn
        ) {
    snowflakes = [];

    min_Scale_px = minimum_Scale_px;
    max_Scale_px = maximum_Scale_px;
    min_Speed_px = minimum_Speed_px;
    max_Speed_px = maximum_Speed_px;
    min_Rotation_angle = minimum_Rotation_angle;
    max_Rotation_angle = maximum_Rotation_angle;
    min_Sleep_Add_ml = minimum_Sleep_Add_ml;
    max_Sleep_Add_ml = maximum_Sleep_Add_ml;
    max_Turn = maximum_Turn;

    maxNum = max_Nums;
    amount = amounts;

    offsetpos = max_Scale_px;//px maxScale
    minScale = min_Scale_px * 0.015625;//% * (1/64)
    maxScale = max_Scale_px * 0.015625;//%
    minSpeed = min_Speed_px / 1000;
    maxSpeed = max_Speed_px / 1000;
    minRs = min_Rotation_angle / 1000;
    maxRs = max_Rotation_angle / 1000;
    minTs = min_Sleep_Add_ml;
    maxTs = max_Sleep_Add_ml;
    maxTurn = max_Turn;

    counter_snow = 0;
    renderer = PIXI.autoDetectRenderer(480, 328, {
        autoResize: false,
        clearBeforeRender: true,
        forceCanvas: false,
        transparent: true,
        antialias: false
    });
    renderer.view.className = "canvas_snow";
    container = new PIXI.DisplayObjectContainer();
    stage = new PIXI.Stage(0x000000, true);
    stage.addChild(container);
    currentTexture = new PIXI.Texture(new PIXI.Texture.fromImage(imgSnowflakes).baseTexture, new PIXI.math.Rectangle(0, 0, 64, 64));
    addSnow();
    if (update_bool == 0) {
        update_bool = 1;
        requestAnimationFrame(update);
    }

};

function resizesnow() {
    if (!$('.snowConteiner').parent().height()) {
        MyLib.setTimeid[200] = setTimeout(function () {
            resizesnow();
        }, 500);
    } else {
        cx_snow = $('.snowConteiner').parent().height() / 328;
        offsetpos = (max_Scale_px) * cx_snow;//px maxScale
        minScale = (min_Scale_px * 0.015625) * cx_snow;//px * (1/64)
        maxScale = (max_Scale_px * 0.015625) * cx_snow;//px
        minSpeed = (min_Speed_px / 1000) * cx_snow;
        maxSpeed = (max_Speed_px / 1000) * cx_snow;
        minRs = (min_Rotation_angle / 1000) * cx_snow;
        maxRs = (max_Rotation_angle / 1000) * cx_snow;
        renderer.resize($('.snowConteiner').parent().width(), $('.snowConteiner').parent().height()-10);
        addSnow();
    }
}
function snowAppend(el){
el.append(renderer.view);
resizesnow();
}
var i988976876 = 0;
function addSnow() {
    if (counter_snow < maxNum) {
        for (i988976876 = 0; i988976876 < amount; i988976876++) {
            delete snowflakeA;
            snowflakeA = null;
            snowflakeA = new PIXI.Sprite(currentTexture);
            snowflakeA.anchor.set(0.5);
            snowflakeA.y = -offsetpos;
            snowflakeA.x = Math.random() * renderer.width;
            snowflakeA.Speed = (Math.random() * maxSpeed) + minSpeed;
            snowflakeA.turns = (Math.random() * maxTurn) - maxTurn / 2;
            snowflakeA.Rs = (Math.random() * maxRs) + minRs;
            snowflakeA.scale.set((Math.random() * maxScale) + minScale);
            snowflakes.push(snowflakeA);
            container.addChild(snowflakeA);
            counter_snow++;
        }
        setTimeout(function () {
            addSnow();
        }, (Math.random() * maxTs) + minTs);
    }
}
var i121213123 = 0;
function update() {
    for (i121213123 = 0; i121213123 < snowflakes.length; i121213123++) {
        snowflakeU = snowflakes[i121213123];
        snowflakeU.y += snowflakeU.Speed;
        snowflakeU.x += snowflakeU.turns;
        snowflakeU.rotation += snowflakeU.Rs;

        if (snowflakeU.y > renderer.height + offsetpos ||
                snowflakeU.x < -offsetpos ||
                snowflakeU.x > renderer.width + offsetpos) {
            snowflakeU.y = -offsetpos;
            snowflakeU.x = Math.random() * renderer.width;
            snowflakeU.Speed = (Math.random() * maxSpeed) + minSpeed;
            snowflakeU.turns = (Math.random() * maxTurn) - maxTurn / 2;
            snowflakeU.Rs = (Math.random() * maxRs) + minRs;
            snowflakeU.scale.set((Math.random() * maxScale) + minScale);
        }
    }
    renderer.render(stage);
    requestAnimationFrame(update);
}