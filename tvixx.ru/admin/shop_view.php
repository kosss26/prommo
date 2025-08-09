<?php
//require_once 'system/header.php';
 $file = file('test_shop.txt');
 $style;
 
 foreach ($file as $line_num => $line) {
    $arr = explode("",$line);
    if($arr[3] == "Броня"){
    	$style = "red";
    }else
    if($arr[3] == "Урон"){
    	$style = "green";
    }else
    if($arr[3] == "Элита"){
    	$style = "yellow";
    }else
    if($arr[3] == "Уворот"){
    	$style = "blue";
    }else{
    	$style = "black";
    }
    if(!isset($_GET['view']) && !isset($_GET['id'])){
    ?>
                           <div class="shops shopblock" onclick="showContent('#')">
                            <table  class="table_block2" >
                            <tr>
                                <td style="width: 90px;">
                                    <div class="shopicobg shopico19">
                                    </div>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td class="arrowShop<?= $ids[$x]; ?>">
                                                <?= $arr[2] ?>
                                                <?php
                                              /*  if ($user['access'] > 2) {
                                                    echo' (id: ' . $shopmagazin['id'] . ')';
                                                }*/
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                 if($arr[3] != "нет"){?>
                                                 	<font color="<?=$style;?>"><?=$arr[3];?></font>
                                               <?php  }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Уровень: <?= $arr[4]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Цена:
                                                
                                            </td>
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </div>
    <?php
}
}
if(isset($_GET['view']) && isset($_GET['id'])){
	echo "gellli";
}
$footval = 'adminindex';
include '../system/foot/foot.php';
?>