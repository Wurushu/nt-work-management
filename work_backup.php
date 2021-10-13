<?php
	$rank_only = array(1,2,3);
	$history_back = 'work.php';
	include_once("_conf.php");
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
</head>
<body style="height: 120%;">
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">回收桶</h2>
		<table class="pure-table pure-table-bordered table-files" align="center">
			<thead>
				<tr>
					<th>內容</th>
					<th>刪除者</th>
					<th>刪除日期</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach(pdo_select("select * from `work` where `dead` != 0 && `dead` != '' order by `dead` DESC") as $v){
						$work_user = pdo_select("select `name` from `user` where `id` = '". $v['dead_user'] ."'");
						if(count($work_user) == 0){
							$work_user_name = '';
						}else{
							$work_user_name = $work_user[0]['name'];
						}
				?>
						<tr>
							<td><?=mb_substr(str_replace('<br />','',$v['content']),0,10,'utf-8') . ' ....'?></td>
							<td><?=$work_user_name?></td>
							<td><?=$v['dead']?></td>
							<td><?php if($v['dead_user'] == $_SESSION['id']){ ?>
								<input type="button" class="pure-button button-success" style="font-size: 70%;" value="復原" onclick="location.href = 'work_backup_resume.php?id=<?=$v['id']?>';">
								<input type="button" class="pure-button button-error" style="font-size: 45%; vertical-align: bottom;" value="永久刪除" onclick="if(confirm('確定要刪除嗎? 此動作無法復原')){ location.href = 'work_backup_del.php?id=<?=$v['id']?>'; }">
							<?php } ?></td>
						</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
	<?php include('footer.php') ?>
</body>
</html>