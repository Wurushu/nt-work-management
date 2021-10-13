<?php
	$rank_only = array(1,2);
	$history_back = 'work.php';
	include_once("_conf.php");
	
	$work_id = $_GET['id'];
	$work = pdo_select("select * from `work` where `id` = '$work_id'")[0];
	$user = pdo_select("select * from `user` where `id` = '".$work['work_user']."'")[0];
	
	if(!empty($_POST['send'])){
		$work_user = $_POST['work_user'];
		$to = pdo_select("select `email` from `user` where `id` = '$work_user'")[0]['email'];
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
		$mail->addAddress($to); 

		$mail->Subject = $title;
		$mail->Body    = $content;

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
			var mail = '您好，您的工作項目：\n\n<?=$work['content']?>\n\n';
			<?php
				if($work['overday'] == '0000-00-00'){
					echo 'mail += "尚未完成。";';
				}else{
					echo 'mail += "限辦日期為：'.$work['overday'].'。";';
				}
			?>
			$('textarea[name="content"]').val(mail);
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
						<span><?=$user['name']?></span>
					</div>
					<hr style="margin-top: 0">
					<div class="pure-control-group">
						<label>標題:</label>
						<input type="text" name="title" autofocus style="border:none;outline:none;margin:0;padding:0;box-shadow:none;width:100%;" value="NT Work">
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
				<input name="work_user" type="hidden" value="<?=$work['work_user']?>">
			</form>
		</div>
	</div>
	<?php include('footer.php') ?>
</body>
</html>