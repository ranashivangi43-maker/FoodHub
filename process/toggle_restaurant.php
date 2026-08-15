<?php

session_start();
include '../config/db.php';

// Only logged-in admin can perform this action
if(!isset($_SESSION['userId'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

// Check restaurant ID
if(!isset($_GET['id'])){
    header("Location: ../admin/manage_restaurant.php");
    exit;
}

$restaurant_id = $_GET['id'];

// Get current status
$stmt = $conn->prepare("
    SELECT status
    FROM restaurants
    WHERE id = ?
");

$stmt->execute([$restaurant_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$restaurant){
    header("Location: ../admin/manage_restaurant.php");
    exit;
}

// Toggle status
if($restaurant['status'] == 'active'){
    $newStatus = 'inactive';
} else {
    $newStatus = 'active';
}

// Update database
$update = $conn->prepare("
    UPDATE restaurants
    SET status = ?
    WHERE id = ?
");

$update->execute([
    $newStatus,
    $restaurant_id
]);

// Go back to Manage Restaurants
header("Location: ../admin/manage_restaurant.php");
exit;

?>