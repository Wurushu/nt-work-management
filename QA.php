<?php
	$rank_only = array('n',1,2,3);
	include_once("_conf.php");
	
	if(!empty($_POST['send'])){
		$to = 'test_receive@gmail.com';
		$title = 'NT_WORK_QA';
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
			echo '<script>alert(\'寄信失敗\');</script>';
			header('refresh: 0;');
		} else {
			header('refresh: 0;');
		}
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	<style>
		img{
			border: 2px #000 solid;
			max-width: 1200px;
		}
	</style>
</head>
<body>
	<?php include_once('header.php'); ?>
	<h2 class="title-2 align-left" style="margin: 50px 0 0 100px;">常見問題</h2>
	<div class="main-content">
		<div class="qa">
			<p>
				<label>Q：功能不正常?</label>
				<span>有可能是您已關閉瀏覽器的cookie功能。</span>
			</p>
			<p>
				<label>Q：為什麼新增工作時只能指定幾個人？</label>
				<span>工作指派只限於組員或是自己本人。</span>
			</p>
			<p>
				<label>Q：要怎麼編輯我的個人資料？</label>
				<span>只有在註冊會員時才能填寫會員資料，如果需要更改，需請主任進行編輯。</span>
			</p>
			<p>
				<label>Q：為什麼有些工作沒有修改或刪除的按鈕？</label>
				<span>
					「修改」或「刪除」的按鈕，只出現在您所新增的工作項目。<br><br>
					另外，「提醒」按鈕只有該工作承辦人的上級才會出現。
				</span>
			</p>
			<p>
				<label>Q：分組顯示是什麼？</label>
				<span>按下「分組顯示」的按鈕後，工作項目將以組別來分類<br>兩個組別的工作中間會區隔開。</span>
			</p>
			<p>
				<label>Q：要如何排序工作項目？</label>
				<span>在工作列表的表格上方，有各欄位名稱，例如:「編號」「工作內容」「完成日期」等等<br>
				點擊後將會以該欄位排序。<br><br>
				<img src="asset/imgs/qa4.png" width="1000"><br>
				<img src="asset/imgs/qa5.png"><br><br>
				在表格上方也可以點擊「換升序」按鈕，切換升序與降序。</span>
			</p>
			<p>
				<label>Q：工作項目左方的藍色箭頭？</label>
				<span>當您為該項工作的承辦人即會出現。</span>
			</p>
			<p>
				<label>Q：我想在工作裡新增附件？</label>
				<span>在新增/修改工作的頁面，按下「新增附件」的按鈕，即會出現可以上傳檔案的欄位<br><br>
				<img src="asset/imgs/qa6.png" width="600"><br><br>
				若想取消上傳檔案，則按下該項目旁的X即可。</span>
			</p>
			<p>
				<label>Q：網頁上出現奇怪的英文字？</label>
				<span>有可能系統在執行上出現問題，基本上重新登入帳號就能解決。</span>
			</p>
		</div>
		<div class="qa_sendmail">
			<h3>如果任何問題或是意見，歡迎在此提供：</h3>
			<form class="pure-form pure-form-stacked" method="post">
				<div class="pure-control-group">
					<textarea name="content" style="width: 500px;height: 300px; resize: none;"></textarea>
				</div>
				<div class="pure-controls">
					<button type="submit" class="pure-button pure-button-primary">寄信</button>
					<input type="hidden" name="send" value="1">
				</div>
			</form>
		</div>
	</div>
	<?php include('footer.php') ?>
</body>
</html>