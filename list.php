<?php
	include("../conf/conn.php");
	$id=$_GET['id'];
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
			.navbar-brand{padding:10px 15px;cursor:pointer}
			.navbar h4{position:absolute;width:100%;text-align:center;font-weight:700;z-index:-1}
			.navbar-brand{height:auto;}
			.glyphicon{color:#24b6fe}
			hr{margin:10px auto}
			.panel .media-object{width:45px;border-radius:50px}
			.disabled .glyphicon{color:#ccc}
			.btn-sm{margin:10px 20px 0px 0px}
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
							<a class="navbar-brand" href="mgmt.php">
								<span class="glyphicon glyphicon-chevron-left"></span>
							</a>
						</div>
						<h4>信用卡详情</h4>
						<div class="navbar-right">
							<a href="edit.php?id=<?=$id?>" class="navbar-brand">
							  <span class="glyphicon glyphicon-edit"></span>
							</a>
						</div>
					</div>
					
				</div>
			</nav>
			<div class="panel panel-success" >
				
				<div class="panel-heading">
					<div class="media">
						<a class="media-left" >
							<img class="media-object" :src="cardContent.iconUrl"
								 alt="媒体对象">
						</a>
						<div class="media-body">
							<h4 class="media-heading">{{cardContent.bankName}}</h4>
							**** **** **** {{cardContent.cardNum}}
						</div>
					</div>
				</div>
				
				<div class="panel-body">
						<div style='float:left;margin-bottom:20px'>出账日：每月<span style="color:red"> {{cardContent.accountDate}} </span>日</div>
							
						<div style='float:right'>还款日：每月<span style="color:red"> {{cardContent.repaymentDate}} </span>日</div>
						<hr style="clear:both">
						<div style='float:left'>免年费消费次数：<span style="color:blue"> {{cardContent.minConsumptionTime}} </span> 次</div>
							
						<div style='float:right;font-size:1.3em' @click="addItem" v-bind:class="{disabled:!addIndex}">
							<span class="glyphicon glyphicon-credit-card"></span>
							
						</div>
				
				</div>
			</div>
			
			
			<div class="panel panel-info" v-for="rs in consumptionList">
				<div class="panel-heading">
					<h3 class="panel-title" v-if="rs.datetime">{{rs.datetime}}</h3>
					<h3 class="panel-title" v-else>日期 ：<input type='date' class="form-control" v-model='datetime'></h3>
				</div>
				<div class="panel-body">
					<div v-if="rs.amount">消费 ：{{rs.amount}}</div>
					<div v-else>消费 ：<input type='int' class="form-control" onkeyup="value=value.replace(/[^\d]/g,'')" v-model="amount"></div>
				</div>
				<div class="panel-footer">
					<div v-if="rs.amount">备注 ：{{rs.remark}}</div>
					<div v-else>
						备注 ：<input type='text' class="form-control" v-model="remark">
						<div style=''>
							<button class='btn-info btn btn-sm' @click='saveNewItem'>保存</button> <button class='btn btn-default btn-sm' @click='removeItem'>取消</button>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
		<script type="text/javascript">
			var vm = new Vue({
					el: '#p-body',
					data: {
						cardContent:{iconUrl:'images/default.png'},
						consumptionList:[],
						addIndex:true,
						amount:null,
						datetime:null,					
						remark:null
					},
					methods: {
						getCardContent(){
							axios({
								url:'api/getCardContent.php',
								method: 'get',
								params:{id:'<?=$id?>'},
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res.data)								
									vm.cardContent = res.data;
								}]
							})
						},
						getConsumptionList(){
							axios({
								url:'api/getConsumptionList.php',
								method: 'get',
								params:{id:'<?=$id?>'},
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res.data);
									if(res.code==10000){
										vm.consumptionList = res.data;
									}
								}]
							})
						},
						addItem(){
							if(this.addIndex){
								this.consumptionList.unshift({datetime:null,amount:null})
								this.addIndex = false;
							}
						},
						removeItem(){
							this.consumptionList.shift({datetime:null,amount:null});
							this.addIndex = true;
						},
						saveNewItem(){
							let data = {creditCardId:"<?=$id?>",amount:this.amount,datetime:this.datetime,remark:this.remark};
							
							let params = this.FormatData(data); 
						//	console.log(data);
							
							axios({
								url:'api/newConsumption.php',
								method: 'POST',
								data:params,
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res);
								
									if(res.code==10000){
										vm.addIndex = true;
										vm.getConsumptionList();										
									}
							
								}]
								
							})
							
						},
						FormatData(data){
							let paramters = new FormData(); 
							for(var key in data){
								paramters.append(key,data[key])
							}
							return paramters;
						}
					},
					mounted: function(){
						this.getCardContent();
						this.getConsumptionList();
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