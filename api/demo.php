<?php
	
	header("content-Type: text/html; charset=utf-8");
	header('Content-type: text/json');
	
	
	$out = array(
		array("imgUrl"=>'../../image/icons/weather.png','Name'=>'天气','Url'=>'../weather/index'),
		array("imgUrl"=>'../../image/icons/exchange.png','Name'=>'汇率查询','Url'=>'../exchange/index'),
		array("imgUrl"=>'../../image/icons/ip_address.png','Name'=>'IP查询','Url'=>'../queryip/index'),
	);
    
   echo json_encode($out,JSON_UNESCAPED_UNICODE);
?>

