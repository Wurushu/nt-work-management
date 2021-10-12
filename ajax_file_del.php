<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	error_reporting(E_ALL);
	
	if(!empty($_POST['file_id'])){
		$file_id = $_POST['file_id'];
		$file_src = pdo_select("select `src` from `file` where `id`='$file_id'")[0]['src'];
		unlink($file_src);
		echo $file_src;
		$rs = $pdo->prepare("delete from `file` where `id`='$file_id'");
		$rs->execute();
	}
