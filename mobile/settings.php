<?php
	session_start();
	include("../database/database.php");
	if (isset($_GET['logout'])) {
		unset($_COOKIE['username']);
		unset($_COOKIE['password']);
	}
	if (isset($_COOKIE['password']) && isset($_COOKIE['username'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
// 			unset($_COOKIE['username']);
// 			unset($_COOKIE['password']);
			setcookie ("username", "", time() - 3600, "/", $root);
		//echo $_COOKIE['username'];
		setcookie ("password", "", time() - 3600, "/", $root);
			header("Location: login.php");
			echo "MOVETO:LOGOUT"; //Send this to the JavaScript, tell them to log the user out. This will be done by removing the & from the url
		} else {
			$data = mysql_fetch_object(mysql_query($query));
			if (isset($_GET['sub'])) {
				if ($data->password = $_GET['current']) {
					if ($_GET['p1'] == $_GET['p2']) {
						$query = "update espro_users set password = '".mysql_real_escape_string($_GET['p1'])."' where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."'";
						$_COOKIE['password'] = $_GET['p1'];
						mysql_query($query);
						header("Location: index.php");
					}
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
function runmre() {	
	var p1 = hex_md5(document.getElementById("p1").value);
	var p2 = hex_md5(document.getElementById("p2").value);
	var current = hex_md5(document.getElementById("current").value);
	var strrrr = "";
	strrrr = "settings.php?sub=0&p1="+p1+"&p2="+p2+"&current="+hex_md5(document.getElementById("current").value);
	
	window.location = strrrr;
}
</script>
		</head>
		<body>
			<div data-role="page" id="page">
				<div data-role="header" data-theme="b" data-position="fixed">
					<h1>Espro</h1>
					<a href="#leftpanel1" data-icon="bars" data-iconpos="notext" data-rel="dialog" data-transition="fade">Search</a>
						<a href="#rightpanel" data-icon="gear"><?php echo $data->username; ?></a>
			<!--Thanks to SubtlePatterns for the pattern-->
				</div>
				<div data-role="content">
					<form id="form1" action="settings.php" method="get" onsubmit="runmre()">
							<p id="order">Change your password: </p><br>
							Please enter current password: <input class="tp" type="password" id="current" name="current" placeholder="Current Password"><br>
							New Password: <input class="tp" type="password" name="p1" id="p1" placeholder="New Password"><br>
							Repeat: <input class="tp" type="password" name="p2" id="p2" placeholder="Repeated"><br>
							<input type="button" name="sub" value="Go" onclick="runmre()">
						</form>

						<form id="form2" action="settings.php" method="get">
							<p id="order">Log Out</p>
							<input type="submit" name="logout" value="Logout">
						</form>
				</div>
				<div data-role="footer" data-id="foo1" data-position="fixed">
					<p>Copyright CappU 2013</p>
				</div>
				<div data-role="panel" data-position-fixed="true" id="rightpanel" data-position="right" data-display="reveal" data-dismissible="true" data-theme="c">
					<ul data-role="listview" data-theme="c">
						<li><a href="index.php" data-icon="home">Home</a></li>
						<li><a data-ajax="false" href="http://www.cappu.co.uk" data-icon="home">CappU</a></li>
					</ul>
				</div>
				<div data-role="panel" data-position-fixed="true" id="leftpanel1" data-position="left" data-display="reveal" data-dismissible="true" data-theme="a">
					<h3 onclick="window.location = 'index.php'">Espro</h3><br>
					
					<ul data-role="listview" data-theme="a">
						<?php
							$notes_query = "select * from espro_notes where user_id = '".$data->id."' and active = 1";
							$notes = mysql_query($notes_query);
							$toret = "";
							while ($row = mysql_fetch_object($notes)) {
								$toret .= "<li><a href=\"post.php?post=".$row->note_id."\">".$row->title."</a></li>";
							}
							echo $toret;
						?>

    					<li><form action="index.php" method="get"><input data-theme="a" type="submit" value="New" name="new"></form></li>
					</ul>
				</div>
			</div>
		</body>
	</html>
<?php
		}
	} else {
		header("Location: login.php");
	}
?>
