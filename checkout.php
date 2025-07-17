<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Compra - MH HATS</title>
    <style>
        body {
            background-image: url(https://www.wallpapertip.com/wmimgs/51-513883_new-era-caps.jpg);
            background-color: #000;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: 20px;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.6);
            animation: fadeIn 1s ease;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 20px;
            border: 1px solid #ff6700;
            text-align: center;
            font-size: 18px;
        }
        th {
            background-color: #ff6700;
            color: #000;
            font-size: 20px;
        }
        td img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }
        td img:hover {
            transform: scale(1.1);
        }
        td input[type="number"] {
            width: 70px;
            padding: 8px;
            font-size: 16px;
            border: 2px solid #ff6700;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            text-align: center;
            outline: none;
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }
        td input[type="number"]:hover {
            box-shadow: 0 0 10px rgba(255, 103, 0, 0.8);
            transform: scale(1.1);
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .remove-btn, .action-btn {
            background-color: #ff6700;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .remove-btn:hover, .action-btn:hover {
            background-color: #cc5500;
            transform: scale(1.1);
        }
        .disabled {
            background-color: #777;
            cursor: not-allowed;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Resumen de Compra</h1>
    <table id="cart-table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>ID</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Filas del carrito se insertarán aquí -->
        </tbody>
    </table>
    <h2>Total: $<span id="cart-total">0.00</span></h2>

    <div class="button-container">
        <button class="action-btn" id="pay-button" onclick="handlePayment()">Pagar</button>
        <button class="action-btn disabled" id="address-button" onclick="goToAddress()" disabled>Domicilio</button>
        <button class="action-btn" id="cancel-button" onclick="cancelOrder()">Cancelar</button>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const tbody = document.getElementById('cart-table').getElementsByTagName('tbody')[0];

        if (localStorage.getItem('paymentCompleted') === 'true') {
            document.getElementById('address-button').disabled = false;
            document.getElementById('address-button').classList.remove('disabled');
        }

        function updateTotal() {
            let total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
            });
            document.getElementById('cart-total').innerText = total.toFixed(2);
            localStorage.setItem('cart-total', total.toFixed(2));
        }

        function renderCart() {
            tbody.innerHTML = '';

            cart.forEach((item, index) => {
                const row = document.createElement('tr');
                const itemTotal = item.price * item.quantity;

                row.innerHTML = `
                    <td><img src="${item.image}" alt="${item.title}"></td>
                    <td>${item.title}</td>
                    <td>${item.id}</td>
                    <td>
                        <input type="number" value="${item.quantity}" min="1" onchange="changeQuantity(${index}, this.value)">
                    </td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td>$<span id="total-${index}">${itemTotal.toFixed(2)}</span></td>
                    <td>
                        <button class="remove-btn" onclick="removeItem(${index})">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            updateTotal();
        }

        function changeQuantity(index, quantity) {
            quantity = parseInt(quantity);
            if (quantity < 1) {
                quantity = 1;
            }
            cart[index].quantity = quantity;

            const itemTotal = cart[index].price * quantity;
            document.getElementById(`total-${index}`).innerText = itemTotal.toFixed(2);

            localStorage.setItem('cart', JSON.stringify(cart));
            updateTotal();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        function handlePayment() {
            localStorage.setItem('paymentCompleted', 'true');
            window.location.href = 'tarjeta.html';
        }

        function goToAddress() {
            if (localStorage.getItem('paymentCompleted') === 'true') {
                window.location.href = 'direccion.php';
            }
        }

        function cancelOrder() {
            if (confirm("¿Estás seguro de que deseas cancelar el pedido?")) {
                localStorage.removeItem('cart');
                localStorage.removeItem('cart-total');
                localStorage.removeItem('paymentCompleted');
                window.location.href = 'producto.php';
            }
        }

        renderCart();
    </script>
</body>
</html>
