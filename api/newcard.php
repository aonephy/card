<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = "creditCardMgmt";
	
@	$unionId = $_GET['unionId'];
	$groupId = mysql_fetch_array(mysql_query("select accountGroup.groupnum from accountGroup inner join user where accountGroup.guid = user.guid and (user.userid='$user' or accountGroup.unionId='$unionId')"))[0];
	
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if(empty($groupId)){
		
		$out = array(
			'code'=>'10001',
			'msg'=>'auther error!'
		);
	}else{
		$creditCardId = md5(time());
		mysql_query("insert into $table (bank,groupId,ownerId,iconUrl,cardNum,accountDate,repaymentDate,minConsumptionTime,creditCardId,ip,datetime) values ('$_POST[bank]','$groupId','$_POST[ownerId]','images/$_POST[bank].png','$_POST[cardNum]','$_POST[accountDate]','$_POST[repaymentDate]','$_POST[minConsumptionTime]','$creditCardId','$ip',now())") or die(mysql_error());
		$out = array(
			'code'=>'10000',
			'msg'=>"add new card success!"
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

