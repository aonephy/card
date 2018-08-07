<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = 'creditCardConsumption';
	$month = date('m');
	$qry = mysql_query("select * from $table where creditCardId='$_GET[id]' and month(date) in ('$month') and delstatus='1' order by date asc");
	
		
	while($rs = mysql_fetch_assoc($qry)){
		$data[] = $rs; 
	}
	
	
	if(empty($data)){
		$out = array(
			'code'=>'10001',
			'msg'=>'Id error!',
			'month'=>$month
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

