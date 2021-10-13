<?php
	$rank_only = array(1,2,3);
	$history_back = 'work.php';
	include_once("_conf.php");
	
	$work_id = $_GET['id'];
	if(empty($_GET['id'])){
		echo '<script>history.back();</script>';
	}
	if(isset($_POST['edit'])){
		$work_id = $_POST['work_id'];
		$content = nl2br(htmlentities($_POST['content']));
		$overday = $_POST['overday'];
		$work_user = $_POST['work_user'];
		$post_time = date('Y-m-d');
		
		if($overday != '' && strtotime($overday) <= (strtotime('now')-86400)){
			echo '<script>alert("完成日期不得於現在之前");</script>';
			header('refresh: 0;');
		}else{
			$rs = $pdo->prepare("update `work` set `content`='$content', `overday`='$overday', `work_user`='$work_user', `post_time`='$post_time' where `id` ='$work_id';");
			$rs->execute();
			header('location: work.php');
		}
	}else{
		$work_or = pdo_select("select * from `work` where `id` = '$work_id';")[0];
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>

		$(function(){
			$('select[name="work_user"]>option[value="<?=$work_or['work_user']?>"]').prop('selected',true);
			$(document).on('click','.add-file',add_file);
			$(document).on('click','.remove-file',remove_file);
			$(document).on('click','.remove-file2',remove_file2);
			$('#loading').hide();
			
			var content_txt = $('textarea[name="content"]').val();
			$('textarea[name="content"]').val($('textarea[name="content"]').val().replace(/<br \/>/g,'\r'));
		})
		function add_file(){
			var count = Number($('#file_count').val());
			$('#file_count').val(++count);
			var file = '<div class="pure-control-group"><label>附件:</label><input type="file" name="file[]" class="form-file"> <span class="fa fa-close remove-file file-'+count+'" style="cursor: pointer;"></span></div>';
			$('.all-file').append(file);
		}
		function remove_file(){
			var count = Number($('#file_count').val());
			
			$(this).parent().remove();
			$('#file_count').val(--count);
		}
		function remove_file2(){
			if(confirm('確定要刪除該檔案，此動作無法復原')){
				var file_id = $(this).data('file');
				$.ajax({
					url: 'ajax_file_del.php',
					type: 'POST',
					async: false,
					data: {
						file_id: file_id,
					}
				})
				$(this).parent().remove();
			}
		}
		function upload(){
			var pass = true;
			var d1 = $('#content').val();
			var d2 = $('#overday').val();
			var d3 = $('#work_user').val();
			var work_id = '';
			$('#loading').fadeIn(700);
			
			setTimeout(function(){
				$('input[name="file[]"]').each(function(index, value){
					var file = new FormData();
					file.append('file',$(value)[0].files[0]);
					file.append('work_id','<?=$work_id?>');
					$.ajax({
						url: 'ajax_file_upload.php',
						type: 'POST',
						data: file,
						async: false,
						contentType: false,
						cache: false,
						processData: false,
						success: function(re2){
							var json_test = false;
							try{
								re2_json = JSON.parse(re2);
								json_test = true;
							}catch(e){}
							if(json_test == false){
								pass = 3;
							}else if(re2_json.status == 'no'){
								pass = 3;
							}
						}
					});
				});
				if(pass == 3){
					alert('部分檔案上傳失敗，請於「修改」頁面查看');
				}
				$('#edit_work_form').submit();
			},1000);
		}

	</script>
	<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
	<script>
		webshims.setOptions('forms-ext', {types: 'date'});
		webshims.polyfill('forms forms-ext');
	</script>
	<style>
		.ws-popover-opener{
			display: none;
		}
	</style>
</head>
<body style="height: 1000px;">
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<div id="loading" style="display:none;">
			<div class="sk-circle">
				<div class="sk-circle1 sk-child"></div>
				<div class="sk-circle2 sk-child"></div>
				<div class="sk-circle3 sk-child"></div>
				<div class="sk-circle4 sk-child"></div>
				<div class="sk-circle5 sk-child"></div>
				<div class="sk-circle6 sk-child"></div>
				<div class="sk-circle7 sk-child"></div>
				<div class="sk-circle8 sk-child"></div>
				<div class="sk-circle9 sk-child"></div>
				<div class="sk-circle10 sk-child"></div>
				<div class="sk-circle11 sk-child"></div>
				<div class="sk-circle12 sk-child"></div>
			</div>
			<div class="sk-circle-content">上傳中，請稍後...</div>
		</div>
		<h2 class="title-2">修改工作事項</h1>
		<form id="edit_work_form" class="pure-form pure-form-aligned" method="post">
			<fieldset>
				<div class="pure-control-group">
					<label for="content">工作內容</label>
					<textarea id="content" name="content" placeholder="內容" style="width: 45%;height: 200px; resize: none;" required><?=$work_or['content']?></textarea>
				</div>
				<div class="pure-control-group">
					<label for="overday">限辦日期(可不填)</label>
					<input id="overday" name="overday" type="date" placeholder="期限" value="<?=$work_or['overday']?>">
				</div>
				<div class="pure-control-group">
					<label for="work_user">承辦人</label>
					<select name="work_user" id="work_user">
						<option value="<?=$_SESSION['id']?>">自己</option>
						<?php
							foreach(pdo_select("select * from `user` where ((`team`='". $_SESSION['team'] ."' && `rank`=3) || ". $_SESSION['rank'] ."=1) && `rank`!=1 order by `rank`") as $v){
								echo '<option value="'. $v['id'] .'">'. $v['name'] .'('. $rank_name[$v['rank']] .')</option>';
							}
						?>
					</select>
				</div>
				<div class="all-file">
					<?php
						foreach(pdo_select("select * from `file` where `work` = '$work_id'") as $v){
					?>
							<div class="pure-control-group">
								<label>附件:</label>
								<span style="border: 1px #bbb solid; padding: 3px 15px;"><?=urldecode($v['name'])?></span>
								<span class="fa fa-close remove-file2" style="cursor: pointer;" data-file="<?=$v['id']?>">
								</span>
							</div>
					<?php
						}
					?>
				</div>
				<div class="pure-controls">
					<button type="button" class="pure-button pure-button-primary" onclick="upload();">確認修改</button>
					<button type="button" class="pure-button button-xsmall pure-button-primary add-file">新增附件<br><span style="font-size: 0.8em;">(單一檔案限200MB)</span></button>
					<input type="hidden" name="edit" value="1">
					<input type="hidden" name="work_id" value="<?=$work_or['id']?>">
					<input type="hidden" id="file_count" name="file_count" value="0">
				</div>
			</fieldset>
		</form>
	</div>
	<?php include('footer.php') ?>
</body>
</html>