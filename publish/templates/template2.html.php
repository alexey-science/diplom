<!DOCTYPE html>
<html>
<head>
	<title>Parser</title>
	<meta charset="utf-8">
	<link href="../css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="align-page" class="">
		<div id="header">		
			<div id="header_nav">
				<ul>
					<li><a href=<?=$eventNavBtn2?>><?=$textNavBtn2?></a></li>
					<li><a href=<?=$eventNavBtn1?>><?=$textNavBtn1?></a></li>
				</ul>
				<h1><a href="../" class="a_header"><span style="color: #FFF">Par</span>ser</a></h1>
			</div>
		</div>		
	</div>
	<?php
		$root = $_SERVER['DOCUMENT_ROOT'];
	 	if($inc == "main"){
			 include $root . '/templates/main.html.php';
		 }elseif($inc=="article"){
			 include  $root . '/templates/art.html.php';
		 }elseif ($inc=="login") {
			 include  $root . '/templates/login.html.php';
		 }elseif ($inc=="register") {
			 include  $root . '/templates/register.html.php';
		 }elseif ($inc == "gs_data"){
			include  $root . '/templates/gs_qdata.html.php';
		}elseif ($inc == "elib_data"){
			include  $root . '/templates/elib_qdata.html.php';
		}elseif ($inc == "clen_data"){
			include  $root . '/templates/clen_qdata.html.php';
		}
	
	?>
</body>
</html>