<?php

require_once ('../../system/func.php');
require_once ('../../system/dbc.php');
?>

<style>
    .grab_container {
        max-width: 800px;
        margin: 0 auto;
        padding: 15px;
    }

    .grab_menu {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 25px;
    }

    .grab_button {
        background: linear-gradient(to bottom, #a56c2e, #8B4513);
        color: white;
        padding: 14px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        box-shadow: 0 4px 10px rgba(139, 69, 19, 0.2);
        font-size: 15px;
    }

    .grab_button:hover {
        background: linear-gradient(to bottom, #8B4513, #643201);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(139, 69, 19, 0.3);
    }
    
    .grab_button:active {
        transform: translateY(1px);
        box-shadow: 0 2px 5px rgba(139, 69, 19, 0.2);
    }

    .grab_info {
        background: rgba(255, 215, 0, 0.07);
        border-radius: 10px;
        padding: 20px;
        color: #4A2601;
        line-height: 1.6;
        margin-top: 20px;
        border-left: 3px solid rgba(139, 69, 19, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }
    
    .grab_title {
        margin-bottom: 12px;
        font-weight: bold;
        font-size: 17px;
        color: #663300;
        border-bottom: 1px solid rgba(232, 207, 153, 0.3);
        padding-bottom: 8px;
    }
    
    /* Адаптивность для мобильных устройств */
    @media (max-width: 768px) {
        .grab_container {
            padding: 10px;
        }
        
        .grab_menu {
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .grab_button {
            padding: 12px 15px;
            font-size: 14px;
        }
    }
    
    @media (max-width: 480px) {
        .grab_menu {
            grid-template-columns: 1fr;
        }
        
        .grab_info {
            padding: 15px;
        }
        
        .grab_title {
            font-size: 16px;
        }
        
        .grab_button {
            font-size: 13px;
        }
    }
</style>

<div class="grab_container">
    <div class="grab_menu">
        <button class="grab_button" onclick="showContent('/huntb/grab/tec.php')">Текущие</button>
        <button class="grab_button" onclick="showContent('/huntb/grab/search.php')">Напасть</button>
    </div>

    <div class="grab_info">
        <div class="grab_title">Информация о грабежах:</div>
        <div>
            Нападайте на других игроков и получайте награду за победу. 
            Следите за текущими грабежами в разделе "Текущие".
        </div>
    </div>
</div>

<?php

$footval = 'huntb1x1';
require_once ('../../system/foot/foot.php');
