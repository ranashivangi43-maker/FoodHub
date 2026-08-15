<?php

session_start();

include('../config/db.php');


// =========================================
// AUTHENTICATION
// =========================================

if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

if($_SESSION['role'] != 'restaurant'){
    header("Location: ../index.php");
    exit;
}


// =========================================
// GET RESTAURANT
// =========================================

$owner_id = $_SESSION['userId'];

$stmt = $conn->prepare("
    SELECT *
    FROM restaurants
    WHERE owner_id = ?
");

$stmt->execute([$owner_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);


if(!$restaurant){
    header("Location: complete_profile.php");
    exit;
}


$restaurant_id = $restaurant['id'];

$_SESSION['restaurant_id'] = $restaurant_id;


// =========================================
// FOOD STATISTICS
// =========================================

$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM foods
    WHERE restaurant_id = ?
");

$stmt->execute([$restaurant_id]);

$total_foods = $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM foods
    WHERE restaurant_id = ?
    AND status = 'approved'
");

$stmt->execute([$restaurant_id]);

$approved_foods = $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM foods
    WHERE restaurant_id = ?
    AND status = 'pending'
");

$stmt->execute([$restaurant_id]);

$pending_foods = $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM foods
    WHERE restaurant_id = ?
    AND status = 'rejected'
");

$stmt->execute([$restaurant_id]);

$rejected_foods = $stmt->fetchColumn();

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
        Restaurant Dashboard | FoodHub
    </title>


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


    <!-- Restaurant CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/restaurant.css"
    >


    <!-- Dashboard CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/restaurant_dashboard.css"
    >

</head>


<body>


<!-- Navbar -->

<?php include '../includes/restaurant_navbar.php'; ?>


<!-- Main -->

<main class="restaurant-dashboard">

    <div class="container">


        <!-- Welcome -->

        <section class="restaurant-welcome">

            <div>

                <span class="dashboard-label">
                    RESTAURANT PANEL
                </span>

                <h1>
                    Welcome,
                    <?php echo htmlspecialchars($_SESSION['userName']); ?>
                </h1>

                <p>
                    Manage your restaurant, food items and approvals from here.
                </p>

            </div>


            <a
                href="add_food.php"
                class="btn btn-danger rounded-pill px-4"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Food

            </a>

        </section>


        <!-- Restaurant Name -->

        <div class="restaurant-name-card">

            <div class="restaurant-name-icon">

                <i class="bi bi-shop"></i>

            </div>

            <div>

                <small>
                    Your Restaurant
                </small>

                <h4>
                    <?php echo htmlspecialchars($restaurant['name']); ?>
                </h4>

            </div>

        </div>


        <!-- Statistics -->

        <div class="row g-4 mb-5">


            <!-- Total -->

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="restaurant-stat-card">

                    <div class="stat-icon total-icon">

                        <i class="bi bi-grid"></i>

                    </div>

                    <div>

                        <div class="stat-number">
                            <?php echo $total_foods; ?>
                        </div>

                        <div class="stat-label">
                            Total Foods
                        </div>

                    </div>

                </div>

            </div>


            <!-- Approved -->

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="restaurant-stat-card">

                    <div class="stat-icon approved-icon">

                        <i class="bi bi-check-circle"></i>

                    </div>

                    <div>

                        <div class="stat-number">
                            <?php echo $approved_foods; ?>
                        </div>

                        <div class="stat-label">
                            Approved
                        </div>

                    </div>

                </div>

            </div>


            <!-- Pending -->

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="restaurant-stat-card">

                    <div class="stat-icon pending-icon">

                        <i class="bi bi-clock"></i>

                    </div>

                    <div>

                        <div class="stat-number">
                            <?php echo $pending_foods; ?>
                        </div>

                        <div class="stat-label">
                            Pending
                        </div>

                    </div>

                </div>

            </div>


            <!-- Rejected -->

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="restaurant-stat-card">

                    <div class="stat-icon rejected-icon">

                        <i class="bi bi-x-circle"></i>

                    </div>

                    <div>

                        <div class="stat-number">
                            <?php echo $rejected_foods; ?>
                        </div>

                        <div class="stat-label">
                            Rejected
                        </div>

                    </div>

                </div>

            </div>


        </div>


        <!-- Management -->

        <div class="section-heading">

            <div>

                <span>
                    MANAGEMENT
                </span>

                <h2>
                    Manage Your Restaurant
                </h2>

            </div>

        </div>


        <div class="row g-4">


            <!-- Add Food -->

            <div class="col-12 col-md-6 col-xl-4">

                <div class="restaurant-management-card">

                    <div class="management-icon">

                        <i class="bi bi-plus-lg"></i>

                    </div>

                    <h5>
                        Add Food
                    </h5>

                    <p>
                        Add a new food item to your restaurant menu.
                    </p>

                    <a
                        href="add_food.php"
                        class="btn btn-danger rounded-pill"
                    >
                        Add Food
                    </a>

                </div>

            </div>


            <!-- My Foods -->

            <div class="col-12 col-md-6 col-xl-4">

                <div class="restaurant-management-card">

                    <div class="management-icon">

                        <i class="bi bi-grid"></i>

                    </div>

                    <h5>
                        My Foods
                    </h5>

                    <p>
                        View and manage all your food items and their status.
                    </p>

                    <a
                        href="manage_food.php"
                        class="btn btn-danger rounded-pill"
                    >
                        View Foods
                    </a>

                </div>

            </div>


            <!-- Pending -->

            <div class="col-12 col-md-6 col-xl-4">

                <div class="restaurant-management-card">

                    <div class="management-icon">

                        <i class="bi bi-clock-history"></i>

                    </div>

                    <h5>
                        Pending Approvals
                    </h5>

                    <p>
                        Check food items that are waiting for admin approval.
                    </p>

                    <a
                        href="pending_foods.php"
                        class="btn btn-danger rounded-pill"
                    >
                        View Pending
                    </a>

                </div>

            </div>


        </div>

    </div>

</main>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>
</html>