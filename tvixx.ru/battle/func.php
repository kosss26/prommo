<?php
require_once '../system/func.php';

error_log("Battle func.php started");
error_log("POST data: " . print_r($_POST, true));

header('Content-Type: application/json');

// Проверяем авторизацию
if (!isset($user['id'])) {
    error_log("Unauthorized access attempt");
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Получаем параметры
$battle_id = isset($_POST['battle_id']) ? $mc->real_escape_string($_POST['battle_id']) : '';
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$access_key = isset($_POST['access_key']) ? $mc->real_escape_string($_POST['access_key']) : '';
$is_start = isset($_POST['start']) && $_POST['start'] === 'true';
$is_attack = isset($_POST['udar']) && $_POST['udar'] === 'true';
$attack_type = isset($_POST['set']) ? (int)$_POST['set'] : 0;

error_log("Battle ID: $battle_id, User ID: $user_id");

// Проверяем существование боя
$battle = $mc->query("SELECT * FROM `battle` WHERE `battle_id` = '$battle_id' ORDER BY `id` ASC")->fetch_all(MYSQLI_ASSOC);

if (!$battle || count($battle) < 2) {
    error_log("Battle not found or incomplete: $battle_id");
    echo json_encode(['success' => false, 'error' => 'Battle not found']);
    exit;
} 