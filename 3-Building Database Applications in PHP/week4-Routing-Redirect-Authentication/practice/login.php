<?php
session_start();
// require_once "pdo.php";

// p' OR '1' = '1
if (isset($_POST['cancel'])) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}


$salt = 'XyZzy12*_';
// $md5 = hash('md5', 'XyZzy12*_php123');
$stored_hash = hash('md5', 'XyZzy12*_php123');
// 'a8609e8d62c043243c4e201cbb342862';  // Pw is meow123


if (isset($_POST["email"]) && isset($_POST["pass"])) {
    unset($_SESSION["email"]);  // Logout current user
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
    } elseif (strpos($_POST['email'], '@') == false) { # 判断是否含有@
        $_SESSION['error'] = "Email must have an at-sign (@)";
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ($check == $stored_hash) {
            // Redirect the browser to game.php
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["success"] = "Logged in.";
            error_log("Login success ".$_POST['email']);
            header('Location: view.php') ;
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            error_log("Login fail, bro ".$_POST['email']." $check");
        }
    }
    header("Location: login.php");
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>65b036a5-Login Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>65b036a5 - Please Log In</h1>
<?php
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
</html>
