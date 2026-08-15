<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['userId'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: manage_restaurant.php");
    exit;
}

$id = $_GET['id'];

// Get restaurant information
$restaurantStmt = $conn->prepare("
    SELECT name
    FROM restaurants
    WHERE id = ?
");

$restaurantStmt->execute([$id]);

$restaurant = $restaurantStmt->fetch(PDO::FETCH_ASSOC);

if(!$restaurant){
    header("Location: manage_restaurant.php");
    exit;
}

// Get foods
$stmt = $conn->prepare("
    SELECT *
    FROM foods
    WHERE restaurant_id = ?
    ORDER BY id DESC
");

$stmt->execute([$id]);

$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Restaurant Foods | FoodHub Admin</title>

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

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

    <!-- Admin CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/admin.css"
    >

    <!-- Food Page CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/admin_foods.css"
    >

</head>

<body>

<!-- Navbar -->

<?php include '../includes/admin_navbar.php'; ?>


<!-- Main -->

<main class="admin-food-page">

    <div class="container">

        <!-- Page Header -->

        <div class="food-page-header">

            <div>

                <span class="text-danger fw-semibold small">
                    RESTAURANT MENU
                </span>

                <h1>
                    <?php echo htmlspecialchars($restaurant['name']); ?>
                </h1>

                <p>
                    View and manage all food items offered by this restaurant.
                </p>

            </div>

            <a
                href="manage_restaurant.php"
                class="btn btn-dark rounded-pill px-4"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Restaurants
            </a>

        </div>


        <!-- Summary -->

        <div class="food-summary">

            <div class="food-summary-icon">
                <i class="bi bi-grid"></i>
            </div>

            <div>

                <small>
                    Total Food Items
                </small>

                <h4>
                    <?php echo count($foods); ?>
                </h4>

            </div>

        </div>


        <!-- Food Cards -->

        <div class="row g-4">

            <?php if(count($foods) > 0){ ?>

                <?php foreach($foods as $food){ ?>

                    <div class="col-12 col-md-6 col-xl-4">

                        <div class="food-card h-100">

                            <!-- Image -->

                            <div class="food-image-wrapper">

                                <img
                                    src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                    class="food-image"
                                    alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                >

                                <!-- Status -->

                                <div class="food-status">

                                    <?php if($food['status'] == 'approved'){ ?>

                                        <span class="food-status-badge approved">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Approved
                                        </span>

                                    <?php } elseif($food['status'] == 'pending'){ ?>

                                        <span class="food-status-badge pending">
                                            <i class="bi bi-clock-fill"></i>
                                            Pending
                                        </span>

                                    <?php } else { ?>

                                        <span class="food-status-badge rejected">
                                            <i class="bi bi-x-circle-fill"></i>
                                            Rejected
                                        </span>

                                    <?php } ?>

                                </div>

                            </div>


                            <!-- Card Body -->

                            <div class="food-body">

                                <h4>
                                    <?php echo htmlspecialchars($food['food_name']); ?>
                                </h4>


                                <p class="food-description">

                                    <?php echo htmlspecialchars($food['description']); ?>

                                </p>


                                <div class="food-price">

                                    ₹<?php echo htmlspecialchars($food['price']); ?>

                                </div>


                                <!-- Actions -->

                                <div class="food-actions">

                                    <?php if($food['status'] == 'approved'){ ?>

                                        <a
                                            href="../process/food_action.php?id=<?php echo $food['id']; ?>&action=reject"
                                            class="btn btn-outline-danger rounded-pill w-100"
                                        >
                                            <i class="bi bi-x-circle me-1"></i>
                                            Reject Food
                                        </a>

                                    <?php } elseif($food['status'] == 'pending'){ ?>

                                        <a
                                            href="../process/food_action.php?id=<?php echo $food['id']; ?>&action=approve"
                                            class="btn btn-success rounded-pill"
                                        >
                                            <i class="bi bi-check-lg me-1"></i>
                                            Approve
                                        </a>

                                        <a
                                            href="../process/food_action.php?id=<?php echo $food['id']; ?>&action=reject"
                                            class="btn btn-outline-danger rounded-pill"
                                        >
                                            <i class="bi bi-x-lg me-1"></i>
                                            Reject
                                        </a>

                                    <?php } else { ?>

                                        <a
                                            href="../process/food_action.php?id=<?php echo $food['id']; ?>&action=approve"
                                            class="btn btn-success rounded-pill w-100"
                                        >
                                            <i class="bi bi-check-lg me-1"></i>
                                            Approve Food
                                        </a>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <!-- Empty State -->

                <div class="col-12">

                    <div class="food-empty-state">

                        <div class="food-empty-icon">

                            <i class="bi bi-egg-fried"></i>

                        </div>

                        <h4>
                            No Foods Added Yet
                        </h4>

                        <p>
                            This restaurant has not added any food items yet.
                        </p>

                        <a
                            href="manage_restaurant.php"
                            class="btn btn-danger rounded-pill px-4"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Restaurants
                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>