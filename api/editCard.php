<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
@	$unionId = $_GET['unionId'];
	$table = "creditCardMgmt";
	$groupId = mysql_fetch_array(mysql_query("select groupnum from user where userid='$user' or unionId='$unionId'"))[0];
		
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if(empty($groupId)){
		
		$out = array(
			'code'=>'10001',
			'msg'=>'auther error!'
		);
	}else{
	//	mysql_query("insert into $table (bank,groupId,iconUrl,cardNum,accountDate,repaymentDate,minConsumptionTime,creditCardId,ip,datetime) values ('$_POST[bank]','$groupId','images/$_POST[bank].png','$_POST[accountDate]','$_POST[accountDate]','$_POST[repaymentDate]','$_POST[minConsumptionTime]','$creditCardId','$ip',now())") or die(mysql_error());
		
		mysql_query("update $table set bank='$_POST[bank]',cardNum='$_POST[cardNum]',creditLimit='$_POST[creditLimit]',accountDate='$_POST[accountDate]',repaymentDate='$_POST[repaymentDate]',minConsumptionTime='$_POST[minConsumptionTime]',iconUrl='images/$_POST[bank].png',ownerId='$_POST[ownerId]' where groupId='$groupId' and creditCardId='$_POST[creditCardId]'") or die(mysql_error());
		
		$out = array(
			'code'=>'10000',
			'msg'=>"eidt card info success!",
			'post'=>$_POST
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

