<?php
function moneyplus($zolo, $serebro, $med) {
///для админов
    $resultmoney = (((($zolo * 100) + $serebro) * 100) + $med);
    return $resultmoney;
}
?>