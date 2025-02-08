<?php
include('../db_connect.php'); // Патека до поврзувањето со базата

header("Content-Type: application/json");

$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>