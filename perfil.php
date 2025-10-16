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
        <a href="#home" class="logo"><img src="images/Reyes de copas.png" alt=""></a>
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
        </div>
     </section>
    

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