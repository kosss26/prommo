<?php
require_once ('system/func.php');
if (isset($_GET['menu']) && isset($_GET['id']) || isset($_GET['name']) && isset($_GET['id'])) {
    ?>
    <div class="gift-container">
        <div class="gift-header">Выбор подарка</div>
        <div class="gift-list">
            <?php
            $shop_gift = $mc->query("SELECT * FROM `shop_gift` ORDER BY `plata` ASC");
            while ($result = $shop_gift->fetch_array(MYSQLI_ASSOC)) {
                ?>
                <div class="gift-item" onclick="showContent('shop_gift.php?punct=<?= $result['id']; ?>&id=<?= $_GET['id']; ?>')">
                    <img class="gift-image" src="/images/gifts/<?= $result['img']; ?>.png" alt="<?= $result['name']; ?>">
                    <div class="gift-details">
                        <div class="gift-name"><?= $result['name']; ?></div>
                        <div class="gift-price">
                            <img src="/images/icons/plata.png" alt="Платина">
                            <span><?= $result['plata']; ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <style>
        .gift-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
        }

        .gift-header {
            color: #663300;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
        }

        .gift-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 10px;
        }

        .gift-item {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #663300;
            border-radius: 8px;
        }

        .gift-item:hover {
            transform: translateX(5px);
            background: rgba(255, 215, 0, 0.1);
        }

        .gift-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 15px;
        }

        .gift-details {
            flex: 1;
        }

        .gift-name {
            color: #663300;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .gift-price {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #663300;
        }

        .gift-price img {
            width: 16px;
            height: 16px;
        }

        @media screen and (max-width: 480px) {
            .gift-list {
                grid-template-columns: 1fr;
            }

            .gift-item {
                padding: 8px;
            }

            .gift-image {
                width: 60px;
                height: 60px;
            }
        }
    </style>
    <?php
}

if (isset($_GET['punct']) && !isset($_GET['name']) && isset($_GET['id'])) {
    $gift = $mc->query("SELECT * FROM `shop_gift` WHERE `id` = '" . $_GET['punct'] . "'")->fetch_array(MYSQLI_ASSOC);
    $user_1 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $_GET['id'] . "'")->fetch_array(MYSQLI_ASSOC);
    ?>
    <div class="gift-container">
        <div class="gift-header">Подарок для <?= $user_1['name']; ?></div>
        
        <div class="gift-preview">
            <img src="/images/gifts/<?= $gift['img']; ?>.png" alt="<?= $gift['name']; ?>">
            <div class="gift-name"><?= $gift['name']; ?></div>
        </div>

        <div class="gift-form">
            <label class="anonymous-option">
                <input type="checkbox" name="sches" value="off">
                Отправить анонимно
            </label>

            <div class="message-field">
                <textarea class="txt" name="text" placeholder="Напишите сообщение..." rows="3"></textarea>
            </div>

            <button id="send" class="gift-button" onclick="send();">
                Отправить подарок
            </button>
        </div>
    </div>

    <script>
        var bool = 0;
        var name = 1;
        function send() {
            if (bool == 0) {
                bool = 1;
                if ($("input[name=sches]").prop("checked") == false) {
                    name = 0;
                }
                showContent('shop_gift.php?text=' + $(".txt").val() + "&id=<?= $_GET['id']; ?>&punct=<?= $_GET['punct']; ?>&name=" + name);
            }
        }
    </script>

    <style>
        .gift-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            box-sizing: border-box;
        }

        .gift-header {
            color: #663300;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
        }

        .gift-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            padding: 10px;
        }

        .gift-item {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid #663300;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .gift-item:hover {
            transform: translateY(-2px);
            background: rgba(255, 215, 0, 0.2);
        }

        .gift-image {
            text-align: center;
            margin-bottom: 10px;
        }

        .gift-image img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .gift-info {
            text-align: center;
        }

        .gift-name {
            color: #663300;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .gift-price {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            color: #663300;
        }

        .gift-price img {
            width: 16px;
            height: 16px;
        }

        .gift-preview {
            text-align: center;
            margin: 20px 0;
        }

        .gift-preview img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .gift-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            margin: 0 auto;
            padding: 0 10px;
            box-sizing: border-box;
        }

        .anonymous-option {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #663300;
            cursor: pointer;
        }

        .message-field {
            width: 100%;
            box-sizing: border-box;
        }

        .message-field textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #663300;
            border-radius: 6px;
            resize: vertical;
            font-family: inherit;
            box-sizing: border-box;
            min-height: 80px;
            max-height: 200px;
        }

        .gift-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: linear-gradient(to bottom, #ffd700, #ffa500);
            border: 1px solid #663300;
            border-radius: 6px;
            color: #663300;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: bold;
            text-decoration: none;
        }

        .gift-button:hover {
            background: linear-gradient(to bottom, #ffd700, #ff8c00);
            transform: translateY(-1px);
        }

        .gift-button:active {
            transform: translateY(0);
        }

        @media screen and (max-width: 480px) {
            .gift-container {
                padding: 10px;
            }

            .gift-form {
                padding: 0 5px;
            }

            .message-field textarea {
                font-size: 16px;
            }
        }
    </style>
    <?php
}

if (isset($_GET['punct']) && isset($_GET['text']) && isset($_GET['id']) && isset($_GET['name'])) {
    $gift = $mc->query("SELECT * FROM `shop_gift` WHERE `id` = '" . $_GET['punct'] . "'")->fetch_array(MYSQLI_ASSOC);
    $user_1 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $_GET['id'] . "'")->fetch_array(MYSQLI_ASSOC);
    if (isset($user_1) && $user['platinum'] > 0 && $user['platinum'] >= $gift['plata']) {
        $plat= $user['platinum']-$gift['plata'];
        $mc->query("UPDATE `users` SET `platinum`='$plat' WHERE `id`='" . $user['id'] . "'");
        $anonymous = 0;
        if ($_GET['name'] == 0) {
            $anonymous = $user['id'];
        }
        if ($mc->query("INSERT INTO `gifts`("
                        . "`id`, `id_1`, `id_2`, `id_img`, `text`, `name` , `date_gifts`, `anonymous`"
                        . ") VALUES ("
                        . "NULL,"
                        . "'" . $user['id'] . "',"
                        . "'" . $user_1['id'] . "',"
                        . "'" . $gift['img'] . "',"
                        . "'" . $_GET['text'] . "',"
                        . "'" . $gift['name'] . "',"
                        . "'" . date('Y-m-d H:i:s') . "',"
                        . "'$anonymous'"
                        . ")")
        ) {
            $mc->query("INSERT INTO `msg`("
                    . "`id`,"
                    . " `id_user`,"
                    . " `message`,"
                    . " `type`,"
                    . " `date`"
                    . ")VALUES("
                    . "NULL,"
                    . "'" . $user_1['id'] . "',"
                    . "'" . urldecode('Вам подарок .') . "',"
                    ."'gifts',"
                    . "'" . time() . "'"
                    . ")");
            message(urlencode("Подарок отправлен " . $mc->error));
        } else {
            message(urlencode("<font style='color:red'>ошибка 76476</font>"));
        }
    } else {
        message(urlencode("Недостаточно средств"));
    }
}

$footval = "top";
require_once ('system/foot/foot.php');
?>