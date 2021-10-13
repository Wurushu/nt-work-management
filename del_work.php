<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	
	$id = $_GET['id'];
	$rs = $pdo->query("select * from `work` where `id` = '$id';");
	$work = $rs->fetch(2);
	if($_SESSION['rank'] != 1 && $work['order_user'] != $_SESSION['id']){
		header('location: index.php');
	}
	
	//delete sql
	$rs = $pdo->prepare("update `work` set `dead` = '". date('Ymd,H:i:s',strtotime('now')+100) ."', `dead_user` = '".$_SESSION['id']."' where `id` = '$id'");
	$rs->execute();
	header('location: work.php');