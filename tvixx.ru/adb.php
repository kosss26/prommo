<?php
require_once('system/func.php');
require_once('system/dbc.php');
require_once('system/header.php');

$footval = "ban";
require_once('system/foot/foot.php');

$login = $user['login'];
$password = $user['password'];
$id = $user['id'];
$arr = array();
$access = $user['access'];

// Получаем номер чата
$chat = isset($_GET["chat"]) ? (int) $_GET["chat"] : 0;

// Проверяем блок в чате
$time = time();
$banned = 0;

$stmt = $mc->prepare("SELECT * FROM `chatban` WHERE `user` = ? AND `time` > ? ORDER BY `id` DESC LIMIT 1");
$stmt->bind_param('ii', $id, $time);
$stmt->execute();
$result11 = $stmt->get_result();
if ($result11->num_rows) {
    $userbanchat = $result11->fetch_assoc();
    $banned = 1;
}
$stmt->close();

// Проверка прав
if (($access > 0 && $banned == 0) || $access > 1) {
    if (!empty($_GET["msgid"])) {
        $msgid = (int) $_GET["msgid"];

        // Получаем инфо о сообщении чата
        $stmt = $mc->prepare("SELECT * FROM `chat` WHERE `id` = ? ORDER BY `id` DESC LIMIT 1");
        $stmt->bind_param('i', $msgid);
        $stmt->execute();
        $chatmsg = $stmt->get_result();
        
        if ($chatmsg->num_rows) {
            $arr = $chatmsg->fetch_assoc();
            $user_2_id = $arr['id_user'];
        } else {
            echo 'Сообщение не найдено';
            exit;
        }
        $stmt->close();

        // Получаем данные второго пользователя
        $stmt = $mc->prepare("SELECT * FROM `users` WHERE `id` = ? ORDER BY `id` DESC LIMIT 1");
        $stmt->bind_param('i', $user_2_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $user_2 = $result->fetch_assoc();
            $user_2_access = $user_2['access'];
        } else {
            echo 'Пользователь отсутствует. До свидания!';
            exit;
        }
        $stmt->close();
    } else {
        echo 'Неверный ID сообщения';
        exit;
    }
} else {
    echo 'Отказано в доступе';
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Бан в чате - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#41280A">
    <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #1a140f;
            color: #E6CC80;
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .ban_container {
            width: 96%;
            max-width: 800px;
            margin: 15px auto;
            animation: fadeIn 0.5s ease-out;
            box-sizing: border-box;
            text-align: center;
        }

        .ban_message {
            background: rgba(30, 20, 10, 0.7);
            border: 1px solid #8B4513;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.5;
        }

        .ban_select_container {
            margin: 20px 0;
        }

        .ban {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: 1px solid rgba(139, 69, 19, 0.4);
            border-radius: 4px;
            background: rgba(60, 40, 20, 0.5);
            color: #E6CC80;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }

        .ban:focus {
            outline: none;
            border-color: rgba(255, 215, 0, 0.6);
            background: rgba(80, 50, 20, 0.7);
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.3);
        }

        .ban option {
            background: rgba(60, 40, 20, 0.9);
            color: #E6CC80;
        }

        .button_alt_01 {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 15px;
            color: #E6CC80;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
            border: 1px solid #8B4513;
            background: linear-gradient(to right, rgba(65, 40, 10, 0.8), rgba(100, 60, 20, 0.8));
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .button_alt_01:before {
            content: '';
            position: absolute;
            left: 0; top: 0; height: 100%; width: 0;
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.1), transparent);
            transition: width 0.4s ease;
            z-index: 0;
        }

        .button_alt_01:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(139, 69, 19, 0.3);
            color: #FFD700;
            background: linear-gradient(to right, rgba(100, 60, 20, 0.9), rgba(139, 69, 19, 0.9));
        }

        .button_alt_01:hover:before {
            width: 100%;
        }

        .button_alt_01 span {
            position: relative;
            z-index: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .ban_container {
                padding: 10px;
            }

            .ban_message {
                font-size: 14px;
                padding: 12px;
            }

            .ban {
                padding: 8px;
                font-size: 13px;
                max-width: 250px;
            }

            .button_alt_01 {
                padding: 8px 15px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="ban_container">
    <div class="ban_message">
        <?= htmlspecialchars($arr['msg'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
    </div>

    <div class="ban_select_container">
        <select class="ban">
            <option value="1">Предупреждение</option>
            <option value="2">10 минут</option>
            <option value="3">30 минут</option>
            <option value="4">45 минут</option>
            <option value="5">1 час</option>
            <option value="6">2 часа</option>
            <option value="7">8 часов</option>
            <option value="8">24 часа</option>
            <option value="9">48 часов</option>
            <?php if ($user_2_access < $access) { ?>
                <option value="10">Вечно</option>
            <?php } ?>
            <?php if ($access > 1) { ?>
                <option value="11">Снять</option>
            <?php } ?>
        </select>
    </div>

    <div>
        <button class="button_alt_01"><span>Применить</span></button>
    </div>
</div>

<script>
    var but = 0;
    $(document).ready(function () {
        $(".button_alt_01").click(function () {
            if (but === 0) {
                $.ajax({
                    type: "POST",
                    url: "/system/banned.php",
                    data: {
                        "nick": <?= json_encode($login); ?>,
                        "pass": <?= json_encode($password); ?>,
                        "user_2_id": <?= json_encode($arr['id_user'] ?? ''); ?>,
                        "msgid": <?= json_encode($arr['id'] ?? ''); ?>,
                        "time": $('.ban').val(),
                        "msg": <?= json_encode($arr['msg2'] ?? ''); ?>,
                        "chat": <?= json_encode($chat); ?>
                    },
                    success: function () {
                        showContent('/chat.php?chat=' + <?= json_encode($chat); ?>);
                    }
                });
                but++;
            }
        });
    });
</script>

</body>
</html>