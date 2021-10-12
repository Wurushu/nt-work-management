<h1 class="title-1"><a href="work.php">NT Practice System<br><p class="work-manage-title">Work Management</p></a></h1>
<div class="logout">
	<div style="text-align: left;">
		<?=$_SESSION['user']?>&nbsp;&nbsp;<br><?=$_SESSION['name']?>
	</div>
	<div>
		<input type="button" class="pure-button pure-button-primary" onclick="location.href='QA.php'" value="FQA">
	</div>
	<?php if($_SESSION['rank'] == 1){ ?>
		<div>
			<input type="button" class="pure-button pure-button-primary" onclick="location.href='manage_user.php'" value="人員管理">
		</div>
	<?php } ?>
	<div>
		<input type="button" class="pure-button pure-button-primary" onclick="location.href='work.php'" value="工作管理">
	</div>
	<div>
		<input type="button" class="pure-button pure-button-primary" onclick="location.href='index.php'" value="登出">
	</div>
</div>
<button id="goback" class="pure-button pure-button-primary" onclick="history.back();"><span class="fa fa-arrow-circle-left" style="font-size: 1.2em;"></span> 返回</button>
<hr>