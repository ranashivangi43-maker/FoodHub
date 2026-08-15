<?php

session_start();

include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/add_restaurant.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Owner Details
|--------------------------------------------------------------------------
*/

$name = trim($_POST['owner_name'] ?? '');
$email = trim($_POST['owner_email'] ?? '');
$password = $_POST['password'] ?? '';


/*
|--------------------------------------------------------------------------
| Server-side Validation
|--------------------------------------------------------------------------
*/

if ($name === '' || $email === '' || $password === '') {
    echo "All fields are required.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address.";
    exit;
}

if (strlen($password) < 6) {
    echo "Password must be at least 6 characters.";
    exit;
}


/*
|--------------------------------------------------------------------------
| Check if Email Already Exists
|--------------------------------------------------------------------------
*/

$check = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$check->execute([$email]);

if ($check->fetch(PDO::FETCH_ASSOC)) {
    echo "An account with this email already exists.";
    exit;
}


/*
|--------------------------------------------------------------------------
| Create Restaurant Owner Account
|--------------------------------------------------------------------------
*/

$hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->prepare(
    "INSERT INTO users (name, email, password, role)
     VALUES (?, ?, ?, ?)"
);

$insert->execute([
    $name,
    $email,
    $hash,
    'restaurant'
]);


/*
|--------------------------------------------------------------------------
| Get Newly Created User ID
|--------------------------------------------------------------------------
*/

$userId = $conn->lastInsertId();


/*
|--------------------------------------------------------------------------
| Redirect Admin Back to Dashboard
|--------------------------------------------------------------------------
*/

header("Location: ../admin/dashboard.php");
exit;

?>