var pre;
var toret;
function searchs(searchterm, toputid, tosearch) {
	toret = "<p class='sidebarlevel level0'>Results</p>";
	var i;
	for (i = 0; i < tosearch.length; i++) {
		if (tosearch[i].indexOf(searchterm) != -1) {
			toret += '<a class="sidebarlevel level2" href=\'post.php?post='+ids[i]+'\'">'+tosearch[i]+'</a><br>';
		}
	}
	toret += pre;
	document.getElementById("buttons").innerHTML = toret;

}
function onc() {
	
					var user = hex_md5(document.getElementById("user").value);
					var pass = hex_md5(document.getElementById("pass").value);
					var redi = document.getElementById("redir").value;
					if (redi == "") {
						redi = "";
					} else {
						v = " &redirect="+redi;
						redi = v;
					}
					window.location = "login.php?username="+user+"&password="+pass+redi;
				}
				
function onc2() {
	
					//username password email password2
					var username = document.getElementById("username").value;
					var email = document.getElementById("email").value;
					var password = document.getElementById("password").value;
					var password2 = document.getElementById("password2").value;
					if (password == password2) {
						var pass2 = hex_md5(password);
					//	email = email.replace(/./g, "~#~");
						window.location = "signup.php?u="+username+"&p="+pass2+"&e="+email;
					}
					//window.location = "login.php?username="+user+"&password="+pass;
				}