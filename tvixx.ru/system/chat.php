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
$user['id_clan']=$user['id_clan']+5;
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

<style>
.chat_container {
    max-width: 800px;
    margin: 0 auto;
    padding: 15px;
    box-sizing: border-box;
    border-left: 1px solid rgba(102, 51, 0, 0.2);
    border-right: 1px solid rgba(102, 51, 0, 0.2);
}

.chat_tabs {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 20px;
    position: relative;
}

.chat_tabs::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(102, 51, 0, 0.3), transparent);
}

.chat_tab {
    padding: 8px 20px;
    color: #663300;
    background: rgba(255, 215, 0, 0.1);
    border: 1px solid rgba(102, 51, 0, 0.2);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.chat_tab::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(255, 215, 0, 0.2), transparent);
    opacity: 0;
    transition: opacity 0.3s;
}

.chat_tab:hover::before {
    opacity: 1;
}

.chat_tab.active {
    background: rgba(255, 215, 0, 0.2);
    border-color: rgba(102, 51, 0, 0.3);
    font-weight: bold;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chat_input_container {
    background: rgba(255, 255, 255, 0.05);
    padding: 15px;
    border-radius: 8px;
    margin: 15px 0;
    border: 1px solid rgba(102, 51, 0, 0.2);
    width: 100%;
    box-sizing: border-box;
}

.chat_input {
    width: 100%;
    padding: 12px;
    border: 1px solid rgba(102, 51, 0, 0.2);
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.9);
    color: #663300;
    margin-bottom: 12px;
    font-size: 14px;
    transition: all 0.3s;
    box-sizing: border-box;
}

.chat_input:focus {
    outline: none;
    border-color: rgba(102, 51, 0, 0.4);
    box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.1);
}

.chat_button {
    background: linear-gradient(to bottom, #ffd700, #ffa500);
    color: #663300;
    border: 1px solid #663300;
    padding: 10px 24px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 14px;
    display: block;
    margin: 0 auto;
    min-width: 160px;
}

.chat_button:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background: linear-gradient(to bottom, #ffd700, #ff8c00);
}

.chat_button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.chat_messages {
    padding: 15px;
    font-size: 14px;
    line-height: 1.5;
    max-height: 500px;
    overflow-y: auto;
}

.chat_message {
    margin-bottom: 12px;
    padding: 10px;
    border-radius: 6px;
    background: rgba(255, 215, 0, 0.05);
    border: 1px solid rgba(102, 51, 0, 0.1);
    transition: background 0.3s;
}

.chat_message:hover {
    background: rgba(255, 215, 0, 0.1);
}

.chat_admin_controls {
    margin-top: 12px;
    text-align: center;
    color: #663300;
    font-size: 13px;
}

.chat_admin_controls label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.chat_admin_controls input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

/* Стилизация скроллбара */
.chat_messages::-webkit-scrollbar {
    width: 8px;
}

.chat_messages::-webkit-scrollbar-track {
    background: rgba(102, 51, 0, 0.1);
    border-radius: 4px;
}

.chat_messages::-webkit-scrollbar-thumb {
    background: rgba(102, 51, 0, 0.2);
    border-radius: 4px;
}

.chat_messages::-webkit-scrollbar-thumb:hover {
    background: rgba(102, 51, 0, 0.3);
}

@media (max-width: 480px) {
    .chat_container {
        padding: 10px;
        width: 100%;
    }
    
    .chat_tabs {
        gap: 6px;
    }
    
    .chat_tab {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .chat_input_container {
        padding: 10px;
        margin: 10px 0;
    }
    
    .chat_input {
        padding: 8px;
        font-size: 13px;
        width: 100%;
    }
    
    .chat_button {
        padding: 8px 16px;
        font-size: 13px;
        min-width: 140px;
    }
    
    .chat_messages {
        padding: 10px;
        font-size: 13px;
    }
}
</style>

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
            <button id="mybutton" class="chat_button">Отправить</button>
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
    MyLib.c = 0;
    MyLib.a = 1;

    // Добавляем функцию для отправки сообщения к ИИ
    function sendToAI(message) {
        $.ajax({
            type: "POST",
            url: "system/chatbot.php",
            data: { "message": message },
            success: function (data) {
                try {
                    console.log('Raw API response:', data);
                    // Пробуем распарсить ответ, учитывая возможное двойное экранирование
                    let response;
                    try {
                        // Сначала пробуем напрямую распарсить
                        response = JSON.parse(data);
                    } catch (e) {
                        // Если не получилось, пробуем убрать лишнее экранирование
                        response = JSON.parse(data.replace(/\\/g, ''));
                    }
                    
                    if (response.error) {
                        console.error('AI Error:', response.error);
                        $("mobitva:eq(-1)").find("#chat").prepend("<div style='color: red;'>Ошибка: " + response.error + "</div>");
                        // Разблокируем кнопку при ошибке
                        $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                        MyLib.a = 0;
                    } else {
                        console.log('Parsed response:', response);
                        // Проверяем, что response.response существует
                        if (response.response) {
                            let decodedMessage;
                            try {
                                // Пробуем декодировать Unicode-последовательности
                                decodedMessage = JSON.parse('"' + response.response + '"');
                            } catch (e) {
                                // Если не получилось, используем как есть
                                decodedMessage = response.response;
                            }
                            console.log('Decoded message:', decodedMessage);
                            postBotMessage(decodedMessage);
                        } else {
                            console.error('Invalid response format:', response);
                            $("mobitva:eq(-1)").find("#chat").prepend("<div style='color: red;'>Некорректный формат ответа</div>");
                            $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                            MyLib.a = 0;
                        }
                    }
                } catch (e) {
                    console.error('Parse Error:', e);
                    console.error('Raw data that failed to parse:', data);
                    $("mobitva:eq(-1)").find("#chat").prepend("<div style='color: red;'>Ошибка обработки ответа</div>");
                    // Разблокируем кнопку при ошибке
                    $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                    MyLib.a = 0;
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', status, error);
                console.error('Full error details:', xhr.responseText);
                $("mobitva:eq(-1)").find("#chat").prepend("<div style='color: red;'>Ошибка связи с сервером</div>");
                // Разблокируем кнопку при ошибке
                $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                MyLib.a = 0;
            }
        });
    }

    // Единый обработчик для кнопки отправки
    $('#mybutton').click(function () {
        $("mobitva:eq(-1)").find('#mybutton').attr('disabled', true);
        MyLib.c = 5;
        MyLib.a = 1;
        
        const message = $("mobitva:eq(-1)").find('#msg').val();
        if (message.toLowerCase().includes("лия") || message.toLowerCase().includes("босс")) {
            // Используем стандартную функцию отправки
            MyLib.send();
            // Ждем небольшую паузу перед отправкой запроса к ИИ
            setTimeout(function() {
                sendToAI(message);
            }, 1000);
        } else {
            MyLib.send();
        }
    });

    // Функция для отправки сообщения в чат от имени Bot-персонажа
    function postBotMessage(message) {
        console.log('Sending bot message:', message);
        console.log('Message is readable:', message);

        const sendData = {
            "nick": "Liya",
            "login": "Liya",
            "pass": "21021998",
            "msg": message,
            "chat": "<?php echo $chat; ?>",
            "pip": false,
            "name": "Лия",
            "level": "99"
        };
        console.log('Sending data:', sendData);

        $.ajax({
            type: "POST",
            url: "system/chatwrite.php",
            data: sendData,
            success: function (data) {
                console.log('Bot message response:', data);
                if (data !== "0") {
                    console.error('Chat Error:', data);
                    console.log('Error details:', data);
                    $("mobitva:eq(-1)").find("#chat").prepend("<font color='#ff0000'>" + data + "</font><br>");
                    $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                    MyLib.a = 0;
                } else {
                    console.log('Message sent successfully, updating chat...');
                    // Обновляем чат для получения других сообщений
                    lastId = 0;
                    // Делаем несколько попыток обновления
                    MyLib.update();
                    for (let i = 1; i <= 3; i++) {
                        setTimeout(() => {
                            console.log(`Update attempt ${i}`);
                            MyLib.update();
                        }, i * 500);
                    }
                    
                    $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                    MyLib.a = 0;
                }
            }
        });
    }

    $(document).ready(function () {
        MyLib.fc = function () {
            if (MyLib.a === 0) {
                if (MyLib.c > 0) {
                    $("mobitva:eq(-1)").find('#mybutton').val('Ждите ' + MyLib.c);
                    MyLib.c = MyLib.c - 1;
                } else {
                    $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                    $("mobitva:eq(-1)").find('#mybutton').val(' Отправить ');
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
            console.log('Updating chat...');
            $.ajax({
                type: "POST",
                url: 'system/chatread.php',
                data: {
                    "nick": "<?php echo $login; ?>",
                    "pass": "<?php echo $password; ?>",
                    "lastId": lastId,
                    "chat": "<?php echo $chat; ?>",
                    "_": new Date().getTime()
                },
                cache: false,
                success: function (data) {
                    console.log('Chat update response:', data);
                    try {
                        const parsedData = JSON.parse(data);
                        console.log('Parsed chat data:', parsedData);
                        if (parsedData.length > 0) {
                            console.log('First message in update:', parsedData[0]);
                        }

                        if (parsedData.length > 0 && (lastId === 0 || lastId !== parsedData[0][0])) {
                            lastId = parsedData[0][0];
                            for (var i = parsedData.length - 1; i >= 0; i--) {
                                console.log('Adding message:', parsedData[i]);
                                $("mobitva:eq(-1)").find("#chat").prepend(
                                    "<div style='margin-bottom: 6px;word-break: break-word;'>" + 
                                    parsedData[i][1] + 
                                    "</div>"
                                );
                            }
                            // Прокручиваем чат вверх после добавления сообщений
                            $("mobitva:eq(-1)").find("#chat").scrollTop(0);
                        }
                    } catch (e) {
                        console.error('Error updating chat:', e);
                        console.error('Data that caused error:', data);
                    }
                }
            });
        };

        MyLib.update();
        MyLib.intervaltimer.push(setInterval(function () {
            MyLib.update();
        }, 2000));
        MyLib.intervaltimer.push(setInterval(function () {
            MyLib.fc();
        }, 1000));
    });
</script>
<?php
include 'system/foot/foot.php';
?>