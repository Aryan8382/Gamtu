<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

header("Content-Type: application/json");

include "db.php";

// Read JSON
$data = json_decode(file_get_contents("php://input"), true);

$user_id   = intval($data['user_id'] ?? 0);
$latitude  = floatval($data['latitude'] ?? 0);
$longitude = floatval($data['longitude'] ?? 0);

if (!$user_id || !$latitude || !$longitude) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid data"
    ]);
    exit;
}

// Reverse Geocoding using OpenStreetMap
$url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=".$latitude."&lon=".$longitude;

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: GamtuIndia/1.0\r\n"
    ]
];

$context = stream_context_create($opts);

$response = @file_get_contents($url, false, $context);

$address = "";

if($response){
    $json = json_decode($response, true);

    if(isset($json['display_name'])){
        $address = $conn->real_escape_string($json['display_name']);
    }
}

// Update user
$sql = "UPDATE users
SET
latitude = '$latitude',
longitude = '$longitude',
current_address = '$address',
location_updated_at = NOW()
WHERE id = '$user_id'";

if($conn->query($sql)){

    echo json_encode([
        "success" => true,
        "message" => "Location Updated",
        "address" => $address
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);

}
?>