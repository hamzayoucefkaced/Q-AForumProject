<?php
session_start();
$_SESSION["user"]=null;
$_SESSION["pass"]=null;
?>
<html>
<head><link rel=stylesheet type=text/css href=style.css></head>
<body>
QUEUE UNDERFLOW LOGIN PAGE!<br>
<form method="post" action="Questions.php">
Username:<input name="user" type="text" pattern="{1,}" required title="Input a value"></input><br></br>
Password:<input name="pass" type="password" pattern="{1,}" required title="Input a value"></input><br></br>
<input type="submit" value="Login"></input>
</form>
<button><a href=register.php>Register</a></button>
</body>
</html>