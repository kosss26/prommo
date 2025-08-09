<?php
// Устанавливаем тип содержимого как изображение PNG
header('Content-Type: image/png');

// Создаем изображение размером 32x32
$image = imagecreatetruecolor(32, 32);

// Определяем цвета
$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
$brown = imagecolorallocate($image, 160, 100, 50); // Коричневый для пещеры
$darkBrown = imagecolorallocate($image, 120, 70, 30); // Темно-коричневый для деталей
$black = imagecolorallocate($image, 0, 0, 0); // Черный для контура

// Делаем фон прозрачным
imagefill($image, 0, 0, $transparent);

// Рисуем пещеру (арку)
imagefilledarc($image, 16, 16, 28, 28, 0, 180, $brown, IMG_ARC_PIE);

// Рисуем вход в пещеру
imagefilledarc($image, 16, 16, 20, 20, 0, 180, $darkBrown, IMG_ARC_PIE);

// Рисуем контур
imagearc($image, 16, 16, 28, 28, 0, 180, $black);
imagearc($image, 16, 16, 20, 20, 0, 180, $black);

// Сохраняем прозрачность
imagesavealpha($image, true);

// Выводим изображение
imagepng($image);

// Освобождаем память
imagedestroy($image); 