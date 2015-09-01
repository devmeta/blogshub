<!DOCTYPE html><html lang="<?php echo LOCALE;?>">
<head>
	<title><?php echo $config['blog']['title'];?></title>
	<meta charset="utf-8"> 
	<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
	<meta name="keywords" content="blogs gratis, periodismo, internet, free blogs, publishing, multimedia, articles, comments" />
	<meta name="description" content="Plataforma gratuita de Blogs" />
	<link rel="shortcut icon" href="/assets/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="/min/?g=css<?php echo isset($_SESSION['user_id']) ? 1 :'';?>"> 
	<link rel="stylesheet" type="text/css" href="/assets/css/<?php echo $config['blog']['style'];?>.css"> 
	<script type="text/javascript" src="/min/?g=js<?php echo isset($_SESSION['user_id']) ? 1 :'';?>"></script>
	<link rel="canonical" href="<?php echo strtok($_SERVER['REQUEST_URI'],'?');?>" />
	<link rel="group" href="<?php echo isset($_SESSION['role']) ? $_SESSION['role'] : '';?>" />
	<meta property="og:type" content="article">
	<meta property="og:url" content="<?php echo 'http://' . $config['blog']['username'] . '.devmeta.net' . strtok($_SERVER['REQUEST_URI'],'?');?>">
	<meta property="og:site_name" content="<?php echo $blog['title'];?>">
	<meta property="og:title" content="<?php echo isset($post) ? $post['title'] : $blog['title'];?>">
	<meta property="og:description" content="<?php echo isset($post) ? $post['caption'] : $blog['caption'];?>">
	<meta property="og:image" content="<?php echo isset($post['image']) ? $config['baseurl'] . '/upload/posts/sd-' . $post['image'] : $fb_image;?>">
</head>
<body>
	<div class='notifications-container'>
		<div class='notifications top-right'></div>
	</div>

	<?php include 'views/shared/header.php';?>

	<div class="container">
		<div class="row content"></div>
	</div>

	<?php include 'views/shared/footer.php';?>
</body>
</html>