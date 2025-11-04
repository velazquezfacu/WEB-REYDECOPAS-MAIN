var urlParams = new URLSearchParams(window.location.search);
var valor = urlParams.get('valor');

document.addEventListener('DOMContentLoaded', function() {
  const nombreReceta = document.querySelector('.receta-title');
  const ingredientesReceta = document.querySelector('.lista-ingredientes');
  const preparacionReceta = document.querySelector('.lista-pasos');
  let recetas;
  let indiceRecetaActual = valor;

  // Cargar el archivo JSON
  fetch('/json/recetas.json')
    .then(response => response.json())
    .then(data => {
      recetas = data;
      mostrarReceta(indiceRecetaActual);
    });

  // Función para mostrar una receta
  function mostrarReceta(valor) {
      const receta = recetas[valor];
      nombreReceta.textContent = receta.nombre;
      ingredientesReceta.innerHTML = receta.ingredientes.map(ingredientes => `<li>${ingredientes}</li>`).join('');
      preparacionReceta.innerHTML = receta.preparacion.map(preparaciones => `<li>${preparaciones}</li>`).join('');
    }

  /*==================== Scripts de Ventana Modal ====================*/

  const cardsModal = document.querySelectorAll('.card');
  const closeModal = document.querySelector('.modal-close');
  const modal = this.documentElement.querySelector('.modal');
  const imgRecetas = document.querySelectorAll('.img-receta');
  const imgModal = document.querySelector('.modal-img')

  cardsModal.forEach(function(element, index) {
    element.addEventListener('click', (e) => {
      e.preventDefault();
      modal.classList.add('modal-show');
      mostrarReceta(index);
    });
  });

  closeModal.addEventListener('click', (e) => {
    e.preventDefault();
    modal.classList.remove('modal-show');
  });

  cardsModal.forEach(function(openModal, index) {
    openModal.addEventListener('click', function() {
        imgModal.src = imgRecetas[index].getAttribute('src');
    });
  });
});

// Script para redirigir la pagina pasandole un valor mediante URL

var rutaImagen = '/images/recipe' + valor + '.jpg';
function redirigir(valor) {
    window.location.href = "recipe.html?valor=" + encodeURIComponent(valor);
}
document.getElementById('img-receta').src = rutaImagen;




