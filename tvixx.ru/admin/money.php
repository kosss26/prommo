<?php
require_once '../system/func.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}

require_once '../system/header.php';
?>
<br><br><br>
<?php
if (isset($_GET['Submit']) && isset($_GET['deshifrovca']) && $_GET['Submit'] == 'Дешифровать') {
    if (empty($_GET['deshifrovca'])) {
        $money = 0;
    } else {
        $money = (int) ltrim($_GET['deshifrovca'], '0');
    }
    $med = $money % 100; ///медь
    $serebro = ($money - $med) / 100 % 100;
    $zoloto = floor(((($money - $med) / 100) - $serebro) / 100);
    echo 'Золото: ' . $zoloto . ' Серебро: ' . $serebro . ' Медь: ' . $med;
    message("дешифровано");
    ?><script>
    $('input[name="zolo"]').val(<?=$zoloto;?>);
    $('input[name="serebro"]').val(<?=$serebro;?>);
    $('input[name="med"]').val(<?=$med;?>);

    </script>
    <?php
}
?>
<form id="form1">
    <input type="number" name="deshifrovca" value="<?php if (isset($_GET['deshifrovca'])) echo $_GET['deshifrovca']; ?>" >
    <input name="Submit" class="button_alt_01 butt1" type="button" value="Дешифровать">
</form>
----------------------------------------------------------
<?php
$zoloto = 0;
$serebro = 0;
$med = 0;
if (isset($_GET['Submit']) && $_GET['Submit'] == 'Зашифровать' && isset($_GET['zolo']) && isset($_GET['serebro']) && isset($_GET['med'])) {
    if (empty($_GET['zolo'])) {
        $zoloto = 0;
    } else {
        $zoloto = (int) ltrim($_GET['zolo'], '0');
    }
    if (empty($_GET['serebro'])) {
        $serebro = 0;
    } else {
        $serebro = (int) ltrim($_GET['serebro'], '0');
    }
    if (empty($_GET['med'])) {
        $med = 0;
    } else {
        $med = (int) ltrim($_GET['med'], '0');
    }
    echo (((($zoloto * 100) + $serebro) * 100) + $med);
    message("зашифровано");
        ?><script>
    $('input[name="deshifrovca"]').val(<?=(((($zoloto * 100) + $serebro) * 100) + $med);?>);
    </script>
    <?php
}
?>
<form id="form2">
    Золото:
    <input type="number" name="zolo" value="<?php if (isset($_GET['zolo'])) echo $_GET['zolo']; ?>" ><br>
    Серебро:
    <input type="number" name="serebro" value="<?php if (isset($_GET['serebro'])) echo $_GET['serebro']; ?>" ><br>
    Медь:
    <input type="number" name="med" value="<?php if (isset($_GET['med'])) echo $_GET['med']; ?>" >
    <input name="Submit" class="button_alt_01 butt2" type="button" value="Зашифровать">
</form>
<script>
    $(".butt1").click(function () {
        showContent("admin/money.php?Submit=" + $(this).val() + "&" + $("#form1").serialize());
    });
    $(".butt2").click(function () {
        showContent("admin/money.php?Submit=" + $(this).val() + "&" + $("#form2").serialize());
    });
</script>

<?php
$footval = 'adminmoney';
include '../system/foot/foot.php';
?>
