<?php
session_set_cookie_params(
  60 * 24,        // 24 minutes lifetime
  '/',            // path
  '*cappu.co.uk', // any subdomain of website.com
  false,          // SSL not required
  true            // not accessible by JavaScript
);

	session_start();
	include ("html/index.php");

	include("database/database.php");
	include("libraries/mobile.php");
// 	include ("../includes/summarise.php");

	$detect = new Mobile_Detect();
	if (!isset($_GET['override'])) {

		if ($detect->isMobile()) {
			$url = "mobile";
			header ("Location: $url");

		} else {
			$url = "desktop";
		}
		
	}
	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			//The user is already logged in!!!
			header("Location: index.php");
		}
	}
	if (isset($_GET['username'])) {


		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_GET['username'])."' and password = '".mysql_real_escape_string($_GET['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r == 1) {
			setcookie("password", $_GET['password'], time()+60*60*24*30, "/", "cappu.co.uk");
			setcookie("username", $_GET['username'], time()+60*60*24*30, "/", "cappu.co.uk");
// 			$_COOKIE['username'] = $_GET['username'];
// 			z$_COOKIE['password'] = $_GET['password'];
			if (isset($_GET['redirect'])) {
				//echo "Hello";
				//exit(0);
				header("Location: ".$_GET['redirect']);
			} else {
				header("Location: index.php");
			}
		}
	}
	$l = "";
	if (isset($_GET['redirect'])) {
		$l = "<input class='tp' type='hidden' name='redirect' id = 'redir' value='".$_GET['redirect']."'>";
	} else {
		$l = "<input class='tp' type='hidden' id='redir' value=''>";
	}
	
	$content = "<p class=\"resptext\">Hello. Welcome to Espro, the upcoming extension to CappU<br>Espro is a easy way to make and store notes and remember interesting snippets of information from CappU services. It will do this by having an easy to use 'Remember this' button on each CappU page, allowing you to create your own database of interesting facts, tweets, and notes. Eventually it will allow you to store these offline too!<br>By using Espro and derivatives (Procrastinate, DarkWrite, Capsule) you agree that we may place session cookies on your machine</p>
				<form action=\"login.php\" id=\"submitme\" method=\"get\" onsubmit=\"onc()\">
					".$l."
					<input class=\"tp\" type=\"text\" id=\"user\" name=\"username\" placeholder=\"Username\">
					<br>
					<input class=\"tp\" type=\"password\" id=\"pass\" name=\"password\" placeholder=\"Password\"><br><br>
					<input type=\"button\" name=\"sub\" value=\"Go\" onclick=\"onc()\" class=\"tp\">
				</form><br><br><br>";
	$side = array();
	array_push($side, 
			  new sidebaritem("signup.php", "Sign up", 2));
	gen_screen($content, $side, "Espro", $_GET['search'], "Espro", "", 0);
	//That last bit was for when we merge to COOKIE cookies. I originally used GET in order to allow me to test
?>

