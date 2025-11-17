<?php
session_start();
// Capturar redirect_url de la URL si existe
$redirect_url = isset($_GET['redirect_url']) ? $_GET['redirect_url'] : '';
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['nombre'] : '';

// Si el usuario ya está logueado y hay un redirect_url, redirigir directamente
if ($isLoggedIn && !empty($redirect_url)) {
    // Si redirect_url está en la carpeta php/, mantener la ruta
    if (strpos($redirect_url, 'php/') === 0) {
        header("Location: " . $redirect_url);
    } else {
        // Si no tiene php/, asumimos que está en php/
        header("Location: php/" . $redirect_url);
    }
    exit();
}
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
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Forum&family=Goudy+Bookletter+1911&family=Petit+Formal+Script&display=swap" rel="stylesheet">  
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Scroll Reveal -->

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .contacto {
            flex-grow: 1;
        }

        /* Modal de advertencia para socios inactivos */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-inactive {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-inactive .icon-warning {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .modal-inactive h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .modal-inactive p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-modal {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-contacto {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-contacto:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }

        .btn-cerrar-modal {
            background: #95a5a6;
            color: white;
        }

        .btn-cerrar-modal:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="#home" class="logo"><img src="images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">
                <li><a href="index.php#home">Inicio</a></li>
                <li><a href="index.php#servicios">Servicios</a></li>
                <li><a href="index.php#recetas">Ustedes</a></li>
                <li><a href="experiencias.php">Experiencias</a></li>
                <li><a href="index.php#contacto">Contacto</a></li>                
                <?php if ($isLoggedIn): ?>
                    <li class="user-menu">
                        <div class="user-avatar" id="user-avatar">
                            <i class='bx bxs-user-circle'></i>
                        </div>
                        <div class="user-dropdown" id="user-dropdown">
                            <div class="dropdown-header">
                                <p>Hola, <strong><?php echo htmlspecialchars($userName); ?></strong></p>
                            </div>
                            <?php if (isset($_SESSION['nombre']) && $_SESSION['nombre'] === 'super'): ?>
                                <a href="admin.php"><i class='bx bxs-dashboard'></i> Panel Admin</a>
                            <?php else: ?>
                                <a href="perfil.php"><i class='bx bxs-user'></i> Mi Perfil</a>
                            <?php endif; ?>
                            <a href="php/cerrarSesion.php"><i class='bx bx-log-out'></i> Cerrar Sesión</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="php/chequeo.php">Cuenta</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="index.php#home">Inicio</a></li>
            <li><a href="index.php#about">Nosotros</a></li>
            <li><a href="experiencias.php">Experiencias</a></li>
            <li><a href="index.php#contacto">Contacto</a></li>
            <li><a href="sesion.php">Cuenta</a></li>
        </ul>
        <i class='bx bx-x' id="nav-close"></i>
    </div> 



<section class="contacto" id="contacto">
    <div class="custom-shape-divider-top-1710973018" style="min-height: calc(100vh - 180px);">
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
                <?php if (!empty($redirect_url)): ?>
                    <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($redirect_url); ?>">
                <?php endif; ?>
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

<!-- Modal de advertencia para socios inactivos -->
<div class="modal-overlay" id="modal-inactive">
    <div class="modal-inactive">
        <i class='bx bx-error-circle icon-warning'></i>
        <h2>Cuenta Inactiva</h2>
        <p id="modal-message">Tu cuenta está dada de baja. Por favor, comunicate con un administrador para reestablecerla.</p>
        <div class="modal-buttons">
            <a href="#" class="btn-modal btn-contacto" id="btn-contactar">
                <i class='bx bx-envelope'></i> Contactar
            </a>
            <button class="btn-modal btn-cerrar-modal" id="btn-cerrar-modal">Cerrar</button>
        </div>
    </div>
</div>

<footer class="footer" id="footer">
    <div class="footer-logo-container">
        <img src="./images/Reyes de copas.png" alt="">
    </div>
    <div class="footer-box-container">
        <div class="footer-box">
            <h4>Rey de Copas - Club de socios</h4>
            <p>El infierno esta encantador</p>
            <p>MN 10538 - MP 6207</p>
        </div>
        <div class="footer-box">
            <h4>Sede Social</h4>
            <p>Diego A. Milito, Avellaneda, Provincia de Buenos Aires [Martes]</p>
            <p>Av. SeFueronAlaB 4141, Villa Urquiza [Sabado]</p>
        </div>
        <div class="footer-box">
            <h4>Contacto</h4>
            <div class="box-info">
                <p>lorem@ipsum.com</p>
            </div>
            <div class="box-info">
                <p>11-1234-5678</p>
            </div>
        </div>
    </div>
</footer>

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
                    } else if (response.inactive) {
                        // Si el socio está inactivo, mostrar modal
                        const modal = $('#modal-inactive');
                        const btnContactar = $('#btn-contactar');

                        // Crear URL con parámetros para prellenar el formulario
                        const nombreCompleto = response.user_name || '';
                        const email = response.user_email || '';
                        const mensaje = 'Hola, mi cuenta está inactiva y deseo reestablecerla. Por favor, ayúdenme con este proceso.';

                        // Los parámetros deben ir ANTES del hash
                        const contactUrl = `index.php?nombre=${encodeURIComponent(nombreCompleto)}&email=${encodeURIComponent(email)}&mensaje=${encodeURIComponent(mensaje)}#contacto`;

                        btnContactar.attr('href', contactUrl);
                        modal.addClass('show');

                        // Ocultar mensaje de error si existe
                        $('#respuesta').css('display', 'none');
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

        // 3. CERRAR MODAL DE SOCIO INACTIVO
        $('#btn-cerrar-modal').on('click', function() {
            $('#modal-inactive').removeClass('show');
        });

        // Cerrar modal al hacer clic fuera de él
        $('#modal-inactive').on('click', function(e) {
            if (e.target.id === 'modal-inactive') {
                $(this).removeClass('show');
            }
        });
    });
</script>

<script>
    /*=============== SHOW MENU ===============*/
    const navMenu = document.getElementById('nav-menu'),
          navToggle = document.getElementById('nav-toggle'),
          navClose = document.getElementById('nav-close')

    /*===== MENU SHOW =====*/
    if(navToggle){
        navToggle.addEventListener('click', () =>{
            navMenu.classList.add('show-menu')
        })
    }

    /*===== MENU HIDDEN =====*/
    if(navClose){
        navClose.addEventListener('click', () =>{
            navMenu.classList.remove('show-menu')
        })
    }
</script>
</body>
</html>