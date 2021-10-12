<?php
	$rank_only = array(1);
	include_once("_conf.php");
	
	if(isset($_POST['add'])){
		$user = $_POST['user'];
		$pd = hash('sha256',$_POST['pd']);
		$name = $_POST['name'];
		$email = $_POST['email'];
		$rank = $_POST['rank'];
		$belong = $_POST['belong'];
		
		if($rank == 2){
			$belong = pdo_select("select `id` from `user` where `rank` = 1")[0]['id'];
		}
		
		if(count(pdo_select("select * from `user` where `user` = '$user';")) < 1){
			$rs = $pdo->prepare("insert into `user`(`user`,`pd`,`name`,`email`,`rank`,`belong`) values('$user','$pd','$name','$email','$rank','$belong');");
			$rs->execute();
			header('location: manage_user.php');
		}else{
			echo "<script>alert('此帳號已存在');</script>";
			header('refresh: 0;');
		}
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>
		$(function(){
			$('#rank').on('change',change_rank);
		})
		function change_rank(){
			if($('#rank>select[name="rank"]').val() == '2'){
				$('#belong').hide();
			}else{
				$('#belong').show();
			}
		}
	</script>
</head>
<body>
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">新增人員</h1>
		<form class="pure-form pure-form-aligned" method="post">
			<fieldset>
				<div class="pure-control-group">
					<label for="user">帳號</label>
					<input id="user" name="user" type="text" placeholder="username" required>
				</div>
				<div class="pure-control-group">
					<label for="pd">密碼</label>
					<input id="pd" name="pd" type="password" placeholder="password" required>
				</div>
				<div class="pure-control-group">
					<label for="email">email</label>
					<input id="email" name="email" type="email" placeholder="email" required>
				</div>
				<div class="pure-control-group">
					<label for="name">姓名</label>
					<input id="name" name="name" type="text" placeholder="name" required>
				</div>
				<div id="rank" class="pure-control-group">
					<label for="rank">職位</label>
					<select name="rank">
						<option value="3" selected>組員</option>
						<option value="2">組長</option>
					</select>
				</div>
				<div id="belong" class="pure-control-group">
					<label for="belong">組長為</label>
					<select name="belong">
						<?php
							foreach(pdo_select("select * from `user` where `rank` = 2") as $v){
								echo '<option value="'. $v['id'] .'">'. $v['name'] .'</option>';
							}
						?>
					</select>
				</div>
				<div class="pure-controls">
					<button type="submit" class="pure-button pure-button-primary">新增</button>
					<input type="hidden" name="add" value="1">
				</div>
			</fieldset>
		</form>
	</div>
	<?php include('footer.php') ?>
</body>
</html>