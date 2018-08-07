<?php
	include("../conf/conn.php");
	$openid = $_GET['openid'];
	$user=$_SESSION['user'];
	if(!empty($user)){
?>
<!DOCTYPE html>
<html>
	<head>
		<title>皮皮侠信用卡管家</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1, maximum-scale=1.0">
		<link rel="stylesheet" href="/css/bootstrap.min.css">  
		<link rel="Shortcut Icon" href="/ppxb.ico" />
		<style>
			.panel{width:96%;margin:20px auto}
			.navbar-right{float:right}
			
			.navbar{border-bottom:1px solid #eee;min-height:unset}
			.navbar-brand{padding:10px 15px;cursor:pointer}
			.navbar h4{position:absolute;width:100%;text-align:center;font-weight:700}
			.navbar-brand{height:auto}
			.glyphicon{color:#24b6fe}
			
			.panel .media-object{width:64px;border-radius:50px}
			@media (min-width: 768px){
				.navbar-header{width:100%}
			}
		</style>
		<script src="/jquery/jquery-1.11.3.min.js"></script>
		<script src="/js/vue.min.js"></script>
		<script src="/js/axios.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
	<body>
		
		<div id='p-body' >
			<nav class="navbar" role="navigation">
				<div class="container-fluid"> 
					<div class="navbar-header">
						<h4>待还款信用卡（6天内）</h4>
						<div class="navbar-right">
							<a href="mgmt.php" class="navbar-brand">
							  <span class="glyphicon glyphicon-th-list"></span>
							</a>
						</div>
					</div>
					
				</div>
			</nav>	
			<div class="panel panel-info"  v-for="rs,index in cardList">
				
				<div class="panel-heading">
					
						<div class="media">
						<a class="media-left" :href="'list.php?id='+rs.creditCardId">
							<img class="media-object" :src="rs.iconUrl"
								 alt="媒体对象">
						</a>
						<div class="media-body">
							<a :href="'list.php?id='+rs.creditCardId">
								<h4 class="media-heading">{{rs.bankName}}</h4>
								**** **** **** {{rs.cardNum}}
								<div>还款日：每月 <span style="color:red"> {{rs.repaymentDate}} </span>日</div>
							</a>	
						</div>
					</div>
					
				</div>
				<!--
				<div class="panel-body">
				
					<div class="media">
						<a class="media-left" :href="'list.php?id='+rs.creditCardId">
							<img class="media-object" :src="rs.iconUrl"
								 alt="媒体对象">
						</a>
						<div class="media-body">
							<a :href="'list.php?id='+rs.creditCardId">
								<h4 class="media-heading">{{rs.bankName}}</h4>
								**** **** **** {{rs.cardNum}}
								<div>还款日：每月{{rs.repaymentDate}}日</div>
							</a>	
						</div>
					</div>
					
				</div>
				-->
			</div>
		</div>
		
		<script type="text/javascript">
			var vm = new Vue({
					el: '#p-body',
					data: {
						cardList:[],
					},
					methods: {
						getCardList(){
							axios({
								url:'api/getCardList.php',
								method: 'get',
								params:{method:'lately'},
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res.data)								
									vm.cardList = res.data;
								}]
							})
						}
					},
					mounted: function(){
						this.getCardList();
					}
				
				})
		
		</script>

	</body>
</html>	
<?php
	}else{
		$address = $_SERVER['REQUEST_URI'];
	//	$address = urlencode($address);
		$url = "/bbs/login.php?openid=$openid&dir=".$address; 
		Header("Location:$url");
	}
?>