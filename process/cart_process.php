<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

$id = $_SESSION["userId"];
$food_id = $_GET['id'] ?? '';

if($food_id == ''){
    echo "Invalid food";
    exit;
}

/* Get food name */
$foodStmt = $conn->prepare("
    SELECT food_name
    FROM foods
    WHERE id = ?
");
$foodStmt->execute([$food_id]);
$food = $foodStmt->fetch(PDO::FETCH_ASSOC);

if(!$food){
    echo "Food not found";
    exit;
}

/* Check if food already exists in cart */
$check = $conn->prepare("
    SELECT *
    FROM cart
    WHERE user_id = ?
    AND food_id = ?
");

$check->execute([$id, $food_id]);

$result = $check->fetch(PDO::FETCH_ASSOC);

if($result){

    /* Increase quantity */
    $stmt = $conn->prepare("
        UPDATE cart
        SET quantity = quantity + 1
        WHERE user_id = ?
        AND food_id = ?
    ");

    $stmt->execute([$id, $food_id]);

} else {

    /* Add new food */
    $stmt = $conn->prepare("
        INSERT INTO cart(user_id, food_id, quantity)
        VALUES(?,?,1)
    ");

    $stmt->execute([$id, $food_id]);
}

/* Return to dashboard with success information */
header(
    "Location: ../user/dashboard.php?cart_added=1&food_name="
    . urlencode($food['food_name'])
);

exit;

?>