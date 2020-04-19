<?php
session_start();
if (! isset($_SESSION["email"])) {
    die("ACCESS DENIED");
}
// If the user requested logout go back to index.php
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}
require_once "pdo.php";

if (isset($_POST['make']) && isset($_POST['model'])
  && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) <1 || strlen($_POST['year']) <1 || strlen($_POST['mileage']) <1) {
        $_SESSION['failure'] = 'All values are required';
    } elseif (! is_numeric($_POST['year']) || ! is_numeric($_POST['mileage'])) {
        $_SESSION['failure'] = 'Mileage and year must be numeric:';
    } else {
        // $_SESSION['make'] = $_POST['make'];
        // $_SESSION['year'] = $_POST['year'];
        // $_SESSION['mileage'] = $_POST['mileage'];
        $sql = "INSERT INTO autos (make,model, year, mileage)
                VALUES (:make,:model, :year, :mileage)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':make' => $_POST['make'],
          ':model' => $_POST['model'],
          ':year' => $_POST['year'],
          ':mileage' => $_POST['mileage']));
        $_SESSION['success']   = 'Record added';
        header("Location: index.php");
        return;
    }
    header("Location: add.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>3c1e97c3 - Welcome to Auto DataBase</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
    <?php echo('<h1>Tracking Autos for '. htmlentities($_SESSION["email"]) ."</h1>\n") ;
    if (isset($_SESSION["failure"])) {
        echo('<p style="color:red">'.htmlentities($_SESSION["failure"])."</p>\n");
        unset($_SESSION["failure"]);
    };
    ?>
    <form method='post' class="col-md-4">
    <p>Make
    <input type="text"  class="form-control" name="make"></p>
    <p>Model
    <input type="text" class="form-control" name="model" ></p>
    <p>Year
    <input type="text" class="form-control" name="year" ></p>
    <p>Mileage
    <input type="text" class="form-control" name="mileage"></p>
  <input type="submit" class="btn btn-primary" name = "Add" value="Add">
  <input type="submit" class="btn btn-default" name="cancel" value="Cancel">
</form>
    </div>
  </body>
</html>
