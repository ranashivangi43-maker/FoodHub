<?php

session_start();
include '../config/db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: ../../index.php");
    exit;
}

$user_id = $_SESSION['userId'];

$order_id = $_GET['id'] ?? '';

if (empty($order_id)) {
    header("Location: ../orders.php");
    exit;
}


/* =========================================
   GET RESTAURANT
========================================= */

$stmt = $conn->prepare("
    SELECT id, name
    FROM restaurants
    WHERE owner_id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    echo "Restaurant not found.";
    exit;
}

$restaurant_id = $restaurant['id'];


/* =========================================
   GET ORDER DETAILS
========================================= */

$stmt = $conn->prepare("
    SELECT
        orders.id AS order_id,
        orders.total_amount,
        orders.status,
        orders.created_at,
        orders.address,
        orders.phone,
        orders.payment_method,
        orders.paymment_status,

        users.name AS customer_name,
        users.email AS customer_email

    FROM orders

    INNER JOIN users
        ON orders.user_id = users.id

    WHERE orders.id = ?

    AND EXISTS (
        SELECT 1
        FROM order_item

        INNER JOIN foods
            ON order_item.food_id = foods.id

        WHERE order_item.order_id = orders.id
        AND foods.restaurant_id = ?
    )

    LIMIT 1
");

$stmt->execute([
    $order_id,
    $restaurant_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit;
}


/* =========================================
   GET ORDER ITEMS
========================================= */

$stmt = $conn->prepare("
    SELECT
        order_item.quantity,
        order_item.price,

        foods.food_name,
        foods.image

    FROM order_item

    INNER JOIN foods
        ON order_item.food_id = foods.id

    WHERE order_item.order_id = ?

    AND foods.restaurant_id = ?

    ORDER BY order_item.id ASC
");

$stmt->execute([
    $order_id,
    $restaurant_id
]);

$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Order #<?php echo $order['order_id']; ?> - FoodHub
    </title>

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

    <!-- Restaurant CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/restaurant.css"
    >

</head>


<body>

<div class="restaurant-order-details-page">

    <div class="container">

        <!-- HEADER -->

        <div class="order-details-header">

            <div class="order-details-heading">

                <span>
                    ORDER DETAILS
                </span>

                <h1>
                    Order #<?php echo $order['order_id']; ?>
                </h1>

                <p>
                    Review the customer's order and delivery information.
                </p>

            </div>


            <a
                href="../restaurant/orders.php"
                class="order-details-back"
            >

                <i class="bi bi-arrow-left"></i>

                Back to Orders

            </a>

        </div>


        <!-- MAIN CARD -->

        <div class="order-details-card">


            <!-- ORDER HEADER -->

            <div class="order-details-top">

                <div>

                    <div class="order-details-number">

                        Order #<?php echo $order['order_id']; ?>

                    </div>

                    <span class="order-details-date">

                        <i class="bi bi-calendar3"></i>

                        <?php
                        echo date(
                            "d M Y, h:i A",
                            strtotime($order['created_at'])
                        );
                        ?>

                    </span>

                </div>


                <!-- STATUS -->

                <div class="order-status-box">

                    <div>

                        <small class="text-muted d-block mb-2">
                            Order Status
                        </small>


                        <?php if ($order['status'] === 'pending') { ?>

                            <span class="status-badge status-pending">
                                Pending
                            </span>

                        <?php } elseif ($order['status'] === 'confirmed') { ?>

                            <span class="status-badge status-confirmed">
                                Confirmed
                            </span>

                        <?php } elseif ($order['status'] === 'delivered') { ?>

                            <span class="status-badge status-delivered">
                                Delivered
                            </span>

                        <?php } elseif ($order['status'] === 'cancelled') { ?>

                            <span class="status-badge status-cancelled">
                                Cancelled
                            </span>

                        <?php } else { ?>

                            <span class="status-badge">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- STATUS ACTIONS -->

                    <div class="status-actions">

                        <?php if ($order['status'] === 'pending') { ?>

                            <!-- CONFIRM -->

                            <form
                                action="order_status.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="order_id"
                                    value="<?php echo $order['order_id']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="status"
                                    value="confirmed"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >

                                    <i class="bi bi-check-circle"></i>

                                    Confirm Order

                                </button>

                            </form>


                            <!-- CANCEL -->

                            <form
                                action="order_status.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="order_id"
                                    value="<?php echo $order['order_id']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="status"
                                    value="cancelled"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger"
                                >

                                    <i class="bi bi-x-circle"></i>

                                    Cancel Order

                                </button>

                            </form>


                        <?php } elseif ($order['status'] === 'confirmed') { ?>

                            <!-- DELIVERED -->

                            <form
                                action="order_status.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="order_id"
                                    value="<?php echo $order['order_id']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="status"
                                    value="delivered"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-box-seam"></i>

                                    Mark as Delivered

                                </button>

                            </form>


                        <?php } elseif ($order['status'] === 'delivered') { ?>

                            <span class="order-completed">

                                <i class="bi bi-check-circle-fill"></i>

                                Order Completed

                            </span>


                        <?php } elseif ($order['status'] === 'cancelled') { ?>

                            <span class="order-cancelled">

                                <i class="bi bi-x-circle-fill"></i>

                                Order Cancelled

                            </span>

                        <?php } ?>

                    </div>

                </div>

            </div>


            <!-- CUSTOMER INFORMATION -->

            <div class="mt-4">

                <div class="order-details-section-title">

                    <i class="bi bi-person"></i>

                    Customer Information

                </div>


                <div class="order-customer-grid">


                    <div class="order-customer-box">

                        <span>
                            CUSTOMER NAME
                        </span>

                        <strong>

                            <i class="bi bi-person"></i>

                            <?php
                            echo htmlspecialchars(
                                $order['customer_name']
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="order-customer-box">

                        <span>
                            PHONE
                        </span>

                        <strong>

                            <i class="bi bi-telephone"></i>

                            <?php
                            echo htmlspecialchars(
                                $order['phone']
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="order-customer-box">

                        <span>
                            EMAIL
                        </span>

                        <strong>

                            <i class="bi bi-envelope"></i>

                            <?php
                            echo htmlspecialchars(
                                $order['customer_email']
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="order-customer-box">

                        <span>
                            PAYMENT METHOD
                        </span>

                        <strong>

                            <i class="bi bi-wallet2"></i>

                            <?php
                            echo htmlspecialchars(
                                $order['payment_method']
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="order-customer-box full-width">

                        <span>
                            DELIVERY ADDRESS
                        </span>

                        <strong>

                            <i class="bi bi-geo-alt"></i>

                            <?php
                            echo nl2br(
                                htmlspecialchars(
                                    $order['address']
                                )
                            );
                            ?>

                        </strong>

                    </div>

                </div>

            </div>


            <!-- ORDER ITEMS -->

            <div class="mt-5">

                <div class="order-details-section-title">

                    <i class="bi bi-bag"></i>

                    Ordered Items

                </div>


                <div class="order-food-list">


                    <?php foreach ($order_items as $item) { ?>

                        <div class="order-food-item">


                            <!-- IMAGE -->

                            <?php if (!empty($item['image'])) { ?>

                                <img
                                    src="../uploads/foods/<?php
                                    echo htmlspecialchars(
                                        $item['image']
                                    );
                                    ?>"
                                    class="order-food-image"
                                    alt="<?php
                                    echo htmlspecialchars(
                                        $item['food_name']
                                    );
                                    ?>"
                                >

                            <?php } else { ?>

                                <div class="order-food-image d-flex align-items-center justify-content-center bg-light">

                                    <i class="bi bi-image text-muted fs-4"></i>

                                </div>

                            <?php } ?>


                            <!-- DETAILS -->

                            <div class="order-food-info">

                                <h4>

                                    <?php
                                    echo htmlspecialchars(
                                        $item['food_name']
                                    );
                                    ?>

                                </h4>

                                <p>

                                    Quantity:
                                    <?php echo $item['quantity']; ?>

                                    &nbsp; × &nbsp;

                                    ₹<?php
                                    echo number_format(
                                        $item['price'],
                                        2
                                    );
                                    ?>

                                </p>

                            </div>


                            <!-- SUBTOTAL -->

                            <div class="order-food-price">

                                <strong>

                                    ₹<?php
                                    echo number_format(
                                        $item['price'] *
                                        $item['quantity'],
                                        2
                                    );
                                    ?>

                                </strong>

                                <span>
                                    Item total
                                </span>

                            </div>


                        </div>

                    <?php } ?>


                </div>


                <!-- TOTAL -->

                <div class="order-total-box">

                    <span>
                        Order Total
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


            <!-- PAYMENT INFORMATION -->

            <div class="mt-5">

                <div class="order-details-section-title">

                    <i class="bi bi-credit-card"></i>

                    Payment Information

                </div>


                <div class="order-payment-grid">


                    <div class="order-payment-box">

                        <span>
                            PAYMENT METHOD
                        </span>

                        <strong>

                            <?php
                            echo htmlspecialchars(
                                $order['payment_method']
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="order-payment-box">

                        <span>
                            PAYMENT STATUS
                        </span>


                        <?php if (
                            strtolower(
                                $order['paymment_status']
                            ) === 'paid'
                        ) { ?>

                            <strong class="order-payment-paid">

                                <i class="bi bi-check-circle"></i>

                                Paid

                            </strong>

                        <?php } else { ?>

                            <strong class="order-payment-pending">

                                <i class="bi bi-clock"></i>

                                Pending

                            </strong>

                        <?php } ?>

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>