<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	$user=$_SESSION['user'];
	$table = 'creditCardMgmt';
	$groupId = mysql_fetch_array(mysql_query("select accountGroup.groupnum from accountGroup inner join user where accountGroup.guid = user.guid and userid = '$user'"))[0];
	$day = date("d");
	$dayOffset = 6;
	
	$qry = mysql_query("select $table.bank,$table.iconUrl,bankList.bankName,$table.cardNum,$table.creditCardId,$table.accountDate,$table.repaymentDate from $table inner join bankList where $table.bank=bankList.bankId and $table.groupId='$groupId' and $table.delstatus='1' order by $table.repaymentDate");
	
	if(@$_GET['method']=='lately'){
		$qry = mysql_query("select $table.bank,$table.iconUrl,bankList.bankName,$table.cardNum,$table.creditCardId,$table.accountDate,$table.repaymentDate from $table inner join bankList where $table.bank=bankList.bankId and $table.groupId='$groupId' and $table.delstatus='1' and $table.repaymentDate<($day+$dayOffset) and $table.repaymentDate>=$day order by $table.repaymentDate");
	}
	
	while($rs = mysql_fetch_assoc($qry)){
		$data[] = $rs; 
	}
	
	if(empty($data)){
		
		$out = array(
			'code'=>'10001',
			'msg'=>'groupId error!',
			'sql'=>"select $table.bank,$table.iconUrl,bankList.bankName from $table innor join in bankList where $table.bank=bankList.bankId and $table.groupId='$groupId' and $table.delstatus='1'"			
		);
	}else{
		$out = array(
			'code'=>'10000',
			'msg'=>"get bank list success!",
			'data'=>$data,
			'num'=>date("d")
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

