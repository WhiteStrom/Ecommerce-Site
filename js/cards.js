document.addEventListener('DOMContentLoaded', function() {
    cargarSlides();
});

function cargarSlides() {
    fetch('./php/cards.php')  // Ajusta la ruta según sea necesario
        .then(response => response.json())
        .then(data => {
            let cardsContainer = document.querySelector('.cards');
            cardsContainer.innerHTML = ''; // Limpiar el contenido anterior

            data.forEach(producto => {
                let cardHTML = `
                    <div class="card">
                        <div class="card-background" style="background-image: url('${producto.imagen}');"></div>                
                        <div class="card-content">
                            <h3 class="nombre">${producto.nombre}</h3>
                            <h4 class="precio">${producto.precio}$</h4>
                            <p class="descripcion">${producto.descripcion}</p>
                        </div>
                    </div>
                `;
                cardsContainer.innerHTML += cardHTML;
            });
        })
        .catch(error => console.error('Error al cargar los productos del slider:', error));
}
