<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	
	$id = $_GET['id'];
	$rs = $pdo->query("select * from `work` where `id` = '$id';");
	$work = $rs->fetch(2);
	if($_SESSION['rank'] != 1 && $work['order_user'] != $_SESSION['id']){
		header('location: index.php');
	}
	
	//delete file
	$rs = $pdo->query("select `src` from `file` where `work` = '$id';");
	$file = $rs->fetch(2);
	@unlink($file['src']);
	
	//backup work
	$fopen = fopen('backup/['.iconv('utf-8','big5',date('y年m月d日 H點i分s秒')).']_'.$work['id'].'_'.iconv('utf-8','big5',$_SESSION['name']).'.html','w+');	
	fwrite($fopen,'<div style="font-size: 30px;">內容: '.$work['content'] . '<br>');
	fwrite($fopen,'限辦: '.$work['overday'] . '<br>');
	fwrite($fopen,'發佈: '.$work['post_time'] . '<br>');
	fwrite($fopen,'完成: '.($work['complete'] == 1 ? '是 '.$work['ps'] : '否') . '<br>');
	fwrite($fopen,'承辦人: '.pdo_select("select `name` from `user` where `id` = '".$work['work_user']."';")[0]['name'] . '<br>');
	fwrite($fopen,'審核: '.pdo_select("select `name` from `user` where `id` = '".$work['order_user']."';")[0]['name']. '</div>');
	fclose($fopen);
	
	//delete sql
	$rs = $pdo->prepare("delete from `file` where `work` = '$id';");
	$rs->execute();
	$rs = $pdo->prepare("delete from `work` where `id` = '$id';");
	$rs->execute();
	header('location: work.php');