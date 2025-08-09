<?php
require_once 'system/func.php';
require_once 'system/dbc.php';
require_once 'system/header.php';

$colorStyle = array("black", "green", "blue", "red", "yellow");
$textStyle = array("", "Урон", "Уворот", "Броня", "Элита");

$ids = $mc->query("SELECT `id_shop` FROM `userbag` WHERE `id_user` = '".$user['id']."' AND `iznos` = '0'")->fetch_all(MYSQLI_ASSOC);

if(isset($_GET['remont']) && isset($_GET['id'])){
	$shop = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $_GET['id'] . "'")->fetch_array(MYSQLI_ASSOC);
	//если не платина
	if($user['money'] >= $shop['money'] && $shop['money'] > 0){
		$cena = $shop['money'] / 2;
		if($mc->query("UPDATE `users` SET `money` = `money` - '".$cena."' WHERE `id` = '".$user['id']."'")){
			$mc->query("UPDATE `userbag` SET `iznos` = '".$shop['iznos']."' WHERE `id_shop` = '".$shop['id']."'");
            ?><script>showContent("/main.php?msg=успешно")</script><?php
        }
    }
}

?><center>--ремонт--</center><?php

?><style>
.repair-container {
    background: linear-gradient(135deg, #2C1810, #1A0F0A);
    padding: 30px 40px 30px 20px;
    margin: 0 auto;
    width: calc(100% - 60px);
    max-width: 780px;
    color: #E8D5B5;
    box-sizing: border-box;
    min-height: calc(100vh - 60px);
}

.repair-content {
    background: rgba(196, 174, 127, 0.9);
    border: 2px solid #7B4811;
    border-radius: 8px;
    padding: 15px;
    margin: 0;
    width: 100%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.repair-header {
    font-size: 20px;
    font-weight: bold;
    color: #663300;
    text-align: center;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    background: url('/images/scroll-header.png') center no-repeat;
    background-size: contain;
    padding: 15px;
}

.repair-item {
    background: rgba(211, 198, 163, 0.9);
    border: 2px solid #7B4811;
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 15px;
}

.repair-item-content {
    display: flex;
    gap: 15px;
    align-items: center;
}

.repair-item-image {
    width: 90px;
    flex-shrink: 0;
}

.repair-item-info {
    flex-grow: 1;
}

.repair-item-name {
    font-size: 16px;
    font-weight: bold;
    color: #4A3520;
    margin-bottom: 5px;
}

.repair-item-style {
    margin: 5px 0;
}

.repair-item-stats {
    color: #4A3520;
    font-size: 14px;
    margin: 5px 0;
}

.repair-item-price {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 5px 0;
}

.repair-button {
    background: #FF8B00;
    color: #FFF;
    border: 2px solid #663300;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
    margin-top: 10px;
    width: 100%;
    text-align: center;
}

.repair-button:hover {
    background: #FFE200;
    color: #663300;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

@media screen and (max-width: 840px) {
    .repair-container {
        width: 100%;
        margin: 0;
        padding: 20px 30px 20px 15px;
        border-radius: 0;
    }
    
    .repair-content {
        border-radius: 0;
    }
    
    .repair-item-content {
        flex-direction: column;
        text-align: center;
    }
    
    .repair-item-image {
        margin: 0 auto;
    }
}
</style>

<div class="repair-container">
    <div class="repair-content">
        <div class="repair-header">Ремонт снаряжения</div>
        
        <?php for ($x = 0; $x < count($ids); $x++) {
            $shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $ids[$x]['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="repair-item">
                <div class="repair-item-content">
                    <div class="repair-item-image">
                        <div class="shopicobg shopico<?= $shopmagazin['id_image']; ?>"></div>
                    </div>
                    <div class="repair-item-info">
                        <div class="repair-item-name">
                            <?php if ($shopmagazin['stil'] > 0): ?>
                                <font style="color:<?= $colorStyle[$shopmagazin['stil']] ?>;font-weight: bold;">
                                    <?= $shopmagazin['name'] ?>
                                </font>
                            <?php else: ?>
                                <?= $shopmagazin['name'] ?>
                            <?php endif; ?>
                            
                            <?php if ($user['access'] > 2): ?>
                                (id: <?= $shopmagazin['id'] ?>)
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($shopmagazin['stil'] > 0): ?>
                            <div class="repair-item-style">
                                <font style="color:<?= $colorStyle[$shopmagazin['stil']] ?>;font-weight: bold;">
                                    <?= $textStyle[$shopmagazin['stil']] ?>
                                </font>
                            </div>
                        <?php endif; ?>
                        
                        <div class="repair-item-stats">
                            Уровень: <?= $shopmagazin['level'] ?>
                        </div>
                        
                        <div class="repair-item-price">
                            Цена ремонта: 
                            <?php
                            $zolo = money($shopmagazin['money'], "zoloto");
                            $med = money($shopmagazin['money'], "med");
                            $serebro = money($shopmagazin['money'], "serebro");
                            $platinum = $shopmagazin['platinum'];

                            if ($platinum > 0) {
                                echo '<img src="/images/icons/plata.png" width="16"><span style="color:red">' . ($platinum / 2) . '</span> ';
                            }
                            if ($zolo > 0) {
                                echo '<img src="/images/icons/zoloto.png" width="16"><span style="color:red">' . ($zolo / 2) . '</span> ';
                            }
                            if ($serebro > 0) {
                                echo '<img src="/images/icons/serebro.png" width="16"><span style="color:red">' . ($serebro / 2) . '</span> ';
                            }
                            if ($med > 0) {
                                echo '<img src="/images/icons/med.png" width="16"><span style="color:red">' . ($med / 2) . '</span>';
                            }
                            ?>
                        </div>
                        
                        <button class="repair-button" onclick="showContent('remont.php?remont&id=<?= $shopmagazin['id'] ?>')">
                            Ремонтировать
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
$footval = 'shoptomain';
require_once 'system/foot/foot.php';
?>
    
    