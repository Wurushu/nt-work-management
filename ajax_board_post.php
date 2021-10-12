<?php
	$rank_only = array(1,2,3);
	include_once("_conf.php");
	error_reporting(0);
	
	$post = htmlentities($_POST['post']);
	$user = $_SESSION['id'];
	$date = date('Y-m-d_H點i分',strtotime('now')+100);
	
	$rs = $pdo->prepare("insert into `board`(`post`,`user`,`date`) values('$post','$user','$date');");
	$rs->execute();
	
	echo '<div class="board-one">'.$post.'<p class="board-one-who"><input type="button" class="board-del pure-button button-error" value="刪除" onclick="board_del('.$pdo->lastInsertId().');">('.$date.' '.$_SESSION['name'].')</p></div>';
	