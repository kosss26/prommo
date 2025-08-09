<?php
require_once ('../../system/func.php');
require_once ('../../system/dbc.php');
ob_start();
if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Недоступно до 2 уровня"));</script>
    <?php
}
//диапазоны уровней получение по лвл в качестве индекса
$arr_lvl = [
    -1 => [-1, 0],
    0 => [-1, 0],
    1 => [1, 2],
    2 => [1, 2],
    3 => [3, 4],
    4 => [3, 4],
    5 => [5, 6],
    6 => [5, 6],
    7 => [7, 8],
    8 => [7, 8],
    9 => [9, 10],
    10 => [9, 10],
    11 => [11, 12],
    12 => [11, 12],
    13 => [13, 14],
    14 => [13, 14],
    15 => [15, 16],
    16 => [15, 16],
    17 => [17, 18],
    18 => [17, 18],
    19 => [19, 20],
    20 => [19, 20],
    21 => [21, 22],
    22 => [21, 22],
    23 => [23, 24],
    24 => [23, 24],
    25 => [25, 26],
    26 => [25, 26],
    27 => [27, 28],
    28 => [27, 28],
    29 => [29, 30],
    30 => [29, 30],
    31 => [31, 32],
    32 => [31, 32],
    33 => [33, 34],
    34 => [33, 34],
    35 => [35, 36],
    36 => [35, 36],
    37 => [37, 38],
    38 => [37, 38],
    39 => [39, 40],
    40 => [39, 40],
    41 => [41, 42],
    42 => [41, 42],
    43 => [43, 44],
    44 => [43, 44],
    45 => [45, 46],
    46 => [45, 46],
    47 => [47, 48],
    48 => [47, 48],
    49 => [49, 50],
    50 => [49, 50],
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Турниры - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111">
    <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
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
            --primary-button: #ff8452;
            --primary-button-hover: #ff6a33;
            --alert-color: #ff4c4c;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        }

        .tournament-arena {
            width: 96%;
            max-width: 800px;
            margin: 15px auto;
            animation: fadeIn 0.5s ease-out;
        }

        .tournament-level {
            text-align: center;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 20px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            position: relative;
            overflow: hidden;
        }

        .tournament-level span {
            position: relative;
            color: var(--accent);
        }

        .tournament-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .tournament-button {
            padding: 14px 20px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 15px;
            color: var(--text);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            text-align: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tournament-button i {
            margin-right: 8px;
        }

        .tournament-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            background: var(--item-hover);
            color: var(--accent);
        }

        .tournament-info {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .tournament-info-section {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        .tournament-info-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .tournament-info-title {
            color: var(--accent);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .tournament-info-title i {
            margin-right: 8px;
        }

        .tournament-info-text {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .tournament-info-text b {
            color: var(--accent);
            font-weight: 600;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .tournament-arena {
                padding: 10px;
                margin: 5px auto;
            }

            .tournament-level {
                font-size: 14px;
                padding: 12px;
            }

            .tournament-menu {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .tournament-button {
                padding: 12px 15px;
                font-size: 14px;
            }

            .tournament-info {
                padding: 15px;
            }

            .tournament-info-title {
                font-size: 15px;
            }

            .tournament-info-text {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .tournament-level {
                font-size: 13px;
                padding: 10px;
            }

            .tournament-button {
                padding: 10px 12px;
                font-size: 13px;
            }

            .tournament-info {
                padding: 12px;
            }

            .tournament-info-title {
                font-size: 14px;
            }

            .tournament-info-text {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<div class="tournament-arena">
    <div class="tournament-level">
        <i class="fas fa-users"></i> С вами в турнире могут участвовать игроки <span><?=$arr_lvl[$user['level']][0];?>-<?=$arr_lvl[$user['level']][1];?> уровней</span>
    </div>

    <div class="tournament-menu">
        <button class="tournament-button" onclick="showContent('/huntb/tur/vjv/index.php')">
            <i class="fas fa-skull"></i> Выживание
        </button>
        
        <button class="tournament-button" onclick="showContent('/huntb/tur/stenka/index.php')">
            <i class="fas fa-shield-alt"></i> Стенка на стенку
        </button>
        
        <button class="tournament-button" onclick="showContent('/huntb/tur/slava.php')">
            <i class="fas fa-award"></i> Стена славы
        </button>
    </div>

    <div class="tournament-info">
        <div class="tournament-info-section">
            <div class="tournament-info-title">
                <i class="fas fa-skull"></i> Выживание
            </div>
            <div class="tournament-info-text">
                В этом виде турнира каждый воин сражается за себя. 
                Победителем объявляется <b>последний оставшийся на ногах</b>. 
                Призовой фонд, собранный из взносов участников турнира, делится между 
                победителем (50%) и первым и вторым местами по урону (25%).
            </div>
        </div>

        <div class="tournament-info-section">
            <div class="tournament-info-title">
                <i class="fas fa-shield-alt"></i> Стенка на стенку
            </div>
            <div class="tournament-info-text">
                В этом виде турнира собираются две равные команды от каждого народа 
                и бьются до полной победы одной из команд. Призовой фонд, собранный 
                из взносов участников турнира, делится поровну между <b>выжившими</b> 
                участниками победившей команды.
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof showContent === 'undefined') {
        function showContent(url) {
            console.log("Переход по ссылке (showContent):", url);
            alert("Функция showContent не найдена. Переход по ссылке: " + url);
        }
    }
</script>

<?php
$footval = 'huntb1x1';
require_once ('../../system/foot/foot.php');
ob_end_flush();
?>
</body>
</html>