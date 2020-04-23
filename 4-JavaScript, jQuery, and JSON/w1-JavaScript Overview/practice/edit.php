<?php
require_once "pdo.php";
require_once "bootstrap.php";
session_start();
if (! isset($_SESSION["name"]) || ! isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1
    ||  strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email address must contain @';
        header('Location: edit.php?profile_id='.htmlentities($_GET['profile_id']));
        return;
    }

    $sql = "UPDATE profile SET user_id = :user_id, first_name = :first_name, last_name = :last_name,
            email = :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
        ':user_id' => $_SESSION['user_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $_POST['profile_id']
      )
    );
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php') ;
    return;
}

// Guardian: Make sure that user_id is present
if (! isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php') ;
    return;
}

// Flash pattern
// if (isset($_SESSION['error'])) {
//     echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
//     unset($_SESSION['error']);
// }

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sm = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>
<title>43b5c2f6 - Edit Profile</title>
<div class="container">
<h1>Edit Profile</h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
 ?>
<form method="post" class="col-md-4">
<p>First Name:
<input type="text" class="form-control" name="first_name" value="<?= $fn ?>"></p>
<p>Last Name:
<input type="text" class="form-control" name="last_name" value="<?= $ln ?>"></p>
<p>Email:
<input type="text" class="form-control" name="email" value="<?= $em ?>"></p>
<p>Headline:
<input type="text" class="form-control" name="headline" value="<?= $hl ?>"></p>
<p>Summary:
<input type="text" class="form-control" name="summary" value="<?= $sm ?>"></p>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p><input type="submit" class="btn btn-primary" value="Save"/>
<a href="index.php" class="btn btn-default">Cancel</a></p>
</form>
</div>
