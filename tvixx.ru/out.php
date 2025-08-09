<?php
// установка даты истечения срока действия на час назад
setcookie("login", "", time() - 86400*31);
setcookie("password", "", time() - 86400*31);
echo("<meta http-equiv='refresh' content='1'>");
?>

