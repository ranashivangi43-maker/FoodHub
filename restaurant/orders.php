<?php

session_start();

include '../config/db.php';

if (
    !isset($_SESSION['userId']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'restaurant'
) {
    header("Location: ../index.php");
    exit;
}

$owner_id = $_SESSION['userId'];

/*
|--------------------------------------------------------------------------
| Get Restaurant
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id, name
    FROM restaurants
    WHERE owner_id = ?
    LIMIT 1
");

$stmt->execute([$owner_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    exit("Restaurant not found.");
}

$restaurant_id = $restaurant['id'];


/*
|--------------------------------------------------------------------------
| Get Orders
|--------------------------------------------------------------------------
|
| Important:
| We only fetch orders where at least one order_item belongs
| to a food from this restaurant.
|
*/

$stmt = $conn->prepare("
    SELECT DISTINCT
        orders.id,
        orders.user_id,
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

    INNER JOIN order_item
        ON orders.id = order_item.order_id

    INNER JOIN foods
        ON order_item.food_id = foods.id

    WHERE foods.restaurant_id = ?

    ORDER BY orders.id DESC
");

$stmt->execute([$restaurant_id]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Get Restaurant Items For All Orders
|--------------------------------------------------------------------------
*/

$order_items = [];

if (count($orders) > 0) {

    $order_ids = array_column($orders, 'id');

    $placeholders = implode(',', array_fill(0, count($order_ids), '?'));

    $params = array_merge([$restaurant_id], $order_ids);

    $stmt = $conn->prepare("
        SELECT
            order_item.id,
            order_item.order_id,
            order_item.food_id,
            order_item.quantity,
            order_item.price,

            foods.food_name,
            foods.image

        FROM order_item

        INNER JOIN foods
            ON order_item.food_id = foods.id

        WHERE foods.restaurant_id = ?
        AND order_item.order_id IN ($placeholders)

        ORDER BY order_item.id ASC
    ");

    $stmt->execute($params);

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {

        $order_items[$item['order_id']][] = $item;

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Orders | <?php echo htmlspecialchars($restaurant['name']); ?></title>

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


<?php include '../includes/restaurant_navbar.php'; ?>


<main class="restaurant-orders-page">

    <div class="container">


        <!-- =========================================
             PAGE HEADER
        ========================================== -->

        <div class="restaurant-orders-header">

            <div>

                <span class="restaurant-page-label">
                    RESTAURANT ORDERS
                </span>

                <h1>
                    Orders
                </h1>

                <p>
                    Manage orders placed for your restaurant.
                </p>

            </div>

            <div class="restaurant-orders-count">

                <i class="bi bi-receipt"></i>

                <span>
                    <?php echo count($orders); ?>
                </span>

                <small>
                    Orders
                </small>

            </div>

        </div>


        <?php if (count($orders) == 0) { ?>


            <!-- =========================================
                 EMPTY STATE
            ========================================== -->

            <div class="restaurant-empty-orders">

                <div class="restaurant-empty-icon">

                    <i class="bi bi-receipt"></i>

                </div>

                <h3>
                    No orders yet
                </h3>

                <p>
                    Orders placed for your restaurant will appear here.
                </p>

            </div>


        <?php } else { ?>


            <!-- =========================================
                 ORDERS
            ========================================== -->

            <div class="restaurant-orders-list">


                <?php foreach ($orders as $order) { ?>


                    <?php

                    $restaurant_order_total = 0;

                    if (isset($order_items[$order['id']])) {

                        foreach ($order_items[$order['id']] as $item) {

                            $restaurant_order_total +=
                                $item['price'] * $item['quantity'];

                        }

                    }

                    ?>


                    <!-- =========================================
                         ORDER CARD
                    ========================================== -->

                    <div class="restaurant-order-card">


                        <!-- TOP -->

                        <div class="restaurant-order-top">


                            <div>

                                <span class="restaurant-order-number">

                                    Order #<?php echo $order['id']; ?>

                                </span>

                                <span class="restaurant-order-date">

                                    <i class="bi bi-calendar3"></i>

                                    <?php echo date(
                                        'd M Y, h:i A',
                                        strtotime($order['created_at'])
                                    ); ?>

                                </span>

                            </div>


                            <!-- STATUS -->

                            <?php

                            $status = $order['status'];

                            ?>

                            <span
                                class="restaurant-order-status <?php echo htmlspecialchars($status); ?>"
                            >

                                <i class="bi bi-circle-fill"></i>

                                <?php echo ucfirst($status); ?>

                            </span>


                        </div>


                        <!-- CUSTOMER -->

                        <div class="restaurant-order-customer">


                            <div class="restaurant-customer-info">

                                <div class="restaurant-customer-icon">

                                    <i class="bi bi-person"></i>

                                </div>

                                <div>

                                    <span>
                                        Customer
                                    </span>

                                    <strong>
                                        <?php echo htmlspecialchars(
                                            $order['customer_name']
                                        ); ?>
                                    </strong>

                                </div>

                            </div>


                            <div class="restaurant-customer-info">

                                <div class="restaurant-customer-icon">

                                    <i class="bi bi-telephone"></i>

                                </div>

                                <div>

                                    <span>
                                        Phone
                                    </span>

                                    <strong>
                                        <?php echo htmlspecialchars(
                                            $order['phone']
                                        ); ?>
                                    </strong>

                                </div>

                            </div>


                        </div>


                        <!-- ITEMS -->

                        <div class="restaurant-order-items">


                            <div class="restaurant-items-heading">

                                <span>
                                    Ordered Items
                                </span>

                            </div>


                            <?php if (
                                isset($order_items[$order['id']])
                            ) { ?>


                                <?php foreach (
                                    $order_items[$order['id']]
                                    as $item
                                ) { ?>


                                    <div class="restaurant-order-item">


                                        <!-- IMAGE -->

                                        <img
                                            src="../uploads/foods/<?php
                                                echo htmlspecialchars(
                                                    $item['image']
                                                );
                                            ?>"
                                            alt="<?php
                                                echo htmlspecialchars(
                                                    $item['food_name']
                                                );
                                            ?>"
                                        >


                                        <!-- FOOD -->

                                        <div class="restaurant-order-food">

                                            <strong>
                                                <?php echo htmlspecialchars(
                                                    $item['food_name']
                                                ); ?>
                                            </strong>

                                            <span>

                                                ₹<?php echo number_format(
                                                    $item['price'],
                                                    2
                                                ); ?>

                                                ×

                                                <?php echo $item['quantity']; ?>

                                            </span>

                                        </div>


                                        <!-- SUBTOTAL -->

                                        <strong class="restaurant-item-subtotal">

                                            ₹<?php echo number_format(
                                                $item['price'] *
                                                $item['quantity'],
                                                2
                                            ); ?>

                                        </strong>


                                    </div>


                                <?php } ?>


                            <?php } ?>


                        </div>


                        <!-- BOTTOM -->

                        <div class="restaurant-order-bottom">


                            <!-- PAYMENT -->

                            <div class="restaurant-payment-info">

                                <div>

                                    <span>
                                        Payment
                                    </span>

                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $order['payment_method']
                                        );

                                        ?>

                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Payment Status
                                    </span>

                                    <strong
                                        class="<?php
                                            echo $order['paymment_status']
                                                === 'paid'
                                                ? 'payment-paid'
                                                : 'payment-pending';
                                        ?>"
                                    >

                                        <?php echo ucfirst(
                                            $order['paymment_status']
                                        ); ?>

                                    </strong>

                                </div>

                            </div>


                            <!-- TOTAL -->

                            <div class="restaurant-order-total">

                                <span>
                                    Your Restaurant Total
                                </span>

                                <strong>
                                    ₹<?php echo number_format(
                                        $restaurant_order_total,
                                        2
                                    ); ?>
                                </strong>

                            </div>


                            <!-- ACTION -->

                            <a
                                href="order_details.php?id=<?php
                                    echo $order['id'];
                                ?>"
                                class="restaurant-view-order-btn"
                            >

                                View Order

                                <i class="bi bi-arrow-right"></i>

                            </a>


                        </div>


                    </div>


                <?php } ?>


            </div>


        <?php } ?>


    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>