<?php
require_once "pdo.php";
require_once "bootstrap.php";
session_start();


if (! isset($_SESSION["name"]) || ! isset($_SESSION["user_id"])) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}

if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
    try {
        $sql = "DELETE FROM profile WHERE profile_id = :zip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_POST['profile_id']));
        $_SESSION['success'] = 'Record deleted';
        header('Location: index.php') ;
        return;
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Delete Failed';
        header('Location: index.php') ;
        return;
    }
}

$stmt = $pdo->prepare("SELECT profile_id, first_name, last_name FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php') ;
    return;
}

?>
<title>43b5c2f6 - Welcome to Auto DataBase</title>
<div class="container">
  <h1>Deleting Profile</h1>
  <?php  echo "<p>First Name: ".$row['first_name']."</p>
  <p>Last Name: ".$row['last_name']."</p>";
 ?>
<!-- <p style="color: red; font-weight: bold;">Confirm: Deleting <?= htmlentities($row['make']) ?></p> -->
<form method="post">
  <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
  <input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>
</div>
