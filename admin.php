<?php
session_start();

// 1. Proteger la página: si no hay sesión o el usuario no es 'super', redirigir.
if (!isset($_SESSION['user_id']) || $_SESSION['nombre'] !== 'super') {
    header("Location: index.php"); // Redirige a la página principal
    exit();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['nombre'] : '';

// --- INICIO: LÓGICA PARA OBTENER ESTADÍSTICAS ---
$conexion = new mysqli("localhost", "root", "", "reyescopas");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 1. Cantidad total de usuarios
$result_usuarios = $conexion->query("SELECT COUNT(id) as total FROM usuarios");
$total_usuarios = $result_usuarios->fetch_assoc()['total'] - 1;

// 2. Cantidad de socios activos
$result_socios = $conexion->query("SELECT COUNT(id) as total FROM socio WHERE estado = 'Activo'");
$socios_activos = $result_socios->fetch_assoc()['total'];

// 3. Desglose de socios por plan
$sql_planes = "SELECT p.nombre_plan, COUNT(s.id) as cantidad 
               FROM socio s 
               JOIN planes p ON s.id_plan = p.id 
               WHERE s.estado = 'Activo' 
               GROUP BY p.nombre_plan";
$result_planes = $conexion->query($sql_planes);
$socios_por_plan = [];
while($row = $result_planes->fetch_assoc()) {
    $socios_por_plan[] = $row;
}

// 4. Cantidad de experiencias reservadas (confirmadas)
$result_reservas = $conexion->query("SELECT COUNT(id) as total FROM reservas_experiencias WHERE estado = 'confirmada'");
$total_reservas = $result_reservas->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Club de Socios C.A.I - Panel de Administración</title>
     <!-- Favicon -->
     <link rel="shortcut icon" href="images/escudocai.ico" type="image/x-icon">
     <!-- Custom CSS Link -->
     <link rel="stylesheet" href="./styles/styles.css">
     <link rel="stylesheet" href="./styles/perfil-tabs.css"> <!-- Estilo de perfil -->
     <!-- Google Font Link -->
     <link rel="stylesheet" href="./styles/admin-dashboard.css"> <!-- Estilos para el dashboard -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Forum&family=Goudy+Bookletter+1911&family=Petit+Formal+Script&display=swap" rel="stylesheet">
     <!-- Box Icons -->
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

 </head>
 <body>

    <!-- Navbar -->
    <header class="header">
        <a href="#home" class="logo"><img src="images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">
                <li><a href="index.php#home">Inicio</a></li>
                <li><a href="experiencias.php">Experiencias</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="user-menu">
                        <div class="user-avatar" id="user-avatar">
                            <i class='bx bxs-user-circle'></i>
                        </div>
                        <div class="user-dropdown" id="user-dropdown">
                            <div class="dropdown-header">
                                <p>Hola, <strong><?php echo htmlspecialchars($userName); ?></strong></p>
                            </div>                            
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
             <li><a href="experiencias.php">Experiencias</a></li>
             <?php if ($isLoggedIn): ?>
                 <li><a href="php/cerrarSesion.php"><i class='bx bx-log-out'></i> Cerrar Sesión</a></li>
             <?php else: ?>
                 <li><a href="php/chequeo.php">Cuenta</a></li>
             <?php endif; ?>
         </ul>
         <i class='bx bx-x' id="nav-close"></i>
      </div>

    <section class="perfil-section" id="home">
        <div class="perfil-container">
            <h1 class="perfil-title">Panel de Administración</h1>
            <p class="perfil-text">Gestión de usuarios, socios y contenido del sitio.</p>

            <div class="tabs-container">
                <!-- Aquí podrías agregar pestañas para diferentes secciones del admin -->
                <div class="tabs-nav">
                    <button class="tab-btn active" data-tab="dashboard">
                        <i class='bx bxs-dashboard'></i>
                        <span>Dashboard</span>
                    </button>
                </div>

                <!-- Contenido del Dashboard -->
                <div class="tab-content active" id="tab-dashboard">
                    <div class="perfil-form">
                        <h3 class="form-section-title"><strong>ACCIONES RÁPIDAS</strong></h3>
                        <div class="dashboard-grid">
                            <!-- Card Total Usuarios -->
                            <div class="stat-card">
                                <div class="stat-card-icon users">
                                    <i class='bx bxs-group'></i>
                                </div>
                                <div class="stat-card-info">
                                    <p>Usuarios Registrados</p>
                                    <span><?php echo $total_usuarios; ?></span>
                                </div>
                            </div>

                            <!-- Card Socios Activos -->
                            <div class="stat-card">
                                <div class="stat-card-icon members">
                                    <i class='bx bxs-user-check'></i>
                                </div>
                                <div class="stat-card-info">
                                    <p>Socios Activos</p>
                                    <span><?php echo $socios_activos; ?></span>
                                </div>
                            </div>

                             <!-- Card Reservas Confirmadas -->
                             <div class="stat-card">
                                <div class="stat-card-icon bookings">
                                    <i class='bx bxs-calendar-star'></i>
                                </div>
                                <div class="stat-card-info">
                                    <p>Reservas Confirmadas</p>
                                    <span><?php echo $total_reservas; ?></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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

    <!-- Scripts -->
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
</html>