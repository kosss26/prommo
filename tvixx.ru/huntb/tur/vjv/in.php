<?php
require_once '../../../system/func.php';
ob_start();

if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script>
    <?php
    exit(0);
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
$time_save = ceil(((time() + $timeP) % 3600) / 60);
$time_start_tur = 0;
for ($i = 0; $i <= 60; $i += $timeN) {
    if ($time_save <= $i) {
        $time_start_tur = ((time() + $timeP) - (time() + $timeP) % 3600) + ($i * 60);
        break;
    }
}

$min_lvl_tur = $arr_lvl[$user['level']][0];
$max_lvl_tur = $arr_lvl[$user['level']][1];

$ucharr = $mc->query("SELECT * FROM `huntb_list` WHERE "
                . "`level` >= '" . $arr_lvl[$user['level']][0] . "' &&"
                . " `level` <= '" . $arr_lvl[$user['level']][1] . "' &&"
                . " `type`='3' ORDER BY `time_start` ASC ")->fetch_all(MYSQLI_ASSOC);
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
    display: inline-block;
    border: none;
}

.survival-button:hover {
    background: linear-gradient(to bottom, #8B4513, #643201);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.survival-button.danger {
    background: linear-gradient(to bottom, #C75D4D, #A13C2E);
}

.survival-button.danger:hover {
    background: linear-gradient(to bottom, #A13C2E, #7A2D23);
}

.survival-players {
    margin-top: 20px;
}

.survival-player {
    display: grid;
    grid-template-columns: 40px 1fr;
    gap: 10px;
    align-items: center;
    padding: 10px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 4px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.survival-player:hover {
    background: rgba(187, 152, 84, 0.2);
    transform: translateX(2px);
}

.survival-player-number {
    text-align: center;
    font-weight: 500;
    color: #4A2601;
}

.survival-player-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.survival-player-icon {
    width: 19px;
    height: 19px;
}

.survival-player-name {
    color: #4A2601;
}

.survival-player-name b {
    color: #8B4513;
}

.survival-empty {
    padding: 15px;
    text-align: center;
    color: #643201;
    font-style: italic;
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
        margin-bottom: 8px;
    }
    
    .survival-player {
        padding: 8px;
        font-size: 13px;
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
    
    .survival-player-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
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
                <span class="survival-stat-value"><?= count($ucharr); ?>/<?= $max_users_tur; ?></span>
            </div>

            <div class="survival-stat">
                <span>Время начала:</span>
                <span class="survival-stat-value">
                    <?= sprintf("%02d:%02d", ($time_start_tur / 3600) % 24, ($time_start_tur % 3600) / 60); ?>
                </span>
            </div>
        </div>
    </div>

    <div style="text-align: center">
        <?php if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "' && `type`='3'")->num_rows>0) { 
            $footval = "vjv_huntb_in_registered";
            ?>
            <button class="survival-button danger" onclick="showContent('/huntb/tur/vjv/remove.php')">
                Отказаться
            </button>
        <?php } else { 
            $footval = "vjv_huntb_in";
            ?>
            <button class="survival-button" onclick="showContent('/huntb/tur/vjv/add.php')">
                Зарегистрироваться
            </button>
        <?php } ?>
    </div>

    <?php if (count($ucharr)): ?>
        <div class="survival-players">
            <?php foreach ($ucharr as $i => $player): 
                $usrunc = $mc->query("SELECT * FROM `users` WHERE `id` >= '" . $player['user_id'] . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC);
            ?>
                <div class="survival-player" onclick="showContent('/profile/<?= $usrunc['id']; ?>')">
                    <div class="survival-player-number">
                        <?= $i + 1; ?>.
                    </div>
                    <div class="survival-player-info">
                        <img class="survival-player-icon" 
                             src="/img/icon/<?= $usrunc['side'] == 0 || $usrunc['side'] == 1 ? 'icoevil' : 'icogood'; ?>.png" 
                             alt="">
                        <div class="survival-player-name">
                            <?= $usrunc['name'] == $user['name'] ? "<b>" . $usrunc['name'] . "</b>" : $usrunc['name']; ?>
                            [<?= $usrunc['level']; ?>]
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="survival-empty">
            Нет бойцов
        </div>
    <?php endif; ?>
</div>

<?php
$footval = "vjv_huntb_index";
require_once ('../../../system/foot/foot.php');
ob_end_flush();
?>