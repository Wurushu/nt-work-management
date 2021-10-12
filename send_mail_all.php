<?php
	$rank_only = array(1,2);
	include_once("_conf.php");
	
	if(!empty($_POST['send'])){
		$title = $_POST['title'];
		$content = $_POST['content'];
		
		include('mailer/PHPMailerAutoload.php');

		$mail = new PHPMailer;

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'test_send@gmail.com';                 // SMTP username
		$mail->Password = 'test_send_password';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to
		$mail->CharSet = "utf-8";                       
		$mail->Encoding = "base64";
		
		$mail->setFrom('test_send@gmail.com', 'test');
		
		foreach($_POST['send_user'] as $v){
			$mail->addAddress(pdo_select("select `email` from `user` where `id` = '{$v}'")[0]['email']);
		}
		
		$mail->Subject = $title;
		$mail->Body = $content;

		if(!$mail->send()) {
			echo '<script>alert("email地址或其他未知錯誤");</script>';
			header('refresh: 0;');
		} else {
			header('location: work.php');
		}
		
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<script>
		$(function(){
			var mail = '您好，這是一封提醒的郵件。\n您的工作項目即將逾期。';
			$('textarea[name="content"]').val(mail);
			$('input[name="send_user[]"]').on('change',function(){
				if($('input[name="send_user[]"]:checked').length == 0){
					$(this).prop('checked',true);
					alert('必須有收件人');
				}
			});
		})
		function sendmail(){
			var content = $('textarea[name="content"]').val();
		}
	</script>
</head>
<body>
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">寄件提醒</h2>
		<div class="mail-div">
			<form class="pure-form pure-form-stacked" method="post" onsubmit="return sendmail();">
				<fieldset>
					<div class="pure-control-group">
						<label>收件人:</label>
						<?php
							$last_user = 'gggggg';
							$user_count = 0;
							foreach(pdo_select("select * from `work` order by `work_user`") as $v){
								$work_user = pdo_select("select * from `user` where `id` = '". $v['work_user'] ."'")[0];
								$overday = $v['overday'];
								if($v['overday'] == '0000-00-00' || (strtotime($v['overday']) - strtotime('now')) > 259200 || $v['complete'] != 0){
									continue;
								}
								if($last_user == $work_user['id']){
									continue;
								}
								$last_user = $work_user['id'];
						?>		
									<span style="margin-right: 20px;"><label style="display: inline-block;"><input type="checkbox" name="send_user[]" checked value="<?=$work_user['id']?>"><?=$work_user['name']?></label></span>
						<?php						
								$user_count++;
							}
							if($user_count == 0){
								echo '<script>alert("無將到期工作");history.back();</script>';
							}
						?>
					</div>
					<hr style="margin-top: 0">
					<div class="pure-control-group">
						<label>標題:</label>
						<input type="text" name="title" style="border:none;outline:none;margin:0;padding:0;box-shadow:none;width:100%;" value="NT Work">
					</div>
					<hr style="margin-top: 0">
					<div class="pure-control-group">
						<label>內容:</label>
						<textarea name="content" style="width: 500px;height: 300px; resize: none;"></textarea>
					</div>
				</fieldset>
				<div class="pure-controls">
					<button type="submit" class="pure-button pure-button-primary">寄送</button>
					<input type="hidden" name="send" value="1">
				</div>
			</form>
		</div>
	</div>
	<?php include('footer.php') ?>
</body>
</html>