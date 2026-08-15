<?php

session_start();
include('../config/db.php');

if(!isset($_SESSION['userId'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] != 'restaurant'){
    header("Location: ../login.php");
    exit;
}

$restaurant_id = $_SESSION['restaurant_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM foods
    WHERE restaurant_id = ?
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

    <title>My Foods | FoodHub</title>


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


    <!-- Manage Food CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/manage_food.css"
    >

</head>


<body>


<!-- Navbar -->

<?php include '../includes/restaurant_navbar.php'; ?>


<!-- Main -->

<main class="manage-food-page">

    <div class="container">


        <!-- Page Header -->

        <div class="manage-food-header">

            <div>

                <span class="dashboard-label">
                    MENU MANAGEMENT
                </span>

                <h1>
                    My Foods
                </h1>

                <p>
                    View and manage all food items added to your restaurant.
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


                        <div class="manage-food-card h-100">


                            <!-- Image -->

                            <div class="manage-food-image-wrapper">

                                <img
                                    src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                    class="manage-food-image"
                                    alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                                >


                                <!-- Status -->

                                <div class="manage-food-status">

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



                            <!-- Body -->

                            <div class="manage-food-body">


                                <div class="d-flex justify-content-between align-items-start gap-3">

                                    <h4>
                                        <?php echo htmlspecialchars($food['food_name']); ?>
                                    </h4>

                                    <div class="manage-food-price">
                                        ₹<?php echo htmlspecialchars($food['price']); ?>
                                    </div>

                                </div>


                                <p class="manage-food-description">

                                    <?php echo htmlspecialchars($food['description']); ?>

                                </p>



                                <!-- Actions -->

                                <div class="manage-food-actions">


                                    <a
                                        href="edit_food.php?id=<?php echo $food['id']; ?>"
                                        class="btn btn-dark rounded-pill"
                                    >

                                        <i class="bi bi-pencil me-1"></i>

                                        Edit

                                    </a>



                                    <button
                                        type="button"
                                        class="btn btn-outline-danger rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal<?php echo $food['id']; ?>"
                                    >

                                        <i class="bi bi-trash me-1"></i>

                                        Delete

                                    </button>


                                </div>


                            </div>


                        </div>


                    </div>



                    <!-- Delete Modal -->

                    <div
                        class="modal fade"
                        id="deleteModal<?php echo $food['id']; ?>"
                        tabindex="-1"
                    >

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content delete-modal">


                                <div class="modal-header">

                                    <h5 class="modal-title">

                                        Delete Food

                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                    ></button>

                                </div>


                                <div class="modal-body">

                                    Are you sure you want to delete

                                    <strong>
                                        <?php echo htmlspecialchars($food['food_name']); ?>
                                    </strong>?

                                    <p class="text-muted small mt-2 mb-0">
                                        This action cannot be undone.
                                    </p>

                                </div>


                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-light rounded-pill px-4"
                                        data-bs-dismiss="modal"
                                    >
                                        Cancel
                                    </button>


                                    <a
                                        href="../process/delete_food.php?id=<?php echo $food['id']; ?>"
                                        class="btn btn-danger rounded-pill px-4"
                                    >
                                        Delete
                                    </a>

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
                            You haven't added any food items to your restaurant menu yet.
                        </p>


                        <a
                            href="add_food.php"
                            class="btn btn-danger rounded-pill px-4"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Add Your First Food

                        </a>

                    </div>

                </div>


            <?php } ?>


        </div>

    </div>

</main>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>