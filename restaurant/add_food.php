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
// CHECK RESTAURANT
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

$_SESSION['restaurant_id'] = $restaurant['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Food | FoodHub</title>


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


    <!-- Add Food CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/add_food.css"
    >

</head>


<body>


<!-- Navbar -->

<?php include '../includes/restaurant_navbar.php'; ?>


<!-- Main -->

<main class="add-food-page">

    <div class="container">


        <!-- Page Header -->

        <div class="add-food-header">

            <div>

                <span class="dashboard-label">
                    RESTAURANT MENU
                </span>

                <h1>
                    Add Food Item
                </h1>

                <p>
                    Add a new food item to your restaurant menu.
                    It will be sent for admin approval.
                </p>

            </div>


            <a
                href="manage_food.php"
                class="btn btn-dark rounded-pill px-4"
            >

                <i class="bi bi-arrow-left me-1"></i>

                My Foods

            </a>

        </div>



        <!-- Form -->

        <div class="add-food-card">

            <form
                action="../process/add_food_process.php"
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- Food Name -->

                <div class="form-section">

                    <label class="form-label">
                        Food Name
                    </label>

                    <input
                        type="text"
                        name="food_name"
                        class="form-control"
                        placeholder="e.g. Butter Chicken"
                        required
                    >

                </div>



                <!-- Description -->

                <div class="form-section">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        placeholder="Describe your food item..."
                        required
                    ></textarea>

                </div>



                <!-- Price -->

                <div class="form-section">

                    <label class="form-label">
                        Price
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            ₹
                        </span>

                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            placeholder="Enter price"
                            min="1"
                            step="0.01"
                            required
                        >

                    </div>

                </div>



                <!-- Main Image -->

                <div class="form-section">

                    <label class="form-label">
                        Main Food Image
                    </label>

                    <input
                        type="file"
                        name="food_image"
                        class="form-control"
                        accept="image/*"
                        required
                    >

                    <small class="form-help">
                        This image will be used as the main image of your food item.
                    </small>

                </div>



                <!-- Additional Images -->

                <div class="form-section">

                    <label class="form-label">
                        Additional Food Images
                    </label>

                    <input
                        type="file"
                        name="extra_images[]"
                        class="form-control"
                        accept="image/*"
                        multiple
                    >

                    <small class="form-help">
                        You can select multiple additional images.
                    </small>

                </div>



                <!-- Approval Notice -->

                <div class="approval-notice">

                    <div class="approval-icon">

                        <i class="bi bi-info-circle"></i>

                    </div>

                    <div>

                        <strong>
                            Admin Approval Required
                        </strong>

                        <p>
                            After submitting this food item, it will remain
                            pending until an administrator approves it.
                        </p>

                    </div>

                </div>



                <!-- Buttons -->

                <div class="form-actions">

                    <a
                        href="dashboard.php"
                        class="btn btn-outline-secondary rounded-pill px-4"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-danger rounded-pill px-4"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Food

                    </button>

                </div>


            </form>

        </div>

    </div>

</main>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>