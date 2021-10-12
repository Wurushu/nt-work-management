<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	error_reporting(E_ALL);
	
	$re = new stdClass();
	$re->status = 'ok';
	if(!empty($_FILES['file']) && count($_FILES['file']) > 0 && !empty($_POST['work_id'])){
		$work_id = $_POST['work_id'];
		$file = $_FILES['file'];
		$tmp_name = $file['tmp_name'];
		$name = $file['name'];
		$size = $file['size'];
		$qid = uniqid();
		
		if(!move_uploaded_file($tmp_name,'files/'.$qid.iconv('UTF-8','big5',$name))){
			$re->status = 'no';
		}
	}else{
		$re->status = 'no';
	}
	if($re->status == 'ok'){
		$rs = $pdo->prepare("insert into `file`(`work`,`name`,`size`,`src`) values('$work_id','".$name."','". (ceil($size / 1048576)) ."','files/".$qid.$name."');");
		$rs->execute();
	}
	echo json_encode($re);