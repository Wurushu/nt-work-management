<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>NT Work Management System</title>
<link href="asset/css/pure.css" rel="stylesheet">
<link href="asset/css/pure-grid-respon.css" rel="stylesheet">
<link href="asset/css/style.css" rel="stylesheet">
<link href="asset/css/font-awesome.css" rel="stylesheet">
<link href="asset/css/loading.css" rel="stylesheet">
<script src="asset/js/jquery.js"></script>
<script>
	$(document).on('mousedown','.logout input[type="button"]',function(event){
		if(event.button == 1){
			window.open($(this).data('link_url'));
		}
	});
</script>