<?php
session_start();
require_once "pdo.php";
require_once "util.php";

$stmt = $pdo->query("SELECT profile_id, user_id,first_name, last_name,headline FROM profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>4b53088e - Welcome to Resume Registry</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
<h1>Welcome to Resume Registry</h1>
<?php
if (! isset($_SESSION["name"]) || ! isset($_SESSION["user_id"])) {
    echo '<p>
  <a href="login.php">Please log in</a>
  </p>';
    echo('<table border="1" class="table table-bordered ">'."\n");
    echo('<tr>
<th>Name</th>
<th>Headline</th>
</tr>');
    foreach ($rows as $row) {
        echo "<tr><td>";
        echo("<a href='view.php?profile_id=".$row['profile_id']."'>");
        echo(htmlentities($row['first_name'].' '.$row['last_name']));
        echo("</a>");
        echo("</td><td>");
        echo(htmlentities($row['headline']));
        echo("</td>");
    }
    echo("</table>");
} else {
    // if (count($rows) <1) {
    if ($rows == false) {
        echo "<p>No rows found</p>";
    } else {
        flashMsg();
        echo('<table border="1" class="table table-bordered ">'."\n");
        foreach ($rows as $row) {
            echo "<tr><td>";
            echo("<a href='view.php?profile_id=".$row['profile_id']."'>");
            echo(htmlentities($row['first_name'].' '.$row['last_name']));
            echo("</a>");
            echo("</td><td>");
            echo(htmlentities($row['headline']));
            echo("</td><td>");
            echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
            echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
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
