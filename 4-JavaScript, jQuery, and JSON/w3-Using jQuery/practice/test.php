<?php
require_once('pdo.php');
$x = 'cool';
// $sql = 'INSERT INTO `position`(`profile_id`, `rank`, `year`, `description`)
// VALUES (:profile_id, :xrank, :year, :xdesc)';
$sql = 'INSERT INTO `position`(`profile_id`, `rank`, `year`, `description`)
VALUES (:profile_id, :xrank, :year, :xdesc)';
// $sql = "INSERT INTO `position`( `profile_id`, `rank`, `year`, `description`) VALUES (37, 88, 8888, 'TOP-desc')";
$stmt = $pdo->prepare($sql);
// $stmt -> execute();
$profile_id = 37;
$rank = 99;
$year =2049;
$desc = "Once upon a time";
$stmt -> execute(
    array(
     ':profile_id' => $profile_id,
     ':xrank' => $rank,
     ':year' => $year,
     ':xdesc' => $desc
     )
);
// try {
//     // $stmt -> execute();
//     $stmt -> execute(
//         array(
//          ':profile_id' => $profile_id,
//          ':rank' => $rank,
//          ':year' => $year,
//          ':desc' => $desc
//          )
//     );
// } catch (\Exception $e) {
//     $x='not so god';
// }

 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Test</title>
   </head>
   <body>
     <h1>Result</h1>
<?php
echo("<p>".$x."</p>")
 ?>
   </body>
 </html>
