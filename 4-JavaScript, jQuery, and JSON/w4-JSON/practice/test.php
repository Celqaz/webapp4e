<?php
require_once "pdo.php";
$stmt =$pdo->prepare("SELECT * FROM institution WHERE name = :name");
$stmt -> execute(
    array(
      ':name' => 'Mississippi State versity'
    )
);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
// echo "<p>string</p>";
print_r($row);

if ($row === false) {
    echo'Wrong';
}
