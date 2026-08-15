<?php

session_start();
include '../config/db.php';

// ===============================
// AUTHENTICATION
// ===============================

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit;
}

// ===============================
// GET USER
// ===============================

$user_id = $_SESSION['userId'];

$stmt = $conn->prepare("
    SELECT id, name, email, role, created_at
    FROM users
    WHERE id = ?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: dashboard.php");
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

    <title>My Profile | FoodHub</title>

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

    <!-- Your Common User CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/user.css"
    >

</head>

<body>

    <!-- Navbar -->
    <?php include '../includes/user_navbar.php'; ?>


    <!-- Profile Page -->
    <main class="user-profile-page">

        <div class="container">


            <!-- Page Heading -->

            <div class="profile-page-heading">

                <span>
                    MY ACCOUNT
                </span>

                <h1>
                    My Profile
                </h1>

                <p>
                    Manage your personal information and account details.
                </p>

            </div>


            <!-- Profile Card -->

            <div class="user-profile-card">


                <!-- Profile Header -->

                <div class="user-profile-header">

                    <div class="user-profile-avatar">

                        <?php
                        echo strtoupper(
                            substr($user['name'], 0, 1)
                        );
                        ?>

                    </div>


                    <div class="user-profile-main">

                        <h2>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </h2>

                        <p>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </p>

                        <span class="profile-role">

                            <i class="bi bi-person-fill"></i>

                            Customer

                        </span>

                    </div>

                </div>


                <hr>


                <!-- Account Information -->

                <div class="profile-section">

                    <div class="profile-section-title">

                        <i class="bi bi-person-vcard"></i>

                        Account Information

                    </div>


                    <div class="row g-4">


                        <!-- Name -->

                        <div class="col-md-6">

                            <div class="profile-info">

                                <span class="profile-info-label">
                                    Full Name
                                </span>

                                <strong>
                                    <?php echo htmlspecialchars($user['name']); ?>
                                </strong>

                            </div>

                        </div>


                        <!-- Email -->

                        <div class="col-md-6">

                            <div class="profile-info">

                                <span class="profile-info-label">
                                    Email Address
                                </span>

                                <strong>
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </strong>

                            </div>

                        </div>


                        <!-- Account Type -->

                        <div class="col-md-6">

                            <div class="profile-info">

                                <span class="profile-info-label">
                                    Account Type
                                </span>

                                <strong>
                                    Customer
                                </strong>

                            </div>

                        </div>


                        <!-- Member Since -->

                        <div class="col-md-6">

                            <div class="profile-info">

                                <span class="profile-info-label">
                                    Member Since
                                </span>

                                <strong>

                                    <?php
                                    echo date(
                                        'd M Y',
                                        strtotime($user['created_at'])
                                    );
                                    ?>

                                </strong>

                            </div>

                        </div>


                    </div>

                </div>


                <!-- Actions -->

                <div class="profile-actions">

                    <a
                        href="dashboard.php"
                        class="btn profile-back-btn"
                    >
                       
                        Back
                    </a>


                    <a
                        href="edit_profile.php"
                        class="btn profile-edit-btn"
                    >
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Profile
                    </a>

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