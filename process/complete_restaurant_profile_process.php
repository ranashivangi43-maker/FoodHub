<?php

session_start();

include '../config/db.php';


/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Only Restaurant Owners
|--------------------------------------------------------------------------
*/

if ($_SESSION['role'] !== 'restaurant') {
    header("Location: ../login.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Only POST Requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../restaurant/complete_profile.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Form Data
|--------------------------------------------------------------------------
*/

$owner_id = $_SESSION['userId'];

$restaurant_name = trim($_POST['restaurant_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');


/*
|--------------------------------------------------------------------------
| Validate Fields
|--------------------------------------------------------------------------
*/

if (
    $restaurant_name === '' ||
    $phone === '' ||
    $address === ''
) {
    echo "All fields are required.";
    exit;
}


/*
|--------------------------------------------------------------------------
| Check Image
|--------------------------------------------------------------------------
*/

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo "Please upload a restaurant image.";
    exit;
}


$image = $_FILES['image'];


/*
|--------------------------------------------------------------------------
| Validate Image Type
|--------------------------------------------------------------------------
*/

$allowedTypes = [
    'image/jpeg',
    'image/jpg',
    'image/png'
];

if (!in_array($image['type'], $allowedTypes)) {
    echo "Only JPG, JPEG and PNG images are allowed.";
    exit;
}


/*
|--------------------------------------------------------------------------
| Create Unique Image Name
|--------------------------------------------------------------------------
*/

$extension = pathinfo($image['name'], PATHINFO_EXTENSION);

$imageName = time() . '_' . uniqid() . '.' . $extension;


/*
|--------------------------------------------------------------------------
| Upload Directory
|--------------------------------------------------------------------------
*/

$uploadDirectory = "../uploads/restaurants/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

$imagePath = $uploadDirectory . $imageName;


/*
|--------------------------------------------------------------------------
| Move Image
|--------------------------------------------------------------------------
*/

if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
    echo "Image upload failed.";
    exit;
}


/*
|--------------------------------------------------------------------------
| Check Existing Restaurant Profile
|--------------------------------------------------------------------------
*/

$check = $conn->prepare(
    "SELECT id FROM restaurants WHERE owner_id = ?"
);

$check->execute([$owner_id]);

if ($check->fetch(PDO::FETCH_ASSOC)) {

    echo "Restaurant profile already exists.";
    exit;
}


/*
|--------------------------------------------------------------------------
| Insert Restaurant
|--------------------------------------------------------------------------
*/

$insert = $conn->prepare(
    "INSERT INTO restaurants
    (name, address, phone, image, owner_id)
    VALUES (?, ?, ?, ?, ?)"
);

$insert->execute([
    $restaurant_name,
    $address,
    $phone,
    $imageName,
    $owner_id
]);


/*
|--------------------------------------------------------------------------
| Get Restaurant ID
|--------------------------------------------------------------------------
*/

$restaurant_id = $conn->lastInsertId();

$_SESSION['restaurant_id'] = $restaurant_id;


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: ../restaurant/dashboard.php");
exit;

?>