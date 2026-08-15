<?php
session_start();
include '../config/db.php';
// $keySecret = "mohjRHVE837512cfW018z2JR";
if(!isset($_SESSION['userId'])){
    echo "Not logged in";
        exit;
}
$user_id=$_SESSION['userId'];
$fullName=$_POST["name"];
$phone=$_POST["phone"];
$address=$_POST["address"];
$paymentMethod=$_POST["payment_method"];
$payment_id = $_POST['razorpay_payment_id'] ?? '';
$razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
$razorpay_signature = $_POST['razorpay_signature'] ?? '';

$payment_status = "pending";

if($paymentMethod == "RozarPay"){

    if(empty($payment_id)){
        echo "Payment Failed";
        exit;
    }

    $payment_status = "paid";
}
if(isset($_POST['cart_order'])){
    $stmt=$conn->prepare("SELECT cart.*,foods.price FROM cart
    INNER JOIN foods
    ON cart.food_id=foods.id
    where user_id=?");
    $stmt->execute([$user_id]);
    $cart=$stmt->fetchAll(PDO::FETCH_ASSOC);


    $total=0;
    foreach($cart as $item){
        $total+=$item['price']*$item['quantity'];
    }

$insert=$conn->prepare("INSERT INTO orders(user_id,total_amount,address,phone,payment_method,paymment_status,payment_id
) VALUES(?,?,?,?,?,?,?)");
$insert->execute([$user_id,$total,$address,$phone,$paymentMethod,$payment_status,$payment_id
]);
$order_id=$conn->lastInsertId();
    foreach($cart as $item){
        $insert2=$conn->prepare("INSERT INTO order_item(order_id,food_id,quantity,price)VALUES(?,?,?,?)");

        
$insert2->execute([$order_id,$item['food_id'],$item['quantity'],$item['price']]);
    }
$del=$conn->prepare("DELETE FROM cart where user_id=?");
$del->execute([$user_id]);
}
else{
    $food_id=$_POST['food_id'];
if(!$food_id){
    echo "Food not found";
    exit;
}

$quantity=$_POST["quantity"];
if($quantity < 1){
    echo "Invalid quantity";
    exit;
}
$stmt=$conn->prepare("SELECT * FROM foods where id=?");
$stmt->execute([$food_id]);
$food=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$food){
    echo "Food not found";
    exit;
}
$total=$food['price']*$quantity;

$insert=$conn->prepare("INSERT INTO orders(user_id,total_amount,address,phone,payment_method,paymment_status,payment_id
) VALUES(?,?,?,?,?,?,?)");
$insert->execute([$user_id,$total,$address,$phone,$paymentMethod,$payment_status,$payment_id]);
$order_id=$conn->lastInsertId();
$insert2=$conn->prepare("INSERT INTO order_item(order_id,food_id,quantity,price)VALUES(?,?,?,?)");
$insert2->execute([$order_id,$food_id,$quantity,$food['price']]);
}


if($paymentMethod == "COD"){
    header("Location: ../user/order_success.php?order_id=".$order_id);
    exit;
}

?>