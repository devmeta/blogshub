<!DOCTYPE html>
<html>
<head>
	<title><?php echo config('blog')->data->title;?></title>
	<meta charset="utf-8"> 
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="shortcut icon" href="/assets/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="/min/?g=css.plain"> 
	<script type="text/javascript" src="/min/?g=js.plain"></script>
</head>
<body class="plain">
	<?php include SP . 'app/views/shared/header-plain.php';?>
	<div class="container">
		<div class="row content"><?php echo $content;?></div>
	</div>
	<?php include SP . 'app/views/shared/footer.php';?>
</body>
</html>