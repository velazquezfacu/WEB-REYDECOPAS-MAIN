// window.onload = function() {
//   // Se ejecuta cuando se ha cargado todo el contenido de la página, incluidos los recursos externos (como imágenes)
//   const navbar = document.querySelector('.navbar');
//   const navbarHeight = elemento.offsetHeight;
// };

// Formulario de Contacto Focus

const inputs = document.querySelectorAll(".input");

function focusFunc() {
  let parent = this.parentNode;
  parent.classList.add("focus");
}

function blurFunc() {
  let parent = this.parentNode;
  if(this.value == ""){
    parent.classList.remove("focus");
  }
}

inputs.forEach((input) => {
  input.addEventListener("focus", focusFunc);
  input.addEventListener("blur", blurFunc);
});

// Go Top Button Show/Hide

window.onscroll = function(){
  var windowSize = window.innerHeight;
  console.log(windowSize);
  if(document.documentElement.scrollTop > (windowSize - 100)){
    document.querySelector('.go-top-container')
    .classList.add('show');
  }else{
    document.querySelector('.go-top-container')
    .classList.remove('show');
  }
}

document.querySelector('.go-top-container').addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

/*==================== Typed JS ====================*/

const typed = new Typed('.home-title',{
  strings: ["Rey de Copas - Club de Socios de C.A.I", "Únite al mejor club de Argentina.", "Más de un Millón de socios activos."],
  startDelay: 300,
  typeSpeed: 50,
  backSpeed: 50,
  backDelay: 3000,
  loop: true
});

/*==================== Scroll Reveal ====================*/

ScrollReveal().reveal('.home-container', { origin: 'left', reset:false, distance:'500px', delay:500, duration:1500 });
ScrollReveal().reveal('.go-bot-button', { origin: 'bottom', reset:false, distance:'500px', delay:1000, duration:1500 });
ScrollReveal().reveal('.whatsapp-button', { origin: 'top', reset:false, distance:'500px', delay:1500, duration:1500 });

ScrollReveal().reveal('.text-description', { origin: 'left', reset:false, distance:'150px', delay:500, duration:1000 });
ScrollReveal().reveal('.image-description', { origin: 'top', reset:false, distance:'150px', delay:500, duration:1000 });

ScrollReveal().reveal('.image-instagram', { origin: 'left', reset:false, distance:'150px', delay:500, duration:1000 });
ScrollReveal().reveal('.text-instagram', { origin: 'top', reset:false, distance:'150px', delay:500, duration:1000 });

ScrollReveal().reveal('.servicios-item:nth-child(1)', { origin: 'left', reset:false, distance:'150px', delay:500, duration:1500 });
ScrollReveal().reveal('.servicios-item:nth-child(2)', { origin: 'left', reset:false, distance:'150px', delay:700, duration:1600 });
ScrollReveal().reveal('.servicios-item:nth-child(3)', { origin: 'left', reset:false, distance:'150px', delay:900, duration:1700 });
ScrollReveal().reveal('.servicios-item:nth-child(4)', { origin: 'left', reset:false, distance:'150px', delay:1100, duration:1800 });
ScrollReveal().reveal('.servicios-item:nth-child(5)', { origin: 'left', reset:false, distance:'150px', delay:1300, duration:1900 });
ScrollReveal().reveal('.servicios-item:nth-child(6)', { origin: 'left', reset:false, distance:'150px', delay:1500, duration:2000 });

ScrollReveal().reveal('.container a:nth-child(1)', { origin: 'left', reset:false, distance:'150px', delay:500, duration:500 });
ScrollReveal().reveal('.container a:nth-child(2)', { origin: 'left', reset:false, distance:'150px', delay:700, duration:500 });
ScrollReveal().reveal('.container a:nth-child(3)', { origin: 'left', reset:false, distance:'150px', delay:900, duration:500 });

ScrollReveal().reveal('.form', { origin: 'top', reset:false, distance:'150px', delay:900, duration:1000 });
ScrollReveal().reveal('.contact-info', { origin: 'left', reset:false, distance:'150px', delay:900, duration:700 });
ScrollReveal().reveal('.contact-form', { origin: 'right', reset:false, distance:'150px', delay:900, duration:700 });

/*==================== Pasaje de valor mediante URl a 'Receta' ====================*/

function redirigir(valor) {
  window.location.href = "recipe.html?valor=" + encodeURIComponent(valor);
}

/*==================== Ancla a la sección about desde home ====================*/

document.querySelector('.go-bot').addEventListener('click', function(event) {
  window.location.href = '#about';
});

// SHOW MENU

const navMenu = document.getElementById('nav-menu'),
  navToggle = document.getElementById('nav-toggle'),
  navClose = document.getElementById('nav-close');

// MENU SHOW
navToggle.addEventListener('click', () => {
  navMenu.classList.add('show-menu')
  document.body.style.overflow = 'hidden';
});

// MENU CLOSE
navClose.addEventListener('click', () => {
  navMenu.classList.remove('show-menu')
  document.body.style.overflow = 'auto';
});
