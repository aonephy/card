<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = 'creditCardMgmt';
	$qry = mysql_query("select * from $table inner join bankList where $table.creditCardId='$_GET[id]' and $table.delstatus='1' and $table.bank=bankList.bankId");
	
	$rs = mysql_fetch_assoc($qry);
	$data = $rs; 
	
	
	if(empty($data)){
		
		$out = array(
			'code'=>'10001',
			'msg'=>'Id error!'
		);
	}else{
		$out = array(
			'code'=>'10000',
			'msg'=>"get bank list success!",
			'data'=>$data
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

