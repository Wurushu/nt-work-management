<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	error_reporting(0);
	
	$id = $_POST['id'];
	$board = pdo_select("select `user` from `board` where `id` = '$id';")[0]['user'];
	if($board != $_SESSION['id']){
		exit('錯誤');
	}
	
	$rs = $pdo->prepare("delete from `board` where `id` = '$id';");
	$rs->execute();

