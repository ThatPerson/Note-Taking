<?php
	session_start();
	include("../database/database.php");
	if (isset($_GET['username'])) {


		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_GET['username'])."' and password = '".mysql_real_escape_string($_GET['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r == 1) {

			setcookie("password", $_GET['password'], time()+60*60*24*30, "/", $root);
			setcookie("username", $_GET['username'], time()+60*60*24*30, "/", $root);
// 			$_COOKIE['username'] = $_GET['username'];
// 			$_COOKIE['password'] = $_GET['password'];
			header("Location: index.php");
		} else {
			$error = "Incorrect login details";
		}
	}
	if (isset($_COOKIE['password']) && isset($_COOKIE['username'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			//The user is already logged in!!!
			header("Location: index.php");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
	<html>
		<head>
			<title>Espro</title>

			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
			<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
			<script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
			<script type="text/javascript" src="scripts/script.js"></script>
<link href='http://fonts.googleapis.com/css?family=Kavoon|Rambla|Titillium+Web' rel='stylesheet' type='text/css'>		
			<style>
				h3, p {
					text-align:center;
				}
			</style>
<script type="text/javascript" src="scripts/md5-min.js"></script>
<script type="text/javascript">
function onc() {
	
	var user = hex_md5(document.getElementById("username").value);
	var pass = hex_md5(document.getElementById("password").value);
	window.location = ("login.php?username="+user+"&password="+pass);
}
</script>
		</head>
		<body>
			<div data-role="page" id="page">
				<div data-role="header" data-theme="b" data-position="fixed">
					<h1>Espro</h1>

					<a href="#rightpanel" data-icon="home" data-iconpos="notext"></a>
			<!--Thanks to SubtlePatterns for the pattern-->
				</div>
				<div data-role="content">
					<form id="form1" onsubmit="onc()">
						Username:
						<input type="text" id="username" placeholder = "Username">
						Password:
						<input type="password" id="password" placeholder = "Password">
						<input type="submit" value="Login">
					</form>
					<p><?php echo $error; ?></p>
				</div>
				<div data-role="footer" data-id="foo1" data-position="fixed">
					<p>Copyright CappU 2013</p>
				</div>
				<div data-role="panel" data-position-fixed="true" id="rightpanel" data-position="left" data-display="reveal" data-dismissible="true" data-theme="c">
					<ul data-role="listview" data-theme="c">

						<li><a data-ajax="false" href="http://www.cappu.co.uk" data-icon="home">CappU</a></li>
					</ul>
				</div>
			</div>
		</body>
	</html>
<?php
		

?>
