<?php
require_once 'system/header.php';
require_once 'system/func.php';
auth();
//секр сл 1 16juc7le
//секр сл 2 or8flttb
//айди 86179
//секрет danilgu


//баланс http://www.free-kassa.ru/api.php?merchant_id=86179&s=38d4bde72dbf4a7fa3bc6fe830f1d371&action=get_balance

if(isset($_GET['pay'])){
	if($_GET['pay'] > 0){
		$colvo = $_GET['pay'];
		

	if(isset($_GET['event']))
	{
		if($_GET['event'] == 1)
		{
			$colvo = 150;
			$mc->query("INSERT INTO `buyplata`(`user`, `colvo`, `status`, `event`) VALUES ('".$user['id']."','". $colvo ."',0, 1)");
		}
		
	}else{
		$mc->query("INSERT INTO `buyplata`(`user`, `colvo`, `status`) VALUES ('".$user['id']."','".$colvo."',0)");
	}

		$id_zak = $mc->insert_id;
			$merchant_id = '86179';
			$secret_word = '16juc7le';
			$order_id = $id_zak;
			$order_amount = $colvo;
			$sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$order_id);
			//http://www.free-kassa.ru/merchant/cash.php?m=86179&oa=16.25&o=2&s=791718cb7a251d3b31c60023539f0b41&i=1&lang=ru&us_login=Danilgu2&pay=????????
			?>

                        <!--form method='get' action='http://www.free-kassa.ru/merchant/cash.php'>
                        <input type='hidden' name='m' value='<?php echo $merchant_id;?>'>
						<input type='hidden' name='oa' value='<?php echo $order_amount;?>'>
						<input type='hidden' name='o' value='<?php echo $order_id; ?>'>
						<input type='hidden' name='s' value='<?php echo $sign;?>'>
						<input type='hidden' name='lang' value='ru'>
						<input type='hidden' name='us_login' value='<?php echo $user['login'];?>'>
						<input type='submit' name='pay' value='Оплатить'>
						</form-->
						переход на <a href="http://www.free-kassa.ru/merchant/cash.php?m=<?php echo $merchant_id;?>&oa=<?php echo $order_amount;?>&o=<?php echo $order_id; ?>&s=<?php echo $sign;?>&lang=ru&us_login=<?php echo $user['login'];?>">free-kassa.ru</a>
						<meta http-equiv="refresh" content="0;URL=http://www.free-kassa.ru/merchant/cash.php?m=<?php echo $merchant_id;?>&oa=<?php echo $order_amount;?>&o=<?php echo $order_id; ?>&s=<?php echo $sign;?>&lang=ru&us_login=<?php echo $user['login'];?>"><center>
			<?php
	}else{
			?>

                      
						переход на <a href="https://tvixx.ru/">tvixx.ru</a>
						<meta http-equiv="refresh" content="0;URL=https://tvixx.ru/"><center>
			<?php
	}
}
?>