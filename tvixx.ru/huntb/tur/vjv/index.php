<?php
require_once '../../../system/func.php';
ob_start();
if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script>
    <?php
}
$timeN = 5;
$timeP = 10800;
$max_users_tur = 32;
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
$name_tur = "выживание";
$min_lvl_tur = $arr_lvl[$user['level']][0];
$max_lvl_tur = $arr_lvl[$user['level']][1];
$time_save = ceil(((time() + $timeP) % 3600) / 60);
$time_start_tur = 0;
for ($i = 0; $i <= 60; $i += $timeN) {
    if ($time_save <= $i) {
        $time_start_tur = ((time() + $timeP) - (time() + $timeP) % 3600) + ($i * 60);
        break;
    }
}
$uchcount = $mc->query("SELECT * FROM `huntb_list` WHERE "
                . "`level` >= '" . $arr_lvl[$user['level']][0] . "' &&"
                . " `level` <= '" . $arr_lvl[$user['level']][1] . "' &&"
                . " `type`='3'")->num_rows;
?>

<style>
.survival-arena {
    background: linear-gradient(to bottom, rgba(139, 69, 19, 0.1), rgba(139, 69, 19, 0.05));
    border-radius: 8px;
    padding: 15px;
    margin: 10px auto;
    max-width: 800px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.survival-header {
    background: linear-gradient(to bottom, #BB9854, #8B4513);
    color: white;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
    font-size: 18px;
    font-weight: 500;
    text-shadow: 1px 1px 1px rgba(0,0,0,0.2);
}

.survival-info {
    background: rgba(187, 152, 84, 0.1);
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 20px;
}

.survival-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.survival-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 4px;
    color: #4A2601;
    font-size: 14px;
}

.survival-stat img {
    width: 20px;
    height: 20px;
}

.survival-stat-value {
    background: rgba(139, 69, 19, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
    font-weight: 500;
    color: #8B4513;
}

.survival-button {
    background: linear-gradient(to bottom, #BB9854, #8B4513);
    color: white;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    text-shadow: 1px 1px 1px rgba(0,0,0,0.2);
    margin: 0 auto;
    display: block;
    border: none;
    width: fit-content;
}

.survival-button:hover {
    background: linear-gradient(to bottom, #8B4513, #643201);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .survival-arena {
        padding: 10px;
        margin: 5px;
    }
    
    .survival-header {
        font-size: 16px;
        padding: 10px;
    }
    
    .survival-stats {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .survival-stat {
        font-size: 13px;
    }
    
    .survival-button {
        padding: 10px 20px;
        font-size: 13px;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .survival-header {
        font-size: 14px;
    }
    
    .survival-stat {
        font-size: 12px;
    }
    
    .survival-button {
        padding: 8px 16px;
        font-size: 12px;
    }
}
</style>

<div class="survival-arena">
    <div class="survival-header">
        Выживание
    </div>

    <div class="survival-info">
        <div class="survival-stats">
            <div class="survival-stat">
                <span>Золотой приз-взнос</span>
                <img src="/images/icons/zoloto.png" alt="Золото">
                <span class="survival-stat-value">1</span>
            </div>

            <div class="survival-stat">
                <span>Игроки:</span>
                <span class="survival-stat-value"><?= $uchcount; ?>/<?= $max_users_tur; ?></span>
            </div>

            <div class="survival-stat">
                <span>Время:</span>
                <span class="survival-stat-value">
                    <?= sprintf("%02d:%02d", ($time_start_tur / 3600) % 24, ($time_start_tur % 3600) / 60); ?>
                </span>
            </div>
        </div>
    </div>

    <button class="survival-button" onclick="showContent('/huntb/tur/vjv/in.php')">
        Просмотреть
    </button>
</div>

<?php
$footval = "vjv_huntb_index";
require_once ('../../../system/foot/foot.php');
ob_end_flush();
?>