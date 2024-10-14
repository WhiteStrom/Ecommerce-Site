document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();
});

// Leer productos (GET)
function cargarProductos() {
  fetch('../php/get_products.php')
      .then(response => response.json())
      .then(data => {
          let cardsContainer = document.querySelector('.cards');
          cardsContainer.innerHTML = '';

          if (data.length === 0) {
              // Si no hay productos, mostrar mensaje
              cardsContainer.innerHTML = '<p>No hay productos disponibles</p>';
              return;
          }

          data.forEach(producto => {
                let cardHTML = `
                    <div class="card">
                        <div class="card-background" style="background-image: url('${producto.imagen}');"></div>    
                        <div class="card-header">
				            <h3 class="nombre">${producto.nombre}</h3>
			            </div>            
                        <div class="card-content"> 
                            <h4 class="precio">${producto.precio}$</h4>
                            <p class="descripcion">${producto.descripcion}</p>
                        </div>
                        <div class="card-footer">
                            <button onclick="editarProducto(${producto.id}, '${producto.nombre}', ${producto.precio}, '${producto.descripcion}', '${producto.imagen}')">Editar</button>
                            <button onclick="EliminarProducto(${producto.id})" class="btn-danger">Eliminar</button>
			            </div>
                    </div>
                `;
                cardsContainer.innerHTML += cardHTML;
          });
      })
      .catch(error => console.error('Error al cargar los productos:', error));
}

// funcion para agregar Productos
function agregarProducto() {
  let producto = {
      nombre: document.getElementById('nombre').value,
      descripcion: document.getElementById('descripcion').value,
      precio: parseFloat(document.getElementById('precio').value),
      imagen: document.getElementById('imagen').value
  };

  fetch('../php/create_product.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify(producto),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert('Producto creado exitosamente');
          cargarProductos(); // Recargar productos después de crear uno nuevo
          document.getElementById('formProducto').reset(); // Limpiar el formulario
      } else {
          alert('Error al crear el producto: ' + data.error);
      }
  })
  .catch(error => console.error('Error al crear el producto:', error));
}

// funcion para Editar Productos
function editarProducto(id, nombre, precio, descripcion, imagen) {
    // Abrir el modal
    const modal = document.getElementById("editarProducto");
    modal.style.display = "block";

    // Asignar los valores actuales al formulario
    document.getElementById("productoId").value = id;
    document.getElementById("nombreProducto").value = nombre;
    document.getElementById("precioProducto").value = precio;
    document.getElementById("descripcionProducto").value = descripcion;
    document.getElementById("imagenProducto").value = imagen;
}

// Cerrar el modal cuando el usuario haga clic en el botón de cerrar
document.getElementById("cerrarModal").onclick = function() {
    document.getElementById("editarProducto").style.display = "none";
};

// Manejar la actualización del producto al enviar el formulario
document.getElementById("formEditarProducto").addEventListener("submit", function(event) {
    event.preventDefault();

    // Obtener los valores del formulario
    const id = document.getElementById("productoId").value;
    const nuevoNombre = document.getElementById("nombreProducto").value;
    const nuevoPrecio = parseFloat(document.getElementById("precioProducto").value);
    const nuevaDescripcion = document.getElementById("descripcionProducto").value;
    const nuevaImagen = document.getElementById("imagenProducto").value;

    // Validar que todos los campos estén completos
    if (!nuevoNombre || !nuevoPrecio || !nuevaDescripcion || !nuevaImagen || isNaN(nuevoPrecio)) {
        alert("Por favor, completa todos los campos correctamente.");
        return;
    }

    // Crear el objeto con los datos del producto
    const productoActualizado = {
        id: id,
        nombre: nuevoNombre,
        precio: nuevoPrecio,
        descripcion: nuevaDescripcion,
        imagen: nuevaImagen
    };

    // Realizar la petición para actualizar el producto
    fetch('../php/update_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ...productoActualizado,
            _method: 'PUT'
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Producto actualizado exitosamente');
            document.getElementById("editarProducto").style.display = "none"; // Cerrar modal
            cargarProductos(); // Recargar los productos
        } else {
            alert('Error al actualizar el producto: ' + (data.error || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error al actualizar el producto:', error);
        alert('Hubo un problema al actualizar el producto. Inténtalo de nuevo más tarde.');
    });
});

//Eliminar Productos.

let productoIdAEliminar = null; // Variable para almacenar el ID del producto a eliminar

// Función para abrir el modal de confirmación
function EliminarProducto(id) {
    productoIdAEliminar = id; // Guardar el ID del producto que se quiere eliminar
    const modal = document.getElementById("EliminarProducto");
    modal.style.display = "flex"; // Mostrar el modal
    console.log("Producto ID a eliminar:", id); // Asegúrate de que el ID se está pasando correctamente
}

// Cerrar el modal de confirmación
document.getElementById("cerrarModalEliminar").onclick = function() {
    document.getElementById("EliminarProducto").style.display = "none";
};

// Si se hace clic en el botón "Cancelar", cerrar el modal
document.getElementById("cancelarEliminar").onclick = function() {
    document.getElementById("EliminarProducto").style.display = "none";
};

// Si se confirma la eliminación
document.getElementById("confirmarEliminar").onclick = function() {
    eliminarProductoConfirmado(productoIdAEliminar); // Llamar a la función para eliminar el producto
};

// Función para eliminar el producto después de la confirmación
function eliminarProductoConfirmado(id) {
    if (!id) {
        console.error('No se proporcionó un ID de producto válido para eliminar.');
        return;
    }

    fetch('../php/delete_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: id,
            _method: 'DELETE'  // Simulación del método DELETE
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Producto eliminado exitosamente');
            document.getElementById("EliminarProducto").style.display = "none"; // Cerrar modal
            cargarProductos(); // Recargar productos después de eliminar uno
        } else {
            alert('Error al eliminar el producto: ' + data.error);
        }
    })
    .catch(error => console.error('Error al eliminar el producto:', error));
}

