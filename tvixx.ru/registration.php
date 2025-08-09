<?php
require_once ('system/func.php');
require_once ('system/header.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<?php
//noauth(); // Закроем от авторизированых
//1 - off , 0 - on
$reg_onoff = 0;


if (isset($_GET['names']) && isset($_GET['login']) &&
        isset($_GET['pass']) && isset($_GET['repass']) && isset($_GET['side']) && isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $names = htmlspecialchars($mc->real_escape_string($_GET['names']));
    $login = htmlspecialchars($mc->real_escape_string($_GET['login']));
    $pass = htmlspecialchars($mc->real_escape_string($_GET['pass']));
    $repass = htmlspecialchars($mc->real_escape_string($_GET['repass']));
    $mail = htmlspecialchars($mc->real_escape_string($_GET['mail']));
    $side = htmlspecialchars($mc->real_escape_string($_GET['side']));
    $loca = 98; //Школа воинов

    $sql = $mc->query("SELECT * FROM `users` WHERE `login` = '" . $login . "'")->num_rows;  // Доступность логина
    $sql1 = $mc->query("SELECT * FROM `users` WHERE `name` = '" . $names . "'")->num_rows;  // Доступность Имени
    $findlogin = $mc->query("SELECT COUNT(0) FROM `users` WHERE `login`='" . $login . "'")->fetch_array(MYSQLI_ASSOC);
    $findname = $mc->query("SELECT COUNT(0) FROM `users` WHERE `name`='" . $names . "'")->fetch_array(MYSQLI_ASSOC);

    if (empty($login)) {
        message('Введите логин');
    } else if (empty($names)) {
        message('Введите имя');
    } else if (empty($pass)) {
        message('Введите пароль');
    } else if ($findlogin['COUNT(0)'] != 0) {
        message('Данный логин уже занят');
    } else if ($findname['COUNT(0)'] != 0) {
        message('Данное имя уже занято ');
    } else if (empty($repass)) {
        message('Введите пароль еще раз');
    } else if (empty($mail)) {
        message('Введите почтовый ящик');
    } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        message('Некорректный формат почтового ящика');
    } else if (!preg_match('|^[a-z0-9\-]+$|i', $pass)) {
        message('Кириллица в пароле запрещена');
    } else if ($sql > 0) {
        message('Такой логин уже занят');
    } else if ($sql1 > 0) {
        message('Такой ник уже занят');
    } else if (mb_strlen($login) > 20 or mb_strlen($login) < 3) {
        message('Логин не может быть короче 3 и длинее 20 символов');
    } else if (mb_strlen($names) > 20 or mb_strlen($names) < 3) {
        message('Имя не может быть короче 3 и длинее 20 символов');
    } else if (mb_strlen($pass) > 20 or mb_strlen($pass) < 3) {
        message('Пароль не может быть короче 3 и длинее 20 символов');
    } else if ($pass != $repass) {
        message('Пароль не совпадают');
    } else if ($login == $pass) {
        message('Логин и пароль не должны совпадать');
    } else if ($side < 0 || $side > 3) {
        message('Некорректная расса');
        exit(0);
    } else {
        $myref = $mc->query("SELECT * FROM `refLast` WHERE `id`='1'")->fetch_array(MYSQLI_ASSOC);
        $myref['refLast']++;
        $mc->query("UPDATE `refLast` SET `refLast` = '" . $myref['refLast'] . "' WHERE `id` ='1'");
        $mc->query("INSERT INTO `users` SET "
                . "`login` = '" . $login . "',"
                . "`name` = '" . $names . "',"
                . " `password` = '" . md5($pass) . "',"
                . " `email` = '" . $mail . "',"
                . " `side` = '" . $side . "',"
                . "`location` = '" . $loca . "',"
                . "`tutorial`='1' ,"
                . "`max_health`='15',"
                . "`health`='15',"
                . "`strength`='1',"
                . "`toch`='8',"
                . "`lov`='3',"
                . "`kd`='2',"
                . "`myref`='" . $myref['refLast'] . "',"
                . "`ref`='$ref',"
                . "`registr` = '" . time() . "',`superudar`='" . rand(1, 3) . rand(1, 3) . "'");
        if ($mc->insert_id) {
            $uzernewid = $mc->insert_id;
            $arrTemp = [1354, 1355, 1356];
            for ($i = 0; $i < count($arrTemp); $i++) {
                //смотрим на новую вещь
                $infoshop1Res = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $arrTemp[$i] . "'");
                if ($infoshop1Res->num_rows > 0) {
                    $infoshop1 = $infoshop1Res->fetch_array(MYSQLI_ASSOC);
                    //дата истечения в unix
                    if ($infoshop1['time_s'] > 0) {
                        $time_the_lapse = $infoshop1['time_s'] + time();
                    } else {
                        $time_the_lapse = 0;
                    }
                    $mc->query("INSERT INTO `userbag`("
                            . "`id_user`,"
                            . " `id_shop`,"
                            . " `id_punct`,"
                            . " `dress`,"
                            . " `iznos`,"
                            . " `time_end`,"
                            . " `id_quests`,"
                            . " `koll`,"
                            . " `max_hc`,"
                            . " `stil`,"
                            . " `BattleFlag`"
                            . ") VALUES ("
                            . "'" . $uzernewid . "',"
                            . "'" . $infoshop1['id'] . "',"
                            . "'" . $infoshop1['id_punct'] . "',"
                            . "'1',"
                            . "'" . $infoshop1['iznos'] . "',"
                            . "'$time_the_lapse',"
                            . "'" . $infoshop1['id_quests'] . "',"
                            . "'" . $infoshop1['koll'] . "',"
                            . "'" . $infoshop1['max_hc'] . "',"
                            . "'" . $infoshop1['stil'] . "',"
                            . "'" . $infoshop1['BattleFlag'] . "'"
                            . ")");
                }
            }

            //проверяем ссылку реф
            if ($ref > 0) {
                //если герой есть с таким номером реф
                if ($mc->query("SELECT * FROM `users` WHERE `myref` = '$ref'")->num_rows > 0) {
                    //запишем зарегистрированному 20 серебра и сообщение
                    $mc->query("UPDATE `users` SET `money` = `money`+'2000' WHERE `id` = '$uzernewid' ");
                    $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('$uzernewid','Вы получили 20 <img class=\"ico_head_all\" src=\"/images/icons/serebro.png\">. От пригласившего вас игрока.','" . time() . "','ref')");
            
                }
            }
            
            setcookie('login', urlencode($login), time() + 86400 * 365, '/');
            setcookie('password', md5($pass), time() + 86400 * 365, '/');
            $user = $mc->query("SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '" . md5($pass) . "' ORDER BY `id` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
            ?>
            <script>
                // Анимация при успешной регистрации
                $('.register-container').addClass('animate__animated animate__fadeOut');
                setTimeout(function() {
                    showContent("/main.php?initGameRegistered");
                }, 500);
            </script>
            <?php
            exit(0);
        }
    }
}
?>

<!-- Фон с частицами -->
<div id="particles-js"></div>

<?php if ($reg_onoff == 0) { ?>
<div class="register-container animate__animated animate__fadeIn">
    <div class="login-logo">
        <img src="images/logo2.png" alt="Логотип" class="animate__animated animate__pulse animate__infinite">
    </div>
    
    <div class="register-card auth-card">
        <div class="auth-header">
            <h2>Регистрация</h2>
            <p class="subtitle">Создайте своего персонажа</p>
        </div>
        
        <div class="auth-content">
            <div class="form-group">
                <label for="names">Имя персонажа:</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-tag"></i>
                    <input type="text" id="names" class="form-control" maxlength="50" placeholder="Введите имя персонажа">
                </div>
                <small class="form-hint">От 3 до 20 символов</small>
            </div>
            
            <div class="form-group">
                <label for="login">Логин:</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="login" class="form-control" maxlength="50" placeholder="Введите логин для входа">
                </div>
                <small class="form-hint">От 3 до 20 символов</small>
            </div>
            
            <div class="form-group">
                <label for="pass">Пароль:</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="pass" class="form-control" maxlength="50" placeholder="Придумайте пароль">
                    <span class="toggle-password" onclick="togglePasswordVisibility('pass')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <small class="form-hint">Только латинские буквы, цифры и символы</small>
            </div>
            
            <div class="form-group">
                <label for="repass">Повторите пароль:</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="repass" class="form-control" maxlength="50" placeholder="Повторите пароль">
                    <span class="toggle-password" onclick="togglePasswordVisibility('repass')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="mail">E-mail:</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="mail" class="form-control" maxlength="50" placeholder="Введите почтовый ящик">
                </div>
                <small class="form-hint">Необходим для восстановления доступа</small>
            </div>
            
            <div class="form-group">
                <label for="side">Выберите сторону:</label>
                <div class="input-with-icon">
                    <i class="fas fa-users"></i>
                    <select id="side" class="form-control">
                        <option value="0">Шейванин</option>
                        <option value="1">Шейванка</option>
                        <option value="2">Нармасец</option>
                        <option value="3">Нармаска</option>
                    </select>
                </div>
                <small class="form-hint">Выберите сторону, за которую будете играть</small>
            </div>
            
            <div class="form-group">
                <label for="ref">Реф-ссылка (если есть):</label>
                <div class="input-with-icon">
                    <i class="fas fa-link"></i>
                    <input type="text" id="ref" class="form-control" maxlength="50" placeholder="Реф-ссылка (необязательно)">
                </div>
                <small class="form-hint">Дает бонус при регистрации</small>
            </div>
            
            <div class="form-actions">
                <button class="btn-primary btn-large register-btn" id="registerBtn">
                    <span class="btn-text">Создать персонажа</span>
                    <div class="btn-effect"></div>
                </button>
                
                <button class="btn-secondary login-btn" onclick="showContent('/index.php')">
                    <span class="btn-text">Уже есть аккаунт? Войти</span>
                    <div class="btn-effect"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Инициализация частиц для фона
    document.addEventListener('DOMContentLoaded', function() {
        particlesJS("particles-js", {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: "#c8ac70" },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: true },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#c8ac70",
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: "none",
                    random: true,
                    straight: false,
                    out_mode: "out",
                    bounce: false
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: { enable: true, mode: "repulse" },
                    onclick: { enable: true, mode: "push" }
                },
                modes: {
                    repulse: { distance: 100, duration: 0.4 },
                    push: { particles_nb: 4 }
                }
            }
        });
        
        // Устанавливаем первую расу как выбранную по умолчанию
        $('#side').val("0");
    });
    
    // Переключение видимости пароля
    function togglePasswordVisibility(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = document.querySelector('#' + fieldId).nextElementSibling.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
    
    // Обработка клика по кнопке регистрации
    $("#registerBtn").click(function() {
        // Анимация кнопки
        $(this).addClass('button-pressed');
        
        setTimeout(() => {
            showContent(
                "/registration.php?" +
                "names=" + encodeURIComponent($("#names").val()) +
                "&login=" + encodeURIComponent($("#login").val()) +
                "&pass=" + encodeURIComponent($("#pass").val()) +
                "&repass=" + encodeURIComponent($("#repass").val()) +
                "&mail=" + encodeURIComponent($("#mail").val()) +
                "&side=" + encodeURIComponent($("#side").val()) +
                "&ref=" + encodeURIComponent($("#ref").val())
            );
            
            // Удаляем класс анимации
            $(this).removeClass('button-pressed');
        }, 300);
    });
    
    // Обработка нажатия Enter
    $("input").keypress(function(e) {
        if (e.which === 13) {
            $("#registerBtn").click();
        }
    });
</script>
<?php
}
$footval = 'registration';
require_once ('system/foot/foot.php');
?>