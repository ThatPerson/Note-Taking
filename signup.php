<?php
session_set_cookie_params(
  60 * 24,        // 24 minutes lifetime
  '/',            // path
  '.cappu.co.uk', // any subdomain of website.com
  false,          // SSL not required
  true            // not accessible by JavaScript
);
	session_start();
	include ("html/index.php");
	include("database/database.php");
	include("libraries/mobile.php");

	$detect = new Mobile_Detect();
	if (!isset($_GET['override'])) {

		if ($detect->isMobile()) {
			$url = "mobile";
			header ("Location: $url");

		} else {
			$url = "desktop";
		}
		
	}
	if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_SESSION['username'])."' and password = '".mysql_real_escape_string($_SESSION['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			//The user is already logged in!!!
			header("Location: index.php");
		}
	}
	$error = "";
	if (isset($_GET['u'])) {
		//Signup has been submitted
		$password = mysql_real_escape_string($_GET['p']);
		$username = mysql_real_escape_string($_GET['u']);
		$email = mysql_real_escape_string($_GET['e']);
		$que = "select id from espro_users where username = '".$username."' or email = '".$password."'";
		$r = mysql_num_rows(mysql_query($que));
		if ($r != 0) {
			//Account already made
			$error = "This account is already made. If you own this account, go to the login page, <a href='login.php'>here</a>. If not, then it is taken.";

		} else {
			//No account
			$quer = "INSERT INTO espro_users(datesignedup, username, password, blog_name, reputation, blog_active, max_notes, email) VALUES (SYSDATE(), '".$username."', '".$password."', '', 10, 0, 100, '".$email."')";
			//$error = $quer;
			//This is a valid string. We just need to run mysql_query :D
			mysql_query($quer);
			setcookie("password", md5($username), time()+60*60*24*30, "/", "cappu.co.uk");
			setcookie("username", $password, time()+60*60*24*30, "/", "cappu.co.uk");
// 			$_SESSION['username'] = md5($username);
// 			$_SESSION['password'] = $password;
			header("Location: index.php");
			//$error = "Sorry, but signing up is not avaliable yet.";
		}
	}
	$content = '<p class="resptext">Hello. Welcome to Espro, the upcoming extension to CappU<br>Espro is a easy way to make and store notes and remember interesting snippets of information from CappU services. It will do this by having an easy to use \'Remember this\' button on each CappU page, allowing you to create your own database of interesting facts, tweets, and notes. Eventually it will allow you to store these offline too!</p>
					<form action="signup.php" method="get" onsubmit="onc()">
						<p class="resptext"><b>Signup</b></p>
						<p class="resptext">Username: </p>
						<input type="text" class="tp" placeholder="Username" name="username" id="username">
						<p class="resptext" title="Email address will not be shown at all, on any part of this website">Email: </p>
						<input type="text" class="tp" title="Email address will not be shown at all, on any part of this website" placeholder="Email" name="email" id="email">
						<p class="resptext">Password: </p>
						<input type="password" class="tp" placeholder="Password" name="password" id="password">
						<p class="resptext">Repeat Password: </p>
						<input type="password" class="tp" placeholder="Repeat Password" name="password2" id="password2"><br>
						<input type="button" class="tp" onclick="onc2()" value="Sign up"><br><br><br>
					</form>';
	$side = array();
	array_push($side, 
			  new sidebaritem("login.php", "Login", 2));
	gen_screen($content, $side, "Espro", $_GET['search'], "Espro - Signup","", 0);
	//That last bit was for when we merge to SESSION cookies. I originally used GET in order to allow me to test
?>
