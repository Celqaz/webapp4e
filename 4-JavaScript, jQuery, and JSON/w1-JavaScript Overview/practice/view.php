<?php
session_start();
// if (! isset($_SESSION["email"])) {
//     die("Not logged in");
// }
require_once "pdo.php";
$stmt = $pdo->prepare("SELECT profile_id, user_id,first_name, last_name, email, headline, summary FROM profile where profile_id= :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>43b5c2f6 - Profile Information</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
      <h1>Profile Information</h1>
        <?php
        echo('<p>First Name: '.htmlentities($row["first_name"]).'</p>');
        echo('<p>Last Name: '.htmlentities($row["last_name"]).'</p>');
        echo('<p>Email: '.htmlentities($row["email"]).'</p>');
        echo('<p>Headline:<br>'.htmlentities($row["headline"]).'</p>');
        echo('<p>Summary:<br>'.htmlentities($row["summary"]).'</p>');
        ?>
        <a href="index.php">Done</a>
        </div>
  </body>
</html>
