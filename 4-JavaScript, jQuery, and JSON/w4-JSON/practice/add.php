<?php
session_start();
require_once "pdo.php";
require_once "util.php";
require_once "bootstrap.php";
require_once "val.php";

if (! isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

// If the user requested logout go back to index.php
if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}
if (isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
    # 验证数据
    // $msg = validateProfile();
    $msg = validateData();
    if (is_string($msg)) {
        $_SESSION["error"] = $msg;
        header('Location: add.php');
        return;
    }

    // insertData($pdo);
    insertProfileData($pdo);
    $profile_id = $pdo->lastInsertId();
    // $msg = validatePos();
    // if (is_string($msg)) {
    //     $_SESSION["error"] = $msg;
    //     header('Location: add.php');
    //     return;
    // }
    insertDataPos($pdo, $profile_id);
    insertDataEdu($pdo, $profile_id);
    // insert edu
    // $msg = validateEdu();
    // if (is_string($msg)) {
    //     $_SESSION["error"] = $msg;
    //     header('Location: add.php');
    //     return;
    // }
    // insertDataEdu($pdo, $profile_id);

    $_SESSION['success'] = 'added';
    header('Location: index.php') ;
    return;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>4b53088e - Welcome to Auto DataBase</title>
  </head>
  <body>
    <div class="container">
    <?php echo('<h1>Adding Profile for '. htmlentities($_SESSION["name"]) ."</h1>\n") ;
    flashMsg();
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
    <p>
        Education:
    <input id='addEdu' type="submit" value="+">
    </p>
  <div class="container" id = "edu_fields">
    <!--generate sth -->
  </div>
    <p>
      Position:
      <input id='addPos' type="submit" value="+">
    </p>
    <div class="container" id = "position_fields">
      <!--generate sth -->
    </div>
    <p><input type="submit" class="btn btn-primary" value="Add"/>
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
  //Event handler
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
