<?php
	
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = "creditCardMgmt";
	@$unionId = $_GET['unionId'];
	$groupId = mysql_fetch_array(mysql_query("select accountGroup.groupnum from accountGroup inner join user where accountGroup.guid = user.guid and (user.userid='$user' or accountGroup.unionId='$unionId')"))[0];
	
	if(empty($groupId)){
		$out = array(
			'code'=>'10001',
			'msg'=>'auther error!'
		);
	}else{
		mysql_query("update $table set repaymentTimestamp=now() where groupId='$groupId' and creditCardId='$_GET[creditCardId]'") or die(mysql_error());
		$out = array(
			'code'=>'10000',
			'msg'=>"set already pay success!"
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

