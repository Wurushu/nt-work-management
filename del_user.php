<?php
	$rank_only = array(1);
	include_once("_conf.php");
	
	$id = $_GET['id'];
	if(pdo_select("select `rank` from `user` where `id` = '$id';")[0]['rank'] == 1){
		header('location: manage_user.php');		
		exit();
	}
	$rs = $pdo->prepare("delete from `user` where `id` = '$id';");
	$rs->execute();
	header('location: manage_user.php');