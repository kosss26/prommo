<?php
require_once ('../system/func.php');
require_once ('../system/header.php');
$footval='huntbattle';
require_once ('../system/foot//foot.php');

auth(); // Закроем от неавторизированых
requestModer(); // Закроем для тех у кого есть запрос на модератора

$user = $_GET['id'];
$users = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id`='$user'"));
$mobloc = $mob['id_loc'];
$location = mysql_fetch_array(mysql_query("SELECT * FROM `location` WHERE `id`='$loca'"));
///////АНТИЧИТ

$battle_search = mysql_fetch_array(mysql_query("SELECT COUNT(1),`enemy_id` FROM `battle` WHERE `user_id`='" . $user["id"] . "' AND `pocinul`=0"));
if ($battle_search['COUNT(1)'] == 0) {
    if ($mob['id_loc'] != $user["location"]) {
 
    }
    mysql_fetch_array(mysql_query("INSERT INTO `battle`(`user_id`, `enemy_id`, `user_hp`, `enemy_hp`) VALUES ('" . $user["id"] . "','$mobid','" . $user["health"] . "','" . $mob["hp"] . "')"));
} else {
    if ($battle_search['enemy_id'] != $mobid) {

    }
}
echo '

<style>
    .my_hp{position: absolute; top: 40px; left: 30px; background-repeat: no-repeat;height: 22px;width: 27px;background-size: contain; background-image: url("/images/icons/hp.png");}
    .enemy_hp{position: absolute; top: 40px; right: 30px; background-repeat: no-repeat;height: 22px;width: 27px;background-size: contain; background-image: url("/images/icons/hp.png");}

    .numcolor{position: absolute;height: 22px;width: 27px;font-size: 1.3em; color: #FF6600;font-weight: bold;  text-shadow: #4B2601 0 0 3px;}
    .mynumhp{top: -2; left: 25px;}
    .enemynumhp{top: -2; right: 26px;text-align: right; width: 200px;}

    .g1{display:inline-block;  background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/g1.png");}
    .g2{display:inline-block; background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/g2.png");}
    .g3{display:inline-block; background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/g3.png");}
    .y1{display:inline-block;  background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/y1.png");}
    .y2{display:inline-block; background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/y2.png");}
    .y3{display:inline-block; background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/y3.png");}
    .r1{display:inline-block;  background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/r1.png");}
    .r2{display:inline-block; background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/r2.png");}
    .r3{display:inline-block; background-repeat: no-repeat;background-size: contain;height: 96px;width: 77px;background-image: url("/img/button/r3.png");}

    .myhpdown{z-index:2;position:absolute;left:60px;top:60px;width: 100px;}
    .enemyhpdown{z-index:2;position:absolute;right:15px;top:60px;width: 100px;text-align: right;}

    .myname{position: absolute; top: 0px; left: 27px; background-repeat: no-repeat;height: 27px;width: 196px;background-size: contain; background-image: url("/img/location/GOL_app_namebox.png");}
    .enemyname{position: absolute; top: 180px; right: 27px; background-repeat: no-repeat;height: 27px;width: 196px;background-size: contain; background-image: url("/img/location/GOL_app_namebox.png");}
    .nameclass{position: absolute;top: 3; text-align: center;width: 196px;}

    .otrazit{transform: scale(-1, 1);}
    .normas{ background-repeat: no-repeat; animation-iteration-count: infinite; background-size: contain;height: 110px;width: 90px;background-image: url("/img/norm.gif");}
    .sheivan{ background-repeat: no-repeat; animation-iteration-count: infinite; background-size: contain;height: 110px;width: 90px;background-image: url("/img/sheivan.gif");}
    .repei{ background-repeat: no-repeat; animation-iteration-count: infinite; background-size: contain;height: 110px;width: 90px;background-image: url("/img/repei.gif");}
    .bandit{ background-repeat: no-repeat; animation-iteration-count: infinite; background-size: contain;height: 110px;width: 90px;background-image: url("/img/bandit.gif");}
    .kykla{ background-repeat: no-repeat; animation-iteration-count: infinite; background-size: contain;height: 110px;width: 90px;background-image: url("/img/kykla.gif");}

    .normasup{ background-repeat: no-repeat;  background-size: contain;height: 120px;width: 230px;background-image: url("/img/normup.gif");}
    .normascenter{ background-repeat: no-repeat; background-size: contain;height: 120px;width: 230px;background-image: url("/img/normcenter.gif");}
    .normasdown{ background-repeat: no-repeat;  background-size: contain;height: 120px;width: 230px;background-image: url("/img/normdown.gif");}
.number{
font-size: 14px;
}
    .mypos{position:absolute;left:-60px;top:-10px;}
    .myposudar{position:absolute;left:30px;top:60px;}
    .mobpos{position:absolute;right:30px;top:75px;}
</style>
';
?>
<body><center>
    <div class="location<?php echo $location['IdImage']; ?>">
<?php
       echo ' <div class="location">
            <div class="my_hp"><div id="myhp" class="numcolor mynumhp"><number>0</number></div></div>';
            echo '<div class="enemy_hp"><div id="enemyhp" align="right" class="numcolor enemynumhp">0</div></div>
            <div class="myhpdown numcolor" id="myhpdown">259</div>
            <div class="enemyhpdown numcolor " align="right" id="enemyhpdown">259</div>
        </div>
        <div class="myname"><div class="nameclass" id="myname">admin</div></div>
        <div class="enemyname" ><div id="enemyname" class="nameclass">should</div></div>
';
        if ($user['side'] >1) { 
            echo "<img id='images' src='/img/norm.gif'><div class='normas mypos'></div></div>";
         } else {
           echo " <div class='mypos'></div>";
         }if ($mob['iconid'] == '2') { 
            echo "<div class='mobpos repei'></div>";
         }if ($mob['iconid'] == '6') { 
            echo "<div class='mobpos bandit'></div>";
         } 
    echo '</div>
    <div class="g3" id="button3" onclick="onclickButtonup()"></div>
    <div class="g1" id ="button1" onclick="onclickButtoncenter()"></div>
    <div class="g2" id="button2" onclick="onclickButtondown()"></div>
</center>';



?>
<script>
    var button = 0;
    var myhp = '';
    var enemyhp = '';
    var enemyhpdown = '';
    var myhpdown = '';
    var rassa = '';
    buttonfunc();
    start();
    function buttonfunc() {
        if (button === 1) {
            document.getElementById('button3').className = 'g3';
            document.getElementById('button1').className = 'g1';
            document.getElementById('button2').className = 'g2';
        }
        if (button === 10) {
            document.getElementById('button3').className = 'y3';
            document.getElementById('button1').className = 'y1';
            document.getElementById('button2').className = 'y2';
        }
        if (button === 20) {
            document.getElementById('button1').className = 'r1';
            document.getElementById('button2').className = 'r2';
            document.getElementById('button3').className = 'r3';
        }

        if (button === 30) {
            document.getElementById('button1').style.display = 'none';
            document.getElementById('button2').style.display = 'none';
            document.getElementById('button3').style.display = 'none';
            udar(getRandomInRange(1, 3));
        }
        if (button === 31) {
            document.getElementById('button1').style.display = 'none';
            document.getElementById('button2').style.display = 'none';
            document.getElementById('button3').style.display = 'none';
        }
        button = button + 1;
        setTimeout('buttonfunc()', 1000);
    }

    function successurl() {
        document.getElementById('button1').style.display = 'inline-block';
        document.getElementById('button2').style.display = 'inline-block';
        document.getElementById('button3').style.display = 'inline-block';
        document.getElementById('myhp').innerHTML = myhp;
        document.getElementById('myhpdown').innerHTML = '<marquee scrollamount=2 height=50; loop=1 direction=up>' + myhpdown + '</marquee>';
        button = ;
    }


    function animback() {
        if (rassa === 'normas') {
            document.getElementById('normas').innerHTML = '<div class="normas mypos"></div>';
        }
        if (rassa === 'sheivan') {
            document.getElementById('sheivan').innerHTML = '<div class="sheivan mypos"></div>';
        }
    }

    function getRandomInRange(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function onclickButtonup()
    {
        document.getElementById('button1').style.display = 'none';
        document.getElementById('button2').style.display = 'none';
        document.getElementById('button3').style.display = 'none';
        button = 31;
        udar('1');
    }
    function onclickButtondown()
    {
        document.getElementById('button1').style.display = 'none';
        document.getElementById('button2').style.display = 'none';
        document.getElementById('button3').style.display = 'none';
        button = 31;
        udar('2');
    }
    function onclickButtoncenter()
    {
        document.getElementById('button1').style.display = 'none';
        document.getElementById('button2').style.display = 'none';
        document.getElementById('button3').style.display = 'none';
        button = 31;
        udar('3');
    }

    function udar(set) {
        $.ajax({
            url: '/hunt/func.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'start': 'false',
                'udar': 'true',
                'set': set,
                'access_key': '<?php echo md5(md5(md5($battlekey['id'] * 5))); ?>'},
            cache: false,
            error: function () {
                setTimeout('udar(getRandomInRange(1, 3))', 5000);
            },
            success: function (data) {

                if (data.myhp <= 0 || data.enemyhp <= 0){
                    showContent('/hunt/result');
                }

                myhp = data.myhp;
                enemyhp = data.enemyhp;
                enemyhpdown = data.enemyhpdown;
                myhpdown = data.myhpdown;
                rassa = data.ns;
                document.getElementById('enemyhp').innerHTML = enemyhp;
                document.getElementById(rassa).innerHTML = data.anim;
                document.getElementById('enemyhpdown').innerHTML = '<marquee scrollamount=2 height=50; loop=1 direction=up>' + enemyhpdown + '</marquee>';
                setTimeout('animback()', 900);
                setTimeout('successurl()', 6000);
            }

        });
    }

    function start() {
        $.ajax({
            url: '/hunt/func.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'start': 'true',
                'access_key': '<?php echo md5(md5(md5($battlekey['id'] * 5))); ?>'},
            cache: false,
            error: function () {
                setTimeout('start()', 5000);
            },
            success: function (data) {
                if (data.myhp <= 0 || data.enemyhp <= 0) {
                    showContent('/hunt/result');
                }
                document.getElementById('myhp').innerHTML = data.myhp;
                document.getElementById('enemyhp').innerHTML = data.enemyhp;
                document.getElementById('myname').innerHTML = data.myname;
                document.getElementById('enemyname').innerHTML = data.enemyname;
            }
        });
    }
</script>
