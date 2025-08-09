<?php
require_once 'system/func.php';
require_once 'system/dbc.php';
require_once 'system/header.php';
auth(); // Закроем от неавторизированых

$id_shop = 1803;
$id_shop2 = 1892;
$eventlevel = 0;

if(0 <= $user['level'] && $user['level'] <= 9)
{
    //жало 10 лвл
    $id_shop2 = 1892;
    $eventlevel = "10";
}else if(10 <= $user['level'] && $user['level'] <= 14)
{
    //10 ур копья
    $id_shop2 = 1893;
   // $id_shop3 = 1892;
    $eventlevel = "10 и 15";
}else if(15 <= $user['level'] && $user['level'] <= 20)
{
    //15 ур копья
    $id_shop2 = 1894;
    //$id_shop3 = 1893;
    $eventlevel = "15 и 20";
}else {
    //20 ур копья
    $id_shop2 = 1895;
    $eventlevel = "21";
}



$EventDress = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $id_shop . "'")->fetch_array(MYSQLI_ASSOC);
$EventDress2 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $id_shop2 . "'")->fetch_array(MYSQLI_ASSOC);


    if ($EventDress2['health'] < 0) {
        $maghealth = '';
    } else {
        $maghealth = '+';
    }
    if ($EventDress2['strength'] < 0) {
        $magstrength = '';
    } else {
        $magstrength = '+';
    }
    if ($EventDress2['toch'] < 0) {
        $magtoch = '';
    } else {
        $magtoch = '+';
    }
    if ($EventDress2['lov'] < 0) {
        $maglov = '';
    } else {
        $maglov = '+';
    }
    if ($EventDress2['kd'] < 0) {
        $magkd = '';
    } else {
        $magkd = '+';
    }
    if ($EventDress2['block'] < 0) {
        $magblock = '';
    } else {
        $magblock = '+';
    }

    $nameeffects = "";
        if (isset($EventDress2['nameeffects'])) {
            $effects = explode("|", $EventDress2['nameeffects']);
            for ($i = 0; count($effects) > $i; $i++) {
                if ($effects[$i] !== "") {
                    $nameeffects .= "<b style='padding-top: 3px;padding-bottom: 3px;'>" . $effects[$i] . "</b><br>";
                }
            }
        }
?>
<center style="padding-top: 3px;padding-bottom: 3px;text-align: center;">
    <b>ИВЕНТ ВКЛЮЧАЕТ:</b>
</center>


<!--Первое оружие-->
<center style="padding-top: 3px;padding-bottom: 3px;text-align: center;">
    <b><?= $EventDress['name'] ?></b>
</center>
    <table class="table_block2" style="width: 98%;">
        <tbody>
            <tr>
                <td style="width: 90px;"><div class="shopicobg shopico<?= $EventDress['id_image']; ?>"></div></td>
                <td style="display: unset;"><?= $EventDress['opisanie'] ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table_block2" style="width: 96%;">
        <tbody>
            <tr><td style="padding-top: 10px;padding-bottom: 3px;"></td></tr>
        </tbody>
    </table>
    <hr class="hr_01"/>
    <table class="table_block2" style="width: 96%;">
        <tbody>
            <tr><td style="padding-top: 10px;padding-bottom: 3px;"></td></tr>
        </tbody>
    </table>

<!--Второе оружие-->
<center style="padding-top: 3px;padding-bottom: 3px;text-align: center;">
    <b><?= $EventDress2['name'] ?></b>
</center>
    <table class="table_block2" style="width: 98%;">
        <tbody>
            <tr>
                <td style="width: 90px;"><div class="shopicobg shopico<?= $EventDress2['id_image']; ?>"></div></td>
                <td style="display: unset;"><?= $EventDress2['opisanie'] ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table_block2" style="width: 98%;">
     <tr>
        <td style="width: 140px;padding-top: 3px;padding-bottom: 3px;">Уровень:</td>
        <td>
            <img src="/img/img23.png" width="16px">
            <?= $eventlevel; ?>
        </td>
    </tr>

    <?php if ($EventDress2['koll'] > -1) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Количество:</td>
            <td><?= $EventDress2['koll']; ?></td>
        </tr>
    <?php }if ($EventDress2['iznos'] > -1) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Износ:</td>
            <td><?= $EventDress2['iznos']; ?></td>
        </tr>
    <?php }if ($EventDress2['time_s'] > 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Годность время:</td>
            <td><?= age_times($EventDress2['time_s']); ?></td>
        </tr>
    <?php }if ($EventDress2['toch'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Точность:</td>
            <td><?= ico('icons', 'toch.png') . " " . $magtoch . $EventDress2['toch']; ?></td>
        </tr>
    <?php }if ($EventDress2['strength'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Урон:</td>
            <td><?= ico('icons', 'power.jpg') . " " . $magstrength . $EventDress2['strength']; ?></td>
        </tr>
    <?php }if ($EventDress2['block'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Блок:</td>
            <td><?= ico('icons', 'shit.png') . " " . $magblock . $EventDress2['block']; ?></td>
        </tr>
    <?php }if ($EventDress2['kd'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Оглушение:</td>
            <td><?= ico('icons', 'kd.png') . " " . $magkd . $EventDress2['kd']; ?></td>
        </tr>
    <?php }if ($EventDress2['lov'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Уворот:</td>
            <td><?= ico('icons', 'img235.png') . " " . $maglov . $EventDress2['lov']; ?></td>
        </tr>   
    <?php }if ($EventDress2['bron'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Броня:</td>
            <td><?= ico('icons', 'bron.png') . " " . $magbron . $EventDress2['bron']; ?></td>
        </tr>   
    <?php }if ($EventDress2['health'] != 0) { ?>
        <tr>
            <td style="padding-top: 3px;padding-bottom: 3px;">Здоровье:</td>
            <td><?= ico('icons', 'hp.png') . " " . $maghealth . $EventDress2['health']; ?></td>
        </tr>
    <?php } ?> 
        </table> 

         <table  class="table_block2" style="width: 96%;">
            <tr>
                <td style="width:100%;padding-top: 3px;padding-bottom: 3px;"><?= $nameeffects; ?></td>
            </tr>
        </table>

    <table class="table_block2" style="width: 96%;">
        <tbody>
            <tr><td style="padding-top: 30px;padding-bottom: 3px;"></td></tr>
        </tbody>
    </table>

    <table class="table_block2" style="width: 96%;">
        <tbody>
            <tr>
                <td style="width:200px;padding-top: 3px;padding-bottom: 3px;">Цена: 150 руб.</td>
            </tr>
        </tbody>
    </table>


    <table class="table_block2" style="width: 96%;">
        <tbody>
            <tr>
                    <td style="width:100%;padding-top: 3px;padding-bottom: 3px;"></td>
            </tr>
        </tbody>
    </table>

    <br>

    <table class="table_block2" style="width: 96%;">
        <tbody>
            <tr>
                <td style="width:20px;"></td>
            </tr>
        </tbody>
    </table>
    <br>
                    

     <div><a onclick="showContent('/freekassa.php?pay=150&event=1')">Купить</a></div>



  <?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $footval = 'backtoshop';
    require_once 'system/foot/foot.php';
  ?>     