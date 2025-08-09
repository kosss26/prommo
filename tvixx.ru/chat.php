<?php
require_once ('system/func.php');
require_once ('system/dbc.php');

$footval = "chat";
auth();
$login = $user['login'];
$password = $user['password'];
$access = (int) $user['access'];
$name_1 = $user['name'];
$user_id = $user['id'];
$user['id_clan'] = $user['id_clan'] + 5;
if (isset($_GET['chat'])) {
    $chat = (int) $_GET['chat'];
} else {
    $chat = 0;
}

//проверяем бан
$time = time();
$banned = 0;
$result11 = $mc->query("SELECT * FROM `chatban` WHERE `username` = '" . $name_1 . "' AND `time`>'$time' ORDER BY `id` DESC LIMIT 1");
if ($result11->num_rows && $chat < 2) {
    $userbanchat = $result11->fetch_array(MYSQLI_ASSOC);
    $banned = 1;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#41280A">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #D4B98F;
            color: #4A3C31;
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .chat_container {
            width: 96%;
            max-width: 800px;
            margin: 15px auto;
        }

        .chat_tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            margin-bottom: 20px;
            background: linear-gradient(to right, rgba(224, 201, 166, 0.8), rgba(232, 213, 184, 0.8));
            border: 1px solid #A68B5F;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .chat_tab {
            padding: 6px 14px;
            border-radius: 20px;
            color: #4A3C31;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(166, 139, 95, 0.5);
            background-color: rgba(224, 201, 166, 0.3);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .chat_tab:hover {
            background-color: rgba(184, 159, 115, 0.6);
            color: #3A2F25;
            border-color: rgba(184, 159, 115, 0.8);
            transform: translateY(-1px);
        }

        .chat_tab.active {
            background-color: rgba(232, 213, 184, 0.9);
            color: #3A2F25;
            font-weight: 700;
            border: 1px solid #A68B5F;
            box-shadow: 0 0 8px rgba(166, 139, 95, 0.3);
        }

        .chat_input_container {
            background: rgba(232, 213, 184, 0.7);
            border: 1px solid #A68B5F;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            position: sticky;
            bottom: 0;
            z-index: 2;
        }

        .chat_input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(166, 139, 95, 0.5);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.9);
            color: #4A3C31;
            font-size: 16px;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .chat_input:focus {
            outline: none;
            border-color: rgba(184, 159, 115, 0.8);
            box-shadow: 0 0 5px rgba(184, 159, 115, 0.5);
        }

        .chat_button {
            width: 100%;
            max-width: 200px;
            padding: 10px;
            border-radius: 6px;
            background: linear-gradient(to right, #6B8E23, #556B2F);
            color: #FFF8DC;
            border: 1px solid rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            margin: 10px auto 0;
            display: block;
            position: relative;
            overflow: hidden;
            font-size: 16px;
        }

        .chat_button:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.2), transparent);
            transition: width 0.4s ease;
            z-index: 0;
        }

        .chat_button:hover:not(:disabled) {
            background: linear-gradient(to right, #556B2F, #4A7232);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .chat_button:hover:not(:disabled):before {
            width: 100%;
        }

        .chat_button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .chat_button span {
            position: relative;
            z-index: 1;
        }

        .chat_messages {
            background: rgba(232, 213, 184, 0.7);
            border: 1px solid #A68B5F;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            line-height: 1.5;
        }

        .chat_message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 6px;
            background: linear-gradient(to right, rgba(224, 201, 166, 0.5), rgba(232, 213, 184, 0.5));
            border: 1px solid rgba(166, 139, 95, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .chat_message:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(184, 159, 115, 0.1), transparent);
            transition: width 0.4s ease;
            z-index: 0;
        }

        .chat_message:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(166, 139, 95, 0.3);
            border-color: rgba(184, 159, 115, 0.6);
        }

        .chat_message:hover:before {
            width: 100%;
        }

        .chat_admin_controls {
            margin-top: 10px;
            text-align: center;
            color: #4A3C31;
            font-size: 14px;
        }

        .chat_admin_controls label {
            margin-left: 5px;
        }

        @media (max-width: 480px) {
            .chat_container {
                margin: 10px auto;
                width: 100%;
            }

            .chat_tabs {
                padding: 8px;
                gap: 6px;
            }

            .chat_tab {
                padding: 5px 10px;
                font-size: 13px;
            }

            .chat_input_container {
                padding: 10px;
                position: sticky;
                bottom: 0;
            }

            .chat_input {
                padding: 8px;
                font-size: 16px;
            }

            .chat_button {
                padding: 8px;
                font-size: 16px;
            }

            .chat_messages {
                padding: 10px;
                font-size: 13px;
            }
            
            input, textarea, select, button {
                font-size: 16px;
            }
            
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="chat_container">
        <div class="chat_tabs">
            <?php if ($access > 0): ?>
                <a class="chat_tab <?= $chat == 3 ? 'active' : '' ?>" onclick="showContent('/chat.php?chat=3')">МД</a>
            <?php endif; ?>
            <a class="chat_tab <?= $chat == 0 ? 'active' : '' ?>" onclick="showContent('/chat.php?chat=0')">Общий</a>
            <a class="chat_tab <?= $chat == 1 ? 'active' : '' ?>" onclick="showContent('/chat.php?chat=1')">Торговый</a>
            <?php if ($user['id_clan'] > 5): ?>
                <a class="chat_tab <?= $chat == $user['id_clan'] ? 'active' : '' ?>" onclick="showContent('/chat.php?chat=<?= $user['id_clan'] ?>')">Клановый</a>
            <?php endif; ?>
        </div>

        <?php if ($chat < 2 && $banned == 1): ?>
            <div class="chat_message">
                Вы заблокированы до <?= date("d.m.Y H:i:s", $userbanchat['time']); ?>
            </div>
        <?php else: ?>
            <div class="chat_input_container">
                <input type="text" id="msg" class="chat_input" placeholder="Введите сообщение...">
                <button id="mybutton" class="chat_button"><span>Отправить</span></button>
                <?php if ($user['access'] > '3'): ?>
                    <div class="chat_admin_controls">
                        <input type="checkbox" id="pip" name="pip">
                        <label for="pip">Админ-режим</label>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div id="chat" class="chat_messages"></div>
    </div>

    <script>
        function preventZoomOnFocus() {
            document.addEventListener('focusin', function(e) {
                if(/(input|textarea|select)/i.test(e.target.tagName)) {
                    document.body.scrollTop = document.body.scrollTop;
                }
            });
            
            document.addEventListener('touchend', function(e) {
                var now = Date.now();
                var lastTouch = window.lastTouch || now + 1;
                var delta = now - lastTouch;
                if(delta < 300 && delta > 0) {
                    e.preventDefault();
                }
                window.lastTouch = now;
            }, false);
            
            document.addEventListener('touchstart', function(e) {
                if (e.touches.length > 1) {
                    e.preventDefault();
                }
            }, { passive: false });
        }
        
        preventZoomOnFocus();
    
        MyLib.c = 0;
        MyLib.a = 1;

        $(document).ready(function () {
            preventZoomOnFocus();
            
            $("mobitva:eq(-1)").find('#mybutton').click(function () {
                $("mobitva:eq(-1)").find('#mybutton').attr('disabled', true);
                MyLib.c = 5;
                MyLib.a = 1;
                MyLib.send();
            });

            MyLib.fc = function () {
                if (MyLib.a === 0) {
                    if (MyLib.c > 0) {
                        $("mobitva:eq(-1)").find('#mybutton').html('<span>Ждите ' + MyLib.c + '</span>');
                        MyLib.c = MyLib.c - 1;
                    } else {
                        $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                        $("mobitva:eq(-1)").find('#mybutton').html('<span>Отправить</span>');
                    }
                }
            };

            MyLib.send = function () {
                $.ajax({
                    type: "POST",
                    url: "system/chatwrite.php",
                    data: {
                        "nick": "<?php echo $login; ?>",
                        "pass": "<?php echo $password; ?>",
                        "msg": $("mobitva:eq(-1)").find('#msg').val(),
                        "chat": "<?php echo $chat; ?>",
                        "pip": $("mobitva:eq(-1)").find("#pip").prop('checked')
                    },
                    success: function (data) {
                        $("mobitva:eq(-1)").find('#msg').val("");
                        MyLib.a = 0;
                        MyLib.update();
                        if (data !== "0") {
                            $("mobitva:eq(-1)").find("#chat").prepend("<font color='#ff0000'>" + data + "</font><br>");
                        }
                    }
                });
            };
            var lastId = 0;
            var cl = 0;
            MyLib.update = function () {
                $.ajax({
                    type: "POST",
                    url: 'system/chatread.php',
                    data: {
                        "nick": "<?php echo $login; ?>",
                        "pass": "<?php echo $password; ?>",
                        "lastId": lastId,
                        "chat": "<?php echo $chat; ?>"
                    },
                    success: function (data) {
                        if (JSON.parse(data).length > 0 && lastId === 0 || JSON.parse(data).length > 0 && lastId !== JSON.parse(data)[0][0]) {
                            try {
                                lastId = JSON.parse(data)[0][0];
                                for (var i = JSON.parse(data).length - 1; i >= 0; i--) {
                                    <?php if ($access == 1 && $chat < 2 && $banned == 0 || (int) $user['access'] > 1 && $chat < 2) { ?>
                                        if (JSON.parse(data)[i][2] > 0 && JSON.parse(data)[i][2] !== <?php echo $user_id; ?>) {
                                            $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message'>" + JSON.parse(data)[i][1].replace("] <font style='font", "] <a class='msgid' msgid=" + JSON.parse(data)[i][0] + ">±</a> <font style='font") + "</div>");
                                        } else {
                                            $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message'>" + JSON.parse(data)[i][1] + "</div>");
                                        }
                                    <?php } else { ?>
                                        $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message'>" + JSON.parse(data)[i][1] + "</div>");
                                    <?php } ?>
                                }
                                <?php if ($access == 1 && $chat < 2 && $banned == 0 || (int) $user['access'] > 1 && $chat < 2) { ?>
                                    $(".msgid").click(function () {
                                        if (cl == 0) {
                                            showContent("/adb.php?msgid=" + $(this).attr("msgid") + "&chat=<?php echo $chat; ?>");
                                            cl++;
                                        }
                                    });
                                <?php } ?>
                            } catch (e) {
                            }
                        }
                    }
                });
            };

            MyLib.update();
            MyLib.intervaltimer.push(setInterval(function () {
                MyLib.update();
            }, 10000));
            MyLib.intervaltimer.push(setInterval(function () {
                MyLib.fc();
            }, 1000));
        });
    </script>
    <?php include 'system/foot/foot.php'; ?>
</body>
</html>