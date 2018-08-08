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
		
		<script src="/jquery/jquery.validate.min.js"></script>
		<script src="/jquery/messages_cn.js"></script>
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
					<div class="media" style="position:relative">
						<a class="media-left" >
							<img class="media-object" :src="cardContent.iconUrl" alt="媒体对象">
						</a>
						<div class="media-body">
							<h4 class="media-heading">{{cardContent.bankName}}</h4>
							**** **** **** {{cardContent.cardNum}}
							
						</div>
						
					</div>
				</div>
				
				<div class="panel-body">
						<div style='float:left;margin-bottom:20px'>出账日 ：每月<span style="color:red"> {{cardContent.accountDate}} </span>日</div>
						
						<div style='float:right'>还款日 ：每月<span style="color:red"> {{cardContent.repaymentDate}} </span>日</div>
						<div style="clear:both">持卡人 ： <span style='    padding: 3px 20px;background: #a7a7a7;color: #fff;'>{{cardContent.username}}</span></div>
						<hr style="clear:both">
						<div style='float:left'>免年费消费次数 ：<span style="color:blue"> {{cardContent.minConsumptionTime}} </span> 次</div>
							
						<div style='float:right;font-size:1.3em' @click="addItem" v-bind:class="{disabled:!addIndex}">
							<span class="glyphicon glyphicon-credit-card"></span>
							
						</div>
				
				</div>
			</div>
			
			
			<form class="content panel panel-info" onsubmit="return false"  v-for="rs in consumptionList">
				<div class="panel-heading">
					<h3 class="panel-title" v-if="rs.date">{{rs.date}}</h3>
					<h3 class="panel-title" v-else>日期 ：<input type='date' class="form-control" name='date' v-model='date'></h3>
				</div>
				<div class="panel-body">
					<div v-if="rs.amount">消费 ：{{rs.amount}}</div>
					<div v-else>消费 ：<input type='int' class="form-control" name='amount' onkeyup="value=value.replace(/[^\d]/g,'')" v-model="amount"></div>
				</div>
				<div class="panel-footer">
					<div v-if="rs.amount">备注 ：{{rs.remark}}</div>
					<div v-else>
						备注 ：<input type='text' class="form-control" v-model="remark">
						<div style=''>
							<input type='submit' name='submit' class='btn-info btn btn-sm' @click='formCheck'> <button class='btn btn-default btn-sm' @click='removeItem'>取消</button>
						</div>
					</div>
				</div>
			</from>
			
			
		</div>
		
		<script type="text/javascript">
			var vm = new Vue({
					el: '#p-body',
					data: {
						cardContent:{iconUrl:'images/default.png'},
						consumptionList:[],
						addIndex:true,
						amount:'',
						date:'',					
						remark:'',
						dataSaveing:false
					},
					methods: {
						getCardContent(){
							axios({
								url:'api/getCardContent.php',
								method: 'get',
								params:{id:'<?=$id?>'},
								responseType: 'json',
								transformResponse: [function(res){
							//		console.log(res.data)								
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
							//		console.log(res.data);
									if(res.code==10000){
										vm.consumptionList = res.data;
										vm.dataSaveing = false;
									}
								}]
							})
						},
						addItem(){
							if(this.addIndex){
								this.date = '';
								this.amount = '';
								this.remark = '';
								this.consumptionList.unshift({date:'',amount:'',remark:''})
								this.addIndex = false;
							//	console.log(this.consumptionList)
							}
						},
						removeItem(){
							this.consumptionList.shift({datetime:null,amount:null});
							this.addIndex = true;
						},
						saveNewItem(){
							if(!vm.dataSaveing){
											
								vm.dataSaveing = true;
								
								let data = {creditCardId:"<?=$id?>",amount:this.amount,date:this.date,remark:this.remark};
								
								let params = this.FormatData(data); 
								console.log(data);
								if(this.date==''||this.amount==''){
									alert("日期及消费金额不能为空！");
								}else{
									axios({
										url:'api/newConsumption.php',
										method: 'POST',
										data:params,
										responseType: 'json',
										transformResponse: [function(res){
											
											if(res.code==10000){
												vm.addIndex = true;
												vm.getConsumptionList();
											}
									
										}]
										
									})
								}
							}
						},
						FormatData(data){
							let paramters = new FormData(); 
							for(var key in data){
								paramters.append(key,data[key])
							}
							return paramters;
						},
						formCheck(){
							//	console.log('form validation!')
								
								$(".content").validate({
									rules : {
										amount : {
											required : true,
											number : true,
											digits : true,
										},
										date : {
											required : true,
											date : true,
										}
									},
									submitHandler: function(form){
										vm.saveNewItem();
										
									}
								});
							
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