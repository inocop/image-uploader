<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

	<title>Image confirm</title>
	<link rel="stylesheet" href="./css/style.css">
</head>

<body>
	<div class="container">
		<h2>Image confirm</h2>
	</div>

	<div class="container">
		<img style="width:100%;" src="data:<?php echo $mime_type?>;base64,<?php echo $base64?>">
	</div>

	<div class="container">
		<form action="./controller.php?method=upload" method="post" enctype="multipart/form-data">
			<input type="hidden" name="base64" value="<?php echo $base64?>">
			<input type="submit" value="OK">
		</form>
	</div>
</body>
</html>