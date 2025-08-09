<?php
  class connect{
  	public function req($a){
  	switch($a){
  	case 0:
        json_encode("each ".$a);
        break;
      case 1:
        json_encode("true");
        return true;
        break;
    }
        }
  }
   if(!empty($_GET['version'])){
   	echo "version program".$_GET['version'];
   }
?>