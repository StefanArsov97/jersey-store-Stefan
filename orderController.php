<?php
// orderController.php - Controller for managing orders

include 'config/db_connect.php';
session_start();

// Create a new order
function createOrder($conn, $customerName, $customerEmail) {
    $totalPrice = calculateTotalPrice($conn);

    // Insert into orders table
    $query = "INSERT INTO orders (customer_name, customer_email, total_price) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssd', $customerName, $customerEmail, $totalPrice);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error creating order: " . mysqli_error($conn));
    }

    $orderId = mysqli_insert_id($conn);

    // Insert into order_items table
    $cartItems = getCartItems($conn);

    foreach ($cartItems as $item) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiid', $orderId, $item['id'], $item['quantity'], $item['price']);

        if (!mysqli_stmt_execute($stmt)) {
            die("Error adding order items: " . mysqli_error($conn));
        }
    }

    // Clear cart after order
    clearCart();

    return $orderId;
}

// Fetch all orders
function fetchOrders($conn) {
    $query = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error fetching orders: " . mysqli_error($conn));
    }

    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    return $orders;
}

// Fetch details of a single order
function fetchOrderDetails($conn, $orderId) {
    $query = "SELECT o.id, o.customer_name, o.customer_email, o.total_price, o.order_date, 
                     oi.product_id, oi.quantity, oi.price AS item_price, p.name AS product_name, p.image 
              FROM orders o
              JOIN order_items oi ON o.id = oi.order_id
              JOIN products p ON oi.product_id = p.id
              WHERE o.id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error fetching order details: " . mysqli_error($conn));
    }

    $orderDetails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orderDetails[] = $row;
    }
    return $orderDetails;
}

?>