<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "bootstrap.php";

if (! isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

// Guardian: Make sure that user_id is present
if (! isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION["error"] = $msg;
        header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
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
        ':profile_id' => $_REQUEST['profile_id']
      )
    );

    //Clear out old positions data
    // Clear out the old position entries
    $sql = 'DELETE FROM position WHERE (profile_id = :profile_id)';
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute(
        array(
        ':profile_id' => $_REQUEST['profile_id']
      )
    );
    $profile_id = $_REQUEST['profile_id'];
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION["error"] = $msg;
        header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    }
    insertPosData($pdo, $profile_id);
    $_SESSION['success'] = 'Profile updated';
    header('Location: index.php') ;
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


$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sm = htmlentities($row['summary']);
$profile_id = $row['profile_id'];

$positions = loadPos($pdo, $_REQUEST['profile_id']);
?>
<title>4b53088e - Edit Profile</title>
<div class="container">
  <?php echo('<h1>Adding Profile for '. htmlentities($_SESSION["name"]) ."</h1>\n") ;
  flashMsg();
  ?>
  <form method="post" class="col-md-4">
  <p>First Name:
  <input type="text" class="form-control" name="first_name" value="<?= $fn?>"></p>
  <p>Last Name:
  <input type="text" class="form-control" name="last_name" value="<?= $ln?>"></p>
  <p>Email:
  <input type="text" class="form-control" name="email" value="<?= $em?>"></p>
  <p>Headline:
  <input type="text" class="form-control" name="headline" value="<?= $hl?>"></p>
  <p>Summary:
  <input type="text" class="form-control" name="summary" value="<?= $sm?>"></p>
  <p>
    Position:
    <input id='addPos' type="submit" value="+">
  <div class="container" id = "position_fields">
    <?php
    $pos = 0;
    foreach ($positions as $position) {
        $pos++;
        echo('<div id="position'.$pos.'">' ."\n");
        echo('<p>Year:<input type="text" name="year'.$pos.'"');
        echo('value="'.$position['year'].'"/>'. "\n");
        echo('<input type="button" value="-" ');
        echo(' onclick = "$(\'#position'.$pos.'\').remove();return false;">'."\n");
        echo("</p>\n");
        echo(' <textarea name="desc'.$pos.'" rows="8" cols="80">'."\n");
        echo(htmlentities($position['description'])."\n");
        echo("\n</textarea>\n</div>\n");
    } ?>
  <!--generate sth -->
  </div>
</p>
  <p><input type="submit" class="btn btn-primary" value="Save"/>
  <a href="index.php" class="btn btn-default">Cancel</a></p>
  </form>
</div>
<script type="text/javascript">
countPos = 0;
$(document).ready(function(){
window.console && console.log('Document ready called');
$('#addPos').click(function(event){
event.preventDefault();
if ( countPos >= 9 ) {
    alert("Maximum of nine position entries exceeded");
    return;
}
countPos++;
window.console && console.log("Adding position" + countPos);
$('#position_fields').append(
'<div id = "position'+countPos+'"> \
<p>Year: <input type="text" name="year'+countPos+'" value=""/> \
<input type="button" value="-" \
    onclick = "$(\'#position'+countPos+'\').remove();return false;"></p>\
<textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
</div>');
});
});
</script>
