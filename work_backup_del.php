<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	
	$id = $_GET['id'];
	$rs = $pdo->query("select * from `work` where `id` = '$id';");
	$work = $rs->fetch(2);
	if($work['dead_user'] != $_SESSION['id']){
		header('location: index.php');
	}
	
	$rs = $pdo->prepare("delete from `work` where `id` = '$id'");
	$rs->execute();
	header('location: work_backup.php');