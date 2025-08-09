<?php
require_once ('../system/func.php');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$loca = $user["location"];
$location = $mc->query("SELECT * FROM `location` WHERE `id`='$loca'")->fetch_array(MYSQLI_ASSOC);
if ($result221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' AND `dress`='1' AND `id_punct` = '1'")) {
    $myrow221 = $result221->fetch_array(MYSQLI_ASSOC);
    if ($result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221['id_shop'] . "'")) {
        //thing to arr par
        $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
        $userWeaponico = (int) $infoshop['id_image'];
    }
} else {
    $userWeaponico = 0;
}
?>
<link rel="stylesheet" href="style/battle.css?136.1" type="text/css">
<div style="width: 100%;position: absolute;left: 0;top: 0;text-align: center;">
    <div class="null_480_666" style="margin: auto;">
        <div class="mainBattle"></div>
        <div class="buttonBattle"></div>
    </div>
</div>
<script>
    MyLib.footName = "huntbattle";
    MyLib.bttl.Pico = parseInt("<?php echo $user['side']; ?>") + 0;
    MyLib.bttl.Playername = "<?php echo $user['name']; ?>";
    MyLib.bttl.Pweapon = "<?php echo $userWeaponico; ?>";
    MyLib.bttl.PposL = -4;//%
    MyLib.bttl.MposR = -4;//%
    MyLib.bttl.PposX = -50;//%
    MyLib.bttl.MposX = -50;//%
    MyLib.bttl.movemob = 0;
    MyLib.bttl.Pload = 0;
    MyLib.bttl.end = 0;
    MyLib.bttl.loading = 0;
    MyLib.bttl.lost_mob_id = 0;
    MyLib.bttl.tmpEntityP = [];
    MyLib.bttl.tmpEntityM = [];
    MyLib.bttl.arrEntityP = [];
    MyLib.bttl.arrEntityM = [];
    MyLib.bttl.EntityCoordP = [];
    MyLib.bttl.EntityCoordM = [];
    MyLib.bttl.Panimation = 0;
    MyLib.bttl.Panimationcount = 0;
    MyLib.bttl.Manimation = 0;
    MyLib.bttl.Manimationcount = 0;
    MyLib.bttl.Mweapon = 0;
    MyLib.bttl.BattleResult = 0;
    MyLib.bttl.PshieldNC = 0;
    MyLib.bttl.tmpPeleksirdNC = [];
    MyLib.bttl.PeleksirdNC = [];
    MyLib.bttl.PeleksirVisible = 0;
    MyLib.bttl.butbatVisible = 0;
    MyLib.bttl.Pshield = 0;
    MyLib.bttl.Pvisible = 0;


    MyLib.bttl.Ptype = "P";

    MyLib.bttl.Plife = "";
    MyLib.bttl.tempPlife = "";

    MyLib.bttl.Mtype = "M";
    MyLib.bttl.Mshield = 0;
    MyLib.bttl.Mobname = "";
    MyLib.bttl.Mvisible = 0;
    MyLib.bttl.Mico = 1;

    MyLib.bttl.Mlife = "";
    MyLib.bttl.tempMlife = "";

    MyLib.bttl.PDress = 0;
    MyLib.bttl.MDress = 0;
    MyLib.bttl.tmpMobanim = 0;
    MyLib.bttl.tmpPanim = 0;
    MyLib.bttl.setmobanim = 0;
    MyLib.bttl.setPanim = 0;

    MyLib.bttl.ButtonBattleColorCount = 0;
    MyLib.bttl.arrObjCanv = {};

    if (typeof MyLib.htmlMainBattle !== 'undefined') {
        $(".mainBattle:eq(-1)").append(MyLib.htmlMainBattle);
        MyLib.bttl.realBattleEntity.width = MyLib.bttl.realBattleEntity.offsetWidth;
        MyLib.bttl.realBattleEntity.height = MyLib.bttl.realBattleEntity.offsetHeight;
    }
    if (typeof MyLib.htmlButtonsBattle !== 'undefined') {
        $(".buttonBattle:eq(-1)").append(MyLib.htmlButtonsBattle);
    }
    $(document.getElementById("HeroLifeR")).html("");
    $(document.getElementById("HeroLifeL")).html("");

    $(document.getElementById("name1")).text("");
    $(document.getElementById("name2")).text("");

    $(document.getElementById("layer1")).css({left: MyLib.bttl.PposX + "%"});
    $(document.getElementById("layer2")).css({right: MyLib.bttl.MposX + "%"});
    $(".layer0").removeClass().addClass("layer0 location<?= $location['IdImage']; ?>");
<?= $location['snow'] == 1 ? "$('.snowConteiner').html('');snowAppend($('.snowConteiner'));" : "$('.snowConteiner').html('');"; ?>
    battleLoad();
    resizeBattle();
</script>
<?php
$footval = 'huntbattle';
require_once ('../system/foot/foot.php');
?>