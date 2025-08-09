<?php
function money($money, $result) {
    $med = $money % 100; ///медь
    $serebro = ($money - $med) / 100 % 100;
    $zoloto = floor(((($money - $med) / 100) - $serebro) / 100);

////вывод $med $serebro $zoloto
    if ($result == 'med') {
        return $med;
    }
    if ($result == 'serebro') {
        return $serebro;
    }
    if ($result == 'zoloto') {
        return $zoloto;
    }
}
?>