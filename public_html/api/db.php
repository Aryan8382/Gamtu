<?php
$host = "localhost";
$user = "u709643491_gamtu";
$pass = "Gamtu@root1";
$db   = "u709643491_gamtu_ecommerc";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>