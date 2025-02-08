<?php
session_start();

// Вклучување на датотеката за поврзување со базата
include 'config/db_connect.php';

// Проверка дали корисникот е најавен
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Извлекување на производите од базата
$query = "SELECT id, name, price, image FROM products";
$result = mysqli_query($conn, $query);

// Проверка дали има производи
if (!$result || mysqli_num_rows($result) == 0) {
    die("Нема производи за прикажување или грешка во базата: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
        }
        .product img {
            width: 100%;
            height: auto;
        }
        .product h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .product p {
            color: #555;
        }
        .product a, .product button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        .product a:hover, .product button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <h1>Football Jersey Store</h1>
</header>
<nav>
    <ul>
        <li><a href="frontendindex.html">Home</a></li>
        <li><a href="index.php">Products</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
<main>
<h2>Available Jerseys</h2>
    <div class="products">
        <?php while ($product = mysqli_fetch_assoc($result)) { ?>
            <div class="product">
                <div class="carousel" id="carousel-<?php echo $product['id']; ?>">
                    <?php
                    $images = explode(',', $product['image']); // Претпоставка: Сликите се чуваат како CSV во полето image
                    foreach ($images as $index => $image) {
                        $activeClass = $index === 0 ? 'active' : '';
                        echo "<img src='$image' class='$activeClass' alt='{$product['name']}'>";
                    }
                    ?>
                    <button class="prev" onclick="changeSlide(<?php echo $product['id']; ?>, -1)">&lt;</button>
                    <button class="next" onclick="changeSlide(<?php echo $product['id']; ?>, 1)">&gt;</button>
                </div>
                <h3><?php echo $product['name']; ?></h3>
                <p>Price: $<?php echo $product['price']; ?></p>
                <button class="add-to-cart" 
                        data-id="<?php echo $product['id']; ?>" 
                        data-name="<?php echo $product['name']; ?>" 
                        data-price="<?php echo $product['price']; ?>">
                    Add to Cart
                </button>
            </div>
        <?php } ?>
    </div>
    
    <!-- Cart Section -->
    <section id="cart">
        <h2>Your Cart</h2>
        <div id="cart-items">
            <p>No items in cart yet.</p>
        </div>
        <p>Total Price: $<span id="cart-total">0.00</span></p>
        <button id="checkout-button">Checkout</button>
        <p id="checkout-message" style="color: green; font-weight: bold; display: none;">NAPLATENO!</p>
    </section>
</main>
<footer>
    <p>&copy; 2025 Football Jersey Store. All Rights Reserved.</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let cart = [];
        let totalPrice = 0;

        const cartItems = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');
        const checkoutButton = document.getElementById('checkout-button');
        const checkoutMessage = document.getElementById('checkout-message');

        const buttons = document.querySelectorAll('.add-to-cart');
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');
                const productPrice = parseFloat(button.getAttribute('data-price'));

                // Add product to cart
                cart.push({ id: productId, name: productName, price: productPrice });
                totalPrice += productPrice;

                // Update cart UI
                updateCartUI();
            });
        });

        checkoutButton.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            // Reset cart and total price
            cart = [];
            totalPrice = 0;

            // Update cart UI
            updateCartUI();

            // Display checkout message
            checkoutMessage.style.display = 'block';

            // Hide the message after 3 seconds
            setTimeout(() => {
                checkoutMessage.style.display = 'none';
            }, 3000);
        });

        function updateCartUI() {
            // Clear the cart items list
            cartItems.innerHTML = '';

            // Add each item in the cart to the UI
            if (cart.length > 0) {
                cart.forEach((item, index) => {
                    const itemElement = document.createElement('p');
                    itemElement.textContent = `${item.name} - $${item.price.toFixed(2)}`;
                    cartItems.appendChild(itemElement);
                });
            } else {
                cartItems.innerHTML = '<p>No items in cart yet.</p>';
            }

            // Update total price
            cartTotal.textContent = totalPrice.toFixed(2);
        }
    });
</script>
</body>
</html>
