<?php
	$rank_only = array(1);
	include_once("_conf.php");

	$id = $_GET['id'];

	if(isset($_POST['edit'])){
		$user = $_POST['user'];
		$or_user = $_POST['or_user'];
		$name = $_POST['name'];
		$pd = trim($_POST['pd']) == '' ? '' : ' `pd`=\''.hash('sha256',$_POST['pd']).'\',';
		$email = $_POST['email'];
		$rank = $_POST['rank'];
		$belong = $_POST['belong'];
		
		if($rank == 2){
			$belong = pdo_select("select `id` from `user` where `rank` = 1")[0]['id'];
		}
		
		if(isset($_POST['rank_1']) && $_POST['rank_1'] == 1){
			$belong = '';
			$rank = 1;
		}
		
		if($user == $or_user || count(pdo_select("select * from `user` where `user` = '$user';")) < 1){
			$rs = $pdo->prepare("update `user` set `user`='$user',$pd `name`='$name', `email`='$email', `rank`='$rank', `belong`='$belong' where `id` ='$id';");
			$rs->execute();
			if(isset($_POST['rank_1']) && $_POST['rank_1'] == 1){
				echo "<script>alert('需重新登入');</script>";	
				header('refresh: 0; url=index.php');
			}else{
				header('location: manage_user.php');
			}
		}else{
			echo "<script>alert('此帳號已存在');</script>";
			header('refresh: 0;');
		}
	}else{
		$or = pdo_select("select * from `user` where `id` = '$id';")[0];
		$user = $or['user'];
		$name = $or['name'];
		$email = $or['email'];
		$rank = $or['rank'];
		$belong = $or['belong'];		
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>
		$(function(){
			$('#rank').on('change',change_rank);
			$('#belong option[value="<?=$belong?>"]').prop('selected',true);
			<?php 
				if($rank == 2 || $rank == 1){
					echo "$('#belong').hide();";
				}
			?>
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
		<h2 class="title-2">修改人員</h1>
		<form class="pure-form pure-form-aligned" method="post">
			<fieldset>
				<div class="pure-control-group">
					<label for="user">帳號</label>
					<input id="user" name="user" type="text" placeholder="username" value="<?=$user?>" required>
				</div>
				<div class="pure-control-group">
					<label for="pd">新密碼(可不填)</label>
					<input id="pd" name="pd" type="password" placeholder="可不填">
				</div>
				<div class="pure-control-group">
					<label for="email">email</label>
					<input id="email" name="email" type="text" placeholder="email" value="<?=$email?>" required>
				</div>
				<div class="pure-control-group">
					<label for="name">姓名</label>
					<input id="name" name="name" type="text" value="<?=$name?>" placeholder="name" required>
				</div>
				<?php if($rank != 1){ ?>
					<div id="rank" class="pure-control-group">
						<label for="rank">職位</label>
						<select name="rank">
							<option value="3" <?php if($rank == 3){ echo 'selected'; } ?>>組員</option>
							<option value="2" <?php if($rank == 2){ echo 'selected'; } ?>>組長</option>
						</select>
					</div>
				<?php }else{ ?>
					<input name="rank_1" type="hidden" value="1">
				<?php } ?>
				<div id="belong" class="pure-control-group">
					<label for="belong">組長為</label>
					<select name="belong">
						<?php
							foreach(pdo_select("select * from `user` where `rank` = 2 and `id` != '$id'") as $v){
								echo '<option value="'. $v['id'] .'">'. $v['name'] .'</option>';
							}
						?>
					</select>
				</div>
				<div class="pure-controls">
					<button type="submit" class="pure-button pure-button-primary">確認修改</button>
					<input type="hidden" name="edit" value="1">
					<input type="hidden" name="or_user" value="<?=$user?>">
				</div>
			</fieldset>
		</form>
	</div>
	<?php include('footer.php') ?>
</body>
</html>