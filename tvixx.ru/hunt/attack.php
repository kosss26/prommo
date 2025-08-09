<?php
require_once ('../system/func.php');
require '../system/dbc.php';

auth();
// Закроем от неавторизированых
requestModer();
// Закроем для тех у кого есть запрос на модератора
//проверяем что герой не в бою
if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/battle.php");</script><?php
    exit(0);
}
//проверяем результаты если есть то перекинем туда чтобы обработало монстров
if ($mc->query("SELECT * FROM `resultbattle` WHERE `id_user`='" . $user['id'] . "' ORDER BY `id` DESC LIMIT 1")->num_rows > 0) {
    ?><script>/*nextshowcontemt*/showContent("/hunt/result.php");</script><?php
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#41280A">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
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
            --attack-button: #ff3a2f;
            --attack-button-hover: #ff6f67;
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            color: var(--text);
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        }
        
        a {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }
        
        .otrazit {
            transform: scale(-1, 1); 
        }
        
        .attack-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        
        .monster-title {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text);
            padding: 15px 20px;
            border-radius: var(--radius);
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            position: relative;
            backdrop-filter: blur(8px);
            overflow: hidden;
        }
        
        .canvas-container {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 20px;
            margin: 0 auto 25px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            max-width: 600px;
            overflow: hidden;
            backdrop-filter: blur(8px);
        }
        
        .canvas-wrapper {
            position: relative;
            margin: 0 auto;
            display: inline-block;
            background: rgba(0,0,0,0.3);
            border-radius: 8px;
            padding: 10px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.4);
        }
        
        #MiniCanvas {
            display: block;
            margin: 0 auto;
            image-rendering: pixelated;
            border-radius: 4px;
        }
        
        .attack-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 40px;
            background: var(--attack-button);
            color: var(--text);
            border-radius: 30px;
            font-weight: 600;
            font-size: 18px;
            margin: 15px 0;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        .attack-button:hover {
            transform: translateY(-3px);
            background: var(--attack-button-hover);
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
        }
        
        .admin-link {
            display: inline-block;
            margin-top: 15px;
            color: var(--muted);
            text-decoration: none;
            padding: 8px 16px;
            background: var(--secondary-bg);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .admin-link i {
            margin-right: 5px;
        }
        
        .admin-link:hover {
            color: var(--accent);
            background: var(--item-hover);
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text);
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .back-button i {
            margin-right: 8px;
        }
        
        .back-button:hover {
            background: var(--item-hover);
            transform: translateY(-2px);
            color: var(--accent);
        }
        
        @media (max-width: 600px) {
            .attack-container {
                padding: 10px;
            }
            
            .monster-title {
                font-size: 16px;
                padding: 12px 15px;
                margin-bottom: 15px;
            }
            
            .canvas-container {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            .attack-button {
                padding: 14px 30px;
                font-size: 16px;
            }
        }
        
        @media (max-width: 400px) {
            .monster-title {
                font-size: 15px;
                padding: 10px;
            }
            
            .canvas-container {
                padding: 10px;
            }
            
            .attack-button {
                padding: 12px 25px;
                font-size: 15px;
                width: 90%;
            }
        }
    </style>
</head>
<body>
<?php
if (isset($_GET['id'])) {
    $mobid = $_GET['id'];
} else {
    ?>
    <script>showContent("/main.php");</script>
    <?php
    exit(0);
}
if ($user['location'] == 1 || $user['location'] == 2) {
    $user['location'] = 102;
}
if ($result221 = $mc->query("SELECT * FROM `hunt` WHERE `id` = '" . json_decode($user['huntList'])[$mobid] . "'")) {
    $mob = $result221->fetch_array(MYSQLI_ASSOC);
} else {
    ?>
    <script>showContent("/main.php");</script>
    <?php
    exit(0);
}
?>

<div class="attack-container">
    <div class="monster-title">
        <i class="fas fa-skull me-2"></i> <?php echo $mob['name']; ?> <i class="fas fa-skull ms-2"></i>
    </div>
    
    <div class="canvas-container">
        <div class="canvas-wrapper">
            <canvas id="MiniCanvas"></canvas>
        </div>
        
        <?php if ($user['access'] > 2): ?>
            <a onclick="showContent('/admin/hunt.php?mob=edit&id=<?php echo $mob['id']; ?>')" class="admin-link">
                <i class="fas fa-edit"></i> Изменить моба id[<?= $mob['id']; ?>] (Админ)
            </a>
        <?php endif; ?>
    </div>
    
    <center>
        <a href="#" onclick="showContent('/hunt/index.php')" class="back-button">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
        
        <div class="attack-button arrowHunt<?= $mob['id']; ?>" onclick="HuntMobBattleOne('<?php echo $mobid; ?>')">
            <i class="fas fa-swords me-2"></i> Атаковать <i class="fas fa-swords ms-2 otrazit"></i>
        </div>
    </center>
</div>

<script>
    var MiniCanvas = $("mobitva:eq(-1)").find("#MiniCanvas").get(0);
    var ctxMiniCanvas = MiniCanvas.getContext("2d");
    var buffMiniCanvas = document.createElement("canvas");
    var ctxbuffMiniCanvas = buffMiniCanvas.getContext("2d");
    var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
    var cancelAnimationFrame = window.cancelAnimationFrame || window.mozCancelAnimationFrame;
    var myReq;
    var weaponData = [];
    var imageweapon;
    var spriteData = [];
    var spriteImage = [];
    var PshieldNC = 0;
    var Pshield = 0;
    var Panimation = 0;
    var Pweapon = 0;
    var Panimationcount = 0;
    var Pico = <?php echo $mob['iconid']; ?>;
    var PposX = -170;
    var PposY = -130;
    MiniCanvas.width = buffMiniCanvas.width = 280;
    MiniCanvas.height = buffMiniCanvas.height = 150;

    if (Pico === 9||Pico === 19||Pico === 29||Pico === 39||Pico === 49||Pico === 59||Pico === 69) {
        PposX = -170;
        PposY = -75;
        MiniCanvas.width = buffMiniCanvas.width = 280;
        MiniCanvas.height = buffMiniCanvas.height = 200;
    }


    $.ajax({
        url: "./json/weapon/weapon_new.json?139.1114",
        dataType: "json",
        success: function (a) {
            weaponData = JSON.parse(JSON.stringify(a));
            imageweapon = new Image;
            imageweapon.src = weaponData.img;
        }
    });
    $.ajax({
        url: "./json/Mob/animation.json?136.3331",
        dataType: "json",
        success: function (a) {
            spriteData = JSON.parse(JSON.stringify(a));
            var newJson = {};
                for (a = 1; a <= spriteData.AnimCount; a++) {
                    newJson[a] = spriteData[spriteData.keyToAnim[a]];
                }
                newJson.img = spriteData.img;
                spriteData = newJson;
            for (a = 1; a < spriteData.img.length + 1; a++)
                spriteImage[a] = new Image, spriteImage[a].src = spriteData.img[a - 1];
        }
    });
    function render() {
        MiniCanvas.width = MiniCanvas.width;
        try {
            //ctxMiniCanvas.fillStyle = "#ff0000";
            //ctxMiniCanvas.fillRect(0,0,MiniCanvas.width,MiniCanvas.height);
            ctxMiniCanvas.drawImage(buffMiniCanvas,
                    0,
                    0,
                    MiniCanvas.width,
                    MiniCanvas.height,
                    0,
                    0,
                    buffMiniCanvas.width,
                    buffMiniCanvas.height
                    );
        } catch (e) {
        }
        MyLib.setTimeid[200] = setTimeout(function () {
            myReq = requestAnimationFrame(render);
        }, 1000 / 10);
    }
    myReq = requestAnimationFrame(render);

    MyLib.intervaltimer[1] = setInterval(function () {
        buffMiniCanvas.width = buffMiniCanvas.width;
        try {
            if (Panimationcount >= spriteData[Pico][Panimation].length) {
                Panimationcount = 0;
            }
            for (var a = 0; a < spriteData[Pico][Panimation][Panimationcount].length; a++) {
                if (spriteData[Pico][Panimation][Panimationcount][a][9] !== -1) {
                    ctxbuffMiniCanvas.save();
                    ctxbuffMiniCanvas.translate(Math.round(buffMiniCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] + spriteData[Pico][Panimation][Panimationcount][a][6] / 2) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                    ctxbuffMiniCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                    ctxbuffMiniCanvas.drawImage(spriteImage[Pico], spriteData[Pico][Panimation][Panimationcount][a][0], spriteData[Pico][Panimation][Panimationcount][a][1], spriteData[Pico][Panimation][Panimationcount][a][2], spriteData[Pico][Panimation][Panimationcount][a][3], Math.round(-spriteData[Pico][Panimation][Panimationcount][a][6] / 2), Math.round(-spriteData[Pico][Panimation][Panimationcount][a][7] / 2), spriteData[Pico][Panimation][Panimationcount][a][6], spriteData[Pico][Panimation][Panimationcount][a][7]);
                    ctxbuffMiniCanvas.restore();
                } else {
                    ctxbuffMiniCanvas.save();
                    ctxbuffMiniCanvas.translate(Math.round(buffMiniCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] + spriteData[Pico][Panimation][Panimationcount][a][6] / 2) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                    ctxbuffMiniCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                    ctxbuffMiniCanvas.drawImage(imageweapon, weaponData.imgC[Pweapon][0], weaponData.imgC[Pweapon][1], weaponData.imgC[Pweapon][2], weaponData.imgC[Pweapon][3], Math.round(-weaponData.imgC[Pweapon][2] / 2), Math.round(-weaponData.imgC[Pweapon][3] / 2), weaponData.imgC[Pweapon][2], weaponData.imgC[Pweapon][3]);
                    ctxbuffMiniCanvas.restore();
                }
            }
        } catch (e) {

        }

        Panimationcount++;
    }, 200);
</script>
<?php
$footval = 'huntattack';
require_once ('../system/foot/foot.php');
?>
</body>
</html>