<?php
session_start();
require_once "pdo.php";
require_once "util.php";
$data_array =resumeInfo($pdo, $_GET['profile_id']);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>1533e368 - Profile Information</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
      <h1>Profile Information</h1>
        <?php
        echo('<p>First Name: '.htmlentities($data_array['pro']["first_name"]).'</p>');
        echo('<p>Last Name: '.htmlentities($data_array['pro']["last_name"]).'</p>');
        echo('<p>Email: '.htmlentities($data_array['pro']["email"]).'</p>');
        echo('<p>Headline:<br>'.htmlentities($data_array['pro']["headline"]).'</p>');
        echo('<p>Summary:<br>'.htmlentities($data_array['pro']["summary"]).'</p>');
        ?>
        <p>Education</p>
        <?php
        echo('<ul>');
        foreach ($data_array['edu'] as $rowEdu) {
            echo('<li>'.htmlentities($rowEdu['year']).': '.htmlentities($rowEdu['name']).'</li>');
        }
        echo('</ul>');
         ?>
        <p>Position</p>
        <?php
        // print_r($rowsPos);
        echo('<ul>');
        foreach ($data_array['pos'] as $rowPos) {
            echo('<li>'.htmlentities($rowPos['year']).': '.htmlentities($rowPos['description']).'</li>');
        }
        echo('</ul>');
         ?>
        <a href="index.php">Done</a>
        </div>
  </body>
</html>
