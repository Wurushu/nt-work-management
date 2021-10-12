<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	
	if(!empty($_POST['add'])){
		foreach($_POST['content'] as $k=>$v){
			$rs = $pdo->prepare("insert into `work`(`content`,`overday`,`work_user`,`order_user`) values('".$v."','".$_POST['overday'][$k]."','".$_POST['work_user'][$k]."','".$_POST['order_user'][$k]."');");
			$rs->execute();
		}
		
		$fp3 = fopen($year_path, 'w+');
		fwrite($fp3,date('Y'));
		fclose($fp3);
		
		header('location: work.php');
	}else{	
		$fp = fopen($year_path, 'r+');
		$fp2 = fopen($work_path, 'r+');
		$year = fgets($fp);
		
		$rank_users[1] = pdo_select("select `id`, `name` from `user` where `rank` = 1;");
		$rank_users[2] = pdo_select("select `id`, `name` from `user` where `rank` = 2;");
		$rank_users[3] = pdo_select("select `id`, `name` from `user` where `rank` = 3;");
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>
	</script>
</head>
<body>
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">年度工作</h1>
		<p class="align-center"><span class="font-color-b">提示</span>：日期及人員日後皆可修改，不一定要在此指定</p>
		<form class="pure-form pure-form-aligned" method="post">
			<table class="pure-table pure-table-bordered table-work" align="center">
				<thead>
					<tr style="height: 50px; font-size: 25px;">
						<th>工作內容</th>
						<th>完成日期</th>
						<th>擬辦</th>
						<th>審核</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(date('Y') != $year || !empty($_GET['again'])){
							while($work = fgets($fp2)){
								$work = explode(',,,',trim($work));
								if(count($work) != 3){ 
									echo '<tr><td colspan="4" style="background-color: #eee;">'.$work[0].'</td></tr>';
									continue; 
								}
								if(($work[1] != 1 && $work[1] != 2 && $work[1] != 3) || ($work[2] != 1 && $work[2] != 2 && $work[2] != 3)){
									continue;
								}
					?>
								<tr>
									<td><?=$work[0]?><input type="hidden" name="content[]" value="<?=$work[0]?>"></td>
									<td><input type="date" value="0000-00-00" name="overday[]"></td>
									<td>
										<select name="work_user[]" style="font-size: 16px; height: 40px;">
											<?php
												foreach($rank_users[$work[1]] as $v){
													echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
												}
											?>
										</select>
									</td>
									<td>
										<select name="order_user[]" style="font-size: 16px; height: 40px;">
											<?php
												foreach($rank_users[$work[2]] as $v){
													echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
												}
											?>
										</select>
									</td>
								</tr>
					<?php
							}
						}else{
					?>
							<script>
								if(confirm('今年度已新增完成 是否要再次新增')){
									location.href = 'year_work.php?again=true';
								}else{
									history.back();
								}
							</script>
					<?php
						}
					?>
					<tr>
						<td colspan="4" class="align-center">
							<input type="submit" class="pure-button pure-button-primary" style="font-size: 170%;" value="送出">
							<input type="hidden" name="add" value="1">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<?php include('footer.php') ?>
</body>
</html>
<?php
	fclose($fp);
	fclose($fp2);
?>