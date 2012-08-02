<?php
$board = basename(getcwd());
include('../bin/functions.php');
if(isset($_GET['thread'])) {
	connect();
}
else {
	header('Location: index.php');
}
?>
<html>
<head>
<title>
<?php
echo $board.' | ';
echo threadinfo($board,$_GET['thread']);
?>
</title>
<link rel="stylesheet" media="all" type="text/css" href="../static/style.css" />
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
getthread($board,$_GET['thread']);
?>

<div align="center"><p>-bytelife.org-</p></div>

</body>
</html>