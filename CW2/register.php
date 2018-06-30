<?php
if (isset($_POST['ruser'])){
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cw2";
$conn = new PDO("mysql:dbname=$dbname;host=$servername;", $username, $password);
$query = $conn -> prepare("insert into users(user,pass,ustatus,answer) values (:user,:pass,1,:answer)");
$query -> bindParam(":user",$_POST['ruser']);
$query -> bindParam(":pass",$_POST['rpass']);
$query -> bindParam(":answer",$_POST['sanswer']);
$query-> execute();
echo "you have registered succesfully";
header('Location: login.php');}
?>
<html>
<head><link rel=stylesheet type=text/css href=style.css></head>
<body>
<form method="post" action="register.php">
Username:<input name="ruser" type="text"></input><br>
Password:<input name="rpass" type="password"></input><br>
Secret question:<select><option>Whats your mum's name?</option><option>Where were you born?</option><option>What year were you born in?</option></select><br>
<input type=text name=sanswer placeholder='type your answer here'></input><br>
<input type=submit></input>
<button><a href=login.php>Go back to login page</a></button>
</form>
</body>
</html>
