<?php
session_start();
require_once "pdo.php";
require_once "util.php";

$data_array =eduInfo($pdo, $_GET['profile_id']);
// TABLE profile
$stmt = $pdo->prepare("SELECT profile_id, user_id,first_name, last_name, email, headline, summary FROM profile where profile_id= :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));



// $row = $stmt->fetch(PDO::FETCH_ASSOC);
// $data_array['pro'];
// error_log($row);
// TABLE position
$stmt2 = $pdo->prepare("SELECT year,description FROM position where profile_id= :xyz");
$stmt2->execute(array(":xyz" => $_GET['profile_id']));


$rowsPos = $stmt2->fetchAll(PDO::FETCH_ASSOC);
// Education Information
$rowsEdu = eduInfo($pdo);
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
        <p>Education</p>
        <?php
        echo('<ul>');
        foreach ($rowsEdu as $rowEdu) {
            echo('<li>'.$rowEdu['year'].': '.$rowEdu['name'].'</li>');
        }
        echo('</ul>');
         ?>
        <p>Position</p>
        <?php
        // print_r($rowsPos);
        echo('<ul>');
        foreach ($rowsPos as $rowPos) {
            echo('<li>'.$rowPos['year'].': '.$rowPos['description'].'</li>');
        }
        echo('</ul>');
         ?>
        <a href="index.php">Done</a>
        </div>
  </body>
</html>
