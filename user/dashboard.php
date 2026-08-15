<?php
session_start();
include '../config/db.php';
if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

if($_SESSION['role'] != 'user'){
    header("Location: ../index.php");
    exit;
}
$cartSuccess = isset($_GET['cart_added']) && $_GET['cart_added'] == '1';
$cartFoodName = $_GET['food_name'] ?? '';
$user_id = $_SESSION['userId'];
$stmt=$conn->prepare("SELECT foods.*,restaurants.name AS restaurant_name, wishlist.id AS wishlist_id FROM foods
INNER JOIN restaurants ON foods.restaurant_id=restaurants.id
LEFT JOIN wishlist
ON foods.id=wishlist.food_id
AND wishlist.user_id=?
WHERE foods.status='approved' AND restaurants.status='active'");
$stmt->execute([$user_id]);
$foods=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet"> 

    <link
    rel="stylesheet"
    href="../assets/css/user.css"
>
</head>

<body class="bg-light">
<?php if($cartSuccess){ ?>

<div class="cart-toast" id="cartToast">

    <div class="cart-toast-icon">
        <i class="bi bi-check-lg"></i>
    </div>

    <div class="cart-toast-content">
        <strong>Added to cart</strong>

        <span>
            <?php echo htmlspecialchars($cartFoodName); ?>
            has been added to your cart.
        </span>

        <a href="cart.php">
            View Cart
        </a>
    </div>

    <button type="button" class="cart-toast-close" onclick="closeCartToast()">
        <i class="bi bi-x"></i>
    </button>

</div>

<?php } ?>
    <!-- Navbar -->
    <!-- Navbar -->
<?php include '../includes/user_navbar.php'; ?>
<!-- Hero -->
<section class="user-hero">
    <div class="container">

        <span class="hero-label">
            FOODHUB
        </span>

        <h1>
            Welcome, <?php echo htmlspecialchars($_SESSION['userName']); ?>
        </h1>

        <p>
            Discover delicious food from restaurants around you.
        </p>

    </div>
</section>


<!-- Main -->
<main class="user-dashboard">

    <div class="container">

        <!-- Search -->
        <section class="food-search">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="foodSearch"
                    placeholder="Search for food or restaurant..."
                >

            </div>

        </section>


        <!-- Section Heading -->
        <section class="food-section">

            <div class="food-section-heading">

                <div>
                    <span class="section-label">
                        EXPLORE
                    </span>

                    <h2>
                        Discover Food
                    </h2>

                    <p>
                        Choose from delicious dishes available from our restaurants.
                    </p>
                </div>

            </div>


            <!-- Food Cards -->
            <div class="row g-4">

                <?php if(count($foods) > 0){ ?>

                    <?php foreach($foods as $food){ ?>

                        <div
                            class="col-12 col-md-6 col-xl-4 food-item"
                            data-food="<?php echo strtolower(
                                $food['food_name'] . ' ' . $food['restaurant_name']
                            ); ?>"
                        >

                            <div class="food-card">

                                <!-- Image -->
                                <div class="food-image-wrapper">

                                    <img
                                        src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                        alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                        class="food-image"
                                    >

                                    <!-- Wishlist -->
                                    <a href="../process/wishlist_process.php?id=<?php echo $food['id']; ?>">

                    <?php if($food['wishlist_id']){ ?>

                        <i
                            class="bi bi-suit-heart-fill position-absolute top-0 end-0 m-3"
                            style="
                                font-size:24px;
                                color:red;
                                cursor:pointer;
                            "
                        ></i>

                    <?php } else { ?>

                        <i
                            class="bi bi-suit-heart position-absolute top-0 end-0 m-3"
                            style="
                                font-size:24px;
                                color:white;
                                cursor:pointer;
                                text-shadow:0 0 5px rgba(0,0,0,0.5);
                            "
                        ></i>

                    <?php } ?>

                </a>

                                </div>


                                <!-- Content -->
                                <div class="food-card-body">

                                    <div class="food-card-top">

                                        <div>

                                            <h3>
                                                <?php echo htmlspecialchars($food['food_name']); ?>
                                            </h3>

                                            <span class="restaurant-name">
                                                <i class="bi bi-shop"></i>
                                                <?php echo htmlspecialchars($food['restaurant_name']); ?>
                                            </span>

                                        </div>

                                    </div>


                                    <p class="food-description">
                                        <?php echo htmlspecialchars($food['description']); ?>
                                    </p>


                                    <div class="food-card-bottom">

                                        <strong class="food-price">
                                            ₹<?php echo htmlspecialchars($food['price']); ?>
                                        </strong>

                                        <div class="food-actions">

                                            <a
    href="../process/cart_process.php?id=<?php echo $food['id']; ?>"
    class="btn btn-warning"
    title="Add to cart"
>
    <i class="bi bi-cart-plus"></i>
</a>

                                            <a
                                                href="../user/checkout.php?food_id=<?php echo $food['id']; ?>&quantity=1"
                                                class="buy-btn"
                                            >
                                                Buy Now
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="col-12">

                        <div class="empty-food">

                            <i class="bi bi-emoji-frown"></i>

                            <h4>
                                No food available
                            </h4>

                            <p>
                                There are currently no approved food items available.
                            </p>

                        </div>

                    </div>

                <?php } ?>
                <div class="col-12" id="noSearchResult" style="display: none;">
    <div class="search-empty">

        <i class="bi bi-search"></i>

        <h4>No matching food found</h4>

        <p>
            Try searching for a different food or restaurant.
        </p>

    </div>
</div>
            </div>

        </section>

    </div>

</main>
    
    
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
        </script>
        <script>
const searchInput = document.getElementById('foodSearch');
const foodItems = document.querySelectorAll('.food-item');
const noSearchResult = document.getElementById('noSearchResult');
const searchTerm = document.getElementById('searchTerm');
const clearSearch = document.getElementById('clearSearch');

searchInput.addEventListener('input', function () {

    const searchValue = this.value.toLowerCase().trim();

    let visibleFoods = 0;

    foodItems.forEach(function (item) {

        const foodData = item.getAttribute('data-food');

        if (foodData.includes(searchValue)) {
            item.style.display = '';
            visibleFoods++;
        } else {
            item.style.display = 'none';
        }

    });

    if (searchValue !== '' && visibleFoods === 0) {

        noSearchResult.style.display = 'block';
        searchTerm.textContent = this.value;

    } else {

        noSearchResult.style.display = 'none';

    }

});

clearSearch.addEventListener('click', function () {

    searchInput.value = '';

    foodItems.forEach(function (item) {
        item.style.display = '';
    });

    noSearchResult.style.display = 'none';

    searchInput.focus();

});
</script>
<script>
function closeCartToast() {
    const toast = document.getElementById('cartToast');

    if (toast) {
        toast.remove();
    }
}

const cartToast = document.getElementById('cartToast');

if (cartToast) {
    setTimeout(function () {
        cartToast.remove();
    }, 4000);
}
</script>
</body>

</html>