<?php
	header("content-Type: text/html; charset=utf-8");
//	header('Content-type: text/json');
	include("../../conf/conn.php");
	
	$user=$_SESSION['user'];
	$table = 'creditCardConsumption';
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if(empty($_POST['creditCardId'])){
		$out = array(
			'code'=>'10001',
			'msg'=>'creditCardId error!'
		);
	}else{
		mysql_query("insert into $table (creditCardId,amount,remark,ip,date,timestamp) values ('$_POST[creditCardId]','$_POST[amount]','$_POST[remark]','$ip','$_POST[date]',now())") or die(mysql_error());
		$out = array(
			'code'=>'10000',
			'msg'=>"add new consumption success!"
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

