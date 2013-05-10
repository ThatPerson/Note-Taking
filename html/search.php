<!doctype html>
<html>
	<head>
		<title>CappU - your new research tool</title>
		<link href='http://fonts.googleapis.com/css?family=Sue+Ellen+Francisco|Galdeano|Poly|Oxygen' rel='stylesheet' type='text/css'>
		<link href='html/styles/style2.css' rel='stylesheet' type='text/css'>
<!-- 		<script src='html/scripts/script.js' type="text/javascript"></script> -->
		<meta charset="UTF-8">
		<meta name="description" content="Web based research tool.">
		<meta name="keywords" content="CappU, Research, Information">
		<meta name="author" content="Ben Tatman, Tom Speller">
		<link rel="shortcut icon" href="cappu.ico" />
	</head>
	<body>
		<div class="central">
			<div class="middle">
				<h3 class="cen">CappU</h3>
				<form action="index.php" method="get">
				<input type="text" class="area" name="search" placeholder="Search">
				</form>
			</div>
		</div>
		<div class="bottombar">
			<span class="button"><a href="http://www.cappu.co.uk" class="spantext">CappU</a></span>
			<span class="button"><a href="http://www.cappu.co.uk/espro" class="spantext">Espro</a></span>
			<span class="button"><a href="http://www.cappu.co.uk/latte" class="spantext">Latte</a></span>
			<span class="button"><a href="http://www.cappu.co.uk/procrastinate" class="spantext">Procrastinate</a></span>
			<span class="right">&copy; CappU 2013</span>
		</div>
		<?php echo file_get_contents("html/gosquared.html"); ?> 
	</body>
</html>