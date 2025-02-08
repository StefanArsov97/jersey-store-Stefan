<?php
// productController.php - Controller for managing products

include 'config/db_connect.php';

// Fetch all products
function fetchProducts($conn) {
    $query = "SELECT products.id, products.name, products.description, products.price, products.image, categories.name AS category_name 
              FROM products 
              JOIN categories ON products.category_id = categories.id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error fetching products: " . mysqli_error($conn));
    }

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    return $products;
}

// Fetch single product by ID
function fetchProductById($conn, $id) {
    $query = "SELECT products.id, products.name, products.description, products.price, products.image, categories.name AS category_name 
              FROM products 
              JOIN categories ON products.category_id = categories.id 
              WHERE products.id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error fetching product: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result);
}

// Add a new product
function addProduct($conn, $name, $description, $price, $image, $category_id) {
    $query = "INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssdsi', $name, $description, $price, $image, $category_id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error adding product: " . mysqli_error($conn));
    }
}

// Update a product
function updateProduct($conn, $id, $name, $description, $price, $image, $category_id) {
    $query = "UPDATE products SET name = ?, description = ?, price = ?, image = ?, category_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssdsi', $name, $description, $price, $image, $category_id, $id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating product: " . mysqli_error($conn));
    }
}

// Delete a product
function deleteProduct($conn, $id) {
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error deleting product: " . mysqli_error($conn));
    }
}

?>