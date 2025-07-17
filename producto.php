<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - MH HATS</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;600&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #000000;
            background-image: url(https://www.wallpapertip.com/wmimgs/51-513883_new-era-caps.jpg);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            color: #ffffff;
            overflow-x: hidden;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: linear-gradient(90deg, #000000, #6d30084f);
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #ffffff;
            padding: 10px 20px;
            border: 2px solid transparent;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            border-color: #ff6700;
            transform: scale(1.1);
        }

        .cart-toggle {
            background-color: #ff6700;
            border: none;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 50px;
            font-size: 18px;
            cursor: pointer;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cart-toggle:hover {
            transform: scale(1.2);
            box-shadow: 0 4px 15px rgba(255, 103, 0, 0.5);
        }

        .cart-toggle span {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #ffffff;
            color: #ff6700;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .search-container {
            text-align: center;
            margin: 20px;
        }

        .search-container input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 2px solid #ff6700;
            width: 300px;
            transition: width 0.3s ease;
        }

        .search-container input:focus {
            width: 350px;
            outline: none;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 40px;
            max-width: 1000px;
            margin: auto;
        }

        .product-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 15px rgba(255, 103, 0, 0.5);
        }

        .product-card img {
            width: 100%;
            border-radius: 10px;
        }

        .add-to-cart-btn {
            background-color: #ff6700;
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 18px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #d45900;
            transform: scale(1.1);
        }

        #cart-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 400px;
            height: 100%;
            background: linear-gradient(180deg, #2b2b2b, #1a1a1a);
            padding: 20px;
            color: #fff;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.6);
            overflow-y: auto;
            transition: right 0.5s ease;
            z-index: 1000;
        }

        #cart-menu.open {
            right: 0;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff6700;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .cart-item img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            margin-right: 10px;
        }

        .cart-item p {
            flex-grow: 1;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #ff6700;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #d45900;
            transform: translateY(-5px);
        }

        .pagination {
            text-align: center;
            margin: 20px;
        }

        .pagination button {
            background-color: #ff6700;
            border: none;
            color: #fff;
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .pagination button:hover {
            background-color: #d45900;
        }

        .pagination button.active {
            background-color: #d45900;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="nav-links">
                <a href="#" onclick="loadProducts()">Todos</a>
                <a href="#" onclick="loadCategory('mlb')">MLB</a>
                <a href="#" onclick="loadCategory('nba')">NBA</a>
                <a href="#" onclick="loadCategory('f1')">F1</a>
            </div>
            <button class="cart-toggle" onclick="toggleCart()">🛒 <span id="cart-count">0</span></button>
        </div>
    </header>
    <main>
        <div class="search-container">
            <input type="text" id="search" placeholder="Buscar producto..." onkeyup="searchProducts()">
        </div>
        <section class="product-grid" id="product-grid"></section>
        <div class="pagination" id="pagination"></div>
    </main>
    <div id="cart-menu">
        <div class="cart-header">
            <h2>Carrito</h2>
            <button onclick="toggleCart()">✖</button>
        </div>
        <div id="cart-items"></div>
        <p>Total: $<span id="cart-total">0.00</span></p>
        <button class="checkout-btn" onclick="goToCheckout()">Terminar Compra</button>
    </div>
    <script>
        let cart = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        function toggleCart() {
            const cartMenu = document.getElementById('cart-menu');
            cartMenu.classList.toggle('open');
        }

        function loadProducts(search = "", page = 1) {
    fetch(`fetch_products.php?search=${search}&page=${page}`)
        .then(res => res.json())
        .then(data => {
            const productGrid = document.getElementById("product-grid");
            const pagination = document.getElementById("pagination");

            // Renderizar productos
            productGrid.innerHTML = data.products.map(product => `
                <div class="product-card" onclick="viewProduct(${product.id})">
                    <img src="${product.image}" alt="${product.title}">
                    <h3>${product.title}</h3>
                    <p>$${product.price.toFixed(2)}</p>
                    <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(${product.id}, '${product.title}', ${product.price}, '${product.image}')">Agregar al Carrito</button>
                </div>
            `).join("");

            // Renderizar botones de paginación
            pagination.innerHTML = "";
            for (let i = 1; i <= data.total_pages; i++) {
                pagination.innerHTML += `
                    <button class="${i === data.current_page ? 'active' : ''}" onclick="loadProducts('${search}', ${i})">${i}</button>
                `;
            }
        })
        .catch(error => console.error("Error cargando productos:", error));
}

        function viewProduct(id) {
            window.location.href = `detalle_producto.php?id=${id}`;
        }

        function searchProducts() {
            const search = document.getElementById("search").value;
            loadProducts(search);
        }

        function addToCart(id, title, price, image) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ id, title, price, image, quantity: 1 });
            }
            updateCart();
        }

        function updateCart() {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            const cartCount = document.getElementById('cart-count');
            cartItems.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                total += item.price * item.quantity;
                cartItems.innerHTML += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.title}">
                        <p>${item.title} (x${item.quantity})</p>
                        <button onclick="removeFromCart(${item.id})">Eliminar</button>
                    </div>
                `;
            });

            cartTotal.textContent = total.toFixed(2);
            cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCart();
        }

        function goToCheckout() {
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.href = 'checkout.php';
        }

        document.addEventListener("DOMContentLoaded", () => {
            loadProducts();
        });
    </script>
</body>
</html>