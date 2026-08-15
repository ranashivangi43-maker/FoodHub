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

$stmt = $conn->prepare("
    SELECT restaurants.*, 
           users.name AS owner_name,
           users.email
    FROM restaurants
    INNER JOIN users 
        ON restaurants.owner_id = users.id
    ORDER BY restaurants.id DESC
");

$stmt->execute();

$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Restaurants | FoodHub</title>

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

</head>


<body>

<!-- Navbar -->
 <?php include '../includes/admin_navbar.php'; ?>


<!-- Main -->

<main class="container dashboard-wrapper">


    <!-- Page Header -->

    <div class="page-header mb-4">

        <div>

            <span class="text-danger fw-semibold small">
                ADMIN PANEL
            </span>

            <h1 class="fw-bold mb-1">
                Manage Restaurants
            </h1>

            <p class="text-muted mb-0">
                View and manage all restaurants registered on FoodHub.
            </p>

        </div>


        <a
            href="add_restaurant.php"
            class="btn btn-danger rounded-pill px-4 mt-3 mt-md-0"
        >

            <i class="bi bi-plus-lg me-1"></i>

            Add Restaurant

        </a>

    </div>



    <!-- Restaurant Count -->

    <div class="restaurant-summary mb-4">

        <div class="summary-icon">

            <i class="bi bi-shop"></i>

        </div>

        <div>

            <small class="text-muted">
                Total Restaurants
            </small>

            <h4 class="fw-bold mb-0">
                <?php echo count($restaurants); ?>
            </h4>

        </div>

    </div>



    <!-- Restaurants -->

    <div class="row g-4">

        <?php if(count($restaurants) > 0){ ?>

            <?php foreach($restaurants as $restaurant){ ?>


                <div class="col-12 col-md-6 col-xl-4">

                    <div class="restaurant-card h-100">


                        <!-- Image -->

                        <div class="restaurant-image-wrapper">

                            <img
                                src="../uploads/restaurants/<?php echo htmlspecialchars($restaurant['image']); ?>"
                                class="restaurant-image"
                                alt="Restaurant"
                            >


                            <!-- Status -->

                            <div class="status-position">

                                <?php if($restaurant['status'] == 'active'){ ?>

                                    <span class="status-badge active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Active
                                    </span>

                                <?php } else { ?>

                                    <span class="status-badge inactive">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Inactive
                                    </span>

                                <?php } ?>

                            </div>

                        </div>



                        <!-- Card Body -->

                        <div class="restaurant-body">


                            <h4 class="fw-bold mb-3">

                                <?php 
                                    echo htmlspecialchars($restaurant['name']); 
                                ?>

                            </h4>


                            <!-- Owner -->

                            <div class="restaurant-info">

                                <div class="info-icon">

                                    <i class="bi bi-person"></i>

                                </div>

                                <div>

                                    <small>
                                        Owner
                                    </small>

                                    <p>
                                        <?php 
                                            echo htmlspecialchars(
                                                $restaurant['owner_name']
                                            ); 
                                        ?>
                                    </p>

                                </div>

                            </div>



                            <!-- Email -->

                            <div class="restaurant-info">

                                <div class="info-icon">

                                    <i class="bi bi-envelope"></i>

                                </div>

                                <div>

                                    <small>
                                        Email
                                    </small>

                                    <p>
                                        <?php 
                                            echo htmlspecialchars(
                                                $restaurant['email']
                                            ); 
                                        ?>
                                    </p>

                                </div>

                            </div>



                            <!-- Phone -->

                            <div class="restaurant-info">

                                <div class="info-icon">

                                    <i class="bi bi-telephone"></i>

                                </div>

                                <div>

                                    <small>
                                        Phone
                                    </small>

                                    <p>
                                        <?php 
                                            echo htmlspecialchars(
                                                $restaurant['phone']
                                            ); 
                                        ?>
                                    </p>

                                </div>

                            </div>



                            <!-- Buttons -->

                            <div class="restaurant-actions">

                                <a
                                    href="view_restaurant_foods.php?id=<?php echo $restaurant['id']; ?>"
                                    class="btn btn-dark rounded-pill"
                                >

                                    <i class="bi bi-grid me-1"></i>

                                    View Foods

                                </a>


                                <a
                                    href="../process/toggle_restaurant.php?id=<?php echo $restaurant['id']; ?>"
                                    class="btn btn-outline-danger rounded-pill"
                                >

                                    <?php if($restaurant['status'] == 'active'){ ?>

                                        <i class="bi bi-pause-circle me-1"></i>
                                        Deactivate

                                    <?php } else { ?>

                                        <i class="bi bi-check-circle me-1"></i>
                                        Activate

                                    <?php } ?>

                                </a>

                            </div>


                        </div>

                    </div>

                </div>


            <?php } ?>


        <?php } else { ?>


            <!-- Empty State -->

            <div class="col-12">

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-shop"></i>

                    </div>

                    <h4 class="fw-bold">
                        No Restaurants Found
                    </h4>

                    <p class="text-muted">
                        There are currently no restaurants registered on FoodHub.
                    </p>

                    <a
                        href="add_restaurant.php"
                        class="btn btn-danger rounded-pill px-4"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Restaurant

                    </a>

                </div>

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