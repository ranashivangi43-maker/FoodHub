<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] !== 'restaurant') {
    header("Location: ../login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Complete Restaurant Profile | FoodHub</title>

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

<!-- Simple Header -->
<nav class="restaurant-header">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center">

            <a href="#" class="restaurant-logo">
                🍔 FoodHub
            </a>

            <a href="../restaurant/logout.php"
               class="btn btn-danger rounded-pill px-4">
                Logout
            </a>

        </div>

    </div>

</nav>


<!-- Main Content -->
<main class="profile-page">

    <div class="container">

        <!-- Page Heading -->
        <div class="profile-heading text-center">

            <span class="profile-label">
                RESTAURANT OWNER
            </span>

            <h1>
                Complete Your Restaurant Profile
            </h1>

            <p>
                Add your restaurant information so customers can discover your restaurant.
            </p>

        </div>


        <!-- Profile Card -->
        <div class="profile-card">

            <!-- Icon -->
            <div class="profile-icon">

                <i class="bi bi-shop"></i>

            </div>


            <div class="text-center mb-4">

                <h2>
                    Restaurant Information
                </h2>

                <p>
                    Tell us about your restaurant
                </p>

            </div>


            <form
                action="../process/complete_restaurant_profile_process.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- Restaurant Name -->
                <div class="form-group">

                    <label>
                        Restaurant Name
                    </label>

                    <div class="input-wrapper">

                        <i class="bi bi-shop"></i>

                        <input
                            type="text"
                            name="restaurant_name"
                            placeholder="Enter your restaurant name"
                            required
                        >

                    </div>

                </div>


                <!-- Phone -->
                <div class="form-group">

                    <label>
                        Phone Number
                    </label>

                    <div class="input-wrapper">

                        <i class="bi bi-telephone"></i>

                        <input
                            type="tel"
                            name="phone"
                            placeholder="Enter restaurant phone number"
                            required
                        >

                    </div>

                </div>


                <!-- Address -->
                <div class="form-group">

                    <label>
                        Restaurant Address
                    </label>

                    <div class="input-wrapper textarea-wrapper">

                        <i class="bi bi-geo-alt"></i>

                        <textarea
                            name="address"
                            rows="4"
                            placeholder="Enter complete restaurant address"
                            required
                        ></textarea>

                    </div>

                </div>


                <!-- Image -->
                <div class="form-group">

                    <label>
                        Restaurant Image
                    </label>

                    <div class="image-upload">

                        <i class="bi bi-image"></i>

                        <span>
                            Upload restaurant image
                        </span>

                        <small>
                            JPG, JPEG or PNG
                        </small>

                        <input
                            type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png"
                            required
                        >

                    </div>

                </div>


                <!-- Submit -->
                <button
                    type="submit"
                    class="btn profile-submit w-100"
                >

                    <i class="bi bi-check-circle me-2"></i>

                    Complete Restaurant Profile

                </button>

            </form>

        </div>

    </div>

</main>

</body>
</html>