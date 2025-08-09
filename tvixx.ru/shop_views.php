<?php
class Bot{
  public $cm;
  public function Comand($comand){
  	$cm = array(
        '?????' => date("h:i")
      );
    foreach($cm as $c => $v){
      if(preg_match('/'.$c.'/ui',$comand)){
         echo "????";
      }else{
      	echo "noo".$v;
      }
    }
  }
}
$obj = new Bot;
$obj->Comand("??????? ?????? ??????");