<?php

session_start();
include '../config/db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['userId'];

$stmt = $conn->prepare("
    SELECT name, email
    FROM users
    WHERE id = ?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
$success = $_SESSION['success'] ?? '';

unset($_SESSION['success']);
if (!$user) {
    echo "User not found";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Profile | FoodHub</title>

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

    <!-- User CSS -->
    <link
        rel="stylesheet"
        href="../assets/css/user.css"
    >

</head>

<body>

    <!-- Navbar -->
    <?php include '../includes/user_navbar.php'; ?>


    <main class="profile-page">

        <div class="container">
<?php if ($success): ?>

    <div class="alert alert-success alert-dismissible fade show profile-alert" role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        <?php echo htmlspecialchars($success); ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

<?php endif; ?>
            <!-- Page Heading -->

            <div class="profile-page-heading">

                <span>ACCOUNT SETTINGS</span>

                <h1>Edit Your Profile</h1>

                <p>
                    Update your personal information and keep your account details up to date.
                </p>

            </div>


            <!-- Profile Card -->

            <div class="profile-card">

                <!-- Profile Header -->

                <div class="profile-header">

                    <div class="profile-avatar-large">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>

                    <div>

                        <h4>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </h4>

                        <p>
                            FoodHub Customer
                        </p>

                    </div>

                </div>


                <hr>


                <!-- Form -->

                <form
                    action="../process/edit_profile_process.php"
                    method="POST"
                >

                    <!-- Personal Information -->

                    <div class="profile-section-title">

                        <i class="bi bi-person"></i>

                        Personal Information

                    </div>


                    <!-- Name -->

                    <div class="form-group">

                        <label>
                            Full Name
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-person"></i>

                            <input
                                type="text"
                                name="name"
                                value="<?php echo htmlspecialchars($user['name']); ?>"
                                required
                            >

                        </div>

                    </div>


                    <!-- Email -->

                    <div class="form-group">

                        <label>
                            Email Address
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                required
                            >

                        </div>

                    </div>


                    <!-- Password -->

                    <div class="profile-section-title mt-4">

                        <i class="bi bi-shield-lock"></i>

                        Change Password

                    </div>

                    <p class="password-note">
                        Leave these fields empty if you don't want to change your password.
                    </p>


                    <!-- Current Password -->

                    <div class="form-group">

                        <label>
                            Current Password
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-lock"></i>

                            <input
                                type="password"
                                name="current_password"
                                placeholder="Enter current password"
                            >

                        </div>

                    </div>


                    <!-- New Password -->

                    <div class="form-group">

                        <label>
                            New Password
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-key"></i>

                            <input
                                type="password"
                                name="new_password"
                                placeholder="Enter new password"
                            >

                        </div>

                    </div>


                    <!-- Confirm Password -->

                    <div class="form-group">

                        <label>
                            Confirm New Password
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-key-fill"></i>

                            <input
                                type="password"
                                name="confirm_password"
                                placeholder="Confirm new password"
                            >

                        </div>

                    </div>


                    <!-- Actions -->

                    <div class="profile-actions">

                        <a
                            href="dashboard.php"
                            class="btn btn-light"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="profile-submit"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Save Changes
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>