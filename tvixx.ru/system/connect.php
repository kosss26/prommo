<?php
// Проверяем, не были ли отправлены заголовки
if (!headers_sent()) {
    // Очищаем буфер если есть
    if (ob_get_level()) {
        ob_clean();
    }
    
    // Запускаем сессию и буферизацию
    ob_start();
    session_start();
}

ignore_user_abort(true);

$par1 = "localhost";
$par2 = "u2992855_kosoy"; //login
$par3 = "01061981AAa."; //pass
$par4 = "u2992855_game"; //db

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    // Проверяем существует ли уже подключение
    if (!isset($mc) || !($mc instanceof mysqli)) {
        $mc = new mysqli($par1, $par2, $par3, $par4);
        
        if ($mc->connect_error) {
            error_log("Database connection failed: " . $mc->connect_error);
            exit();
        }
        
        $mc->set_charset("utf8mb4");
        if (!$mc->select_db($par4)) {
            throw new Exception('База данных не найдена');
        }
    }

    // Проверяем авторизацию только если пользователь еще не загружен
    if (!isset($user) && isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
        $login = $mc->real_escape_string(urldecode($_COOKIE['login']));
        $password = $mc->real_escape_string($_COOKIE['password']);
        
        $stmt = $mc->prepare("SELECT * FROM `users` WHERE `login` = ? AND `password` = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('ss', $login, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_array(MYSQLI_ASSOC);
            $stmt->close();
        }
    }

} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    if (!headers_sent()) {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
    }
    die('Временные технические проблемы. Попробуйте позже.');
}
