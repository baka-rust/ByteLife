<?php
$board = 'root';
include('bin/functions.php');
connect();
if(!loggedin()) {
	header('LOCATION: index.php');
}
elseif(isset($_SESSION['username'])) {
	if(!checkban($_SESSION['username'])) {
		header('LOCATION: index.php');
	}
}
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
<?php getheader($board); ?>
<div align="center">
<h2 align="center">you have been banned</h2>

<img src="static/ban.png" />

</div>
</body>
</html>