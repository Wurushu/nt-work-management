<?php
	session_start();
	//error_reporting(4);
	date_default_timezone_set('ROC');
	
	//MYSQL Connect
	$host = 'localhost';
	$db = '';
	$user = '';
	$passwd = '';
	
	$pdo = new PDO("mysql:host={$host};dbname={$db}",$user,$passwd);
	$pdo->exec("set names `utf8`");
	
	//Varible Setting
	$rank_name = array(
		1=>'主任',
		2=>'組長',
		3=>'組員'
	);
	
	$rank_color = array(
		1=>'#f00',
		2=>'#afa',
		3=>'#000'
	);
  
	$sort_name_zh = array(
		1=>'編號',
		2=>'工作內容',
		3=>'完成日期',
		4=>'承辦人',
		5=>'審核',
		6=>'是否完成',
	);

	$sort_name_en = array(
		1=>'id',
		2=>'content',
		3=>'overday',
		4=>'work_user',
		5=>'order_user',
		6=>'complete',
	);
	
	$year_path = 'year_work/year.txt';
	$work_path = 'year_work/work.txt';
	
	//use function
	function pdo_select($sql){
		global $pdo;
		$rs = $pdo->query($sql);
		return $rs->fetchAll();
	}
	
	//set
	$rank = isset($_SESSION['rank']) ? $_SESSION['rank'] : 'n';
	$rank_only = isset($rank_only) ? $rank_only : array(1); 
	if(empty($rank) || !in_array($rank,$rank_only)){
		header('location: index.php');
	}