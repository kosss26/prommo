<?php  
chdir();
$imgage_path = "1.jpg"; //Путь к изображению
$text = "Началось!"; //Забираем тест, который мы написали
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
<?php
///Вадим
// VK API - Пример скрипра рандомной смены обложки группы на php и cron
// Нужен ТОКЕН ГРУППЫ СО ВСЕМИ ПРАВАМИ!
$token = "df853d5d40b848717ef95c24f088a824f1965a9da73b7fb324caf9383d2f92a9e7749c2b6df4733d7e131";
$grid = "162798265";
$cover_path = dirname(__FILE__).'/vktime.jpg';
$post_data = array('photo' => new CURLFile($cover_path, 'image/jpeg', 'image0'));
// рандомно выбирается фотография 1,2 или 3
$upload_url = file_get_contents("https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer?group_id=".$grid."&crop_x2=1590&access_token=".$token."&v=5.80");

$url = json_decode($upload_url)->response->upload_url;
//echo $url;
// урл для загрузки фото получен
// фото отправлено
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$result = json_decode(curl_exec($ch),true);
//echo '<pre>';
print_r($result);
// сохраняем фото
$safe = file_get_contents("https://api.vk.com/method/photos.saveOwnerCoverPhoto?hash=".$result['hash']."&photo=".$result['photo']."&access_token=".$token."&v=5.80");
echo '<pre>';
print_r($safe);
?>

<?php
//Женя
// VK API - Пример скрипра рандомной смены обложки группы на php и cron
// Нужен ТОКЕН ГРУППЫ СО ВСЕМИ ПРАВАМИ!
$token1 = "9b4252a63d34c70ba9533b87c92f0f17285dfce8b15799d1232c001493b34c4c538dbcdb8ef2cfe892f27";
$grid1 = "58156554";
$upload_url1 = file_get_contents("https://api.vk.com/method/photos.getOwnerCoverPhotoUploadServer?group_id=".$grid1."&crop_x2=1590&access_token=".$token1."&v=5.80");
$url1 = json_decode($upload_url1)->response->upload_url;
//echo $url;
// урл для загрузки фото получен
// фото отправлено
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url1);
curl_setopt($ch1, CURLOPT_POST, true);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_data);
$result1 = json_decode(curl_exec($ch1),true);
//echo '<pre>';
print_r($result1);
// сохраняем фото
$safe1 = file_get_contents("https://api.vk.com/method/photos.saveOwnerCoverPhoto?hash=".$result1['hash']."&photo=".$result1['photo']."&access_token=".$token1."&v=5.80");
echo '<pre>';
print_r($safe1);
?>

