<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['restaurant_id'])){
        echo "Not logged in";
        exit;
    }
    
        $id=$_GET['id'];
        $restaurant_id=$_SESSION['restaurant_id'];
        $getfood=$conn->prepare("SELECT * FROM foods WHERE id=? AND restaurant_id=?");
        $getfood->execute([$id,$restaurant_id]);
        $food=$getfood->fetch(PDO::FETCH_ASSOC);
        if(!$food){
            echo "Invalid food";
            exit;
        }
        $imagePath="../Uploads/foods/".$food['image'];
        if(file_exists($imagePath)){
            unlink($imagePath);
        }
        $delfood=$conn->prepare("DELETE FROM foods WHERE id=? AND restaurant_id=?");
        $delfood->execute([$id,$restaurant_id]);
        header("Location: ../restaurant/manage_food.php");
        exit;
    
?>