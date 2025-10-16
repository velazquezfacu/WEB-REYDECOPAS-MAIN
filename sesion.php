<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club de Socios C.A.I - Iniciar Sesión</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/escudocai.ico" type="image/x-icon">
    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="./styles/styles.css">
    <!-- Google Font Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Forum&family=Goudy+Bookletter+1911&family=Petit+Formal+Script&display=swap" rel="stylesheet">  
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Scroll Reveal -->

</head>
<body>
    <header class="header">
        <a href="#home" class="logo"><img src="images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">
                <li><a href="index.html#home">Inicio</a></li>
            <li><a href="index.html#about">Sobre nosotros</a></li>
            <!-- <li><a href="#consultorio">Consultorio</a></li> -->
            <li><a href="index.html#servicios">Servicios</a></li>
            <li><a href="index.html#recetas">Ustedes</a></li>
            <li><a href="sesion.php">Cuenta</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="index.html#home">Inicio</a></li>
            <li><a href="index.html#about">Sobre nosotros</a></li>
            <!-- <li><a href="#consultorio">Consultorio</a></li> -->
            <li><a href="index.html#servicios">Servicios</a></li>
            <li><a href="index.html#recetas">Ustedes</a></li>
            <li><a href="sesion.php">Cuenta</a></li>
        </ul>
        <i class='bx bx-x' id="nav-close"></i>
    </div>
  </div> 



<section class="contacto" id="contacto">
    <div class="custom-shape-divider-top-1710973018">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
    <div class="form">
        <div class="contact-info">
            <h1>Iniciar sesion</h1>
        </div>
        <div class="contact-form">
            <span class="circle one"></span>
            <span class="circle two"></span>

            <form id="formSesion" action="php/sesionScript.php" method="post">
                <div class="input-container">
                    <input type="email" name="email" placeholder="Email" class="input">
                    <label for=""></label>
                </div>

                <div class="input-container">
                    <input type="password" name="contrasena" placeholder="Contraseña" class="input" required>
                    <label for=""></label>

                </div>

                <div class="btn-form-container">
                    <input type="submit" value="Iniciar sesion" class="btn custom-btn btn-form">
                </div> 

                <?php
                if(isset($_SESSION['email'])){
                    echo '<div class="btn-form-container">
                            <input type="button" id="cerrar-sesion" class="btn custom-btn btn-form" value="Cerrar Sesion">
                        </div>' ;
                }
                
                ?>
                
                <p class="btn-form-container">Si todavia no tenes una cuenta,&nbsp;<a href="registro.html">creala aca</a></p>   

                    
                <div id="respuesta"></div>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        // 1. LÓGICA DE INICIO DE SESIÓN (#formSesion)
        $('#formSesion').on('submit', function(event) {
            event.preventDefault();
        
            var formData = $(this).serialize();
        
            $.ajax({
                type: 'POST',
                url: 'php/sesionScript.php', 
                data: formData,
                dataType:'json',
                success: function(response){
                    if (response.success) {
                        // Si el inicio de sesión es exitoso, usamos la redirección que nos da el servidor.
                        window.location.href = response.redirect;
                    } else {
                        // Si success es FALSE, mostramos el mensaje de error.
                        $('#respuesta').text(response.message).css('display', 'block');
                    }
                },
                error: function(xhr, status, error) {
                    $('#respuesta').text('Error en la solicitud: ' + error).css('display', 'block');
                }
            });
        });

        // 2. LÓGICA DE CIERRE DE SESIÓN (#cerrar-sesion)
        $('#cerrar-sesion').on('click', function(event) {
            event.preventDefault();
            
            $.ajax({
                url: 'php/cerrarSesion.php',
                success: function(response) {
                    window.location.href = 'sesion.php'; 
                },
                error: function(xhr, status, error) {
                    $('#respuesta').text('Error al cerrar sesión: ' + error).css('display', 'block');
                }
            });
        });
    });
</script>
        
</body>
</html>