<?php
require_once ('system/func.php');
$level = 0;
// min lvl , max lvl , id shop || rand  , price , просто номер ур для удобства

$newAuk = $mc->query("SELECT * FROM `auk_shop` WHERE `minLevel` <= '" . $user['level'] . "' && `maxLevel` >= '" . $user['level'] . "'")->fetch_array(MYSQLI_ASSOC);
$minl = $newAuk['minLevel'];
$maxl = $newAuk['maxLevel'];
$idShops = explode(",", $newAuk['id_shop']);
$idShop = $idShops[$newAuk['num']];
$pr = explode(",", $newAuk['minPlata']);
$price = $pr[$newAuk['num']];
$timer = strtotime("+22 hour", time());
//если нет предмета для моих уровней
if ($mc->query("SELECT * FROM `auk` WHERE `level` >= '$minl' && `level` <= '$maxl' LIMIT 1 ")->num_rows == 0) {
    $mc->query("INSERT INTO `auk` (`id`,`time`,`id_shop`,`min_plata`,`torg`,`level`) VALUES (NULL,'" . $timer . "','$idShop','$price','0','$maxl')");
}
if ($auk = $mc->query("SELECT * FROM `auk` WHERE `level` >= '$minl' && `level` <= '$maxl' LIMIT 1 ")->fetch_array(MYSQLI_ASSOC)) {
    $user_bet = 0;
    if ($auk_user_bet = $mc->query("SELECT * FROM `auk_user` WHERE `id_lot`='" . $auk['id'] . "' &&  `id_user`='" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC)) {
        $user_bet = $auk_user_bet['plata'];
    }
    if (isset($_GET['go']) && !empty($_GET['plata'])) {
        if ($mc->query("SELECT * FROM `auk_user` WHERE `id_user` = '" . $user['id'] . "' && `id_lot`='" . $auk['id'] . "'")->num_rows > 0) {
            if ($user_bet + $_GET['plata'] < $auk['min_plata']) {
                message(urlencode("Минимальная ставка должна быть " . $auk['min_plata'] . "<img src='/images/icons/plata.png'> ."));
            } else {
                if ($_GET['plata'] > 0) {
                    if ($user['platinum'] >= $_GET['plata']) {
                        if ($mc->query("UPDATE `auk_user` SET `plata` = `plata`+'" . $_GET['plata'] . "' WHERE `id_lot`='" . $auk['id'] . "' &&  `id_user`='" . $user['id'] . "'")) {
                            $mc->query("UPDATE `users` SET `platinum` = `platinum`-'" . $_GET['plata'] . "' WHERE `id` = '" . $user['id'] . "'");
                            // Добавляем JavaScript для обновления страницы после успешной ставки
                            echo "<script>
                                setTimeout(function() {
                                    showContent('/auk.php');
                                }, 1000);
                            </script>";
                            message(urlencode("Ставка принята ."));
                        }
                    } else {
                        message(urlencode("Недостаточно средств ."));
                    }
                } else {
                    message(urlencode("Ставка не может быть меньше 1<img src='/images/icons/plata.png'> ."));
                }
            }
        } else {
            if ($_GET['plata'] < $auk['min_plata']) {
                message(urlencode("Минимальная ставка должна быть " . $auk['min_plata'] . "<img src='/images/icons/plata.png'> ."));
            } elseif ($_GET['plata'] < 1) {
                message(urlencode("Ставка не может быть меньше 1<img src='/images/icons/plata.png'> ."));
            } elseif ($user['platinum'] < $_GET['plata']) {
                message(urlencode("Недостаточно средств ."));
            } else {
                if ($mc->query("INSERT INTO `auk_user` (`id_user`,`plata`,`id_lot`) VALUES ('" . $user['id'] . "','" . $_GET['plata'] . "','" . $auk['id'] . "')")) {
                    $mc->query("UPDATE `users` SET `platinum` = `platinum`-'" . $_GET['plata'] . "' WHERE `id` = '" . $user['id'] . "'");
                    // Добавляем JavaScript для обновления страницы после успешной ставки
                    echo "<script>
                        setTimeout(function() {
                            showContent('/auk.php');
                        }, 1000);
                    </script>";
                    message(urlencode("Ставка принята ."));
                }
            }
        }
    }
    $auk_user = $mc->query("SELECT * FROM `auk_user` WHERE `id_lot`='" . $auk['id'] . "' ORDER BY `plata` DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
    $shop = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $auk['id_shop'] . "' ")->fetch_array(MYSQLI_ASSOC);
    //конкретное оружие
    if (isset($_GET['shop'])) {
        $ipunct = 'shopicobg';
        if ($shop['health'] < 0) {
            $maghealth = '';
        } else {
            $maghealth = '+';
        }
        if ($shop['strength'] < 0) {
            $magstrength = '';
        } else {
            $magstrength = '+';
        }
        if ($shop['toch'] < 0) {
            $magtoch = '';
        } else {
            $magtoch = '+';
        }
        if ($shop['lov'] < 0) {
            $maglov = '';
        } else {
            $maglov = '+';
        }
        if ($shop['kd'] < 0) {
            $magkd = '';
        } else {
            $magkd = '+';
        }
        if ($shop['block'] < 0) {
            $magblock = '';
        } else {
            $magblock = '+';
        }

        $nameeffects = "";
        if (isset($shop['nameeffects'])) {

            $effects = explode("|", $shop['nameeffects']);
            for ($i = 0; count($effects) > $i; $i++) {
                if ($effects[$i] !== "") {
                    $nameeffects .= "<b style='padding-top: 3px;padding-bottom: 3px;'>" . $effects[$i] . "</b><br>";
                }
            }
        }
        if ($shop['bron'] < 0) {
            $magbron = '';
        } else {
            $magbron = '+';
        }
        ?>
        <center style="padding-top: 3px;padding-bottom: 3px;text-align: center;">
            <b>
                <?= $shop['name']; ?>
            </b>
        </center>
        <table  class="table_block2" style="width: 98%;">
            <tr>
                <td  style="width: 90px;padding-top: 3px;padding-bottom: 3px;">
                    <div class="<?= $ipunct; ?> shopico<?= $shop['id_image']; ?>">
                    </div>
                </td>
                <td style="display: unset;">
                    <?= $shop['opisanie']; ?>
                </td>
            </tr>
        </table>
        <table  class="table_block2" style="width: 96%;">
            <tr>
                <td style="padding-top: 3px;padding-bottom: 3px;">
                    <?php
                    if ($shop['stil'] > 0) {
                        $colorStyle = ["black", "green", "blue", "red", "yellow"];
                        //$textStyle = ["", "Моща", "Прыг", "Танк", "Цари"];
                        $textStyle = ["", "Урон", "Уворот", "Броня", "Элита"];
                        echo '<font style="color:' . $colorStyle[$shop['stil']] . ';font-weight: bold;">' . $textStyle[$shop['stil']] . '</font>';
                    }
                    ?>
                </td>
            </tr>  
        </table>
        <table  class="table_block2" style="width: 96%;">
            <tr>
                <td style="width: 140px;padding-top: 3px;padding-bottom: 3px;">Уровень:</td>
                <td>
                    <img src="/img/img23.png" width="16px">
                    <?= $shop['level']; ?>
                </td>
            </tr>

            <?php if ($shop['koll'] > -1) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Количество:</td>
                    <td><?= $shop['koll']; ?></td>
                </tr>
            <?php }if ($shop['iznos'] > -1) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Износ:</td>
                    <td><?= $shop['iznos']; ?></td>
                </tr>
            <?php }if ($shop['time_s'] > 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Годность время:</td>
                    <td><?= age_times($shop['time_s']); ?></td>
                </tr>
            <?php }if ($shop['toch'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Точность:</td>
                    <td><?= ico('icons', 'toch.png') . " " . $magtoch . $shop['toch']; ?></td>
                </tr>
            <?php }if ($shop['strength'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Урон:</td>
                    <td><?= ico('icons', 'power.jpg') . " " . $magstrength . $shop['strength']; ?></td>
                </tr>
            <?php }if ($shop['block'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Блок:</td>
                    <td><?= ico('icons', 'shit.png') . " " . $magblock . $shop['block']; ?></td>
                </tr>
            <?php }if ($shop['kd'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Оглушение:</td>
                    <td><?= ico('icons', 'kd.png') . " " . $magkd . $shop['kd']; ?></td>
                </tr>
            <?php }if ($shop['lov'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Уворот:</td>
                    <td><?= ico('icons', 'img235.png') . " " . $maglov . $shop['lov']; ?></td>
                </tr>	
            <?php }if ($shop['bron'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Броня:</td>
                    <td><?= ico('icons', 'bron.png') . " " . $magbron . $shop['bron']; ?></td>
                </tr>	
            <?php }if ($shop['health'] != 0) { ?>
                <tr>
                    <td style="padding-top: 3px;padding-bottom: 3px;">Здоровье:</td>
                    <td><?= ico('icons', 'hp.png') . " " . $maghealth . $shop['health']; ?></td>
                </tr>
            <?php } ?> 
        </table> 
        <table  class="table_block2" style="width: 96%;">
            <tr>
                <td style="width:100%;padding-top: 3px;padding-bottom: 3px;">
                    <?= $nameeffects; ?>
                </td>
            </tr>
        </table>
        <br>
        <table  class="table_block2" style="width: 96%;">
            <tr>
                <td style="width: 20px;"> </td>
                <td >
                    <?php if ($user['access'] > 2) { ?>
                        <b>ID вещи: <?= $shop['id']; ?></b>
                        <div>
                            <a onclick="showContent('/admin/shop.php?shop=edit&id=<?= $shop['id']; ?>')">
                                Изменить (Админ)
                            </a>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <br>
        <?php
        $footval = 'auktoshop';
    }

    if (!isset($_GET['shop'])) {
        $ex = 0;
        ?>
        <div class="auction-item">
            <table class="table_block2">
                <tr>
                    <td class="block105" style="width: 96%;text-align: center;">
                        <div class="shopicobg shopico<?= $shop['id_image']; ?>"></div>
                        <a onclick="showContent('/auk.php?shop')" class="item-name">
                            <?= $shop['name']; ?>
                        </a>
                    </td>
                </tr>
            </table>    

            <div class="bid-section">
                <?php if ($ex == 0) { ?>
                    <div class="auction-timer">
                        <?php if ($auk['torg'] <= 0) { ?>
                            <div class="auction-type">Открытые торги</div>
                        <?php } else { ?>
                            <div class="auction-type">Закрытые торги</div>
                        <?php } ?>
                        до <?= date("d:h:i", $auk['time'] + 10800); ?>
                    </div>

                    <input class="bid-input plata" type="number" name="plata" placeholder="Введите ставку">
                    <button class="bid-button" onclick="makeBid()">Сделать ставку</button>
                <?php } else { ?>
                    <span style="color:red">Технические работы</span>
                <?php } ?>
            </div>

            <div class="bids-list">
                <?php
                if ($auk['torg'] == 0) {
                    foreach ($auk_user as $bid) {
                        $auk1_user = $mc->query("SELECT * FROM `users` WHERE `id`='" . $bid['id_user'] . "' ")->fetch_array(MYSQLI_ASSOC);
                        ?>
                        <div class="bid-row">
                            <span class="bidder-name <?= $bid['id_user'] == $user['id'] ? 'current-user' : ''; ?>">
                                <?= $auk1_user['name']; ?>
                            </span>
                            <span class="bid-amount">
                                <img src="/images/icons/plata.png" alt="Плата">
                                <?= $bid['plata']; ?>
                            </span>
                        </div>
                    <?php }
                }

                if ($auk['torg'] == 1) {
                    $auk1_user = $mc->query("SELECT * FROM `users` WHERE `id`='" . $auk['id_lider'] . "' ")->fetch_array(MYSQLI_ASSOC);
                    ?>
                    <div class="auction-leader">
                        <h3>Лидер открытых торгов</h3>
                        <div class="bid-row">
                            <span class="bidder-name <?= $auk1_user['id'] == $user['id'] ? 'current-user' : ''; ?>">
                                <?= $auk1_user['name']; ?>
                            </span>
                            <span class="bid-amount">
                                <img src="/images/icons/plata.png" alt="Плата">
                                <?= $auk['bet_lider']; ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $footval = 'backtoshop';
    }
}

?>
<script>
function makeBid() {
    var bidAmount = $('.plata').val();
    if(bidAmount) {
        showContent('/auk.php?go&plata=' + bidAmount);
    }
}

// Обработка нажатия Enter в поле ввода
$('.plata').keypress(function(e) {
    if(e.which == 13) {
        makeBid();
    }
});
</script>
<style>
/* Основные стили для таблиц и блоков */
.table_block2 {
    background: linear-gradient(to bottom, rgba(139, 69, 19, 0.15), rgba(139, 69, 19, 0.1));
    border: 1px solid rgba(139, 69, 19, 0.3);
    border-radius: 8px;
    margin: 10px auto;
    width: 98%;
    border-collapse: separate;
    border-spacing: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.table_block2 td {
    padding: 8px;
}

/* Стили для предмета аукциона */
.auction-item {
    background: rgba(139, 69, 19, 0.05);
    border: 2px solid rgba(139, 69, 19, 0.3);
    border-radius: 6px;
    padding: 15px;
    margin: 10px 0;
    transition: transform 0.2s;
}

.auction-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(139, 69, 19, 0.2);
}

/* Стили для ставок */
.bid-section {
    background: rgba(139, 69, 19, 0.07);
    border: 1px solid rgba(139, 69, 19, 0.2);
    border-radius: 6px;
    padding: 15px;
    margin: 10px 0;
}

.bid-input {
    width: 200px;
    padding: 10px;
    border: 2px solid rgba(139, 69, 19, 0.4);
    border-radius: 4px;
    text-align: center;
    margin: 10px auto;
    font-size: 16px;
    background: rgba(255, 255, 255, 0.7);
    transition: all 0.3s;
}

.bid-input:focus {
    border-color: #D2691E;
    box-shadow: 0 0 5px rgba(139, 69, 19, 0.3);
    outline: none;
}

.bid-button {
    background: linear-gradient(to bottom, #8B4513, #654321);
    color: #FFF;
    padding: 10px 25px;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.bid-button:hover {
    background: linear-gradient(to bottom, #654321, #8B4513);
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* Стили для списка ставок */
.bids-list {
    background: rgba(139, 69, 19, 0.05);
    border: 1px solid rgba(139, 69, 19, 0.2);
    border-radius: 6px;
    padding: 10px;
    margin-top: 15px;
}

.bid-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
    border-bottom: 1px solid rgba(139, 69, 19, 0.2);
    transition: background 0.2s;
}

.bid-row:hover {
    background: rgba(139, 69, 19, 0.1);
}

/* Стили для иконок предметов */
.shopicobg {
    display: inline-block;
    width: 40px; /* Уменьшаем размер контейнера */
    height: 40px;
    padding: 5px;
    background: rgba(139, 69, 19, 0.1);
    border: 1px solid rgba(139, 69, 19, 0.3);
    border-radius: 8px;
    margin: 10px auto;
    position: relative;
}

/* Стили для самой иконки */
[class^="shopico"] {
    display: inline-block;
    width: 40px; /* Фиксированный размер иконки */
    height: 40px;
    background-image: url('/images/items.png'); /* Путь к спрайту с иконками */
    background-repeat: no-repeat;
    vertical-align: middle;
}

/* Убираем абсолютное позиционирование */
.block105 .shopicobg {
    margin: 5px auto;
    text-align: center;
}

/* Центрирование иконки в контейнере */
.block105 {
    text-align: center;
}

/* Убираем hover эффект, который мог мешать отображению */
.shopicobg:hover {
    transform: none;
    box-shadow: none;
}

/* Добавляем отступ между иконкой и названием предмета */
.item-name {
    display: block;
    margin-top: 10px;
}

/* Адаптивность */
@media (max-width: 768px) {
    .bid-input {
        width: 90%;
    }
    
    .bid-button {
        width: 90%;
        margin: 10px auto;
    }
    
    .table_block2 {
        width: 95%;
        margin: 5px auto;
    }
}

/* Стили для таймера аукциона */
.auction-timer {
    font-size: 1.2em;
    color: #8B4513;
    text-align: center;
    padding: 10px;
    margin: 10px 0;
    background: rgba(139, 69, 19, 0.08);
    border: 1px solid rgba(139, 69, 19, 0.2);
    border-radius: 4px;
}

/* Стили для характеристик предмета */
.item-stats {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 8px;
    padding: 10px;
    background: rgba(139, 69, 19, 0.05);
    border: 1px solid rgba(139, 69, 19, 0.2);
    border-radius: 6px;
    margin: 10px 0;
}

.stat-label {
    font-weight: bold;
    color: #654321;
}

.stat-value {
    display: flex;
    align-items: center;
    gap: 5px;
}

.item-name {
    display: block;
    color: #8B4513;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1em;
    margin: 10px 0;
    transition: color 0.2s;
}

.item-name:hover {
    color: #D2691E;
}

.auction-type {
    font-weight: bold;
    margin-bottom: 5px;
}

.current-user {
    color: #8B4513;
    font-weight: bold;
}

.bidder-name {
    font-size: 1.1em;
}

.bid-amount {
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: bold;
}

.bid-amount img {
    width: 20px;
    height: 20px;
}

.auction-leader {
    text-align: center;
    padding: 10px;
    background: rgba(139, 69, 19, 0.1);
    border: 1px solid rgba(139, 69, 19, 0.3);
    border-radius: 4px;
    margin-top: 10px;
}

.auction-leader h3 {
    margin: 0 0 10px 0;
    color: #8B4513;
}
</style>

<?php
$footval = isset($_GET['shop']) ? 'auktoshop' : 'backtoshop';
require_once ('system/foot/foot.php');
?>