<?php

session_start();

include '../config/db.php';


// Admin login check
if(!isset($_SESSION['userId'])){
    echo "Admin not logged in.";
    exit;
}

if($_SESSION['role'] != 'admin'){
    echo "Access denied";
    exit;
}


// Check request
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if($id == '' || $action == ''){
    echo "Invalid request";
    exit;
}


// Get restaurant_id of this food
$stmt = $conn->prepare("
    SELECT restaurant_id
    FROM foods
    WHERE id = ?
");

$stmt->execute([$id]);

$food = $stmt->fetch(PDO::FETCH_ASSOC);


// Food not found
if(!$food){
    echo "Food not found";
    exit;
}


// Decide status
if($action == 'approve'){

    $status = 'approved';

}
elseif($action == 'reject'){

    $status = 'rejected';

}
else{

    echo "Invalid action";
    exit;

}


// Update food status
$update = $conn->prepare("
    UPDATE foods
    SET status = ?
    WHERE id = ?
");

$update->execute([$status, $id]);


// Get restaurant ID
$restaurant_id = $food['restaurant_id'];


// Redirect back to same restaurant's food page
header(
    "Location: ../admin/view_restaurant_foods.php?id=" . $restaurant_id
);

exit;

?>