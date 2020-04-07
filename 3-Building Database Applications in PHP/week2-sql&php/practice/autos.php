<?php
require_once "pdo.php";
// Demand a GET parameter
if (! isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}

// If the user requested logout go back to index.php
if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}

// validation
$failure = false;
$success = false;
if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) <1) {
        $failure = 'Make is required';
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $failure = 'Mileage and year must be numeric:';
    } else {
        $sql = "INSERT INTO autos (make, year, mileage)
                VALUES (:make, :year, :mileage)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':make' => $_POST['make'],
          ':year' => $_POST['year'],
          ':mileage' => $_POST['mileage']));
        $success  = 'Record inserted';
    }
}
$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>d19b33f6 - Automobile Tracker</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
    <h1>
    <?php
    if (isset($_REQUEST['name'])) {
        echo "<p>Tracking Autos for: ";
        echo htmlentities($_REQUEST['name']);
        echo "</p>\n";
    }
    ?>
    </h1>
    <?php
    // Note triple not equals and think how badly double
    // not equals would work here...
    if ($failure !== false) {
        // Look closely at the use of single and double quotes
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
    } elseif ($success !== false) {
        echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
    }
    ?>
    <form method='post'>
    <p>Make
    <input type="text" name="make"></p>
    <p>Year
    <input type="text" name="year" ></p>
    <p>Mileage
    <input type="text" name="mileage"></p>
  <input type="submit" value="Add">
  <input type="submit" name="logout" value="Logout">
</form>
<h1>Automobiles</h1>
<?php
foreach ($rows as $row) {
        echo "<ul><li>";
        // echo($row['year']." <b>".$row['make']."</b> ".$row['mileage']);
        echo(htmlentities($row['year'])." ".htmlentities($row['make'])." ".htmlentities($row['mileage']));
        echo("</li></ul>\n");
    }
?>
    </div>
  </body>
</html>
