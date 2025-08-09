<?php 
$time = array(
 "PHP"=>"14.30",
 "LISP"=>"12.00",
 "C++"=>"15.00",
 "UNIX"=>"14.00");
 
$lector= array(
 "PHP"=>"Васильев",
 "LISP"=>"Иванов",
 "C++"=>"Петров",
 "UNIX"=>"Сидоров");
 
define("SIGN", "С уважением, администрация");
define("MEETING_TIME", "18.00");

$date="12 мая"; 
$str = "Здравствуйте, уважаемый {$_POST['first_name']} {$_POST['last_name']} ! \n "; 
$str .= "<br> Сообщаем Вам, что "; 
$lect =""; 
$kurses= $_POST['kurs']; 
      if(!isset($kurses)) { 
         $event= "следующее собрание студентов"; 
         $str .= "{$event} состоится {$date} ".MEETING_TIME." \n"; 
      }else{
 $event= "выбранная Вами лекция состоится {$date} <ul>"; 
 for($i=0; $i < count($kurses); $i++){ 
     $k = $kurses[$i]; 
     $lect = " {$lect} <li> лекция по {$k} в {$time[$k]} "; 
     $lect .= "ваш лектор {$lector[$k]}";
   }
$event = " {$event} {$lect} </ul>"; 
$str .=" {$event}"; 
$str .= "<br> ".SIGN;
 } 
echo $str; 
?>