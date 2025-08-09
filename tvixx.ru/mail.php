<?php
require_once ('system/func.php');
require_once ('system/header.php');
// Закроем от неавторизированых
auth();

if (!isset($_GET['sort'])) {
    $_GET['sort'] = 0;
}
if (!isset($_GET['page'])) {
    $_GET['page'] = 0;
}

// В начале файла после require_once
if (empty($user['mail_op'])) {
    $mc->query("UPDATE `users` SET `mail_op` = '0/0/0' WHERE `id` = '" . $user['id'] . "'");
    $user['mail_op'] = '0/0/0';
}

$mail_settings = explode("/", $user['mail_op']);
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
        padding: 8px;
    }
    
    .mail-container {
        max-width: 100%;
        width: 100%;
        margin: 0 auto;
        box-sizing: border-box;
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
        width: 100%;
        box-sizing: border-box;
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
    
    h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 24px;
    }
    
    /* Стили для навигационных кнопок */
    .mail-nav {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        border-radius: var(--radius);
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    .mail-nav-button {
        flex: 1;
        background: var(--secondary-bg);
        color: var(--text);
        border: 1px solid var(--glass-border);
        padding: 12px 0;
        cursor: pointer;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .mail-nav-button:hover {
        background: var(--item-hover);
    }
    
    .mail-nav-button.active {
        background: var(--primary-gradient);
        color: #111;
    }
    
    /* Стили для списка диалогов */
    .dialog-item {
        background: var(--secondary-bg);
        border-radius: calc(var(--radius) - 4px);
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid var(--glass-border);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        box-sizing: border-box;
    }
    
    .dialog-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
    }
    
    .dialog-name {
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .online-status {
        color: #2ecc71;
        position: relative;
    }
    
    .online-status::before {
        content: '•';
        margin-right: 5px;
        font-size: 1.5em;
        line-height: 0;
        position: relative;
        top: 3px;
    }
    
    .offline-status {
        color: var(--muted);
    }
    
    .dialog-count {
        background: rgba(0,0,0,0.2);
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .dialog-count b {
        color: var(--accent);
    }
    
    /* Стили для чата */
    .chat-container {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 20px;
        position: relative;
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 120px);
    }
    
    .chat-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
        z-index: 1;
    }
    
    .chat-header {
        padding: 15px;
        font-weight: 600;
        font-size: 18px;
        color: var(--accent);
        border-bottom: 1px solid var(--glass-border);
        text-align: center;
        z-index: 2;
    }
    
    .chat-header a {
        color: var(--accent);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .chat-header a:hover {
        color: var(--accent-2);
    }
    
    .chat-messages {
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column-reverse;
        flex: 1;
        min-height: 0;
        padding-bottom: 70px; /* Добавляем отступ для защиты от перекрытия */
    }
    
    .message {
        margin: 8px 0;
        max-width: 85%;
        clear: both;
        position: relative;
    }
    
    .message-outgoing {
        background: var(--primary-gradient);
        color: #111;
        padding: 12px 15px;
        border-radius: 15px 15px 0 15px;
        margin-left: auto;
        float: right;
    }
    
    .message-incoming {
        background: var(--secondary-bg);
        padding: 12px 15px;
        border-radius: 15px 15px 15px 0;
        margin-right: auto;
        float: left;
    }
    
    .message-content {
        word-break: break-word;
        line-height: 1.5;
    }
    
    .message-time {
        font-size: 12px;
        margin-top: 5px;
        opacity: 0.7;
        text-align: right;
    }
    
    .chat-input-container {
        padding: 15px;
        background: var(--secondary-bg);
        border-top: 1px solid var(--glass-border);
        display: flex;
        gap: 10px;
        z-index: 10;
        width: 100%;
        box-sizing: border-box;
        position: absolute;
        bottom: 0;
        left: 0;
    }
    
    .chat-input {
        flex: 1;
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .chat-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .chat-send-button {
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
        white-space: nowrap;
    }
    
    .chat-send-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .chat-blocked {
        text-align: center;
        padding: 15px;
        color: #e74c3c;
        background: rgba(231, 76, 60, 0.1);
        border-radius: var(--radius);
        margin: 15px;
        font-weight: 500;
    }
    
    /* Стили для настроек почты */
    .mail-options {
        max-width: 100%;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }
    
    .options-content {
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
    }
    
    .option-group {
        margin: 15px 0;
        width: 100%;
    }
    
    .option-item {
        background: var(--secondary-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }
    
    .option-item:hover {
        background: var(--item-hover);
    }
    
    .option-label {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        cursor: pointer;
        width: 100%;
    }
    
    .option-label input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-top: 3px;
    }
    
    .option-text {
        display: flex;
        flex-direction: column;
        gap: 5px;
        flex: 1;
    }
    
    .option-title {
        font-weight: 600;
        color: var(--text);
    }
    
    .option-description {
        font-size: 14px;
        color: var(--muted);
    }
    
    /* Адаптивные стили */
    @media (max-width: 768px) {
        body {
            padding: 0;
        }
        
        .mail-container {
            padding: 0;
        }
        
        .card {
            border-radius: 0;
            margin-bottom: 0;
        }
        
        .chat-container {
            border-radius: 0;
            height: calc(100vh - 120px);
            margin-bottom: 0;
        }
        
        .mail-nav {
            flex-wrap: nowrap;
        }
        
        .mail-nav-button {
            flex: 1;
            padding: 8px 4px;
            font-size: 12px;
            white-space: nowrap;
        }
        
        .message {
            max-width: 90%;
        }
        
        .chat-input-container {
            padding: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            box-sizing: border-box;
            background: var(--secondary-bg);
            z-index: 10;
            border-top: 1px solid var(--glass-border);
        }
        
        .chat-input {
            padding: 10px;
            font-size: 16px; /* Предотвращает зум на iOS */
            -webkit-appearance: none; /* Отключение стилей браузера на iOS */
            -moz-appearance: none;
            appearance: none;
        }
        
        /* Предотвращение скейлинга при открытии клавиатуры */
        input, textarea {
            font-size: 16px; /* Предотвращает зум на iOS при фокусе */
        }
    }
</style>

<?php
// Обновляем обработку опций почты
if (isset($_GET['setOption'])) {
    ?>
    <div class="mail-container">
        <div class="card">
            <h2>Настройки почты</h2>
            
            <div class="options-content">
                <form id="mailOptionsForm">
                    <div class="option-group">
                        <div class="option-item">
                            <label class="option-label">
                                <input type="checkbox" name="block_messages" value="1" <?= $user['mail_op'] == '1' ? 'checked' : '' ?>>
                                <span class="option-text">
                                    <span class="option-title">Заблокировать все входящие сообщения</span>
                                    <span class="option-description">Никто не сможет отправлять вам сообщения</span>
                                </span>
                            </label>
                        </div>
                        
                        <div class="option-item">
                            <label class="option-label">
                                <input type="checkbox" name="friends_only" value="1" <?= $user['mail_op'] == '2' ? 'checked' : '' ?>>
                                <span class="option-text">
                                    <span class="option-title">Принимать сообщения только от друзей</span>
                                    <span class="option-description">Только друзья смогут отправлять вам сообщения</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="option-group">
                        <a href="#" onclick="showContent('/mail_op.php')" class="chat-send-button" style="display: block; width: 100%; text-align: center; text-decoration: none;">
                            Расширенные настройки приватности
                        </a>
                    </div>
                    <div class="option-group">
                        <button type="button" class="chat-send-button" onclick="saveMailOptions()" style="display: block; width: 100%;">
                            Сохранить настройки
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function saveMailOptions() {
        var formData = $('#mailOptionsForm').serialize();
        showContent('/mail.php?save_options=1&' + formData);
    }
    </script>
    <?php
    $footval = 'mailtomain';
    require_once 'system/foot/foot.php';
    exit();
}

// Обновляем обработку сохранения опций
if (isset($_GET['save_options'])) {
    $mail_op = '0'; // По умолчанию все сообщения разрешены
    
    if (isset($_GET['block_messages'])) {
        $mail_op = '1'; // Блокировка всех сообщений
    } elseif (isset($_GET['friends_only'])) {
        $mail_op = '2'; // Только от друзей
    }
    
    $mc->query("UPDATE `users` SET `mail_op` = '$mail_op' WHERE `id` = '" . $user['id'] . "'");
    ?><script>showContent('/mail.php');</script><?php
    exit();
}

//Отправка смс
if (isset($_GET['write']) && $_GET['write'] != '') {
    $idRoom = 0;
    $IdSender = 0;
    //определимся с каким api почты мы работаем

    if (isset($_GET['id_2']) && $_GET['id_2'] > 0) {
        //старое апи
        // проверяем есть ли такая комната
        $FindRoom = $mc->query("SELECT * FROM `mail2` WHERE (`id1` = " . $user['id'] . " AND `id2` = " . $_GET['id_2'] . ") OR (`id2` = " . $user['id'] . " AND `id1` = " . $_GET['id_2'] . ")")->fetch_array(MYSQLI_ASSOC);
        if (count($FindRoom) == 0) {
            $mc->query("INSERT INTO `mail2`(`id1`, `id2`) VALUES (" . $user['id'] . ", " . $_GET['id_2'] . ")");
            $idRoom = $mc->insert_id;
        } else {
            $idRoom = $FindRoom['id'];
        }
    }

    if (isset($_GET['room']) && $_GET['room'] > 0) {
        $idRoom = $_GET['room'];
    }


    //проверка на доступность
    $MyMail = $mc->query("SELECT * FROM `mail2` WHERE `id` = " . $idRoom . " AND (`id1` = " . $user['id'] . " OR `id2` = " . $user['id'] . ")")->fetch_array(MYSQLI_ASSOC);
    if (count($MyMail) == 0) {
        ?><div class="mail-container">
            <div class="card">
                <div class="chat-blocked">Ошибка записи</div>
            </div>
        </div><?php
        $footval = 'tomail';
        require_once 'system/foot/foot.php';
        exit(0);
    }

    if ($MyMail['id1'] != $user['id'])
        $IdSender = $MyMail['id1'];
    else
        $IdSender = $MyMail['id2'];

    $_GET['write'] = urlencode(htmlspecialchars($_GET['write']));

    $date = date("d/m/Y H:i");
    //запишем себе с типом исходящии

    $mc->query("INSERT INTO `mailRoom`(`room_id`, `id_sender`, `readMsg`, `text`, `date`, `unixTime`) VALUES (" . $idRoom . ", " . $user['id'] . ", 0, '" . $_GET['write'] . "', '" . $date . "', '" . time() . "')");

    //Обновим таймер в общем списке
    $mc->query("UPDATE `mail2` SET `LastTime` = '" . time() . "' WHERE `mail2`.`id` = " . $idRoom . "");
    //запишем ему оповещение
    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $IdSender . "','Новое сообщение!','" . time() . "','mail')");
}

if (!isset($_GET['room']) && !isset($_GET['id_2'])) {
    ?>
    <div class="mail-container">
        <h2>Сообщения</h2>

    <div class="mail-nav">
        <button type="button" id="btn_5" class="mail-nav-button" onclick="$('ofline').hide();">Онлайн</button>
        <button type="button" id="btn_6" class="mail-nav-button" onclick="$('ofline').show();$('online').show();">Все</button>
        <button type="button" id="btn_7" class="mail-nav-button" onclick="filterFriends();">Друзья</button>
        <button type="button" id="btn_8" class="mail-nav-button" onclick="showContent('/mail.php?setOption');">Опции</button>
    </div>

    <div class="dialog-item" onclick="showContent('new.php')">
        <div class="dialog-name">Новости</div>
    </div>
    <?php
    $MyMails = $mc->query("SELECT * FROM `mail2` WHERE `id1` = " . $user['id'] . " OR `id2` = " . $user['id'] . " ORDER BY `LastTime` DESC");
    while ($MyMail = $MyMails->fetch_array(MYSQLI_ASSOC)) {
        $IdSender = 0;
        $CountMsg = 0;
        $StatusSender = "ofline";
        $HtmlDetaleSender = "id='noo'";

        if ($MyMail['id1'] != $user['id'])
            $IdSender = $MyMail['id1'];
        else
            $IdSender = $MyMail['id2'];

        $InfoSender = $mc->query("SELECT `name`,`online` FROM `users` WHERE `id` = " . $IdSender . "")->fetch_array(MYSQLI_ASSOC);
        $ReadMsgSender = $mc->query("SELECT COUNT(*) FROM `mailRoom` WHERE `room_id` = " . $MyMail['id'] . "")->fetch_array(MYSQLI_ASSOC);
        $NoReadMsgSender = $mc->query("SELECT COUNT(*) FROM `mailRoom` WHERE `room_id` = " . $MyMail['id'] . " AND `id_sender` != " . $user['id'] . " AND `readMsg` = 0")->fetch_array(MYSQLI_ASSOC);

        //Проверка на онлайн
        if ($InfoSender['online'] > time() - 60) {
            $StatusSender = "online";
            $HtmlDetaleSender = "id='onn' style='color:green'";
        }

        //соединяем новые и старые смс
        if ($NoReadMsgSender['COUNT(*)'] == 0) {
            $CountMsg = $ReadMsgSender['COUNT(*)'];
        } else {
            $CountMsg = "<b>" . $NoReadMsgSender['COUNT(*)'] . "</b>/" . $ReadMsgSender['COUNT(*)'];
        }

        //вывод
        ?>
        <<?= $StatusSender ?> id="<?= $IdSender ?>">
            <div class="dialog-item" onclick="showContent('mail.php?room=<?= $MyMail['id'] ?>')">
                    <span class="dialog-name <?= $StatusSender == 'online' ? 'online-status' : 'offline-status' ?>">
                    <?= $InfoSender['name'] ?>
                </span>
                <span class="dialog-count"><?= $CountMsg ?></span>
            </div>
        </<?= $StatusSender ?>>
        <?php
    }
        ?>
    </div>
    <?php
    $footval = 'mailtomain';
}


if (isset($_GET['id_2'])) {
    //Старый апи.. интегрирую
    $footval = 'mailtomain';
    $FindRoom = $mc->query("SELECT * FROM `mail2` WHERE (`id1` = " . $user['id'] . " AND `id2` = " . $_GET['id_2'] . ") OR (`id2` = " . $user['id'] . " AND `id1` = " . $_GET['id_2'] . ")")->fetch_array(MYSQLI_ASSOC);
    
    // Изменяем проверку
    if (!$FindRoom) {  // Заменяем count($FindRoom) == 0 на !$FindRoom
        //Если диалога нет.. делаю иллюзию
        $InfoSender = $mc->query("SELECT `name`,`online`, `mail_op` FROM `users` WHERE `id` = " . $_GET['id_2'] . "")->fetch_array(MYSQLI_ASSOC);
        $sender_settings = explode("/", $InfoSender['mail_op']);
        
        // Проверяем можно ли отправлять сообщения
        $can_send = false;
        
        if ($sender_settings[1] == '0') { // Все могут писать
            $can_send = true;
        } else if ($sender_settings[1] == '2') { // Только друзья
            $friend_check = $mc->query("SELECT COUNT(*) as cnt FROM `friends` WHERE 
                (`id1` = '" . $user['id'] . "' AND `id2` = '" . $_GET['id_2'] . "') OR 
                (`id2` = '" . $user['id'] . "' AND `id1` = '" . $_GET['id_2'] . "')")->fetch_array(MYSQLI_ASSOC);
            $can_send = ($friend_check['cnt'] > 0);
        } else if ($sender_settings[1] == '3') { // Друзья друзей
            $can_send = true; // Временно разрешаем для совместимости
        }

        // И используем эту проверку в форме
            ?>
        <div class="mail-container">
            <div class="chat-container">
                <div class="chat-header">
                    Диалог с <a onclick="showContent('/profile/<?php echo $_GET['id_2']; ?>')"><?php echo $InfoSender['name']; ?></a>
                </div>
                
                <div class="chat-messages">
                    <!-- Здесь будут сообщения -->
                </div>
                
                <?php if ($can_send): ?>
                <form id="form">
                    <div class="chat-input-container">
                        <input type="hidden" name="id_2" value="<?php echo $_GET['id_2']; ?>">
                        <input type="text" class="chat-input" name="write" placeholder="Введите сообщение...">
                        <button type="button" class="chat-send-button butt01">Отправить</button>
                    </div>
                </form>
                <?php else: ?>
                    <div class="chat-blocked">
                        Отправка сообщений ограничена настройками приватности
                    </div>
                <?php endif; ?>
            </div>
            </div>

            <script>
                $(".butt01").click(function () {
                    showContent("/mail.php?" + $("#form").serialize());
                });
            </script>
            <?php
    } else {
        ///а если есть диалог то
        ?><script>/*nextshowcontemt*/showContent('/mail.php?room=<?= $FindRoom['id'] ?>');</script><?php
        exit(0);
    }
}



//вывод сообщений
if (isset($_GET['room'])) {
    $footval = 'tomail';
    $IdSender = 0;
    //ищем комнату
    $MyMail = $mc->query("SELECT * FROM `mail2` WHERE `id` = " . $_GET['room'] . " AND (`id1` = " . $user['id'] . " OR `id2` = " . $user['id'] . ")")->fetch_array(MYSQLI_ASSOC);
    if (count($MyMail) == 0) {
        ?>
        <div class="mail-container">
            <div class="card">
                <div class="chat-blocked">Ошибка чтения</div>
            </div>
        </div>
        <?php
        require_once 'system/foot/foot.php';
        exit(0);
    }

    //Получаем юзера
    if ($MyMail['id1'] != $user['id'])
        $IdSender = $MyMail['id1'];
    else
        $IdSender = $MyMail['id2'];
    $InfoSender = $mc->query("SELECT `name`,`online`, `mail_op` FROM `users` WHERE `id` = " . $IdSender . "")->fetch_array(MYSQLI_ASSOC);

    //Читаем его смс
    $mc->query("UPDATE `mailRoom` SET `readMsg` = 1 WHERE `room_id` = " . $_GET['room'] . " AND `id_sender` != " . $user['id'] . " AND `readMsg` = 0");
    //Получаем список сообщений
    $MyMsgs = $mc->query("SELECT * FROM `mailRoom` WHERE `room_id` = " . $_GET['room'] . " ORDER BY `id` DESC ");
    
    $op = explode("/", $InfoSender['mail_op']);
    ?>
    <div class="mail-container">
    <div class="chat-container">
        <div class="chat-header">
            Диалог с <a onclick="showContent('/profile/<?= $IdSender ?>')"><?= $InfoSender['name'] ?></a>
        </div>

        <div class="chat-messages">
            <?php while ($MyMsg = $MyMsgs->fetch_array(MYSQLI_ASSOC)): ?>
                <div class="message <?= $MyMsg['id_sender'] == $user['id'] ? 'message-outgoing' : 'message-incoming' ?>">
                    <div class="message-content"><?= urldecode($MyMsg['text']) ?></div>
                    <div class="message-time"><?= $MyMsg['date'] ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($op[1] != '1'): // Если не заблокированы входящие ?>
            <form id="form">
                <div class="chat-input-container">
                    <input type="hidden" name="room" value="<?= $_GET['room'] ?>">
                    <input type="text" class="chat-input" name="write" placeholder="Введите сообщение...">
                    <button type="button" class="chat-send-button butt01">Отправить</button>
                </div>
            </form>
        <?php else: ?>
            <div class="chat-blocked">Отправка сообщений заблокирована</div>
        <?php endif; ?>
    </div>
    </div>
    <?php
}

require_once 'system/foot/foot.php';
?>

<script>
// Добавляем в начало скрипта функцию для предотвращения масштабирования
function preventZoomOnFocus() {
    document.addEventListener('focusin', function(e) {
        if(/(input|textarea)/i.test(e.target.tagName)) {
            // Убедимся, что видимая область не меняется
            document.body.scrollTop = document.body.scrollTop;
}
    });
    
    // Блокируем масштабирование на двойное нажатие (iOS)
    document.addEventListener('touchend', function(e) {
        var now = Date.now();
        var lastTouch = window.lastTouch || now + 1;
        var delta = now - lastTouch;
        if(delta < 300 && delta > 0) {
            e.preventDefault();
}
        window.lastTouch = now;
    }, false);
}

$(document).ready(function() {
    // Вызываем функцию предотвращения зума
    preventZoomOnFocus();
    
    // Прокрутка к последнему сообщению
    var chatMessages = $('.chat-messages');
    if(chatMessages.length > 0) {
    chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }
    
    // Обработка отправки сообщения
    $(".butt01").click(function () {
        var formData = $("#form").serialize();
        if(formData.indexOf('write=') > -1 && formData.split('write=')[1] !== '') {
            showContent("/mail.php?" + formData);
        }
    });
    
    // Обработка отправки сообщения по Enter
    $('.chat-input').keypress(function(e) {
        if(e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            $(".butt01").click();
        }
    });
    
    // Фокус на поле ввода при загрузке
    $('.chat-input').focus();
});

function filterFriends() {
    $('online').each(function() {
        $('divs').each(function() {
            if($('online').attr("id") == $('divs').attr("ids")) {
                $('online').hide();
            } else {
                $('ofline').hide();
            }
        });
    });
}
</script>
