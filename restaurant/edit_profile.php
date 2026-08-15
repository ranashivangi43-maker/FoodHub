<?php

session_start();
include '../config/db.php';

if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

if($_SESSION['role'] != 'restaurant'){
    header("Location: ../index.php");
    exit;
}

$owner_id = $_SESSION['userId'];

/* Get restaurant details */

$stmt = $conn->prepare("
    SELECT restaurants.*, users.name AS owner_name, users.email
    FROM restaurants
    INNER JOIN users
        ON restaurants.owner_id = users.id
    WHERE restaurants.owner_id = ?
");

$stmt->execute([$owner_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
$updated = isset($_GET['updated']) && $_GET['updated'] == '1';
if(!$restaurant){
    header("Location: complete_profile.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Restaurant Profile | FoodHub</title>

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

</head>

<body>

<?php include '../includes/restaurant_navbar.php'; ?>


<main class="restaurant-page">

    <div class="container">

        <!-- Page Heading -->

        <div class="profile-page-heading ">

            <span>
                RESTAURANT PROFILE
            </span>

            <h1>
                Edit Your Profile
            </h1>

            <p>
                Keep your restaurant information up to date.
            </p>

        </div>


        <!-- Profile Card -->

        <div class="profile-card">
<?php if($updated){ ?>

    <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>Restaurant profile updated successfully.</span>
    </div>

<?php } ?>
            <form
                action="../process/update_restaurant_profile.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- Restaurant Image -->

                <div class="profile-image-section">

                    <div class="current-profile-image">

                        <?php if(!empty($restaurant['image'])){ ?>

                            <img
                                src="../uploads/restaurants/<?php echo htmlspecialchars($restaurant['image']); ?>"
                                alt="Restaurant Image"
                            >

                        <?php } else { ?>

                            <div class="profile-image-placeholder">
                                <i class="bi bi-shop"></i>
                            </div>

                        <?php } ?>

                    </div>

                    <div>

                        <h5>
                            Restaurant Image
                        </h5>

                        <p>
                            Upload a new image if you want to change it.
                        </p>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/*"
                        >

                    </div>

                </div>


                <hr>


                <!-- Owner Information -->

                <h5 class="profile-section-title">
                    <i class="bi bi-person"></i>
                    Owner Information
                </h5>


                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Owner Name
                        </label>

                        <input
                            type="text"
                            name="owner_name"
                            class="form-control profile-input"
                            value="<?php echo htmlspecialchars($restaurant['owner_name']); ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="owner_email"
                            class="form-control profile-input"
                            value="<?php echo htmlspecialchars($restaurant['email']); ?>"
                            required
                        >

                    </div>

                </div>


                <!-- Restaurant Information -->

                <h5 class="profile-section-title mt-4">
                    <i class="bi bi-shop"></i>
                    Restaurant Information
                </h5>


                <div class="mb-3">

                    <label class="form-label">
                        Restaurant Name
                    </label>

                    <input
                        type="text"
                        name="restaurant_name"
                        class="form-control profile-input"
                        value="<?php echo htmlspecialchars($restaurant['name']); ?>"
                        required
                    >

                </div>


                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control profile-input"
                            value="<?php echo htmlspecialchars($restaurant['phone']); ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control profile-input"
                            value="<?php echo ucfirst(htmlspecialchars($restaurant['status'])); ?>"
                            disabled
                        >

                    </div>

                </div>


                <div class="mt-3">

                    <label class="form-label">
                        Address
                    </label>

                    <textarea
                        name="address"
                        class="form-control profile-input"
                        rows="4"
                        required
                    ><?php echo htmlspecialchars($restaurant['address']); ?></textarea>

                </div>


                <!-- Buttons -->

                <div class="profile-actions">

                    <a
                        href="dashboard.php"
                        class="btn btn-light rounded-pill px-4"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-danger rounded-pill px-4"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>