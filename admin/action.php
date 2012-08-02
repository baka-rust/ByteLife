<?php
include('../bin/functions.php');
connect();


if(loggedin() && checkadmin($_COOKIE['username'])) {
		if(isset($_POST['ban'])) {
			ban($_POST['ban']);
		}
		elseif(isset($_POST['remove'])) {
			removeuser($_POST['remove']);
		}
		elseif(isset($_POST['update'])) {
			update($_POST['update']);
			header('Loaction: index.php');
		}
		else {
			print 'error';
		}
}
else {
		header('Loaction: ..');
}
 

?>