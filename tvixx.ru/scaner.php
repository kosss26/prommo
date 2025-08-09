<?php
$allfiles = [];

function listdir($dir = '.') {
    if (!is_dir($dir)) {
        return false;
    }
    $files = array();
    listdiraux($dir, $files);
    return $files;
}

function listdiraux($dir, &$files) {
    global $allfiles;
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file == '.' 
                || $file == '..' 
                || $file == 'nbproject' 
                || $file == 'scaner.php'
                || $file == 'animator_Niko'
                || $file == 'animator'
                || $file == 'quests'
                || $file == 'cron'
                || preg_match('/GOL_loc(.*)\.png/', $file) == 1
                || $file == 'animator'
                || $file == 'animator'
                || $file == 'animator'
                ) {
            continue;
        }
        $filepath = $dir == '.' ? $file : $dir . '/' . $file;
        if (is_link($filepath)) {
            continue;
        }
        if (is_file($filepath)) {
            $allfiles[] = addcslashes($file, ".-");
            $files[] = $filepath;
        } else if (is_dir($filepath)) {
            listdiraux($filepath, $files);
        }
    }
    closedir($handle);
}

$files = listdir('.');
if (count($files) == count($allfiles)) {
    $no="";
    $yes="";
    for ($i = 0; $i < count($allfiles); $i++) {
        $op=0;
        for ($i1 = 0; $i1 < count($files); $i1++) {
                if (preg_match('/'.$allfiles[$i].'/iu',file_get_contents($files[$i1]), $match)===1) {
                    $yes .= $files[$i] .' в ' . $files[$i1] . "<br>";
                    $op=1;
                } else if ($i1 == count($files)-1
                        &&$op==0
                        &&$files[$i]!='index.php'
                        &&$files[$i]!='index.html'
                        &&$files[$i]!='.htaccess'
                        ) {
                    $no .= $files[$i] . "<br>";
                }
        }
    }
    ?>
<div style="text-align: center">НЕ НАЙДЕН</div>
<?=$no;?>
<div style="text-align: center">НАЙДЕН</div>    
<?=$yes;?>
        <?php
} else {
    echo "количество файлов и путей не совпадает процесс завершен";
}
?>
