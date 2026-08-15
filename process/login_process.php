<?php
session_start();
include('../config/db.php');
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=trim($_POST['email']?? '');
    $password=$_POST['password']?? '';
    if(empty($email) || empty($password)){
        echo "All fields required!";
        exit;
    }
    $info=$conn->prepare("SELECT id,name,email,password,role FROM users WHERE email=?");
    $info->execute([$email]);
    $result=$info->fetch(PDO::FETCH_ASSOC);
    if(!$result){
        echo "Invalid email";
        exit;
    }
    if(!password_verify($password,$result['password'])){
        echo "invalid password";
        exit;
    }
    $_SESSION["userId"]=$result['id'];
    $_SESSION["userName"]=$result['name'];
    $_SESSION["userEmail"]=$result['email'];
    $_SESSION["role"]=$result['role'];

     if($result['role']=='admin'){
echo "admin";
exit;
     }
     elseif($result['role']=='restaurant'){
        $getrestaurant=$conn->prepare("SELECT id FROM restaurants WHERE owner_id=?");
        $getrestaurant->execute([$result['id']]);
        $restaurant=$getrestaurant->fetch(PDO::FETCH_ASSOC);
        $_SESSION['restaurant_id']=$restaurant['id'];
        if(!$restaurant){
    echo "Restaurant profile not found";
    exit;
        }
        else{
            echo "restaurant";
        exit;
        }
     }
    elseif($result['role']=='user'){
        echo "user";
        exit;
     }
     else{
    echo "Invalid role";
    exit;
}
}
?>