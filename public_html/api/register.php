<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name   = $conn->real_escape_string($data['name']);
$email  = $conn->real_escape_string($data['email']);
$mobile = $conn->real_escape_string($data['mobile']);

$sql = "INSERT INTO users (name, email, mobile) VALUES ('$name', '$email', '$mobile')";

if ($conn->query($sql)) {
    $newId = $conn->insert_id;
    echo json_encode([
        "message" => "User registered",
        "user" => [
            "id"   => $newId,
            "name" => $name
        ]
    ]);
} else {
    echo json_encode(["error" => "Email or mobile number already exists."]);
}
?>
