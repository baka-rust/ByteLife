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
<link rel="shortcut icon" type="image/x-icon" href="static/favicon.ico">
</head>

<body>
<?php getheader($board);?>

<div class="home">

<h2>categories</h2>
<div class="homesub">
<b><a href="anonymous">anonymous</a></b> - a random board where users remain completely anonymous<br />
<b><a href="technology">technology</a></b> - a board for discussion involving general technology<br />
<b><a href="gaming">gaming</a></b> - a board for discussion about video games<br />
<b><a href="ring_of_fire">ring_of_fire</a></b> - retained from the original <i>Zacaj's Forums</i>, it's the "anything goes" board<br />
</div>

<h2>updates</h2>
<div class="homesub">
<ul>
<?php getupdates();?>
</ul>
</div>

<h2>about</h2>
<div class="homesub"><span style="color: #C00;"><i><b>bytelife</b></i></span> is a basic forum designed to be as simple as possible whilst still maintaining advanced and useful functionality. It is designed and optimized to be the least bandwidth-intensive as possible, and to still retain functionality on mobile platforms.
Bytelife is a discussion forum centered around technology, however many sections do not directly reference this; even so, many of our users tend to be tech savvy.
If you have any suggested improvements to bytelife, or have any comments/concerns, please feel free to email myself, the lead developer, at <a href="mailto:rust@bytelife.org">rust@bytelife.org</a> and I will try and get back to you as soon as I can. Also, if you have any questions, please visit the <a href="faq.php">faq page</a>.
</div>

</div>
<div align="center"><p>-bytelife.org-</p></div> 	
</body>
</html>