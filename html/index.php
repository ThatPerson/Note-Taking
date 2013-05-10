<?php
	class sidebaritem {
		public $url = "";
		public $keys = "";
		public $level = 0;
		public function __construct($url, $keys, $level) {
			 $this->url = $url;
			 $this->keys = $keys;
			 $this->level = $level;
		}
	    
	}
	function gen_screen($content, $sidebar, $title, $searchbar, $pagetitle, $js = "", $sb = 1) {
		array_push($sidebar,new sidebaritem("http://www.cappu.co.uk/", "CappU", 1),  
				  new sidebaritem("http://www.cappu.co.uk/espro", "Espro", 1),
				  new sidebaritem("http://www.cappu.co.uk/latte", "Latte", 1),
				  new sidebaritem("http://www.cappu.co.uk/procrastinate", "Procrastinate", 1),
				  new sidebaritem("http://www.cappu.co.uk/darkwrite", "DarkWrite", 1),
				  new sidebaritem("http://www.cappu.co.uk/cedar", "Cedar", 1),
				  new sidebaritem("http://www.cappu.co.uk/blog", "Blog", 1),
				  new sidebaritem("http://www.cappu.co.uk/wiki", "Wiki", 1)
				  );
?>
<!doctype html>
<html>
	<head>
		<title><?php echo $pagetitle ?></title>
		<link href='http://fonts.googleapis.com/css?family=Sue+Ellen+Francisco|Galdeano|Poly|Oxygen' rel='stylesheet' type='text/css'>
		<link href='http://www.cappu.co.uk/html/styles/style.css' rel='stylesheet' type='text/css'>
		<script src='http://www.cappu.co.uk/html/scripts/md5-min.js' type="text/javascript"></script> 
		<script src='http://www.cappu.co.uk/html/scripts/script.js' type="text/javascript"></script> 
		<script>
			function swit() {
				if (document.getElementById("dropdown").style.display == "none") {
					document.getElementById("dropdown").style.display = "block"
				} else {
					document.getElementById("dropdown").style.display = "none"
				}
			}
		</script>
		<meta charset="UTF-8">
		<meta name="description" content="Web based research tool.">
		<meta name="keywords" content="CappU, Research, Information">
		<meta name="author" content="Ben Tatman, Tom Speller">
		<link rel="shortcut icon" href="cappu.ico" />
	</head>
	<body>
		<div class="header">
			<div class="headbutton name" onclick="window.location='index.php'"><p class="name"><?php echo $title; ?></p></div>
			<form action="index.php" class="titl" method="get">
			<?php
			if ($sb == 1)
				echo '<input type="text" autocomplete="off" class="titl" name="search" value="'.$searchbar.'" placeholder="Search '.$title.' for information..." results="3" class="search">';
			?>
			</form>
				<?php
				$pos = array("capbut", "newbut", "twibut", "latbut", "othbut");
				$q = 1;
				//echo count($sidebar);
				if (count($sidebar) < 4) {
					$l = count($sidebar);
				} else {
					$l = 4;
					$q = 0;
				}
							for ($i = 0; $i < $l; $i++) {
								echo '<div class="headbutton '.$pos[$i+$q].'" id="f" onclick="window.location = \''.$sidebar[$i]->url.'\'"><p class="headbutton">'.substr($sidebar[$i]->keys, 0, 10).'</p></div>';

							}
							if ($q == 0) {
								echo '<div class="headbutton othbut" id="f" onclick="swit()"><p class="headbutton">Other</p></div></div>';
								echo '<div id="dropdown">';
								for ($i = $l; $i < count($sidebar); $i++) {
									echo '<div class="dropdownitem" onclick="window.location=\''.$sidebar[$i]->url.'\'"><p class="headbutton">'.substr($sidebar[$i]->keys, 0, 15).'</p></div>';
								}
								echo '</div>';
							} else {
								echo '</div>';
							}
						?>

		<div class="content">
			<?php echo $content; ?>
			<p class="center">&copy; CappU 2013 <a href="mailto:report@cappu.co.uk">Contact Us</a> <a href="http://www.cappu.co.uk/about">About Us</a></p>
		</div>
		<?php echo file_get_contents("http://www.cappu.co.uk/html/gosquared.html"); ?> 
		<?php echo $js; ?>
		<script>
			swit()
		</script>
	</body>
</html>
	
<?php
	
	}
?>
