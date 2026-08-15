<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['userId'];

$stmt = $conn->prepare("
    SELECT 
        orders.id AS order_id,
        orders.total_amount,
        orders.created_at,
        orders.status,
        orders.payment_method,
        orders.paymment_status,

        order_item.quantity,
        order_item.price,

        foods.food_name,
        foods.image

    FROM orders

    INNER JOIN order_item
        ON orders.id = order_item.order_id

    INNER JOIN foods
        ON order_item.food_id = foods.id

    WHERE orders.user_id = ?

    ORDER BY orders.id DESC
");

$stmt->execute([$user_id]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Group items by order
|--------------------------------------------------------------------------
*/

$groupedOrders = [];

foreach ($orders as $order) {

    $orderId = $order['order_id'];

    if (!isset($groupedOrders[$orderId])) {
        $groupedOrders[$orderId] = [
            'order_id' => $order['order_id'],
            'total_amount' => $order['total_amount'],
            'created_at' => $order['created_at'],
            'status' => $order['status'],
            'payment_method' => $order['payment_method'],
            'paymment_status' => $order['paymment_status'],
            'items' => []
        ];
    }

    $groupedOrders[$orderId]['items'][] = [
        'food_name' => $order['food_name'],
        'image' => $order['image'],
        'quantity' => $order['quantity'],
        'price' => $order['price']
    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Orders | FoodHub</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- User CSS -->
    <link rel="stylesheet" href="../assets/css/user.css">

</head>

<body class="user-orders-page">


<!-- =========================================
     NAVBAR
========================================= -->
<?php include '../includes/user_navbar.php'; ?>

<!-- =========================================
     ORDERS PAGE
========================================= -->

<main class="orders-page">

    <div class="container">


        <!-- PAGE HEADER -->

        <div class="orders-page-heading">

            <span class="section-label">
                ORDER HISTORY
            </span>

            <h1>
                My Orders
            </h1>

            <p>
                Track and review your recent food orders.
            </p>

        </div>


        <?php if (count($groupedOrders) === 0) { ?>


            <!-- =========================================
                 EMPTY ORDERS
            ========================================= -->

            <div class="orders-empty">

                <div class="orders-empty-icon">

                    <i class="bi bi-bag-x"></i>

                </div>

                <h3>
                    No orders yet
                </h3>

                <p>
                    You haven't placed any orders yet.
                    Find something delicious and place your first order.
                </p>

                <a
                    href="dashboard.php"
                    class="orders-explore-btn"
                >

                    <i class="bi bi-search"></i>

                    Explore Foods

                </a>

            </div>


        <?php } else { ?>


            <!-- =========================================
                 ORDER LIST
            ========================================= -->

            <div class="orders-list">

                <?php foreach ($groupedOrders as $order) { ?>


                    <!-- =========================================
                         ORDER CARD
                    ========================================= -->

                    <div class="order-card">


                        <!-- ORDER HEADER -->

                        <div class="order-card-header">

                            <div>

                                <div class="order-id">

                                    <span>
                                        Order
                                    </span>

                                    #<?php echo $order['order_id']; ?>

                                </div>

                                <div class="order-date">

                                    <i class="bi bi-calendar3"></i>

                                    <?php
                                    echo date(
                                        'd M Y, h:i A',
                                        strtotime($order['created_at'])
                                    );
                                    ?>

                                </div>

                            </div>


                            <!-- STATUS -->

                            <div>

                                <?php
                                $status = strtolower(
                                    $order['status'] ?? 'pending'
                                );
                                ?>

                                <?php if ($status === 'pending') { ?>

                                    <span class="order-status status-pending">

                                        <i class="bi bi-clock"></i>

                                        Pending

                                    </span>

                                <?php } elseif ($status === 'confirmed') { ?>

                                    <span class="order-status status-confirmed">

                                        <i class="bi bi-check-circle"></i>

                                        Confirmed

                                    </span>

                                <?php } elseif ($status === 'delivered') { ?>

                                    <span class="order-status status-delivered">

                                        <i class="bi bi-check2-circle"></i>

                                        Delivered

                                    </span>

                                <?php } elseif ($status === 'cancelled') { ?>

                                    <span class="order-status status-cancelled">

                                        <i class="bi bi-x-circle"></i>

                                        Cancelled

                                    </span>

                                <?php } else { ?>

                                    <span class="order-status status-pending">

                                        <?php echo ucfirst($status); ?>

                                    </span>

                                <?php } ?>

                            </div>

                        </div>


                        <!-- =========================================
                             ORDER ITEMS
                        ========================================= -->

                        <div class="order-items">

                            <?php foreach ($order['items'] as $item) { ?>

                                <div class="order-item">


                                    <!-- IMAGE -->

                                    <div class="order-item-image">

                                        <img
                                            src="../uploads/foods/<?php echo htmlspecialchars($item['image']); ?>"
                                            alt="<?php echo htmlspecialchars($item['food_name']); ?>"
                                        >

                                    </div>


                                    <!-- DETAILS -->

                                    <div class="order-item-details">

                                        <h4>
                                            <?php
                                            echo htmlspecialchars(
                                                $item['food_name']
                                            );
                                            ?>
                                        </h4>

                                        <p>

                                            <span>
                                                ₹<?php echo $item['price']; ?>
                                            </span>

                                            ×

                                            <?php echo $item['quantity']; ?>

                                        </p>

                                    </div>


                                    <!-- SUBTOTAL -->

                                    <div class="order-item-price">

                                        ₹<?php
                                        echo number_format(
                                            $item['price'] * $item['quantity'],
                                            2
                                        );
                                        ?>

                                    </div>


                                </div>

                            <?php } ?>

                        </div>


                        <!-- =========================================
                             ORDER FOOTER
                        ========================================= -->

                        <div class="order-card-footer">


                            <!-- PAYMENT -->

                            <div class="order-payment">

                                <div class="payment-info">

                                    <span>
                                        Payment
                                    </span>

                                    <strong>

                                        <?php
                                        if ($order['payment_method'] === 'COD') {
                                            echo 'Cash on Delivery';
                                        } elseif ($order['payment_method'] === 'RozarPay') {
                                            echo 'Razorpay';
                                        } else {
                                            echo htmlspecialchars(
                                                $order['payment_method']
                                            );
                                        }
                                        ?>

                                    </strong>

                                </div>


                                <div class="payment-info">

                                    <span>
                                        Payment Status
                                    </span>

                                    <?php
                                    $paymentStatus = strtolower(
                                        $order['paymment_status'] ?? 'pending'
                                    );
                                    ?>

                                    <?php if ($paymentStatus === 'paid') { ?>

                                        <strong class="payment-paid">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Paid

                                        </strong>

                                    <?php } else { ?>

                                        <strong class="payment-pending">

                                            <i class="bi bi-clock"></i>

                                            Pending

                                        </strong>

                                    <?php } ?>

                                </div>

                            </div>


                            <!-- TOTAL -->

                            <div class="order-total">

                                <span>
                                    Total Amount
                                </span>

                                <strong>
                                    ₹<?php
                                    echo number_format(
                                        $order['total_amount'],
                                        2
                                    );
                                    ?>
                                </strong>

                            </div>

                        </div>


                    </div>


                <?php } ?>

            </div>


        <?php } ?>


    </div>

</main>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>