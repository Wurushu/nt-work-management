<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	error_reporting(0);
	
	$re = new stdClass();
	$re->status = 'ok';
	if(!empty($_POST['content']) && !empty($_POST['work_user']) && !empty($_SESSION['id'])){
		$content = nl2br(htmlentities($_POST['content']));
		$overday = $_POST['overday'];
		$work_user = $_POST['work_user'];
		$order_user = $_SESSION['id'];
		$post_time = date('Y-m-d');

		if($overday != '' && strtotime($overday) <= (strtotime('now')-86400)){
			$re->status = 'no';
			$re->msg = '日期錯誤';
		}
	}else{
		$re->status = 'no';
		$re->msg = '資料錯誤';
	}
	if($re->status == 'ok'){
		$rs = $pdo->prepare("insert into `work`(`content`,`overday`,`work_user`,`order_user`,`post_time`) values('$content','$overday','$work_user','$order_user','$post_time');");
		$rs->execute();
		$re->id = $pdo->lastInsertId();
	}
	echo json_encode($re);