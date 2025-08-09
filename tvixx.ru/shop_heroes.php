<?php
require_once 'system/func.php';
require_once 'system/dbc.php';
require_once 'system/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_snyat.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_kupit.php';

?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --bg-grad-start: #111;
        --bg-grad-end: #1a1a1a;
        --accent: #f5c15d;
        --accent-2: #ff8452;
        --card-bg: rgba(255,255,255,0.05);
        --glass-bg: rgba(255,255,255,0.08);
        --glass-border: rgba(255,255,255,0.12);
        --text: #fff;
        --muted: #c2c2c2;
        --radius: 16px;
        --secondary-bg: rgba(255,255,255,0.03);
        --item-hover: rgba(255,255,255,0.15);
        --panel-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        --success-gradient: linear-gradient(135deg, #2ecc71, #27ae60);
        --danger-gradient: linear-gradient(135deg, #e74c3c, #c0392b);
        --primary-gradient: linear-gradient(135deg, var(--accent), var(--accent-2));
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .shop-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
    }
    
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .hero-card {
        background: var(--secondary-bg);
        border-radius: calc(var(--radius) - 4px);
        border: 1px solid var(--glass-border);
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .hero-card:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
    }
    
    .hero-card a {
        text-decoration: none;
        color: var(--text);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .hero-card b {
        font-weight: 600;
        color: var(--accent);
        font-size: 16px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    
    .hero-card span {
        display: flex;
        align-items: center;
        background: rgba(0,0,0,0.4);
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: 600;
        color: white;
        font-size: 16px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .hero-card span img {
        margin-right: 5px;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));
    }
    
    .hero-link {
        color: var(--accent);
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        padding: 5px 10px;
        border-radius: 8px;
    }
    
    .hero-link:hover {
        background: var(--glass-bg);
    }
    
    .button_alt_01 {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px 20px;
        border: none;
        border-radius: var(--radius);
        text-align: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 10px auto;
        display: block;
        width: 200px;
        box-shadow: var(--panel-shadow);
    }
    
    .button_alt_01:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    
    .stat-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 15px 0;
    }
    
    .stat-table tr:hover {
        background: var(--secondary-bg);
    }
    
    .stat-table td {
        padding: 8px 10px;
        border-bottom: 1px solid var(--glass-border);
    }
    
    .stat-table .colonleft {
        width: 40%;
        text-align: left;
        color: var(--muted);
        font-weight: 500;
    }
    
    .stat-table .colonright {
        width: 60%;
        text-align: right;
        font-weight: 500;
    }
    
    .hero-header {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 15px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.4);
        background: rgba(0,0,0,0.2);
        padding: 10px;
        border-radius: 10px;
    }
    
    .hero-header img {
        margin-left: 10px;
    }
    
    .divider {
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .warning-text {
        color: var(--muted);
        text-align: center;
        font-size: 14px;
        line-height: 1.6;
        margin: 20px 0;
    }
    
    .warning-text strong {
        color: #e74c3c;
    }
    
    @media (max-width: 600px) {
        .shop-container {
            padding: 0 10px;
        }
    }
</style>

<div class="shop-container">
    <?php
    if(isset($_GET['goHero']) && isset($_GET['id']) && $_GET['id'] > 0){
        $ids = intval($_GET['id']);
        //герой
        $hero = $mc->query("SELECT * FROM `heroes` WHERE `id` = '".$ids."'")->fetch_array(MYSQLI_ASSOC);
        //вещи игрока
        $userShop = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '".intval($user['id'])."' AND `id_punct` > '0' AND `id_punct` < 9 ")->fetch_array(MYSQLI_ASSOC);
        //опыт героя
        $exp = $mc->query("SELECT * FROM `exp` WHERE `lvl` = '".$hero['level']."'")->fetch_array(MYSQLI_ASSOC);
        //вещи героя
        $shopH = $mc->query("SELECT * FROM `shop_heroes` WHERE `id_hero` = '".$ids."'")->fetch_all(MYSQLI_ASSOC);
        if($user['platinum'] >= $hero['platinum'] && (!empty($ids) || $ids > 0)){
            if($user['level'] > 1 && $user['level'] < 8){
                //ну поехали
                if($mc->query("UPDATE `users` SET `platinum` = `platinum` - '".$hero['platinum']."',`exp` = '".$exp['exp']."' WHERE `id` = '".intval($user['id'])."'")){
                    if($mc->query("DELETE FROM `userbag` WHERE `id_user` = '".$user['id']."' AND `id_punct` < '9' ")){
                      for($i = 0; $i < count($shopH); $i++){
                        shop_buy($shopH[$i]['id_shop'], 'y');
                      }
                      $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>Персонаж " . $user['name'] . "</font></a><font color='#0033cc'> купил персонажа </font><font color='#0033cc'><b>" . $hero['name'] . "</b></font>");
                      $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','0', '" . $chatmsg . " " . date('H:i:s') . "','','','' )");
                      message(urlencode("Вы успешно купили персонажа <b>{$hero['name']}</b>"));
                    }
                }
            }else{
                message("Покупка возможно только с персонажа не старше 7-го уровня!");
            }
        }else{
            message("Недостаточно средств");
        }
        
    }

    if(isset($_GET['shop']) && isset($_GET['id'])){
        $shop_heroes = $mc->query("SELECT * FROM `shop_heroes` WHERE `id_hero` = '".intval($_GET['id'])."'")->fetch_all(MYSQLI_ASSOC);
        
        $or;
        $shit;
        $shlem;
        $per;
        $dos;
        $ob;
        $am;
        $kol = [];
        $is = ",";
        for($i = 0; $i < count($shop_heroes); $i++){
          $shop = $mc->query("SELECT * FROM `shop` WHERE `id` = '".$shop_heroes[$i]['id_shop']."'")->fetch_array(MYSQLI_ASSOC);
          
          
          if($shop_heroes[$i]['punct'] == 1){
             $or = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 2){
             $shit = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 3){
             $shlem = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 4){
             $per = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 5){
             $dos = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 6){
             $ob = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 7){
             $am = $shop['name'];
          }else if($shop_heroes[$i]['punct'] == 8){
             $kol[] = $shop['name'];
             $is = ",";
            
          }
        }
        if(is_array($kol) && empty($kol)){
            $kol[0] = "";
            $is = "-";
            $kol[1] = "";
        }
        ?>
        <div class="card">
            <h2>Снаряжение героя</h2>
            <table class="stat-table">
                <tbody>
                    <tr><td class="colonleft">Оружие:</td><td class="colonright"><?= isset($or) ? $or : "-"; ?></td></tr>
                    <tr><td class="colonleft">Защита:</td><td class="colonright"><?= isset($shit) ? $shit : "-"; ?></td></tr>
                    <tr><td class="colonleft">Шлем:</td><td class="colonright"><?= isset($shlem) ? $shlem : "-"; ?></td></tr>
                    <tr><td class="colonleft">Перчатки:</td><td class="colonright"><?= isset($per) ? $per : "-"; ?></td></tr>
                    <tr><td class="colonleft">Доспехи:</td><td class="colonright"><?= isset($dos) ? $dos : "-"; ?></td></tr>
                    <tr><td class="colonleft">Обувь:</td><td class="colonright"><?= isset($ob) ? $ob : "-"; ?></td></tr>
                    <tr><td class="colonleft">Амулет:</td><td class="colonright"><?= isset($am) ? $am : "-"; ?></td></tr>
                    <tr><td class="colonleft">Кольца:</td><td class="colonright"><?= $kol[0].$is.$kol[1]; ?></td></tr>
                </tbody>
            </table>
            <div class="button_alt_01" onclick="showContent('shop_heroes.php?kupit&id=<?= intval($_GET['id']); ?>')">Назад к герою</div>
        </div>
        <?php
    }
    
    if(isset($_GET['kupit']) && isset($_GET['id'])){
        $hero = $mc->query("SELECT * FROM `heroes` WHERE `id` = '".intval($_GET['id'])."'")->fetch_array(MYSQLI_ASSOC);
        $uron = 0;
        $toch = 0;
        $hp = 0;
        $bron = 0;
        $lov = 0;
        $kd = 0;
        $block = 0;
        //получаю вещи героя
        $shop_heroes = $mc->query("SELECT * FROM `shop_heroes` WHERE `id_hero` = '".$hero['id']."'")->fetch_all(MYSQLI_ASSOC);
        //перебор вещей и запись параметров
        for($i = 0; $i < count($shop_heroes); $i++){
            $shop = $mc->query("SELECT * FROM `shop` WHERE `id` = '".$shop_heroes[$i]['id_shop']."'")->fetch_array(MYSQLI_ASSOC);
            $uron += $shop['strength'];
            $toch += $shop['toch'];
            $hp += $shop['health'];
            $bron += $shop['bron'];
            $lov += $shop['lov'];
            $kd += $shop['kd'];
            $block += $shop['block'];
        }
        ?>
        <div class="card">
            <div class="hero-header">
                <?= $hero['name']."[".$hero['level'];?>] <img src="/images/icons/plata.png" width="16px"><?= $hero['platinum'];?>
            </div>
            
            <div class="divider"></div>
            
            <table class="stat-table">
                <tr>
                    <td class="colonleft">Уровень:</td>
                    <td class="colonright">
                        <img src="/img/img23.png" width="16px"> <?=$hero['level'];?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Жизнь:</td>
                    <td class="colonright">
                        <?= ico('icons', 'hp.png')." ". $hp;?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Урон:</td>
                    <td class="colonright">
                        <?= ico('icons', 'power.jpg')." ". $uron;?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Точность:</td>
                    <td class="colonright">
                     <?= ico('icons', 'toch.png')." ". $toch;?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Броня:</td>
                    <td class="colonright">
                        <?= ico('icons', 'bron.png')." ". $bron;?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Уворот:</td>
                    <td class="colonright">
                        <?= ico('icons', 'img235.png')." ". $lov;?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Оглушение:</td>
                    <td class="colonright">
                        <?= ico('icons', 'kd.png')." ". $kd;?>
                    </td>
                </tr>
                <tr>
                    <td class="colonleft">Блок:</td>
                    <td class="colonright">
                       <?= ico('icons', 'shit.png')." ". $block;?>
                    </td>
                </tr>
            </table>
            
            <div class="divider"></div>
            
            <center>
                <a class="hero-link" onclick="showContent('shop_heroes.php?shop&id=<?=$hero['id'];?>')">Снаряжение</a>
            </center>
            
            <div class="button_alt_01" onclick="showContent('shop_heroes.php?goHero&id=<?=$hero['id'];?>')">Купить</div>
        </div>
        <?php
    }
    
    $hero = $mc->query("SELECT * FROM `heroes` ")->fetch_all(MYSQLI_ASSOC);
    if(!isset($_GET['kupit']) && !isset($_GET['id'])){
    ?>
    <div class="card">
        <h2>Персонажи на продажу</h2>
        
        <?php for($i = 0; $i < count($hero); $i++){ ?>
            <div class="hero-card" onclick="showContent('shop_heroes.php?kupit&id=<?=$hero[$i]['id'];?>')">
                <a>
                    <b><?= $hero[$i]['name']."[".$hero[$i]['level'];?>]</b>
                    <span><img src="/images/icons/plata.png" width="16px"><?= $hero[$i]['platinum'];?></span>
                </a>
            </div>
        <?php } ?>
        
        <div class="warning-text">
            <p>Уровень, снаряжение и прогресс заданий Вашего персонажа будут заменены на новые!</p>
            <p>Покупка возможно только с персонажа не старше 7-го уровня!</p>
            <p><strong>Внимание:</strong> покупая или обменивая персонажей на руках, Вы рискуете стать жертвой мошенничества!</p>
        </div>
    </div>
    <?php
    }
    ?>
</div>

<?php
$footval = 'shoptomain';
require_once 'system/foot/foot.php';
?>