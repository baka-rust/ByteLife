<?php
$board = 'root';
include('../bin/functions.php');
connect();
if(loggedin()) {
	if(!checkadmin($_COOKIE['username'])) {
		header('LOCATION: ..');
	}
}
else {
	header('LOCATION: ..');
}
?>
<html>
<head>
<title>bytelife</title>
<link rel="stylesheet" media="all" type="text/css" href="../static/style.css">
<script language="javascript">
function toggle(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block')
		e.style.display = 'none';
	else
		e.style.display = 'block';
}
</script>
<link rel="shortcut icon" type="image/x-icon" href="static/favicon.ico">
</head>

<body>
<?php getheader($board);?>
<h2 align="center">admin panel</h2>
<div class="home">

<h2>ban/unban user</h2>
	<div class="homesub">
    <form name="userban" action="action.php" method="post">
    	<input type="text" name="ban" /><input type="submit" value="submit" />
    </form>
	</div>
    
<h2>remove user</h2>
	<div class="homesub">
    <form name="userremove" action="action.php" method="post">
    	<input type="text" name="remove" /><input type="submit" value="submit" />
    </form>
	</div>

<h2>post update</h2>
	<div class="homesub">
    <form name="update" action="action.php" method="post">
    	<input type="text" name="update" /><input type="submit" value="submit" />
    </form>
    </div>

</div>
</body>
</html>