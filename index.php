<?php
session_set_cookie_params(
  60 * 24,        // 24 minutes lifetime
  '/',            // path
  '*cappu.co.uk', // any subdomain of website.com
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
		$error = "";
		$query = "select * from espro_users where md5(username) = '".mysql_real_escape_string($_COOKIE['username'])."' and password = '".mysql_real_escape_string($_COOKIE['password'])."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
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
			if (isset($_GET['delete'])) {
				//We are nice, we do not fully delete them. We archive them.
				$query = "update espro_notes set active = 0, public = 0 where user_id=".$data->id." and note_id = ".mysql_real_escape_string($_GET['delete']);
				mysql_query($query);
				//echo $query;
			}
			$notes_query = "select * from espro_notes where user_id = '".$data->id."' and active = 1 order by modified desc";
			$notes = mysql_query($notes_query);
			$i = 0;
					//$toret = "<p class=\"resptext\">";
					$array = "";
					while ($row = mysql_fetch_object($notes)) {
						$array .= "titles[".$i."] = '".$row->title."'; \n";
						$array .= "ids[".$i."] = ".$row->note_id."; \n";
						$toret .= "<div class='block'><h3>".$row->title."</h3><p>".substr($row->content, 0, 80)."</p><a href='post.php?post=".$row->note_id."'>Visit</a>   <a href='index.php?delete=".$row->note_id."'>Delete</a></div>";
// 						$toret .= '<a class="internalsidebarlevel level2" href=\'post.php?post='.$row->note_id.'\'">'.$row->title.'</a><br>';
						$i ++;
					}
					//$toret .= '</p>';
			$js = '<script>
				pre = document.getElementById("buttons").innerHTML;
			function resize(object) {
					var content = object.innerHTML;
					content = content.replace(/\n/g, "<br>");
					content += "<br>";
					document.getElementById("hiddendiv").innerHTML = content;
					console.log(document.getElementById("hiddendiv").style.height);
					object.style.height = document.getElementById("hiddendiv").style.height;
				}
				var titles = new Array();
				var ids = new Array();
				'.$array.'
				setTimeout("searchs(\"\", \"buttonholder\", titles);", 000);
				document.getElementById("search").onkeyup = function(){searchs(document.getElementById("search").value, \'\', titles)}
			</script>';
			
			$content = "<p class=\"resptext\">".$toret."</p>
					<br><br>";
					
			$side = array();
			array_push($side, new sidebaritem("index.php?new=New", "New", 2),
					  new sidebaritem("settings.php", "Settings", 2));
			
			gen_screen($content, $side, "Espro", "", "Espro", $js, 0);

		}
	} else {
		header("Location: login.php");
	}
?>
