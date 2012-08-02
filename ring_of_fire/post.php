<?php
include('../bin/functions.php');
connect();
$board = basename(getcwd());
if(isset($_GET['d'])) {
	if(checkadmin($_SESSION['username'])) {
		removepost($_GET['d'],$board);
	}
}
elseif(isset($_GET['s'])) {
	if(checkadmin($_SESSION['username'])) {
		stickypost($_GET['s'],$board);
	}
}
header("Location: index.php");
?>