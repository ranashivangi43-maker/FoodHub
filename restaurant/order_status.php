<?php

session_start();
include '../config/db.php';

if (!isset($_SESSION['userId'])) {
    echo "Not logged in";
    exit;
}

$user_id = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: orders.php");
    exit;
}

$order_id = $_POST['order_id'] ?? '';
$new_status = $_POST['status'] ?? '';

if (empty($order_id) || empty($new_status)) {
    header("Location: orders.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Allowed Statuses
|--------------------------------------------------------------------------
*/

$allowed_statuses = [
    'pending',
    'confirmed',
    'delivered',
    'cancelled'
];

if (!in_array($new_status, $allowed_statuses)) {
    header("Location: orders.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Restaurant
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id
    FROM restaurants
    WHERE owner_id = ?
");

$stmt->execute([$user_id]);

$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    echo "Restaurant not found";
    exit;
}

$restaurant_id = $restaurant['id'];

/*
|--------------------------------------------------------------------------
| Verify Order Belongs To This Restaurant
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT orders.id, orders.status
    FROM orders

    INNER JOIN order_item
        ON orders.id = order_item.order_id

    INNER JOIN foods
        ON order_item.food_id = foods.id

    WHERE orders.id = ?
    AND foods.restaurant_id = ?

    LIMIT 1
");

$stmt->execute([
    $order_id,
    $restaurant_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found";
    exit;
}

/*
|--------------------------------------------------------------------------
| Prevent Invalid Status Changes
|--------------------------------------------------------------------------
*/

$current_status = $order['status'];

/*
|--------------------------------------------------------------------------
| Status Flow
|--------------------------------------------------------------------------
*/

$valid_transition = false;

if ($current_status === 'pending' && in_array($new_status, ['confirmed', 'cancelled'])) {
    $valid_transition = true;
}

if ($current_status === 'confirmed' && $new_status === 'delivered') {
    $valid_transition = true;
}

if ($current_status === $new_status) {
    $valid_transition = true;
}

if (!$valid_transition) {
    echo "Invalid status change";
    exit;
}

/*
|--------------------------------------------------------------------------
| Update Order
|--------------------------------------------------------------------------
*/

$update = $conn->prepare("
    UPDATE orders
    SET status = ?
    WHERE id = ?
");

$update->execute([
    $new_status,
    $order_id
]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: order_details.php?id=" . $order_id . "&updated=1");
exit;

?>