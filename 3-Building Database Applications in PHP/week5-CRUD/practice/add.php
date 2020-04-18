<?php
session_start();
if (! isset($_SESSION["email"])) {
    die("ACCESS DENIED");
}
// If the user requested logout go back to index.php
if (isset($_POST['cancel'])) {
    header('Location: view.php');
    return;
}
require_once "pdo.php";

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) <1) {
        $_SESSION['failure_add'] = 'Make is required';
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['failure_add'] = 'Mileage and year must be numeric:';
    } else {
        $_SESSION['make'] = $_POST['make'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['mileage'] = $_POST['mileage'];
        $sql = "INSERT INTO autos (make, year, mileage)
                VALUES (:make, :year, :mileage)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':make' => $_SESSION['make'],
          ':year' => $_SESSION['year'],
          ':mileage' => $_SESSION['mileage']));
        $_SESSION['success_add']   = 'Record inserted';
        header("Location: view.php");
        return;
    };
    header("Location: add.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>65b036a5 - Automobile Tracker</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
    <?php echo('<h1>Tracking Autos for '. htmlentities($_SESSION["email"]) ."</h1>\n") ;
    if (isset($_SESSION["failure_add"])) {
        echo('<p style="color:red">'.htmlentities($_SESSION["failure_add"])."</p>\n");
        unset($_SESSION["failure_add"]);
    };
    ?>
    <form method='post'>
    <p>Make
    <input type="text" name="make"></p>
    <p>Year
    <input type="text" name="year" ></p>
    <p>Mileage
    <input type="text" name="mileage"></p>
  <input type="submit" name = "Add" value="Add">
  <input type="submit" name="cancel" value="Cancel">
</form>
    </div>
  </body>
</html>
