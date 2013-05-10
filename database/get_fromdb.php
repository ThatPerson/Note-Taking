<?php
	include("database.php");
	if (isset($_GET['username']) && isset($_GET['password'])) {
		$query = "select * from espro_users where md5(username) = '".$_GET['username']."' and password = '".$_GET['password']."' limit 1"; //Two stage hashing on it. First layer is SHA-256, and this is stored in the DB. The MD5 hash of the sha256 is given to the user;
		$r = mysql_num_rows(mysql_query($query));
		if ($r != 1) {
			echo "MOVETO:LOGOUT"; //Send this to the JavaScript, tell them to log the user out. This will be done by removing the & from the url
		} else {
			//User exists.
			$data = mysql_fetch_object(mysql_query($query));
			/*Now we need to get our notes out of it.
			*We do this very simply
			*/
			$notes_query = "select * from espro_notes where user_id = '".$data->id."'";
			$notes = mysql_query($notes_query);
			
			$toret = "{\"notes\":[";
			while ($row = mysql_fetch_object($notes)) {
				$toret .= '{"id":"'.$row->id.'", "title":"'.$row->title.'", "content":"'.substr(str_replace("\n", "<br>", $row->content),0,30).'", "created":"'.$row->created.'", "modified":"'.$row->modified.'"},'; 
			}
			$toret = substr($toret, 0, strlen($toret)-1);
			$toret .= "]}";
			echo $toret;
		}
	} else {
		echo "NOT ENOUGH";
	}
?>
