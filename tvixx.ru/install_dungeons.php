<?php
/**
 * Скрипт-обертка для установки системы подземелий
 */

// Проверяем наличие файла установки
if (file_exists('dungeons/install_dungeons.php')) {
    // Перенаправляем на файл установки
    header('Location: dungeons/install_dungeons.php');
    exit;
} else {
    die("Файл установки не найден. Проверьте наличие директории 'dungeons' и файла 'install_dungeons.php' в ней.");
} 