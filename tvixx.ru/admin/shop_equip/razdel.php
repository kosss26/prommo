<?php
require_once '../../system/func.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}
$footval = 'shop_edit';
include '../../system/foot/foot.php';

$id_location=0;
if(isset($_GET['id_location'])&&$_GET['id_location']>0){
    $id_location=$_GET['id_location'];
}

$c1=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='1' ")->num_rows;
$c2=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='2' ")->num_rows;
$c3=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='3' ")->num_rows;
$c4=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='4' ")->num_rows;
$c5=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='5' ")->num_rows;
$c10=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='10' ")->num_rows;
$c11=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='11' ")->num_rows;
$c12=$mc->query("SELECT * FROM `shop_equip` WHERE `id_location` = '$id_location' && `id_punct_shop`='12' ")->num_rows;
?>


<center>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=1&id_location=<?=$id_location;?>')">Оружие (<?= $c1; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=2&id_location=<?=$id_location;?>')">Броня (<?= $c2; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=3&id_location=<?=$id_location;?>')">Зелья и Свитки (<?= $c3; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=4&id_location=<?=$id_location;?>')">Амулеты (<?= $c4; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=5&id_location=<?=$id_location;?>')">Разное (<?= $c5; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=10&id_location=<?=$id_location;?>')">Для заданий (<?= $c10; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=11&id_location=<?=$id_location;?>')">Бонусы (<?= $c11; ?>)</div>
    <div class="button_alt_01" onclick="showContent('/admin/shop_equip/edit.php?razdel=12&id_location=<?=$id_location;?>')">Скрытые (<?= $c12; ?>)</div>
</center>