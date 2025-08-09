<?php
require_once ('system/func.php');
?>
<center>-Новости-</center>
<table class="table_block2">
    <tr>
        <td class="block101" style="width: 2%"></td>
        <td class="block102" style="width: 96%"></td>
        <td class="block103" style="width: 2%"></td>
    </tr>
    <tr>
        <td class="block104" style="width: 2%"></td>
        <td class="block105" style="width: 96%">
            <?php
            $new = $mc->query("SELECT * FROM `news` ORDER BY `id` DESC ");
            while ($result = $new->fetch_array(MYSQLI_ASSOC)) {
                ?>
                <table style="margin: auto;width: 100%;padding: 6px;">
                    <tr>
                        <td style="width: 100%;text-align: center;padding-bottom: 6px;">
                            <?= $result['date']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100%;text-align: center;padding-bottom: 6px;">
                            <b><?= $result['title']; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100%;text-align: center;color:#693401;padding-bottom: 6px;">
                            <?= $result['text']; ?>
                        </td>
                    </tr>
                    <?php if ($user['access'] > 3) { ?>
                        <tr>
                            <td style="width: 100%;text-align: right;padding-bottom: 6px;">
                                <button onclick="showContent('/new.php?delete&id=<?= $result['id']; ?>')">Удалить</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <table style="margin: auto;width: 100%">
                    <tr>
                        <td style="width: 100%;text-align: center;">
                            <hr>
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </td>
        <td class="block106" style="width: 2%"></td>
    </tr>
    <tr>
        <td class="block107"></td>
        <td class="block108"></td>
        <td class="block109"></td>
    </tr>
</table>          
<script>

</script>
<?php
if (isset($_GET['delete']) && isset($_GET['id'])) {
    if ($mc->query("DELETE FROM `news` WHERE `id`= '" . $_GET['id'] . "'")) {
        message("Новость удалена");
    }
}
$footval = "top";
require_once ('system/foot/foot.php');
?>