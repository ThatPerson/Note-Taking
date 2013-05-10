<?php
	session_start();
	include("../database/database.php");
	if (isset($_COOKIE['password']) && isset($_COOKIE['username'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			unset($_COOKIE['username']);
			unset($_COOKIE['password']);
			header("Location: login.php");
			echo "MOVETO:LOGOUT"; //Send this to the JavaScript, tell them to log the user out. This will be done by removing the & from the url
		} else {
			$data = mysql_fetch_object(mysql_query($query));
			if (isset($_GET['new'])) {
				$qu = "select * from espro_notes where active = 1 and public = 0 and user_id = ".$data->id;
				//echo $qu;
				$len = mysql_num_rows(mysql_query($qu));
				if ($len < $data->max_notes) {
					$query = "insert into espro_notes (note_id, user_id, created, modified, title, content, public) values(".(intval(mysql_num_rows(mysql_query("select * from espro_notes where user_id = ".$data->id)))+1).", ".$data->id.", now(), now(), \"My Note\", \"Just click here and start typing for your new note!\", 0)";
					mysql_query($query);
				} else {
					$error = "No more notes avaliable. For more, either make some of your notes public (you have more of these), delete some notes, or email <a href='mailto:ben_tatman@cappu.co.uk'>CappU</a> with a reason for more";

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
					<ul data-role="listview" data-theme="c" >
						<?php
							$notes_query = "select * from espro_notes where user_id = '".$data->id."' and active = 1 order by modified desc";
							$notes = mysql_query($notes_query);
							$toret = "";
							while ($row = mysql_fetch_object($notes)) {
								$toret .= "<li><a href=\"post.php?post=".$row->note_id."\">".$row->title."</a></li>";
							}
							echo $toret;
						?>

    					<li><form action="index.php" method="get"><input data-theme="c" type="submit" value="New" name="new"></form></li>
					</ul>
				</div>
				<div data-role="footer" data-id="foo1" data-position="fixed">
					<p>Copyright CappU 2013</p>
				</div>
				<div data-role="panel" data-position-fixed="true" id="rightpanel" data-position="right" data-display="reveal" data-dismissible="true" data-theme="c">
					<ul data-role="listview" data-theme="c">
						<li><a href="index.php" data-icon="home">Home</a></li>
						<li><a href="settings.php" data-icon="gear">Settings</a></li>
						<li><a data-ajax="false" href="http://www.cappu.co.uk" data-icon="home">CappU</a></li>
						<li><a data-ajax="false" href="http://www.cappu.co.uk/cedar" data-icon="home">Cedar</a></li>
					</ul>
				</div>
				<div data-role="panel" data-position-fixed="true" id="leftpanel1" data-position="left" data-display="reveal" data-dismissible="true" data-theme="a">
					<h3 onclick="window.location = 'index.php'">Espro</h3><br>
					
					<ul data-role="listview" data-theme="a">
						<?php
							$notes_query = "select * from espro_notes where user_id = '".$data->id."' and active = 1 order by modified desc";
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
