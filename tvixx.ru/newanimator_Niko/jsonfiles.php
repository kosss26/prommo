<?php
header('Content-type: text/html; charset=utf-8');
if (isset($_POST['save'])) {
    $result = "Ошибка сохранения";
    $file = "code/" . $_POST['save'];
    $current = $_POST['code']; ///КОД
    if (file_put_contents($file, $current)) {
        $result = "Сохранил";
    } else {

        mkdir("code", 0700);
        if (file_put_contents($file, $current)) {
            $result = "Сохранил";
        } else {
            $result = "Ошибка";
        }
    }

    echo $result;
}

if (isset($_POST['open'])) {
    $file = "code/" . $_POST['open'];
    $current = file_get_contents($file);
    echo $current;
}

if (isset($_POST['files'])) {
    $dir = 'code';
    $files = scandir($dir);

    for ($i = 2; $i < count($files); $i++) {
        echo "<input type='button' value='" . $files[$i] . "' onclick=DownloadJson('" . $files[$i] . "');><br>";
    }
}
?>