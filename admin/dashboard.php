<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['userId']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

$totalUsers = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();

$totalRestaurants = $conn->query("SELECT COUNT(*) FROM restaurants")->fetchColumn();

$pendingFoods = $conn->query("SELECT COUNT(*) FROM foods WHERE status='pending'")->fetchColumn();

$approvedFoods = $conn->query("SELECT COUNT(*) FROM foods WHERE status='approved'")->fetchColumn();

$latestRestaurants = $conn->query("
    SELECT name, address, status, created_at 
    FROM restaurants 
    ORDER BY id DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$latestFoods = $conn->query("
    SELECT foods.food_name, foods.status, foods.created_at, restaurants.name AS restaurant_name
    FROM foods
    JOIN restaurants ON foods.restaurant_id = restaurants.id
    ORDER BY foods.id DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | FoodHub</title>

    <!-- Poppins -->
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
        href="/PHP/Restaurant/assets/css/admin.css"
    >

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->
<?php include '../includes/admin_navbar.php'; ?>



<!-- =========================
     DASHBOARD
========================= -->

<main class="dashboard-wrapper">

    <div class="container">


        <!-- Welcome -->

        <div class="welcome-section">

            <h1>

                Welcome,
                <?php echo htmlspecialchars($_SESSION['userName']); ?> 

            </h1>

            <p>

                Manage your restaurants, food items and platform activity.

            </p>

        </div>



        <!-- =========================
             STAT CARDS
        ========================= -->

        <div class="row g-4 mb-5">


            <!-- Users -->

            <div class="col-md-6 col-xl-3">

                <div class="card dashboard-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="stat-number">
                                    <h2><?php echo $totalUsers; ?></h2>
                                </div>

                                <div class="stat-label">
                                    Total Users
                                </div>

                            </div>

                            <div class="stat-icon users-icon">

                                <i class="bi bi-people-fill"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Restaurants -->

            <div class="col-md-6 col-xl-3">

                <div class="card dashboard-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="stat-number">
                                    <h2><?php echo $totalRestaurants; ?></h2>
                                </div>

                                <div class="stat-label">
                                    Restaurants
                                </div>

                            </div>

                            <div class="stat-icon restaurant-icon">

                                <i class="bi bi-shop"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Pending -->

            <div class="col-md-6 col-xl-3">

                <div class="card dashboard-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="stat-number">
                                    <h2><?php echo $pendingFoods; ?></h2>
                                </div>

                                <div class="stat-label">
                                    Pending Foods
                                </div>

                            </div>

                            <div class="stat-icon pending-icon">

                                <i class="bi bi-hourglass-split"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Approved -->

            <div class="col-md-6 col-xl-3">

                <div class="card dashboard-card shadow-sm">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="stat-number">
                                    <h2><?php echo $approvedFoods; ?></h2>
                                </div>

                                <div class="stat-label">
                                    Approved Foods
                                </div>

                            </div>

                            <div class="stat-icon approved-icon">

                                <i class="bi bi-check-circle-fill"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>



        <!-- =========================
             MANAGEMENT
        ========================= -->

        <div class="mb-4">

            <h4 class="fw-bold">
                Quick Management
            </h4>

            <p class="text-muted">
                Manage your FoodHub platform.
            </p>

        </div>


        <div class="row g-4">


            <!-- Add Restaurant -->

            <div class="col-md-6 col-lg-4">

                <div class="card management-card shadow-sm h-100">

                    <div class="card-body p-4">

                        <div class="management-icon mb-4">

                            <i class="bi bi-shop-window"></i>

                        </div>

                        <h5>
                            Add Restaurant
                        </h5>

                        <p>
                            Create a new restaurant account and add it to FoodHub.
                        </p>

                        <a
                            href="add_restaurant.php"
                            class="btn admin-btn"
                        >

                            Add Restaurant

                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- Manage Restaurants -->

            <div class="col-md-6 col-lg-4">

                <div class="card management-card shadow-sm h-100">

                    <div class="card-body p-4">

                        <div class="management-icon mb-4">

                            <i class="bi bi-buildings"></i>

                        </div>

                        <h5>
                            Manage Restaurants
                        </h5>

                        <p>
                            View and manage all restaurants registered on the platform.
                        </p>

                        <a
                            href="manage_restaurant.php"
                            class="btn admin-btn"
                        >

                            View Restaurants

                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>



            <!-- Food Approval -->

            <div class="col-md-6 col-lg-4">

                <div class="card management-card shadow-sm h-100">

                    <div class="card-body p-4">

                        <div class="management-icon mb-4">

                            <i class="bi bi-check2-square"></i>

                        </div>

                        <h5>
                            Food Approvals
                        </h5>

                        <p>
                            Review food items submitted by restaurants and approve them.
                        </p>

                        <a
                            href="approve_food.php"
                            class="btn admin-btn"
                        >

                            Review Foods

                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

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