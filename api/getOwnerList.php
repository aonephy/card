<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
@	$unionId = $_GET['unionId'];
	if(empty($unionId)) $unionId = '';
	
	$table = 'user';
	$groupId = mysql_fetch_array(mysql_query("select groupnum from user where userid='$user' or unionId='$unionId'"))[0];
	
	$sql = "select guid,username from $table where groupnum='$groupId'";
	
	$qry = mysql_query($sql);
		
	while($rs = mysql_fetch_assoc($qry)){
		$data[] = $rs; 
	}
	
	if(empty($data)){
		$out = array(
			'code'=>'10001',
			'msg'=>'groupId error!',
			'sql'=>$sql		
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

