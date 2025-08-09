<?php
	
	require_once ('system/func.php');
	require_once ('system/header.php');

	auth(); // Закроем от неавторизированых
	notModer(); // Закроем от тех кто не модератор

?>

	<h1>Правила Модератора</h1>
	<ol>
		<li>1</li>
		<li>2</li>
		<li>3</li>
		<li>4</li>
		<li>5</li>
	</ol>

	<a href="/main.php">На главную</a>