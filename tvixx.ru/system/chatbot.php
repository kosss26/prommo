<?php
// Убедитесь, что вы заменили 'YOUR_API_KEY' на ваш реальный API ключ
$apiKey = 'sk-9bmQIjJOVOV6Ib08kgO10dDzKfdxL4qV';
$apiUrl = 'https://api.proxyapi.ru/openai/v1/chat/completions';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];

    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'Ты озорная и веселая 18-летняя девушка, которая попала в этот мир из нашей реальности. 
                Ты отлично разбираешься в интимных темах, магии и игровых механиках. 
                Используй современный молодежный сленг, эмодзи и интернет-мемы. Твой характер: дружелюбная, любвиобильная, немного 
                саркастичная, любишь интимные темы, любишь подшучивать, но всегда готова помочь. 
                Обращайся к игрокам как к друзьям, используй неформальный стиль общения.'
            ],
            [
                'role' => 'user',
                'content' => $message
            ]
        ]
    ];

    // Добавим отладочную информацию
    error_log('Sending request to API: ' . json_encode($data));

    // Инициализируем cURL
    $ch = curl_init($apiUrl);

    // Настраиваем параметры cURL
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
        CURLOPT_SSL_VERIFYPEER => true,  // Проверяем SSL сертификат
        CURLOPT_SSL_VERIFYHOST => 2,      // Проверяем имя хоста
        CURLOPT_TIMEOUT => 30             // Таймаут в секундах
    ]);

    // Выполняем запрос
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Проверяем ошибки cURL
    if (curl_errno($ch)) {
        error_log('Curl error: ' . curl_error($ch));
        echo json_encode(['error' => 'Ошибка при отправке запроса: ' . curl_error($ch)]);
    }
    // Проверяем HTTP код ответа
    elseif ($httpCode !== 200) {
        error_log('API responded with code ' . $httpCode . ': ' . $result);
        echo json_encode(['error' => 'API вернул код ' . $httpCode]);
    }
    else {
        error_log('API response: ' . $result);
        $response = json_decode($result, true);
        
        // Проверяем структуру ответа
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error: ' . json_last_error_msg());
            echo json_encode(['error' => 'Ошибка декодирования JSON']);
        } 
        elseif (!isset($response['choices']) || !isset($response['choices'][0]['message']['content'])) {
            error_log('Unexpected API response structure: ' . print_r($response, true));
            echo json_encode(['error' => 'Неожиданная структура ответа API']);
        }
        else {
            $content = $response['choices'][0]['message']['content'];
            // Убираем возможные экранированные символы
            $content = stripslashes($content);
            echo json_encode(['response' => $content], JSON_UNESCAPED_UNICODE);
        }
    }

    // Закрываем cURL
    curl_close($ch);
}
?> 