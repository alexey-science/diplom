<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script src="../js/script.js"></script>

    <title>Парсер</title>
</head>
<body onload="changeRadio()">
    <div id="header" class="head-block">
        <a href="." style="text-decoration: none"><div id="logo" class="logo-block">
            Агрегатор
        </div></a>
        <div id="navigation" class="nav-block">
            <a href="<?=$eventNavBtn1?>" id="btnEv1"><div class="btn-nav"><?=$textNavBtn1?></div></a>
            <a href="<?=$eventNavBtn2?>"  id="btnEv2"><div class="btn-nav"><?=$textNavBtn2?></div></a>
        </div>
    </div>
    
    
    <div id="content" class="content-block">
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
		 }elseif ($inc == "gsTb"){
			include  $root . '/templates/gsTable.html.php';
		}elseif ($inc == "elibTb"){
			include  $root . '/templates/elibTable.html.php';
		}elseif ($inc == "clenTb"){
			include  $root . '/templates/clenTable.html.php';
		}
	
	?>
    </div>
    
    
    <div id="footer" class="footer-block">
        <div>ЮУИУиЭ &copy; <?=date('Y')?></div>
    </div>
    <script src="../js/jquery-2.2.4.min.js"></script>
</body>
</html>