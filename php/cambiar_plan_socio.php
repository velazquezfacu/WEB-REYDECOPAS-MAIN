<?php
session_start();
// Database connection
$conexion = new mysqli("localhost", "root", "", "reyescopas");

// Check connection
if ($conexion->connect_error) {
    // Log the error for debugging, but show a user-friendly message
    error_log("Error de conexión a la base de datos en altasocio.php: " . $conexion->connect_error);
    die("Lo sentimos, no podemos procesar su solicitud en este momento. Por favor, intente más tarde.");
}

// Query to get plan details
$sql_planes = "SELECT nombre_plan, costo_plan FROM planes";
$result_planes = $conexion->query($sql_planes);

$planes = [];
if ($result_planes) { // Check if query was successful
    if ($result_planes->num_rows > 0) {
        while ($row = $result_planes->fetch_assoc()) {
            $planes[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club de Socios C.A.I - Afiliaciones</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/escudocai.ico" type="image/x-icon">
    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/perfil-tabs.css">
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
        <a href="../index.php#home" class="logo"><img src="../images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">
                <li><a href="../index.php#home">Inicio</a></li>
                <li><a href="../index.php#servicios">Servicios</a></li>
                <li><a href="../index.php#recetas">Ustedes</a></li>
                <li><a href="../experiencias.php">Experiencias</a></li>
                <li><a href="../index.php#contacto">Contacto</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="user-menu">
                        <div class="user-avatar" id="user-avatar">
                            <i class='bx bxs-user-circle'></i>
                        </div>
                        <div class="user-dropdown" id="user-dropdown">
                            <div class="dropdown-header">
                                <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong></p>
                            </div>
                            <a href="../perfil.php"><i class='bx bxs-user'></i> Mi Perfil</a>
                            <a href="cerrarSesion.php"><i class='bx bx-log-out'></i> Cerrar Sesión</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="../sesion.php">Cuenta</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="../index.php#home">Inicio</a></li>
            <li><a href="../index.php#servicios">Servicios</a></li>
            <li><a href="../index.php#recetas">Ustedes</a></li>
            <li><a href="../experiencias.php">Experiencias</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="../perfil.php"><i class='bx bxs-user'></i> Mi Perfil</a></li>
                <li><a href="cerrarSesion.php"><i class='bx bx-log-out'></i> Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="../sesion.php">Cuenta</a></li>
            <?php endif; ?>
        </ul>
        <i class='bx bx-x' id="nav-close"></i>
    </div>

    <!-- WhatsApp Me -->
    <div id="whatsapp-button" class="whatsapp-button">
        <a href="https://wa.me/+5491138204268" target="_blank">
            <img src="../images/wasap.png" alt="WhatsApp">
        </a>
    </div>

    <section class="perfil-section" id="home">
        <div class="perfil-container">
            <h1 class="perfil-title">¡Cambia al plan que desees!</h1>
            <p class="perfil-text">En este momento contas con el Plan: </p>

            <div class="tabs-container">
                <form action="func_altasc.php" method="POST" class="perfil-form">
                    <h3 class="form-section-title"><strong>ELEGÍ TU PLAN</strong></h3>

                    <div class="planes-wrapper">
                        <?php foreach ($planes as $index => $plan): ?>
                            <label class="plan-item">
                                <input type="radio" name="plan" value="<?php echo htmlspecialchars($plan['nombre_plan']); ?>" required>
                                <div class="plan-box">
                                    <div class="plan-info">
                                        <h4 class="plan-title"><?php echo htmlspecialchars($plan['nombre_plan']); ?></h4>
                                        <div class="plan-pricing">
                                            <span class="plan-price">$<?php echo number_format($plan['costo_plan'], 0, ',', '.'); ?></span>
                                            <span class="plan-period">/mes</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <h3 class="form-section-title" style="margin-top: 3rem;"><strong>COMPLETÁ TUS DATOS</strong></h3>
                    <div class="form-grid">
                        <div class="input-container">
                            <label for="sexo">Sexo</label>
                            <select id="sexo" name="sexo" class="input" required>
                                <option value="">Seleccionar...</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="input-container">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="input" required>
                        </div>
                    </div>

                    <div class="btn-form-container" style="margin-top: 2rem;">
                        <button type="submit" class="btn custom-btn btn-form">
                            <i class='bx bx-check-circle'></i> Confirmar y Continuar al Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Script para el menú desplegable de usuario
        const userAvatar = document.getElementById('user-avatar');
        const userDropdown = document.getElementById('user-dropdown');

        if (userAvatar && userDropdown) {
            userAvatar.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }

        // Script para el menú responsive
        const navMenu = document.getElementById('nav-menu'),
              navToggle = document.getElementById('nav-toggle'),
              navClose = document.getElementById('nav-close')

        if(navToggle) navToggle.addEventListener('click', () => navMenu.classList.add('show-menu'))
        if(navClose) navClose.addEventListener('click', () => navMenu.classList.remove('show-menu'))
    </script>
</body>

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

     <script src="./js/scripts.js"></script>
</html>
<?php
// Close the database connection at the very end of the script
if ($conexion) {
    $conexion->close();
}
?>