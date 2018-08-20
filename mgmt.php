<?php
	include("../conf/conn.php");

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
		<script src="/jquery/jquery-1.11.3.min.js"></script>
		
		<style>
			.panel{width:96%;margin:10px auto}
			.navbar-right{float:right}
			
			.navbar{border-bottom:1px solid #eee;min-height:unset;margin-bottom:unset}
			.navbar-brand{padding:10px 15px;cursor:pointer;height:auto}
			.navbar h4{position:absolute;width:100%;text-align:center;font-weight:700;z-index:-1}}
			
			.glyphicon{color:#24b6fe}
			.media-body div{font-size:14px;line-height: 1.5;}
			.media{position:relative;}
			.media-right-sm-box{position:absolute;top:0px;right:0px}
			.panel .media-object{width:64px;border-radius:50px}
			@media (min-width: 768px){
				.navbar-header{width:100%}
			}
		</style>
		<script src="/js/vue.min.js"></script>
		<script src="/js/axios.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
	<body>
		
		<div id='p-body'>
			<nav class="navbar" role="navigation">
				<div class="container-fluid"> 
					<div class="navbar-header">
						
						<div class="navbar-left">
							<a class="navbar-brand" href="./">
								<span class="glyphicon glyphicon-chevron-left"></span>
							</a>
						</div>
						<h4 v-if="cardList.length!=0">信用卡列表 (<span style='color:red'> {{cardList.length}} </span>张卡)</h4>
						<h4 v-else>信用卡列表</h4>
						<div class="navbar-right">
							<a href="add.php" class="navbar-brand">
							  <span class="glyphicon glyphicon-plus"></span>
							</a>
						</div>
					</div>
					
				</div>
			</nav>
			<div class="panel panel-success"  v-for="rs,index in cardList">
				<!--
				<div class="panel-heading">
					<h3 class="panel-title">{{rs.bankName}}</h3>
				</div>
				-->
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
								<!--
								<div>出账日：每月{{rs.accountDate}}日</div>
								-->
								<div>还款日：每月<span style="color:red"> {{rs.repaymentDate}}</span> 日</div>
								<div>已消费 
									<span style="color:blue" v-if="rs.creditCardConsumptionNum"> {{rs.creditCardConsumptionNum}} / {{rs.minConsumptionTime}}</span>
									<span style="color:blue" v-else> 0 / {{rs.minConsumptionTime}} </span>
								次</div>
								<div v-if="rs.username" style="clear:both">持卡人 ： <span style='padding: 3px 20px;background: #a7a7a7;color: #fff;'>{{rs.username}}</span></div>
									
								<!--	{{isPay(rs.repaymentTimestamp,rs.repaymentDate)}}-->
							</a>	
						</div>
						
						<a class="media-right-sm-box" :href="'javascript:vm.alreadyPay(\''+rs.creditCardId+'\')'" v-if="!isPay(rs.repaymentTimestamp,rs.repaymentDate)">
							<img class="media-object" style='width:50px;border-radius:5px' src="images/alreadyPay.png" alt="媒体对象">
						</a>
					</div>
				</div>
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
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res.data)								
									vm.cardList = res.data;
								}]
							})
						},
						isPay(repaymentTimestamp,repaymentDate){
							console.log(repaymentTimestamp);
							if(repaymentTimestamp==null){
								return false;
							}else{
								let pay_month = new Date(repaymentTimestamp).getMonth()+1;
								let month = new Date().getMonth()+1;
								
								if(pay_month<month){
									return false;
								}else{
									return true;
								}
							}
						},
						alreadyPay(id){
							if(confirm('确认已经还款？')){
								console.log(id);
								axios({
									url:'api/alreadyPay.php',
									method: 'get',
									params:{creditCardId:id},
									responseType: 'json',
									transformResponse: [function(res){
										vm.getCardList();
									}]
								})
							}
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
		$url = "/bbs/login.php?dir=".$address; 
		Header("Location:$url");
	}
?>