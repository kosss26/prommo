<?php
require_once '../system/func.php';
require_once '../ai/GameBot.php';

if (!isset($user)) {
    die(json_encode(['error' => 'Unauthorized']));
}

$apiKey = 'your-gptj-api-key';
$bot = new GameBot($mc, $apiKey);

if (isset($_POST['message'])) {
    $response = $bot->handleMessage($user['id'], $_POST['message']);
    echo json_encode([
        'response' => $response,
        'timestamp' => time()
    ]);
} 