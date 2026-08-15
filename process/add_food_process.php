<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['restaurant_id'])){
    echo "Not logged in";
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $restaurant_id = $_SESSION['restaurant_id'];

    $foodname = $_POST['food_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Main food image
    $img = $_FILES['food_image']['name'];
    $temp = $_FILES['food_image']['tmp_name'];

    $foodimg = time() . $img;

    $folder = "../uploads/foods/" . $foodimg;

    move_uploaded_file($temp, $folder);


    // Insert food
    $insertfood = $conn->prepare("
        INSERT INTO foods
        (restaurant_id, food_name, description, price, image, status)
        VALUES (?, ?, ?, ?, ?, 'pending')
    ");

    $insertfood->execute([
        $restaurant_id,
        $foodname,
        $description,
        $price,
        $foodimg
    ]);


    header("Location: ../restaurant/manage_food.php");
    exit;
}

?>