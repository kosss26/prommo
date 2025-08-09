<?php
require_once ('system/func.php');
require_once ('system/dbc.php');
require_once ('system/header.php');
noauth();

// Добавление необходимых стилей и скриптов в заголовок
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<?php

if (isset($_GET['vostanovlenie'])) {
    ?>
    <div class="auth-container animate__animated animate__fadeIn">
        <div class="auth-header">
            <h2>Получение кода восстановления</h2>
        </div>
        <div class="auth-card">
            <div class="auth-content">
                <p>Если к вашему персонажу привязан почтовый ящик, то введите его:</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="eemail" maxlength="50" placeholder="Email">
                    <button class="btn-primary" onclick="showContent('/index.php?vostanovlenie&email=' + $('#eemail').val())">
                        <span class="btn-text">Отправить</span>
                        <div class="btn-effect"></div>
                    </button>
                </div>
                <p class="small-text">На указанный почтовый ящик будет отправлен код восстановления</p>
            </div>
        </div>
    </div>
    <?php
}

if (isset($_GET['vostanovlenie']) && isset($_GET['email'])) {
    $code = rand(111111, 999999);
    $userE = 1;
    $email = $_GET['email'];
    $userCOUNTe = $mc->query("SELECT COUNT(*) FROM `users` WHERE `email` = '" . $_GET['email'] . "'")->fetch_array(MYSQLI_ASSOC);
    $userInfoE = $mc->query("SELECT * FROM `users` WHERE `email`")->fetch_array(MYSQLI_ASSOC);
    if ($userCOUNTe['COUNT(*)'] > 1) {
        $userE = "*";
    }
    if ($mc->query("INSERT INTO `code_mail` (`code`,`id_user`,`email`) VALUES('" . $code . "','" . $userE . "','" . $email . "')")) {
        $date = date("h:i");
        $time = date("d.m.20y");
        $headers = "<head>MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n</head>";
        $htmlpismo = "<div style='background-color:#C8AC70;padding:20px;border-radius:8px;font-family:Arial,sans-serif;'>
            <h2 style='color:#472c00;'>Здравствуйте, " . $user['name'] . "</h2>
            <p style='font-size:16px;'><b>Ваш код:</b> " . $code . "</p>
            <p><b>Дата:</b> " . $time . "</p>
            <p><b>Время:</b> " . $date . "</p>
            <p><b>Сервер:</b> mmoria</p>
            <p>По всем вопросам писать на support@tvixx.ru</p>
            <div style='text-align:center;margin-top:20px;'>
                <img src='https://tvixx.ru/images/logo2.png' style='max-width:250px;'>
            </div>
        </div>";
        if (preg_match("/[0-9a-z]+@[a-z]/", $email)) {
            if($mc->query("SELECT * FROM `users` WHERE `email` = '".$email."'")->fetch_array(MYSQLI_ASSOC)){
                mail($email, "MMOria", $htmlpismo, $headers);
                ?><script>showContent("/index.php?goEmail&email=<?= $email; ?>");</script><?php
            } else {
                message("Почта не привязана");
            }
        } else {
            message("Почта введена некорректно");
        }
    }
}

if (isset($_GET['goEmail']) && isset($_GET['email'])) {
    message("На указанный почтовый адрес отправлен код для восстановления персонажей");
    ?>
    <div class="auth-container animate__animated animate__fadeIn">
        <div class="auth-header">
            <h2>Проверка кода</h2>
        </div>
        <div class="auth-card">
            <div class="auth-content">
                <p>Введите код из полученного письма:</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="code" maxlength="50" placeholder="Код восстановления">
                    <button class="btn-primary" onclick="showContent('/index.php?gEmail&email=<?= $_GET['email']; ?>&code=' + $('#code').val())">
                        <span class="btn-text">Проверить</span>
                        <div class="btn-effect"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

$array = [];
if (isset($_GET['gEmail']) && isset($_GET['code']) && isset($_GET['email'])) {
    $CODES = $mc->query("SELECT * FROM `code_mail` WHERE `code` = '" . $_GET['code'] . "' AND `email` = '" . $_GET['email'] . "'")->fetch_array(MYSQLI_ASSOC);
    if (preg_match("/[0-9a-z]+@[a-z]/", $_GET['email'])) {
        if ((int) $_GET['code'] == (int) $CODES['code']) {
            if ($CODES['id_user'] == "*" || $CODES['id_user'] == 1) {
                $account = $mc->query("SELECT * FROM `users` WHERE `email` = '" . $CODES['email'] . "' OR `email` = '" . $_GET['email'] . "'")->fetch_all(MYSQLI_ASSOC);
                ?>
                <div class="auth-container animate__animated animate__fadeIn">
                    <div class="auth-header">
                        <h2>Выбор персонажа</h2>
                    </div>
                    <div class="auth-card">
                        <div class="auth-content">
                            <p>Выберите персонажа для восстановления доступа:</p>
                            <div class="character-list">
                                <?php
                                for ($i = 0; $i < count($account); $i++) {
                                    if ($mc->query("INSERT INTO `code_user` (`id_user`,`login`,`password`,`level`,`side`,`name`, `email`)VALUES('" . $account[$i]['id'] . "','" . $account[$i]['login'] . "','" . $account[$i]['password'] . "','" . $account[$i]['level'] . "','" . $account[$i]['side'] . "','" . $account[$i]['name'] . "', '". $_GET['email'] ."')")) {
                                        ?>
                                        <div class="character-item" onclick="showContent('index.php?gEmail=<?= $_GET['email']; ?>&code=<?= $_GET['code']; ?>&onclick&arr=<?= $account[$i]['id']; ?>')">
                                            <div class="character-avatar">
                                                <img src="images/avatar/<?= ($account[$i]['sex'] == 'male') ? 'male.gif' : 'female.jpg'; ?>" alt="Аватар">
                                            </div>
                                            <div class="character-info">
                                                <span class="character-name"><?= $account[$i]['name']; ?></span>
                                                <span class="character-level">Уровень: <?= $account[$i]['level']; ?></span>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            message("Неверный код подтверждения");
        }
    } else {
        message("Некорректный формат почты");
    }
}

if (isset($_GET['gEmail']) && isset($_GET['onclick']) && isset($_GET['code']) && isset($_GET['arr'])) {
    $CODES = $mc->query("SELECT COUNT(*) FROM `code_mail` WHERE  `code` = '" . $_GET['code'] . "' AND `email` = '" . $_GET['gEmail'] . "'")->fetch_array(MYSQLI_ASSOC);
    $acc = $mc->query("SELECT *, COUNT(*) FROM `code_user` WHERE `id_user` = '" . $_GET['arr'] . "' AND `email` = '". $_GET['gEmail'] ."'")->fetch_array(MYSQLI_ASSOC);

    if($CODES['COUNT(*)'] == 0 || $acc['COUNT(*)'] == 0) {
        $mc->query("DELETE FROM `code_user` WHERE `id_user` = '" . $_GET['arr'] . "'");
        $mc->query("DELETE FROM `code_mail` WHERE `email` = '" . $_GET['gEmail'] . "'");
        ?><script>showContent('index.php?')</script><?php
        exit(0);
    }

    $ii = rand(0, 50);
    $newid = $acc['id_user'] * $ii;
    ?>
    <div class="auth-container animate__animated animate__fadeIn">
        <div class="auth-header">
            <h2>Изменение пароля</h2>
        </div>
        <div class="auth-card">
            <div class="auth-content">
                <div class="character-selected">
                    <div class="character-avatar large">
                        <img src="images/avatar/<?= ($acc['sex'] == 'male') ? 'male.gif' : 'female.jpg'; ?>" alt="Аватар">
                    </div>
                    <div class="character-info">
                        <h3><?= $acc['name']; ?> <span class="level-badge">Ур. <?= $acc['level']; ?></span></h3>
                        <p>Логин: <strong><?= $acc['login']; ?></strong></p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="pass1">Новый пароль:</label>
                    <input type="password" class="form-control" id="pass1" maxlength="50">
                </div>
                <div class="form-group">
                    <label for="pass2">Повторите пароль:</label>
                    <input type="password" class="form-control" id="pass2" maxlength="50">
                </div>
                <div class="password-requirements">
                    <p>Требования к паролю:</p>
                    <ul>
                        <li id="req-length">Минимум 6 символов</li>
                        <li id="req-match">Пароли должны совпадать</li>
                    </ul>
                </div>
                <button class="btn-primary btn-large" id="changePasswordBtn" onclick="showContent('/index.php?data&id=<?= $newid; ?>&cl=<?= $ii; ?>&pass1=' + $('#pass1').val() + '&pass2=' + $('#pass2').val())">
                    <span class="btn-text">Сменить пароль</span>
                    <div class="btn-effect"></div>
                </button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function validatePassword() {
                const pass1 = $('#pass1').val();
                const pass2 = $('#pass2').val();
                
                // Проверка длины
                if (pass1.length >= 6) {
                    $('#req-length').addClass('valid');
                } else {
                    $('#req-length').removeClass('valid');
                }
                
                // Проверка совпадения
                if (pass1 && pass2 && pass1 === pass2) {
                    $('#req-match').addClass('valid');
                } else {
                    $('#req-match').removeClass('valid');
                }
                
                // Активация кнопки
                if (pass1.length >= 6 && pass1 === pass2) {
                    $('#changePasswordBtn').prop('disabled', false);
                } else {
                    $('#changePasswordBtn').prop('disabled', true);
                }
            }
            
            $('#pass1, #pass2').on('keyup', validatePassword);
            $('#changePasswordBtn').prop('disabled', true);
        });
    </script>
    <?php
}

if (isset($_GET['data']) && isset($_GET['id']) && isset($_GET['pass1']) && isset($_GET['pass2']) && isset($_GET['cl'])) {
    if ($_GET['pass1'] == $_GET['pass2']) {
        $uid = $_GET['id'] / $_GET['cl'];
        $pass00 = md5($_GET['pass1']);
        $count = $mc->query("SELECT COUNT(*) FROM `users` WHERE `id` = '" . $uid . "'")->fetch_array(MYSQLI_ASSOC);
        $accc = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $uid . "'")->fetch_array(MYSQLI_ASSOC);
        if ($count['COUNT(*)'] > 0) {
            if ($mc->query("UPDATE `users` SET `password` = '" . $pass00 . "' WHERE `id` = '" . $uid . "'")) {
                message("Пароль успешно изменен!");
                if ($mc->query("DELETE FROM `code_user` WHERE `id_user` = '" . $uid . "'")) {
                    $mc->query("DELETE FROM `code_mail` WHERE `email` = '" . $accc['email'] . "'");
                    ?>
                    <div class="auth-container animate__animated animate__fadeIn">
                        <div class="auth-card success-card">
                            <div class="auth-content text-center">
                                <div class="success-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3>Пароль успешно изменен!</h3>
                                <p>Теперь вы можете войти, используя новый пароль</p>
                                <button class="btn-primary" onclick="showContent('index.php')">
                                    <span class="btn-text">Вернуться на страницу входа</span>
                                    <div class="btn-effect"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                message("Произошла ошибка при изменении пароля");
                if ($mc->query("DELETE FROM `code_user` WHERE `id_user` = '" . $uid . "'")) {
                    $mc->query("DELETE FROM `code_mail` WHERE `email` = '" . $accc['email'] . "'");
                    ?><script>showContent('index.php')</script><?php
                }
            }
        }
    } else {
        message("Пароли не совпадают");
    }
}

if (isset($_GET['login']) && isset($_GET['password'])) {
    $login = urldecode($_GET['login']);
    $pass = $_GET['password'];
    //получить параметры героя 1 запись взять
    $result = $mc->query("SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '" . md5($pass) . "' ORDER BY `id` DESC LIMIT 1");
    if ($result->num_rows) {
        $resulddb = $result->fetch_array(MYSQLI_ASSOC);
        setcookie('login', urlencode($resulddb['login']), time() + 2592000, '/');
        setcookie('password', md5($pass), time() + 2592000, '/');
        ?>
        <script>
            // Добавить эффект перехода
            $('.login-container').addClass('animate__animated animate__fadeOut');
            setTimeout(function() {
                showContent("/main.php?initGame");
            }, 500);
        </script>
        <?php
        exit(0);
    } else if (empty($login)) {
        message('Введите логин');
    } else if (empty($pass)) {
        message('Введите пароль');
    } else {
        message('<div style="color: #ff5252;">Неверный логин или пароль</div>');
    }
}

if (isset($user) && !$user['id']) {
    if (isset($_GET['init'])) {
        message("Пройдите регистрацию или войдите в свой игровой аккаунт");
    }
}

// Главная страница авторизации (если не задействованы другие варианты)
if (!isset($_GET['gEmail']) && !isset($_GET['onclick']) && !isset($_GET['arr']) && !isset($_GET['cl']) && 
    !isset($_GET['vostanovlenie']) && !isset($_GET['email']) && !isset($_GET['goEmail'])) {
?>
    <!-- Фон с частицами -->
    <div id="particles-js"></div>
    
    <div class="login-container animate__animated animate__fadeIn">
        <div class="login-logo">
            <img src="images/logo2.png" alt="Логотип" class="animate__animated animate__pulse animate__infinite">
        </div>
        
        <div class="login-card">
            <div class="login-header">
                <h2>Вход в игру</h2>
            </div>
            
            <div class="login-form">
                <div class="form-group">
                    <label for="login">Логин:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="login" class="form-control" maxlength="50" placeholder="Введите ваш логин">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" class="form-control" maxlength="50" placeholder="Введите ваш пароль">
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button class="btn-primary login-btn" id="loginBtn">
                        <span class="btn-text">Войти в игру</span>
                        <div class="btn-effect"></div>
                    </button>
                    
                    <button class="btn-secondary register-btn" id="registerModalBtn">
                        <span class="btn-text">Регистрация</span>
                        <div class="btn-effect"></div>
                    </button>
                </div>
                
                <div class="forgot-password">
                    <a onclick="showContent('/index.php?vostanovlenie')">Забыли пароль?</a>
                </div>
            </div>
        </div>
        
        <div class="download-app">
            <a href="app.apk" download class="app-download-btn">
                <i class="fab fa-android"></i>
                <span>Скачать Android Приложение</span>
            </a>
        </div>
    </div>
    
    <!-- Модальное окно регистрации -->
    <div class="modal-overlay" id="registerModal">
        <div class="modal-container animate__animated animate__fadeInDown">
            <div class="modal-header">
                <h2>Регистрация</h2>
                <span class="close-modal" id="closeRegisterModal">&times;</span>
            </div>
            <div class="modal-content">
                <div class="auth-content">
                    <div class="form-group">
                        <label for="reg_names">Имя персонажа:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user-tag"></i>
                            <input type="text" id="reg_names" class="form-control" maxlength="50" placeholder="Введите имя персонажа">
                        </div>
                        <small class="form-hint">От 3 до 20 символов</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_login">Логин:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="reg_login" class="form-control" maxlength="50" placeholder="Введите логин для входа">
                        </div>
                        <small class="form-hint">От 3 до 20 символов</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_pass">Пароль:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="reg_pass" class="form-control" maxlength="50" placeholder="Придумайте пароль">
                            <span class="toggle-password" onclick="toggleRegPasswordVisibility('reg_pass')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <small class="form-hint">Только латинские буквы, цифры и символы</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_repass">Повторите пароль:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="reg_repass" class="form-control" maxlength="50" placeholder="Повторите пароль">
                            <span class="toggle-password" onclick="toggleRegPasswordVisibility('reg_repass')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_mail">E-mail:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="reg_mail" class="form-control" maxlength="50" placeholder="Введите почтовый ящик">
                        </div>
                        <small class="form-hint">Необходим для восстановления доступа</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_side">Выберите сторону:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-users"></i>
                            <select id="reg_side" class="form-control">
                                <option value="0">Шейванин</option>
                                <option value="1">Шейванка</option>
                                <option value="2">Нармасец</option>
                                <option value="3">Нармаска</option>
                            </select>
                        </div>
                        <small class="form-hint">Выберите сторону, за которую будете играть</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_ref">Реф-ссылка (если есть):</label>
                        <div class="input-with-icon">
                            <i class="fas fa-link"></i>
                            <input type="text" id="reg_ref" class="form-control" maxlength="50" placeholder="Реф-ссылка (необязательно)">
                        </div>
                        <small class="form-hint">Дает бонус при регистрации</small>
                    </div>
                    
                    <div class="form-actions">
                        <button class="btn-primary btn-large register-btn" id="submitRegisterBtn">
                            <span class="btn-text">Создать персонажа</span>
                            <div class="btn-effect"></div>
                        </button>
                    </div>
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
        });
        
        // Переключение видимости пароля
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.toggle-password i');
            
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
        
        // Обработка входа в игру
        $("#loginBtn").click(function() {
            // Добавляем анимацию кнопке при нажатии
            $(this).addClass('button-pressed');
            
            setTimeout(() => {
                showContent(
                    "?" +
                    "login=" + encodeURIComponent($("#login").val()) +
                    "&password=" + encodeURIComponent($("#password").val())
                );
                
                // Удаляем класс анимации
                $(this).removeClass('button-pressed');
            }, 300);
        });
        
        // Добавляем обработку клавиши Enter
        $("#login, #password").keypress(function(e) {
            if (e.which === 13) {
                $("#loginBtn").click();
            }
        });

        // Функция для модального окна регистрации
        $(document).ready(function() {
            // Открытие модального окна регистрации
            $("#registerModalBtn").click(function() {
                $("#registerModal").fadeIn(300);
                $("body").addClass("modal-open");
            });
            
            // Закрытие модального окна при клике на крестик
            $("#closeRegisterModal").click(function() {
                $("#registerModal").fadeOut(300);
                $("body").removeClass("modal-open");
            });
            
            // Закрытие модального окна при клике вне его области
            $(window).click(function(event) {
                if ($(event.target).is(".modal-overlay")) {
                    $(".modal-overlay").fadeOut(300);
                    $("body").removeClass("modal-open");
                }
            });
            
            // Обработка кнопки регистрации
            $("#submitRegisterBtn").click(function() {
                $(this).addClass('button-pressed');
                
                setTimeout(() => {
                    showContent(
                        "/registration.php?" +
                        "names=" + encodeURIComponent($("#reg_names").val()) +
                        "&login=" + encodeURIComponent($("#reg_login").val()) +
                        "&pass=" + encodeURIComponent($("#reg_pass").val()) +
                        "&repass=" + encodeURIComponent($("#reg_repass").val()) +
                        "&mail=" + encodeURIComponent($("#reg_mail").val()) +
                        "&side=" + encodeURIComponent($("#reg_side").val()) +
                        "&ref=" + encodeURIComponent($("#reg_ref").val())
                    );
                    
                    $(this).removeClass('button-pressed');
                }, 300);
            });
            
            // Обработка Enter в форме регистрации
            $("#registerModal input").keypress(function(e) {
                if (e.which === 13) {
                    $("#submitRegisterBtn").click();
                }
            });

            // Выбор расы в модальном окне регистрации
            $('#reg_side').val("0");
        });
        
        // Переключение видимости пароля в модальном окне
        function toggleRegPasswordVisibility(fieldId) {
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
    </script>
<?php
}

$footval = 'indexnone';
require_once ('system/foot/foot.php');
    