<?php
session_set_cookie_params(
  60 * 24,        // 24 minutes lifetime
  '/',            // path
  '.cappu.co.uk', // any subdomain of website.com
  false,          // SSL not required
  true            // not accessible by JavaScript
);
	session_start();
	include("database/database.php");
	include("libraries/mobile.php");
	include("html/index.php");
	if (isset($_GET['logout'])) {
		setcookie ("username", "", time() - 3600, "/", $root);
		//echo $_COOKIE['username'];
		setcookie ("password", "", time() - 3600, "/", $root);
		header("Location: login.php");
// 		setcookie("username", $password, time()+60*60*24*30, "/", $root);
	}
	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			header("Location: login.php");
			echo "MOVETO:LOGOUT"; //Send this to the JavaScript, tell them to log the user out. This will be done by removing the & from the url
		} else {
		
			$data = mysql_fetch_object(mysql_query($query));
			if (isset($_GET['sub'])) {
				if ($data->password = $_GET['current']) {
					if ($_GET['p1'] == $_GET['p2']) {
						$query = "update espro_users set password = '".$_GET['p1']."' where md5(username) = '".$_COOKIE['username']."'";
						setcookie("password", $_GET['p1'], time()+60*60*24*30, "/", $root);
						//$_COOKIE['password'] = $_GET['p1'];
						mysql_query($query);
						header("Location: index.php");
					}
				}
			}
			if (isset($_GET['sub3'])) {
			
					$quer = "update espro_users set blog_active = 1, blog_name = '".mysql_real_escape_string($_GET['name'])."' where id = ".$data->id;
			

				mysql_query($quer);
			}
			$data = mysql_fetch_object(mysql_query($query));
			if ($data->blog_active == 1) {
									$p = '
								<input class="tp" type="radio" name="active" checked value="active"><p style="display:inline" class="resptext">On</p><br>
								<input class="tp" type="radio" name="active" value="disactive"><p style="display:inline" class="resptext">Off</p>';
								} else {
									$p = '
								<input class="tp" type="radio" name="active" value="active"><p style="display:inline" class="resptext">On</p><br>
								<input class="tp" type="radio" name="active" checked value="disactive"><p style="display:inline" class="resptext">Off</p>';
								}
			$content = '<form id="form1" action="settings.php" method="get" onsubmit="runmre()">
							<p class="resptext"><b>Change your password: </b></p><br>
							<p class="resptext">Please enter current password: </p><input class="tp" type="password" id="current" name="current" placeholder="Current Password"><br>
							<p class="resptext">New Password: </p><input class="tp" type="password" name="p1" id="p1" placeholder="New Password"><br>
							<p class="resptext">Repeat: </p><input class="tp" type="password" name="p2" id="p2" placeholder="Repeated"><br>
							<br>
							<input class="tp" type="button" name="sub" value="Go" onclick="runmre()">
						</form>

						<form id="form2" action="settings.php" method="get">
							<p class="resptext"><b>Log Out</b></p>
							<input class="tp" type="submit" name="logout" value="Logout">
						</form>
						<form id="form3" action="settings.php" method="get">
							<p class="resptext"><b>Blog Settings</b></p>
							<p class="resptext">Change your blog name</p>
							<input type="text" class="tp" name="name" value="'.$data->blog_name.'" placeholder="Change the name">
							<br><br>
							<input class="tp" type="submit" name="sub3" value="Update">
							<br><br><br>
						</form>';
						$js = '<script type="text/javascript">
			function runmre() {	
				var p1 = hex_md5(document.getElementById("p1").value);
				var p2 = hex_md5(document.getElementById("p2").value);
				var current = hex_md5(document.getElementById("current").value);
				var strrrr = "";
				strrrr = "settings.php?sub=0&p1="+p1+"&p2="+p2+"&current="+hex_md5(document.getElementById("current").value);
				
				window.location = strrrr;
			}
			</script>';
			$side = array();
		
			gen_screen($content, $side, "Espro", "", "Espro - Settings", $js, 0);
?>

<?php
		}
	} else {
		header("Location: login.php");
	}
?>
