<?php
require_once 'system/header.php';
require_once 'system/func.php';

$actio = $mc->query("SELECT * FROM `action`");
if ($actio->num_rows > 0) {
    ?>
    <center>--специальные акции--</center>
    <?php
    while ($action = $actio->fetch_assoc()) {
        $shops = explode(",", $action['id_shop']);
        ?>
        <table class="table_block2">
            <tr>
                <td class="block101" style="width: 2%"></td>
                <td class="block102" style="width: 96%"></td>
                <td class="block103" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block104" style="width: 2%"></td>
                <td class="block105" style="width: 96%; text-align: center;">
                    <center>
                        <strong>-- <?= htmlspecialchars($action['name'], ENT_QUOTES, 'UTF-8'); ?> --</strong>
                        <br><br>
                        <?php
                        foreach ($shops as $shopId) {
                            // Защита от SQL-инъекций
                            $stmt = $mc->prepare("SELECT name FROM shop WHERE id = ?");
                            $stmt->bind_param('i', $shopId);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($shop = $result->fetch_assoc()) {
                                echo htmlspecialchars($shop['name'], ENT_QUOTES, 'UTF-8') . "<br>";
                            }
                            $stmt->close();
                        }
                        ?>
                        <br><br>
                    </center>
                    <span style="text-align: left">Цена: <?= htmlspecialchars($action['money'], ENT_QUOTES, 'UTF-8'); ?> рублей</span>
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
} else {
    echo "<center>Акции пока недоступны</center>";
}

$footval = "bank";
require_once('system/foot/foot.php');
?>
