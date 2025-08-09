<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>/*nextshowcontemt*/showContent("/");</script><?php
    exit(0);
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

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
        --panel-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        --success-gradient: linear-gradient(135deg, #2ecc71, #27ae60);
        --danger-gradient: linear-gradient(135deg, #e74c3c, #c0392b);
        --primary-gradient: linear-gradient(135deg, var(--accent), var(--accent-2));
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .location-panel {
        max-width: 900px;
        margin: 0 auto;
    }
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .section {
        margin-bottom: 25px;
    }
    
    .button-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .button_alt_01 {
        background: var(--primary-gradient);
        color: #111;
        padding: 20px;
        border: none;
        border-radius: var(--radius);
        text-align: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100px;
    }
    
    .button_alt_01:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    .button_alt_01::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }
    
    .button_alt_01:hover::before {
        transform: translateX(100%);
    }
    
    @media (max-width: 600px) {
        .button-grid {
            grid-template-columns: 1fr;
        }
        
        .button_alt_01 {
            height: 80px;
        }
    }
</style>

<div class="location-panel">
    <h2>Редактор локации</h2>
    
    <div class="section">
        <div class="button-grid">
            <div class="button_alt_01" onclick="showContent('/admin/location/edit.php?func=add')">Создать локацию</div>
            <div class="button_alt_01" onclick="showContent('/admin/location/edit.php?func=allloc')">Редактировать локации</div>
        </div>
    </div>
</div>

<?php $footval='adminlocindex'; include '../../system/foot/foot.php';?>