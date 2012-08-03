<?php
include('functions.php');
connect();
if(isset($_POST['parent']) && loggedin()) { 				//clean up errors
	if(checkban($_SESSION['username'])) {
		header('LOCATION: ../banned.php');
	}
	elseif($_POST['content'] == '') {
		echo 'ERROR, MUST INCLUDE CONTENT';
	}
	else {
		if($_POST['board'] == 'anonymous') {
			if(checkadmin($_SESSION['username'])) {
				$username = $_SESSION['username']; //change this with login script
			}
			else {
				$username = 'anonymous'; //change this with login script
			}
		}
		else {
			$username = $_SESSION['username']; //change this with login script
		}
		$parent = $_POST['parent'];
		$indent = $_POST['indent'];
		$content = $_POST['content'];
		$thread = $_POST['thread'];
		$board = $_POST['board'];
		updatestamp($board,$thread);
		newreply($board,$username,$parent,$indent,$content);
		echo '<html><head><meta http-equiv="REFRESH" content="2;url=../'.$board.'/thread.php?thread='.$thread.'"><link rel="stylesheet" media="all" type="text/css" href="../../static/style.css" /></head><body>';
		getheader($board);
		print '<div align="center"><h2>Updating index.</h2></div><div align="center"><p>-bytelife.org-</p></div></body></html>';
	}
}
elseif(isset($_POST['new']) && loggedin()) {
	if(checkban($_SESSION['username'])) {
		header('LOCATION: ../banned.php');
		break;
	}
	elseif($_POST['title'] == '') {
		echo 'ERROR, MUST INCLUDE TITLE';
	}
	elseif($_POST['content'] == '') {
		echo 'ERROR, MUST INCLUDE CONTENT';
	}
	else {
		$username = $_SESSION['username']; //change this with login script
		$title = $_POST['title'];
		$content = $_POST['content'];
		$board = $_POST['board'];
		newpost($board,$username,$title,$content);
		echo '<html><head><meta http-equiv="REFRESH" content="2;url=../'.$board.'/index.php"><link rel="stylesheet" media="all" type="text/css" href="../../static/style.css" /></head><body>';
		getheader($board);
		print '<div align="center"><h2>Updating index.</h2></div><div align="center"><p>-bytelife.org-</p></div></body></html>';

	}
}
elseif(isset($_POST['password'])){
	if(isset($_POST['username'])){
		if(isset($_POST['repassword'])){
			if($_POST['password'] == $_POST['repassword']){
				register($_POST['username'],$_POST['password']);
			}
		}
		elseif(isset($_POST['password'])){
			login($_POST['username'],$_POST['password']);
			header('LOCATION: ..');
		}
	}
}
elseif(isset($_GET['logout'])){
	if($_GET['logout'] == 'y'){
		logout();
		header('LOCATION: ..');
	}
	else{
		header('LOCATION: ..');
	}
}
else{
	header('LOCATION: ..'); 
}
?>
