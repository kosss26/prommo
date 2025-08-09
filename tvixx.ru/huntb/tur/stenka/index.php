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
if ($user['side'] == 0 || $user['side'] == 1) {
    $user_rasa = 0;
} else {
    $user_rasa = 1;
}
$name_tur = "стенка";
$time_save = ceil(((time() + $timeP) % 3600) / 60);
$time_start_tur = 0;
for ($i = 0; $i <= 60; $i += $timeN) {
    if ($time_save <= $i) {
        $time_start_tur = ((time() + $timeP) - (time() + $timeP) % 3600) + ($i * 60);
        break;
    }
}
$ucharrU = $mc->query("SELECT * FROM `huntb_list` WHERE "
                . "`level` >= '" . $arr_lvl[$user['level']][0] . "' &&"
                . " `level` <= '" . $arr_lvl[$user['level']][1] . "' &&"
                . " `rasa` = '$user_rasa' &&"
                . " `type`='5' ORDER BY `time_start` ASC ")->num_rows;
$ucharrA = $mc->query("SELECT * FROM `huntb_list` WHERE "
                . "`level` >= '" . $arr_lvl[$user['level']][0] . "' &&"
                . " `level` <= '" . $arr_lvl[$user['level']][1] . "' &&"
                . " `rasa` != '$user_rasa' &&"
                . " `type`='5' ORDER BY `time_start` ASC ")->num_rows;
$uchcount = $ucharrU + $ucharrA;
?>

<style>
.wall-arena {
    background: linear-gradient(to bottom, rgba(139, 69, 19, 0.1), rgba(139, 69, 19, 0.05));
    border-radius: 8px;
    padding: 15px;
    margin: 10px auto;
    max-width: 800px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wall-header {
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

.wall-info {
    background: rgba(187, 152, 84, 0.1);
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 20px;
}

.wall-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.wall-teams {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin: 15px 0;
    position: relative;
    padding: 15px;
    background: rgba(187, 152, 84, 0.05);
    border-radius: 6px;
}

.wall-team {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 6px;
    padding: 15px;
    border: 1px solid rgba(139, 69, 19, 0.1);
    transition: all 0.2s;
}

.wall-team:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wall-team-title {
    color: #4A2601;
    font-weight: 600;
    margin-bottom: 10px;
    text-align: center;
    font-size: 15px;
    text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
}

.wall-stat {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 6px;
    color: #4A2601;
    font-size: 14px;
    transition: all 0.2s;
}

.wall-stat:hover {
    background: rgba(255, 255, 255, 0.9);
}

.wall-stat-value {
    background: linear-gradient(to bottom, rgba(139, 69, 19, 0.1), rgba(139, 69, 19, 0.05));
    padding: 4px 10px;
    border-radius: 4px;
    font-family: monospace;
    font-weight: 500;
    color: #8B4513;
    min-width: 50px;
    text-align: center;
}

.wall-button {
    background: linear-gradient(to bottom, #BB9854, #8B4513);
    color: white;
    padding: 12px 30px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    text-shadow: 1px 1px 1px rgba(0,0,0,0.2);
    margin: 0 auto;
    display: block;
    border: none;
    width: fit-content;
    position: relative;
    overflow: hidden;
}

.wall-button:after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s;
}

.wall-button:hover:after {
    opacity: 1;
}

@media (max-width: 768px) {
    .wall-teams {
        grid-template-columns: 1fr;
        gap: 12px;
        padding: 12px;
    }
    
    .wall-team {
        padding: 12px;
    }
    
    .wall-team-title {
        font-size: 14px;
    }
    
    .wall-stat {
        padding: 10px;
        font-size: 13px;
    }
    
    .wall-button {
        padding: 10px 20px;
        font-size: 13px;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .wall-teams {
        padding: 8px;
    }
    
    .wall-team {
        padding: 10px;
    }
    
    .wall-team-title {
        font-size: 13px;
    }
    
    .wall-stat {
        padding: 8px;
        font-size: 12px;
    }
    
    .wall-stat-value {
        padding: 3px 8px;
    }
}
</style>

<div class="wall-arena">
    <div class="wall-header">
        Стенка на стенку
    </div>

    <div class="wall-info">
        <div class="wall-stats">
            <div class="wall-stat">
                <span>Золотой приз-взнос</span>
                <img src="/images/icons/zoloto.png" alt="Золото">
                <span class="wall-stat-value">1</span>
            </div>

            <div class="wall-stat">
                <span>Время начала:</span>
                <span class="wall-stat-value">
                    <?= sprintf("%02d:%02d", ($time_start_tur / 3600) % 24, ($time_start_tur % 3600) / 60); ?>
                </span>
            </div>
        </div>

        <div class="wall-teams">
            <div class="wall-team">
                <div class="wall-team-title">Ваша команда</div>
                <div class="wall-stat">
                    <span>Бойцов:</span>
                    <span class="wall-stat-value"><?= $ucharrU; ?>/<?= $max_users_tur/2; ?></span>
                </div>
            </div>

            <div class="wall-team">
                <div class="wall-team-title">Противники</div>
                <div class="wall-stat">
                    <span>Бойцов:</span>
                    <span class="wall-stat-value"><?= $ucharrA; ?>/<?= $max_users_tur/2; ?></span>
                </div>
            </div>
        </div>
    </div>

    <button class="wall-button" onclick="showContent('/huntb/tur/stenka/in.php')">
        Просмотреть
    </button>
</div>

<?php
$footval = "stenka_huntb_index";
require_once ('../../../system/foot/foot.php');
ob_end_flush();
?>