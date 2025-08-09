<?php

require_once ('../../system/func.php');
require_once ('../../system/dbc.php');

if (isset($_GET['newGrab'])) {
    $onl = time();
    $user_loc = $mc->query("SELECT * FROM `users` WHERE `location` = '" . $user['location'] . "' AND `online`>($onl-60)  AND `health` > '0' ");

    if ($user_loc->num_rows > 0) {
        $userS = $user_loc->fetch_all(MYSQLI_ASSOC);
        $rand = rand(0, count($userS) - 1);
        if ($userS[$rand]['id'] != $user['id']) {
            echo '<div class="found_player">';
            echo '<div class="search_title">Найден противник</div>';
            echo "<div>Игрок " . $userS[$rand]['name'] . " [" . $userS[$rand]['level'] . "]</div>";
            echo '<div class="countdown" id="ol"></div>';
            echo '</div>';
            ?>
            <script>
                var col = 4;
                MyLib.intervaltimer[1200] = setInterval(function () {
                    $('#ol').text(col);
                    col--;
                    if (col <= 0) {
                        col = 0;
                        $('#ol').text("В бой");
                        $('#ol').addClass("battle_ready");
                    }

                }, 1400);
            </script>
            <?php
        }
    } else {
        echo '<div class="not_found">В этой локации никого нет</div>';
    }
}