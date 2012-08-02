<?php
$board = 'root';
include('bin/functions.php');
connect();
?>
<html>
<head>
<title>bytelife</title>
<link rel="stylesheet" media="all" type="text/css" href="static/style.css">
<script language="javascript">
function toggle(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block')
		e.style.display = 'none';
	else
		e.style.display = 'block';
}
</script>
</head>

<body>
<?php
getheader($board);
if(isset($_GET['c'])){
	if($_GET['c'] == 'y'){
		print '<div align="center"><h2 align="center">register</h2>You have sucessfully registered.<br>You may now login.</div>';
	}
	elseif($_GET['c'] == 'n'){
		print '<div align="center"><h2 align="center">register</h2>Error: There was a problem with your registration. Please try again.<br>';
		print '<form name="register" action="bin/post.php" method="post">
				<table>
				<tr><td>username</td><td><input type="text" name="username" /></td></tr>
				<tr><td>password</td><td><input type="password" name="password" /></td></tr>
				<tr><td>confirm password</td><td><input type="password" name="repassword" /></td></tr>
				<tr><td></td><td style="text-align: left;"><input type="submit" value="submit" /></td></tr></table>
				</table>
				</form>
				</div>
				<div align="center"><p>-bytelife.org-</p></div> 
				</body>
				</html>';
	}
}
else{
	print '<div align="center">
			<h2 align="center">register</h2>
		  <form name="register" action="bin/post.php" method="post">
		  <table>
		  <tr><td>username</td><td><input type="text" name="username" /></td></tr>
		  <tr><td>password</td><td><input type="password" name="password" /></td></tr>
		  <tr><td>confirm password</td><td><input type="password" name="repassword" /></td></tr>
		  <tr><td></td><td style="text-align: left;"><input type="submit" value="submit" /></td></tr></table>
		  </table>
		  </form>
		  </div>
		  <div align="center"><p>-bytelife.org-</p></div> 
		  </body>
		  </html>';
}
?>