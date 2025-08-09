<?php  
$imgage_path = "1.jpg"; //Путь к изображению
$text = gmdate("H:i:s", strtotime("15 August 2018 12:00:00")-time()); //Забираем тест, который мы написали
$img = imagecreatefromjpeg($imgage_path); // создаём новое изображение из файла
$font = "ofont.ru_Cleanvertising.ttf"; // путь к шрифту
$font_size = 58; // размер шрифта
$color = imageColorAllocate($img, 255, 255, 255); //Цвет шрифта
$size=getimagesize($imgage_path); //Узнаем размер изображения
$w=(int)$size[0]; // ширина
$h=(int)$size[1]; // высота
// текст по центру 
$box = imagettfbbox($font_size, 0, $font, $text);
$x = 1153; //по оси x
$y = 216; //по оси y
//Разметка самого текста
imagettftext($img, $font_size, 0, $x, $y, $color, $font, $text);
imagejpeg($img, "vktime.jpg");


imagedestroy($img);
?>