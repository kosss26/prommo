<?php

// шаблонное изображение

$anim = new GifCreator\AnimGif();
file_put_contents("/img/sheivan1.gif", $anim);
 header("Content-type: image/gif"); 
echo $gif; 