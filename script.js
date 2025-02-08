// script.js - JavaScript functionality for Football Jersey Store

document.addEventListener("DOMContentLoaded", () => {
    fetchProducts();
    setupCart();
});

// Fetch products from backend
async function fetchProducts() {
    const productList = document.getElementById('product-list');
    try {
        const response = await fetch('http://localhost/proekt_rsa/api/products.php');
        const products = await response.json();

        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'card';
            productCard.style.width = '18rem'; // Дава ширина на картичката

            productCard.innerHTML = `
                <img src="${product.image}" class="card-img-top" alt="${product.name}">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">Price: $${parseFloat(product.price).toFixed(2)}</p>
                    <button class="btn btn-primary" onclick="addToCart(${product.id}, 1)">Add to Cart</button>
                </div>
            `;
            productList.appendChild(productCard);
        });
    } catch (error) {
        console.error('Error fetching products:', error);
        productList.innerHTML = '<p>Error loading products.</p>';
    }
}


// Setup cart functionality
function setupCart() {
    document.getElementById('checkout-button').addEventListener('click', async () => {
        const customerName = prompt('Enter your name:');
        const customerEmail = prompt('Enter your email:');

        if (customerName && customerEmail) {
            try {
                const response = await fetch('http://localhost/proekt_rsa/api/orders', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ customerName, customerEmail })
                });
                const order = await response.json();
                alert(Order #${order.id} placed successfully!);
                loadCart(); // Clear cart after order
            } catch (error) {
                console.error('Error placing order:', error);
                alert('Error placing order. Please try again.');
            }
        }
    });
}

// Add product to cart
function addToCart(productId, quantity) {
    fetch('http://localhost/proekt_rsa/api/cart', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId, quantity })
    })
        .then(() => loadCart())
        .catch(error => console.error('Error adding to cart:', error));
}
//login
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch('http://localhost/proekt_rsa/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        const result = await response.json();
        alert(result.message);
    } catch (error) {
        console.error('Login error:', error);
    }
});

// Load cart details
async function loadCart() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    try {
        const response = await fetch('http://localhost/proekt_rsa/api/cart');
        const items = await response.json();

        cartItems.innerHTML = '';
        let total = 0;
        items.forEach(item => {
            total += item.total_price;
            const cartItem = document.createElement('div');
            cartItem.innerHTML = `
                <p>${item.name} (x${item.quantity}) - $${item.total_price.toFixed(2)}</p>
                <button onclick="removeFromCart(${item.id})">Remove</button>
            `;
            cartItems.appendChild(cartItem);
        });
        cartTotal.textContent = total.toFixed(2);
    } catch (error) {
        console.error('Error loading cart:', error);
        cartItems.innerHTML = '<p>Error loading cart.</p>';
    }
}

// Remove product from cart
function removeFromCart(productId) {
    fetch(http://localhost/proekt_rsa/api/cart/${productId}, { method: 'DELETE' })
        .then(() => loadCart())
        .catch(error => console.error('Error removing from cart:', error));
}