<?php
    session_start();

    // 1. Proteger la página: si no hay sesión, redirigir al login
    if (!isset($_SESSION['nombre'])) {
        header("Location: php/sesion.php");
        exit();
    }

    // 2. Incluir el script que obtiene los datos del perfil
    include 'php/func_perfil.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club de Socios C.A.I - Perfil</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/escudocai.ico" type="image/x-icon">
    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="./styles/styles.css">
    <!-- Google Font Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- Google Font Link Agregado-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Forum&family=Goudy+Bookletter+1911&family=Petit+Formal+Script&display=swap" rel="stylesheet">  
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Scroll Reveal -->
    <script src="https://unpkg.com/scrollreveal"></script>

</head>
<body>
    <!-- Navbar -->
    <header class="header">
        <a href="index.html#home" class="logo"><img src="images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">                
                <li><a href="index.html#home">Inicio</a></li>
                <li><a href="index.html#about">Sobre Nosotros</a></li>
                <li><a href="index.html#servicios">Servicios</a></li>
                <li><a href="index.html#recetas">Ustedes</a></li>
                <li><a href="php/cerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="index.html#home">Inicio</a></li>
            <li><a href="index.html#about">Sobre mi</a></li>
            <li><a href="index.html#servicios">Servicios</a></li>
            <li><a href="index.html#recetas">Ustedes</a></li>
            <li><a href="php/cerrarSesion.php">Cerrar Sesión</a></li>
        </ul>
        <i class='bx bx-x' id="nav-close"></i>
    </div>

    <!-- WhatsApp Me -->
    <div id="whatsapp-button" class="whatsapp-button">
        <a href="https://wa.me/+5491138204268" target="_blank">
            <img src="images/wasap.png" alt="WhatsApp">
        </a>
    </div>

     <section class="perfil-section" id="home">
        <div class="perfil-container">
            <h1 class="perfil-title">Bienvenido, <?php echo htmlspecialchars($nombreusuario); ?></h1>
            <p class="perfil-text">Desde aquí podrás gestionar tu cuenta, ver tus datos y acceder a los beneficios exclusivos para socios.</p>

            <form action="php/modificar.php" method="POST" class="alta-form" style="margin-top: 2rem;">
                <h3 class="form-section-title"><strong>DATOS PERSONALES</strong></h3>
                <div class="form-grid">
                    <div class="input-container">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="input" value="<?php echo htmlspecialchars($nombre); ?>" required>
                    </div>
                    <div class="input-container">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="input" value="<?php echo htmlspecialchars($apellido); ?>" required>
                    </div>
                    <div class="input-container">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="input" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="input-container">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" class="input" value="<?php echo htmlspecialchars($dni); ?>" readonly>
                    </div>
                    <div class="input-container">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="input" value="<?php echo htmlspecialchars($telefono); ?>" required maxlength="10" pattern="[0-9]{10}" title="El teléfono debe tener 10 dígitos sin espacios ni guiones.">
                    </div>
                </div>

                <h3 class="form-section-title"><strong>DATOS DE SOCIO</strong></h3>
                <div class="form-grid">
                    <div class="input-container">
                        <label for="nro_socio">Número de Socio</label>
                        <input type="text" id="nro_socio" name="nro_socio" class="input" value="<?php echo htmlspecialchars($nro_socio); ?>" readonly>
                    </div>
                    <div class="input-container">
                        <label for="fecha_registro">Fecha de Registro</o_socio">
                        <input type="text" id="fecha_registro" name="fecha_registro" class="input" value="<?php echo htmlspecialchars($fecha_registro); ?>" readonly>
                    </div>
                    <div class="input-container">
                        <label for="plan">Tipo de Plan</label>
                        <input type="text" id="plan" name="plan" class="input" value="<?php echo htmlspecialchars($nombre_plan); ?>" readonly>
                        <a href="#">Cambiar de plan!</a>
                    </div>
                    <div class="input-container">
                        <label for="plan">Porcentaje de Interes</label>
                        <input type="text" id="interes" name="interes" class="input" value="<?php echo htmlspecialchars($interes); ?>" readonly>
                    </div>
                     <div class="input-container">
                        <label for="plan">Estado</label>
                        <input type="text" id="estado" name="estado" class="input" value="<?php echo htmlspecialchars($estado); ?>" readonly>
                    </div>
                </div>
                <div class="btn-form-container">
                    <button type="submit" class="btn custom-btn btn-form">Guardar Cambios</button>
                </div>
            </form>
        </div>
     </section>
    
     <script>
        // Script para permitir solo números en el campo de teléfono
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('telefono');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>

      <footer class="footer" id="footer">
        <div class="footer-logo-container">
            <img src="./images/Reyes de copas.png" alt="">
        </div>
        <div class="footer-box-container">
            <div class="footer-box">
                <h4>Rey de Copas - Club de socios</h4>
                <p>Lic. en Nutricion - UBA</p>
                <p>MN 10538 - MP 6207</p>
            </div>
            <div class="footer-box">
                <h4>Turnos</h4>
                <p>Bauness 2254, Villa Urquiza [Martes]</p>
                <p>Av. Triunvirato 4141, Villa Urquiza [Sabado]</p>
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
</body>
</html>