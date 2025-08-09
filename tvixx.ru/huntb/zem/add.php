<?php

require_once '../../system/func.php';

// Улучшенная проверка времени
$timezone = 0;
$current_time = time() + 3600*($timezone+date("I"));

// Времена для первого этапа (18:00)
$registration_first_start = mktime(17, 50, 0, date("m"), date("d"), date("Y")); // 17:50
$battle_first_start = mktime(18, 00, 0, date("m"), date("d"), date("Y")); // 18:00

// Времена для второго этапа (20:00)
$registration_second_start = mktime(19, 50, 0, date("m"), date("d"), date("Y")); // 19:50
$battle_second_start = mktime(20, 00, 0, date("m"), date("d"), date("Y")); // 20:00

// Проверка условий для первого этапа (отборочный тур)
$can_register_first = ($current_time >= $registration_first_start && $current_time < $battle_first_start);

// Проверка условий для второго этапа (финальное сражение)
$can_register_second = ($current_time >= $registration_second_start && $current_time < $battle_second_start);

if (isset($_GET['add']) && $_GET['add'] == "1800") {
    // Проверка времени для первого этапа
    if (!$can_register_first) {
        ?>
        <script>
            showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Регистрация доступна только с 17:50 до 18:00.'));
        </script>
        <?php
        exit;
    }
    
    // Проверка, не участвует ли игрок в бою
    if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows == 0) {
        // Проверка, что пользователь имеет нужный уровень
        if (isset($user) && $user['level'] > 1) {
            $ucharr = $mc->query("SELECT * FROM `huntb_list` WHERE `type` = 7 AND `location` = " . $user['location'] . "")->fetch_all(MYSQLI_ASSOC);

            // Проверка, не состоит ли его клан уже в участниках
            $nomyclan = true;
            for ($i = 0; $i < count($ucharr); $i++) {
                $usrunc = $mc->query("SELECT `id_clan` FROM `users` WHERE `id` = " . $ucharr[$i]['user_id'] . "")->fetch_array(MYSQLI_ASSOC);
                if ($usrunc['id_clan'] == $user['id_clan']) {
                    $nomyclan = false;
                }
            }

            // Проверка, не принадлежит ли локация уже его клану
            $yazahvatillocu = $mc->query("SELECT COUNT(*) FROM `location` WHERE `idClan` = " . $user['id_clan'] . " AND `id` = " . $user['location'] . "")->fetch_array(MYSQLI_ASSOC);
            if ($yazahvatillocu['COUNT(*)'] != 0) {
                $nomyclan = true;
            }

            // Проверка, что игрок имеет статус в клане и может участвовать
            if ($user['des'] > 0 && $nomyclan) {
                if ($user['side'] == 0 || $user['side'] == 1) {
                    $user_rasa = 0;
                } else {
                    $user_rasa = 1;
                }

                // Удаляем предыдущие заявки и добавляем новую
                $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
                $mc->query("INSERT INTO `huntb_list`("
                        . "`id`,"
                        . " `user_id`,"
                        . " `level`,"
                        . " `rasa`,"
                        . " `time_start`,"
                        . " `location`,"
                        . " `type`"
                        . ") VALUES ("
                        . "'NULL',"
                        . "'" . $user['id'] . "',"
                        . "'" . $user['level'] . "',"
                        . "'$user_rasa',"
                        . "'" . time() . "',"
                        . "'" . $user['location'] . "',"
                        . "'7'"
                        . ")");
                ?>
                <script>
                    showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Заявка на отборочный тур принята.'));
                </script>
            <?php } else { ?>
                <script>
                    showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Невозможно зарегистрироваться. Требуется статус в клане.'));
                </script>
                <?php
            }
        } else {
            ?>
            <script>
                showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Невозможно зарегистрироваться. Требуется уровень выше 1.'));
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            showContent("/hunt/battle.php?msg=" + encodeURIComponent('Вы не можете зарегистрироваться, так как участвуете в бою.'));
        </script>
        <?php
    }
}

if (isset($_GET['add']) && $_GET['add'] == "2000") {
    // Проверка времени для второго этапа
    if (!$can_register_second) {
        ?>
        <script>
            showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Регистрация доступна только с 19:50 до 20:00.'));
        </script>
        <?php
        exit;
    }

    // Проверка, не участвует ли игрок в бою
    if ($mc->query("SELECT * FROM `battle` WHERE `Mid`='" . $user['id'] . "' AND `player_activ`='1' AND `end_battle`='0'")->num_rows == 0) {
        // Проверка, участвует ли клан игрока в сражении за эту локацию
        $locclan = $mc->query("SELECT * FROM `location` WHERE (`idClan` = " . $user['id_clan'] . " OR `idNextClan` = " . $user['id_clan'] . ") AND `id` = " . $user['location'] . "")->fetch_array(MYSQLI_ASSOC);
        if (is_array($locclan) && !empty($locclan)) {
            if ($user['side'] == 0 || $user['side'] == 1) {
                $user_rasa = 0;
            } else {
                $user_rasa = 1;
            }

            // Удаляем предыдущие заявки и добавляем новую
            $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
            $mc->query("INSERT INTO `huntb_list`("
                    . "`id`,"
                    . " `user_id`,"
                    . " `level`,"
                    . " `rasa`,"
                    . " `time_start`,"
                    . " `location`,"
                    . " `type`"
                    . ") VALUES ("
                    . "'NULL',"
                    . "'" . $user['id'] . "',"
                    . "'" . $user['level'] . "',"
                    . "'$user_rasa',"
                    . "'" . time() . "',"
                    . "'" . $user['location'] . "',"
                    . "'8'"
                    . ")");
            ?>
            <script>
                showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Заявка на финальное сражение принята.'));
            </script>
            <?php
        } else {
            ?>
            <script>
                showContent('huntb/zem/index.php?msg=' + encodeURIComponent('Невозможно зарегистрироваться. Ваш клан не участвует в сражении за эту локацию.'));
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            showContent("/hunt/battle.php?msg=" + encodeURIComponent('Вы не можете зарегистрироваться, так как участвуете в бою.'));
        </script>
        <?php
    }
}