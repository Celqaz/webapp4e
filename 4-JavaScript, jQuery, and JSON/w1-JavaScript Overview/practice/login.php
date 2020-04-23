<?php
session_start();
require_once "pdo.php";

// p' OR '1' = '1
if (isset($_POST['cancel'])) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

if (isset($_POST["email"]) && isset($_POST["pass"])) {
//     unset($_SESSION["email"]);
    $stmt = $pdo->prepare("SELECT * FROM users where email = :xyz");
    $stmt->execute(array(":xyz" => $_POST['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $salt = 'XyZzy12*_';
    $check = hash('md5', $salt.$_POST['pass']);
    if ($check == $row['password']) {
        // Redirect the browser to game.php
        $_SESSION["name"] = $row['name'];
        $_SESSION["user_id"] = $row['user_id'];
        error_log("Login success ".$_POST['email']);
        header('Location: index.php') ;
        return;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>43b5c2f6-Login Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>43b5c2f6 - Please Log In</h1>
<?php
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">pass</label>
<input type="text" name="pass" id="pass"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<a href="index.php">Cancel</a>
</form>
<script type="text/javascript">
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('pass').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</div>
</body>
</html>
