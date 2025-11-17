<?php
session_start();

// Conectar para verificar la categoría del usuario
$conexion_check = new mysqli("localhost", "root", "", "reyescopas");
if ($conexion_check->connect_error) {
    die("Error de conexión: " . $conexion_check->connect_error);
}

// Obtener la categoría del usuario actual
$categoria_usuario = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $conexion_check->prepare("SELECT categoria FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $categoria_usuario = $row['categoria'];
    }
    $stmt->close();
}
$conexion_check->close();

// Proteger la página: si no hay sesión o el usuario no es 'admin', redirigir.
if (!isset($_SESSION['user_id']) || $categoria_usuario !== 'admin') {
    header("Location: index.php");
    exit();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['nombre'] : '';

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "reyescopas");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener estadísticas de socios activos
$sql_stats = "SELECT
                COUNT(s.id) as total_socios_activos,
                SUM(p.costo_plan) as total_mensual
              FROM socio s
              JOIN planes p ON s.id_plan = p.id
              WHERE s.estado = 'Activo'";
$result_stats = $conexion->query($sql_stats);
$stats = $result_stats->fetch_assoc();

// Obtener lista de socios activos para preview
$sql_socios = "SELECT
                s.id,
                s.nro_socio,
                u.nombre,
                u.apellido,
                p.nombre_plan,
                p.costo_plan,
                (SELECT COUNT(*) FROM cuotas WHERE id_socio = s.id) as total_cuotas
               FROM socio s
               JOIN usuarios u ON s.id_usua = u.id
               JOIN planes p ON s.id_plan = p.id
               WHERE s.estado = 'Activo'
               ORDER BY s.nro_socio";
$result_socios = $conexion->query($sql_socios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Generar Cuotas - Panel de Administración</title>
     <!-- Favicon -->
     <link rel="shortcut icon" href="images/escudocai.ico" type="image/x-icon">
     <!-- Custom CSS Link -->
     <link rel="stylesheet" href="./styles/styles.css">
     <link rel="stylesheet" href="./styles/perfil-tabs.css">
     <link rel="stylesheet" href="./styles/admin-dashboard.css">
     <!-- Google Font Link -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Forum&family=Goudy+Bookletter+1911&family=Petit+Formal+Script&display=swap" rel="stylesheet">
     <!-- Box Icons -->
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

     <style>
         /* Estilos para los select dropdown */
         select option {
             background-color: rgba(0, 0, 0, 0.95);
             color: white;
             padding: 10px;
         }

         select option:hover {
             background-color: rgba(231, 76, 60, 0.8);
         }

         .generation-card {
             background: rgba(255, 255, 255, 0.05);
             padding: 2rem;
             border-radius: 15px;
             border: 1px solid rgba(255, 255, 255, 0.1);
             margin-bottom: 2rem;
         }

         .generation-card h3 {
             color: white;
             margin-bottom: 1.5rem;
             font-family: 'Poppins', sans-serif;
         }

         .form-group {
             margin-bottom: 1.5rem;
         }

         .form-group label {
             display: block;
             color: rgba(255, 255, 255, 0.8);
             margin-bottom: 0.5rem;
             font-family: 'Poppins', sans-serif;
             font-size: 0.9rem;
         }

         .form-group input,
         .form-group select {
             width: 100%;
             padding: 0.8rem;
             border-radius: 8px;
             border: 1px solid rgba(255, 255, 255, 0.2);
             background: rgba(255, 255, 255, 0.05);
             color: white;
             font-family: 'Poppins', sans-serif;
         }

         .btn-generate {
             padding: 1rem 2rem;
             border-radius: 8px;
             border: none;
             background: linear-gradient(135deg, #2ecc71, #27ae60);
             color: white;
             font-family: 'Poppins', sans-serif;
             font-weight: 600;
             cursor: pointer;
             font-size: 1rem;
             transition: all 0.3s;
             width: 100%;
         }

         .btn-generate:hover {
             transform: scale(1.02);
             box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
         }

         .btn-generate:disabled {
             opacity: 0.5;
             cursor: not-allowed;
         }

         .preview-section {
             background: rgba(255, 255, 255, 0.03);
             padding: 1.5rem;
             border-radius: 10px;
             margin-top: 1.5rem;
             margin-bottom: 1.5rem;
         }

         .alert-warning {
             background: rgba(243, 156, 18, 0.2);
             border-left: 4px solid #f39c12;
             padding: 1rem;
             border-radius: 8px;
             color: white;
             margin-bottom: 1rem;
         }

         .alert-success {
             background: rgba(46, 204, 113, 0.2);
             border-left: 4px solid #2ecc71;
             padding: 1rem;
             border-radius: 8px;
             color: white;
             margin-bottom: 1rem;
         }

         .alert-error {
             background: rgba(231, 76, 60, 0.2);
             border-left: 4px solid #e74c3c;
             padding: 1rem;
             border-radius: 8px;
             color: white;
             margin-bottom: 1rem;
         }

         /* Estilos para el scroll interno de la vista previa */
         .preview-section div::-webkit-scrollbar {
             width: 8px;
         }

         .preview-section div::-webkit-scrollbar-track {
             background: rgba(255, 255, 255, 0.1);
             border-radius: 10px;
         }

         .preview-section div::-webkit-scrollbar-thumb {
             background: rgba(231, 76, 60, 0.8);
             border-radius: 10px;
         }

         .preview-section div::-webkit-scrollbar-thumb:hover {
             background: rgba(231, 76, 60, 1);
         }

         /* Override body flex para esta página */
         body {
             display: flex !important;
             flex-direction: column;
             min-height: 100vh;
             position: relative;
         }

         .perfil-section {
             flex: 1 !important;
             min-height: 0;
             padding-bottom: 20px;
         }

         .footer {
             position: relative;
             margin-top: auto;
             flex-shrink: 0;
         }

         html {
             height: 100%;
         }
     </style>

 </head>
 <body>

    <!-- Navbar -->
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

    <!-- Sidebar Lateral -->
    <aside class="admin-sidebar-lateral">
        <div class="sidebar-content">
            <h3 class="sidebar-title">Módulos</h3>
            <nav class="sidebar-menu">
                <a href="admin.php" class="sidebar-module">
                    <i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span>
                </a>
                <a href="socios.php" class="sidebar-module">
                    <i class='bx bxs-group'></i>
                    <span>Socios</span>
                </a>
                <a href="cuenta_corriente.php" class="sidebar-module">
                    <i class='bx bx-money'></i>
                    <span>Cuenta Corriente</span>
                </a>
                <a href="generar_cuotas.php" class="sidebar-module active">
                    <i class='bx bx-calendar-plus'></i>
                    <span>Generar Cuotas</span>
                </a>
            </nav>
        </div>
    </aside>

    <section class="perfil-section" id="home" style="padding: 100px 5% 20px;">
        <div class="perfil-container" style="max-width: 95%; margin-bottom: 20px; padding: 2rem;">
            <h1 class="perfil-title">Generación de Cuotas</h1>
            <p class="perfil-text">Generar cuotas mensuales para todos los socios activos.</p>

            <div class="tabs-container">
                <div class="tab-content active">
                    <div class="perfil-form">

                        <!-- Mensaje de respuesta -->
                        <div id="responseMessage"></div>

                        <!-- Estadísticas -->
                        <div class="dashboard-grid" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-card-icon members">
                                    <i class='bx bxs-group'></i>
                                </div>
                                <div class="stat-card-info">
                                    <p>Socios Activos</p>
                                    <span><?php echo $stats['total_socios_activos']; ?></span>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-card-icon bookings">
                                    <i class='bx bx-dollar'></i>
                                </div>
                                <div class="stat-card-info">
                                    <p>Total Mensual Estimado</p>
                                    <span>$<?php echo number_format($stats['total_mensual'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de generación -->
                        <div class="generation-card">
                            <h3><i class='bx bx-calendar-plus'></i> Generar Nuevas Cuotas</h3>

                            <div class="alert-warning">
                                <i class='bx bx-info-circle'></i> <strong>Importante:</strong> Esta acción generará una nueva cuota para cada socio activo. Asegúrate de configurar correctamente la fecha de vencimiento.
                            </div>

                            <form id="formGenerarCuotas">
                                <div class="form-group">
                                    <label for="fechaVencimiento">
                                        <i class='bx bx-calendar'></i> Fecha de Vencimiento
                                    </label>
                                    <input type="date" id="fechaVencimiento" name="fechaVencimiento" required>
                                </div>

                                <div class="form-group">
                                    <label for="periodo">
                                        <i class='bx bx-time'></i> Período
                                    </label>
                                    <select id="periodo" name="periodo">
                                        <option value="manual">Manual (generación única)</option>
                                        <option value="mensual">Mensual (automático)</option>
                                    </select>
                                </div>

                                <div class="preview-section">
                                    <h4 style="color: white; margin-bottom: 1rem;">
                                        <i class='bx bx-list-check'></i> Vista Previa
                                    </h4>
                                    <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 1rem;">
                                        Se generarán cuotas para los siguientes <strong><?php echo $stats['total_socios_activos']; ?></strong> socios:
                                    </p>
                                    <div style="max-height: 200px; overflow-y: auto; background: rgba(0, 0, 0, 0.2); padding: 1rem; border-radius: 8px; scrollbar-width: thin; scrollbar-color: rgba(231, 76, 60, 0.8) rgba(255, 255, 255, 0.1);">
                                        <?php if ($result_socios->num_rows > 0): ?>
                                            <?php while($socio = $result_socios->fetch_assoc()): ?>
                                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.1); color: white;">
                                                    <span><?php echo htmlspecialchars($socio['nro_socio']); ?> - <?php echo htmlspecialchars($socio['nombre'] . ' ' . $socio['apellido']); ?></span>
                                                    <span style="color: #2ecc71; font-weight: 600;">$<?php echo number_format($socio['costo_plan'], 0, ',', '.'); ?></span>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <p style="color: rgba(255, 255, 255, 0.5); text-align: center;">No hay socios activos</p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <button type="submit" class="btn-generate" id="btnGenerar">
                                    <i class='bx bx-check-circle'></i> Generar Cuotas
                                </button>
                            </form>
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

        // Establecer fecha mínima como hoy
        const fechaInput = document.getElementById('fechaVencimiento');
        const today = new Date().toISOString().split('T')[0];
        fechaInput.min = today;

        // Manejar envío del formulario
        document.getElementById('formGenerarCuotas').addEventListener('submit', function(e) {
            e.preventDefault();

            const fechaVencimiento = document.getElementById('fechaVencimiento').value;
            const periodo = document.getElementById('periodo').value;
            const btnGenerar = document.getElementById('btnGenerar');
            const responseMessage = document.getElementById('responseMessage');

            if (!fechaVencimiento) {
                responseMessage.innerHTML = '<div class="alert-error"><i class="bx bx-error"></i> Por favor selecciona una fecha de vencimiento.</div>';
                return;
            }

            // Confirmar acción
            if (!confirm(`¿Estás seguro de generar cuotas para todos los socios activos con vencimiento el ${fechaVencimiento}?`)) {
                return;
            }

            // Deshabilitar botón
            btnGenerar.disabled = true;
            btnGenerar.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Generando...';

            // Enviar petición AJAX
            const formData = new FormData();
            formData.append('fecha_vencimiento', fechaVencimiento);
            formData.append('periodo', periodo);

            fetch('php/procesar_generacion_cuotas.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    responseMessage.innerHTML = `<div class="alert-success">
                        <i class="bx bx-check-circle"></i> <strong>¡Éxito!</strong> ${data.message}
                        <br><small>Cuotas generadas: ${data.cuotas_generadas} | Total: $${new Intl.NumberFormat('es-AR').format(data.total_generado)}</small>
                    </div>`;

                    // Limpiar formulario
                    document.getElementById('formGenerarCuotas').reset();
                } else {
                    responseMessage.innerHTML = `<div class="alert-error">
                        <i class="bx bx-error"></i> <strong>Error:</strong> ${data.message}
                    </div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseMessage.innerHTML = '<div class="alert-error"><i class="bx bx-error"></i> Error al procesar la solicitud.</div>';
            })
            .finally(() => {
                // Rehabilitar botón
                btnGenerar.disabled = false;
                btnGenerar.innerHTML = '<i class="bx bx-check-circle"></i> Generar Cuotas';

                // Scroll al mensaje
                responseMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        });
    </script>

  </body>
</html>
<?php $conexion->close(); ?>
