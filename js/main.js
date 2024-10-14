// Detectar el scroll y agregar/quitar la clase para el estilo del header
  document.addEventListener('scroll', function () {
      const header = document.getElementById('header');
      if (window.scrollY > 50) {
          header.classList.add('scrolled');
      } else {
          header.classList.remove('scrolled');
      }
  });

// Inicializa VanillaTilt para los elementos de la lista
VanillaTilt.init(document.querySelectorAll(".sci li a"), {
  max: 30,
  speed: 400,
  glare: true
});

// Cambia el color del fondo del body basado en la hora del día
document.addEventListener('DOMContentLoaded', function() {
  const body = document.body;
  const hour = new Date().getHours();
  let defaultBackgroundColor;

  // Función para aplicar el tema basado en la hora
  function applyTimeTheme() {
    if (hour >= 6 && hour < 18) {
      body.classList.add('day-theme');
      body.classList.remove('night-theme');
      defaultBackgroundColor = getComputedStyle(body).backgroundColor; // Guarda el color de fondo del tema diurno
    } else {
      body.classList.add('night-theme');
      body.classList.remove('day-theme');
      defaultBackgroundColor = getComputedStyle(body).backgroundColor; // Guarda el color de fondo del tema nocturno
    }
  }

  // Aplica el tema inicial
  applyTimeTheme();

  // Lógica para cambiar el color del fondo al pasar el mouse
  let list = document.querySelectorAll('.sci li');
  
  list.forEach(element => {
    element.addEventListener('mouseenter', function(event) {
      let color = event.target.style.getPropertyValue('--clr');
      body.style.backgroundColor = color; // Cambia temporalmente el color de fondo
    });
    
    element.addEventListener('mouseleave', function(event) {
      // Restaura el color de fondo predeterminado según el tema
      body.style.backgroundColor = defaultBackgroundColor;
    });
  });
});
