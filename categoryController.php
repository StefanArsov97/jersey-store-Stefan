<?php
// categoryController.php - Controller for managing categories

include 'config/db_connect.php';

// Fetch all categories
function fetchCategories($conn) {
    $query = "SELECT * FROM categories";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error fetching categories: " . mysqli_error($conn));
    }

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

// Fetch single category by ID
function fetchCategoryById($conn, $id) {
    $query = "SELECT * FROM categories WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error fetching category: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result);
}

// Add a new category
function addCategory($conn, $name) {
    $query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $name);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error adding category: " . mysqli_error($conn));
    }
}

// Update a category
function updateCategory($conn, $id, $name) {
    $query = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $name, $id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating category: " . mysqli_error($conn));
    }
}

// Delete a category
function deleteCategory($conn, $id) {
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error deleting category: " . mysqli_error($conn));
    }
}

?>