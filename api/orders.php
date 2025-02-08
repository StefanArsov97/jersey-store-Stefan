<?php
// api/orders.php - Endpoint to manage orders

include '../config/db_connect.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $customerName = $data['customerName'];
    $customerEmail = $data['customerEmail'];

    $query = "INSERT INTO orders (customer_name, customer_email, order_date) VALUES ('$customerName', '$customerEmail', NOW())";

    if (!mysqli_query($conn, $query)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create order']);
        exit;
    }

    $orderId = mysqli_insert_id($conn);

    $cartQuery = "SELECT product_id, quantity FROM cart";
    $cartResult = mysqli_query($conn, $cartQuery);

    while ($cartItem = mysqli_fetch_assoc($cartResult)) {
        $productId = $cartItem['product_id'];
        $quantity = $cartItem['quantity'];

        $orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$orderId', '$productId', '$quantity')";
        mysqli_query($conn, $orderItemQuery);
    }

    mysqli_query($conn, "DELETE FROM cart");

    echo json_encode(['message' => 'Order placed successfully', 'orderId' => $orderId]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>