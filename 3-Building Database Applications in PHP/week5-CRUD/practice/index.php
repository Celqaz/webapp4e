<?php
session_start();
require_once "pdo.php";

if (isset($_SESSION["email"])) {
    $stmt = $pdo->query("SELECT autos_id,make,model,year,mileage FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
};
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Bin Li - Welcome to Auto DataBase</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
<h1>Welcome to the Automobiles Database</h1>
<?php
if (! isset($_SESSION["email"])) {
    echo '<p>
  <a href="login.php">Please Log In</a>
  </p>
  <p>
  Attempt to go to
  <a href="add.php">add data</a> without logging in
  </p>';
} else {
    if (count($rows) <1) {
        echo "no entry";
    } else {
        echo('<table border="1" class="table table-bordered">'."\n");
        foreach ($rows as $row) {
            echo "<tr><td>";
            echo(htmlentities($row['make']));
            echo("</td><td>");
            echo(htmlentities($row['model']));
            echo("</td><td>");
            echo(htmlentities($row['year']));
            echo("</td><td>");
            echo(htmlentities($row['mileage']));
            echo("</td><td>");
            echo('<a href="edit.php?user_id='.$row['autos_id'].'">Edit</a> / ');
            echo('<a href="delete.php?user_id='.$row['autos_id'].'">Delete</a>');
            echo("</td></tr>\n");
        };
        echo("</table>");
    };
    echo('
    <p>
      <a href="add.php">Add New Entry</a>
    </p>
    <p>
      <a href="logout.php">Logout</a>
    </p>');
};
?>

  </div>
  </body>
</html>
