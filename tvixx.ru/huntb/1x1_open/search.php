<?php

require_once '../../system/func.php';
if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>/*nextshowcontemt*/showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script>
    <?php
    exit(0);
}
if (isset($user['vinos_m']) && $user['vinos_m']<=0) {
    ?>
    <script>/*nextshowcontemt*/showContent("/main.php?msg=" + decodeURI("Недостаточно выносливости."));</script>
    <?php
    exit(0);
}
if (isset($user['temp_health']) && $user['temp_health']<=0) {
    ?>
    <script>/*nextshowcontemt*/showContent("/main.php?msg=" + decodeURI("Недостаточно здоровья."));</script>
    <?php
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Открытый поединок - Mobitva v1.0</title>
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

.battle-search {
    width: 96%;
    max-width: 800px;
    margin: 15px auto;
    animation: fadeIn 0.5s ease-out;
}

.battle-header {
    text-align: center;
    padding: 15px;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.battle-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.battle-subtitle {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 5px;
}

.battle-info {
    margin: 15px 0;
    padding: 15px;
    font-size: 16px;
    color: var(--muted);
    text-align: center;
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.battle-button {
    padding: 12px 25px;
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 15px;
    color: var(--text);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border: 1px solid var(--glass-border);
    background: var(--glass-bg);
    text-transform: uppercase;
    text-align: center;
    display: inline-block;
    min-width: 200px;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(8px);
}

.battle-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    background: var(--item-hover);
    color: var(--accent);
}

.battle-button:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.battle-button.primary {
    background: var(--primary-button);
    color: #111;
    font-weight: 600;
    border: none;
}

.battle-button.primary:hover {
    background: var(--primary-button-hover);
    color: #111;
}

/* Стили для таймера и анимации */
.timer-container {
    margin: 20px auto;
    text-align: center;
    max-width: 300px;
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    padding: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.timer-display {
    font-size: 24px;
    font-weight: 600;
    color: var(--accent);
    padding: 8px 12px;
    border-radius: var(--radius);
    display: inline-block;
    min-width: 100px;
    margin-bottom: 10px;
}

.progress-bar-container {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    margin: 10px auto;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(to right, var(--accent-2), var(--primary-button));
    border-radius: 4px;
    width: 0%;
    transition: width 0.9s ease;
}

.search-dots {
    display: inline-block;
    position: relative;
    width: 25px;
    text-align: left;
}

.search-dots::after {
    content: '';
    animation: searchDots 1.5s infinite;
}

.battle-alert {
    font-size: 22px;
    font-weight: 700;
    color: var(--alert-color);
    text-align: center;
    margin: 15px 0;
    display: none;
    animation: battlePulse 0.6s infinite alternate;
}

@keyframes searchDots {
    0% { content: '.'; }
    33% { content: '..'; }
    66% { content: '...'; }
    100% { content: ''; }
}

@keyframes battlePulse {
    from { transform: scale(1); }
    to { transform: scale(1.05); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .battle-header {
        padding: 12px;
    }
    
    .battle-title {
        font-size: 16px;
    }
    
    .battle-subtitle {
        font-size: 13px;
    }
    
    .battle-info {
        padding: 12px;
        font-size: 14px;
    }
    
    .battle-button {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .timer-display {
        font-size: 22px;
    }
    
    .timer-container {
        padding: 12px;
    }
}

@media (max-width: 480px) {
    .battle-header {
        padding: 10px;
    }
    
    .battle-title {
        font-size: 14px;
    }
    
    .battle-subtitle {
        font-size: 12px;
    }
    
    .battle-info {
        padding: 10px;
        font-size: 13px;
    }
    
    .battle-button {
        padding: 8px 16px;
        font-size: 12px;
        min-width: 180px;
    }
    
    .timer-display {
        font-size: 20px;
    }
    
    .battle-alert {
        font-size: 18px;
    }
}
</style>
</head>
<body>

<div class="battle-search">
    <div class="battle-header">
        <div class="battle-title"><i class="fas fa-users"></i> Открытый поединок</div>
        <div class="battle-subtitle">Бой с равным противником, открытый для вмешательства других бойцов</div>
    </div>
    
    <div class="timer-container">
        <div class="timer-display">00:00</div>
        <div class="progress-bar-container">
            <div class="progress-bar"></div>
        </div>
    </div>
    
    <div class="battle-info info">
        <i class="fas fa-sync fa-spin"></i> Идет поиск противника<span class="search-dots"></span>
    </div>
    
    <div class="battle-alert text-center">
        <i class="fas fa-exclamation-triangle"></i> ПРОТИВНИК НАЙДЕН! ПРИГОТОВЬТЕСЬ К БОЮ!
    </div>
    
    <div class="text-center" style="margin-top: 20px;">
        <div class="battle-button" onclick="huntb_remove()"><i class="fas fa-times"></i> Отказаться</div>
    </div>
</div>

<script>
    function huntb_remove() {
        try {
            $.ajax({
                type: "POST",
                url: "../../huntb/1x1_open/remove.php",
                dataType: "json",
                success: function (data) {
                    if (data.result == 1) {
                        clearTimeout(MyLib.setTimeoutHuntB);
                        showContent("/");
                    } else {
                        alert("error 1765445");
                    }
                },
                error: function (e) {
                    alert("error 141323");
                }
            });
        } catch (e) {
            alert("error 11252345634");
        }

    }
    
    huntb_add("2");
    
    var inbattle = 0;
    var totalSeconds = 0;
    
    function formatTime(seconds) {
        var minutes = Math.floor(seconds / 60);
        var remainingSeconds = seconds % 60;
        return (minutes < 10 ? "0" : "") + minutes + ":" + (remainingSeconds < 10 ? "0" : "") + remainingSeconds;
    }
    
    function updateProgressBar(seconds) {
        // Максимальное время ожидания около 60 секунд
        var progressPercent = Math.min(100, (seconds / 60) * 100);
        $(".progress-bar").css("width", progressPercent + "%");
    }
    
    function setTimeHuntB() {
        clearTimeout(MyLib.setTimeoutHuntB);
        try {
            $.ajax({
                type: "POST",
                url: "../../huntb/1x1/check.php",
                dataType: "json",
                success: function (data) {
                    if (data.result == 1 || inbattle == 1) {
                        // Переход к бою если бой создан
                        $(".battle-info").hide();
                        $(".timer-container").hide();
                        $(".battle-alert").show();
                        
                        // Небольшая задержка для анимации
                        setTimeout(function() {
                            showContent('/hunt/battle.php');
                        }, 1500);
                    } else if (data.result != 0 && data.error != 0) {
                        alert("error " + data.error + " setTimeHuntB");
                        showContent('/');
                    } else {
                        if (data.time > 0) {
                            totalSeconds++;
                            $(".timer-display").text(formatTime(totalSeconds));
                            updateProgressBar(totalSeconds);
                        } else {
                            inbattle = 1;
                            $(".battle-info").hide();
                            $(".timer-container").hide();
                            $(".battle-alert").show();
                        }
                        MyLib.setTimeoutHuntB = setTimeout(function () {
                            setTimeHuntB();
                        }, 1000);
                    }
                },
                error: function (e) {
                    alert("error 1554");
                    showContent('/');
                }
            });
        } catch (e) {
            alert("error 1556");
            showContent('/');
        }
    }
    
    function huntb_add(x) {
        try {
            $.ajax({
                type: "POST",
                url: "../../huntb/1x1_open/add.php",
                dataType: "json",
                data: {
                    type: x
                },
                success: function (data) {
                    if (data.result == 1) {
                        setTimeHuntB();
                    } else {
                        alert("error 1665454");
                    }
                },
                error: function (e) {
                    alert("error 15467343");
                }
            });
        } catch (e) {
            alert("error 1412");
        }
    }
</script>
<?php

$footval = "huntb1x1";
require_once ('../../system/foot/foot.php');

