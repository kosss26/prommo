<?php
require_once ('system/func.php');
require_once ('system/header.php');
?>
<html>
    <head>
        <title>test</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<center><a><h3>-Покупка Платины-</h3></a></center>
<form action="" method="post" >
<center>
    <select name="country">
<option value="стоп">-Не Выбрано-</option>
<option value="Казахстан">Казахстан</option>
<option value="Россия">Россия</option>
<option value="Украина">Украина</option>
<option value="Кыргызстан">Кыргызстан</option>
</select>
<input type="submit" value="Выбрать Страну" class="button" >
</center>
</form>

<?php
   $country = $_POST["country"];
if($country == "Казахстан") {
message("Для данной страны нет платежных систем");
}else{
    message("vse zbs");
}
?>
</body>
</html>
