document.addEventListener('DOMContentLoaded', function () {
  mostrarFactura();
  const confirmarPagoBtn = document.getElementById('confirmar-pago');

  // Función para manejar la confirmación del pago
  confirmarPagoBtn.addEventListener('click', function() {
      const metodoPago = document.getElementById('select-pago').value;
      mostrarMensajeExito(metodoPago);
  });
});

// Mostrar factura
function mostrarFactura() {
  let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
  let facturaDiv = document.getElementById('factura');

  if (carrito.length === 0) {
      facturaDiv.innerHTML = '<p>No hay productos en el carrito.</p>';
  } else {
      let totalFactura = 0;
      let facturaHTML = '<table><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>';
      carrito.forEach(item => {
          let subtotal = (item.precio * item.cantidad).toFixed(2);
          totalFactura += parseFloat(subtotal);
          facturaHTML += `
              <tr>
                  <td>${item.nombre}</td>
                  <td>${item.cantidad}</td>
                  <td>$${item.precio.toFixed(2)}</td>
                  <td>$${subtotal}</td>
              </tr>
          `;
      });
      facturaHTML += `
          <tr>
              <td colspan="3"><strong>Total:</strong></td>
              <td><strong>$${totalFactura.toFixed(2)}</strong></td>
          </tr>
      `;
      facturaHTML += '</table>';
      facturaDiv.innerHTML = facturaHTML;
  }
}

// Mostrar mensaje de éxito
function mostrarMensajeExito(metodoPago) {
  const mensajeExitoDiv = document.getElementById('mensaje-exito');
  mensajeExitoDiv.style.display = 'block';

  // Opcional: Puedes hacer algo con el método de pago seleccionado
  console.log(`Pago realizado con: ${metodoPago}`);

  // Limpiar carrito
  localStorage.removeItem('carrito');
}
