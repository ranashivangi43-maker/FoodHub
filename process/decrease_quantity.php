<?php
session_start();
include '../config/db.php';
$user_id=$_SESSION['userId'];
$food_id=$_GET['id']??'';
$decrease=$conn->prepare("UPDATE cart SET quantity=CASE
    WHEN quantity>1 THEN quantity-1
    ELSE 1
    END
    WHERE user_id=? AND food_id=?");
$decrease->execute([$user_id,$food_id]);
header ("Location: ../user/cart.php");
exit;
?>