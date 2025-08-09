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
$max_users_tur = 15;
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
                . " `type`='5' ORDER BY `time_start` ASC ")->fetch_all(MYSQLI_ASSOC);
$ucharrA = $mc->query("SELECT * FROM `huntb_list` WHERE "
                . "`level` >= '" . $arr_lvl[$user['level']][0] . "' &&"
                . " `level` <= '" . $arr_lvl[$user['level']][1] . "' &&"
                . " `rasa` != '$user_rasa' &&"
                . " `type`='5' ORDER BY `time_start` ASC ")->fetch_all(MYSQLI_ASSOC);
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
    background: rgba(255, 255, 255, 0.7);
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 20px;
    color: #4A2601;
    line-height: 1.5;
    border: 1px solid rgba(139, 69, 19, 0.1);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.wall-info-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
    background: rgba(187, 152, 84, 0.1);
    border-radius: 4px;
    color: #4A2601;
    font-size: 14px;
    justify-content: center;
    margin-bottom: 15px;
    border: 1px solid rgba(139, 69, 19, 0.05);
}

.wall-info-header img {
    width: 20px;
    height: 20px;
}

.wall-info code {
    background: rgba(139, 69, 19, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
    color: #8B4513;
}

.wall-info b {
    color: #8B4513;
    font-weight: 600;
}

.wall-teams {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
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

.wall-team-header {
    color: #4A2601;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(139, 69, 19, 0.1);
}

.wall-players {
    display: grid;
    gap: 8px;
}

.wall-player {
    display: grid;
    grid-template-columns: 40px auto;
    gap: 10px;
    align-items: center;
    padding: 10px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.wall-player:hover {
    background: rgba(187, 152, 84, 0.2);
    transform: translateX(2px);
}

.wall-player-number {
    text-align: center;
    color: #4A2601;
    font-weight: 500;
}

.wall-player-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.wall-player-icon {
    width: 19px;
    height: 19px;
}

.wall-player-name {
    color: #4A2601;
}

.wall-player-name b {
    color: #8B4513;
}

.wall-empty {
    text-align: center;
    padding: 15px;
    color: #643201;
    font-style: italic;
}

.wall-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
}

.wall-button {
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
    border: none;
}

.wall-button:hover {
    background: linear-gradient(to bottom, #8B4513, #643201);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wall-button.danger {
    background: linear-gradient(to bottom, #C75D4D, #A13C2E);
}

.wall-button.danger:hover {
    background: linear-gradient(to bottom, #A13C2E, #7A2D23);
}

@media (max-width: 768px) {
    .wall-arena {
        padding: 10px;
        margin: 5px;
    }
    
    .wall-header {
        font-size: 16px;
    }
    
    .wall-teams {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .wall-team {
        padding: 12px;
    }
    
    .wall-player {
        padding: 8px;
        font-size: 13px;
    }
    
    .wall-button {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .wall-header {
        font-size: 14px;
    }
    
    .wall-team-header {
        font-size: 14px;
    }
    
    .wall-player-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .wall-buttons {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<div class="wall-arena">
    <div class="wall-header">
        Стенка на стенку
    </div>

    <div class="wall-info">
        <div class="wall-info-header">
            Золотой приз-взнос
            <img src="/images/icons/zoloto.png" alt="Золото">
            1
        </div>

        <div>
            Турнир начинается в 
            <code><?= sprintf("%02d:%02d", ($time_start_tur / 3600) % 24, ($time_start_tur % 3600) / 60); ?></code>
            при наличии <b>минимум 3</b> бойцов с каждой из сторон или раньше если набирается 
            <b><?= $max_users_tur; ?></b> бойцов с каждой из сторон.
        </div>
    </div>

    <div class="wall-teams">
        <div class="wall-team">
            <div class="wall-team-header">Ваша команда</div>
            <div class="wall-players">
                <?php if (count($ucharrU)): ?>
                    <?php foreach ($ucharrU as $i => $player): 
                        $usrunc = $mc->query("SELECT * FROM `users` WHERE `id` >= '" . $player['user_id'] . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC);
                    ?>
                        <div class="wall-player" onclick="showContent('/profile/<?= $usrunc['id']; ?>')">
                            <div class="wall-player-number"><?= $i + 1; ?>.</div>
                            <div class="wall-player-info">
                                <img class="wall-player-icon" 
                                     src="/img/icon/<?= $usrunc['side'] == 0 || $usrunc['side'] == 1 ? 'icoevil' : 'icogood'; ?>.png" 
                                     alt="">
                                <div class="wall-player-name">
                                    <?= $usrunc['name'] == $user['name'] ? "<b>" . $usrunc['name'] . "</b>" : $usrunc['name']; ?>
                                    [<?= $usrunc['level']; ?>]
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="wall-empty">нет бойцов</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="wall-team">
            <div class="wall-team-header">Противники</div>
            <div class="wall-players">
                <?php if (count($ucharrA)): ?>
                    <?php foreach ($ucharrA as $i => $player): 
                        $usrunc = $mc->query("SELECT * FROM `users` WHERE `id` >= '" . $player['user_id'] . "' LIMIT 1")->fetch_array(MYSQLI_ASSOC);
                    ?>
                        <div class="wall-player" onclick="showContent('/profile/<?= $usrunc['id']; ?>')">
                            <div class="wall-player-number"><?= $i + 1; ?>.</div>
                            <div class="wall-player-info">
                                <img class="wall-player-icon" 
                                     src="/img/icon/<?= $usrunc['side'] == 0 || $usrunc['side'] == 1 ? 'icoevil' : 'icogood'; ?>.png" 
                                     alt="">
                                <div class="wall-player-name">
                                    <?= $usrunc['name'] == $user['name'] ? "<b>" . $usrunc['name'] . "</b>" : $usrunc['name']; ?>
                                    [<?= $usrunc['level']; ?>]
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="wall-empty">нет бойцов</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="wall-buttons">
        <?php if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "' && `type`='5'")->num_rows>0): 
            $footval = "stenka_huntb_in_registered";
        ?>
            <button class="wall-button danger" onclick="showContent('/huntb/tur/stenka/remove.php')">
                Отказаться
            </button>
        <?php else:
            $footval = "stenka_huntb_in";
        ?>
            <button class="wall-button" onclick="showContent('/huntb/tur/stenka/add.php')">
                Зарегистрироваться
            </button>
        <?php endif; ?>
    </div>
</div>

<?php
require_once ('../../../system/foot/foot.php');
ob_end_flush();
?>