<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "db.php";

$user_id = $conn->real_escape_string($_GET['user_id']);

$orders = [];

// Get all orders of the user
$orderResult = $conn->query("
    SELECT *
    FROM orders
    WHERE user_id='$user_id'
    ORDER BY id DESC
");

while ($order = $orderResult->fetch_assoc()) {

    $order_id = $order['id'];

    // Get all products of this order
    $items = [];

    $itemResult = $conn->query("
        SELECT
            product_name,
            quantity,
            price
        FROM order_items
        WHERE order_id='$order_id'
    ");

    while ($item = $itemResult->fetch_assoc()) {
        $items[] = $item;
    }

    // Attach products to order
    $order['items'] = $items;

    $orders[] = $order;
}

echo json_encode($orders);
?>