<?php
require_once '../system/func.php';
if ($clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC)) {
    $arrTotemStat = [
        ["I", "", "1", "", "", "", "", "10", "0"],
        ["II", "", "1", "", "", "", "1", "15", "500"],
        ["III", "1", "2", "", "", "", "2", "25", "1000"],
        ["IV", "1", "2", "", "", "1", "3", "35", "1500"],
        ["V", "2", "3", "2", "", "2", "4", "50", "2000"],
        ["VI", "2", "4", "5", "", "2", "4", "70", "2500"],
        ["VII", "3", "5", "5", "", "3", "5", "95", "3000"],
        ["VIII", "3", "6", "5", "1", "3", "5", "125", "4000"],
        ["IX", "4", "7", "5", "2", "4", "6", "165", "5000"],
        ["X", "5", "8", "5", "3", "4", "6", "210", "6000"],
        ["XI", "6", "9", "5", "4", "4", "7", "260", "7000"],
        ["XII", "7", "10", "10", "5", "5", "10", "315", "8000"],
    ];

    if(isset($_GET['kupit'])) {
        if($user['des'] == 3 && $clan['totem'] != 11) {
            if($clan['gold'] >= $arrTotemStat[$clan['totem']+1][8]) {
                if($mc->query("UPDATE `clan` SET `totem` = `totem`+ '1',`totemtec` = `totemtec` + '1',`gold` = `gold` - '".$arrTotemStat[$clan['totem']+1][8]."' WHERE `id` = '".$user['id_clan']."'")) {
                    ?><script>showContent("../main.php?msg=Успешно куплен тотем")</script><?php
                }
            } else {
                message("Недостаточно средств в казне");
            }
        } else {
            message("недостаточно прав для покупки или тотем равен 11");
        }
    }
    ?>
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
        --table-header: rgba(255,255,255,0.1);
        --table-row-alt: rgba(255,255,255,0.02);
        --table-row-hover: rgba(255,255,255,0.07);
        --team1-color: #e74c3c;
        --team2-color: #3498db;
        --danger-color: #ff4c4c;
        --positive-color: #2ecc71;
        --highlight-bg: rgba(245, 193, 93, 0.15);
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

    .totem_container {
        max-width: 800px;
        margin: 15px auto;
        padding: 0 15px;
        animation: fadeIn 0.5s ease-out;
    }

    .totem_header {
        font-size: 22px;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 20px;
        text-align: center;
        letter-spacing: 0.5px;
        position: relative;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .totem_header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 1px;
        background: linear-gradient(to right, transparent, var(--glass-border), transparent);
    }

    .totem_header i {
        margin-right: 8px;
    }

    .totem_table {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(8px);
        overflow: hidden;
    }

    .totem_table:hover {
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        transform: translateY(-2px);
    }

    .totem_table_header {
        display: grid;
        grid-template-columns: 0.5fr repeat(8, 1fr);
        gap: 12px;
        padding: 12px 8px;
        margin-bottom: 5px;
        border-bottom: 1px solid var(--glass-border);
        font-weight: 600;
        color: var(--accent);
        background: var(--secondary-bg);
        border-radius: 8px 8px 0 0;
    }

    .totem_row {
        display: grid;
        grid-template-columns: 0.5fr repeat(8, 1fr);
        gap: 12px;
        padding: 12px 8px;
        border-bottom: 1px solid var(--glass-border);
        color: var(--text);
        transition: all 0.3s;
    }

    .totem_row:last-child {
        border-bottom: none;
    }

    .totem_row:hover {
        background: var(--item-hover);
        transform: translateX(5px);
        border-radius: 8px;
    }

    .totem_row.current {
        background: var(--highlight-bg);
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .totem_row.inactive {
        opacity: 0.5;
    }

    .totem_cell {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .totem_cell:first-child {
        color: var(--accent);
        font-weight: 600;
    }

    .totem_icon {
        width: 20px;
        height: 20px;
        filter: brightness(1.2);
        transition: transform 0.3s;
    }

    .totem_row:hover .totem_icon {
        transform: scale(1.15);
    }

    .totem_button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 15px auto;
        padding: 12px 24px;
        background: var(--accent-2);
        color: #111;
        border: none;
        border-radius: var(--radius);
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 250px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .totem_button i {
        margin-right: 10px;
    }

    .totem_button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        background: #ff6a33;
    }

    .totem_button:active {
        transform: translateY(1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .totem_costs {
        color: var(--muted);
        text-align: center;
        margin-top: 5px;
        font-size: 14px;
    }

    .totem_costs span {
        color: var(--accent);
        font-weight: 600;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .totem_table_header,
        .totem_row {
            grid-template-columns: 0.5fr repeat(4, 1fr);
            gap: 8px;
        }
        
        .totem_cell:nth-child(n+6):nth-child(-n+9) {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .totem_container {
            padding: 0 10px;
            margin: 10px auto;
        }
        
        .totem_header {
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .totem_table {
            padding: 15px 10px;
        }
        
        .totem_table_header,
        .totem_row {
            padding: 10px 5px;
            gap: 5px;
            font-size: 13px;
            grid-template-columns: 0.5fr repeat(3, 1fr);
        }

        .totem_cell:nth-child(n+5):nth-child(-n+9) {
            display: none;
        }

        .totem_icon {
            width: 16px;
            height: 16px;
        }
        
        .totem_button {
            padding: 10px 20px;
            font-size: 14px;
            min-width: 200px;
        }
    }
    </style>

    <div class="totem_container">
        <div class="totem_header">
            <i class="fas fa-totem-pole"></i> Тотем клана
        </div>
        
        <div class="totem_table">
            <div class="totem_table_header">
                <div class="totem_cell">Ур.</div>
                <div class="totem_cell"><img src="/images/icons/toch.png" class="totem_icon" alt="Точность"></div>
                <div class="totem_cell"><img src="/images/icons/power.jpg" class="totem_icon" alt="Сила"></div>
                <div class="totem_cell"><img src="/images/icons/shit.png" class="totem_icon" alt="Защита"></div>
                <div class="totem_cell"><img src="/images/icons/kd.png" class="totem_icon" alt="Крит"></div>
                <div class="totem_cell"><img src="/images/icons/img235.png" class="totem_icon" alt="Ловкость"></div>
                <div class="totem_cell"><img src="/images/icons/bron.png" class="totem_icon" alt="Броня"></div>
                <div class="totem_cell"><img src="/images/icons/hp.png" class="totem_icon" alt="Здоровье"></div>
                <div class="totem_cell"><img src="/images/icons/zoloto.png" class="totem_icon" alt="Стоимость"></div>
            </div>

            <?php for ($i = 0; $i < count($arrTotemStat); $i++): ?>
                <div class="totem_row <?= $clan['totem'] == $i ? 'current' : ($clan['totem'] >= $i ? '' : 'inactive') ?>">
                    <?php for ($i1 = 0; $i1 < count($arrTotemStat[$i]); $i1++): ?>
                        <div class="totem_cell">
                            <?= $arrTotemStat[$i][$i1]; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>

        <?php if($clan['totem'] < 11): ?>
            <div style="text-align: center;">
                <div class="totem_costs">
                    Стоимость: <span><?= $arrTotemStat[$clan['totem']+1][8]; ?></span> золота из казны клана
                </div>
                <button class="totem_button" onclick="showContent('../clan/totem.php?kupit')">
                    <i class="fas fa-level-up-alt"></i> Улучшить до <?= $clan['totem']+2; ?> уровня
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php
}
$footval = "totem";
require_once '../system/foot/foot.php';
?>
