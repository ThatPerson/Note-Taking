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
			$post = 1;
			if (isset($_GET['post'])) {
				$post = intval($_GET['post']);
			}
			if (isset($_GET['delete'])) {
				//We are nice, we do not fully delete them. We archive them.
				$query = "update espro_notes set active = 0 where user_id=".$data->id." and note_id = ".$post;
				mysql_query($query);
				//echo $query;
				header("Location: index.php");
			}

			if (isset($_POST['title']) && isset($_POST['content'])) {
				$query = "update espro_notes set title = '".mysql_real_escape_string($_POST['title'])."', content = '".mysql_real_escape_string($_POST['content'])."', modified = now() where active=1 and note_id = ".$post." and user_id = ".$data->id;

				mysql_query($query);

			}

			$notestuff = "select * from espro_notes where user_id = '".$data->id."' and note_id = '".$post."'";
			$note = mysql_fetch_object(mysql_query($notestuff));
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
	<html>
		<head>
			<title>Espro</title>

			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
			<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
			<script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>

<link href='http://fonts.googleapis.com/css?family=Kavoon|Rambla|Titillium+Web' rel='stylesheet' type='text/css'>		
			<style>
				h3, p {
					text-align:center;
				}
			</style>
			<script>
				
				function submitfo() {
					var post = document.getElementById("post").innerHTML;
					var url;
					url = "post.php?post="+post+"&title="+document.getElementById("title").value+"&content="+document.getElementById("content").value;

					alert("Updating");
					window.location = url.split("\n").join("<br>");
				}
			</script>
		</head>
		<body>
			<div id="post"><?php echo $post; ?></div>
			<div data-role="page" id="page">
				<div data-role="header" data-theme="b" data-position="fixed">
					<h1>Espro</h1>
					<a href="#leftpanel1" data-icon="bars" data-iconpos="notext" data-rel="dialog" data-transition="fade">Search</a>
						<a href="#rightpanel" data-icon="gear"><?php echo $data->username; ?></a>
			<!--Thanks to SubtlePatterns for the pattern-->
				</div>
				<div data-role="content">
				<form action="post.php?post=<?php echo $post; ?>" method="post">
					<input id="title" type="text" name="title" value="<?php echo $note->title; ?>">
					<textarea id="content" name="content">
						<?php
																					
																						echo str_replace("\\", "", str_replace("<br>", "\n", $note->content));
																					?>
					</textarea>
					<input type="submit" data-corners="false" value="Update" data-icon="edit" name="edit">
					<input type="hidden" name="post" value="<?php echo $post; ?>" >
					</form>
				</div>
				<div data-role="footer" data-id="foo1" data-position="fixed">
					<div data-role="navbar" data-theme="b">
						<ul>

							<li><form action="post.php" method="get"><input type="hidden" name="post" value="<?php echo $post; ?>" ><input type="submit" data-corners="false" value="Delete" data-icon="delete" name="delete"></form></li>

						</ul>
					</div><!-- /navbar -->
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
					<h3>Espro</h3><br>
					
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
