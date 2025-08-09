<?php
require_once ('system/dbc.php');
require_once ('system/func.php');

class History{
	public function __construct($id){
		global $mc;
		
		if(intval($id)){
			$nik = $mc->query("SELECT * FROM `users` WHERE `id` = '".$id."'")->fetch_array(MYSQLI_ASSOC);
			$nikk = explode("",$nik['newNames']);
			for($i = 0; $i < count($nikk); $i++){
				if(!empty($nikk[$i])){
				  echo $nikk[$i]."<br>";
				}
		    }
		}else{
			throw new Exception('Взлом');
		}
	}
}
 if(isset($_GET['id'])){
 	$int = intval($_GET['id']);
     $nams = $mc->query("SELECT * FROM `users` WHERE `id` = '".$int."'")->fetch_array(MYSQLI_ASSOC);
?>
	<center>--История ников персонажа <b><?=$nams['name'];?></b>--</center><hr>
	<table class="table_block2">
            <tr>
                <td class="block101" style="width: 2%"></td>
                <td class="block102" style="width: 96%"></td>
                <td class="block103" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block104" style="width: 2%"></td>
                <td class="block105" style="width: 96%;text-align: center;">
            <center>
	<?php
	try{
	  $i = new History($_GET['id']);
	}catch(Exception $e){
		echo "ошибка: {$e->getMessage()}";
	}
	?>
	</center>
            <td class="block106" style="width: 2%"></td>
            </tr>
            <tr>
                <td class="block107"></td>
                <td class="block108"></td>
                <td class="block109"></td>
            </tr>
        </table><?php
}
$footval = "changeParams";
require_once ('system/foot/foot.php');
