<?php
include($_SERVER['DOCUMENT_ROOT'].'/static/config.php');
session_start();

function connect() {
	global $db_host, $db_user, $db_pass, $db;
	mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db($db) or die ('Could not connect to database.');
}

function register($username,$password) {
	$username = mysql_real_escape_string(strip_tags($username));
	$password = mysql_real_escape_string(strip_tags($password));
	$color = '#'.strtoupper(dechex(rand(0,10000000)));
	$salt = '!'.rand(0,10000000);
	$password = md5($salt.$password);
	$query = mysql_query("SELECT * FROM users WHERE username='$username'");
	if(mysql_num_rows($query) == 0){
		$ip = $_SERVER['REMOTE_ADDR'];
		mysql_query("INSERT INTO users (username,password,level,color,ip,salt) VALUES ('$username','$password','1','$color','$ip','$salt')");
		header('LOCATION: ../register.php?c=y');
	}
	else {
		header('LOCATION: ../register.php?c=n');
	}
}

function login($username,$password) {
	$username = mysql_real_escape_string(strip_tags($username));
	$password = mysql_real_escape_string(strip_tags($password));
	$query = mysql_query("SELECT * FROM users WHERE username='$username'");
	while($data = mysql_fetch_array($query)) {
		$password = md5($data['salt'].$password);
		if($data['password'] == $password) {
			$cooknum = rand();
			$_SESSION['username'] = $username;
			$_SESSION['cooknum'] = $cooknum;
			mysql_query("UPDATE users SET cookie='$cooknum' WHERE username='$username'");
			setcookie('username',$username,time()+86400,'/') or die(print("cookie1"));
			setcookie('cooknum',$cooknum,time()+86400,'/') or die(print("cookie2"));
		}
	}	
}

function logout() {
	setcookie('username','',time()-86400, '/');
	setcookie('cooknum','',time()-86400, '/');
	unset($_SESSION['username']);
	unset($_SESSION['cooknum']);
}

function loggedin() {
	if(isset($_COOKIE['username']) && isset($_COOKIE['cooknum'])) {
		$username = $_COOKIE['username'];
		$cooknum = $_COOKIE['cooknum'];
		$query = mysql_query("SELECT * FROM users WHERE username='$username' AND cookie='$cooknum'");
		if(mysql_num_rows($query) > 0) {
			$_SESSION['username'] = $_COOKIE['username'];
			$_SESSION['cooknum'] = $_COOKIE['cooknum'];
		}
		else {
			setcookie('username','',time()-86400);
			setcookie('cooknum','',time()-86400);
		}
	}
	if(isset($_SESSION['username']) && isset($_SESSION['cooknum']) && isset($_COOKIE['username']) && isset($_COOKIE['cooknum'])) {
		return true;
	}
}

function getcolor($username) {
	$username = mysql_real_escape_string(strip_tags($username));
	$query = mysql_query("SELECT color FROM users WHERE username='$username'");
	while($data = mysql_fetch_array($query)){
		return $data['color'];
	}
}

function getthreads($board,$page) {
	$board = mysql_real_escape_string(strip_tags($board));
	$page = mysql_real_escape_string(strip_tags($page));
	global $replynum;
	$page = mysql_real_escape_string($page);			//check if int, else throw error
	$startRange = $page * 10 - 10;
	$endRange = $page * 10;
	if($page == 1) {
		$query = mysql_query("SELECT * FROM $board WHERE parent='0' AND sticky='1' ORDER BY stamp DESC LIMIT $startRange, $endRange");
		if($query) {
			while($data = mysql_fetch_array($query)) {
				$replynum = 0;
				getnumreplies($board,$data['id']);
				$color = getcolor($data['username']);
				if(isset($_SESSION['username'])) {
					if(checkadmin($_SESSION['username'])) {
						print '<div class="post"><b><i><u>'.$data['title'].'</u></i></b> by <b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply"> [<a href="thread.php?thread='.$data['id'].'">view thread</a>] [<a href="post.php?d='.$data['id'].'">x</a>] [<a href="post.php?s='.$data['id'].'">s</a>] <img src="../static/sticky.gif"></span><div class="content"><p>'.$data['content'].'</p><div class="replynum"><i>'.$replynum.' replies</i></div></div></div><br />';
					}
					else {
						print '<div class="post"><b><i><u>'.$data['title'].'</u></i></b> by <b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply"> [<a href="thread.php?thread='.$data['id'].'">view thread</a>] <img src="../static/sticky.gif"></span><div class="content"><p>'.$data['content'].'</p><div class="replynum"><i>'.$replynum.' replies</i></div></div></div><br />';
					}
				}
				else {
					print '<div class="post"><b><i><u>'.$data['title'].'</u></i></b> by <b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply"> [<a href="thread.php?thread='.$data['id'].'">view thread</a>] <img src="../static/sticky.gif"></span><div class="content"><p>'.$data['content'].'</p><div class="replynum"><i>'.$replynum.' replies</i></div></div></div><br />';
				}
			}
		}
	}
	$query = mysql_query("SELECT * FROM $board WHERE parent='0' AND sticky='0' ORDER BY stamp DESC LIMIT $startRange, $endRange");
	if((mysql_num_rows($query) < 1) && ($page > 1)) {
		print '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
		//header("LOCATION: index.php");
	}
	while($data = mysql_fetch_array($query)){
		$replynum = 0;
		getnumreplies($board,$data['id']);
		$color = getcolor($data['username']);
		if(isset($_SESSION['username'])) {
			if(checkadmin($_SESSION['username'])) {
				print '<div class="post"><b><i><u>'.$data['title'].'</u></i></b> by <b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply"> [<a href="thread.php?thread='.$data['id'].'">view thread</a>] [<a href="post.php?d='.$data['id'].'">x</a>] [<a href="post.php?s='.$data['id'].'">s</a>]</span><div class="content"><p>'.$data['content'].'</p><div class="replynum"><i>'.$replynum.' replies</i></div></div></div><br />';
			}
			else {
				print '<div class="post"><b><i><u>'.$data['title'].'</u></i></b> by <b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply"> [<a href="thread.php?thread='.$data['id'].'">view thread</a>]</span><div class="content"><p>'.$data['content'].'</p><div class="replynum"><i>'.$replynum.' replies</i></div></div></div><br />';
			}
		}
		else {
			print '<div class="post"><b><i><u>'.$data['title'].'</u></i></b> by <b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply"> [<a href="thread.php?thread='.$data['id'].'">view thread</a>]</span><div class="content"><p>'.$data['content'].'</p><div class="replynum"><i>'.$replynum.' replies</i></div></div></div><br />';
		}
	}
}

function getnumreplies($board,$thread) {
	$board = mysql_real_escape_string(strip_tags($board));
	$thread = mysql_real_escape_string(strip_tags($thread));
	global $replynum;
	$query = mysql_query("SELECT * FROM $board WHERE parent='$thread'");
	if(mysql_num_rows($query) > 0){
		$replynum += mysql_num_rows($query);
		while($data = mysql_fetch_array($query)){
			getnumreplies($board,$data['id']);
		}
	}
}

function getthread($board,$thread) {
	$board = mysql_real_escape_string(strip_tags($board));
	$thread = mysql_real_escape_string(strip_tags($thread));
	$query = mysql_query("SELECT * FROM $board WHERE id='$thread'");
	if(mysql_num_rows($query) > 0){
		while($data = mysql_fetch_array($query)){
			if($data['parent'] != '0') {
				//return to index 
			}
			$color = getcolor($data['username']);
			if(isset($_SESSION['username'])) {
				if(checkadmin($_SESSION['username'])) {
					print '<div class="post"><b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply">[<a href="" onClick="toggle(\'reply '.$data['id'].'\'); return false;">reply</a>] [<a href="post.php?d='.$data['id'].'">x</a>]</span><div class="content"><p>'.$data['content'].'</p></div></div>';
				}
				else {
					print '<div class="post"><b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply">[<a href="" onClick="toggle(\'reply '.$data['id'].'\'); return false;">reply</a>]</span><div class="content"><p>'.$data['content'].'</p></div></div>';
				}
			}
			else {
				print '<div class="post"><b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply">[<a href="" onClick="toggle(\'reply '.$data['id'].'\'); return false;">reply</a>]</span><div class="content"><p>'.$data['content'].'</p></div></div>';
			}
			print '<div id="reply '.$data['id'].'" class="replybox"><form name="reply" action="../bin/post.php" method="post"><textarea name="content" rows="6" cols="70"></textarea><br /><input type="submit" value="Submit" /><input type="hidden" name="indent" value="1" /><input type="hidden" name="parent" value="'.$data['id'].'" /><input type="hidden" name="thread" value="'.$_GET['thread'].'" /><input type="hidden" name="board" value="'.$board.'" /></form></div>';
			getreplies($board,$data['id']);
		}
	}
	else {
		echo '<div align="center"><h2>error: specified thread does not exist.</h2></div>';
	}
}

function threadinfo($board,$thread) {
	$board = mysql_real_escape_string(strip_tags($board));
	$thread = mysql_real_escape_string(strip_tags($thread));
	$query = mysql_query("SELECT * FROM $board WHERE id='$thread'");
	while($data = mysql_fetch_array($query)) {
		return $data['title'];
	}
}

function getreplies($board,$index) {
	$board = mysql_real_escape_string(strip_tags($board));
	$index = mysql_real_escape_string(strip_tags($index));
	$query = mysql_query("SELECT * FROM $board WHERE parent='$index'");
	if(mysql_num_rows($query) > 0){
		while($data = mysql_fetch_array($query)) {
			$color = getcolor($data['username']);
			$indent = $data['indent'] * 50;
			$replyindent = $indent + 50;
			$postindent = $replyindent / 50;
			if(isset($_SESSION['username'])) {
				if(checkadmin($_SESSION['username'])) {
					print '<div style="padding-left: '.$indent.'px;"><div class="post"><b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply">[<a href="" onClick="toggle(\'reply '.$data['id'].'\'); return false;">reply</a>] [<a href="post.php?d='.$data['id'].'">x</a>]</span><div class="content"><p>'.$data['content'].'</p></div></div></div>';
				}
				else {
					print '<div style="padding-left: '.$indent.'px;"><div class="post"><b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply">[<a href="" onClick="toggle(\'reply '.$data['id'].'\'); return false;">reply</a>]</span><div class="content"><p>'.$data['content'].'</p></div></div></div>';
				}
			}
			else {
				print '<div style="padding-left: '.$indent.'px;"><div class="post"><b><div style="display:inline;color:'.$color.'">'.$data['username'].'</div></b> | '.$data['date'].' | '.$data['time'].' <span class="reply">[<a href="" onClick="toggle(\'reply '.$data['id'].'\'); return false;">reply</a>]</span><div class="content"><p>'.$data['content'].'</p></div></div></div>';
			}
			print '<div id="reply '.$data['id'].'" class="replybox" style="padding-left: '.$replyindent.'px;"><form name="reply" action="../bin/post.php" method="post"><textarea name="content" rows="6" cols="70"></textarea><br /><input type="submit" value="Submit" /><input type="hidden" name="indent" value="'.$postindent.'" /><input type="hidden" name="parent" value="'.$data['id'].'" /><input type="hidden" name="thread" value="'.$_GET['thread'].'" /><input type="hidden" name="board" value="'.$board.'" /></form></div>';
			getreplies($board,$data['id']);
		}
	}
}

function getupdates() {
	$query = mysql_query("SELECT * FROM updates ORDER BY id DESC LIMIT 3");
	while($data = mysql_fetch_array($query)) {
		print '<li>'.$data['update'].'</li>';
	}
}

function getheader($board) {
	$board = mysql_real_escape_string(strip_tags($board));
	$username = '';
	if(loggedin()){
		$username = $_COOKIE['username'];
		$username = mysql_real_escape_string(strip_tags($username));
	}
	$color = getcolor($username);
	if($board != 'root'){
		if(isset($_GET['thread'])){
			$title = threadinfo($board,$_GET['thread']);
			print '<div class="header"><a href="../index.php" class="title"><b>bytelife</b></a> | <a href="index.php" class="title">'.$board.'</a> | <a href="#" class="title">'.$title.'</a>';
			if(loggedin()){
				$username = $_SESSION['username'];
				print '<div class="header-info"><div style="display:inline;color:'.$color.'">'.$username.'</div> | [<a href="../bin/post.php?logout=y" class="title">logout</a>]</div></div><br />';

			}
			else {
				print '<div class="header-info">[<a href="" onClick="toggle(\'login\'); return false;" class="title">login</a>]</div></div><br />';
				print '<div id="login" class="loginbox"><div class="loginboxin"><form name="login" action="../bin/post.php" method="post"><table><tr><td>username</td><td><input type="text" name="username" /></td></tr><tr><td>password</td><td><input type="password" name="password" /></td></tr><tr><td></td><td><input type="submit" value="login" /></td></tr></table></table></form></div></div>';
			}
		}
		else {
			print '<div class="header"><a href="../index.php" class="title"><b>bytelife</b></a> | <a href="#" class="title">'.$board.'</a> | [<a href="" onClick="toggle(\'new thread\'); return false;">new thread</a>]';
			if(loggedin()){
				print '<div class="header-info"><div style="display:inline;color:'.$color.'">'.$username.'</div> | [<a href="../bin/post.php?logout=y" class="title">logout</a>]</div><br />';
			}
			else{
				print '<div class="header-info">[<a href="" onClick="toggle(\'login\'); return false;" class="title">login</a>]</div>';
				print '<br /><br /><div id="login" class="loginbox"><div class="loginboxin"><form name="login" action="../bin/post.php" method="post"><table><tr><td>username</td><td><input type="text" name="username" /></td></tr><tr><td>password</td><td><input type="password" name="password" /></td></tr><tr><td></td><td><input type="submit" value="login" /></td></tr></table></table></form></div></div>';
			}
			print '</div><br /><div id="new thread" class="replybox"><form name="reply" action="../bin/post.php" method="post"><input type="text" name="title" /><br><textarea name="content" rows="6" cols="70"></textarea><br /><input type="submit" value="Submit" /><input type="hidden" name="new" value="1" /><input type="hidden" name="board" value="'.$board.'" /></form></div>';
		}
	}
	else{
		if(loggedin()){
			print '<div class="header"><a href="index.php" class="title"><b>bytelife</b></a>';
			print '<div class="header-info"><div style="display:inline;color:'.$color.'">'.$username.'</div> | [<a href="../bin/post.php?logout=y" class="title">logout</a>]</div></div><br />';
		}
		else{
			print '<div class="header"><a href="index.php" class="title"><b>bytelife</b></a><div class="header-info">[<a href="" onClick="toggle(\'login\'); return false;" class="title">login</a>]</div></div><br />';
			print '<div id="login" class="loginbox"><div class="loginboxin"><form name="login" action="../bin/post.php" method="post"><table><tr><td>username</td><td><input type="text" name="username" /></td></tr><tr><td>password</td><td><input type="password" name="password" /></td></tr><tr><td></td><td><input type="submit" value="login" /></td></tr></table></table></form></div></div>';
		}
	}
}

function newpost($board,$username,$title,$content) {
	$board = mysql_real_escape_string(strip_tags($board));
	if($board == 'anonymous') {
		$username = 'anonymous';
	}
	else {
		$username = mysql_real_escape_string(strip_tags($username));
	}
	$title = mysql_real_escape_string(strip_tags($title));
	$body = mysql_real_escape_string(geturls(nl2br(strip_tags($content))));
	$date = date('m/j/Y');
	$time = date('g:i A');
	$stamp = time();
	mysql_query("INSERT INTO $board (title,username,parent,indent,content,date,time,stamp) VALUES ('$title','$username','0','0','$body','$date','$time','$stamp')");
}

function newreply($board,$username,$parent,$indent,$content) {
	$board = mysql_real_escape_string(strip_tags($board));
	if($board == 'anonymous') {
		$username = 'anonymous';
	}
	else {
		$username = mysql_real_escape_string(strip_tags($username));
	}
	$parent = mysql_real_escape_string(strip_tags($parent));
	$indent = mysql_real_escape_string(strip_tags($indent));
	$body = mysql_real_escape_string(geturls(nl2br(strip_tags($content))));
	$date = date('m/j/Y');
	$time = date('g:i A');
	$stamp = time();
	mysql_query("INSERT INTO $board (username,parent,indent,content,date,time,stamp) VALUES ('$username','$parent','$indent','$body','$date','$time','$stamp')");
}

function updatestamp($board,$id) {
	$board = mysql_real_escape_string(strip_tags($board));
	$id = mysql_real_escape_string(strip_tags($id));
	$stamp = time();
	mysql_query("UPDATE $board SET stamp='$stamp' WHERE id='$id'");
}

function bbcode($body) {
	if(substr($body,-1,1) != "\n") {
		$body .= "\n";
	}
	$body = str_replace(">", "&gt;", $body);
	$body = preg_replace('/^(&gt;[^\>](.*))\n/m', '<div class="quote">\\1</div>', $body);
	$body = preg_replace('@\[i\](.+?)\[\/i\]@i', '<i>$1</i>', $body);
	$body = preg_replace('@\[img\](.+?)\[\/img\]@i', '<a href=\"$1\" target=\"_blank\" /><img src=\"$1\" class=\"embed\" /></a>', $body);
	return $body;
}

function checkban($username) {
	$username = mysql_real_escape_string(strip_tags($username));
	$query = mysql_query("SELECT * FROM users WHERE username='$username' AND level='0'");
	if(mysql_num_rows($query) > 0) {
		return true;
	}
	else {
		return false;
	}
}

function checkadmin($username) {
	//$username = mysql_real_escape_string(strip_tags($username));
	$username = $_SESSION['username'];
	$query = mysql_query("SELECT * FROM users WHERE username='$username' AND level='9'");
	if(mysql_num_rows($query) > 0) {
		return true;
	}
	else {
		return false;
	}
}

function geturls($content) {
	$search = "/(http[s]*:\/\/[\S]+)/";
	$replace = "<a href='\${1}'>\${1}</a>";
	$output = preg_replace($search, $replace, $content);
	return $output;
}

function ban($username) {
	$username = mysql_real_escape_string(strip_tags($username));
	mysql_query("UPDATE users SET level='0' WHERE username='$username'");
	header("LOCATION: index.php");
}

function unban($username) {
	$username = mysql_real_escape_string(strip_tags($username));
	mysql_query("UPDATE users SET level='1' WHERE username='$username'");
	header("LOCATION: index.php");
}

function update($content) {
	$content = mysql_real_escape_string(strip_tags($content));
	mysql_query("INSERT INTO updates (`id`,`update`) VALUES (NULL, '$content');"); //not sure why the quotes need to be like that; should look into this
	//mysql_query("INSERT INTO updates (id,update) VALUES (NULL, '$content')"); //test to see if this works
	header("LOCATION: index.php");
}

function removepost($id,$board) {
	$id = mysql_real_escape_string(strip_tags($id));
	$board = mysql_real_escape_string(strip_tags($board));
	mysql_query("DELETE FROM $board WHERE id='$id'");
	header("LOCATION: index.php");
}

function stickypost($postnum,$board) {
	$query = mysql_query("SELECT * FROM $board WHERE id='$postnum' AND sticky='0'");
	if(mysql_num_rows($query) > 0) {
		mysql_query("UPDATE $board SET sticky='1' WHERE id='$postnum'");
	}
	elseif(mysql_num_rows($query) == 0) {
		mysql_query("UPDATE $board SET sticky='0' WHERE id='$postnum'");
	}
	else {
		return false;
	}
}

?>