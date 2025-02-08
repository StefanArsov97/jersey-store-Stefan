// JavaScript for handling the frontend interactions

document.addEventListener('DOMContentLoaded', () => {
    // Fetch and display products
    fetchProducts();

    // Event listener for adding items to cart
    document.getElementById('products-container').addEventListener('click', (event) => {
        if (event.target.classList.contains('add-to-cart')) {
            const productId = event.target.dataset.id;
            const quantity = 1; // Default quantity for now
            addToCart(productId, quantity);
        }
    });

    // Event listener for placing order
    document.getElementById('place-order').addEventListener('click', () => {
        const customerName = document.getElementById('customer-name').value;
        const customerEmail = document.getElementById('customer-email').value;
        placeOrder(customerName, customerEmail);
    });
});

function fetchProducts() {
    fetch('api/products.php')
        .then(response => response.json())
        .then(products => {
            const container = document.getElementById('products-container');
            container.innerHTML = '';
            products.forEach(product => {
                container.innerHTML += `
                    <div class="product">
                        <img src="${product.image}" alt="${product.name}" />
                        <h3>${product.name}</h3>
                        <p>Price: $${product.price}</p>
                        <button class="add-to-cart" data-id="${product.id}">Add to Cart</button>
                    </div>
                `;
            });
        })
        .catch(error => console.error('Error fetching products:', error));
}

function addToCart(productId, quantity) {
    fetch('api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productId, quantity }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                fetchCart();
            } else {
                alert('Error adding item to cart');
            }
        })
        .catch(error => console.error('Error adding to cart:', error));
}

function fetchCart() {
    fetch('api/cart.php')
        .then(response => response.json())
        .then(cartItems => {
            const cartContainer = document.getElementById('cart-container');
            cartContainer.innerHTML = '';
            let total = 0;

            cartItems.forEach(item => {
                total += parseFloat(item.total_price);
                cartContainer.innerHTML += `
                    <div class="cart-item">
                        <p>${item.name} x ${item.quantity}</p>
                        <p>Total: $${item.total_price}</p>
                        <button class="remove-from-cart" data-id="${item.id}">Remove</button>
                    </div>
                `;
            });

            document.getElementById('cart-total').innerText = Total: $${total.toFixed(2)};
        })
        .catch(error => console.error('Error fetching cart:', error));
}

function placeOrder(customerName, customerEmail) {
    fetch('api/orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ customerName, customerEmail }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                fetchCart();
            } else {
                alert('Error placing order');
            }
        })
        .catch(error => console.error('Error placing order:', error));
}