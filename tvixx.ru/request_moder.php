<?php
require_once ('system/func.php');
require_once ('system/header.php');

auth(); // Закроем от неавторизированых
norequestModer(); // Закроем от тех у кого нет запроса

$id = $user['id'];
$login = $user['login'];
$loca = $user["location"];
$side = $user["side"];

$location = $mc->query("SELECT * FROM `location` WHERE `id`='$loca'")->fetch_array(MYSQLI_ASSOC);


// Принимаем пост запрпос, если человек нажал на кнопку
if (isset($_GET['access'])) {
    $data = $_GET['access'];
    if ($data == 'true') {
        // Сделать человека модератором
        // Добавляем пользователя в таблицу модераторов
        $mc->query("UPDATE `users` SET `access` = '1' WHERE `users`.`id` = '$id'");
        $add_moder = $mc->query("INSERT INTO `moderator` (`user_id`, `user_login`) VALUES ('$id', '$login')");

        // Удаляем его из запросов на модератора
        $delete_request = $mc->query("DELETE FROM `request_moderator` WHERE `user_id` = '$id'");
    } else if ($data == 'false') {
        // Удалить человека из запросов на модератора
        // Посылаем запрос в БД на удаление запроса, где id заявки равно id запроса
        $delete_request = $mc->query("DELETE FROM `request_moderator` WHERE `user_id` = '$id'");
    }
    ?><script>showContent("/main.php");</script><?php
} else {
    ?>

    <center>
        <div class="ramka_dvig">
            <div class="location<?php echo $location['IdImage']; ?>">
                <div class="location" style="z-index: 99;">

                </div>
                <div class="questpers06">
                    </div>
            </div>
        </div>
        <br>
        <table class="table_block2" cellspacing="0" cellpadding="0">
            <tr>
                <td class="block101"></td>
                <td class="block102"></td>
                <td class="block103"></td>
            </tr>

            <tr>
                <td class="block104"></td>
                <td class="block105">
            <center>
                <div>
                    <b>
                        Многоуважаемый, не хотите ли стать модератором нашей игры?
                        Мы предоставим Вам массу возможностей - от обычного бана недругам,
                        до зарплаты за Вашу работу. Естественно, Вы можете отказаться
                        от нашего предложения.
                    </b>
                </div>
                <br>
                <div class="button_alt_00" onclick="showContent('request_moder.php?access=true')" style="height: 50px;line-height: 50px;">
                    <b>Принять предложение</b>
                </div>
                <p>
                <div class="button_alt_00" onclick="showContent('request_moder.php?access=false')" style="height: 50px;line-height: 50px;">
                    <b>Отклонить предложение</b>
                </div>
            </center>
            </td>
            <td class="block106"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table>

    </center>

<?php } $footval='requestmoder'; require_once 'system/foot/foot.php'; ?>