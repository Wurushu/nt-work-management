<?php 
	$rank_only = array('n',1,2,3);
	include_once("_conf.php");
	set_time_limit(0);
	
	include('mailer/PHPMailerAutoload.php');
	
	$fopen = fopen('work_mail_check.txt','w+');
	if(fgets($fopen) == date('m-d')){
		exit();
	}else{
		fwrite($fopen,date('m-d'));
	}
	fclose($fopen);
		
	$send_list = array(1=>array(), 2=>array());			
	foreach(pdo_select("select * from `work` where `dead` = 0 and `complete` != 1 and `overday` != '0000-00-00'") as $v){		
		$post_time = $v['post_time'];
		$post_time_s = strtotime($post_time);
		$overday = $v['overday'];
		$overday_s = strtotime($overday);
		$now = strtotime(date('Y-m-d'));
	
		$work_user = pdo_select("select `email` from `user` where `id` = '". $v['work_user'] ."';");
		if(count($work_user) < 1){
			continue;
		}else{
			$work_user_email = $work_user[0]['email'];
		}
		if(trim($work_user_email) == ''){
			continue;
		}
		if($overday_s <= $post_time_s || $overday_s < $now){
			continue;
		}
		
		if(($overday_s - $now) <= 259200 && !file_exists('auto_mail_log/'.$v['id'].'_1.txt')){
			if(empty($send_list[1][$v['work_user']])){
				$send_list[1][$v['work_user']] = array(
					'email'=>$work_user_email,
					'work'=>'「' . mb_substr(htmlentities($v['content']),0,10,'utf-8') . '......」<br>',
				);
			}else{
				$send_list[1][$v['work_user']]['work'] .= '「' . mb_substr(htmlentities($v['content']),0,10,'utf-8') . '......」<br>';
			}
			$fopen = fopen('auto_mail_log/'.$v['id'].'_1.txt','x+');
			fclose($fopen);
			continue;
		}
		if($now >= $post_time_s + round(($overday_s - $post_time_s) / 3) && !file_exists('auto_mail_log/'.$v['id'].'_1.txt') && !file_exists('auto_mail_log/'.$v['id'].'_2.txt')){
			if(empty($send_list[2][$v['work_user']])){
				$send_list[2][$v['work_user']] = array(
					'email'=>$work_user_email,
					'work'=>'「' . mb_substr(htmlentities($v['content']),0,10,'utf-8') . '......」<br>',
				);
			}else{
				$send_list[2][$v['work_user']]['work'] .= '「' . mb_substr(htmlentities($v['content']),0,10,'utf-8') . '......」<br>';
			}
			$fopen = fopen('auto_mail_log/'.$v['id'].'_2.txt','x+');
			fclose($fopen);
		}
	}
	
	for($i=1; $i<=2; $i++){
		foreach($send_list[$i] as $v){
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  						// Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'test_send@gmail.com';                 // SMTP username
			$mail->Password = 'test_send_password';                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to
			$mail->CharSet = "utf-8";                       
			$mail->Encoding = "base64";
			$mail->setFrom('test_send@gmail.com', 'test');
			$mail->isHTML(true);
			$mail->addAddress($v['email']);
			$mail->Subject = '您好，系統提醒郵件';
			if($i == 1){
				$mail->Body = "您好，這是系統提醒郵件<br>下列工作三日內即將到期：<br><br>". $v['work'] ."<br><br><hr><span style=\"font-style: oblique;\">NT Work Management - ". date('Y-m-d H:i:s') ."</span>";
				$mail->AltBody = "您好，這是系統提醒郵件\n 下列工作三日內即將到期：\n\n". $v['work'] ." \n\n\n NT Work Management - ". date('Y-m-d H:i:s');
			}else{
				$mail->Body = "您好，這是系統提醒郵件<br>您開始進行下列的工作了嗎？<br><br>". $v['work'] ."<br><br><hr><span style=\"font-style: oblique;\">NT Work Management - ". date('Y-m-d H:i:s') ."</span>";
				$mail->AltBody = "您好，這是系統提醒郵件\n 您開始進行下列的工作了嗎？\n\n". $v['work'] ." \n\n\n NT Work Management - ". date('Y-m-d H:i:s');
			}
			
			if(!$mail->send()) {
				$fopen = fopen('auto_mail_error.txt','a+');
				fwrite($fopen,date('Y-m-d') . PHP_EOL);
				fclose($fopen);
			}
		}	
	}