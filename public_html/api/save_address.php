<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if($_SERVER['REQUEST_METHOD']=="OPTIONS"){
    exit();
}

include "db.php";

$data=json_decode(file_get_contents("php://input"),true);

$user_id    = intval($data['user_id']);
$label      = $conn->real_escape_string($data['label']);
$house_no   = $conn->real_escape_string($data['house_no']);
$floor      = $conn->real_escape_string($data['floor']);
$street     = $conn->real_escape_string($data['street']);
$landmark   = $conn->real_escape_string($data['landmark']);
$city       = $conn->real_escape_string($data['city']);
$state      = $conn->real_escape_string($data['state']);
$pincode    = $conn->real_escape_string($data['pincode']);
$latitude   = $conn->real_escape_string($data['latitude']);
$longitude  = $conn->real_escape_string($data['longitude']);

if($user_id==0){
    echo json_encode([
        "success"=>false,
        "message"=>"Invalid User"
    ]);
    exit;
}

/*
Remove previous default address
*/

$conn->query("
UPDATE user_addresses
SET is_default=0
WHERE user_id='$user_id'
");

/*
Insert new address
*/

$sql="INSERT INTO user_addresses
(
user_id,
label,
house_no,
floor,
street,
landmark,
city,
state,
pincode,
latitude,
longitude,
is_default
)

VALUES
(
'$user_id',
'$label',
'$house_no',
'$floor',
'$street',
'$landmark',
'$city',
'$state',
'$pincode',
'$latitude',
'$longitude',
1
)";

if($conn->query($sql)){

    echo json_encode([
        "success"=>true,
        "message"=>"Address Saved Successfully"
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>$conn->error
    ]);

}

?>