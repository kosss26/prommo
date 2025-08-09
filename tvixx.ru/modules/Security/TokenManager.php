<?php
/**
 * TokenManager - Система управления безопасностью и токенами
 * 
 * @version     1.0.0
 * @author      Команда разработчиков MMOria
 * @copyright   2023-2024 MMOria Game Studio
 */

class TokenManager {
    /**
     * Генерирует CSRF-токен и сохраняет его в сессии
     * 
     * @param string $formName Имя формы для идентификации токена
     * @return string Сгенерированный токен
     */
    public static function generateToken($formName = 'default') {
        // Запускаем сессию, если еще не запущена
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Генерируем случайный токен
        $token = bin2hex(random_bytes(32));
        
        // Сохраняем токен в сессии с привязкой к имени формы
        $_SESSION['csrf_tokens'][$formName] = [
            'token' => $token,
            'expires' => time() + 7200 // 2 часа
        ];
        
        return $token;
    }
    
    /**
     * Проверяет валидность CSRF-токена
     * 
     * @param string $token Токен для проверки
     * @param string $formName Имя формы для идентификации токена
     * @return bool Результат проверки
     */
    public static function validateToken($token, $formName = 'default') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Если токен не существует или срок действия истек
        if (!isset($_SESSION['csrf_tokens'][$formName]) || 
            $_SESSION['csrf_tokens'][$formName]['token'] !== $token ||
            $_SESSION['csrf_tokens'][$formName]['expires'] < time()) {
            return false;
        }
        
        // Если используется одноразовый токен, удаляем его после проверки
        // Для чата оставляем токен активным
        if ($formName !== 'chat_form') {
            unset($_SESSION['csrf_tokens'][$formName]);
        }
        
        return true;
    }
    
    /**
     * Очищает все устаревшие токены из сессии
     */
    public static function cleanupExpiredTokens() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_tokens'])) {
            return;
        }
        
        $now = time();
        foreach ($_SESSION['csrf_tokens'] as $formName => $tokenData) {
            if ($tokenData['expires'] < $now) {
                unset($_SESSION['csrf_tokens'][$formName]);
            }
        }
    }
    
    /**
     * Создает хеш пароля с использованием современных алгоритмов
     * 
     * @param string $password Исходный пароль
     * @return string Хешированный пароль
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 1024,
            'time_cost' => 2,
            'threads' => 2
        ]);
    }
    
    /**
     * Проверяет пароль с защитой от timing-атак
     * 
     * @param string $password Пароль для проверки
     * @param string $hash Хеш пароля из базы данных
     * @return bool Результат проверки
     */
    public static function verifyPassword($password, $hash) {
        // Для обратной совместимости со старыми MD5 хешами
        if (strlen($hash) === 32) {
            return hash_equals(md5($password), $hash);
        }
        
        return password_verify($password, $hash);
    }
    
    /**
     * Генерирует защищенный от атак токен для восстановления пароля
     * 
     * @param int $userId ID пользователя
     * @return string Сгенерированный токен
     */
    public static function generateRecoveryToken($userId) {
        // Комбинируем ID пользователя с случайными байтами и текущим временем
        $random = bin2hex(random_bytes(16));
        $timestamp = time();
        $data = $userId . '|' . $random . '|' . $timestamp;
        
        // Создаем HMAC для проверки целостности
        $signature = hash_hmac('sha256', $data, getenv('APP_SECRET') ?: 'mobitva2secret');
        
        // Кодируем для безопасной передачи
        return base64_encode($data . '|' . $signature);
    }
    
    /**
     * Проверяет и извлекает информацию из токена восстановления
     * 
     * @param string $token Токен для проверки
     * @param int $expiration Срок действия токена в секундах (по умолчанию 24 часа)
     * @return array|false Массив с данными или false в случае ошибки
     */
    public static function validateRecoveryToken($token, $expiration = 86400) {
        try {
            // Декодируем токен
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);
            
            if (count($parts) !== 4) {
                return false;
            }
            
            [$userId, $random, $timestamp, $signature] = $parts;
            
            // Проверяем HMAC
            $data = $userId . '|' . $random . '|' . $timestamp;
            $expectedSignature = hash_hmac('sha256', $data, getenv('APP_SECRET') ?: 'mobitva2secret');
            
            if (!hash_equals($expectedSignature, $signature)) {
                return false;
            }
            
            // Проверяем срок действия
            if ((int)$timestamp + $expiration < time()) {
                return false;
            }
            
            return [
                'user_id' => (int)$userId,
                'timestamp' => (int)$timestamp
            ];
        } catch (Exception $e) {
            error_log('Recovery token validation error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Очищает все ввод от потенциально опасных элементов
     * 
     * @param string $input Строка для очистки
     * @return string Очищенная строка
     */
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Генерирует безопасную строку для использования в SQL запросах
     * 
     * @param string $input Входная строка
     * @param mysqli $connection Объект соединения с базой данных
     * @return string Экранированная строка
     */
    public static function prepareForDatabase($input, $connection) {
        return $connection->real_escape_string(trim($input));
    }
} 