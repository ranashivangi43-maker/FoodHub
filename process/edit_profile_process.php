<?php

session_start();

include '../config/db.php';


// =========================================
// AUTHENTICATION
// =========================================

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit;
}


// =========================================
// GET USER
// =========================================

$user_id = $_SESSION['userId'];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';


// =========================================
// BASIC VALIDATION
// =========================================

if ($name == '' || $email == '') {
    die("Name and email are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}


// =========================================
// GET CURRENT USER
// =========================================

$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE id = ?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}


// =========================================
// CHECK EMAIL
// =========================================

$stmt = $conn->prepare("
    SELECT id
    FROM users
    WHERE email = ?
    AND id != ?
");

$stmt->execute([$email, $user_id]);

if ($stmt->fetch()) {
    die("This email is already registered.");
}


// =========================================
// PASSWORD CHANGE
// =========================================

$password = $user['password'];

if ($current_password != '' || $new_password != '' || $confirm_password != '') {

    if ($current_password == '') {
        die("Current password is required.");
    }

    if (!password_verify($current_password, $user['password'])) {
        die("Current password is incorrect.");
    }

    if ($new_password == '') {
        die("New password is required.");
    }

    if ($new_password !== $confirm_password) {
        die("New passwords do not match.");
    }

    if (strlen($new_password) < 6) {
        die("New password must contain at least 6 characters.");
    }

    $password = password_hash($new_password, PASSWORD_DEFAULT);
}


// =========================================
// UPDATE USER
// =========================================

$stmt = $conn->prepare("
    UPDATE users
    SET name = ?, email = ?, password = ?
    WHERE id = ?
");

$stmt->execute([
    $name,
    $email,
    $password,
    $user_id
]);


// =========================================
// UPDATE SESSION
// =========================================

$_SESSION['userName'] = $name;


// =========================================
// REDIRECT
// =========================================

$_SESSION['success'] = "Profile updated successfully.";

header("Location: ../user/edit_profile.php");
exit;

?>