<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id        = $conn->real_escape_string($data['user_id']);
$address        = $conn->real_escape_string($data['address']);
$items          = $data['items'];
$payment_method = $conn->real_escape_string($data['payment_method'] ?? 'cod');
$customer_name  = $conn->real_escape_string($data['customer_name'] ?? '');
$customer_phone = $conn->real_escape_string($data['customer_phone'] ?? '');

// Calculate Total
$total = 0;

foreach ($items as $item) {
    if (is_array($item) && isset($item['price'], $item['qty'])) {
        $total += floatval($item['price']) * intval($item['qty']);
    }
}

do {
    $unique_id = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

    $check = $conn->query("SELECT id FROM orders WHERE unique_id='$unique_id'");
} while ($check->num_rows > 0);

// Save Order
$conn->query("
INSERT INTO orders
(
    unique_id,
    user_id,
    total,
    address,
    payment_method,
    customer_name,
    customer_phone
)
VALUES
(
    '$unique_id',
    '$user_id',
    '$total',
    '$address',
    '$payment_method',
    '$customer_name',
    '$customer_phone'
)");

$order_id = $conn->insert_id;

// Save Order Items
$itemsForSync = [];

foreach ($items as $name => $item) {

    if(!is_array($item)) continue;

    $qty   = intval($item['qty']);
    $price = floatval($item['price']);

    $product_name = $conn->real_escape_string($name);

    $conn->query("
    INSERT INTO order_items
    (
    order_id,
    product_name,
    quantity,
    price
    )
    VALUES
    (
    '$order_id',
    '$product_name',
    '$qty',
    '$price'
    )");

    $itemsForSync[] = [
        "product_name"=>$name,
        "qty"=>$qty,
        "price"=>$price
    ];
}

// Get Customer Details
$customer_email = "";

$userResult = $conn->query("SELECT name,email FROM users WHERE id='$user_id'");

if($userResult && $userResult->num_rows){

    $user = $userResult->fetch_assoc();

    if($customer_name==""){
        $customer_name = $user['name'];
    }

    $customer_email = $user['email'];
}

//=====================================================
// SEND TO TOTALKARO
//=====================================================

$payload = [

    "website_order_id"=>$order_id,

    "customer_name"=>$customer_name,

    "customer_phone"=>$customer_phone,

    "customer_email"=>$customer_email,

    "address"=>$address,

    "payment_mode"=>$payment_method,

    "total"=>$total,

    "items"=>$itemsForSync

];

$jsonPayload = json_encode($payload);

// Replace with your actual API URL
$totalkaroApiUrl = "https://api.totalkaro.com/api/online-orders";

$syncPayload = json_encode([
    "totalkaro_user_id" => 6,          // ← Fixed: always maps to TotalKaro user 6
    "website_order_id"  => $order_id,
    "customer_name"     => $customer_name,
    "customer_phone"    => $customer_phone,
    "customer_email"    => $customer_email,
    "address"           => $address,
    "payment_mode"      => $payment_method,
    "total"             => $total,
    "items"             => $itemsForSync
]);

$ch = curl_init($totalkaroApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Content-Length: " . strlen($syncPayload)
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $syncPayload);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 second timeout

$syncResponse = curl_exec($ch);
$syncHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError    = curl_error($ch);
curl_close($ch);

$syncResult = json_decode($syncResponse, true);

// ── Return response ───────────────────────────────────────
echo json_encode([
    "success"       => true,
    "order_id"      => $order_id,
    "total"         => $total,

    "http_code"     => $syncHttpCode,
    "curl_error"    => $curlError,
    "api_response"  => $syncResponse,

    "synced_to_pos" => ($syncHttpCode === 200),
    "pos_order_id"  => $syncResult['orderId'] ?? null
]);


?>