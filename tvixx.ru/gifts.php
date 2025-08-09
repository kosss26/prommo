<?php
require_once ('system/func.php');
if (isset($_GET['del']) && isset($_GET['gifts_del']) && isset($_GET['id'])) {
    if($_GET['id']==$user['id']||$user['access']>1) {
        $mc->query("DELETE FROM `gifts` WHERE `id_2` = '" . $_GET['id'] . "' && `id` = '" . $_GET['gifts_del'] . "'");
        message(urlencode("Подарок выброшен."));
    } else {
        message(urlencode("<font style='color:red'>не вышло блииииин админы узнаю пизды вломят блииииииин</font>"));
    }
}


if (!isset($_GET['gifts'])) {
    if (isset($_GET['id'])) {
        $user2 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $_GET['id'] . "'")->fetch_array(MYSQLI_ASSOC);
        ?>
        <center>Подарки <?= $user2['name']; ?></center><?php
        $gifts = $mc->query("SELECT * FROM `gifts` WHERE `id_2` = '" . $_GET['id'] . "' ORDER BY `id` DESC");
        ?>
        <table  style="margin: auto;width: 100%">
            <tr>
                <td style="text-align: center;">
                    <?php while ($result = $gifts->fetch_array(MYSQLI_ASSOC)) { ?>
                        <img onclick="showContent('gifts.php?gifts=<?= $result['id']; ?>&id=<?= $_GET['id']; ?>')" style="width: 80px;height: 80px;" class="shops" src='/images/gifts/<?= $result['id_img']; ?>.png'>
                    <?php } ?> 
                </td>
            </tr>
        </table>
        <?php
    }
}

if (isset($_GET['gifts']) && isset($_GET['id'])) {
    ?>
    <table class="table_block2">
        <tr>
            <td class="block101" style="width: 2%"></td>
            <td class="block102" style="width: 96%"></td>
            <td class="block103" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block104" style="width: 2%"></td>
            <td class="block105" style="width: 96%"><?php
                $gift = $mc->query("SELECT * FROM `gifts` WHERE `id` = '" . $_GET['gifts'] . "'  ORDER BY `id` DESC")->fetch_array(MYSQLI_ASSOC);
                $user1 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $gift['id_1'] . "'")->fetch_array(MYSQLI_ASSOC);
                ?>
        <center>
            <img src='/images/gifts/<?= $gift['id_img']; ?>.png'>
            <br>
            <b><?= $gift['name']; ?></b>
            <br>
            <?= $gift['text']; ?>
            <br>
        </center>
        <div style="text-align: right">
            <?php if ($gift['anonymous'] > 0 || $user['access'] > 2) { ?>
                <a onclick="showContent('profile.php?id=<?= $user1['id']; ?>')">
                    <ins><?= $user1['name']; ?></ins>
                </a>
            <?php } ?>
            <br>
            <?= $gift['date_gifts']; ?>
        </div>
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
$footval = 'gifts';
require_once 'system/foot/foot.php';
?>