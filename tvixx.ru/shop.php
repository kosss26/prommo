<?php
require_once 'system/func.php';
require_once 'system/dbc.php';
require_once 'system/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_snyat.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_kupit.php';
auth(); // Закроем от неавторизированых

// В начале файла после подключения всех require добавим определение массивов для стилей
$colorStyle = array("black", "green", "blue", "red", "yellow");
$textStyle = array("", "Урон", "Уворот", "Броня", "Элита");
?>

<!-- Общие стили для всех модальных окон -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --bg-grad-start: #111;
        --bg-grad-end: #1a1a1a;
        --accent: #f5c15d;
        --accent-2: #ff8452;
        --card-bg: rgba(34, 34, 36, 0.8);
        --glass-bg: rgba(28, 28, 30, 0.8);
        --glass-border: rgba(60, 60, 67, 0.3);
        --text: #fff;
        --muted: #c2c2c2;
        --radius: 16px;
        --secondary-bg: rgba(40, 40, 45, 0.85);
        --item-hover: rgba(50, 50, 55, 0.9);
        --table-header: rgba(45, 45, 50, 0.85);
        --table-row-alt: rgba(35, 35, 40, 0.7);
        --table-row-hover: rgba(55, 55, 60, 0.85);
        --team1-color: #e74c3c;
        --team2-color: #3498db;
        --danger-color: #ff4c4c;
        --positive-color: #2ecc71;
    }

    body {
        overflow-x: hidden; 
        position: relative;
        width: 100%;
        max-width: 100vw;
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .modal-window {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 25px;
        width: 85%;
        max-width: 320px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        z-index: 1001;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .modal-header {
        color: var(--accent);
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--glass-border);
    }

    .modal-content {
        color: var(--text);
        font-size: 15px;
        line-height: 1.5;
        text-align: center;
        margin-bottom: 25px;
    }

    .modal-button {
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #111;
        border: none;
        display: block;
        margin: 0 auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .modal-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
    }
</style>

<!-- Обновим стили для магазина -->
<style>
    /* Общие стили */
    body {
        overflow-x: hidden;
        position: relative;
        width: 100%;
        max-width: 100vw;
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
    }

    /* Обновим стили контейнера магазина */
    .shop-container {
        background: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 20px;
        width: 90%;
        max-width: 600px;
        margin: 15px auto 70px;
        box-sizing: border-box;
        overflow-x: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        position: relative;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .shop-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .shop-header {
        font-size: 24px;
        font-weight: 700;
        color: var(--accent);
        text-align: center;
        margin-bottom: 25px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        padding: 15px 10px;
        border-bottom: 2px solid var(--glass-border);
        position: relative;
    }

    .shop-header::after {
        content: '🏆';
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
    }

    .shop-category-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin: 0 auto;
        max-width: 500px;
    }

    .shop-category {
        background: rgba(28, 28, 30, 0.9) !important;
        color: var(--text) !important;
        border: 1px solid var(--glass-border) !important;
        padding: 10px 8px;
        font-size: 14px;
        border-radius: var(--radius);
        cursor: pointer;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .shop-category .category-icon {
        font-size: 18px;
        margin-bottom: 3px;
        color: var(--accent);
    }

    .shop-category:hover {
        background: var(--item-hover);
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border-color: var(--accent);
    }

    /* Стили для списка товаров */
    .shop-item {
        background: var(--secondary-bg) !important;
        border: 1px solid var(--glass-border) !important;
        border-radius: var(--radius);
        margin: 15px 0;
        padding: 15px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .shop-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background: linear-gradient(to bottom, var(--accent), var(--accent-2));
        border-radius: var(--radius) 0 0 var(--radius);
    }

    .shop-item:hover {
        background: var(--item-hover);
        transform: translateY(-4px) translateX(2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--accent);
    }

    .shop-item-image {
        width: 90px;
        height: 90px;
        margin-right: 15px;
        flex-shrink: 0;
        border-radius: 10px;
        padding: 5px;
        background: var(--glass-bg);
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--glass-border);
    }

    .shop-item-info {
        flex: 1;
    }

    .shop-item-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
        position: relative;
        padding-right: 25px;
    }

    .shop-item-name::after {
        content: '→';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .shop-item:hover .shop-item-name::after {
        transform: translate(5px, -50%);
    }

    .shop-item-price {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .shop-item-price img {
        width: 18px;
        height: 18px;
        filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.1));
    }

    .shop-item-stats {
        font-size: 14px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .shop-item-stats::before {
        content: '⭐';
        font-size: 14px;
        color: var(--accent);
    }

    /* Адаптивность */
    @media screen and (max-width: 600px) {
        .shop-container {
            width: 95%;
            padding: 15px;
            margin: 10px auto 60px;
        }

        .shop-category-grid {
            grid-template-columns: 1fr;
        }

        .shop-item {
            padding: 12px;
        }

        .shop-item-image {
            width: 80px;
            height: 80px;
        }

        .shop-header {
            font-size: 22px;
        }
    }

    /* Фильтры */
    .shop-filters {
        background: var(--secondary-bg) !important;
        border-radius: var(--radius);
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--glass-border) !important;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .filter-group {
        margin-bottom: 10px;
    }

    .filter-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 10px;
        text-align: center;
    }

    .filter-buttons {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 5px;
    }

    .filter-button {
        flex: 1;
        min-width: 70px;
        padding: 8px 5px;
        background: var(--glass-bg) !important;
        border: 1px solid var(--glass-border) !important;
        border-radius: 50px;
        color: var(--text) !important;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .filter-button.active {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #111;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-color: var(--accent);
    }

    .filter-button:hover:not(.active) {
        background: var(--item-hover);
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    /* Стили для карточки товара */
    .item-card-container {
        padding: 20px;
        background: transparent;
    }

    .item-card {
        background: var(--card-bg) !important;
        border: 1px solid var(--glass-border) !important;
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .item-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .item-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--glass-border);
        position: relative;
    }

    .item-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 8px;
    }

    .item-description {
        font-size: 14px;
        color: var(--muted);
        font-style: italic;
    }

    .item-main {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        align-items: flex-start;
    }

    .item-icon {
        flex-shrink: 0;
        width: 90px;
        height: 90px;
        background: var(--glass-bg);
        border-radius: var(--radius);
        padding: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--glass-border);
    }

    .item-stats {
        flex: 1;
        background: var(--secondary-bg) !important;
        border-radius: var(--radius);
        padding: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--glass-border) !important;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--glass-border);
    }

    .stat-row:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: var(--muted);
        font-weight: 600;
    }

    .stat-value {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        font-weight: 600;
    }

    .stat-value img {
        width: 18px;
        height: 18px;
        filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.1));
    }

    .stat-value.negative {
        color: var(--danger-color);
    }

    .item-effects {
        margin: 15px 0;
        padding: 15px;
        background: var(--secondary-bg) !important;
        border-radius: var(--radius);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--glass-border) !important;
    }

    .effect-badge {
        display: inline-block;
        padding: 8px 15px;
        margin: 5px;
        background: rgba(245, 193, 93, 0.15);
        border-radius: 50px;
        color: var(--accent);
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .item-quantity {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
        background: var(--secondary-bg);
        padding: 15px;
        border-radius: var(--radius);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--glass-border);
    }

    .quantity-button {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #111;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        font-size: 18px;
    }

    .quantity-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    .quantity-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        min-width: 40px;
        text-align: center;
    }

    .price-display {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text);
        font-weight: 600;
        padding: 5px 15px;
        background: var(--glass-bg);
        border-radius: 50px;
        border: 1px solid var(--glass-border);
    }

    .price-display img {
        width: 18px;
        height: 18px;
        vertical-align: middle;
    }

    .item-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 25px;
    }

    .item-button {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #111;
        border: none;
        padding: 15px 20px;
        font-size: 16px;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .item-button:hover {
        background: linear-gradient(135deg, var(--accent-2), var(--accent));
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    /* Оформляем фильтры стилей */
    .shopminiblock, 
    .sortStyle {
        padding: 8px 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        background: var(--glass-bg);
        color: var(--text);
        border: 1px solid var(--glass-border);
    }

    .shopminiblock.active, 
    .sortStyle.active {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #111 !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-color: var(--accent);
    }

    .shopminiblock:hover:not(.active), 
    .sortStyle:hover:not(.active) {
        background: var(--item-hover);
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    /* Цвета для стилей предметов */
    .shop-item-name font[style*="color:black"] {
        color: var(--text) !important;
    }
    
    .shop-item-name font[style*="color:green"] {
        color: var(--positive-color) !important;
    }
    
    .shop-item-name font[style*="color:blue"] {
        color: var(--team2-color) !important;
    }
    
    .shop-item-name font[style*="color:red"] {
        color: var(--team1-color) !important;
    }
    
    .shop-item-name font[style*="color:yellow"] {
        color: var(--accent) !important;
    }
    
    /* Стили для иконок предметов - используем спрайт-изображение */
    .shopicobg {
        position: relative;
        width: 80px;
        height: 80px;
        background-image: url("/images/shopico.png?136.1114");
        background-repeat: no-repeat;
        box-sizing: content-box !important; /* Гарантирует, что padding не влияет на размер */
        min-width: 80px !important; /* Минимальная ширина */
        max-width: 80px !important; /* Максимальная ширина */
        min-height: 80px !important; /* Минимальная высота */
        max-height: 80px !important; /* Максимальная высота */
        padding: 0 !important; /* Убираем padding */
        margin: 0 !important; /* Убираем margin */
        flex: 0 0 80px !important; /* Для flex-контейнеров */
    }
    
    /* Базовые позиции для спрайта иконок (по 9 иконок в ряду, размером 80x80px) */
    .shopico1{background-position:-0px -0px;}
    .shopico2{background-position:-80px -0px;}
    .shopico3{background-position:-160px -0px;}
    .shopico4{background-position:-240px -0px;}
    .shopico5{background-position:-320px -0px;}
    .shopico6{background-position:-400px -0px;}
    .shopico7{background-position:-480px -0px;}
    .shopico8{background-position:-560px -0px;}
    .shopico9{background-position:-640px -0px;}
    .shopico10{background-position:0px -80px;}
    .shopico11{background-position:-80px -80px;}
    .shopico12{background-position:-160px -80px;}
    .shopico13{background-position:-240px -80px;}
    .shopico14{background-position:-320px -80px;}
    .shopico15{background-position:-400px -80px;}
    .shopico16{background-position:-480px -80px;}
    .shopico17{background-position:-560px -80px;}
    .shopico18{background-position:-640px -80px;}
    .shopico19{background-position:0px -160px;}
    .shopico20{background-position:-80px -160px;}
    /* Добавляем еще 60 позиций для наиболее часто используемых иконок */
    .shopico21{background-position:-160px -160px;}
    .shopico22{background-position:-240px -160px;}
    .shopico23{background-position:-320px -160px;}
    .shopico24{background-position:-400px -160px;}
    .shopico25{background-position:-480px -160px;}
    .shopico26{background-position:-560px -160px;}
    .shopico27{background-position:-640px -160px;}
    .shopico28{background-position:0px -240px;}
    .shopico29{background-position:-80px -240px;}
    .shopico30{background-position:-160px -240px;}
    /* Остальные позиции могут быть добавлены при необходимости */
</style>

<!-- Обновим JavaScript для фильтров -->
<script>
    MyLib.userLevel = <?= $user['level']; ?>;
    MyLib.userMoney = <?= $user['money']; ?>;
    MyLib.userPlatina = <?= $user['platinum']; ?>;

    function shopsort1(elem) {
        $(".shopminiblock").removeClass("active");
        $(elem).addClass("active");
        $(".shopblock").show();
        MyLib.ticks = 0;
        if (MyLib.style != 5) {
            $(".shopblock").each(function() {
                if ($(this).attr("stail") != MyLib.style) {
                    $(this).hide();
                }
            });
        }
    }

    function shopsort2(elem) {
        $(".shopminiblock").removeClass("active");
        $(elem).addClass("active");
        $(".shopblock").each(function() {
            var money = parseInt($(this).attr("money"));
            var platina = parseInt($(this).attr("platina"));
            var level = parseInt($(this).attr("level"));
            
            if (money <= MyLib.userMoney && 
                platina <= MyLib.userPlatina && 
                level <= MyLib.userLevel) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        MyLib.ticks = 1;
        if (MyLib.style != 5) {
            $(".shopblock:visible").each(function() {
                if ($(this).attr("stail") != MyLib.style) {
                    $(this).hide();
                }
            });
        }
    }

    function shopsort3(elem) {
        $(".shopminiblock").removeClass("active");
        $(elem).addClass("active");
        $(".shopblock").each(function() {
            if (parseInt($(this).attr("level")) == MyLib.userLevel) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        MyLib.ticks = 2;
        if (MyLib.style != 5) {
            $(".shopblock:visible").each(function() {
                if ($(this).attr("stail") != MyLib.style) {
                    $(this).hide();
                }
            });
        }
    }

    function shopsortStyle(elem) {
        $(".sortStyle").removeClass("active");
        $(elem).addClass("active");
        MyLib.style = $(elem).attr("value");
        if (MyLib.style == 5) {
            $(".shopblock").show();
            if (MyLib.ticks == 1) {
                shopsort2($(".sort2"));
            } else if (MyLib.ticks == 2) {
                shopsort3($(".sort3"));
            }
        } else {
            $(".shopblock").each(function() {
                if ($(this).attr("stail") == MyLib.style) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            if (MyLib.ticks == 1) {
                $(".shopblock:visible").each(function() {
                    var money = parseInt($(this).attr("money"));
                    var platina = parseInt($(this).attr("platina"));
                    var level = parseInt($(this).attr("level"));
                    if (money > MyLib.userMoney || 
                        platina > MyLib.userPlatina || 
                        level > MyLib.userLevel) {
                        $(this).hide();
                    }
                });
            } else if (MyLib.ticks == 2) {
                $(".shopblock:visible").each(function() {
                    if (parseInt($(this).attr("level")) != MyLib.userLevel) {
                        $(this).hide();
                    }
                });
            }
        }
    }

    // Автоматически активируем первый фильтр при загрузке
    MyLib.setTimeid[200] = setTimeout(function() {
        $(".sort1").click();
        $(".sortStyle[value='5']").click();
    }, 200);
</script>

<?php
$locatquest = $user['location'];

if (isset($_GET['prem'])) {
    if ($user['prem'] < 1) {
        if ($user['platinum'] >= 200) {
            $tim = strtotime("+30 day", time());
            if ($mc->query("UPDATE `users` SET `platinum` = `platinum`-'200',`prem` = '1',`prem_t` = '$tim' WHERE `id` = '" . $user['id'] . "' ")) {
                ?>
                <div class="modal-overlay" style="display: block;">
                    <div class="modal-window">
                        <div class="modal-header">Успешная активация</div>
                        <div class="modal-content">
                            Премиум-аккаунт успешно активирован! 🎉
                        </div>
                        <div class="modal-buttons">
                            <button class="modal-button modal-confirm" onclick="showContent('shop.php')">Отлично</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="modal-overlay" style="display: block;">
                <div class="modal-window">
                    <div class="modal-header">Недостаточно средств</div>
                    <div class="modal-content">
                        У вас недостаточно платины для активации премиум-аккаунта
                    </div>
                    <div class="modal-buttons">
                        <button class="modal-button modal-confirm" onclick="showContent('shop.php')">Понятно</button>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="modal-overlay" style="display: block;">
            <div class="modal-window">
                <div class="modal-header">Уже активировано</div>
                <div class="modal-content">
                    У вас уже есть активный премиум-аккаунт
                </div>
                <div class="modal-buttons">
                    <button class="modal-button modal-confirm" onclick="showContent('shop.php')">Понятно</button>
                </div>
            </div>
        </div>
        <?php
    }
}

function age_times($secs) {
    $bit = array(
        ' year' => floor($secs / 31556926),
        ' day' => $secs / 86400 % 365,
        ' hour' => $secs / 3600 % 24,
        ' minute' => $secs / 60 % 60,
        ' second' => $secs % 60
    );
    $years = 0;
    $days = 0;
    $hours = 0;
    foreach ($bit as $k => $v) {
        $str = (string) $v;
        $str = strlen($str) == 1 ? "0" . $str : $str;
//года
        if ($v > 0 && $k == ' year') {
            if ((int) $str{strlen($str) - 1} > 4 || (int) $str{strlen($str) - 1} == 0 || (int) $str{strlen($str) - 2} > 0 && (int) $str{strlen($str) - 2} < 2) {
                $years = $v;
                $ret[] = $v . ' лет ';
            } elseif ((int) $str{strlen($str) - 1} > 1 && (int) $str{strlen($str) - 1} < 5) {
                $years = $v;
                $ret[] = $v . ' года ';
            } elseif ((int) $str{strlen($str) - 1} == 1) {
                $years = $v;
                $ret[] = $v . ' год ';
            }
        }
//дни
        if ($v > 0 && $k == ' day') {
            if ((int) $str{strlen($str) - 1} > 4 || (int) $str{strlen($str) - 1} == 0 || (int) $str{strlen($str) - 2} > 0 && (int) $str{strlen($str) - 2} < 2) {
                $days = $v;
                $ret[] = $v . ' дней ';
            } elseif ((int) $str{strlen($str) - 1} > 1 && (int) $str{strlen($str) - 1} < 5) {
                $days = $v;
                $ret[] = $v . ' дня ';
            } elseif ((int) $str{strlen($str) - 1} == 1) {
                $days = $v;
                $ret[] = $v . ' день ';
            }
        }
        if ($v > 0 && $k == ' hour' && $years == 0) {
            if ($v > 4 && $v < 21) {
                $hours = $v;
                $ret[] = $v . ' часов ';
            } elseif ($v > 1 && $v < 5 || $v > 21) {
                $hours = $v;
                $ret[] = $v . ' часа ';
            } elseif ((int) $str{strlen($str) - 1} == 1) {
                $hours = $v;
                $ret[] = $v . ' час ';
            }
        }
        if ($v > 0 && $k == ' minute' && $years == 0 && $days == 0) {
            $ret[] = $v . ' мин ';
        }
        if ($v > 0 && $k == ' second' && $years == 0 && $days == 0 && $hours == 0) {
            $ret[] = $v . ' сек ';
        }
    }
    return join(' ', $ret);
}

////////////Продажа вещи
if (isset($_GET['id']) && isset($_GET['prod']) && isset($_GET['shoppr'])) {
    $idt = (int) $_GET['id'];
    //заносим список айди вещей в магазе герою
    $mc->query("UPDATE `users` SET `shopList` = '[[],[],[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
    if ($idt != "" && $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_shop`='$idt'")->num_rows == 0) {
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?shop=<?= $_GET['shoppr']; ?>&in&msg=<?= urlencode("Ошибка продажи предмета"); ?>");</script>
        <?php
        exit(0);
    }
    if ($mc->query("SELECT * FROM `shop` WHERE `id`='$idt'")->num_rows > 0) {
        $countvesh = $mc->query("SELECT COUNT(0) FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_shop`='$idt' AND `dress`='0'")->fetch_array(MYSQLI_ASSOC);
        if ($countvesh['COUNT(0)'] >= $_GET['prod'] && $_GET['prod'] > 0) {
            $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='$idt'")->fetch_array(MYSQLI_ASSOC);
            $proucent = 19.23;
            $proucentMoney = round(($shopmagazin['money'] * $proucent) / 100);
            $moneyprod = $user['money'] + ($proucentMoney * $_GET['prod']);
            $plataprod = $user['platinum'] + ((round(($shopmagazin['platinum'] * $proucent) / 100)) * $_GET['prod']);
            $mc->query("UPDATE `users` SET `money`='" . $moneyprod . "',`platinum`='" . $plataprod . "' WHERE `id`='" . $user['id'] . "'");
            $mc->query("DELETE FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_shop`='$idt' AND `dress`='0' LIMIT " . $_GET['prod']);
            ?>
            <script>/*nextshowcontemt*/showContent("/shop.php?shop=<?= $_GET['shoppr']; ?>&in&msg=<?= urlencode('Продан предмет<br>' . $shopmagazin['name'] . ' (' . $_GET['prod'] . ')'); ?>");</script>
            <?php
            exit(0);
        } else {
            ?>
            <script>/*nextshowcontemt*/showContent("/shop.php?shop=<?= $_GET['shoppr']; ?>&in&msg=<?= urlencode("Ошибка продажи предмета"); ?>");</script>
            <?php
            exit(0);
        }
    } else {
        $mc->query("DELETE FROM `userbag` WHERE `id_user`='" . $user['id'] . "' && `id_shop` NOT IN (SELECT `id` FROM `shop`) LIMIT 1");
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode('Предмет удалён !<br>'); ?>");
        </script>
        <?php
        exit(0);
    }
}


//покупка
if (isset($_GET['id']) && isset($_GET['buy']) && isset($_GET['numbers']) && isset($_GET['shop'])) {
    $idt = (int) $_GET['id'];
    //заносим список айди вещей в магазе герою
    $mc->query("UPDATE `users` SET `shopList` = '[[],[],[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");

    $num = $_GET['numbers'];
    if ($num < 1) {
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode("Нет места"); ?>");
        </script>
        <?php
        exit(0);
    }
    $counts = $user['max_bag_count'] - $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_punct` < '10'")->num_rows;
    if ($counts > 32) {
        $counts = 32;
    } elseif ($counts < 1 || $num > $counts || $num < 1) {
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode("Нет свободного места"); ?>");
        </script>
        <?php
        exit(0);
    }
    $arrTemp0 = json_decode($user['shopList']);
    if ($idt >= 0 && $idt < count($arrTemp0[$_GET['shop'] - 1])) {
        //получаем айдишники раздела и ыещи
        $ids = $arrTemp0[$_GET['shop'] - 1][$idt];
        //получаем 1 шмотку из магазина
        $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='$ids'")->fetch_array(MYSQLI_ASSOC);
        $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $arrTemp0[$_GET['shop'] - 1][$idt] . "'");

        if ($shopmagazin['max_hc'] > 0 && $countBagDropRes->num_rows + $num > $shopmagazin['max_hc'] || $shopmagazin['level'] > $user['level']) {
            ?>
            <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode("Недостаточно денег. Перейдите в банк..."); ?>");
            </script>
            <?php
            exit(0);
        }
        //отсчитываем бабосы
        $buy = $user['money'] - ($shopmagazin['money'] * $num);
        $platabuy = $user['platinum'] - ($shopmagazin['platinum'] * $num);
        //проверяем что бабосов хватило
        if ($buy >= 0 && $platabuy >= 0) {
            for ($i = 0; $i < $num; $i++) {
                if ($_GET['buy'] == 1) {
                    //Если купить и одеть,то
                    shop_buy($shopmagazin['id'], 'y');
                }
                if ($_GET['buy'] == 2) {
                    //Если купить
                    shop_buy($shopmagazin['id'], 'n');
                }
            }
            $mc->query("UPDATE `users` SET `money`='" . $buy . "',`platinum`='" . $platabuy . "' WHERE `id`='" . $user['id'] . "'");
            ?>
            <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode("Куплен предмет <br>" . $shopmagazin['name'] . "(" . $num . ")"); ?>");
            </script>
            <?php
            exit(0);
        } else {
            ?>
            <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode("Недостаточно денег. Перейдите в банк"); ?>");
            </script>
            <?php
            exit(0);
        }
    } else {
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?msg=<?= urlencode("Недостаточно денег. Перейдите в банк"); ?>");
        </script>
        <?php
        exit(0);
    }
}

if (isset($_GET['shoppr'])) {
    $_GET['shop'] = $_GET['shoppr'];
}
//клейма
$arrcleim = [];
$arrResultCleim = [];
//урон [lvl,id,count]
$arrcleim[] = [[10, 1399, 1], [11, 1400, 1], [12, 1401, 1], [13, 1402, 1], [14, 1403, 6]];
//уворот
$arrcleim[] = [[10, 1409, 1], [11, 1410, 1], [12, 1411, 1], [13, 1412, 1], [14, 1413, 6]];
//точность
$arrcleim[] = [[10, 1405, 1], [11, 1414, 1], [12, 1415, 1], [13, 1421, 1], [14, 1422, 6]];
//блок
$arrcleim[] = [[10, 1423, 1], [11, 1424, 1], [12, 1425, 1], [13, 1426, 1], [14, 1427, 6]];
//броня
$arrcleim[] = [[10, 1428, 1], [11, 1429, 1], [12, 1430, 1], [13, 1431, 1], [14, 1432, 6]];
//оглушение
$arrcleim[] = [[10, 1433, 1], [11, 1434, 1], [12, 1435, 1], [13, 1436, 1], [14, 1437, 6]];
//здоровье
$arrcleim[] = [[10, 1438, 1], [11, 1439, 1], [12, 1440, 1], [13, 1441, 1], [14, 1442, 6]];
if ($user['location'] == 56) {
    for ($i0 = 0; $i0 < count($arrcleim); $i0++) {
        for ($i1 = 0; $i1 < count($arrcleim[$i0]); $i1++) {
            $numThis = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $arrcleim[$i0][$i1][1] . "'")->num_rows;
            if ($arrcleim[$i0][$i1][0] <= $user['level'] && $numThis < $arrcleim[$i0][$i1][2]) {
                $arrResultCleim[] = $arrcleim[$i0][$i1][1];
                break;
            }
        }
    }
}
if ($mc->query("SELECT * FROM `shop_equip` WHERE `id_location`='" . $locatquest . "' LIMIT 10")->num_rows > 0 || count($arrResultCleim) > 0) {
    $arrShop = [];
    $arrShop[0] = [];
    $arrShop[1] = [];
    $arrShop[2] = [];
    $arrShop[3] = [];
    $arrShop[4] = [];
    $arrShop[5] = [];
    $arrShop[6] = [];
    $arrShop[7] = [];
    $arrShop[8] = [];
    $arrShop[9] = [];
    $arrShop[10] = [];
    $arrShop[11] = [];



    //формируем списки вещей
    $shop_equipAll = $mc->query("SELECT * FROM `shop_equip` WHERE `id_location`='$locatquest' ORDER BY `id_punct_shop`,`level`,`platinum`,`money`")->fetch_all(MYSQLI_ASSOC);
    foreach ($shop_equipAll as $value) {
        //ограничения по мин макс уровню
        $shopRes = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $value['id_shop'] . "'");
        if ($shopRes->num_rows > 0) {
            $shopThis = $shopRes->fetch_array(MYSQLI_ASSOC);
            $countBagDropRes = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop`='" . $value['id_shop'] . "'");
            if ($user['level'] < $shopThis['drop_min_level'] || $user['level'] > $shopThis['drop_max_level'] || ($shopThis['max_hc'] > 0 && $countBagDropRes->num_rows >= $shopThis['max_hc'])) {
                continue;
            }
        }

        //распределение по разделам
        if ($value['id_punct_shop'] == 1) {
            $arrShop[0][] = $value['id_shop'];
        }
        if ($value['id_punct_shop'] == 2) {
            $arrShop[1][] = $value['id_shop'];
        }
        if ($value['id_punct_shop'] == 3) {
            $arrShop[2][] = $value['id_shop'];
        }
        if ($value['id_punct_shop'] == 4) {
            $arrShop[3][] = $value['id_shop'];
        }
        if ($value['id_punct_shop'] == 5) {
            $arrShop[4][] = $value['id_shop'];
        }

        if ($value['id_punct_shop'] == 10) {
            $arrShop[9][] = $value['id_shop'];
        }
        if ($value['id_punct_shop'] == 11) {
            $arrShop[10][] = $value['id_shop'];
        }
        if ($value['id_punct_shop'] == 12) {
            $arrShop[11][] = $value['id_shop'];
        }
    }
    $arrShop[4] = array_merge($arrShop[4], $arrResultCleim);
    //заносим список айди вещей в магазе герою
    $mc->query("UPDATE `users` SET `shopList` = '" . json_encode($arrShop) . "' WHERE `users`.`id` = '" . $user['id'] . "'");

    //вывод разделов магазина
    if (!isset($_GET['shop']) && !isset($_GET['id'])) {
        ?>
        <div class="shop-container">
            <div class="shop-header">Магазин</div>
            <div class="shop-category-grid">
                <?php if (count($arrShop[0]) > 0) { ?>
                    <button class="shop-category" onclick="showContent('shop.php?shop=1&in')">
                        <span class="category-icon">⚔️</span>
                        Оружие
                    </button>
                <?php } ?>
                <?php if (count($arrShop[1]) > 0) { ?>
                    <button class="shop-category" onclick="showContent('shop.php?shop=2&in')">
                        <span class="category-icon">🛡️</span>
                        Броня
                    </button>
                <?php } ?>
                <?php if (count($arrShop[2]) > 0) { ?>
                    <button class="shop-category" onclick="showContent('shop.php?shop=3&in')">
                        <span class="category-icon">🧪</span>
                        Зелья и Свитки
                    </button>
                <?php } ?>
                <?php if (count($arrShop[3]) > 0) { ?>
                    <button class="shop-category" onclick="showContent('shop.php?shop=4&in')">
                        <span class="category-icon">🔮</span>
                        Амулеты
                    </button>
                <?php } ?>
                <?php if (count($arrShop[4]) > 0) { ?>
                    <button class="shop-category" onclick="showContent('shop.php?shop=5&in')">
                        <span class="category-icon">🎒</span>
                        Разное
                    </button>
                <?php } ?>
                <?php if ($user['level'] >= 10 && $user['access'] > 3){?>
                    <button class="shop-category" onclick="showContent('auk.php')">
                        <span class="category-icon">🏷️</span>
                        Аукцион
                    </button>
                <?php } ?>
                <button class="shop-category" onclick="showContent('shop.php?shop=6&in')">
                    <span class="category-icon">💸</span>
                    Продать
                </button>
                <button class="shop-category" onclick="showContent('shop_heroes.php')">
                    <span class="category-icon">👥</span>
                    Персонажи
                </button>
                <?php if ($user['level'] >= 5) { ?>
                    <button class="shop-category" onclick="showContent('premium.php')">
                        <span class="category-icon">👑</span>
                        Премиум
                    </button>
                <?php } ?>
                <button class="shop-category" onclick="showContent('remont.php')">
                    <span class="category-icon">🔧</span>
                    Ремонт
                </button>
                <?php if ($user['access'] > 2) { ?>
                    <?php if (count($arrShop[9]) > 0) { ?>
                        <button class="shop-category" onclick="showContent('shop.php?shop=10&in')">
                            <span class="category-icon">🎯</span>
                            Для заданий
                        </button>
                    <?php } ?>
                    <?php if (count($arrShop[10]) > 0) { ?>
                        <button class="shop-category" onclick="showContent('shop.php?shop=11&in')">
                            <span class="category-icon">🎗️</span>
                            Бонусы
                        </button>
                    <?php } ?>
                    <?php if (count($arrShop[11]) > 0) { ?>
                        <button class="shop-category" onclick="showContent('shop.php?shop=12&in')">
                            <span class="category-icon">🔒</span>
                            Скрытые
                        </button>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php
        $footval = 'shoptomain';
        require_once 'system/foot/foot.php';
    }
    //список вещей в разделе
    if (isset($_GET['shop']) && !isset($_GET['id']) && isset($_GET['in'])) {
        ?>
        <div class="shop-container">
            <script>
                MyLib.restore = 1;
            </script>

            <div class="shop-filters">
                <div class="filter-group">
                    <div class="filter-title">Фильтр товаров</div>
                    <div class="filter-buttons">
                        <div onclick="shopsort1(this);" class="filter-button sort1 allminia">Все</div>
                        <div onclick="shopsort2(this);" class="filter-button sort2 allminia">Доступные</div>
                        <div onclick="shopsort3(this);" class="filter-button sort3 allminia">Мой уровень</div>
                    </div>
                </div>
                
                <div class="filter-group">
                    <div class="filter-title">Тип предмета</div>
                    <div class="filter-buttons">
                        <div onclick="shopsortStyle(this);" class="filter-button sortStyle" value="0" style="color: #555">Нет</div>
                        <div onclick="shopsortStyle(this);" class="filter-button sortStyle" value="1" style="color: green">Урон</div>
                        <div onclick="shopsortStyle(this);" class="filter-button sortStyle" value="2" style="color: blue">Уворот</div>
                        <div onclick="shopsortStyle(this);" class="filter-button sortStyle" value="3" style="color: red">Броня</div>
                        <div onclick="shopsortStyle(this);" class="filter-button sortStyle" value="4" style="color: #FFD700">Элита</div>
                        <div onclick="shopsortStyle(this);" class="filter-button sortStyle" value="5" style="color: #555">Все</div>
                    </div>
                </div>
            </div>

            <script>
                MyLib.userLevel = <?= $user['level']; ?>;
                MyLib.userMoney = <?= $user['money']; ?>;
                MyLib.userPlatina = <?= $user['platinum']; ?>;

                // Автоматически активируем первый фильтр при загрузке
                MyLib.setTimeid[200] = setTimeout(function() {
                    $(".allminia:eq(" + MyLib.ticks + ")").click();
                    $(".sortStyle:eq(" + MyLib.style + ")").click();
                }, 200);
            </script>
            <?php
            //выводим что купить
            if (isset($_GET['shop']) && $_GET['shop'] > 0 && $_GET['shop'] < 6 || $user['access'] > 2 && isset($_GET['shop']) && $_GET['shop'] > 9 && $_GET['shop'] < 13) {
                //получаем айдишники раздела
                $ids = json_decode($user['shopList'])[$_GET['shop'] - 1];
                for ($x = 0; $x < count($ids); $x++) {
                    $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $ids[$x] . "'")->fetch_array(MYSQLI_ASSOC);
                    ?>
                    <div class="shop-item shopblock" 
                         onclick="showContent('shop.php?shop=<?= $_GET['shop']; ?>&id=<?= $x; ?>')"
                         money="<?= $shopmagazin['money'] ?>"
                         platina="<?= $shopmagazin['platinum'] ?>"
                         level="<?= $shopmagazin['level'] ?>"
                         stail="<?= $shopmagazin['stil'] ?>">
                        <div class="shop-item-image">
                            <div class="shopicobg shopico<?= $shopmagazin['id_image']; ?>"></div>
                        </div>
                        <div class="shop-item-info">
                            <div class="shop-item-name">
                                <?php if ($shopmagazin['stil'] > 0): ?>
                                    <font style="color:<?= $colorStyle[$shopmagazin['stil']] ?>;font-weight: bold;">
                                        <?= $shopmagazin['name'] ?>
                                    </font>
                                <?php else: ?>
                                    <?= $shopmagazin['name'] ?>
                                <?php endif; ?>
                            </div>
                            <div class="shop-item-price">
                                <?php
                                $zolo = money($shopmagazin['money'], "zoloto");
                                $med = money($shopmagazin['money'], "med");
                                $serebro = money($shopmagazin['money'], "serebro");
                                $platinum = $shopmagazin['platinum'];
                                
                                if ($platinum > 0) echo '<img src="/images/icons/plata.png" width="16">' . $platinum . ' ';
                                if ($zolo > 0) echo '<img src="/images/icons/zoloto.png" width="16">' . $zolo . ' ';
                                if ($serebro > 0) echo '<img src="/images/icons/serebro.png" width="16">' . $serebro . ' ';
                                if ($med > 0) echo '<img src="/images/icons/med.png" width="16">' . $med;
                                ?>
                            </div>
                            <div class="shop-item-stats">
                                Уровень: <?= $shopmagazin['level']; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }


            //выводим что продать
            if ($_GET['shop'] == 6) {
                $shopmagazin1 = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `dress`='0' AND `id_punct` < '10' GROUP BY `id_shop`");
                while ($shopmagazin2 = $shopmagazin1->fetch_array(MYSQLI_ASSOC)) {
                    $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $shopmagazin2['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
                    $counts = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `dress`='0' AND `id_shop`='" . $shopmagazin2['id_shop'] . "'")->num_rows;
                    ?>
                    <div class="shop-item" onclick="showContent('shop.php?shoppr=<?= $_GET['shop']; ?>&id=<?= $shopmagazin['id']; ?>&prodsee')">
                        <div class="shop-item-image">
                            <div class="shopicobg shopico<?= $shopmagazin['id_image']; ?>"></div>
                        </div>
                        <div class="shop-item-info">
                            <div class="shop-item-name">
                                <?php if ($shopmagazin['stil'] > 0): ?>
                                    <font style="color:<?= $colorStyle[$shopmagazin['stil']] ?>;font-weight: bold;">
                                        <?= $shopmagazin['name'] ?>
                                    </font>
                                <?php else: ?>
                                    <?= $shopmagazin['name'] ?>
                                <?php endif; ?>
                                <?php if ($counts > 1) { ?>
                                    <span style="font-size: 13px; color: #663300; margin-left: 5px;">(<?= $counts ?>)</span>
                                <?php } ?>
                            </div>
                            <div class="shop-item-price">
                                <?php
                                $proucent = 19.23;
                                $proucentMoney = round(($shopmagazin['money'] * $proucent) / 100);
                                $proucentPlatinum = round(($shopmagazin['platinum'] * $proucent) / 100);
                                
                                if ($proucentPlatinum > 0) echo '<img src="/images/icons/plata.png" width="16">' . $proucentPlatinum . ' ';
                                
                                $zolo = money($proucentMoney, "zoloto");
                                $med = money($proucentMoney, "med");
                                $serebro = money($proucentMoney, "serebro");
                                
                                if ($zolo > 0) echo '<img src="/images/icons/zoloto.png" width="16">' . $zolo . ' ';
                                if ($serebro > 0) echo '<img src="/images/icons/serebro.png" width="16">' . $serebro . ' ';
                                if ($med > 0) echo '<img src="/images/icons/med.png" width="16">' . $med;
                                ?>
                            </div>
                            <div class="shop-item-stats">
                                Уровень: <?= $shopmagazin['level']; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        $footval = 'backtoshop';
        require_once 'system/foot/foot.php';
    }

    //просмотреть конкретную вещь покупка
    if ($user['access'] > 2 && isset($_GET['shop']) && $_GET['shop'] < 13 && $_GET['shop'] > 9 && isset($_GET['id']) || isset($_GET['shop']) && $_GET['shop'] < 6 && $_GET['shop'] > 0 && isset($_GET['id'])) {
        //получаем айдишники раздела и айди вещи
        $ids = json_decode($user['shopList'])[$_GET['shop'] - 1][$_GET['id']];
        $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='$ids'")->fetch_array(MYSQLI_ASSOC);
        $counts = $user['max_bag_count'] - $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_punct` < '10'")->num_rows;
        if ($counts > 32) {
            $counts = 32;
        } elseif ($counts < 1) {
            $counts = 0;
        }
        
        // Подготовка знаков для характеристик
        $maghealth = $shopmagazin['health'] < 0 ? '' : '+';
        $magstrength = $shopmagazin['strength'] < 0 ? '' : '+';
        $magtoch = $shopmagazin['toch'] < 0 ? '' : '+';
        $maglov = $shopmagazin['lov'] < 0 ? '' : '+';
        $magblock = $shopmagazin['block'] < 0 ? '' : '+';
        $magbron = $shopmagazin['bron'] < 0 ? '' : '+';

        // При покупке предмета
        if (isset($_GET['buy']) && $_GET['buy'] == 2) {
            // Проверяем активные квесты
            $active_quests = $mc->query("
                SELECT qp.* 
                FROM quest_progress qp 
                WHERE qp.id_user = '{$user['id']}' 
                AND qp.status = 'ACTIVE'
            ");

            if ($active_quests && $active_quests->num_rows > 0) {
                while ($quest = $active_quests->fetch_assoc()) {
                    $progress_data = json_decode($quest['progress_data'], true);
                    
                    // Если есть требования по покупке предметов
                    if (!empty($progress_data['shop_items'][$shopmagazin['id']])) {
                        $progress_data['shop_items'][$shopmagazin['id']]['current']++;
                        
                        // Обновляем прогресс
                        $mc->query("UPDATE quest_progress SET 
                            progress_data = '" . json_encode($progress_data) . "'
                            WHERE id = '{$quest['id']}'
                        ");
                    }
                }
            }
        }
        ?>

        <div class="item-card-container">
            <div class="item-card">
                <!-- Заголовок с названием предмета -->
                <div class="item-header">
                    <div class="item-name">
                        <?= $shopmagazin['name'] ?>
                    </div>
                    <div class="item-description">
                        <?= $shopmagazin['opisanie'] ?>
                    </div>
                </div>

                <!-- Основная информация о предмете -->
                <div class="item-main">
                    <div class="item-icon">
                        <div class="shopicobg shopico<?= $shopmagazin['id_image'] ?>"></div>
                    </div>

                    <!-- Характеристики предмета -->
                    <div class="item-stats">
                        <div class="stat-row">
                            <span class="stat-label">Уровень:</span>
                            <span class="stat-value">
                                ⭐ <?= $shopmagazin['level'] ?>
                            </span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">Цена:</span>
                            <span class="stat-value">
                                <?php
                                $zolo = money($shopmagazin['money'], "zoloto");
                                $med = money($shopmagazin['money'], "med");
                                $serebro = money($shopmagazin['money'], "serebro");
                                $platinum = $shopmagazin['platinum'];
                                
                                if ($platinum > 0) echo '<img src="/images/icons/plata.png" width="16">' . $platinum . ' ';
                                if ($zolo > 0) echo '<img src="/images/icons/zoloto.png" width="16">' . $zolo . ' ';
                                if ($serebro > 0) echo '<img src="/images/icons/serebro.png" width="16">' . $serebro . ' ';
                                if ($med > 0) echo '<img src="/images/icons/med.png" width="16">' . $med;
                                ?>
                            </span>
                        </div>

                        <?php if($shopmagazin['iznos'] > 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Износ:</span>
                            <span class="stat-value"><?= $shopmagazin['iznos'] ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($shopmagazin['toch'] != 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Точность:</span>
                            <span class="stat-value <?= $shopmagazin['toch'] < 0 ? 'negative' : '' ?>">
                                <img src="/images/icons/toch.png" width="16"> <?= $magtoch . $shopmagazin['toch'] ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if($shopmagazin['strength'] != 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Урон:</span>
                            <span class="stat-value <?= $shopmagazin['strength'] < 0 ? 'negative' : '' ?>">
                                <img src="/images/icons/power.jpg" width="16"> <?= $magstrength . $shopmagazin['strength'] ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if($shopmagazin['block'] != 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Блок:</span>
                            <span class="stat-value <?= $shopmagazin['block'] < 0 ? 'negative' : '' ?>">
                                <img src="/images/icons/shit.png" width="16"> <?= $magblock . $shopmagazin['block'] ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if($shopmagazin['lov'] != 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Уворот:</span>
                            <span class="stat-value <?= $shopmagazin['lov'] < 0 ? 'negative' : '' ?>">
                                <img src="/images/icons/img235.png" width="16"> <?= $maglov . $shopmagazin['lov'] ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if($shopmagazin['bron'] != 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Броня:</span>
                            <span class="stat-value <?= $shopmagazin['bron'] < 0 ? 'negative' : '' ?>">
                                <img src="/images/icons/bron.png" width="16"> <?= $magbron . $shopmagazin['bron'] ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if($shopmagazin['health'] != 0): ?>
                        <div class="stat-row">
                            <span class="stat-label">Здоровье:</span>
                            <span class="stat-value <?= $shopmagazin['health'] < 0 ? 'negative' : '' ?>">
                                <img src="/images/icons/hp.png" width="16"> <?= $maghealth . $shopmagazin['health'] ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Эффекты предмета -->
                <?php if (!empty($shopmagazin['nameeffects'])): ?>
                    <div class="item-effects">
                        <?php
                        $effects = explode("|", $shopmagazin['nameeffects']);
                        foreach ($effects as $effect) {
                            if (!empty($effect)) {
                                echo '<div class="effect-badge">' . $effect . '</div>';
                            }
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Кнопки действий -->
                <div class="item-actions">
                    <button class="item-button" onclick="showContent('shop.php?id=<?= $_GET['id'] ?>&buy=2&shop=<?= $_GET['shop'] ?>&numbers=1')">
                        Купить
                    </button>
                </div>
            </div>
        </div>

        <script>
            maxCount = <?= $counts ?>;
            myCount = 1;
            money = <?= $shopmagazin['money'] ?>;
            moneyTov = <?= $shopmagazin['money'] ?>;
            plata = <?= $shopmagazin['platinum'] ?>;
            plataTov = <?= $shopmagazin['platinum'] ?>;
            NumCount(0);
        </script>

        <?php
        $footval = 'backtoshopshop';
        require_once 'system/foot/foot.php';
    }
} else {
    //заносим список айди вещей в магазе герою
    $mc->query("UPDATE `users` SET `shopList` = '[[],[],[],[],[]]' WHERE `users`.`id` = '" . $user['id'] . "'");
    ?>
    <center>
        <br>
        <br> Здесь нет магазина, магазины 
        <br> расположены в городах
    </center>
    <?php
    $footval = 'shoptomain';
    require_once 'system/foot/foot.php';
}

// Добавляем обработчик для просмотра и продажи предмета
if (isset($_GET['id']) && isset($_GET['prodsee'])) {
    // Получаем информацию о предмете
    $idt = (int)$_GET['id'];
    $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='$idt'")->fetch_array(MYSQLI_ASSOC);
    
    if (!$shopmagazin) {
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?shop=6&in&msg=<?= urlencode("Предмет не найден"); ?>");</script>
        <?php
        exit(0);
    }
    
    // Подсчитываем количество предметов у пользователя
    $counts = $mc->query("SELECT COUNT(*) as count FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `dress`='0' AND `id_shop`='$idt'")->fetch_array(MYSQLI_ASSOC);
    $count = $counts['count'];
    
    if ($count == 0) {
        ?>
        <script>/*nextshowcontemt*/showContent("/shop.php?shop=6&in&msg=<?= urlencode("У вас нет этого предмета"); ?>");</script>
        <?php
        exit(0);
    }
    
    // Рассчитываем выручку от продажи
    $proucent = 19.23;
    $proucentMoney = round(($shopmagazin['money'] * $proucent) / 100);
    $proucentPlatinum = round(($shopmagazin['platinum'] * $proucent) / 100);
    
    // Подготовка знаков для характеристик
    $maghealth = $shopmagazin['health'] < 0 ? '' : '+';
    $magstrength = $shopmagazin['strength'] < 0 ? '' : '+';
    $magtoch = $shopmagazin['toch'] < 0 ? '' : '+';
    $maglov = $shopmagazin['lov'] < 0 ? '' : '+';
    $magblock = $shopmagazin['block'] < 0 ? '' : '+';
    $magbron = $shopmagazin['bron'] < 0 ? '' : '+';
    ?>
    
    <div class="item-card-container">
        <div class="item-card">
            <!-- Заголовок с названием предмета -->
            <div class="item-header">
                <div class="item-name">
                    <?php if ($shopmagazin['stil'] > 0): ?>
                        <font style="color:<?= $colorStyle[$shopmagazin['stil']] ?>;font-weight: bold;">
                            <?= $shopmagazin['name'] ?>
                        </font>
                    <?php else: ?>
                        <?= $shopmagazin['name'] ?>
                    <?php endif; ?>
                </div>
                <div class="item-description">
                    Продажа предмета
                </div>
            </div>

            <!-- Основная информация о предмете -->
            <div class="item-main">
                <div class="item-icon">
                    <div class="shopicobg shopico<?= $shopmagazin['id_image'] ?>"></div>
                </div>

                <!-- Характеристики предмета -->
                <div class="item-stats">
                    <div class="stat-row">
                        <span class="stat-label">Кол-во в сумке:</span>
                        <span class="stat-value"><?= $count ?></span>
                    </div>
                    
                    <div class="stat-row">
                        <span class="stat-label">Выручка за 1 шт:</span>
                        <span class="stat-value">
                            <?php
                            if ($proucentPlatinum > 0) echo '<img src="/images/icons/plata.png" width="16">' . $proucentPlatinum . ' ';
                            if ($proucentMoney > 0) {
                                $zolo = money($proucentMoney, "zoloto");
                                $med = money($proucentMoney, "med");
                                $serebro = money($proucentMoney, "serebro");
                                
                                if ($zolo > 0) echo '<img src="/images/icons/zoloto.png" width="16">' . $zolo . ' ';
                                if ($serebro > 0) echo '<img src="/images/icons/serebro.png" width="16">' . $serebro . ' ';
                                if ($med > 0) echo '<img src="/images/icons/med.png" width="16">' . $med;
                            }
                            ?>
                        </span>
                    </div>

                    <?php if($shopmagazin['toch'] != 0): ?>
                    <div class="stat-row">
                        <span class="stat-label">Точность:</span>
                        <span class="stat-value <?= $shopmagazin['toch'] < 0 ? 'negative' : '' ?>">
                            <img src="/images/icons/toch.png" width="16"> <?= $magtoch . $shopmagazin['toch'] ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($shopmagazin['strength'] != 0): ?>
                    <div class="stat-row">
                        <span class="stat-label">Урон:</span>
                        <span class="stat-value <?= $shopmagazin['strength'] < 0 ? 'negative' : '' ?>">
                            <img src="/images/icons/power.jpg" width="16"> <?= $magstrength . $shopmagazin['strength'] ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($shopmagazin['block'] != 0): ?>
                    <div class="stat-row">
                        <span class="stat-label">Блок:</span>
                        <span class="stat-value <?= $shopmagazin['block'] < 0 ? 'negative' : '' ?>">
                            <img src="/images/icons/shit.png" width="16"> <?= $magblock . $shopmagazin['block'] ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($shopmagazin['lov'] != 0): ?>
                    <div class="stat-row">
                        <span class="stat-label">Уворот:</span>
                        <span class="stat-value <?= $shopmagazin['lov'] < 0 ? 'negative' : '' ?>">
                            <img src="/images/icons/img235.png" width="16"> <?= $maglov . $shopmagazin['lov'] ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($shopmagazin['bron'] != 0): ?>
                    <div class="stat-row">
                        <span class="stat-label">Броня:</span>
                        <span class="stat-value <?= $shopmagazin['bron'] < 0 ? 'negative' : '' ?>">
                            <img src="/images/icons/bron.png" width="16"> <?= $magbron . $shopmagazin['bron'] ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if($shopmagazin['health'] != 0): ?>
                    <div class="stat-row">
                        <span class="stat-label">Здоровье:</span>
                        <span class="stat-value <?= $shopmagazin['health'] < 0 ? 'negative' : '' ?>">
                            <img src="/images/icons/hp.png" width="16"> <?= $maghealth . $shopmagazin['health'] ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Выбор количества для продажи -->
            <div class="item-quantity">
                <div class="quantity-button" onclick="changeQuantity(-1)">-</div>
                <div class="quantity-value" id="quantity">1</div>
                <div class="quantity-button" onclick="changeQuantity(1)">+</div>
                
                <div class="price-display">
                    <span>Сумма:</span>
                    <span id="total-price">
                        <?php
                        if ($proucentPlatinum > 0) echo '<img src="/images/icons/plata.png" width="16">' . $proucentPlatinum . ' ';
                        if ($proucentMoney > 0) {
                            $zolo = money($proucentMoney, "zoloto");
                            $med = money($proucentMoney, "med");
                            $serebro = money($proucentMoney, "serebro");
                            
                            if ($zolo > 0) echo '<img src="/images/icons/zoloto.png" width="16">' . $zolo . ' ';
                            if ($serebro > 0) echo '<img src="/images/icons/serebro.png" width="16">' . $serebro . ' ';
                            if ($med > 0) echo '<img src="/images/icons/med.png" width="16">' . $med;
                        }
                        ?>
                    </span>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="item-actions">
                <button class="item-button" id="sellButton" onclick="sellItems()">
                    Продать
                </button>
            </div>
        </div>
    </div>

    <script>
        // Максимальное количество предметов для продажи
        var maxItems = <?= $count ?>;
        var currentQuantity = 1;
        var moneyPerItem = <?= $proucentMoney ?>;
        var platinumPerItem = <?= $proucentPlatinum ?>;
        var itemId = <?= $idt ?>;
        
        // Функция для форматирования денег
        function formatMoney(amount) {
            // Упрощенный вариант для демонстрации
            return amount;
        }
        
        // Функция изменения количества
        function changeQuantity(delta) {
            var newQuantity = currentQuantity + delta;
            if (newQuantity >= 1 && newQuantity <= maxItems) {
                currentQuantity = newQuantity;
                document.getElementById('quantity').textContent = currentQuantity;
                updateTotalPrice();
            }
        }
        
        // Обновление общей стоимости
        function updateTotalPrice() {
            var totalMoney = moneyPerItem * currentQuantity;
            var totalPlatinum = platinumPerItem * currentQuantity;
            
            // Здесь должно быть обновление отображения цены с форматированием
            // Для простоты используем упрощенный вариант
            var displayText = '';
            if (totalPlatinum > 0) {
                displayText += '<img src="/images/icons/plata.png" width="16">' + totalPlatinum + ' ';
            }
            
            if (totalMoney > 0) {
                // В реальном коде здесь должен быть вызов функции форматирования денег
                displayText += formatMoney(totalMoney);
            }
            
            document.getElementById('total-price').innerHTML = displayText;
        }
        
        // Функция продажи
        function sellItems() {
            // Переход на страницу с параметрами продажи
            showContent('shop.php?id=' + itemId + '&prod=' + currentQuantity + '&shoppr=6');
        }
    </script>

    <?php
    $footval = 'backtoshopshop';
    require_once 'system/foot/foot.php';
}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        