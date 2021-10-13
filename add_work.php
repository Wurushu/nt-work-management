<?php
	$rank_only = array(1,2,3);
	$history_back = 'work.php';
	include_once("_conf.php");
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>
		$(function(){
			$(document).on('click','.add-file',add_file);
			$(document).on('click','.remove-file',remove_file);
			$('#loading').hide();
		})
		function add_file(){
			var count = Number($('#file_count').val());
			$('#file_count').val(++	count);
			var file = '<div class="pure-control-group"><label>附件:</label><input type="file" name="file[]" class="form-file"> <span class="fa fa-close remove-file file-'+count+'" style="cursor: pointer;"></span></div>';
			$('.all-file').append(file);
		}
		function remove_file(){
			var count = Number($('#file_count').val());
			
			$(this).parent().remove();
			$('#file_count').val(--count);
		}
		function upload(){
			var pass = true;
			var d1 = $('#content').val();
			var d2 = $('#overday').val();
			var d3 = $('#work_user').val();
			var work_id = '';
			$('#loading').fadeIn(700);
			
			window.setTimeout(function(){
				
				$.ajax({
					url: 'ajax_work_add.php',
					type: 'POST',
					async: false,
					data: {
						content: d1,
						overday: d2,
						work_user: d3
					},
					success: function(re){
						var json_test = false;
						try{
							re_json = JSON.parse(re);
							json_test = true;
						}catch(e){}
						if(json_test == true && re_json.status == 'no'){
							pass = 2;
						}else{
							work_id = re_json.id;
							$('input[name="file[]"]').each(function(index, value){
								var file = new FormData();
								file.append('file',$(value)[0].files[0]);
								file.append('work_id',work_id);
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
						}
						$('.sk-circle-content').text('完成!!!!!!!!!');
						$('.sk-circle .sk-child').addClass('done');
					}
				});
			
			}, 1000);
			window.setTimeout(function(){
				$('#loading').hide();
				
				if(pass == 2){
					alert(re_json.msg);
				}else if(pass == 3){
					alert('部分檔案上傳失敗，請於「修改」頁面查看');
					location.href = 'work.php';
				}else{
					location.href = 'work.php';
				}
			}, 1000);

		}
	</script>
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
		<h2 class="title-2">新增工作事項</h1>
		<form class="pure-form pure-form-aligned" method="post" enctype="multipart/form-data" onsubmit="upload(); return false;">
			<fieldset>
				<div class="pure-control-group">
					<label for="content">工作內容</label>
					<textarea id="content" name="content" placeholder="內容" style="width: 45%;height: 200px; resize: none;" required></textarea>
				</div>
				<div class="pure-control-group">
					<label for="overday">限辦日期(可不填)</label>
					<input id="overday" name="overday" type="date" placeholder="期限">
				</div>
				<div class="pure-control-group">
					<label for="work_user">承辦人</label>
					<select name="work_user" id="work_user">
						<option value="<?=$_SESSION['id']?>">自己</option>
						<?php
							foreach(pdo_select("select * from `user` where `team`='". $_SESSION['team'] ."' && `rank`=3 order by `rank`") as $v){
								echo '<option value="'. $v['id'] .'" style="font-size: 18px;">'. $v['name'] .'('. $rank_name[$v['rank']] .')</option>';
							}
						?>
					</select>
				</div><br>
				<div class="all-file"></div>
				<div class="pure-controls">
					<button type="submit" class="pure-button pure-button-primary">新增</button>
					<button type="button" class="pure-button button-xsmall pure-button-primary add-file">新增附件<br><span style="font-size: 0.8em;">(單一檔案限200MB)</span></button>
					<input type="hidden" name="add" value="1">
					<input type="hidden" id="file_count" name="file_count" value="0">
				</div>
			</fieldset>
		</form>
	</div>
	<?php include('footer.php') ?>
</body>
</html>