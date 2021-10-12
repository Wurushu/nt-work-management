<?php
	$rank_only = array(1);
	include_once("_conf.php");
?>
<!doctype html>
<html>
<head>
	<?php include_once('head.php'); ?>
</head>
<body style="height: 120%;">
	<?php include_once('header.php'); ?>
	<div class="main-content">
		<h2 class="title-2">備份</h2>
		<table class="pure-table pure-table-bordered table-files" align="center">
			<tbody>
				<?php
					if ($handle = opendir('backup')) {
						while ($file = iconv('big5','utf-8',readdir($handle))) {
							if($file == '.' || $file == '..'){
								continue;
							}
							echo '<tr><td><a href="backup/'.$file.'" target="new">'. $file . '</a></td></tr>';
						}
						closedir($handle);
					}
				?>
			</tbody>
		</table>
	</div>
	<?php include('footer.php') ?>
</body>
</html>