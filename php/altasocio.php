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

// Query to get plan names
$sql_planes = "SELECT nombre_plan FROM planes";
$result_planes = $conexion->query($sql_planes);

$planes = [];
if ($result_planes) { // Check if query was successful
    if ($result_planes->num_rows > 0) {
        while ($row = $result_planes->fetch_assoc()) {
            $planes[] = $row['nombre_plan'];
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
        <a href="../index.html#home" class="logo"><img src="../images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">
                <li><a href="../index.html#home">Inicio</a></li>
                <li><a href="../index.html#about">Sobre Nosotros</a></li>
                <li><a href="../index.html#servicios">Servicios</a></li>
                <li><a href="../index.html#recetas">Ustedes</a></li>
                <li><a href="sesion.php">Perfil</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="../index.html#home">Inicio</a></li>
            <li><a href="../index.html#about">Sobre Nosotros</a></li>
            <li><a href="../index.html#servicios">Servicios</a></li>
            <li><a href="../index.html#recetas">Ustedes</a></li>
            <li><a href="sesion.php">Perfil</a></li>
        </ul>
        <i class='bx bx-x' id="nav-close"></i>
    </div>

    <!-- WhatsApp Me -->
    <div id="whatsapp-button" class="whatsapp-button">
        <a href="https://wa.me/+5491138204268" target="_blank">
            <img src="../images/wasap.png" alt="WhatsApp">
        </a>
    </div>

    <section class = "home" id="home">
        <div class="altasoc-container">           
            <h1 class="altasoc-text"> ¡Cargá tus datos personales y asociate!</h1>
            <form action="func_altasc.php" method="POST" class="alta-form">
                <div class="form-grid">
                    <div class="input-container">
                        <label for="plan">Tipo de plan</label>
                        <select id="plan" name="plan" class="input" required>
                            <?php foreach ($planes as $plan): ?>
                                <option value="<?php echo htmlspecialchars($plan); ?>"><?php echo htmlspecialchars($plan); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="sexo">Sexo</label>
                        <select id="sexo" name="sexo" class="input">
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="tipo_doc">Tipo de documento</label>
                        <select id="tipo_doc" name="tipo_doc" class="input">
                            <option value="dni">DNI</option>
                            <option value="pasaporte">Pasaporte</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="num_doc">Número de documento</label>
                        <input type="text" id="num_doc" name="num_doc" class="input" required>
                    </div>
                    <div class="input-container">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="input" required>
                    </div> 
                     <div class="input-container">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="input" required>
                    </div>
                    <div class="input-container">
                        <label for="email_confirm">Confirmación de email</label>
                        <input type="email" id="email_confirm" name="email_confirm" class="input" required>
                    </div>                 
                </div>
                <div class="btn-form-container">
                    <button type="submit" class="btn-form">Siguiente</button>
                </div>
            </form>
        </div>

    </section>
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