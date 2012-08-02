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

<h2>What is this place?</h2>
	<div class="homesub">
    Simply put, this is my website. I acquired the domain after deciding to start an open source project hosting website, but that sort of fell through. Since then, I've decided to use it as a personal web server, and now a simple forum. I've designed this forum from the ground up, and I'm excited to develop it further. I have no idea where this will lead, but I'm excited for it.
	</div>
    
<h2>Who are you?</h2>
	<div class="homesub">
    I am just your average web developer (well, noobie well developer anyways). I enjoy creating little websites, and getting people excited about them. I don't have much practice, but creating this forum has certainly sharpened my skills. Outside of web dev, I enjoy other types of programming, making things (I participate in the Maker Faire), drawing, and composing music with my hipster band. I go by the username <span style="color: #C00;"><i><b>rust</b></i></span> and sometimes Spy. You can send me personal emails at <a href="mailto:rust@bytelife.org">rust@bytelife.org</a>.
	</div>

<h2>How do I make an account?</h2>
	<div class="homesub">
    I haven't actually posted a link to the register page anywhere, so this is a legitimate question. Mostly, it's because I haven't really found a good place for it. If you would like to make an account, you can find the register page <a href="register.php">here</a>.
    </div>
    
<h2>Are there any rules?</h2>
	<div class="homesub">
    There's really only one rule: don't be a dick. It's pretty easy to follow. If you're doing something that you have to think twice about, don't do it. Along with that comes the sub-rule: don't post illegal content. Pretty much the same as not being a dick. If you follow that one rule, you won't get banned.
    </div>

<h2>How can I help?</h2>
	<div class="homesub">
    I'm pretty much the only developer on the project right now, but if you have any suggestions or comments, feel free to contact me at <a href="mailto:rust@bytelife.org">rust@bytelife.org</a> and I'll be happy to look into your qualms. 
    </div>

</div>
<div align="center"><p>-bytelife.org-</p></div> 
</body>
</html>