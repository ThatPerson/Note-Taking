function searchs(searchterm, toputid, tosearch) {
	var toret = "";
	var i;
	for (i = 0; i < tosearch.length; i++) {
		if (tosearch[i].indexOf(searchterm) != -1) {
			toret += '<a class="sidebarlevel level2" href=\'post.php?post='+ids[i]+'\'">'+tosearch[i]+'</a><br>';
		}
	}
	document.getElementById("buttons").innerHTML = toret;

}
