<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

	<title>Image upload</title>
	<link rel="stylesheet" href="./css/style.css">
</head>

<body>
	<div class="container">
		<h2>Image upload</h2>
	</div>

	<div class="container">
		<form action="./controller.php?method=confirm" method="post" enctype="multipart/form-data">
			<input name="userfile" type="file" accept="image/*">
			<input type="submit" value="upload">
		</form>
		<p><?php echo $message?></p>
	</div>

	<div class="container">
		<ul class="center">
		<?php foreach ($file_links as $link) : ?>
		<li>
		<a href="<?php echo $link ?>"><?php echo basename($link) ?></a>
		</li>
		<?php endforeach; ?>
		</ul>
	</div>
</body>
</html>