<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/functions/bablo.php';
require '../system/func.php';
require '../system/dbc.php';
$footval = 'adminadmin';
require '../system/foot/foot.php';
?>
<html>
    <head>
        <title>Mobitva v1.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
        <meta name="theme-color" content="#C8AC70">
        <link rel="shortcut icon" href="../favicon.ico" />
        <meta name="author" content="Kosoy"/>
        <style>
            body {
                background: #1a1a1a;
                color: #fff;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 10px;
            }
            .editor-container {
                max-width: 800px;
                margin: 0 auto;
                background: #2a2a2a;
                padding: 20px;
                border-radius: 5px;
            }
            h3 {
                color: #f8b334;
                text-align: center;
                margin-bottom: 20px;
            }
            .form-section {
                margin-bottom: 20px;
            }
            .form-section label {
                display: block;
                margin-bottom: 5px;
                color: #f8b334;
            }
            .form-section input, .form-section select {
                width: 100%;
                padding: 8px;
                margin-bottom: 10px;
                background: #3a3a3a;
                border: 1px solid #f8b334;
                color: #fff;
                border-radius: 3px;
                box-sizing: border-box;
            }
            .form-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            .button_alt_01 {
                background: #f8b334;
                color: #1a1a1a;
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
                text-align: center;
                transition: opacity 0.3s;
            }
            .button_alt_01:hover {
                opacity: 0.8;
            }
            details {
                background: #3a3a3a;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 20px;
            }
            summary {
                cursor: pointer;
                color: #f8b334;
            }
            hr {
                border: none;
                border-top: 1px solid #f8b334;
                margin: 10px 0;
            }
            @media (max-width: 600px) {
                .form-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <?php
        $banned = 0;
        if (!$user OR $user['access'] < 3) {
            ?><script>showContent("/");</script><?php
            exit(0);
        } else {
            if (isset($_GET['id']) && $_GET['id']) {
                $uid = (int) $_GET['id'];
            } else {
                message('неверный id');
                exit(0);
            }
            $ires = $mc->query("SELECT * FROM `users` WHERE `id`='$uid'");
            if ($ires->num_rows) {
                $i = $ires->fetch_array(MYSQLI_ASSOC);
            } else {
                message('пользователь с id = ' . $uid . ' не найден');
                exit(0);
            }
            $result = $mc->query("SELECT * FROM `chatban` WHERE `user` = '$uid'");
            if ($result->num_rows) {
                $msgres = $result->fetch_array(MYSQLI_ASSOC);
                $banned = 1;
            }
        }

        if (!empty($_GET['save'])) {
            $olduser = $mc->query("SELECT * FROM `users` WHERE `id`='" . $_GET['id'] . "'")->fetch_array(MYSQLI_ASSOC);
            $named = htmlentities($_GET['named']);
            $id = $_GET['id'];
            $login = $_GET['login'];
            $password = $_GET['password'];
            $side = $_GET['side'];
            $access = $_GET['access'];
            $toch = $_GET['toch'];
            $strength = $_GET['strength'];
            $kd = $_GET['kd'];
            $lov = $_GET['lov'];
            $bron = $_GET['bron'];
            $block = $_GET['block'];
            $health = $_GET['health'];
            $level = $_GET['level'];
            $exp = $_GET['exp'];
            $slava = $_GET['slava'];
            $vinos_m = $_GET['vinos_m'];
            $tur_reit = $_GET['tur_reit'];
            $rep_p = $_GET['rep_p'];
            $rep_m = $_GET['rep_m'];
            $platinum = $_GET['platinum'];
            $prem_s = explode(':', $_GET['prem_t']);
            $prem = (((((($prem_s[0] * 24) + $prem_s[1]) * 60) + $prem_s[2]) * 60) + $prem_s[3])+time();
            $id_clan = $_GET['id_clan'];
            $superudar = $_GET['superudar'];
            $money = (((($_GET['zolo'] * 100) + $_GET['serebro']) * 100) + $_GET['med']);
            
            if($prem>=time()){
                $premi=1;
            }else{
                 $premi=0;
            }
            
            // Пересчитываем характеристики в зависимости от уровня
            if ($level != $olduser['level']) {
                $health = 10 + (5 * $level);
                $strength = 1 + (2 * $level) - 2;
                $toch = 8 + (2 * $level) - 2;
                $bron = 0 + (2 * $level) - 2;
                $lov = 3 + (2 * $level) - 2;
                $kd = 2 + (2 * $level) - 2;
                $block = 0 + (2 * $level) - 2;
            }
            
            $mc->query("UPDATE `users` SET  "
                    . "`side`='" . $side . "',"
                    . "`name`='" . $named . "',"
                    . " `money`='" . $money . "',"
                    . "  `platinum`='" . $platinum . "',"
                    . " `bron`='" . $bron . "',"
                    . " `block`='" . $block . "',"
                    . " `health`='" . $health . "',"
                    . " `access`='" . $access . "',"
                    . " `strength`='" . $strength . "',"
                    . " `kd`='" . $kd . "',"
                    . " `lov`='" . $lov . "',"
                    . " `toch`='" . $toch . "',"
                    . " `level`='" . $level . "',"
                    . " `exp`='" . $exp . "',"
                    . " `slava`='" . $slava . "',"
                    . " `vinos_m`='" . $vinos_m . "',"
                    . " `tur_reit`='" . $tur_reit . "',"
                    . " `rep_p`='" . $rep_p . "',"
                    . " `rep_m`='" . $rep_m . "',"
                    . " `id_clan`='" . $id_clan . "',"
                    . " `superudar`='" . $superudar . "',"
                    . " `prem` = '".$premi . "',"
                    . " `prem_t`='" . $prem . "'"
                    . " WHERE `id`='" . $id . "'");
            if ($mc->errno === 0) {
                $date = date("Y-m-d H:i:s");
                $edit = '';
                if ($side != $olduser['side']) {
                    $edit .= 'расу, ';
                }
                if ($named != $olduser['name']) {
                    $edit .= 'имя с ' . $olduser['name'] . ' на ' . $named . ', ';
                }
                if ($access != $olduser['access']) {
                    $edit .= 'доступ с ' . $olduser['access'] . ' на ' . $access . ', ';
                }
                if ($toch != $olduser['toch']) {
                    $edit .= 'точность с ' . $olduser['toch'] . ' на ' . $toch . ', ';
                }
                if ($strength != $olduser['strength']) {
                    $edit .= 'урон с ' . $olduser['strength'] . ' на ' . $strength . ', ';
                }
                if ($kd != $olduser['kd']) {
                    $edit .= 'оглушение с ' . $olduser['kd'] . ' на ' . $kd . ', ';
                }
                if ($lov != $olduser['lov']) {
                    $edit .= 'ловкось с ' . $olduser['lov'] . ' на ' . $lov . ', ';
                }
                if ($block != $olduser['block']) {
                    $edit .= 'блок с ' . $olduser['block'] . ' на ' . $block . ', ';
                }
                if ($health != $olduser['health']) {
                    $edit .= 'здоровье с ' . $olduser['health'] . ' на ' . $health . ', ';
                }
                if ($level != $olduser['level']) {
                    $edit .= 'уровень с ' . $olduser['level'] . ' на ' . $level . ', ';
                }
                if ($exp != $olduser['exp']) {
                    $edit .= 'опыт с ' . $olduser['exp'] . ' на ' . $exp . ', ';
                } 
                if ($slava != $olduser['slava']) {
                    $edit .= 'славу с ' . $olduser['slava'] . ' на ' . $slava . ', ';
                }
                if ($vinos_m != $olduser['vinos_m']) {
                    $edit .= 'выносливость с ' . $olduser['vinos_m'] . ' на ' . $vinos_m . ', ';
                }
                if ($tur_reit != $olduser['tur_reit']) {
                    $edit .= 'рейтинг турнира с ' . $olduser['tur_reit'] . ' на ' . $tur_reit . ', ';
                }
                if ($rep_p != $olduser['rep_p']) {
                    $edit .= 'репутацию + с ' . $olduser['rep_p'] . ' на ' . $rep_p . ', ';
                } 
                if ($rep_m != $olduser['rep_m']) {
                    $edit .= 'репутацию - с ' . $olduser['rep_m'] . ' на ' . $rep_m . ', ';
                }
                if ($platinum != $olduser['platinum']) {
                    $edit .= 'плату с ' . $olduser['platinum'] . ' на ' . $platinum . ', ';
                }
                if ($prem != $olduser['prem_t']) {
                    $edit .= 'премиум с ' . sprintf(
                            "%02d:%02d:%02d:%02d",
                            (($olduser['prem_t']-time()) / 3600) / 24,
                            (($olduser['prem_t']-time()) / 3600) % 24,
                            (($olduser['prem_t']-time()) % 3600) / 60,
                            (($olduser['prem_t']-time()) % 3600) % 60
                            ) . ' на ' . sprintf(
                            "%02d:%02d:%02d:%02d",
                            (($prem-time()) / 3600) / 24,
                            (($prem-time()) / 3600) % 24,
                            (($prem-time()) % 3600) / 60,
                            (($prem-time()) % 3600) % 60
                            ) . ', ';
                }
                if ($id_clan != $olduser['id_clan']) {
                    $edit .= 'клан с ' . $olduser['id_clan'] . ' на ' . $id_clan . ', ';
                }
                if ($superudar != $olduser['superudar']) {
                    $edit .= 'СУ с ' . $olduser['superudar'] . ' на ' . $superudar . ', ';
                }
                if ($money != $olduser['money']) {
                    if (money($money, 'med') != money($olduser['money'], 'med')) {
                        $edit .= 'медь с ' . money($olduser['money'], 'med') . ' на ' . money($money, 'med') . ', ';
                    }
                    if (money($money, 'serebro') != money($olduser['money'], 'serebro')) {
                        $edit .= 'серебро с ' . money($olduser['money'], 'serebro') . ' на ' . money($money, 'serebro') . ', ';
                    }
                    if (money($money, 'zoloto') != money($olduser['money'], 'zoloto')) {
                        $edit .= 'медь с ' . money($olduser['money'], 'zoloto') . ' на ' . money($money, 'zoloto') . ', ';
                    }
                }
                $mc->query("INSERT INTO `adminlog`(`idEditor`, `msg`, `idUser`, `date`) VALUES ('" . $user['id'] . "','" . $date . "->" . $edit . "','" . $id . "','" . $date . "')");

                $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> изменил героя </font><a onclick=\\'showContent(\\\"/profile.php?id=" . $uid . "\\\")\\'><font color=\\'#0033cc\\'>" . $i['name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
                $chatmsg3 = "id : " . $user['id'] . " name :" . $user['name'] . " изменил героя -> id : " . $uid . " name " . $i['name'];
                $mc->query("INSERT INTO `chat`("
                        . "`id`,"
                        . "`name`,"
                        . "`id_user`,"
                        . "`chat_room`,"
                        . "`msg`,"
                        . "`msg2`,"
                        . "`msg3`,"
                        . "`time`,"
                        . " `unix_time`"
                        . ") VALUES ("
                        . "NULL,"
                        . "'АДМИНИСТРИРОВАНИЕ',"
                        . "'" . $user['id'] . "',"
                        . "'5',"
                        . " '$chatmsg',"
                        . "'" . $uid . "',"
                        . "'$chatmsg3',"
                        . "'" . $date . "',"
                        . "'" . time() . "'"
                        . " )");
                ?>
                <script>
                    showContent("/profile.php?id=<?php echo $id; ?>&msg=" + encodeURIComponent("герой изменен успешно"));
                </script>
                <?php
                exit(0);
            } else {
                ?>
                <script>
                    showContent("/profile.php?id=<?php echo $id; ?>&msg=" + encodeURIComponent("ОШИБКА . ГЕРОЙ НЕ БЫЛ ИЗМЕНЕН ! ОСТОРОЖНО !!!"));
                </script>
                <?php
                exit(0);
            }
        }

        if (!empty($_GET['vhodpers'])) {
            setcookie('login', urlencode($_GET['login']), time() + 86400 * 365, '/');
            setcookie('password', $_GET['password'], time() + 86400 * 365, '/');
            $date = date("Y-m-d H:i:s");
            $chatmsg = "<a onclick=\\'showContent(\\\"/profile.php?id=" . $user['id'] . "\\\")\\'><font color=\\'#0033cc\\'>" . $user['name'] . "</font></a><font color=\\'#0033cc\\'> прикинулся тип он </font><a onclick=\\'showContent(\\\"/profile.php?id=" . $uid . "\\\")\\'><font color=\\'#0033cc\\'>" . $i['name'] . "</font></a><font color=\\'#0033cc\\'> !</font>";
            $chatmsg3 = "id : " . $user['id'] . " name :" . $user['name'] . " прикинулся тип он id : " . $uid . " name " . $i['name'];
            $mc->query("INSERT INTO `chat`("
                    . "`id`,"
                    . "`name`,"
                    . "`id_user`,"
                    . "`chat_room`,"
                    . "`msg`,"
                    . "`msg2`,"
                    . "`msg3`,"
                    . "`time`,"
                    . " `unix_time`"
                    . ") VALUES ("
                    . "NULL,"
                    . "'ВХОД В ЧУЖОЙ АККАУНТ',"
                    . "'" . $user['id'] . "',"
                    . "'5',"
                    . " '$chatmsg',"
                    . "'" . $uid . "',"
                    . "'$chatmsg3',"
                    . "'" . $date . "',"
                    . "'" . time() . "'"
                    . " )");
        }

        if (!empty($_GET['unban'])) {
            if (!empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $name_1 = $user['name'];
                $user_2_name = $i['name'];
                $mc->query("DELETE FROM `chatban` WHERE `chatban`.`user` = '$id'");
                $date = date("Y-m-d H:i:s");
                $msgban = date("H:i") . '<span style="color: #8C3B06;"> ' . $name_1 . ' снял бан с ' . $user_2_name . '</span><br>';
                $mc->query("INSERT INTO`chat`("
                        . "`id`,"
                        . "`name`,"
                        . "`id_user`,"
                        . "`chat_room`,"
                        . "`msg`,`msg2`,"
                        . "`time`,"
                        . "`unix_time`"
                        . ") VALUES ("
                        . "NULL,"
                        . "'РозБан',"
                        . "'',"
                        . "'0',"
                        . "'" . $msgban . "',"
                        . "'Снял(а)" . $name_1 . "',"
                        . "'" . $date . "',"
                        . "'" . time() . "'"
                        . ")");
                ?>
                <script>
                    showContent('/admin/admin.php?id=<?php echo $id; ?>');
                </script>
                <?php
                exit(0);
            } else {
                message('неверный id ' . $id);
                exit(0);
            }
        }

        $side_0 = "";
        $side_1 = "";
        $side_2 = "";
        $side_3 = "";
        if ($i['side'] == '0') {
            $side_0 = 'selected';
        } elseif ($i['side'] == '1') {
            $side_1 = 'selected';
        } elseif ($i['side'] == '2') {
            $side_2 = 'selected';
        } elseif ($i['side'] == '3') {
            $side_3 = 'selected';
        }
        ?>
        <div class="editor-container">
            <h3>-Редактор игрока-</h3>
            <form id="form1">
                <input name='id' type="hidden" value='<?php echo $i['id']; ?>'>
                <input name='password' type='hidden' value='<?php echo $i['password']; ?>'>
                <input name='login' type='hidden' value='<?php echo $i['login']; ?>'>

                <div class="form-section">
                    <h4>Основные данные</h4>
                    <div class="form-grid">
                        <div>
                            <label>Имя:</label>
                            <input name='named' type='text' value='<?php echo html_entity_decode($i['name']); ?>'>
                        </div>
                        <div>
                            <label>Сторона:</label>
                            <select name='side'>
                                <option <?php echo $side_0; ?> value='0'>Шейван</option>
                                <option <?php echo $side_1; ?> value='1'>Шейванка</option>
                                <option <?php echo $side_2; ?> value='2'>Нормас</option>
                                <option <?php echo $side_3; ?> value='3'>Нормаска</option>
                            </select>
                        </div>
                        <div>
                            <label>Права:</label>
                            <input name='access' type='text' value='<?php echo $i['access']; ?>'>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Характеристики</h4>
                    <div class="alert alert-warning">
                        <strong>Внимание!</strong> При изменении уровня характеристики будут автоматически пересчитаны в соответствии с формулой расчета.
                    </div>
                    <div class="form-grid">
                        <div>
                            <label>Здоровье:</label>
                            <input name='health' type='text' value='<?php echo $i['health']; ?>'>
                        </div>
                        <div>
                            <label>Урон:</label>
                            <input name='strength' type='text' value='<?php echo $i['strength']; ?>'>
                        </div>
                        <div>
                            <label>Точность:</label>
                            <input name='toch' type='text' value='<?php echo $i['toch']; ?>'>
                        </div>
                        <div>
                            <label>Броня:</label>
                            <input name='bron' type='text' value='<?php echo $i['bron']; ?>'>
                        </div>
                        <div>
                            <label>Уворот:</label>
                            <input name='lov' type='text' value='<?php echo $i['lov']; ?>'>
                        </div>
                        <div>
                            <label>Оглушение:</label>
                            <input name='kd' type='text' value='<?php echo $i['kd']; ?>'>
                        </div>
                        <div>
                            <label>Блок:</label>
                            <input name='block' type='text' value='<?php echo $i['block']; ?>'>
                        </div>
                        <div>
                            <label>Выносливость:</label>
                            <input name='vinos_m' type='text' value='<?php echo $i['vinos_m']; ?>'>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Прогресс</h4>
                    <div class="form-grid">
                        <div>
                            <label>Уровень:</label>
                            <input name='level' type='text' value='<?php echo $i['level']; ?>'>
                        </div>
                        <div>
                            <label>Опыт:</label>
                            <input name='exp' type='text' value='<?php echo $i['exp']; ?>'>
                        </div>
                        <div>
                            <label>Слава:</label>
                            <input name='slava' type='text' value='<?php echo $i['slava']; ?>'>
                        </div>
                        <div>
                            <label>Рейтинг турнира:</label>
                            <input name='tur_reit' type='text' value='<?php echo $i['tur_reit']; ?>'>
                        </div>
                        <div>
                            <label>Репутация +:</label>
                            <input name='rep_p' type='text' value='<?php echo $i['rep_p']; ?>'>
                        </div>
                        <div>
                            <label>Репутация -:</label>
                            <input name='rep_m' type='text' value='<?php echo $i['rep_m']; ?>'>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Ресурсы</h4>
                    <div class="form-grid">
                        <div>
                            <label>Платина:</label>
                            <input name='platinum' type='text' value='<?php echo $i['platinum']; ?>'>
                        </div>
                        <div>
                            <label>Золото:</label>
                            <input name='zolo' type='text' value='<?php echo money($i['money'], 'zoloto'); ?>'>
                        </div>
                        <div>
                            <label>Серебро:</label>
                            <input name='serebro' type='text' value='<?php echo money($i['money'], 'serebro'); ?>'>
                        </div>
                        <div>
                            <label>Медь:</label>
                            <input name='med' type='text' value='<?php echo money($i['money'], 'med'); ?>'>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Дополнительно</h4>
                    <div class="form-grid">
                        <div>
                            <label>ID Клана:</label>
                            <input name='id_clan' type='text' value='<?php echo $i['id_clan']; ?>'>
                        </div>
                        <div>
                            <label>Супер Удары:</label>
                            <input name='superudar' type='text' value='<?php echo $i['superudar']; ?>'>
                        </div>
                        <div>
                            <label>Премиум (дд:чч:мм:сс):</label>
                            <input name='prem_t' type='text' value='<?= sprintf(
                            "%02d:%02d:%02d:%02d",
                            (($i['prem_t']-time()) / 3600) / 24,
                            (($i['prem_t']-time()) / 3600) % 24,
                            (($i['prem_t']-time()) % 3600) / 60,
                            (($i['prem_t']-time()) % 3600) % 60
                            ); ?>'>
                        </div>
                    </div>
                </div>

                <button id='save' class='button_alt_01' type='button' value='1'>Изменить</button>
            </form>

            <div class="form-section">
                <button id='vhodpers' class='button_alt_01' type='button' value='1'>Войти за этого персонажа</button>
            </div>

            <details>
                <summary>Новый Лог: (<?= $mc->query("SELECT * FROM `adminlog` WHERE `idUser`='" . $i['id'] . "' ORDER BY `adminlog`.`id` DESC")->num_rows;?>)</summary>
                <?php
                $newlogpers1 = $mc->query("SELECT * FROM `adminlog` WHERE `idUser`='" . $i['id'] . "' ORDER BY `adminlog`.`id` DESC");
                while ($newlogpers = $newlogpers1->fetch_array(MYSQLI_ASSOC)) {
                    $redname = "Система";
                    if ($newlogpers['idEditor'] != 0) {
                        $reduser = $mc->query("SELECT `name` FROM `users` WHERE `id`='" . $newlogpers['idEditor'] . "'")->fetch_array(MYSQLI_ASSOC);
                        $redname = $reduser['name'];
                    }
                    echo '<b>' . $redname . '</b> Изменил <font color="#0033cc">' . $newlogpers['msg'] . '</font> у <b>' . $i['name'] . '</b><hr>';
                }
                ?>
            </details>

            <details>
                <summary>Старый Лог: (<?= $mc->query("SELECT * FROM `chat` WHERE `chat_room`='5' AND `msg` LIKE '%" . $i['name'] . "%' ORDER BY `chat`.`id` DESC")->num_rows;?>)</summary>
                <?php
                $logpers1 = $mc->query("SELECT * FROM `chat` WHERE `chat_room`='5' AND `msg` LIKE '%" . $i['name'] . "%' ORDER BY `chat`.`id` DESC");
                while ($logpers = $logpers1->fetch_array(MYSQLI_ASSOC)) {
                    echo $logpers['msg'] . '<br>';
                }
                ?>
            </details>

            <?php if ($banned == 1) { ?>
                <div class="form-section">
                    <h4>Информация о бане</h4>
                    <form id="form2">
                        <input id='id' type="hidden" value='<?php echo $i['id']; ?>'>
                        <div>
                            <p>Модератор <?php echo $msgres['user2name'] ?> забанил(а) <?php echo $msgres['username'] ?> на <?php echo $msgres['how'] ?> за:</p>
                            <p><?php echo htmlspecialchars_decode($msgres['msg']) ?></p>
                        </div>
                    </form>
                    <button id='unban' class='button_alt_01' type='button' value="1">Снять бан</button>
                </div>
            <?php } ?>
        </div>

        <script>
            $("#save").click(function () {
                showContent(
                        "/admin/admin.php?save=1&" + $("#form1").serialize()
                        );
            });
            $("#vhodpers").click(function () {
                showContent(
                        "/admin/admin.php?vhodpers=1&" + $("#form1").serialize()
                        );
            });
            $("#unban").click(function () {
                showContent(
                        "/admin/admin.php?unban=1&id=" + $("#form2").children("#id").val()
                        );
            });
        </script>
    </body>
</html>