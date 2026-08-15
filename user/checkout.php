<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['userId'];

$cart_mode = isset($_GET['cart']);

$cart_items = [];
$food = null;
$food_images = [];
$quantity = 1;
$grand_total = 0;
$payable_amount = 0;


/* =========================================
   CART CHECKOUT
========================================= */

if ($cart_mode) {

    $stmt = $conn->prepare("
        SELECT cart.*, foods.*
        FROM cart
        INNER JOIN foods
            ON cart.food_id = foods.id
        WHERE cart.user_id = ?
    ");

    $stmt->execute([$user_id]);

    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($cart_items) < 1) {
        echo "Cart is empty.";
        exit;
    }

    foreach ($cart_items as $item) {
        $grand_total += $item['price'] * $item['quantity'];
    }

    $payable_amount = $grand_total;
}


/* =========================================
   SINGLE FOOD CHECKOUT
========================================= */

else {

    $food_id = $_GET['food_id'] ?? '';
    $quantity = isset($_GET['quantity']) ? (int) $_GET['quantity'] : 1;

    if (empty($food_id)) {
        echo "Food not found.";
        exit;
    }

    if ($quantity < 1) {
        $quantity = 1;
    }

    $stmt = $conn->prepare("
        SELECT *
        FROM foods
        WHERE id = ?
    ");

    $stmt->execute([$food_id]);

    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$food) {
        echo "Food not found.";
        exit;
    }

    $getimg = $conn->prepare("
        SELECT *
        FROM food_images
        WHERE food_id = ?
    ");

    $getimg->execute([$food_id]);

    $food_images = $getimg->fetchAll(PDO::FETCH_ASSOC);

    $payable_amount = $food['price'] * $quantity;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | FoodHub</title>

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


<body class="checkout-page">


<!-- =========================================
     NAVBAR
========================================= -->

<?php include '../includes/user_navbar.php'; ?>



<!-- =========================================
     CHECKOUT HEADER
========================================= -->

<section class="checkout-header">

    <div class="container">

        <div class="checkout-heading">

            <span>CHECKOUT</span>

            <h1>Complete Your Order</h1>

            <p>
                Review your order and provide your delivery details.
            </p>

        </div>


    </div>

</section>



<!-- =========================================
     MAIN CHECKOUT
========================================= -->

<main class="checkout-main">

    <div class="container">

        <div class="row g-4">


            <!-- =================================
                 LEFT SIDE - ORDER SUMMARY
            ================================= -->

            <div class="col-lg-7">

                <div class="checkout-card">

                    <div class="checkout-card-header">

                        <div>

                            <span class="checkout-label">
                                YOUR ORDER
                            </span>

                            <h3>
                                Order Summary
                            </h3>

                        </div>

                        <div class="order-count">

                            <?php if ($cart_mode) { ?>

                                <?php echo count($cart_items); ?> items

                            <?php } else { ?>

                                1 item

                            <?php } ?>

                        </div>

                    </div>



                    <div class="checkout-card-body">


                        <?php if ($cart_mode) { ?>


                            <!-- =========================
                                 CART ITEMS
                            ========================= -->

                            <?php foreach ($cart_items as $item) { ?>

                                <div class="checkout-item">

                                    <div class="checkout-item-image">

                                        <img
                                            src="../uploads/foods/<?php echo htmlspecialchars($item['image']); ?>"
                                            alt="<?php echo htmlspecialchars($item['food_name']); ?>"
                                        >

                                    </div>


                                    <div class="checkout-item-details">

                                        <h4>
                                            <?php echo htmlspecialchars($item['food_name']); ?>
                                        </h4>

                                        <p>
                                            ₹ <?php echo number_format($item['price'], 2); ?>
                                            each
                                        </p>

                                        <span class="item-quantity">

                                            Qty:
                                            <?php echo $item['quantity']; ?>

                                        </span>

                                    </div>


                                    <div class="checkout-item-price">

                                        ₹ <?php
                                        echo number_format(
                                            $item['price'] * $item['quantity'],
                                            2
                                        );
                                        ?>

                                    </div>

                                </div>

                            <?php } ?>


                            <!-- Cart Total -->

                            <div class="checkout-total-row">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    ₹ <?php echo number_format($grand_total, 2); ?>
                                </strong>

                            </div>


                        <?php } else { ?>


                            <!-- =========================
                                 SINGLE ITEM
                            ========================= -->

                            <div class="single-checkout-product">


                                <!-- IMAGE -->

                                <div class="single-product-gallery">

                                    <div class="main-product-image">

                                        <img
                                            id="mainImage"
                                            src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                            alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                        >

                                    </div>


                                    <div class="product-thumbnails">


                                        <!-- Main Image -->

                                        <img
                                            src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                            class="product-thumb active"
                                            alt=""
                                        >


                                        <?php foreach ($food_images as $index => $img) { ?>

                                            <?php if ($index < 2) { ?>

                                                <img
                                                    src="../uploads/foods/<?php echo htmlspecialchars($img['image']); ?>"
                                                    class="product-thumb"
                                                    alt=""
                                                >

                                            <?php } ?>

                                        <?php } ?>


                                        <?php if (count($food_images) > 2) { ?>

                                            <button
                                                type="button"
                                                class="gallery-more"
                                                data-bs-toggle="modal"
                                                data-bs-target="#galleryModal"
                                            >

                                                <img
                                                    src="../uploads/foods/<?php echo htmlspecialchars($food_images[2]['image']); ?>"
                                                    alt=""
                                                >

                                                <span>
                                                    +<?php echo count($food_images) - 2; ?>
                                                </span>

                                            </button>

                                        <?php } ?>

                                    </div>

                                </div>



                                <!-- DETAILS -->

                                <div class="single-product-details">

                                    <span class="food-type-label">
                                        FOOD ITEM
                                    </span>

                                    <h2>
                                        <?php echo htmlspecialchars($food['food_name']); ?>
                                    </h2>

                                    <p class="single-product-description">
                                        <?php echo htmlspecialchars($food['description']); ?>
                                    </p>

                                    <div class="single-product-price">

                                        ₹ <?php echo number_format($food['price'], 2); ?>

                                    </div>


                                    <!-- Quantity -->

                                    <div class="checkout-quantity">

                                        <label>
                                            Quantity
                                        </label>

                                        <div class="quantity-control">

                                            <button
                                                type="button"
                                                id="decreaseQty"
                                            >
                                                −
                                            </button>

                                            <input
                                                type="number"
                                                id="quantity"
                                                value="<?php echo $quantity; ?>"
                                                min="1"
                                            >

                                            <button
                                                type="button"
                                                id="increaseQty"
                                            >
                                                +
                                            </button>

                                        </div>

                                    </div>


                                    <!-- Product Total -->

                                    <div class="single-product-total">

                                        <span>
                                            Item Total
                                        </span>

                                        <strong>
                                            ₹
                                            <span id="total">
                                                <?php
                                                echo number_format(
                                                    $food['price'] * $quantity,
                                                    2
                                                );
                                                ?>
                                            </span>
                                        </strong>

                                    </div>

                                </div>

                            </div>


                        <?php } ?>

                    </div>

                </div>

            </div>



            <!-- =================================
                 RIGHT SIDE
            ================================= -->

            <div class="col-lg-5">

                <div class="checkout-side">


                    <!-- DELIVERY -->

                    <div class="checkout-card delivery-card">

                        <div class="checkout-card-header">

                            <div>

                                <span class="checkout-label">
                                    DELIVERY
                                </span>

                                <h3>
                                    Delivery Details
                                </h3>

                            </div>

                            <i class="bi bi-truck checkout-heading-icon"></i>

                        </div>


                        <div class="checkout-card-body">


                            <form
                                action="../process/place_order.php"
                                method="POST"
                                id="checkoutForm"
                            >


                                <?php if ($cart_mode) { ?>

                                    <input
                                        type="hidden"
                                        name="cart_order"
                                        value="1"
                                    >

                                <?php } else { ?>

                                    <input
                                        type="hidden"
                                        name="food_id"
                                        value="<?php echo $food['id']; ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="quantity"
                                        id="hidden_quantity"
                                        value="<?php echo $quantity; ?>"
                                    >

                                <?php } ?>


                                <!-- NAME -->

                                <div class="checkout-form-group">

                                    <label>
                                        Full Name
                                    </label>

                                    <div class="checkout-input">

                                        <i class="bi bi-person"></i>

                                        <input
                                            type="text"
                                            name="name"
                                            placeholder="Enter your full name"
                                            required
                                        >

                                    </div>

                                </div>



                                <!-- PHONE -->

                                <div class="checkout-form-group">

                                    <label>
                                        Phone Number
                                    </label>

                                    <div class="checkout-input">

                                        <i class="bi bi-telephone"></i>

                                        <input
                                            type="tel"
                                            name="phone"
                                            placeholder="Enter your phone number"
                                            required
                                        >

                                    </div>

                                </div>



                                <!-- ADDRESS -->

                                <div class="checkout-form-group">

                                    <label>
                                        Delivery Address
                                    </label>

                                    <div class="checkout-input checkout-textarea">

                                        <i class="bi bi-geo-alt"></i>

                                        <textarea
                                            name="address"
                                            rows="3"
                                            placeholder="Enter your complete delivery address"
                                            required
                                        ></textarea>

                                    </div>

                                </div>



                                <!-- PAYMENT -->

                                <div class="checkout-form-group">

                                    <label>
                                        Payment Method
                                    </label>

                                    <div class="payment-options">


                                        <!-- COD -->

                                        <label class="payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="COD"
                                            >

                                            <div class="payment-option-content">

                                                <div class="payment-icon">
                                                    <i class="bi bi-cash-stack"></i>
                                                </div>

                                                <div>

                                                    <strong>
                                                        Cash on Delivery
                                                    </strong>

                                                    <small>
                                                        Pay when your order arrives
                                                    </small>

                                                </div>

                                            </div>

                                            <i class="bi bi-check-circle-fill payment-check"></i>

                                        </label>



                                        <!-- RAZORPAY -->

                                        <label class="payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="Razorpay"
                                            >

                                            <div class="payment-option-content">

                                                <div class="payment-icon">
                                                    <i class="bi bi-credit-card"></i>
                                                </div>

                                                <div>

                                                    <strong>
                                                        Razorpay
                                                    </strong>

                                                    <small>
                                                        Pay securely online
                                                    </small>

                                                </div>

                                            </div>

                                            <i class="bi bi-check-circle-fill payment-check"></i>

                                        </label>

                                    </div>

                                </div>



                                <!-- ORDER TOTAL -->

                                <div class="checkout-final-total">

                                    <span>
                                        Amount to Pay
                                    </span>

                                    <strong>
                                        ₹
                                        <span id="checkoutPayable">
                                            <?php
                                            echo number_format(
                                                $payable_amount,
                                                2
                                            );
                                            ?>
                                        </span>
                                    </strong>

                                </div>



                                <!-- PAYMENT BUTTON -->

                                <button
                                    type="button"
                                    id="payBtn"
                                    class="checkout-place-btn"
                                >

                                    <span id="payBtnText">
                                        Place Order
                                    </span>

                                    

                                </button>


                                <div class="secure-checkout">

                                    <i class="bi bi-shield-check"></i>

                                    Secure checkout

                                </div>


                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>



<!-- =========================================
     GALLERY MODAL
========================================= -->

<?php if (!$cart_mode && count($food_images) > 2) { ?>

<div
    class="modal fade"
    id="galleryModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content gallery-modal">

            <div class="modal-body">

                <button
                    type="button"
                    class="gallery-close"
                    data-bs-dismiss="modal"
                >
                    <i class="bi bi-x"></i>
                </button>

                <img
                    id="modalMainImage"
                    src="../uploads/foods/<?php echo htmlspecialchars($food_images[2]['image']); ?>"
                    class="img-fluid"
                    alt=""
                >

            </div>

        </div>

    </div>

</div>

<?php } ?>



<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- jQuery -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<!-- Razorpay -->

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>



<script>

/* =========================================
   SINGLE ITEM QUANTITY
========================================= */

<?php if (!$cart_mode) { ?>

const quantityInput = document.getElementById("quantity");
const hiddenQuantity = document.getElementById("hidden_quantity");

const totalText = document.getElementById("total");
const checkoutPayable = document.getElementById("checkoutPayable");

const decreaseQty = document.getElementById("decreaseQty");
const increaseQty = document.getElementById("increaseQty");

const price = <?php echo $food['price']; ?>;


function updateQuantity() {

    let qty = parseInt(quantityInput.value);

    if (isNaN(qty) || qty < 1) {
        qty = 1;
    }

    quantityInput.value = qty;

    hiddenQuantity.value = qty;

    const total = price * qty;

    totalText.innerText = total.toFixed(2);

    checkoutPayable.innerText = total.toFixed(2);
}


decreaseQty.addEventListener("click", function () {

    let qty = parseInt(quantityInput.value);

    if (qty > 1) {
        quantityInput.value = qty - 1;
        updateQuantity();
    }

});


increaseQty.addEventListener("click", function () {

    let qty = parseInt(quantityInput.value);

    quantityInput.value = qty + 1;

    updateQuantity();

});


quantityInput.addEventListener("input", updateQuantity);



/* =========================================
   PRODUCT THUMBNAILS
========================================= */

document.querySelectorAll(".product-thumb").forEach(function (thumb) {

    thumb.addEventListener("click", function () {

        document.getElementById("mainImage").src = this.src;

        document.querySelectorAll(".product-thumb")
            .forEach(function (item) {
                item.classList.remove("active");
            });

        this.classList.add("active");

    });

});


<?php } ?>



/* =========================================
   PAYMENT METHOD
========================================= */

$("input[name='payment_method']").on("change", function () {

    let paymentMethod = $(this).val();

    if (paymentMethod === "COD") {

        $("#payBtnText").text("Place Order");

    }

    if (paymentMethod === "Razorpay") {

        $("#payBtnText").text(
            "Pay ₹" + $("#checkoutPayable").text()
        );

    }

});



/* =========================================
   CHECKOUT
========================================= */

$("#payBtn").click(function () {

    let paymentMethod =
        $("input[name='payment_method']:checked").val();


    if (!paymentMethod) {

        alert("Please select a payment method.");

        return;

    }


    /* Validate form */

    const form =
        document.getElementById("checkoutForm");

    if (!form.checkValidity()) {

        form.reportValidity();

        return;

    }


    /* =====================================
       CASH ON DELIVERY
    ===================================== */

    if (paymentMethod === "COD") {

        $("#checkoutForm").submit();

        return;

    }



    /* =====================================
       RAZORPAY
    ===================================== */

    if (paymentMethod === "Razorpay") {

        let payableAmount =
            parseFloat(
                $("#checkoutPayable").text()
            );


        let options = {

            key: "rzp_test_SqkeuTlieNzCKB",

            amount: Math.round(payableAmount * 100),

            currency: "INR",

            name: "FoodHub",

            description: "Food Order Payment",

            theme: {
                color: "#dc3545"
            },


            handler: function (response) {

                $.ajax({

                    url: "../process/place_order.php",

                    type: "POST",

                    data:
                        $("#checkoutForm").serialize() +

                        "&razorpay_payment_id=" +
                        response.razorpay_payment_id +

                        "&razorpay_order_id=" +
                        response.razorpay_order_id +

                        "&razorpay_signature=" +
                        response.razorpay_signature,


                    success: function (res) {

                        console.log(res);


                        if (res.trim() === "success") {

                            alert("Payment successful.");

                            window.location.href = "order_success.php?order_id=" + res.order_id;

                        }

                        else {

                            alert(res);

                        }

                    },

                    error: function () {

                        alert(
                            "Something went wrong while placing your order."
                        );

                    }

                });

            }

        };


        let rzp = new Razorpay(options);

        rzp.open();

    }

});

</script>


</body>

</html>