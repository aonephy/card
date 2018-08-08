<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
@	$unionId = $_GET['unionId'];
	if(empty($unionId)) $unionId = '';
	
	$table = 'accountGroup';
	$groupId = mysql_fetch_array(mysql_query("select accountGroup.groupnum from accountGroup inner join user where accountGroup.guid = user.guid and (user.userid='$user' or accountGroup.unionId='$unionId')"))[0];
	
	$sql = "select $table.guid,user.username from $table inner join user where $table.guid=user.guid and $table.groupnum='$groupId'";
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

