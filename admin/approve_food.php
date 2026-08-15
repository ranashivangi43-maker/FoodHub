<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['userId'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

// Fetch pending foods
$stmt = $conn->prepare("
    SELECT foods.*, 
           restaurants.name AS restaurant_name
    FROM foods
    JOIN restaurants
        ON foods.restaurant_id = restaurants.id
    WHERE foods.status = 'pending'
    ORDER BY foods.id DESC
");

$stmt->execute();

$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Food Approvals | FoodHub</title>

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
                Food Approvals
            </h1>

            <p class="text-muted mb-0">
                Review and approve food items submitted by restaurants.
            </p>

        </div>

    </div>


    <!-- Pending Summary -->

    <div class="restaurant-summary mb-4">

        <div class="summary-icon">

            <i class="bi bi-hourglass-split"></i>

        </div>

        <div>

            <small class="text-muted">
                Pending Approvals
            </small>

            <h4 class="fw-bold mb-0">
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


                        <!-- Food Image -->

                        <div class="food-image-wrapper">

                            <img
                                src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                class="food-image"
                                alt="Food"
                            >

                            <div class="food-status">

                                <span class="food-pending-badge">

                                    <i class="bi bi-clock-fill"></i>

                                    Pending

                                </span>

                            </div>

                        </div>


                        <!-- Food Body -->

                        <div class="food-body">

                            <h4 class="fw-bold mb-2">

                                <?php
                                echo htmlspecialchars($food['food_name']);
                                ?>

                            </h4>


                            <!-- Restaurant -->

                            <div class="food-info">

                                <div class="food-info-icon">

                                    <i class="bi bi-shop"></i>

                                </div>

                                <div>

                                    <small>
                                        Restaurant
                                    </small>

                                    <p>
                                        <?php
                                        echo htmlspecialchars(
                                            $food['restaurant_name']
                                        );
                                        ?>
                                    </p>

                                </div>

                            </div>


                            <!-- Description -->

                            <div class="food-description">

                                <?php
                                echo htmlspecialchars($food['description']);
                                ?>

                            </div>


                            <!-- Price -->

                            <div class="food-price">

                                <span>
                                    Price
                                </span>

                                <strong>
                                    ₹<?php echo htmlspecialchars($food['price']); ?>
                                </strong>

                            </div>


                            <!-- Actions -->

                            <div class="food-actions">

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

                        <i class="bi bi-check2-circle"></i>

                    </div>

                    <h4 class="fw-bold">
                        No Pending Foods
                    </h4>

                    <p class="text-muted">
                        There are currently no food items waiting for approval.
                    </p>

                    <a
                        href="dashboard.php"
                        class="btn btn-danger rounded-pill px-4"
                    >

                        <i class="bi bi-speedometer2 me-1"></i>

                        Back to Dashboard

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