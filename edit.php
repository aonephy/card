<?php
	include("../conf/conn.php");
	$id=$_GET['id'];
	$user=$_SESSION['user'];
	if(!empty($user)){
?>
<!DOCTYPE html>
<html>
	<head>
		<title>编辑信用卡</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1, maximum-scale=1.0">
		<link rel="stylesheet" href="/css/bootstrap.min.css">  
		<link rel="Shortcut Icon" href="/ppxb.ico" />
		
		<style>
			.navbar-right{float:right}
			.navbar{border-bottom:1px solid #eee;min-height:unset}
			.navbar-brand{padding:10px 15px;cursor:pointer;height:auto}
			.navbar h4{position:absolute;width:100%;text-align:center;font-weight:700;z-index:-1}
			
			.glyphicon{color:#24b6fe}
			
			#content{
				width:90%;margin:auto
			}
		</style>
		
		<script src="/jquery/jquery-1.11.3.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/jquery/jquery.validate.min.js"></script>
		<script src="/jquery/messages_cn.js"></script>
	<body>
		
		<div id='p-body'>
			<nav class="navbar" role="navigation">
				<div class="container-fluid"> 
					<div class="navbar-header">
						<div class="navbar-left">
							<a class="navbar-brand" :href="'list.php?id='+creditCardId">
								<span class="glyphicon glyphicon-chevron-left"></span>
							</a>
						</div>
						<h4>编辑信用卡</h4>
					</div>
					
				</div>
			</nav>
			<form id="content" onsubmit="return false" class='form'>
			
				<div class="form-group">
					<label for="bank">银行名称</label>
					<select class="form-control" id="bank" name="bank" v-model="bank">
						<option v-for="rs in bankList" :value='rs.bankId' >{{rs.bankName}}</option>
					</select>
				</div>
				<div class="form-group">
					<label for="cardNum">卡号后4位</label>
					<input type="text" class="form-control" id="cardNum" name="cardNum" placeholder="请输入卡号后4位" v-model="cardNum">
				</div>
				
				<div class="form-group">
					<label for="creditLimit">额度</label>
					<input type="text" class="form-control" id="creditLimit" name="creditLimit" v-model="creditLimit">						
				</div>
				<div class="form-group">
					<label for="ownerId">所属人</label>
					<select class="form-control" id="ownerId" name="ownerId" v-model="ownerId">
						<option v-for="rs,index in ownerList" :value='rs.guid'>{{rs.username}}</option>
					</select>
				</div>
				<div class="form-group">
					<label for="accountDate">出账日</label>
					<select class="form-control" id="accountDate" name="accountDate" v-model="accountDate">
						<option v-for="rs,index in date" :value='index+1'>{{index+1}}</option>
					</select>
				</div>
				<div class="form-group">
					<label for="repaymentDate">还款日</label>
					<select class="form-control" id="repaymentDate" name="repaymentDate" v-model="repaymentDate">
						<option v-for="rs,index in date" :value='index+1'>{{index+1}}</option>
					</select>
				</div>
				<div class="form-group">
					<label for="minConsumptionTime">免年费消费次数</label>
					<input type="text" class="form-control" id="minConsumptionTime" name="minConsumptionTime" v-model="minConsumptionTime">						
				</div>
			
				<button class="btn btn-default btn-block">提交</button>
			</form>
		</div>
		
		<script src="/js/vue.min.js"></script>
		<script src="/js/axios.min.js"></script>
		<script type="text/javascript">
			var vm = new Vue({
					el: '#p-body',
					data: {
						bankList:[],
						bank:'gd',
						ownerId:'',
						ownerList:[],
						cardNum:null,
						creditLimit:null,
						accountDate:null,
						repaymentDate:null,
						minConsumptionTime:null,
						date:[1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1],
						creditCardId:'<?=$id?>'
					},
					methods: {
						loadBankList(){
							axios({
								url:'api/getBankList.php',
								method: 'get',
								responseType: 'json',
								transformResponse: [function(res){
							//		console.log(res.data)								
									vm.bankList = res.data;
								}]
							});
						},
						getCardContent(){
							axios({
								url:'api/getCardContent.php',
								method: 'get',
								params:{id:'<?=$id?>'},
								responseType: 'json',
								transformResponse: [function(res){
								//	console.log(res.data)		
									vm.bank = res.data.bank;
									vm.cardNum = res.data.cardNum;
									vm.creditLimit = res.data.creditLimit;
									vm.ownerId = res.data.ownerId;
									vm.accountDate = res.data.accountDate;
									vm.repaymentDate = res.data.repaymentDate;
									vm.minConsumptionTime = res.data.minConsumptionTime;
								}]
							})
						},
						getOwnerList(){
							axios({
								url:'api/getOwnerList.php',
								method: 'get',
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res.data)		
									vm.ownerList = res.data;
								}]
							})
						},
						editCard(){
							let data = {bank:this.bank,cardNum:this.cardNum,creditLimit:this.creditLimit,accountDate:this.accountDate,repaymentDate:this.repaymentDate,minConsumptionTime:this.minConsumptionTime,creditCardId:this.creditCardId,ownerId:this.ownerId};
							
							let param = this.FormatData(data); 
							
						//	console.log(data);0
							axios({
								url:'api/editCard.php',
								method: 'POST',
								data:param,
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res)
									window.location.href = 'list.php?id='+vm.creditCardId;
								}]
							});
							
						},
						FormatData(data){
							let paramters = new FormData(); 
							for(var key in data){
								paramters.append(key,data[key])
							}
							return paramters;
						},
						formCheck(){
							$(".form").validate({
								rules : {
									cardNum : {
										required : true,
										number : true,
										digits : true,
										maxlength:4,
										minlength:4
									},
									minConsumptionTime : {
										required : true,
										number : true,
										digits : true
									},
									ownerId : {
										required : true,
									},
								},
								submitHandler: function(form){
									vm.editCard();
								}
							});
						}

					},
					mounted: function(){
						this.loadBankList();
						this.getCardContent();
						this.getOwnerList();
						this.formCheck();//绑定表单验证
						
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