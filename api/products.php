<?php
session_start();

// Вклучување на датотеката за поврзување со базата
include 'config/db_connect.php';

// Проверка дали е поставен ID на продуктот
if (!isset($_GET['id'])) {
    die("Невалиден производ.");
}

$product_id = intval($_GET['id']);

// Извлекување на податоците за продуктот
$query = "SELECT id, name, price, description, image FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);

// Проверка дали продуктот постои
if (!$result || mysqli_num_rows($result) == 0) {
    die("Производот не е пронајден.");
}

$product = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .product-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .product-details img {
            max-width: 300px;
            border-radius: 10px;
        }
        .product-details h2 {
            margin: 20px 0 10px;
        }
        .product-details p {
            margin: 10px 0;
        }
        .back-link {
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <h1>Product Details</h1>
</header>
<main>
    <div class="product-details">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h2><?php echo $product['name']; ?></h2>
        <p><?php echo $product['description']; ?></p>
        <h3>Price: $<?php echo $product['price']; ?></h3>
        <a href="cartController.php?action=add&id=<?php echo $product['id']; ?>&quantity=1" class="btn add-to-cart">Add to Cart</a>
        <a href="index.php" class="back-link">Back to Products</a>
    </div>
</main>
<footer>
    <p>&copy; 2025 Football Jersey Store. All Rights Reserved.</p>
</footer>
</body>
</html>
