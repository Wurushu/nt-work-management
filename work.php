<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	
	if(isset($_GET['edit_work']) && $_GET['edit_work'] == 1){
		if(isset($_GET['cancel'])){
			$cancel = $_GET['cancel'];
			$rs2 = $pdo->prepare("update `work` set `complete` = 0, `ps`='' where `id` = '$cancel'");
			$rs2->execute();
		}
		if(isset($_GET['complete'])){
			$complete = $_GET['complete'];
			$tw_year = intval(date('Y')) - 1911;
			$ps = '['.$tw_year.'/'.date('m/d').']';
			$rs = $pdo->prepare("update `work` set `complete` = 1, `ps`='$ps' where `id` = '$complete'");
			$rs->execute();
		}
		header('refresh: 0; url=work.php');
	}
	
	$sort = !empty($_GET['sort']) ? $_GET['sort'] : '';
	$asc = (!empty($_GET['asc']) && $_GET['asc'] == 'ASC') ? 'ASC' : 'DESC';
	$page = !empty($_GET['page']) ? $_GET['page'] : 1;
	$team_group = !empty($_GET['team_group']) ? 1 : 0;
	
	if($sort == 6){
		$orderby = "order by `complete` {$asc}, `overday`";
	}elseif($sort != ''){
		$orderby = "order by `". $sort_name_en[$sort] ."` {$asc}";
	}else{
		$orderby = 'order by `id` ASC';
	}
	
	$work_count = pdo_select("select count(`id`) as `c` from `work`")[0]['c'];
	$total_page = ceil($work_count / 30);
	$offset = 'offset ';
	if($work_count > 30){
		if($page != ''){
			$offset .= ($page - 1) * 30;
		}else{
			$offset .= '0';
		}
	}else{
		$offset .= '0';
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>
		$(function(){
			$(document).on('click','.work-cancel',work_cancel);
			<?php
				if(isset($_GET['edit_work']) && $_GET['edit_work'] == 1){
					if(!empty($_GET['complete'])){
						echo "$('.complete-animation').addClass('show');";
					}
					header('refresh: 1.5; url=work.php');
				}
			?>
		})
		function work_cancel(){
			var work_id = $(this).attr('value');
			if($(this).prop('checked')){
				$('input[name="cancel[]"][value="'+ work_id +'"]').remove();
			}else{
				$('.work-form').append('<input type="hidden" name="cancel[]" value="'+ work_id +'">');
			}
		}
		function board_submit(){
			var board_post = $('.board>.board-post').val();
			if(board_post == ''){ 
				alert('未填寫留言');
				return; 
			}
			$('.board>.board-post').val('');
			$('input.board-submit[type="button"]').css('visibility','hidden');
			$.ajax({
				url: 'ajax_board_post.php',
				type: 'post',
				data: {
					post: board_post,
				},
				success: function(r){
					$('.board>.board-content').prepend(r);
					$('input.board-submit[type="button"]').css('visibility','visible');
				}
			});
		}
		function board_del(board_id,ob){
			if(!confirm('確定要刪除?')){ return; }
			$(ob).parents('.board-one').remove();
			$.ajax({
				url: 'ajax_board_del.php',
				type: 'post',
				data: {
					id: board_id,
				}
			});
		}
	</script>
	<style>
		table a, table a:visited{
			color: #44b;
		}
		.button-comp{
			font-size: 11px;
			background-color: #3CB371;
		}
		.button-cancel{
			font-size: 7px;
			background-color: #4682B4;
			padding:1px 6px;
		}
		.fa.fa-check{
			font-size: 30px;
		}
		.complete-animation{
			display: none;
		}
		.complete-animation.show{
			display: block;
			position: absolute;
			left: 50%;
			top: 50%;
			color: #FFE4C4;
			transform: translate(-50%, -50%);
			animation: anime1 2s linear;
			z-index: 99;
		}
		@keyframes anime1{
			0%{
				opacity: .5;
				font-size: 10px;
			}
			30%{
				opacity: 1;
				font-size: 700px;
			}
			100%{
				opacity: .0;
				font-size: 10px;
			}
		}
		.board-post{
			width: 280px;
			height: 40px;
			font-size: 17px;
		}
		.board{
			float: left;  
			width: 300px;
			height: 800px;
			overflow: auto;
			border: .8px #666 solid;
			padding: 5px;
			margin-right: 10px;
			background-color: #fffdf5;
			margin-left: 10px;
		}
		.board-one{
			margin-top: 10px;
			padding: 10px;
			padding-top: 20px;
			border-top: 1px #999 solid;
			position: relative;
			font-size: 85%;
			width: 250px;
			word-break: break-all;
		}
		.board-one p.board-one-who{
			font-size: 80%;
			color: #aaa;
			margin: 5px 0 0 0;
			text-align: right;
		}
		.board-del{
			font-size: 85%;
			padding: 1px 5px 1px 5px;
		}
		.main-content{
			padding: 0;
		}
	</style>
</head>
<body>
	<?php include_once('header.php'); ?>
	<div><span class="fa fa-thumbs-up complete-animation"></span></div>
	<div class="main-content">
		<h2 class="title-2">工作事項</h2>
		<div class="align-center">
			<p>
				<input type="button" class="pure-button pure-button-primary" value="新增工作" onclick="location.href = 'add_work.php'">
			</p>
			<p>
				<?php 
					if($team_group){
						echo '<input type="button" class="pure-button button-xsmall pure-button-primary button-success" value="不分組" onclick="location.href = \'work.php\';">';
					}else{
						echo '<input type="button" class="pure-button button-xsmall pure-button-primary" value="分組顯示" onclick="location.href = \'work.php?team_group=1&sort='.$sort.'&asc='.$asc.'\';">';
					}
					
					if($_SESSION['rank'] == 1){
						echo ' <input type="button" class="pure-button button-xsmall pure-button-primary" value="即將逾期提醒" onclick="location.href = \'send_mail_all.php\'">';	
						echo ' <input type="button" class="pure-button button-xsmall pure-button-primary" value="分層負責工作" onclick="location.href = \'year_work.php\'">';
						echo ' <input type="button" class="pure-button button-xsmall pure-button-primary" value="回收桶" onclick="location.href = \'work_backup.php\'">';
					}
				?>
			</p>
		</div>
		<?php
			if($sort != ''){
		?>
				<p class="align-center">
					依照 「<span class="font-color-b"><?=$sort_name_zh[$sort]?></span>」 排序, 
					<span class="font-color-b">
					<?php
						if($asc == 'ASC'){
							echo '升序';
							echo ' <input class="pure-button pure-button-primary switch_asc" type="button" value="變降序" onclick="location.href = \'work.php?sort='.$sort.'&asc=DESC&team_group='.$team_group.'\';">';
						}else{
							echo '降序';
							echo ' <input class="pure-button pure-button-primary switch_asc" type="button" value="變升序" onclick="location.href = \'work.php?sort='.$sort.'&asc=ASC&team_group='.$team_group.'\';">';
						}
					?>
					</span>
				</p>
		<?php
			}
		?>
		<div class="work-div">
			<div class="board">
				<input type="text" class="board-post" placeholder="輸入留言">
				<div style="text-align: right; margin: 5px 0 20px 0;"><input type="button" class="board-submit" value="留言" onclick="board_submit()"></div>
				<div class="board-content">
					<?php
						foreach(pdo_select("select * from `board` order by `id` DESC limit 200") as $v){
							$user = pdo_select("select `name` from `user` where `id` = '".$v['user']."';")[0];
							$del = $_SESSION['id'] == $v['user'] ? '<input type="button" class="board-del pure-button button-error" value="刪除" onclick="board_del('.$v['id'].',this);">' : '';
							echo '<div class="board-one">'.$v['post'].'<p class="board-one-who">'.$del.'('.$v['date'].' '.$user['name'].')</p></div>';
						}
					?>
				</div>
			</div>
			<form method="post" class="work-form">
				<table class="pure-table pure-table-bordered table-work" align="center">
					<thead>
						<tr style="font-size: 20px; text-align: center;">
							<th width="60"><a href="work.php?sort=1&team_group=<?=$team_group?>">編號</a></th>
							<th width="35%">工作內容</th>
                            <th width="40">附件</th>
							<th width="80"><a href="work.php?sort=3&team_group=<?=$team_group?>">限辦日期</a></th>
							<th width="80"><a href="work.php?sort=2&team_group=<?=$team_group?>">發佈日期</a></th>
							<th><a href="work.php?sort=4&team_group=<?=$team_group?>">承辦人</a></th>
							<th><a href="work.php?sort=5&team_group=<?=$team_group?>">審核</a></th>
							<th><a href="work.php?sort=6&team_group=<?=$team_group?>">已完成</a></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($team_group){
								$user_select = pdo_select("select * from `user` where `rank` = 1 || `rank` = 2 order by `rank`");
								foreach($user_select as $k2 => $v2){
									$work_select = pdo_select("select * from `work` where `work_user` = '".$v2['id']."' || `work_user` in (select `id` from `user` where `rank` != 2 && `belong` = '".$v2['id']."') {$orderby} limit 30 {$offset}");
									foreach($work_select as $v){
										$work_user = pdo_select("select * from `user` where `id` = '". $v['work_user'] ."'");
										$order_user = pdo_select("select * from `user` where `id` = '". $v['order_user'] ."'");
										
										if(count($work_user) == 0){
											$work_user['name'] = '<span class="font-color-r">無承辦人</span>';
											$work_user['belong'] = '#$%@^#$%&^%*^&(*&)$*#^$!@#$@%##$^$%&&^#(%)';
										}else{
											$work_user = $work_user[0];
										}
						?>
										<tr>
											<td>
												<?php
													if($v['work_user'] == $_SESSION['id']){
														echo '<span style="font-size: 40px;" class="fa fa-angle-double-right font-color-b work-mine"></span>'.$v['id'];
													}else{
														echo $v['id'];
													}
												?>
											</td>
											<td>
												<?= $v['content']; ?>
											</td>
											<td>
												<?php
													if(count(pdo_select("select * from `file` where `work` = '". $v['id'] ."'")) == 0){
														echo '無';
													}else{
														echo '<a href="files.php?work='. $v['id'] .'" style="text-align: center;"><span class="fa fa-file" style="font-size: 30px;"></span></a>';	
													}
												?>
											</td>
											<td>
												<?php
													if($v['overday'] != '0000-00-00'){
														if(strtotime($v['overday']) - strtotime('now') < 0){
															echo '<span class="fa fa-exclamation font-color-r"> '. $v['overday'] .'<br>(已逾期)</span>';
														}elseif((strtotime($v['overday']) - strtotime('now')) < 259200 && $v['complete'] == 0){
															echo '<span class="fa fa-exclamation font-color-r"> '. $v['overday'] .'</span>';
														}else{
															echo $v['overday'];
														}
													}else{
														echo '無';
													}
												?>
											</td>
											<td><?=$v['post_time']?></td>
											<td><?=$work_user['name']?></td>
											<td>
												<?php
													if(count($order_user) != 0){
														echo $order_user[0]['name'];
													}
												?>
											</td>
											<td>
												<?php
													if($v['complete'] == 1){
														echo '<span style="font-size: 6px;">'.$v['ps'].'</span><br>';
														echo '<span class="button-xlarge"><span class="font-color-g fa fa-check"></span></span>';
														if($v['work_user'] == $_SESSION['id']){
															echo '<input type="button" class="pure-button button-cancel pure-button-primary" value="取消" onclick="if(confirm(\'確認取消?\')){location.href = \'work.php?edit_work=1&cancel='. $v['id'] .'\';};">';
														}
													}else{
														if($v['work_user'] == $_SESSION['id']){
														echo '<input type="button" class="pure-button button-comp pure-button-primary" value="完成！" onclick="if(confirm(\'確認已完成?\')){location.href = \'work.php?edit_work=1&complete='. $v['id'] .'\';};">';
														}
													}
												?>
											</td>
											<td>
												<?php 
													if($v['order_user'] == $_SESSION['id'] || $_SESSION['rank'] == 1){
												?>
														<input type="button" class="pure-button button-xsmall button-success" value="修改" onclick="location.href = 'edit_work.php?id=<?=$v['id']?>'"><br>
														<input type="button" class="pure-button button-xsmall button-error" value="刪除" onclick="if(confirm('是否要刪除?')){ location.href = 'del_work.php?id=<?=$v['id']?>'; }"><br>
												<?php 
													} 
												?>
												<?php
														if($v['complete'] == 0 && ($_SESSION['id'] == $work_user['belong'] || $_SESSION['rank'] == 1)){
												?>
															<input type="button" class="pure-button button-xsmall pure-button-primary button-warning" value="提醒" onclick="location.href = 'send_mail.php?id=<?=$v['id']?>'"><br>
												<?php
														}
												?>
											</td>
										</tr>
						<?php
									}
									if(count($work_select) > 0){
										echo '<tr style="background-color: #fff; border: 1px #fff solid; height: 80px;"><td colspan="9"></td></tr>';
									}
								}
							}else{
								$work_select = pdo_select("select * from `work` {$orderby} limit 30 {$offset}");
								foreach($work_select as $v){
									$work_user = pdo_select("select * from `user` where `id` = '". $v['work_user'] ."'");
									$order_user = pdo_select("select * from `user` where `id` = '". $v['order_user'] ."'");
								
									if(count($work_user) == 0){
										$work_user['name'] = '<span class="font-color-r">無承辦人</span>';
										$work_user['belong'] = '#$%@^#$%&^%*^&(*&)$*#^$!@#$@%##$^$%&&^#(%)';
									}else{
										$work_user = $work_user[0];
									}
						?>		
									<tr>
										<td>
											<?php
												if($v['work_user'] == $_SESSION['id']){
													echo '<span style="font-size: 40px;" class="fa fa-angle-double-right font-color-b work-mine"></span>'.$v['id'];
												}else{
													echo $v['id'];
												}
											?>
										</td>
										<td>
											<?= $v['content']; ?>
										</td>
										<td>
											<?php
												if(count(pdo_select("select * from `file` where `work` = '". $v['id'] ."'")) == 0){
													echo '無';
												}else{
													echo '<a href="files.php?work='. $v['id'] .'" style="text-align: center;"><span class="fa fa-file" style="font-size: 30px;"></span></a>';	
												}
											?>
										</td>
										<td>
											<?php
												if($v['overday'] != '0000-00-00'){
													if(strtotime($v['overday']) - strtotime('now') < 0){
														echo '<span class="fa fa-exclamation font-color-r"> '. $v['overday'] .'<br>(已逾期)</span>';
													}elseif((strtotime($v['overday']) - strtotime('now')) < 259200 && $v['complete'] == 0){
														echo '<span class="fa fa-exclamation font-color-r"> '. $v['overday'] .'</span>';
													}else{
														echo $v['overday'];
													}
												}else{
													echo '無';
												}
											?>
										</td>
										<td><?=$v['post_time']?></td>
										<td><?=$work_user['name']?></td>
										<td>
											<?php
												if(count($order_user) != 0){
													echo $order_user[0]['name'];
												}
											?>
										</td>
										<td>
											<?php
												if($v['complete'] == 1){
													echo '<span style="font-size: 6px;">'.$v['ps'].'</span><br>';
													echo '<span class="button-xlarge"><span class="font-color-g fa fa-check"></span></span>';
													if($v['work_user'] == $_SESSION['id']){
														echo '<input type="button" class="pure-button button-cancel pure-button-primary" value="取消" onclick="if(confirm(\'確認取消?\')){location.href = \'work.php?edit_work=1&cancel='. $v['id'] .'\';};">';
													}
												}else{
													if($v['work_user'] == $_SESSION['id']){
													echo '<input type="button" class="pure-button button-comp pure-button-primary" value="完成！" onclick="if(confirm(\'確認已完成?\')){location.href = \'work.php?edit_work=1&complete='. $v['id'] .'\';};">';
													}
												}
											?>
										</td>
										<td>
											<?php 
												if($v['order_user'] == $_SESSION['id'] || $_SESSION['rank'] == 1){
											?>
													<input type="button" class="pure-button button-xsmall button-success" value="修改" onclick="location.href = 'edit_work.php?id=<?=$v['id']?>'"><br>
													<input type="button" class="pure-button button-xsmall button-error" value="刪除" onclick="if(confirm('是否要刪除?')){ location.href = 'del_work.php?id=<?=$v['id']?>'; }"><br>
											<?php 
												} 
											?>
											<?php
													if($v['complete'] == 0 && ($_SESSION['id'] == $work_user['belong'] || $_SESSION['rank'] == 1)){
											?>
														<input type="button" class="pure-button button-xsmall pure-button-primary button-warning" value="提醒" onclick="location.href = 'send_mail.php?id=<?=$v['id']?>'"><br>
											<?php
													}
											?>
										</td>
									</tr>
						<?php
								}
							}
						?>
					</tbody>
				</table>
				<div>
					<div class="align-center work-page-number">
						<?php
							if($total_page > 1 && !$team_group){
								for($i=1; $i<=$total_page; $i++){
									if($i == $page){
										echo '<span style="font-size: 30px;" class="font-color-r">'.$i.'</span> ';
									}else{
										echo '<a href="work.php?sort='.$sort.'&asc='.$asc.'&page='.$i.'">'.$i.'</a> ';
									}
								} 
							}
 							
						?>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php include('footer.php') ?>
</body>
</html>