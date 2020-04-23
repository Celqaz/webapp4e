<?php
session_start();
require_once "pdo.php";
require_once "bootstrap.php";

if (! isset($_SESSION["name"]) || ! isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

// If the user requested logout go back to index.php
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}
//data validation
if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1
    ||  strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = 'All values are required';
        header("Location: add.php");
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO profile(user_id, first_name, last_name, email, headline, summary)
    VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
        ':user_id' => $_SESSION['user_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary']
      )
    );
    $_SESSION['success'] = 'added';
    header('Location: index.php') ;
    return;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>43b5c2f6 - Welcome to Auto DataBase</title>
  </head>
  <body>
    <div class="container">
    <?php echo('<h1>Adding Profile for '. htmlentities($_SESSION["name"]) ."</h1>\n") ;
    if (isset($_SESSION["error"])) {
        echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
        unset($_SESSION["error"]);
    };
    ?>
    <form method="post" class="col-md-4">
    <p>First Name:
    <input type="text" class="form-control" name="first_name" ></p>
    <p>Last Name:
    <input type="text" class="form-control" name="last_name" ></p>
    <p>Email:
    <input type="text" class="form-control" name="email"></p>
    <p>Headline:
    <input type="text" class="form-control" name="headline"></p>
    <p>Summary:
    <input type="text" class="form-control" name="summary"></p>
    <p><input type="submit" class="btn btn-primary" value="Add"/>
    <a href="index.php" class="btn btn-default">Cancel</a></p>
    </form>
    </div>
  </body>
</html>
