<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['restaurant_id'])){
        echo "Not logged in";
        exit;
    }
   
if($_SERVER["REQUEST_METHOD"]=="POST"){
     $id=$_POST['id'];
    $restaurant_id = $_SESSION['restaurant_id'];
    $foodname=$_POST['food_name'];
    $description=$_POST['description'];
    $price=$_POST['price'];
$getfood=$conn->prepare("SELECT * FROM foods WHERE id=? AND restaurant_id=?");
$getfood->execute([$id,$restaurant_id]);
$food=$getfood->fetch(PDO::FETCH_ASSOC);
    $foodimg=$food['image'];
   
    if(!empty($_FILES['food_image']['name'])){
         $img=$_FILES['food_image']['name'];
    $temp=$_FILES['food_image']['tmp_name'];
    $foodimg=time().$img;
         $folder="../uploads/foods/".$foodimg;
         move_uploaded_file($temp,$folder);
    }
   
    $updatefood=$conn->prepare("UPDATE foods SET food_name=?,description=?,price=?,image=?,status='pending' WHERE id=? AND restaurant_id=?");
    $updatefood->execute([$foodname,$description,$price,$foodimg,$id,$restaurant_id]);
    header("Location: ../restaurant/manage_food.php");
    exit;
}
?>