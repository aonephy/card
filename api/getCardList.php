<?php
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	include("../../conf/conn.php");
	
	$user=$_SESSION['user'];
@	$unionId = $_GET['unionId'];
	if(empty($unionId)) $unionId = '';
	
	$table = 'creditCardMgmt';
	
	$year = date('Y');
	
	$groupId = mysql_fetch_array(mysql_query("select groupnum from user where userid='$user' or unionId='$unionId'"))[0];

	$day = date("d");
	$dayOffset = 6;//未来6天需要还款的卡
	
	$sql = ("select $table.bank,$table.iconUrl,creditBankList.bankName,$table.cardNum,$table.creditCardId,$table.accountDate,$table.repaymentDate,$table.ownerId,$table.repaymentTimestamp,$table.minConsumptionTime from $table inner join creditBankList where $table.bank=creditBankList.bankId and $table.groupId='$groupId' and $table.delstatus='1' order by $table.ownerId desc,$table.repaymentDate");
	
	if(@$_GET['method']=='lately'){
		$sql = ("select $table.bank,$table.iconUrl,creditBankList.bankName,$table.cardNum,$table.creditCardId,$table.accountDate,$table.repaymentDate,$table.ownerId,$table.repaymentTimestamp,$table.minConsumptionTime from $table inner join creditBankList where $table.bank=creditBankList.bankId and $table.groupId='$groupId' and $table.delstatus='1' and $table.repaymentDate<($day+$dayOffset) order by $table.ownerId desc,$table.repaymentDate");
	}
	$qry = mysql_query($sql);
	$i=0;
	while($rs = mysql_fetch_assoc($qry)){
		$data[$i] = $rs; 
		//查询每个卡追认的名字
		if(!empty($rs['ownerId'])){
			$tmp = mysql_fetch_assoc(mysql_query("select username from user where guid='$rs[ownerId]'"));
			
			$data[$i] = array_merge($data[$i],$tmp);
			
			$num = mysql_num_rows(mysql_query("select id from creditCardConsumption where creditCardId='$rs[creditCardId]' and year(date) in ('$year') and delstatus='1'"));
			$data[$i] = array_merge($data[$i],array('creditCardConsumptionNum'=>$num));
		}
		$i++;
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
			'data'=>$data,
			'num'=>$num
		);
	}
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

