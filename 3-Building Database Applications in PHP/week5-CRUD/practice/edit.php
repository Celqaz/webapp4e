<?php
session_start();
if (! isset($_SESSION["email"])) {
    die("ACCESS DENIED");
}
require_once "pdo.php";
require_once "bootstrap.php";
// If the user requested logout go back to index.php
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) <1 || strlen($_POST['model']) <1) {
        $_SESSION['error'] = 'Make and model is required';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage and year must be numeric:';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    } else {
        $sql = "UPDATE autos SET make = :make,
              model = :model, year = :year, mileage = :mileage
              WHERE autos_id = :autos_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':make' => $_POST['make'],
          ':model' => $_POST['model'],
          ':year' => $_POST['year'],
          ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
        $_SESSION['success'] = 'Record updated';
        header('Location: index.php') ;
        return;
    }
}

// Guardian: Make sure that autos_id is present
if (! isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header('Location: index.php') ;
    return;
}
$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = $row['year'];
$mileage = $row['mileage'];
$autos_id  = $row['autos_id'];
?>
<!-- html content -->
<title>3c1e97c3 - Welcome to Auto DataBase</title>
<div class="container">
  <h1>Edit User</h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
<form method="post" class="col-md-4">
<p>Make
<input type="text" class="form-control" name="make" value="<?= $make ?>"></p>
<p>Model
<input type="text" class="form-control" name="model" value="<?= $model ?>"></p>
<p>Year
<input type="text" class="form-control" name="year" value="<?= $year ?>"></p>
<p>Mileage
<input type="text" class="form-control" name="mileage" value="<?= $mileage ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" class="btn btn-primary" value="Save"/>
<input type="submit" class="btn btn-default" name="cancel" value="Cancel"></p>
</form>
</div>
