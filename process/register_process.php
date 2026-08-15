<?php
include '../config/db.php';
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirm=$_POST['confirm_password'];
    if($name==''||$email==''||$password==''||$confirm==''){
        echo "All fileds required";
        exit;
    }
    if($password!=$confirm){
        echo "passwords do not match!";
        exit;
    }
    $fetch=$conn->prepare("SELECT email from users where email=?");
    $fetch->execute([$email]);
    $data=$fetch->fetch(PDO::FETCH_ASSOC);
    if($data){
        echo "email already registered";
        exit;
    }
    $hash=password_hash($password,PASSWORD_DEFAULT);
    $stmt=$conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
    $stmt->execute([$name,$email,$hash]);
   echo "success";
   exit;
}
?>