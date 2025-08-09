<?php
require_once '../../system/func.php';
require_once '../../functions/bablo.php';

if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
$footval = 'shop_edit';
include '../../system/foot/foot.php';
$id_location = 0;
if (isset($_GET['id_location'])) {
    $id_location = $_GET['id_location'];
}

$razdel = 5;
$arrRazdelName = ["Нет раздела", "Оружие", "Броня", "Зелья и Свитки", "Амулеты", "Разное","","","","", "Для заданий", "Бонусы", "Скрытые"];
if (isset($_GET['razdel'])) {
    $razdel = $_GET['razdel'];
}
$razdelName = $arrRazdelName[$razdel];

$thisloc = $mc->query("SELECT * FROM `location` WHERE `id` = '$id_location'")->fetch_array(MYSQLI_ASSOC);
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $thing = $mc->query("SELECT * FROM `shop_equip` WHERE `id` = '" . $_GET['delete'] . "'")->fetch_array(MYSQLI_ASSOC);
    $mc->query("DELETE FROM `shop_equip` WHERE `id` = '" . $_GET['delete'] . "' LIMIT 1");
    $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> убрал вещь </font><a onclick=\\'showContent(\\\"/admin/shop_equip/edit.php?id_location=" . $id_location . "&razdel=" . $razdel . "\\\")\\'><font color=\\'#0033cc\\'>" . $thing['name'] . " из " . $thisloc['Name'] . " раздел " . $razdelName . "</font></a><font color=\\'#0033cc\\'> !</font>";
    $mc->query("INSERT INTO `chat`("
            . "`id`,"
            . "`name`,"
            . "`id_user`,"
            . "`chat_room`,"
            . "`msg`,"
            . "`msg2`,"
            . "`time`,"
            . " `unix_time"
            . "`) VALUES ("
            . "NULL,"
            . "'АДМИНИСТРИРОВАНИЕ',"
            . "'',"
            . "'5',"
            . " '" . $chatmsg . "',"
            . "'',"
            . "'',"
            . "''"
            . " )");
}
if (isset($_GET['add']) && $_GET['add'] > 0) {
    $thing = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $_GET['add'] . "'")->fetch_array(MYSQLI_ASSOC);
    $mc->query("INSERT INTO `shop_equip` ("
            . "`id`,"
            . " `id_shop`,"
            . " `id_location`,"
            . " `id_punct_shop`,"
            . " `name`,"
            . " `platinum`,"
            . " `money`,"
            . " `level`"
            . ") VALUES ("
            . "NULL,"
            . " '" . $_GET['add'] . "',"
            . " '$id_location',"
            . " '$razdel',"
            . " '" . $thing['name'] . "',"
            . " '" . $thing['platinum'] . "',"
            . " '" . $thing['money'] . "',"
            . " '" . $thing['level'] . "'"
            . ")");
    if (empty($thing['name'])) {
        $thing['name'] = "???";
    }

    $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> добавил вещь </font><a onclick=\\'showContent(\\\"/admin/shop_equip/edit.php?id_location=" . $id_location . "&razdel=" . $razdel . "\\\")\\'><font color=\\'#0033cc\\'>" . $thing['name'] . " в " . $thisloc['Name'] . " раздел " . $razdelName . "</font></a><font color=\\'#0033cc\\'> !</font>";
    $mc->query("INSERT INTO `chat`("
            . "`id`,"
            . "`name`,"
            . "`id_user`,"
            . "`chat_room`,"
            . "`msg`,"
            . "`msg2`,"
            . "`time`,"
            . " `unix_time"
            . "`) VALUES ("
            . "NULL,"
            . "'АДМИНИСТРИРОВАНИЕ',"
            . "'',"
            . "'5',"
            . " '" . $chatmsg . "',"
            . "'',"
            . "'',"
            . "''"
            . " )");
}
?>
<center>-Редактор Магазина  <?= $thisloc['Name']; ?>-</center>
<center>-<?= $razdelName; ?>-</center>
<?php
$shop_razdelAll = $mc->query("SELECT `id`,`id_shop` FROM `shop_equip` WHERE `id_location`='$id_location' && `id_punct_shop`='$razdel' ORDER BY `level`,`platinum`,`money`")->fetch_all(MYSQLI_ASSOC);
for ($i = 0; $i < count($shop_razdelAll); $i++) {
    $shopThing = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $shop_razdelAll[$i]['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
    ?>
    <div class="clanturblock" style="padding: 6px;" >
        <table style="width: 100%;margin: auto;">
            <tr>
                <td style="width: 40px;">
                    <div class="shop2icobg shop2ico<?= $shopThing['id_image']; ?>"></div>
                </td>
                <td style="max-width: 100%;">
                    <?php
                    $colorStyle = ["black", "green", "blue", "red", "yellow"];
                    echo '<font style="color:' . $colorStyle[$shopThing['stil']] . ';">' . $shopThing['name'] . '</font>';
                    ?>
                    [<?= $shopThing['level']; ?>]
                    <?php
                    $zolo = money($shopThing['money'], "zoloto");
                    $med = money($shopThing['money'], "med");
                    $serebro = money($shopThing['money'], "serebro");
                    $platinum = $shopThing['platinum'];

                    if ($platinum > 0) {
                        ?>
                        <img src="/images/icons/plata.png" width="16px"><?= $platinum; ?>
                    <?php }if ($zolo > 0) { ?>
                        <img src="/images/icons/zoloto.png" width="16px"><?= $zolo; ?>
                    <?php }if ($serebro > 0) { ?>
                        <img src="/images/icons/serebro.png" width="16px"><?= $serebro; ?>
                    <?php }if ($med > 0) { ?>
                        <img src="/images/icons/med.png" width="16px"><?= $med; ?>
                    <?php } ?>
                    id:<?= $shopThing['id']; ?>:
                    <a onclick="showContent('/admin/shop.php?shop=edit&id=<?= $shopThing['id']; ?>')">
                        Изменить
                    </a>
                </td>
                <td style="width: 50px;text-align: center">
                    <button onclick="showContent('/admin/shop_equip/edit.php?id_location=<?= $id_location; ?>&razdel=<?= $razdel; ?>&delete=<?= $shop_razdelAll[$i]['id']; ?>')" class="button" style="width:100%;text-align: center" >Убрать</button>
                </td>
            </tr>
        </table>
    </div>
    <?php
}
?>

<table style="width: 100%;margin: auto;">
    <tr>
        <td style="width: 50px;">
            <input class='id_shop' type='number' value="0" style='width: 98%;height: 40px;'>
        </td>
        <td style="max-width: 100%;">
            <input onkeyup="search(this.value)" class='name_monster' type='text' style='width: 98%;height: 40px;'>
        </td>
        <td style="width: 50px;text-align: center;">
            <button onclick="add();" class="button" style="width:100%;height: 40px;text-align: center" >Добавить</button>
        </td>
    </tr>
</table>
<div class="search">

</div>
<script>
    function add() {
        showContent('/admin/shop_equip/edit.php?id_location=<?= $id_location; ?>&razdel=<?=$razdel;?>&add=' + $(".id_shop").val());
    }
    function add2(a) {
        showContent('/admin/shop_equip/edit.php?id_location=<?= $id_location; ?>&razdel=<?=$razdel;?>&add=' + a);
    }
    function search(etext) {
        var arr;
        $.ajax({
            type: "POST",
            url: "/admin/shop_equip/search.php?etext=" + etext,
            dataType: "text",
            success: function (data) {
                $(".search").html("");
                if (data != "") {
                    arr = JSON.parse(data);
                    for (var i = 0; i < arr.length; i++) {
                        addShopSearched(arr[i].name, arr[i].level, arr[i].id);
                    }
                }
            },
            error: function () {
                $(".search").html("error");
            }
        });
    }
    function addShopSearched(name, level, id) {
        $(".search").append(
                '<table style="width: 100%;margin: auto;"><tr><td style="max-width: 100%;text-align: center;">' +
                name + ' [' + level + '] id : ' + id +
                '</td><td style="width: 50px;text-align: center;">' +
                '<button onclick="add2(' + id + ');" class="button" style="width:100%;height: 40px;text-align: center" >Добавить</button></td></tr></table>'
        );
    }
</script>