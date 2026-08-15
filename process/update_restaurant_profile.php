<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

if($_SESSION['role'] != 'restaurant'){
    header("Location: ../index.php");
    exit;
}

$owner_id = $_SESSION['userId'];

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: ../restaurant/edit_profile.php");
    exit;
}


/* Get form data */

$owner_name = trim($_POST['owner_name'] ?? '');
$owner_email = trim($_POST['owner_email'] ?? '');
$restaurant_name = trim($_POST['restaurant_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');


/* Basic validation */

if(
    $owner_name == '' ||
    $owner_email == '' ||
    $restaurant_name == '' ||
    $phone == '' ||
    $address == ''
){
    echo "All fields are required.";
    exit;
}


/* Check whether email belongs to another user */

$stmt = $conn->prepare("
    SELECT id
    FROM users
    WHERE email = ?
    AND id != ?
");

$stmt->execute([$owner_email, $owner_id]);

if($stmt->fetch(PDO::FETCH_ASSOC)){
    echo "Email already exists.";
    exit;
}


/* Get current restaurant */

$stmt = $conn->prepare("
    SELECT id, image
    FROM restaurants
    WHERE owner_id = ?
");

$stmt->execute([$owner_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$restaurant){
    echo "Restaurant profile not found.";
    exit;
}


$restaurant_id = $restaurant['id'];
$current_image = $restaurant['image'];


/* Update image only if a new image was selected */

$new_image = $current_image;

if(
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
){

    $original_name = $_FILES['image']['name'];

    $extension = strtolower(
        pathinfo($original_name, PATHINFO_EXTENSION)
    );

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

    if(!in_array($extension, $allowed_extensions)){
        echo "Invalid image format.";
        exit;
    }

    $new_image = time() . '_' . uniqid() . '.' . $extension;

    $temp = $_FILES['image']['tmp_name'];

    $folder = "../uploads/restaurants/" . $new_image;

    if(!move_uploaded_file($temp, $folder)){
        echo "Image upload failed.";
        exit;
    }
}


/* Update users table */

$stmt = $conn->prepare("
    UPDATE users
    SET name = ?, email = ?
    WHERE id = ?
");

$stmt->execute([
    $owner_name,
    $owner_email,
    $owner_id
]);


/* Update restaurants table */

$stmt = $conn->prepare("
    UPDATE restaurants
    SET name = ?,
        phone = ?,
        address = ?,
        image = ?
    WHERE id = ?
");

$stmt->execute([
    $restaurant_name,
    $phone,
    $address,
    $new_image,
    $restaurant_id
]);


/* Update session name */

$_SESSION['userName'] = $owner_name;


/* Redirect */

header("Location: ../restaurant/edit_profile.php?updated=1");
exit;

?>