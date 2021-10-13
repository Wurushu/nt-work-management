<?php
	$rank_only = array('n',1,2,3);
	include_once("_conf.php");

	$passed = true;
	if(isset($_SESSION['user'])){
		unset($_SESSION['user'],$_SESSION['rank']);
	}
	if(isset($_POST['login']) && $_POST['login'] == 1){
		$user = $_POST['user'];
		$pd = hash('sha256',$_POST['pd']);
		
		$rs = pdo_select("select * from `user` where `user` = '$user' && `pd` = '$pd'");
		if(count($rs) == 0){
			$passed = false;
		}
		
		if($passed){
			$_SESSION['id'] = $rs[0]['id'];
			$_SESSION['user'] = $user;
			$_SESSION['name'] = $rs[0]['name'];
			$_SESSION['rank'] = $rs[0]['rank'];
			$_SESSION['team'] = $rs[0]['team'];
			
			header("location: work.php");
		}else{
			header('refresh: 0;');
		}
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
</head>
<body>
	<h1 class="title-1"><a href="index.php">NT Practice System<br><p class="work-manage-title">Work Management</p></a></h1>
	<hr>
	<div class="main-content">
		<div class="align-center"><input type="button" class="pure-button button-small pure-button-primary" value="註冊" onclick="location.href = 'register_user.php'"></div><br>
		<div class="login-div">
			<form method="post" action="index.php" class="pure-form pure-form-stacked">
				<fieldset>
					<legend>登入以進入系統</legend>
					<div>
						<label>帳號</label>
						<input type="text" name="user" placeholder="username" autofocus required>
					</div>
					
					<div>
						<label>密碼</label>
						<input type="password" name="pd" placeholder="password" required>
					</div>
					<div>
						<input type="submit" class="pure-button pure-button-primary" value="登入">
						<input type="hidden" name="login" value="1">
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<p class="align-center font-color-r" style="font-size: 70%; transform: translateY(80px)">(若無法登入，請檢查瀏覽器Cookie功能是否開啟)</p>
	<?php include('footer.php') ?>
</body>
</html>