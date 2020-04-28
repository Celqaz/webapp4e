<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "val.php";
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
$profile_id = $_REQUEST['profile_id'];
if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    $msg =validateData();
    if (is_string($msg)) {
        $_SESSION["error"] = $msg;
        header('Location: edit.php?profile_id='.$profile_id);
        return;
    }
    if (info_update($pdo, $profile_id)) {
        $_SESSION['success'] = 'Profile updated';
        header('Location: index.php') ;
        return;
    }
}
//
    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        $_SESSION['error'] = 'Bad value for profile_id';
        header('Location: index.php') ;
        return;
    }

$data_array =resumeInfo($pdo, $_GET['profile_id']);

$fn = htmlentities($data_array['pro']['first_name']);
$ln = htmlentities($data_array['pro']['last_name']);
$em = htmlentities($data_array['pro']['email']);
$hl = htmlentities($data_array['pro']['headline']);
$sm = htmlentities($data_array['pro']['summary']);
// $profile_id = $row['profile_id'];

// $positions = loadPos($pdo, $profile_id);
// $educations = loadEdu($pdo, $profile_id);
?>
<html>
<body>
<title>1533e368 - Edit Profile</title>
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
<!-- Education -->
  <p>
      Education:
  <input id='addEdu' type="submit" value="+">
  </p>
<div class="container" id = "edu_fields">
  <!--generate sth -->

<div class="container" id = "edu_fields">
  <?php
  $countEdu = 0;
  // if (count($schools)>0) {
      foreach ($data_array['edu'] as $school) {
          $countEdu++;
          echo('<div id="edu'.$countEdu.'">' ."\n");
          echo('<p>Year:<input type="text" name="edu_year'.$countEdu.'"');
          echo('value="'.htmlentities($school['year']).'"/>'. "\n");
          echo('<input type="button" value="-" ');
          echo(' onclick = "$(\'#edu'.$countEdu.'\').remove();return false;">'."\n");
          echo("</p>\n");
          echo('<p>School:<input type="text" size="80" name="edu_school'.$countEdu.'" class="school" value="'.htmlentities($school['name']).'"/>'."\n");
          echo("\n</div>\n");
      }
  // }
echo("</div></p>\n");
?>
</div>
</p>
<!-- Position -->
  <p>
    Position:
    <input id='addPos' type="submit" value="+">
  <div class="container" id = "position_fields">
    <?php
    $pos = 0;
    foreach ($data_array['pos'] as $position) {
        $pos++;
        echo('<div id="position'.$pos.'">' ."\n");
        echo('<p>Year:<input type="text" name="year'.$pos.'"');
        echo('value="'.htmlentities($position['year']).'"/>'. "\n");
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
countEdu = 0;
$(document).ready(function(){
window.console && console.log('Document ready called');
// addPos
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
// addEdu
$('#addEdu').click(function(event){
event.preventDefault();
if ( countEdu >= 9 ) {
  alert("Maximum of nine education entries exceeded");
  return;
}
countEdu++;
window.console && console.log("Adding position" + countEdu);
var source = $('#edu_template').html();
window.console && console.log(source);
$('#edu_fields').append(source.replace(/@COUNT@/g, countEdu));
// $('#edu_fields').append(source);
// 需要click驱动Event handler
  $('.school').autocomplete({
    source: "school.php"
  });
});
});

</script>
<!--html替换  -->
<script id="edu_template" type="text">
<div id = "edu@COUNT@">
<p>Year:
<input type="text" name="edu_year@COUNT@" value="">
<input type="button" onclick="$('#edu@COUNT@').remove();return false;" value="-"><br></p>
<p>School:
<input type="text" size="80" name="edu_school@COUNT@" class="school" value="">
</p>
</div>

</script>
</body>
</html>
