<?php
session_start();
print "<head><link rel=stylesheet type=text/css href=style.css></head>";
print "<button><a href=Questions.php>Back to questions</a></button><br></br>";
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "cw2";
$conn       = new PDO("mysql:dbname=$dbname;host=$servername;", $username, $password);
$query      = $conn->prepare("select id from questions");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
$query6 = $conn->prepare("select ustatus,id from users where user = :name");
$query6->bindParam(":name", $_SESSION['user']);
$query6->execute();
$ustatus = $query6->fetch(PDO::FETCH_ASSOC);
foreach ($result as $r) {
    if (isset($_POST[$r['id']])) {
        $question      = $r['id'];
        $_SESSION['Q'] = $r['id'];
        $view          = $conn->prepare("update questions set views = views + 1 where id = :id");
        $view->bindParam(":id", $_SESSION['Q']);
        $view->execute();
        break;
    }
}
if (isset($_SESSION['Q'])) {
    $question = $_SESSION['Q'];
}
if (!isset($question)) {
    die("No question found!");
} else {
    for ($x = 1; $x < 99999; $x++) {
        if (isset($_POST['up' . $x])) {
            $gg = $conn->prepare("insert into answerupvotes(userid,answerid) values (:uid,:aid)");
            $gg->bindParam(":uid", $ustatus['id']);
            $gg->bindParam(":aid", $x);
            if ($gg->execute()) {
                $inc = $conn->prepare("update answers set upvotes = upvotes + 1 where id = :id");
                $inc->bindParam(":id", $x);
                $inc->execute();
            }
        } elseif (isset($_POST['down' . $x])) {
            $gg = $conn->prepare("insert into answerdownvotes(userid,answerid) values (:uid,:aid)");
            $gg->bindParam(":uid", $ustatus['id']);
            $gg->bindParam(":aid", $x);
            if ($gg->execute()) {
                $inc1 = $conn->prepare("update answers set downvotes = downvotes + 1 where id = :id");
                $inc1->bindParam(":id", $x);
                $inc1->execute();
            }
        } elseif (isset($_POST['rem' . $x])) {
            $inc = $conn->prepare("delete from questionanswers where answerid= :id");
            $inc->bindParam(":id", $x);
            $inc->execute();
            $inc2 = $conn->prepare("delete from answers where id = :id");
            $inc2->bindParam(":id", $x);
            $inc2->execute();
        }
    }
    if (isset($_POST['answer'])) {
        $selectid = $conn->prepare("select id from users where user = :user");
        $selectid->bindParam(":user", $_SESSION['user']);
        $selectid->execute();
        $idres  = $selectid->fetch(PDO::FETCH_ASSOC);
        $insert = $conn->prepare("insert into answers(answer,upvotes,downvotes,userID) values (:answer,0,0,:id)");
        $insert->bindParam(":answer", $_POST['answer']);
        $insert->bindParam(":id", $idres['id']);
        $insert->execute();
        $userques = $conn->prepare("select id from answers where answer = :post");
        $userques->bindparam(":post", $_POST['answer']);
        $userques->execute();
        $ids     = $userques->fetch(PDO::FETCH_ASSOC);
        $insert2 = $conn->prepare("insert into questionanswers(questionid,answerid) values (:qid,:aid)");
        $insert2->bindParam(":qid", $_SESSION['Q']);
        $insert2->bindParam(":aid", $ids['id']);
        $insert2->execute();
    }
    $query2 = $conn->prepare("select question from questions where id = :id");
    $query2->bindParam(":id", $question);
    $query2->execute();
    $result2 = $query2->fetch(PDO::FETCH_ASSOC);
    echo "THE QUESTION IS: ";
    echo $result2['question'];
    $query3 = $conn->prepare("select answerid from questionanswers where questionid = :id");
    $query3->bindParam(":id", $question);
    $query3->execute();
    $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);
    print "<table border=1>";
    print "<th>Answer</th><th>Upvotes</th><th>Downvotes</th><th>Answered by</th><th>Action</th>";
    foreach ($result3 as $r3) {
        $query4 = $conn->prepare("select answer,upvotes,downvotes,userID from answers where id = :id");
        $query4->bindParam(":id", $r3['answerid']);
        $query4->execute();
        $answer = $query4->fetch(PDO::FETCH_ASSOC);
        $query5 = $conn->prepare("select user from users where id = :id");
        $query5->bindParam(":id", $answer['userID']);
        $query5->execute();
        $username = $query5->fetch(PDO::FETCH_ASSOC);
        print "<tr>";
        print "<td>$answer[answer]</td>";
        print "<td>$answer[upvotes]</td>";
        print "<td>$answer[downvotes]</td>";
        print "<td>$username[user]</td>";
        if (isset($ustatus['ustatus'])) {
            if ($ustatus['ustatus'] >= 1) {
                print "<td>";
                print "<form action=Answers.php method=post><button type=submit name=up$r3[answerid] >Upvote</button><br></br><button type=submit name=down$r3[answerid]>Downvote</button><br></br>";
                if ($ustatus['ustatus'] == 2) {
                    print "<button type=submit name=rem$r3[answerid]>Remove</button>";
                }
                print "</form>";
            }
        } else {
            print "<td>";
            print "You need to log in for you to upvote/downvote!";
            print "</td>";
        }
        print "</tr>";
    }
    print "</table>";
    if (isset($ustatus['ustatus'])) {
        print "<form action=Answers.php method=post><input type=text name=answer></input><button type=submit>Add your answer</button></form>";
    }
}
?>
