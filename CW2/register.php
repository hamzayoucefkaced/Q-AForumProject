<?php
if (isset($_POST['ruser'])) {
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "cw2";
    $conn       = new PDO("mysql:dbname=$dbname;host=$servername;", $username, $password);
    $query      = $conn->prepare("select answer from users where user=:user");
    $query->bindParam(":user", $_POST['ruser']);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result['answer'] == $_POST['sanswer']) {
        $query2 = $conn->prepare("update users set pass=:pass where user=:user");
        $query2->bindParam(":pass", $_POST['npass']);
        $query2->bindParam(":user", $_POST['ruser']);
        $query2->execute();
        echo "you have recovered your account succesfully";
        header('Location: login.php');
    }
}
?>
<html>
<head><link rel=stylesheet type=text/css href=style.css></head>
<body>
<form method="post" action="recover.php">
Username:<input name="ruser" type="text"></input><br>
New Password:<input name="npass" type="password"></input><br>
Answer to your secret question:<input type=text name=sanswer placeholder='type your answer here'></input><br>
<input type=submit></input>
<button><a href=login.php>Go back to login page</a></button>
</form>
</body>
</html>
