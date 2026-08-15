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

$id = $_GET['id'] ?? '';
$restaurant = $_SESSION['restaurant_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM foods
    WHERE id = ?
    AND restaurant_id = ?
");

$stmt->execute([$id, $restaurant]);

$food = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$food){
    echo "Food not found";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Food | FoodHub</title>

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

    <!-- Edit Food CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/edit_food.css"
    >

</head>

<body>

<!-- Navbar -->

<?php include '../includes/restaurant_navbar.php'; ?>


<!-- Main -->

<main class="edit-food-page">

    <div class="container">

        <!-- Page Header -->

        <div class="edit-page-header">

            <div>

                <span class="edit-label">
                    FOOD MANAGEMENT
                </span>

                <h1>
                    Edit Food Item
                </h1>

                <p>
                    Update the details of your food item.
                </p>

            </div>

            <a
                href="manage_food.php"
                class="btn btn-dark rounded-pill px-4"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Foods
            </a>

        </div>


        <!-- Edit Card -->

        <div class="edit-food-card">

            <div class="row g-0">


                <!-- LEFT : IMAGE -->

                <div class="col-lg-5">

                    <div class="food-preview-section">

                        <span class="preview-label">
                            FOOD PREVIEW
                        </span>

                        <div class="food-preview-image">

                            <img
                                src="../uploads/foods/<?php echo htmlspecialchars($food['image']); ?>"
                                alt="<?php echo htmlspecialchars($food['food_name']); ?>"
                            >

                        </div>

                        <h4>
                            <?php echo htmlspecialchars($food['food_name']); ?>
                        </h4>

                        <div class="preview-price">

                            ₹<?php echo htmlspecialchars($food['price']); ?>

                        </div>

                    </div>

                </div>


                <!-- RIGHT : FORM -->

                <div class="col-lg-7">

                    <div class="edit-form-section">

                        <div class="form-heading">

                            <h3>
                                Food Information
                            </h3>

                            <p>
                                Make changes to your food item below.
                            </p>

                        </div>


                        <form
                            action="../process/edit_food_process.php"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            <!-- Food ID -->

                            <input
                                type="hidden"
                                name="id"
                                value="<?php echo $food['id']; ?>"
                            >


                            <!-- Food Name -->

                            <div class="mb-4">

                                <label class="form-label">
                                    Food Name
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-egg-fried"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="food_name"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($food['food_name']); ?>"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- Description -->

                            <div class="mb-4">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="4"
                                    required
                                ><?php echo htmlspecialchars($food['description']); ?></textarea>

                            </div>


                            <!-- Price -->

                            <div class="mb-4">

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
                                        value="<?php echo htmlspecialchars($food['price']); ?>"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- Current Image -->

                            <div class="mb-4">

                                <label class="form-label">
                                    Change Food Image
                                </label>

                                <div class="image-upload-box">

                                    <i class="bi bi-cloud-arrow-up"></i>

                                    <div>

                                        <strong>
                                            Choose a new image
                                        </strong>

                                        <small>
                                            Leave empty to keep the current image.
                                        </small>

                                    </div>

                                    <input
                                        type="file"
                                        name="food_image"
                                        class="form-control mt-3"
                                    >

                                </div>

                            </div>


                            <!-- Approval Notice -->

                            <div class="approval-notice">

                                <i class="bi bi-info-circle-fill"></i>

                                <div>

                                    <strong>
                                        Admin approval required
                                    </strong>

                                    <p>
                                        After updating this food item, it will be sent for admin approval again.
                                    </p>

                                </div>

                            </div>


                            <!-- Buttons -->

                            <div class="form-actions">

                                <a
                                    href="manage_food.php"
                                    class="btn btn-light rounded-pill px-4"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-danger rounded-pill px-4"
                                >
                                    <i class="bi bi-check-lg me-1"></i>
                                    Update Food
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>