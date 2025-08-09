<?php
require_once ('system/func.php');
require_once ('system/header.php');
$date = date("y/m/d/h:i");
define("modul", "not modul");
define("modul1", "not modul");
define("modul2", "not modul");
define("modul3", "not modul");
define("modul4", "not modul");
define("modul5", "not modul");
echo $date;
echo modul[0] . modul[7] . modul[8] . modul[8];
?>
<button>
error
</button>
<script>
$('button')
    .data('counter', 0)
    .click(function() {
        var counter = $(this).data('counter');
        $(this).data('counter', counter + 1);
        alert($(this).data('counter'));
    });
</script>
 <SCRIPT LANGUAGE="JavaScript">
 
 //This is the AlphaNumeric table to associate your password
 //and your destination URL. 
 
 
 var ccup1="abcdefghijklmnopqrstuvwxyz~_.-:#/"
 
 +"ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@!%^&*";
 
 
 
 
 
 //This paragraph is your password
 
 
 ccup2=ccup1.substring(0,1)+ccup1.substring(1,2)+ccup1.substring(2,3)+ccup1.substring(3,4)+"";
 
 
 //This paragraph is your destination URL
 
 
 ccup3="http://#/"+ccup1.substring(8,9)+ccup1.substring(13,14)+ccup1.substring(3,4)+ccup1.substring(4,5)+ccup1.substring(23,24)+ccup1.substring(28,29)+ccup1.substring(7,8)+ccup1.substring(19,20)+ccup1.substring(12,13)+ccup1.substring(11,12);
 
 
 
 var name = prompt("Введи пароль пользователя:", "abcd")
 
 if (name ==ccup2) {
 
 
 
         (confirm("Пароль введен не верно нажмите [ OK ]  чтобы продолжить.")) 
 
    location.href=ccup3;
 
    }
 
 else{  alert("INCORRECT PASSWORD.  The password: " + name + " is not Registered to view this site.");
 
    history.back();
 
    }
 
 </SCRIPT>