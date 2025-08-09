<?php
curl_setopt_array($ch = curl_init(), array(
CURLOPT_URL => "https://pusha11.ru/api.php",
CURLOPT_POSTFIELDS => array(
           "type" => "self",
               "id" => "1",
              "key" => "79e8353d22df8a74a1dab59317a6a73d",
              "text" => "какойто текст",
              "title" => "мобитва 2"
),
    CURLOPT_RETURNTRANSFER => true
));
$return=curl_exec($ch); //�������� �����
curl_close($ch);
?>