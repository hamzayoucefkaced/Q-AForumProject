<html>
<body>
<?php
print "<head><link rel=stylesheet type=text/css href=style.css></head>";
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cw2";
$conn = new PDO("mysql:dbname=$dbname;host=$servername;", $username, $password);
if((isset($_POST['user'])) && (isset($_POST['pass']))){
$user1 = $_POST['user'];
$pass1 = $_POST['pass'];
$_SESSION['user'] = $user1;
$_SESSION['pass'] = $pass1;}

$connect = $conn->prepare("select user from users where user = :user and pass = :pass");
$connect->bindParam(":user", $_SESSION['user']);
$connect->bindParam(":pass", $_SESSION['pass']);
$connect->execute();
$result = $connect->fetchAll(PDO::FETCH_ASSOC);
if (sizeof($result)==0){
echo "wrong username or password<br>";
print "<button><a href=recover.php>Recover your account</a></button>";}
else 
{
echo "YOU ARE LOGGED IN AS $_SESSION[user]!";
echo "<button><a href=login.php>Log out</a></button><br>";
echo "WELCOME TO QUEUE UNDERFLOW! WHERE ALL YOUR PROGRAMMING QUESTIONS ARE ANSWERED!";

if (isset($_SESSION['user']))
{
$user = $_SESSION['user'];
}
$conn =  new PDO("mysql:dbname=$dbname;host=$servername;", $username, $password);
for($x=1;$x<99999;$x++){
if (isset($_POST['rem'.$x])){
echo $x;
$gg = $conn -> prepare("delete from questions where id = :id");
$gg -> bindParam(":id",$x);
$gg -> execute();
}
}
if(isset($_POST['answer'])){
$connect1 = $conn -> prepare("select id,ustatus from users where user = :user");
$connect1->bindParam(":user",$user);
$connect1->execute();
$rs = $connect1->fetch(PDO::FETCH_ASSOC);
$connect = $conn -> prepare("insert into questions(question,askedby,views) values (:q,:user,0)");
$connect -> bindParam(":q",$_POST['answer']);
$connect -> bindParam(":user",$rs['id']);
$connect -> execute();
}
$connect3 = $conn -> prepare("select id,ustatus from users where user = :user");
$connect3->bindParam(":user",$user);
$connect3->execute();
$rs1 = $connect3->fetch(PDO::FETCH_ASSOC);
if(isset($_POST['rank'])){
$getquestions = $conn -> prepare("select question,user,views,a.id from questions a, users b where a.askedby = b.id order by a.views DESC");
$getquestions->execute();
$questions = $getquestions->fetchAll(PDO::FETCH_ASSOC);}
else {
$getquestions = $conn -> prepare("select question,user,views,a.id from questions a, users b where a.askedby = b.id");
$getquestions->execute();
$questions = $getquestions->fetchAll(PDO::FETCH_ASSOC);
}
print "<table border=1>";
print "<th>Question</th><th>Asked by</th><th>Views</th><th>Action</th>";
foreach($questions as $q){
print "<tr>";
print "<td>$q[question]</td>";
print "<td>$q[user]</td>";
print "<td>$q[views]</td>";
print "<td><form action=Answers.php method=post><button type=submit name=$q[id]>View</button></form>";
if ($rs1['ustatus']==2){
print "<form action=Questions.php method=post><button type=submit name=rem$q[id]>Remove</button></form>";}
print "</td>";
print "</tr>";}
print "</table>";
if (isset($_SESSION['user'])){
print "<form action=Questions.php method=post><input type=text name=answer></input><button type=submit>Add your Question</button></form>";
print "<form action=Questions.php method=post><input type=submit name=rank value='Rank by most viewed question'></input></form>";}
}




?>
</body>
</html>
