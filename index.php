<?php
	include("../conf/conn.php");
	@$openid = $_GET['openid'];
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
			.media{position:relative;}
			.media-right-sm-box{position:absolute;top:0px;right:0px}
			.panel .media-object{width:64px;border-radius:50px}
			@media (min-width: 768px){
				.navbar-header{width:100%}
			}
			a:hover{text-decoration: none;}
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
						
						<h4 v-if="cardList.length!=0">6天内待还款卡 (<span style='color:red'> {{cardList.length}} </span>张卡)</h4>
						<h4 v-else>6天内待还款卡</h4>
						<div class="navbar-right">
							<a href="mgmt.php" class="navbar-brand">
							  <span class="glyphicon glyphicon-th-list"></span>
							</a>
						</div>
					</div>
					
				</div>
			</nav>	
			<div class="panel panel-info"  v-for="rs,index in cardList" v-if="!isPay(rs.repaymentTimestamp,rs.repaymentDate)">
				
				<div class="panel-heading">
					
					<div class="media">
						<a class="media-left" :href="'list.php?id='+rs.creditCardId">
							<img class="media-object" :src="rs.iconUrl" alt="媒体对象">
						</a>
						<div class="media-body">
							<a :href="'list.php?id='+rs.creditCardId">
								<h4 class="media-heading">{{rs.bankName}}</h4>
								**** **** **** {{rs.cardNum}}
								<div>还款日：每月 <span style="color:red"> {{rs.repaymentDate}} </span>日</div>
								
								<div v-if="rs.username" style="clear:both">持卡人 ： <span style='padding: 3px 20px;background: #a7a7a7;color: #fff;'>{{rs.username}}</span></div>
								
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
								params:{method:'lately'},
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
								console.log(month);
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
		$url = "/bbs/login.php?openid=$openid&dir=".$address; 
		Header("Location:$url");
	}
?>