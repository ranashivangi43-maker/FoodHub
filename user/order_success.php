<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['userId'];

$order_id = $_GET['order_id'] ?? '';

if(empty($order_id)){
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT 
        orders.id,
        orders.total_amount,
        orders.address,
        orders.phone,
        orders.payment_method,
        orders.paymment_status,
        orders.status,
        orders.created_at
    FROM orders
    WHERE orders.id = ? 
    AND orders.user_id = ?
");

$stmt->execute([$order_id, $user_id]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$order){
    echo "Order not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order Confirmed | FoodHub</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="../assets/css/user.css">

</head>

<body class="order-success-page">

    <!-- Navbar -->

    <nav class="user-navbar navbar navbar-expand-lg navbar-dark">

        <div class="container">

            <a href="dashboard.php" class="user-brand">
                <i class="bi bi-egg-fried brand-icon"></i>
                FoodHub
            </a>

        </div>

    </nav>


    <!-- Success -->

    <main class="order-success-wrapper">

        <div class="order-success-card">

            <!-- Success Icon -->

            <div class="order-success-icon">

                <i class="bi bi-check-lg"></i>

            </div>


            <!-- Heading -->

            <span class="order-success-label">
                ORDER CONFIRMED
            </span>

            <h1>
                Your order has been placed!
            </h1>

            <p class="order-success-message">
                Thank you for ordering with FoodHub.
                Your food is being prepared and will be delivered to you soon.
            </p>


            <!-- Order Number -->

            <div class="success-order-number">

                <span>Order Number</span>

                <strong>
                    #<?php echo htmlspecialchars($order['id']); ?>
                </strong>

            </div>


            <!-- Order Details -->

            <div class="success-details">

                <div class="success-detail-row">

                    <span>
                        <i class="bi bi-wallet2"></i>
                        Payment Method
                    </span>

                    <strong>
                        <?php
                        if($order['payment_method'] == 'COD'){
                            echo 'Cash on Delivery';
                        }
                        else{
                            echo 'Razorpay';
                        }
                        ?>
                    </strong>

                </div>


                <div class="success-detail-row">

                    <span>
                        <i class="bi bi-credit-card"></i>
                        Payment Status
                    </span>

                    <strong class="success-status">

                        <?php
                        if($order['payment_method'] == 'COD'){
                            echo 'Pay on Delivery';
                        }
                        else{
                            echo 'Paid';
                        }
                        ?>

                    </strong>

                </div>


                <div class="success-detail-row">

                    <span>
                        <i class="bi bi-cash"></i>
                        Total Amount
                    </span>

                    <strong>
                        ₹<?php echo number_format($order['total_amount'], 2); ?>
                    </strong>

                </div>


                <div class="success-detail-row">

                    <span>
                        <i class="bi bi-geo-alt"></i>
                        Delivery Address
                    </span>

                    <strong class="success-address">
                        <?php echo htmlspecialchars($order['address']); ?>
                    </strong>

                </div>

            </div>


            <!-- COD Message -->

            <?php if($order['payment_method'] == 'COD'){ ?>

                <div class="cod-message">

                    <i class="bi bi-info-circle"></i>

                    <div>

                        <strong>Cash on Delivery</strong>

                        <p>
                            Please keep
                            <strong>
                                ₹<?php echo number_format($order['total_amount'], 2); ?>
                            </strong>
                            ready when your order arrives.
                        </p>

                    </div>

                </div>

            <?php } ?>


            <!-- Actions -->

            <div class="success-actions">

                <a
                    href="my_orders.php"
                    class="success-primary-btn"
                >
                    <i class="bi bi-receipt"></i>
                    View My Orders
                </a>

                <a
                    href="dashboard.php"
                    class="success-secondary-btn"
                >
                    Continue Shopping
                </a>

            </div>

        </div>

    </main>


</body>

</html>