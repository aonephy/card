<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = 'creditCardConsumption';
	$month = date('m');
	$year = date('Y');
	$qry = mysql_query("select * from $table where creditCardId='$_GET[id]' and year(date) in ('$year') and delstatus='1' order by date asc");
	
	while($rs = mysql_fetch_assoc($qry)){
		$data[] = $rs; 
	}
	
	
	if(empty($data)){
		$out = array(
			'code'=>'10001',
			'msg'=>'Id error!',
			'month'=>$month,
			'year'=>$year
		);
	}else{
		$out = array(
			'code'=>'10000',
			'msg'=>"get bank list success!",
			'data'=>$data,
			'month'=>$month,
			'year'=>$year
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

