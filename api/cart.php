<?php
// api/cart.php - Endpoint to manage cart

include '../config/db_connect.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['productId'];
    $quantity = $data['quantity'];

    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$productId', '$quantity') ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

    if (mysqli_query($conn, $query)) {
        http_response_code(200);
        echo json_encode(['message' => 'Item added to cart']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add item to cart']);
    }
} elseif ($method === 'GET') {
    $query = "SELECT c.id, p.name, c.quantity, (p.price * c.quantity) AS total_price FROM cart c JOIN products p ON c.product_id = p.id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch cart']);
        exit;
    }

    $cart = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cart[] = $row;
    }

    echo json_encode($cart);
} elseif ($method === 'DELETE') {
    parse_str(file_get_contents('php://input'), $data);
    $productId = $data['id'];

    $query = "DELETE FROM cart WHERE product_id = '$productId'";

    if (mysqli_query($conn, $query)) {
        http_response_code(200);
        echo json_encode(['message' => 'Item removed from cart']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to remove item from cart']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}?>