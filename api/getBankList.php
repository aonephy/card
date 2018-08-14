<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = 'creditBankList';
	$qry = mysql_query("select bankId,bankName from $table where delstatus='1'");
	
	while($rs = mysql_fetch_assoc($qry)){
		$data[] = $rs; 
	}
	
	if(empty($data)){
		
		$out = array(
			'code'=>'10001',
			'msg'=>'groupId error!'
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

