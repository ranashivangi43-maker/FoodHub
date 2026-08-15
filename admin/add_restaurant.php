<?php
session_start();

if(!isset($_SESSION['userId'])){
    header("Location: ../index.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Restaurant Owner | FoodHub</title>

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
        href="/PHP/Restaurant/assets/css/admin.css"
    >

</head>

<body>

<!-- ================= NAVBAR ================= -->
<?php include '../includes/admin_navbar.php'; ?>


<!-- ================= MAIN ================= -->

<main class="admin-page">

    <div class="container">

        <!-- Page Heading -->

        <div class="admin-page-heading">

            <div>

                <p class="text-danger fw-semibold mb-1">
                    ADMIN PANEL
                </p>

                <h1 class="fw-bold mb-2">
                    Add Restaurant Owner
                </h1>

                <p class="text-muted mb-0">
                    Create an account for a new restaurant owner.
                </p>

            </div>

        </div>


        <!-- ================= FORM CARD ================= -->

        <div class="row justify-content-center">

            <div class="col-lg-7 col-xl-6">

                <div class="admin-form-card">

                    <!-- Icon -->

                    <div class="form-icon">

                        <i class="bi bi-person-fill-add"></i>

                    </div>


                    <div class="text-center mb-4">

                        <h3 class="fw-bold">
                            Owner Information
                        </h3>

                        <p class="text-muted mb-0">
                            Create login credentials for the restaurant owner.
                        </p>

                    </div>


                    <!-- Form -->

                    <form
                        action="../process/add_restaurant_process.php"
                        method="POST"
                        id="addOwnerForm"
                    >

                        <!-- Full Name -->

                        <div class="mb-3">

                            <label
                                for="ownerName"
                                class="form-label fw-semibold"
                            >
                                Full Name
                            </label>

                            <div class="input-group">

                                <span class="input-group-text clean-icon">
                                    <i class="bi bi-person"></i>
                                </span>

                                <input
                                    type="text"
                                    id="ownerName"
                                    name="owner_name"
                                    class="form-control clean-input"
                                    placeholder="Enter owner's full name"
                                    required
                                >

                            </div>

                        </div>


                        <!-- Email -->

                        <div class="mb-3">

                            <label
                                for="ownerEmail"
                                class="form-label fw-semibold"
                            >
                                Email Address
                            </label>

                            <div class="input-group">

                                <span class="input-group-text clean-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    id="ownerEmail"
                                    name="owner_email"
                                    class="form-control clean-input"
                                    placeholder="Enter owner's email"
                                    required
                                >

                            </div>

                        </div>


                        <!-- Password -->

                        <div class="mb-4">

                            <label
                                for="ownerPassword"
                                class="form-label fw-semibold"
                            >
                                Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text clean-icon">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    id="ownerPassword"
                                    name="password"
                                    class="form-control clean-input"
                                    placeholder="Create a password"
                                    required
                                >

                            </div>

                            <small class="text-muted">
                                The owner will use this password to log in.
                            </small>

                        </div>


                        <!-- Submit -->

                        <button
                            type="submit"
                            class="btn btn-danger w-100 rounded-pill py-3 fw-semibold"
                        >
                            <i class="bi bi-person-plus me-2"></i>
                            Create Restaurant Account
                        </button>


                    </form>

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