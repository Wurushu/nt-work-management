<?php
	$rank_only = array(1);
	include_once("_conf.php");
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
</head>
<body>
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">人員管理</h1>
		<div class="align-center"><input type="button" class="pure-button pure-button-primary" value="新增人員" onclick="location.href = 'add_user.php'"></div><br>
		<div class="manage-user-div">
			<table class="pure-table pure-table-bordered table-user" align="center">
				<thead>
					<tr style="font-size: 20px; text-align: center;">
						<th>帳號</th>
						<th>姓名</th>
						<th>email</th>
						<th>職位</th>
						<!-- <th>上級人員</th> -->
						<th>組別</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach(pdo_select("select * from `user` order by `team`, `rank`") as $v){ ?>
						<tr>
							<td><?=$v['user']?></td>
							<td><?=$v['name']?></td>
							<td><?=$v['email']?></td>
							<td><?=$rank_name[$v['rank']]?></td>
							<!--
							<td><?php
								// $belong = pdo_select("select `name` from `user` where `id` = '". $v['belong'] ."'");
								// if(count($belong) != 0){
									// echo $belong[0]['name'];
								// }
							?></td>
							-->
							<td><?php
								echo (!empty($user_team[$v['team']])) ? $user_team[$v['team']] : '';
								// echo '<pre>';
								// var_dump($v);
								// echo '</pre>';
							?></td>
							<td>
								<input type="button" class="pure-button button-small button-success" value="修改" onclick="location.href = 'edit_user.php?id=<?=$v['id']?>'">
								<input type="button" class="pure-button button-small button-error" value="刪除" onclick="if(confirm('是否要刪除此人員')){ location.href = 'del_user.php?id=<?=$v['id']?>' }">
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php include('footer.php') ?>
</body>
</html>