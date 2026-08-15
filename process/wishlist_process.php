<?php
session_start();
include '../config/db.php';
$user_id=$_SESSION["userId"];
$food_id=$_GET['id']??'';
if($food_id==''){
    echo "food not found";
    exit;
}
$stmt=$conn->prepare("SELECT * FROM wishlist where user_id=? AND food_id=?");
$stmt->execute([$user_id,$food_id]);
$wishlist=$stmt->fetch(PDO::FETCH_ASSOC);
if($wishlist){
    $del=$conn->prepare("DELETE FROM wishlist where user_id=? AND food_id=?");
    $del->execute([$user_id,$food_id]);
}
else{
    $stmt=$conn->prepare("INSERT INTO wishlist(user_id,food_id)
VALUES(?,?)");
$stmt->execute([$user_id,$food_id]);
}
header ("Location: ../user/dashboard.php");
exit;
?>