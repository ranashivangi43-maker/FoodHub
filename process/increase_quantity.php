<?php
session_start();
include '../config/db.php';
$user_id=$_SESSION['userId'];
$food_id=$_GET['id']??'';
$increase=$conn->prepare("UPDATE cart SET quantity=quantity+1 WHERE user_id=? AND food_id=?");
$increase->execute([$user_id,$food_id]);
header ("Location: ../user/cart.php");
exit;
?>