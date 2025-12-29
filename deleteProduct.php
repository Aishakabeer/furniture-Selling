<?php
header("Content-Type: application/json");

$servername = "127.0.0.1:3308";
$username = "root"; // Replace with your DB username
$password = "root";     // Replace with your DB password
$dbname = "furniture_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Retrieve ID
$data = json_decode(file_get_contents("php://input"), true);
$prodId = $data['prodId'];

// Delete query
$sql = "DELETE FROM product WHERE prodId = $prodId";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
