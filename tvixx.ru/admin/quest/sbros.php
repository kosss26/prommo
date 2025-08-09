<?php
if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
    $id_user = $_GET['id_user'];
} else {
    $id_user = $user['id'];
}

if (isset($_GET['sbros_id_vziat']) && $_GET['sbros_id_vziat'] > 0) {
    destructQuest($_GET['sbros_id_vziat'], $id_user);
    $mc->query("DELETE FROM `quests_users` WHERE `id_user` ='$id_user' && `id_quests`='" . $_GET['sbros_id_vziat'] . "'");
}
if (isset($_GET['sbros_id_notActive']) && $_GET['sbros_id_notActive'] > 0) {
    destructQuest($_GET['sbros_id_notActive'], $id_user);
    $mc->query("DELETE FROM `quests_notActive` WHERE `id_user` ='$id_user' && `id_quests`='" . $_GET['sbros_id_notActive'] . "'");
}



$user_quests = $mc->query("SELECT `id_quests` FROM `quests_users` WHERE `id_user` = '$id_user' ORDER BY `time_view` DESC")->fetch_all(MYSQLI_ASSOC);
$notActive_quests = $mc->query("SELECT `id_quests` FROM `quests_notActive` WHERE `id_user` = '$id_user'")->fetch_all(MYSQLI_ASSOC);
?>


<br>
<details>
    <summary style='text-align: left'>АКТИВНЫЕ</summary>
    <div style="padding-left: 20px;">
        <?php
        for ($i = 0; $i < count($user_quests); $i++) {
            if ($quests = $mc->query("SELECT `id`,`name`,`rasa`,`comment` FROM `quests` WHERE `id` = '" . $user_quests[$i]['id_quests'] . "'")->fetch_array(MYSQLI_ASSOC)) {
                $icon = "";
                if ($quests['rasa'] == 1) {
                    $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
                } elseif ($quests['rasa'] == 2) {
                    $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
                }
                ?>
                <?= $user_quests[$i]['id_quests'] . "." . $icon . urldecode($quests['name']); ?>
                <a onclick="showContent('/admin/quest/quest.php?sbros=1&sbros_id_vziat=<?= $quests['id']; ?>&id_user=<?= $id_user; ?>');"> >>СБРОСИТЬ<< </a>
                <font style="color:grey;"><?= urldecode($quests['comment']) != '' ? "//" . urldecode($quests['comment']) : ""; ?></font>
                <br>
                <hr>
                <?php
            }
        }
        ?>
    </div>
</details>
<br>
<details>
    <summary style='text-align: left'>ЗАВЕРШЕННЫЕ</summary>
    <div style="padding-left: 20px;">
        <?php
        for ($i = 0; $i < count($notActive_quests); $i++) {
            if ($quests = $mc->query("SELECT `id`,`name`,`rasa`,`comment` FROM `quests` WHERE `id` = '" . $notActive_quests[$i]['id_quests'] . "'")->fetch_array(MYSQLI_ASSOC)) {
                $icon = "";
                if ($quests['rasa'] == 1) {
                    $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
                } elseif ($quests['rasa'] == 2) {
                    $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
                }
                ?>
                <?= $notActive_quests[$i]['id_quests'] . "." . $icon . urldecode($quests['name']); ?>
                <a onclick="showContent('/admin/quest/quest.php?sbros=1&sbros_id_notActive=<?= $quests['id']; ?>&id_user=<?= $id_user; ?>');"> >>СБРОСИТЬ<< </a>
                <font style="color:grey;"><?= urldecode($quests['comment']) != '' ? "//" . urldecode($quests['comment']) : ""; ?></font>
                <br>
                <hr>
                <?php
            }
        }
        ?>
    </div>
</details>


<?php

//******функция выдачи снятия наград по варианту части квеста
function destructQuest($id_q, $id_u) {
    global $mc;
    $arrCountQuests = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '$id_q'")->fetch_all(MYSQLI_ASSOC);
    $this_user = $mc->query("SELECT * FROM `users` WHERE `id` = '$id_u'")->fetch_array(MYSQLI_ASSOC);
    for ($q = 0; $q < count($arrCountQuests); $q++) {
        for ($s = 0; $s < 2; $s++) {
            $pre = ($s == 0) ? "" : (($s == 1) ? "proval_" : (($s == 2) ? "otkaz_" : ""));

            $arrTemp0 = json_decode(urldecode($arrCountQuests[$q][$pre . 'delpv']));
            for ($i = 0; $i < count($arrTemp0); $i++) {
                $mc->query("DELETE FROM `userbag` WHERE `id_user` = '$id_u' && `id_shop` = '" . $arrTemp0[$i][0] . "' LIMIT " . $arrTemp0[$i][1]);
            }

            $mc->query("UPDATE `users` SET "
                    . "`exp` = `exp`+'" . $arrCountQuests[$q][$pre . 'delpexp'] . "',"
                    . "`slava` = `slava`+'" . $arrCountQuests[$q][$pre . 'delpslava'] . "',"
                    . "`vinos_t` = `vinos_t`+'" . $arrCountQuests[$q][$pre . 'delpvinos_t'] . "',"
                    . "`vinos_m` = `vinos_m`+'" . $arrCountQuests[$q][$pre . 'delpvinos_m'] . "',"
                    . "`platinum` = `platinum`+'" . $arrCountQuests[$q][$pre . 'delpplatinum'] . "',"
                    . "`money` = `money`-'" . $arrCountQuests[$q][$pre . 'delpmed'] . "',"
                    . "`pobedmonser` = `pobedmonser`+'" . $arrCountQuests[$q][$pre . 'delppobedmonser'] . "',"
                    . "`pobedigroki` = `pobedigroki`+'" . $arrCountQuests[$q][$pre . 'delppobedigroki'] . "'"
                    . " WHERE `id` = '$id_u'");
            $arrTemp1 = json_decode(urldecode($arrCountQuests[$q][$pre . 'addpv']));
            for ($i = 0; $i < count($arrTemp1); $i++) {
                $mc->query("DELETE FROM `userbag` WHERE `id_user` = '$id_u' && `id_shop` = '" . $arrTemp1[$i][0] . "' LIMIT " . $arrTemp1[$i][1]);
            }
            $mc->query("UPDATE `users` SET "
                    . "`exp` = `exp`-'" . $arrCountQuests[$q][$pre . 'addpexp'] . "',"
                    . "`slava` = `slava`-'" . $arrCountQuests[$q][$pre . 'addpslava'] . "',"
                    . "`vinos_t` = `vinos_t`-'" . $arrCountQuests[$q][$pre . 'addpvinos_t'] . "',"
                    . "`vinos_m` = `vinos_m`-'" . $arrCountQuests[$q][$pre . 'addpvinos_m'] . "',"
                    . "`platinum` = `platinum`-'" . $arrCountQuests[$q][$pre . 'addpplatinum'] . "',"
                    . "`money` = `money`-'" . $arrCountQuests[$q][$pre . 'addpmed'] . "',"
                    . "`pobedmonser` = `pobedmonser`-'" . $arrCountQuests[$q][$pre . 'addppobedmonser'] . "',"
                    . "`pobedigroki` = `pobedigroki`-'" . $arrCountQuests[$q][$pre . 'addppobedigroki'] . "'"
                    . " WHERE `id` = '$id_u'");
        }
    }
}
?>

