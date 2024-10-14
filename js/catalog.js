document.addEventListener('DOMContentLoaded', function () {
    cargarProductos();
    cargarCarrito(); // Cargar carrito al iniciar
    actualizarContadorCarrito();
});

let carrito = [];

// Cargar productos desde el servidor
function cargarProductos() {
    fetch('../php/get_products.php')
        .then(response => response.json())
        .then(data => {
            let productosDiv = document.getElementById('productos');
            productosDiv.innerHTML = '';
            data.forEach(producto => {
                let productoHTML = `
                    <div class='producto'>
                        <img src='${producto.imagen}' alt='${producto.nombre}'>
                        <h3>${producto.nombre}</h3>
                        <p>${producto.descripcion}</p>
                        <p class='precio'>Precio: $${producto.precio}</p>
                        <form onsubmit="añadirAlCarrito(event, ${producto.id}, '${producto.nombre}', ${producto.precio}, '${producto.imagen}')">
                            <input type='number' id='cantidad_${producto.id}' value='1' min='1' max='${producto.stock}'>
                            <input type='submit' value='Añadir al carrito'>
                        </form>
                        <div id="error_${producto.id}" class="error-msg"></div>
                    </div>
                `;
                productosDiv.innerHTML += productoHTML;
            });
        })
        .catch(error => console.error('Error al cargar los productos:', error));
}

// Añadir producto al carrito
function añadirAlCarrito(event, id, nombre, precio, imagen) {
    event.preventDefault();
    let cantidad = parseInt(document.getElementById(`cantidad_${id}`).value);
    
    // Validar cantidad
    if (cantidad < 1) {
        document.getElementById(`error_${id}`).innerText = 'La cantidad debe ser mayor o igual a 1.';
        return;
    }

    let productoExistente = carrito.find(item => item.id === id);
    
    if (productoExistente) {
        productoExistente.cantidad += cantidad;
    } else {
        carrito.push({ id, nombre, precio, imagen, cantidad });
    }

    document.getElementById(`error_${id}`).innerText = '';
    guardarCarrito();
    actualizarContadorCarrito();
}

// Guardar carrito en localStorage
function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

// Cargar carrito desde localStorage
function cargarCarrito() {
    let carritoGuardado = localStorage.getItem('carrito');
    if (carritoGuardado) {
        carrito = JSON.parse(carritoGuardado);
    }
}

// Actualizar contador del carrito
function actualizarContadorCarrito() {
    let totalCantidad = carrito.reduce((sum, item) => sum + item.cantidad, 0);
    document.getElementById('cart-count').innerText = totalCantidad;
}

// Mostrar carrito en el modal
function mostrarCarrito() {
    let carritoDiv = document.getElementById('carrito');
    carritoDiv.innerHTML = '';

    if (carrito.length === 0) {
        carritoDiv.innerHTML = '<p>El carrito está vacío</p>';
    } else {
        let totalCarrito = 0;
        carrito.forEach(item => {
            let subtotal = (item.precio * item.cantidad).toFixed(2);
            totalCarrito += parseFloat(subtotal);
            carritoDiv.innerHTML += `
                <div class='cart-item'>
                    <img src='${item.imagen}' alt='${item.nombre}' width='50'>
                    <div>
                        <h3>${item.nombre}</h3>
                        <h4>Cantidad: <input type="number" value="${item.cantidad}" min="1" onchange="actualizarCantidad(${item.id}, this.value)"></h4>
                        <p>Subtotal: $${subtotal}</p>
                    </div>
                    <button onclick="eliminarDelCarrito(${item.id})"><i class="fas fa-trash"></i></button>
                </div>
            `;
        });
        carritoDiv.innerHTML += `<div class="cart-total"><strong>Total: $${totalCarrito.toFixed(2)}</strong></div>`;
        carritoDiv.innerHTML += `
            <button id="checkout-btn" onclick="irACheckout()">Proceder al pago</button>
        `;
    }
}

// Funcion para redirigir el carrito al cheokout.html
function irACheckout() {
    window.location.href = 'checkout.html';
}

// Actualizar cantidad de un producto en el carrito
function actualizarCantidad(id, nuevaCantidad) {
    let producto = carrito.find(item => item.id === id);
    nuevaCantidad = parseInt(nuevaCantidad);
    
    // Validar cantidad
    if (nuevaCantidad < 1) {
        alert('La cantidad debe ser mayor o igual a 1.');
        return;
    }
    
    producto.cantidad = nuevaCantidad;
    guardarCarrito();
    mostrarCarrito();
    actualizarContadorCarrito();
}

// Eliminar producto del carrito
function eliminarDelCarrito(id) {
    carrito = carrito.filter(item => item.id !== id);
    guardarCarrito();
    mostrarCarrito();
    actualizarContadorCarrito();
}

// Manejo del modal
const cartIcon = document.getElementById('cart-icon');
const cartModal = document.getElementById('cart-modal');
const closeCart = document.getElementById('close-cart');

cartIcon.addEventListener('click', () => {
    cartModal.classList.toggle('show');
    mostrarCarrito();
});

// Cerrar el modal
closeCart.addEventListener('click', () => {
    cartModal.classList.remove('show');
});

// Cerrar el modal si se hace clic fuera del contenido interno del modal
window.addEventListener('click', function(event) {  
    if (cartModal.classList.contains('show') && !cartModal.contains(event.target) && !cartIcon.contains(event.target)) {
        cartModal.classList.remove('show');
    }
});
