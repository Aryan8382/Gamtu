<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$mobile = $conn->real_escape_string($data['mobile']);

$result = $conn->query("SELECT * FROM users WHERE mobile='$mobile'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "user" => [
            "id"   => $user['id'],
            "name" => $user['name']
        ]
    ]);
} else {
    echo json_encode(["error" => "Invalid name or mobile number."]);
}
?>
