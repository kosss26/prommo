<?php
require_once ('system/func.php');

$login = $user['login'];
$password = $user['password'];
$access = (int) $user['access'];
$clan = $mc->query("SELECT * FROM `clan` WHERE `id` = '" . $user['id_clan'] . "'")->fetch_array(MYSQLI_ASSOC);
$chat = (int) $user['id_clan'] + 5;

if ($chat > 5) {
?>

<style>
.chat_container {
    padding: 10px;
    max-width: 100%;
    margin: 0 auto;
}

.chat_header {
    text-align: center;
    padding: 6px 0;
    margin-bottom: 10px;
}

.chat_header_link {
    font-size: 19px;
    color: #4A2601;
    text-decoration: underline;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
}

.chat_header_link:hover {
    color: #643201;
}

.chat_divider {
    height: 1px;
    background: rgba(232, 207, 153, 0.5);
    margin: 15px 0;
    border: none;
}

.chat_input_container {
    text-align: center;
    margin: 15px 0;
    padding: 15px;
    background: rgba(255, 215, 0, 0.07);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
}

.chat_input {
    width: 90%;
    max-width: 400px;
    padding: 12px 15px;
    border: 1px solid rgba(187, 152, 84, 0.5);
    border-radius: 20px;
    margin-bottom: 12px;
    background: rgba(255, 255, 255, 0.2);
    color: #643201;
    font-size: 15px;
    transition: all 0.3s ease;
}

.chat_input:focus {
    outline: none;
    border-color: #8B4513;
    box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
    background: rgba(255, 255, 255, 0.3);
}

.chat_button {
    width: 80%;
    max-width: 300px;
    padding: 12px 20px;
    background: linear-gradient(to bottom, #a56c2e, #8B4513);
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: -4px;
    font-weight: bold;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 10px rgba(139, 69, 19, 0.2);
}

.chat_button:hover:not(:disabled) {
    background: linear-gradient(to bottom, #8B4513, #643201);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(139, 69, 19, 0.3);
}

.chat_button:active:not(:disabled) {
    transform: translateY(1px);
    box-shadow: 0 1px 3px rgba(139, 69, 19, 0.2);
}

.chat_button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.chat_messages {
    width: 100%;
    height: 100%;
    overflow-y: auto;
    padding: 10px 0;
}

.chat_message {
    margin-bottom: 10px;
    word-break: break-word;
    line-height: 1.5;
    padding: 10px 15px;
    border-radius: 10px;
    transition: all 0.2s ease;
    background: rgba(255, 215, 0, 0.05);
    border-left: 3px solid rgba(139, 69, 19, 0.3);
}

.chat_message:hover {
    background: rgba(255, 215, 0, 0.1);
    border-left-color: rgba(139, 69, 19, 0.6);
}

.chat_error {
    color: #ff0000;
    font-weight: bold;
    padding: 20px;
    text-align: center;
    background: rgba(255, 0, 0, 0.05);
    border-radius: 8px;
    margin: 20px 0;
}

/* Адаптивность для мобильных устройств */
@media (max-width: 768px) {
    .chat_container {
        padding: 5px;
    }
    
    .chat_input_container {
        padding: 10px;
    }
    
    .chat_input {
        font-size: 14px;
        padding: 10px 12px;
    }
    
    .chat_button {
        font-size: 14px;
        padding: 10px 15px;
    }
    
    .chat_header_link {
        font-size: 18px;
    }
    
    .chat_message {
        padding: 8px 12px;
        margin-bottom: 8px;
    }
}

@media (max-width: 480px) {
    .chat_input {
        width: 100%;
        font-size: 13px;
    }
    
    .chat_button {
        width: 100%;
        font-size: 13px;
    }
}
</style>

<div class="chat_container">
    <div class="chat_header">
        <a class="chat_header_link" onclick="showContent('/clan/clan_all.php?see_clan=<?=$user['id_clan'];?>')"><?php echo $clan['name']; ?></a>
    </div>

    <hr class="chat_divider">

    <div class="chat_input_container">
        <input type="text" 
               id="msg" 
               class="chat_input" 
               name="msg" 
               maxlength="200" 
               placeholder="Введите сообщение..." 
               autocomplete="off">
        <button id="mybutton" 
                class="chat_button">
            Отправить
        </button>
    </div>

    <hr class="chat_divider">

    <div id="chat" class="chat_messages"></div>
</div>

<script>
    MyLib.c = 0;
    MyLib.a = 1;
    $(document).ready(function () {
        $("mobitva:eq(-1)").find('#mybutton').click(function () {
            $("mobitva:eq(-1)").find('#mybutton').attr('disabled', true);
            MyLib.c = 5;
            MyLib.a = 1;
            MyLib.send();
        });
            
        MyLib.fc = function () {
            if (MyLib.a === 0) {
                if (MyLib.c > 0) {
                    $("mobitva:eq(-1)").find('#mybutton').text('Ждите ' + MyLib.c);
                    MyLib.c = MyLib.c - 1;
                } else {
                    $("mobitva:eq(-1)").find('#mybutton').removeAttr('disabled');
                    $("mobitva:eq(-1)").find('#mybutton').text('Отправить');
                }
            }
        }
            
        MyLib.send = function () {
            $.ajax({
                type: "POST",
                url: "system/chatwrite.php",
                data: {
                    "nick": "<?php echo $login; ?>",
                    "names": "<?php echo $user['name']; ?>",
                    "pass": "<?php echo $password; ?>",
                    "msg": $("mobitva:eq(-1)").find('#msg').val(),
                    "chat": "<?php echo $chat; ?>"
                },
                success: function (data) {
                    $("mobitva:eq(-1)").find('#msg').val("");
                    MyLib.a = 0;
                    MyLib.update();
                    if (data !== "0") {
                        $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message chat_error'>" + data + "</div>");
                    }
                }
            });
        }
        var lastId = 0;
            
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
                    if (lastId === 0 || JSON.parse(data).length > 0 && lastId !== JSON.parse(data)[0][0]) {
                        try {
                            lastId = JSON.parse(data)[0][0];
                            for (var i = JSON.parse(data).length - 1; i >= 0; i--) {
    <?php if ((int) $user['access'] == 1 && $chat != 3 && $chat < 4 || (int) $user['access'] == 2 && $chat != 3 && $chat < 4) { ?>
                                    if (JSON.parse(data)[i][2] > 0) {
                                        $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message'><a onclick=\"showContent('/Adb.php?id=" + JSON.parse(data)[i][2] + "&id_room=" +<?php echo $chat; ?> + "&id_message=" + JSON.parse(data)[i][0] + "')\">±</a> " + JSON.parse(data)[i][1] + "</div>");
                                    } else {
                                        $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message'>" + JSON.parse(data)[i][1] + "</div>");
                                    }
    <?php } else { ?>
                                    $("mobitva:eq(-1)").find("#chat").prepend("<div class='chat_message'>" + JSON.parse(data)[i][1] + "</div>");
    <?php } ?>
                            }
                        } catch (e) {
                                
                        }
                                
                    }
                }
            });
        }
            
        MyLib.update();
        MyLib.intervaltimer.push(setInterval(function () {
            MyLib.update();
        }, 10000));
        MyLib.intervaltimer.push(setInterval(function () {
            MyLib.fc();
        }, 1000));
        
        // Добавляем обработчик Enter для отправки сообщения
        $("mobitva:eq(-1)").find('#msg').on('keypress', function(e) {
            if(e.which === 13 && !$("mobitva:eq(-1)").find('#mybutton').prop('disabled')) {
                $("mobitva:eq(-1)").find('#mybutton').click();
            }
        });
    });
        
</script>

<?php
$footval = "chatclan";
require_once ('system/foot/foot.php');
} else {
?>
    <div class="chat_container">
        <div class="chat_error" style="text-align: center; padding: 40px;">
            Клан не найден
        </div>
    </div>
<?php
    $footval = "chatclannone";
    require_once ('system/foot/foot.php');
}
?>