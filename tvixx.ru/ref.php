<?php
require_once 'system/func.php';

if (isset($_GET['BonMove']) && $_GET['BonMove'] == 1) {
    if ($mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num`='" . $user['myref'] . "'&&(`exp`>'0'||`slava`>'0'||`clan_reit`>'0'||`platinum`>'0')")->num_rows > 0) {
        $bon_arr = $mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num`='" . $user['myref'] . "'&&(`exp`>'0'||`slava`>'0'||`clan_reit`>'0'||`platinum`>'0')")->fetch_array(MYSQLI_ASSOC);
        $mc->query("UPDATE `users` SET "
                . "`exp` = `exp`+'" . $bon_arr['exp'] . "',"
                . " `slava` = `slava`+'" . $bon_arr['slava'] . "',"
                . " `platinum` = `platinum`+'" . $bon_arr['platinum'] . "',"
                . " `reit` = `reit`+'" . $bon_arr['clan_reit'] . "'"
                . "WHERE `id` = '" . $user['id'] . "' ");

//обновить
        $mc->query("UPDATE `ref_bonus` SET "
                . "`exp`='0',"
                . "`slava`='0',"
                . "`clan_reit`='0',"
                . "`platinum`='0'"
                . "WHERE `ref_num` = '" . $user['myref'] . "'");
    }
}

if (!isset($_GET['id'])) {
    echo "<b>Ваша ссылка:</b> " . $user['myref'];
    if ($mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num`='" . $user['myref'] . "'&&(`exp`>'0'||`slava`>'0'||`clan_reit`>'0'||`platinum`>'0')")->num_rows > 0) {
        $bon_arr = $mc->query("SELECT * FROM `ref_bonus` WHERE `ref_num`='" . $user['myref'] . "'&&(`exp`>'0'||`slava`>'0'||`clan_reit`>'0'||`platinum`>'0')")->fetch_array(MYSQLI_ASSOC);
        ?>
        <table class="table_block2">
            <tr>
                <td class="block101" style="width: 2%"></td>
                <td class="block102" style="width: 96%"></td>
                <td class="block103" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block104" style="width: 2%"></td>
                <td class="block105" style="width: 96%">
                    <table style="padding-left: 6px;">
                        <?php if ($bon_arr['platinum'] > 0) { ?><tr><td>Пополнения: <img style="width: 15px;" src="/images/icons/plata.png"><?= $bon_arr['platinum']; ?></td></tr><?php } ?>
                        <?php if ($bon_arr['exp'] > 0) { ?><tr><td>Опыт: +<?= $bon_arr['exp']; ?></td></tr><?php } ?>
                        <?php if ($bon_arr['slava'] > 0) { ?><tr><td>Слава: +<?= $bon_arr['slava']; ?></td></tr><?php } ?>
                        <?php if ($bon_arr['clan_reit'] > 0) { ?><tr><td>Клановый рейтинг: +<?= $bon_arr['clan_reit']; ?></td></tr><?php } ?>
                    </table>
                    <table style="margin: auto; width: 90%;">
                        <tr>
                            <td>
                                <div class="button_alt_01" onclick="showContent('/ref.php?BonMove=1')" style="margin: auto;">Забрать бонусы</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="block106" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table>
        <?php
    }else{
                ?>
        <table class="table_block2">
            <tr>
                <td class="block101" style="width: 2%"></td>
                <td class="block102" style="width: 96%"></td>
                <td class="block103" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block104" style="width: 2%"></td>
                <td class="block105" style="width: 96%;text-align: center">
                    -Нет Бонусов-
                </td>
                <td class="block106" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table>
        <?php
    }
    $user_ref = $mc->query("SELECT `level`,COUNT(*) FROM `users` WHERE `ref` = '" . $user['myref'] . "' GROUP BY `level` ORDER BY `level`")->fetch_all(MYSQLI_ASSOC);
    ?>
    <table class="table_block2">
        <tr>
            <td class="block101" style="width: 2%"></td>
            <td class="block102" style="width: 96%"></td>
            <td class="block103" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block104" style="width: 2%"></td>
            <td class="block105" style="width: 96%">
                <table style="margin: auto;">
                    <tr><td style="width: 50%; text-align: center">Ур.</td><td style="width: 50%; text-align: center">Рефералы</td></tr>
                    <tr><td><hr class="hr_01"></td><td><hr class="hr_01"></td></tr>
                    <?php for ($i = 0; $i < count($user_ref); $i++) { ?>
                        <tr><td style="width: 50%; text-align: center"><a onclick="showContent('/ref.php?id=<?= $user_ref[$i]['level']; ?>')"><?= $user_ref[$i]['level']; ?></a></td><td style="width: 50%; text-align: center"><?= $user_ref[$i]['COUNT(*)']; ?></td></tr><?php }
                    ?>
                </table>
            </td>
            <td class="block106" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block107"></td>
            <td class="block108"></td>
            <td class="block109"></td>
        </tr>
    </table>
    <?php
    $footval = "ref";
}
if (isset($_GET['id'])) {
    $user_row = $mc->query("SELECT * FROM `users` WHERE `level` = '" . $_GET['id'] . "' AND `ref` = '" . $user['myref'] . "' ")->fetch_all(MYSQLI_ASSOC);
    ?>
    <table class="table_block2">
        <tr>
            <td class="block101" style="width: 2%"></td>
            <td class="block102" style="width: 96%"></td>
            <td class="block103" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block104" style="width: 2%"></td>
            <td class="block105" style="width: 96%">
                <table>
                    <table style="margin: auto;">
                        <tr><td><hr class="hr_01"></td><td><hr class="hr_01"></td></tr>
                        <?php for ($i = 0; $i < count($user_row); $i++) { ?>
                            <tr><td style="width: 50%; text-align: center"><?= $user_row[$i]['level']; ?></td><td style="width: 50%; text-align: center"><a onclick="showContent('/profile.php?id=<?= $user_row[$i]['id']; ?>')"><?= $user_row[$i]['name']; ?></a></td></tr><?php }
                        ?>
                    </table>
            </td>
            <td class="block106" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block107"></td>
            <td class="block108"></td>
            <td class="block109"></td>
        </tr>
    </table>
    <?php
    $footval = "ref_viev";
}
require_once ('system/foot/foot.php');