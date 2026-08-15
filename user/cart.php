<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_SESSION['userId'];

$stmt = $conn->prepare("
    SELECT 
        cart.*,
        foods.*,
        restaurants.name AS restaurant_name
    FROM cart
    INNER JOIN foods ON cart.food_id = foods.id
    INNER JOIN restaurants ON foods.restaurant_id = restaurants.id
    WHERE cart.user_id = ?
");
$stmt->execute([$id]);

$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
$totalQuantity = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
    $totalQuantity += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Cart | FoodHub</title>

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

    <!-- Poppins -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- User CSS -->
    <link rel="stylesheet" href="../assets/css/user.css">
</head>

<body>

<!-- =========================
     NAVBAR
========================= -->

<?php include '../includes/user_navbar.php'; ?>

<!-- =========================
     CART PAGE
========================= -->

<main class="cart-page">

    <div class="container">

        <!-- Page Heading -->
        <div class="cart-page-heading">

            <span>
                <i class="bi bi-bag"></i>
                YOUR SHOPPING CART
            </span>

            <h1>My Cart</h1>

            <p>
                Review your selected food items before placing your order.
            </p>

        </div>


        <?php if (empty($cart)) { ?>

            <!-- =========================
                 EMPTY CART
            ========================= -->

            <div class="cart-empty">

                <div class="cart-empty-icon">
                    <i class="bi bi-cart-x"></i>
                </div>

                <h3>Your cart is empty</h3>

                <p>
                    You haven't added any food items to your cart yet.
                </p>

                <a
                    href="../user/dashboard.php"
                    class="cart-browse-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Browse Food
                </a>

            </div>

        <?php } else { ?>

            <div class="row g-4">

                <!-- =========================
                     CART ITEMS
                ========================= -->

                <div class="col-lg-8">

                    <div class="cart-items-header">

                        <div>
                            <h3>Cart Items</h3>

                            <span>
                                <?php echo $totalQuantity; ?>
                                item<?php echo $totalQuantity != 1 ? 's' : ''; ?>
                            </span>
                        </div>

                        <a
                            href="../user/dashboard.php"
                            class="continue-shopping"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Continue Shopping
                        </a>

                    </div>


                    <div class="cart-items">

                        <?php foreach ($cart as $food) { ?>

                            <div class="cart-item">

                                <!-- Image -->
                                <div class="cart-item-image">

                                    <img
                                        src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                        alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                    >

                                </div>


                                <!-- Details -->
                                <div class="cart-item-details">

                                    <div class="cart-item-main">

                                        <h3>
                                            <?php echo htmlspecialchars($food['food_name']); ?>
                                        </h3>

                                        <p class="cart-restaurant">
                                            <i class="bi bi-shop"></i>
                                            <?php echo htmlspecialchars($food['restaurant_name']); ?>
                                        </p>

                                        <p class="cart-description">
                                            <?php echo htmlspecialchars($food['description']); ?>
                                        </p>

                                    </div>


                                    <div class="cart-item-bottom">

                                        <!-- Price -->
                                        <div class="cart-item-price">

                                            <span>Price</span>

                                            <strong>
                                                ₹<?php echo number_format($food['price'], 2); ?>
                                            </strong>

                                        </div>


                                        <!-- Quantity -->
                                        <div class="cart-quantity">

                                            <span>Quantity</span>

                                            <div class="quantity-control">

                                                <a
                                                    href="../process/decrease_quantity.php?id=<?php echo $food['food_id']; ?>"
                                                    class="quantity-btn"
                                                    title="Decrease quantity"
                                                >
                                                    <i class="bi bi-dash"></i>
                                                </a>

                                                <span class="quantity-number">
                                                    <?php echo $food['quantity']; ?>
                                                </span>

                                                <a
                                                    href="../process/increase_quantity.php?id=<?php echo $food['food_id']; ?>"
                                                    class="quantity-btn"
                                                    title="Increase quantity"
                                                >
                                                    <i class="bi bi-plus"></i>
                                                </a>

                                            </div>

                                        </div>


                                        <!-- Subtotal -->
                                        <div class="cart-item-subtotal">

                                            <span>Subtotal</span>

                                            <strong>
                                                ₹<?php echo number_format(
                                                    $food['price'] * $food['quantity'],
                                                    2
                                                ); ?>
                                            </strong>

                                        </div>

                                    </div>


                                    <!-- Actions -->
                                    <div class="cart-item-actions">

                                        <a
                                            href="../process/remove_from_cart_process.php?id=<?php echo $food['food_id']; ?>"
                                            class="cart-remove-btn"
                                        >
                                            <i class="bi bi-trash3"></i>
                                            Remove
                                        </a>

                                        <a
                                            href="../user/checkout.php?food_id=<?php echo $food['food_id']; ?>&quantity=<?php echo $food['quantity']; ?>"
                                            class="cart-buy-btn"
                                        >
                                            Order This
                                        </a>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                </div>


                <!-- =========================
                     CART SUMMARY
                ========================= -->

                <div class="col-lg-4">

                    <div class="cart-summary">

                        <div class="cart-summary-header">

                            <span>
                                <i class="bi bi-receipt"></i>
                                ORDER SUMMARY
                            </span>

                            <h3>Cart Summary</h3>

                        </div>


                        <div class="cart-summary-row">

                            <span>Items</span>

                            <strong>
                                <?php echo $totalQuantity; ?>
                            </strong>

                        </div>


                        <div class="cart-summary-row">

                            <span>Subtotal</span>

                            <strong>
                                ₹<?php echo number_format($total, 2); ?>
                            </strong>

                        </div>


                        <div class="cart-summary-row">

                            <span>Delivery</span>

                            <strong class="free-delivery">
                                FREE
                            </strong>

                        </div>


                        <hr>


                        <div class="cart-summary-total">

                            <span>Total</span>

                            <strong>
                                ₹<?php echo number_format($total, 2); ?>
                            </strong>

                        </div>


                        <!-- Buy entire cart -->
                        <a
                            href="../user/checkout.php?cart=true"
                            class="cart-checkout-btn"
                        >
                            <i class="bi bi-bag-check"></i>
                            Checkout All Items
                        </a>


                        <div class="cart-secure-note">

                            <i class="bi bi-shield-check"></i>

                            <span>
                                Your order details will be securely processed.
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>