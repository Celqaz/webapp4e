<?php
session_start();
if (! isset($_SESSION["email"])) {
    die("Not logged in");
}
require_once "pdo.php";
$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>65b036a5 - View</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
    <?php echo('<h1>Tracking Autos for '. htmlentities($_SESSION["email"]) ."</h1>\n") ?>
    <h2>Automobiles</h2>
    <?php
        if (isset($_SESSION["success"])) {
            echo('<p style="color:green">'.htmlentities($_SESSION["success"])."</p>\n");
            unset($_SESSION["success"]);
        };
        if (isset($_SESSION["success_add"])) {
            echo('<p style="color:green">'.htmlentities($_SESSION["success_add"])."</p>\n");
            unset($_SESSION["success_add"]);
        };
        foreach ($rows as $row) {
            echo "<ul><li>";
            // echo($row['year']." <b>".$row['make']."</b> ".$row['mileage']);
            echo(htmlentities($row['year'])." ".htmlentities($row['make'])." / ".htmlentities($row['mileage']));
            echo("</li></ul>\n");
        }?>
        <a href="add.php">Add New</a>
        <span> | </span>
        <a href="logout.php">Logout</a>
        </div>
  </body>
</html>
