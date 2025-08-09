<?php
require_once '../../system/func.php';
ob_start();
if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Недоступно до 2 уровня"));</script>
    <?php
    exit();
}
?>

<style>
    .clantur_container {
        max-width: 800px;
        margin: 0 auto;
        padding: 15px;
        position: relative;
    }

    .clantur_header {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: #4A2601;
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid rgba(139, 69, 19, 0.2);
        border-radius: 5px;
        background: linear-gradient(to right, transparent, rgba(187, 152, 84, 0.1), transparent);
        position: relative;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .clantur_header:before,
    .clantur_header:after {
        content: '';
        position: absolute;
        width: 40px;
        height: 1px;
        background: rgba(139, 69, 19, 0.3);
        top: 50%;
    }

    .clantur_header:before {
        left: 20px;
        transform: rotate(-45deg);
    }

    .clantur_header:after {
        right: 20px;
        transform: rotate(45deg);
    }

    .clantur_info {
        background: rgba(187, 152, 84, 0.1);
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid rgba(139, 69, 19, 0.15);
        box-shadow: 0 2px 4px rgba(139, 69, 19, 0.05);
        position: relative;
        overflow: hidden;
    }

    .clantur_info:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(to right,
            transparent,
            rgba(139, 69, 19, 0.2),
            transparent
        );
    }

    .clantur_button {
        background: #8B4513;
        color: white;
        padding: 12px 20px;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 10px auto;
        width: fit-content;
        border: 1px solid rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .clantur_button:hover {
        background: #643201;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .clantur_button:after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .clantur_button:hover:after {
        opacity: 1;
    }

    .tur_item {
        background: rgba(187, 152, 84, 0.05);
        border: 1px solid rgba(139, 69, 19, 0.1);
        border-radius: 5px;
        padding: 15px;
        margin: 10px 0;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .tur_item:hover {
        background: rgba(187, 152, 84, 0.1);
        border-color: rgba(139, 69, 19, 0.2);
        box-shadow: 0 2px 4px rgba(139, 69, 19, 0.05);
        transform: translateY(-1px);
    }

    .tur_clan_info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(139, 69, 19, 0.1);
    }

    .tur_clan_name {
        color: #4A2601;
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tur_clan_name img {
        width: 20px;
        height: 20px;
        filter: drop-shadow(0 1px 1px rgba(0,0,0,0.1));
    }

    .tur_details {
        display: flex;
        justify-content: center;
        gap: 20px;
        color: #643201;
        font-size: 14px;
    }

    .tur_count {
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(139, 69, 19, 0.05);
        padding: 5px 10px;
        border-radius: 3px;
    }

    .tur_time {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .tur_no_tournaments {
        text-align: center;
        padding: 20px;
        color: #643201;
        font-style: italic;
    }
</style>

<?php
// Получаем данные клана и турниров
$clan = $mc->query("SELECT * FROM `clan` WHERE `id`='" . $mc->real_escape_string($user['id_clan']) . "'")->fetch_array(MYSQLI_ASSOC);
$tur = $mc->query("SELECT * FROM `req_tur`")->fetch_all(MYSQLI_ASSOC);

// Регистрация на турнир
if(isset($_GET['setReg']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $check = $mc->query("SELECT * FROM `userListTur` WHERE `id_user` = '".$mc->real_escape_string($user['id'])."' AND `id_tur` = '".$id."'")->fetch_array(MYSQLI_ASSOC);
    if(!$check) {
        if($mc->query("INSERT INTO `userListTur` (`id_user`,`id_tur`,`tur`) VALUES ('".$mc->real_escape_string($user['id'])."','".$id."','1')")) {
            ?>
            <script>
                showContent('/huntb/clantur/index.php?go&id=<?php echo $id; ?>&msg=' + encodeURIComponent('Вы зарегистрированы'));
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            showContent('/huntb/clantur/index.php?go&id=<?php echo $id; ?>&msg=' + encodeURIComponent('Вы уже зарегистрированы на этот турнир'));
        </script>
        <?php
    }
    exit();
}

// Выход из турнира
if(isset($_GET['setOut']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if($mc->query("DELETE FROM `userListTur` WHERE `id_user` = '".$mc->real_escape_string($user['id'])."' AND `id_tur` = '".$id."'")) {
        ?>
        <script>
            showContent('/huntb/clantur/index.php?go&id=<?php echo $id; ?>&msg=' + encodeURIComponent('Регистрация отменена'));
        </script>
        <?php
    }
    exit();
}

// Показываем сообщение если есть
if(isset($_GET['msg'])) {
    ?>
    <script>message('<?php echo htmlspecialchars($_GET['msg']); ?>');</script>
    <?php
}

// Просмотр турнира
if(isset($_GET['go']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $tur = $mc->query("SELECT * FROM `req_tur` WHERE `id` = '".$id."'")->fetch_array(MYSQLI_ASSOC);
    if(!$tur) {
        echo "<script>showContent('/huntb/clantur/index.php');</script>";
        exit();
    }
    
    $clName = $mc->query("SELECT `name` FROM `clan` WHERE `id` = '".$mc->real_escape_string($tur['id_clan'])."'")->fetch_array(MYSQLI_ASSOC);
    $listUser = $mc->query("SELECT * FROM `userListTur` WHERE `id_tur` = '".$id."'")->fetch_all(MYSQLI_ASSOC);
    $turCount = $mc->query("SELECT * FROM `tur_list` WHERE `id` = '".$mc->real_escape_string($tur['id_tur'])."'")->fetch_array(MYSQLI_ASSOC);
    ?>
    <div class="tur_header">-Клановый турнир-</div>

    <table class="table_block2">
        <tr>
            <td class="block01" style="width: 2%"></td>
            <td class="block02" style="width: 96%"></td>
            <td class="block03" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block04"></td>
            <td class="block05">
                <div class="tur_info">
                    <div class="tur_organizer">
                        <img src="/images/icons/clan.png" style="width: 16px; vertical-align: middle;"> 
                        Организатор: <b><?php echo htmlspecialchars($clName['name']); ?></b>
                    </div>
                    <div class="tur_time">
                        <img src="/images/icons/time.png" style="width: 16px; vertical-align: middle;">
                        Регистрация: <b>20:45</b> | Старт: <b>21:00</b>
                    </div>
                    <div class="tur_participants">
                        <img src="/images/icons/users.png" style="width: 16px; vertical-align: middle;">
                        Участников: <b><?php echo count($listUser); ?>/<?php echo intval($turCount['count_user']); ?></b>
                    </div>
                    <?php 
                    $prize_gold = isset($turCount['prize_gold']) ? intval($turCount['prize_gold']) : 0;
                    $prize_platinum = isset($turCount['prize_platinum']) ? intval($turCount['prize_platinum']) : 0;
                    
                    if ($prize_gold > 0 || $prize_platinum > 0): 
                    ?>
                    <div class="tur_prize">
                        <img src="/images/icons/prize.png" style="width: 16px; vertical-align: middle;">
                        Награда: 
                        <?php if ($prize_gold > 0): ?>
                            <img src="/images/icons/zoloto.png" style="width: 14px;"> <?php echo $prize_gold; ?>
                        <?php endif; ?>
                        <?php if ($prize_platinum > 0): ?>
                            <img src="/images/icons/plata.png" style="width: 14px;"> <?php echo $prize_platinum; ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </td>
            <td class="block06"></td>
        </tr>
        <tr>
            <td class="block07"></td>
            <td class="block08"></td>
            <td class="block09"></td>
        </tr>
    </table>

    <div class="tur_description">
        Регистрация открыта для всех желающих. При старте турнира будет отобрано <?php echo intval($turCount['count_user']); ?> сильнейших участников.
    </div>
    
    <table class="table_block2">
        <tr>
            <td class="block01" style="width: 2%"></td>
            <td class="block02" style="width: 96%"></td>
            <td class="block03" style="width: 2%"></td>
        </tr>
        <tr>
            <td class="block04"></td>
            <td class="block05">
                <div class="tur_participants_list">
                    <?php 
                    foreach($listUser as $userItem) {
                        // Получаем только необходимые поля
                        $users = $mc->query("SELECT `name`, `level` FROM `users` WHERE `id` = '".$mc->real_escape_string($userItem['id_user'])."'")->fetch_array(MYSQLI_ASSOC);
                        if ($users) {
                            ?>
                            <div class="tur_participant">
                                <span class="tur_name"><?php echo htmlspecialchars($users['name']); ?></span>
                                <span class="tur_level">[<?php echo intval($users['level']); ?>]</span>
                            </div>
                            <?php
                        }
                    }
                    
                    if (empty($listUser)) {
                        echo '<div class="tur_no_participants">Пока нет участников</div>';
                    }
                    ?>
                </div>
                
                <?php
                $isRegistered = $mc->query("SELECT * FROM `userListTur` WHERE `id_user` = '".$mc->real_escape_string($user['id'])."' AND `id_tur` = '".$id."'")->fetch_array(MYSQLI_ASSOC);
                if(!$isRegistered) {
                    ?>
                    <div class="tur_button register" onclick="showContent('/huntb/clantur/index.php?setReg&id=<?php echo $id; ?>');">
                        <img src="/images/icons/register.png" style="width: 16px; vertical-align: middle;"> Зарегистрироваться
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="tur_button cancel" onclick="showContent('/huntb/clantur/index.php?setOut&id=<?php echo $id; ?>');">
                        <img src="/images/icons/cancel.png" style="width: 16px; vertical-align: middle;"> Отменить регистрацию
                    </div>
                    <?php
                }
                ?>
            </td>
            <td class="block06"></td>
        </tr>
        <tr>
            <td class="block07"></td>
            <td class="block08"></td>
            <td class="block09"></td>
        </tr>
    </table>

    <style>
    .tur_header {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 10px 0;
        color: #795548;
    }
    .tur_info {
        padding: 10px;
        line-height: 1.8;
    }
    .tur_description {
        text-align: center;
        padding: 10px;
        color: #666;
        font-style: italic;
    }
    .tur_participants_list {
        padding: 10px;
    }
    .tur_participant {
        padding: 5px;
        margin: 3px 0;
        border-bottom: 1px solid #eee;
    }
    .tur_avatar {
        width: 24px;
        height: 24px;
        vertical-align: middle;
        border-radius: 12px;
        margin-right: 5px;
    }
    .tur_name {
        font-weight: bold;
        color: #2196F3;
    }
    .tur_level {
        color: #666;
        margin-left: 5px;
    }
    .tur_button {
        text-align: center;
        padding: 10px;
        margin: 10px;
        border-radius: 5px;
        cursor: pointer;
    }
    .tur_button.register {
        background: #4CAF50;
        color: white;
    }
    .tur_button.cancel {
        background: #F44336;
        color: white;
    }
    .tur_no_participants {
        text-align: center;
        color: #999;
        padding: 20px;
        font-style: italic;
    }
    </style>
    <?php
} else {
    // Список всех турниров
    ?>
    <div class="clantur_container">
        <div class="clantur_header">
            Турниры от кланов
        </div>

        <div class="clantur_info">
            <?php
            if (empty($tur)) {
                echo '<div class="tur_no_tournaments">В данный момент нет активных турниров</div>';
            } else {
                foreach($tur as $tournament) {
                    $clanName = $mc->query("SELECT `name` FROM `clan` WHERE `id` = '".$mc->real_escape_string($tournament['id_clan'])."'")->fetch_array(MYSQLI_ASSOC);
                    $countUser = $mc->query("SELECT COUNT(*) as count FROM `userListTur` WHERE `id_tur` = '".$mc->real_escape_string($tournament['id'])."'")->fetch_array(MYSQLI_ASSOC);
                    $turCount = $mc->query("SELECT * FROM `tur_list` WHERE `id` = '".$mc->real_escape_string($tournament['id_tur'])."'")->fetch_array(MYSQLI_ASSOC);
                    ?>
                    <div class="tur_item" onclick="showContent('/huntb/clantur/index.php?go&id=<?php echo intval($tournament['id']); ?>')">
                        <div class="tur_clan_info">
                            <div class="tur_clan_name">
                                <img src="/images/icons/clan.png" alt="Клан">
                                <?php echo htmlspecialchars($clanName['name']); ?>
                            </div>
                        </div>
                        <div class="tur_details">
                            <div class="tur_time">
                                <img src="/images/icons/time.png" alt="Время" style="width: 16px;">
                                <span>Старт: <b>21:00</b></span>
                            </div>
                            <div class="tur_count">
                                <img src="/images/icons/users.png" alt="Участники" style="width: 16px;">
                                <span><?php echo $countUser['count']; ?>/<?php echo intval($turCount['count_user']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
}

$footval = "clantur_huntb";
require_once ('../../system/foot/foot.php');
ob_end_flush();
?>