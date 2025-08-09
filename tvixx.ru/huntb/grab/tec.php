<?php
require_once ('../../system/func.php');
require_once ('../../system/dbc.php');
?>
<center>
    нет боёв
</center>

<style>
    .robberies_container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }
    
    .robberies_title {
        font-size: 18px;
        font-weight: bold;
        color: #643201;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(232, 207, 153, 0.3);
    }
    
    .no_robberies {
        background: rgba(255, 215, 0, 0.07);
        border-radius: 10px;
        padding: 30px 20px;
        color: #a56c2e;
        font-style: italic;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }
    
    .robbery_list {
        display: grid;
        gap: 15px;
    }
    
    .robbery_item {
        background: rgba(255, 215, 0, 0.07);
        border-radius: 10px;
        padding: 15px;
        text-align: left;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border-left: 3px solid rgba(139, 69, 19, 0.3);
    }
    
    .robbery_details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px solid rgba(232, 207, 153, 0.3);
    }
    
    .robbery_opponent {
        font-weight: bold;
        color: #643201;
    }
    
    .robbery_status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .status_active {
        background: rgba(0, 128, 0, 0.1);
        color: #006400;
    }
    
    .status_won {
        background: rgba(0, 128, 0, 0.1);
        color: #006400;
    }
    
    .status_lost {
        background: rgba(255, 0, 0, 0.1);
        color: #8B0000;
    }
    
    .robbery_actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }
    
    .robbery_button {
        background: linear-gradient(to bottom, #a56c2e, #8B4513);
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        font-weight: bold;
        border: none;
        box-shadow: 0 2px 5px rgba(139, 69, 19, 0.2);
        font-size: 12px;
    }
    
    .robbery_button:hover {
        background: linear-gradient(to bottom, #8B4513, #643201);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(139, 69, 19, 0.3);
    }
    
    @media (max-width: 768px) {
        .robberies_container {
            padding: 15px;
        }
        
        .robbery_item {
            padding: 12px;
        }
    }
    
    @media (max-width: 480px) {
        .robbery_details {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .robbery_status {
            align-self: flex-start;
        }
    }
</style>

<div class="robberies_container">
    <div class="robberies_title">Текущие грабежи</div>
    
    <div class="no_robberies">
        Нет активных грабежей
    </div>
    
    <!-- Примерный шаблон отображения грабежей, когда они будут реализованы:
    <div class="robbery_list">
        <div class="robbery_item">
            <div class="robbery_details">
                <div class="robbery_opponent">Игрок: Название [10]</div>
                <div class="robbery_status status_active">В процессе</div>
            </div>
            <div>Начало: 20.06.2023 15:30</div>
            <div class="robbery_actions">
                <button class="robbery_button">Открыть</button>
            </div>
        </div>
    </div>
    -->
</div>

<?php
$footval = 'grab_huntb_tec';
require_once ('../../system/foot/foot.php');
