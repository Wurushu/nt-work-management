<?php
	$rank_only = array(1,2,3);
	$history_back = 'work.php';
	include_once("_conf.php");	
	$work_id = $_GET['work'];
	$work = pdo_select("select `content` from `work` where `id`='$work_id'");
	$file = pdo_select("select * from `file` where `work` = '$work_id';");
	if(count($file) == 0){
		echo '查無資料<input type="button" value="返回" onclick="location.href = \'work.php\';">';	
		exit();
	}
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
	
</head>
<body>
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">附件</h2>
			<table class="pure-table pure-table-bordered table-files" align="center">
                <thead>
                    <tr>
                        <th>附件下載</th>
                        <th>檔案大小</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						foreach($file as $k=>$v){
							echo '<tr>';
								echo '<td><a href="'. $v['src'] .'" download="'.  $v['name'] .'">'. $v['name'] .' <span class="fa fa-file"></span></a></td>';
								echo '<td>'. $v['size'] .' MB</td>';
							echo '</tr>';	
						}
					?>
                </tbody>
            </table>
		</div>
	</div>
	<?php include('footer.php') ?>
</body>
</html>