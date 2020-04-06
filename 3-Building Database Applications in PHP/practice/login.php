<?php
require_once "pdo.php";

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

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if (isset($_POST['who']) && isset($_POST['pass'])) {
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $failure = "Email and password are required";
    } elseif (strpos($_POST['who'], '@') == false) { # 判断是否含有@
        $failure = "Email must have an at-sign (@)";
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ($check == $stored_hash) {
            // Redirect the browser to game.php
            header("Location: autos.php?name=".urlencode($_POST['who']));
            error_log("Login success ".$_POST['who']);
            return;
        } else {
            $failure = "Incorrect password";
            error_log("Login fail, bro ".$_POST['who']." $check");
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<title>d19b33f6-Login Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>d19b33f6 - Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ($failure !== false) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
