<?php
	include("../conf/conn.php");
	$user=$_SESSION['user'];
	if(!empty($user)){
?>
<!DOCTYPE html>
<html>
	<head>
		<title>新增信用卡</title>
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
							<a class="navbar-brand" href="mgmt.php">
								<span class="glyphicon glyphicon-chevron-left"></span>
							</a>
						</div>
						<h4>新增信用卡</h4>
					</div>
					
				</div>
			</nav>
			<form id="content" onsubmit="return false">
			
				<div class="form-group">
					<label for="bank">银行名称</label>
					<select class="form-control" id="bank" name="bank" v-model="bank">
						<option v-for="rs in bankList" :value='rs.bankId'>{{rs.bankName}}</option>
					</select>
				</div>
				<div class="form-group">
					<label for="cardNum">卡号后4位</label>
					<input type="text" class="form-control" id="cardNum" name="cardNum" placeholder="请输入卡号后4位" v-model="cardNum">
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
						cardNum:null,
						accountDate:1,
						repaymentDate:1,
						date:[1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1,2,3,4,5,1],
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
						addCard(){
							let data = {bank:this.bank,cardNum:this.cardNum,accountDate:this.accountDate,repaymentDate:this.repaymentDate};
							
							let param = this.FormatData(data); 
							
						//	console.log(data);0
							axios({
								url:'api/newcard.php',
								method: 'POST',
								data:param,
								responseType: 'json',
								transformResponse: [function(res){
									console.log(res)
									window.location.href = 'mgmt.php'
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
							$("#content").validate({
								rules : {
									cardNum : {
										required : true,
										number : true,
										digits : true,
										maxlength:4,
										minlength:4
									}
								},
								submitHandler: function(form){
									vm.addCard();
								}
							});
						}

					},
					mounted: function(){
						this.loadBankList();
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