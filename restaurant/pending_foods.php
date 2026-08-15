<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['userId'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] != 'restaurant'){
    header("Location: ../login.php");
    exit;
}

$restaurant_id = $_SESSION['restaurant_id'] ?? '';

if($restaurant_id == ''){
    header("Location: dashboard.php");
    exit;
}


/* =========================================
   FETCH PENDING FOODS
========================================= */

$stmt = $conn->prepare("
    SELECT *
    FROM foods
    WHERE restaurant_id = ?
    AND status = 'pending'
    ORDER BY id DESC
");

$stmt->execute([$restaurant_id]);

$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pending Foods | FoodHub</title>


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


    <!-- Pending Foods CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/pending_foods.css"
    >

</head>


<body>


<!-- Navbar -->

<?php include '../includes/restaurant_navbar.php'; ?>


<!-- Main -->

<main class="pending-food-page">

    <div class="container">


        <!-- Page Header -->

        <div class="pending-page-header">

            <div>

                <span class="pending-label">
                    FOOD APPROVALS
                </span>

                <h1>
                    Pending Foods
                </h1>

                <p>
                    Food items waiting for admin approval.
                </p>

            </div>


            <a
                href="add_food.php"
                class="btn btn-danger rounded-pill px-4"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Food

            </a>

        </div>


        <!-- Summary -->

        <div class="pending-summary">

            <div class="pending-summary-icon">

                <i class="bi bi-clock-history"></i>

            </div>


            <div>

                <small>
                    Awaiting Approval
                </small>

                <h3>
                    <?php echo count($foods); ?>
                </h3>

            </div>

        </div>


        <!-- Food Cards -->

        <div class="row g-4">


            <?php if(count($foods) > 0){ ?>


                <?php foreach($foods as $food){ ?>


                    <div class="col-12 col-md-6 col-xl-4">


                        <div class="pending-food-card">


                            <!-- Image -->

                            <div class="pending-image-wrapper">

                                <img
                                    src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                    alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                >


                                <div class="pending-status">

                                    <span class="pending-badge">

                                        <i class="bi bi-clock-fill"></i>

                                        Pending

                                    </span>

                                </div>

                            </div>


                            <!-- Body -->

                            <div class="pending-food-body">


                                <div class="d-flex justify-content-between align-items-start gap-3">

                                    <h4>
                                        <?php echo htmlspecialchars($food['food_name']); ?>
                                    </h4>

                                    <span class="pending-price">
                                        ₹<?php echo htmlspecialchars($food['price']); ?>
                                    </span>

                                </div>


                                <p class="pending-description">

                                    <?php echo htmlspecialchars($food['description']); ?>

                                </p>


                                <!-- Approval Message -->

                                <div class="pending-info">

                                    <i class="bi bi-info-circle"></i>

                                    <span>
                                        Waiting for admin approval
                                    </span>

                                </div>


                                <!-- Action -->

                                <div class="pending-actions">

                                    <a
                                        href="edit_food.php?id=<?php echo $food['id']; ?>"
                                        class="btn btn-outline-dark rounded-pill w-100"
                                    >

                                        <i class="bi bi-pencil me-1"></i>

                                        Edit Food

                                    </a>

                                </div>


                            </div>


                        </div>


                    </div>


                <?php } ?>


            <?php } else { ?>


                <!-- Empty State -->

                <div class="col-12">

                    <div class="pending-empty">

                        <div class="pending-empty-icon">

                            <i class="bi bi-check-circle"></i>

                        </div>


                        <h3>
                            No Pending Foods
                        </h3>


                        <p>
                            All your food items have been processed by the admin.
                        </p>


                        <a
                            href="add_food.php"
                            class="btn btn-danger rounded-pill px-4"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Add New Food

                        </a>

                    </div>

                </div>


            <?php } ?>


        </div>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>