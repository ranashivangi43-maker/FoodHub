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
        wishlist.*,
        foods.id AS food_id,
        foods.food_name,
        foods.price,
        foods.image,
        foods.description,
        restaurants.name AS restaurant_name
    FROM wishlist
    INNER JOIN foods 
        ON wishlist.food_id = foods.id
    INNER JOIN restaurants
        ON foods.restaurant_id = restaurants.id
    WHERE wishlist.user_id = ?
    ORDER BY wishlist.id DESC
");

$stmt->execute([$user_id]);
$wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Wishlist | FoodHub</title>

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

<body>

<!-- ================= NAVBAR ================= -->

<?php include '../includes/user_navbar.php'; ?>

<!-- ================= WISHLIST PAGE ================= -->

<main class="wishlist-page">

    <div class="container">

        <!-- Heading -->

        <div class="wishlist-heading">

            <span>
                YOUR FAVORITES
            </span>

            <h1>
                My Wishlist
            </h1>

            <p>
                Your favorite meals, saved in one place.
            </p>

        </div>


        <?php if (count($wishlist) > 0) { ?>

            <!-- Wishlist Grid -->

            <div class="row g-4">

                <?php foreach ($wishlist as $food) { ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="wishlist-card">

                            <!-- Image -->

                            <div class="wishlist-image-wrapper">

                                <img
                                    src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                    alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                    class="wishlist-image"
                                >

                                <!-- Remove -->

                                <button
                                    type="button"
                                    class="wishlist-remove"
                                    data-bs-toggle="modal"
                                    data-bs-target="#removeWishlist<?php echo $food['food_id']; ?>"
                                    title="Remove from wishlist"
                                >

                                    <i class="bi bi-heart-fill"></i>

                                </button>

                            </div>


                            <!-- Card Body -->

                            <div class="wishlist-card-body">

                                <div class="wishlist-card-top">

                                    <h3>
                                        <?php echo htmlspecialchars($food['food_name']); ?>
                                    </h3>

                                    <div class="wishlist-restaurant">

                                        <i class="bi bi-geo-alt-fill"></i>

                                        <?php echo htmlspecialchars($food['restaurant_name']); ?>

                                    </div>

                                </div>


                                <!-- Description -->

                                <?php if (!empty($food['description'])) { ?>

                                    <p class="wishlist-description">

                                        <?php echo htmlspecialchars($food['description']); ?>

                                    </p>

                                <?php } ?>


                                <!-- Bottom -->

                                <div class="wishlist-card-bottom">

                                    <div class="wishlist-price">

                                        ₹<?php echo htmlspecialchars($food['price']); ?>

                                    </div>


                                    <div class="wishlist-actions">

                                        <a
                                            href="../process/cart_process.php?id=<?php echo $food['food_id']; ?>"
                                            class="wishlist-cart-btn"
                                            title="Add to cart"
                                        >

                                            <i class="bi bi-cart-plus"></i>

                                        </a>

                                        <a
                                            href="../user/checkout.php?food_id=<?php echo $food['food_id']; ?>&quantity=1"
                                            class="wishlist-buy-btn"
                                        >

                                            Buy Now

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Remove Wishlist Modal -->

                    <div
                        class="modal fade"
                        id="removeWishlist<?php echo $food['food_id']; ?>"
                        tabindex="-1"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content wishlist-modal">

                                <div class="modal-body text-center">

                                    <div class="wishlist-modal-icon">

                                        <i class="bi bi-heartbreak"></i>

                                    </div>

                                    <h4>
                                        Remove from Wishlist?
                                    </h4>

                                    <p>
                                        Remove
                                        <strong>
                                            <?php echo htmlspecialchars($food['food_name']); ?>
                                        </strong>
                                        from your saved favorites?
                                    </p>

                                    <div class="wishlist-modal-actions">

                                        <button
                                            type="button"
                                            class="wishlist-cancel-btn"
                                            data-bs-dismiss="modal"
                                        >
                                            Keep It
                                        </button>

                                        <a
                                            href="../process/wishlist_process.php?id=<?php echo $food['food_id']; ?>"
                                            class="wishlist-confirm-btn"
                                        >
                                            Remove
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <!-- Empty Wishlist -->

            <div class="wishlist-empty">

                <div class="wishlist-empty-icon">

                    <i class="bi bi-heart"></i>

                </div>

                <h3>
                    Your wishlist is waiting
                </h3>

                <p>
                    Save your favorite dishes here and come back to them anytime.
                </p>

                <a
                    href="dashboard.php"
                    class="wishlist-explore-btn"
                >

                    <i class="bi bi-search"></i>

                    Explore Foods

                </a>

            </div>

        <?php } ?>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>