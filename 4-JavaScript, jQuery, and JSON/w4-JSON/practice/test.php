<?php
require_once "pdo.php";
// $sql = "SELECT institution.name,education.year
// FROM institution INNER JOIN education ON education.institution_id = institution.institution_id WHERE education.profile_id = :profile_id";
// $stmt  = $pdo->prepare($sql);
// $stmt -> execute(
//     array(
//         ':profile_id' => '97'
//       )
// );
// $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function eduInfo($pdo, $profile_id)
{
    //profile
    $stmt_pro = $pdo->prepare("SELECT profile_id, user_id,first_name, last_name, email, headline, summary FROM profile where profile_id= :xyz");
    $stmt_pro->execute(array(":xyz" => $profile_id));
    $arr['pro'] = $stmt_pro->fetch(PDO::FETCH_ASSOC);
    //pos
    $stmt_pos = $pdo->prepare("SELECT year,description FROM position where profile_id= :xyz");
    $stmt_pos->execute(array(":xyz" =>  $profile_id));
    $arr['pos'] = $stmt_pos->fetchAll(PDO::FETCH_ASSOC);
    //edu
    $stmt_edu  = $pdo->prepare("SELECT institution.name,education.year
  FROM institution INNER JOIN education ON education.institution_id = institution.institution_id WHERE education.profile_id = :profile_id ORDER BY rank");
    $stmt_edu -> execute(array(':profile_id' =>  $profile_id));
    $arr['edu']= $stmt_edu->fetchAll(PDO::FETCH_ASSOC);

    // $arr.append()
    return $arr;
}


print_r(eduInfo($pdo, 96));
