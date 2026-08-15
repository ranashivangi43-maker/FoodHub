<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}
$id=$_SESSION["userId"];
$food_id=$_GET['id']??'';
if($food_id==''){
    echo "Invalid Food";
    exit;
}
$delete=$conn->prepare("DELETE FROM cart WHERE user_id=? AND food_id=?");
$delete->execute([$id,$food_id]);
header("Location: ../user/cart.php");
exit;
?>