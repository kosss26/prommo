<? php 

session_start (); 
$ сессия = session_id (); 
$ время = время (); 
$ time_check = $ времени 600; // УСТАНОВИТЬ ВРЕМЯ 10 минут

$ хост = "локальный"; // Имя узла
$ username = "o74786j9_11"; // Mysql username
$ password = "q112233"; // Mysql password
$ db_name = "o74786j9_11"; // Имя базы данных
$ tbl_name = "user_online"; // Имя таблицы

// Подключитесь к серверу и выберите databse
mysql_connect («$ host», «$ username», «$ password») или die («невозможно подключиться к серверу»); 
mysql_select_db («$ db_name») или die («не может выбрать DB»);
$ sql = "SELECT * FROM $ tbl_name WHERE session = '$ session'"; 
$ результат = mysql_query ($ SQL);

$ Count = mysql_num_rows ($ результат);
if ($ count == "0") { 

$ sql1 = "INSERT INTO $ tbl_name (session, time) VALUES ('$ session', '$ time')"; 
$ результат1 = mysql_query ($ SQL1); 
} 

else { 
"$ sql2 = UPDATE $ tbl_name SET time = '$ time' WHERE session = '$ session'"; 
$ результат2 = mysql_query ($ SQL2); 
}

$ sql3 = "SELECT * FROM $ tbl_name"; 
$ result3 = mysql_query ($ SQL3);
$ Count_user_online = mysql_num_rows ($ result3);
echo "Пользователь онлайн: $ count_user_online";

// если более 10 минут, удалите сеанс
$ sql4 = "DELETE FROM $ tbl_name WHERE time <$ time_check"; 
$ result4 = mysql_query ($ sql4);

// Открытие нескольких страниц браузера для результата 


// Закрытие соединения
mysql_close (); 
?>
