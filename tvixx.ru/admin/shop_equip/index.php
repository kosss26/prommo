<?php
require_once '../../system/func.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
$footval = 'adminlocindex';
include '../../system/foot/foot.php';
$allloc = $mc->query("SELECT * FROM `location` ORDER BY `id`")->fetch_all(MYSQLI_ASSOC);
?>
<center><strong>-Редактор Магазинов-</strong></center><br>
<center>Выбор города</center>

<div>
    <?php
    for ($i = 0; $i < count($allloc); $i++) {
        $counts = $mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '" . $allloc[$i]['id'] . "' ")->num_rows;
        $icon = "";
        if ($allloc[$i]['access'] == 1) {
            $icon = "<img height='19' src='/img/icon/icogood.png' width='19' alt=''>";
        } elseif ($allloc[$i]['access'] == 2) {
            $icon = "<img height='19' src='/img/icon/icoevil.png' width='19' alt=''>";
        }
        ?>
        <div class="clanturblock" style="padding: 6px;" onclick="showContent('/admin/shop_equip/razdel.php?id_location=<?= $allloc[$i]['id']; ?>')">
            <table style="width: 100%;margin: auto;">
                <tr>
                    <td style="width: 50px;text-align: center"><?= $allloc[$i]['id'] . " . "; ?></td>
                    <td style="max-width: 100%;text-align: left"><?= $icon . " " . $allloc[$i]['Name'] . " [" . $allloc[$i]['accesslevel'] . "] "; ?></td>
                    <td style="width: 50px;text-align: center"><?= $counts; ?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
</div>