<?php
	$rank_only = array(1);
	include_once("_conf.php");
	
	$id = $_GET['id'];
	$rs = $pdo->prepare("delete from `user` where `id` = '$id';");
	$rs->execute();
	header('location: manage_user.php');