<?php
$board = basename(getcwd());
include('../bin/functions.php');
connect();
?>
<html>
<head>
<title><?php echo $board;?></title>
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
if(isset($_GET['p'])) {
	getthreads($board,$_GET['p']);
}
else {
	getthreads($board,'1');
}
?>

<div align="center"><p><a href="index.php?p=1">1</a> | <a href="index.php?p=2">2</a> | <a href="index.php?p=3">3</a> | <a href="index.php?p=4">4</a> | <a href="index.php?p=5">5</a></p></div>

<div align="center"><p>-bytelife.org-</p></div> 	
</body>
</html>