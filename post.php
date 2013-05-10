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
	$detect = new Mobile_Detect();
	if (!isset($_GET['override'])) {

		if ($detect->isMobile()) {
			$url = "mobile";
			header ("Location: $url");

		} else {
			$url = "desktop";
		}
		
	}
	if (isset($_COOKIE['password']) && isset($_COOKIE['username'])) {
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			header("Location: login.php");
			echo "MOVETO:LOGOUT"; //Send this to the JavaScript, tell them to log the user out. This will be done by removing the & from the url
		} else {
			$data = mysql_fetch_object(mysql_query($query));
			$post = 1;
			if (isset($_GET['post'])) {
				$post = intval($_GET['post']);
			}
			if (isset($_GET['public'])) {
				if ($_GET['public'] == "Make public") {
					$que = "update espro_notes set public = 1 where user_id = ".$data->id." and note_id = ".$post;
				} else {
					$que = "update espro_notes set public = 0 where user_id = ".$data->id." and note_id = ".$post;
				}
				mysql_query($que);
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
			$notes_query = "select * from espro_notes where user_id = '".$data->id."' and active = 1 and note_id = '".$post."'";
			$note = mysql_fetch_object(mysql_query($notes_query));
			
			$side = array();
			array_push($side, 
					  new sidebaritem("post.php?post=".$post."&amp;delete=1", "Delete", 2));
// 					  new sidebaritem("", "Make public", 2)
			if ($note->public == 1) {
				array_push($side, new sidebaritem("post.php?post=".$post."&amp;public=Make local", "Private", 2));
			} else {
				array_push($side, new sidebaritem("post.php?post=".$post."&amp;public=Make public", "Public", 2));
			}
			
			
				$l =  $note->content;
			$content = '<form action="post.php?post='.$_GET['post'].'" method="post">
						<input name="title" type="text" value="'.$note->title.'" id="maint" class="texta">
						<br><p style="top:20px;" class="internalsidebarlevel level0">CREATED: '.$note->created.' :: MODIFIED: '.$note->modified.'</p>
						<textarea name="content" class="book" id="book">'.str_replace("<br>", "\n", $l).'</textarea>
						<input type="submit" name="update" value="Update" class="update side tp">
					</form>';
			gen_screen($content, $side, "Espro", "", "Espro", $js, 0);
		}
	} else {
		header("Location: login.php");
	}
?>
