<?php
// cartController.php - Controller for managing the shopping cart

session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
function addToCart($productId, $quantity) {
    // Check if product already exists in the cart
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Remove product from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Update product quantity in cart
function updateCart($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$productId] = $quantity;
        } else {
            removeFromCart($productId);
        }
    }
}

// Clear the entire cart
function clearCart() {
    $_SESSION['cart'] = [];
}

// Get all items in the cart
function getCartItems($conn) {
    $cartItems = [];

    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $query = "SELECT id, name, price, image FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($product = mysqli_fetch_assoc($result)) {
            $product['quantity'] = $quantity;
            $product['total_price'] = $product['price'] * $quantity;
            $cartItems[] = $product;
        }
    }

    return $cartItems;
}

// Calculate total cart price
function calculateTotalPrice($conn) {
    $cartItems = getCartItems($conn);
    $totalPrice = 0;

    foreach ($cartItems as $item) {
        $totalPrice += $item['total_price'];
    }

    return $totalPrice;
}

?>