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

// Obtener datos de socios con información del usuario relacionado
$conexion = new mysqli("localhost", "root", "", "reyescopas");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql_socios = "SELECT
                s.id,
                s.nro_socio,
                u.nombre,
                u.apellido,
                u.email,
                u.dni,
                u.telefono,
                s.sexo,
                s.fecha_nacimiento,
                s.fecha_registro,
                p.nombre_plan,
                s.estado,
                cc.saldo,
                cc.estado as estado_cuenta
               FROM socio s
               JOIN usuarios u ON s.id_usua = u.id
               JOIN planes p ON s.id_plan = p.id
               LEFT JOIN cuenta_cte cc ON s.id = cc.id_soc
               ORDER BY s.fecha_registro DESC";

$result_socios = $conexion->query($sql_socios);

// Obtener lista de planes para filtro
$sql_planes = "SELECT DISTINCT nombre_plan FROM planes ORDER BY nombre_plan";
$result_planes = $conexion->query($sql_planes);
$planes = [];
while($row = $result_planes->fetch_assoc()) {
    $planes[] = $row['nombre_plan'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Socios - Panel de Administración</title>
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
                <a href="socios.php" class="sidebar-module active">
                    <i class='bx bxs-group'></i>
                    <span>Socios</span>
                </a>
                <a href="cuenta_corriente.php" class="sidebar-module">
                    <i class='bx bx-money'></i>
                    <span>Cuenta Corriente</span>
                </a>
                <a href="generar_cuotas.php" class="sidebar-module">
                    <i class='bx bx-calendar-plus'></i>
                    <span>Generar Cuotas</span>
                </a>
            </nav>
        </div>
    </aside>

    <section class="perfil-section" id="home">
        <div class="perfil-container" style="max-width: 95%;">
            <h1 class="perfil-title">Gestión de Socios</h1>
            <p class="perfil-text">Listado completo de socios con información personal y estado de cuenta.</p>

            <div class="tabs-container">
                <div class="tab-content active">
                    <div class="perfil-form">
                        <h3 class="form-section-title"><strong>LISTADO DE SOCIOS</strong></h3>

                        <!-- Barra de filtros -->
                        <div class="filters-container" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                            <div style="flex: 1; min-width: 250px;">
                                <label style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">
                                    <i class='bx bx-search'></i> Buscar por Nro. Socio o Nombre
                                </label>
                                <input type="text" id="searchInput" placeholder="Ej: 001 o Juan Perez"
                                       style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.05); color: white; font-family: 'Poppins', sans-serif;">
                            </div>

                            <div style="min-width: 180px;">
                                <label style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">
                                    <i class='bx bx-filter'></i> Filtrar por Plan
                                </label>
                                <select id="filterPlan" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.05); color: white; font-family: 'Poppins', sans-serif;">
                                    <option value="">Todos los planes</option>
                                    <?php foreach($planes as $plan): ?>
                                        <option value="<?php echo htmlspecialchars($plan); ?>"><?php echo htmlspecialchars($plan); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div style="min-width: 180px;">
                                <label style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">
                                    <i class='bx bx-filter'></i> Filtrar por Estado
                                </label>
                                <select id="filterEstado" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.05); color: white; font-family: 'Poppins', sans-serif;">
                                    <option value="">Todos los estados</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>

                            <div style="min-width: 200px;">
                                <label style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">
                                    <i class='bx bx-money'></i> Estado de Cuenta
                                </label>
                                <select id="filterEstadoCuenta" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.05); color: white; font-family: 'Poppins', sans-serif;">
                                    <option value="">Todos</option>
                                    <option value="Pagado">Pagado</option>
                                    <option value="Pendiente">Pendiente</option>
                                </select>
                            </div>

                            <button id="clearFilters" style="padding: 0.8rem 1.5rem; border-radius: 8px; border: none; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; font-family: 'Poppins', sans-serif; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                <i class='bx bx-x'></i> Limpiar
                            </button>
                        </div>

                        <div class="activity-table-container">
                            <table class="activity-table">
                                <thead>
                                    <tr>
                                        <th>Nro Socio</th>
                                        <th>Nombre Completo</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Plan</th>
                                        <th>Fecha Registro</th>
                                        <th>Estado</th>
                                        <th>Saldo</th>
                                        <th>Estado Cuenta</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result_socios->num_rows > 0): ?>
                                        <?php while($socio = $result_socios->fetch_assoc()): ?>
                                            <tr class="socio-row"
                                                data-nro="<?php echo htmlspecialchars($socio['nro_socio']); ?>"
                                                data-nombre="<?php echo htmlspecialchars(strtolower($socio['nombre'] . ' ' . $socio['apellido'])); ?>"
                                                data-plan="<?php echo htmlspecialchars($socio['nombre_plan']); ?>"
                                                data-estado="<?php echo htmlspecialchars($socio['estado']); ?>"
                                                data-estado-cuenta="<?php echo htmlspecialchars($socio['estado_cuenta']); ?>">
                                                <td><?php echo htmlspecialchars($socio['nro_socio']); ?></td>
                                                <td><?php echo htmlspecialchars($socio['nombre'] . ' ' . $socio['apellido']); ?></td>
                                                <td><?php echo htmlspecialchars($socio['dni']); ?></td>
                                                <td><?php echo htmlspecialchars($socio['email']); ?></td>
                                                <td><?php echo htmlspecialchars($socio['telefono']); ?></td>
                                                <td><?php echo htmlspecialchars($socio['nombre_plan']); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($socio['fecha_registro'])); ?></td>
                                                <td>
                                                    <span class="activity-badge <?php echo $socio['estado'] === 'Activo' ? 'socio' : 'reserva'; ?>">
                                                        <?php echo htmlspecialchars($socio['estado']); ?>
                                                    </span>
                                                </td>
                                                <td>$<?php echo number_format($socio['saldo'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <span class="activity-badge <?php echo $socio['estado_cuenta'] === 'Pagado' ? 'socio' : 'reserva'; ?>">
                                                        <?php echo htmlspecialchars($socio['estado_cuenta']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($socio['estado'] === 'Activo'): ?>
                                                        <button onclick="desactivarSocio(<?php echo $socio['id']; ?>, '<?php echo htmlspecialchars($socio['nombre'] . ' ' . $socio['apellido']); ?>')"
                                                                style="padding: 0.5rem 1rem; border-radius: 6px; border: none; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; font-family: 'Poppins', sans-serif; font-weight: 500; cursor: pointer; font-size: 0.85rem; transition: all 0.3s;">
                                                            <i class='bx bx-user-x'></i> Dar de Baja
                                                        </button>
                                                    <?php else: ?>
                                                        <button onclick="activarSocio(<?php echo $socio['id']; ?>, '<?php echo htmlspecialchars($socio['nombre'] . ' ' . $socio['apellido']); ?>')"
                                                                style="padding: 0.5rem 1rem; border-radius: 6px; border: none; background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; font-family: 'Poppins', sans-serif; font-weight: 500; cursor: pointer; font-size: 0.85rem; transition: all 0.3s;">
                                                            <i class='bx bx-user-check'></i> Activar
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" style="text-align: center; color: rgba(255, 255, 255, 0.7);">No hay socios registrados</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="pagination-container" style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 2rem;">
                            <button id="prevPage" style="padding: 0.6rem 1.2rem; border-radius: 6px; border: none; background: rgba(255, 255, 255, 0.1); color: white; font-family: 'Poppins', sans-serif; cursor: pointer; transition: all 0.3s;">
                                <i class='bx bx-chevron-left'></i> Anterior
                            </button>
                            <span id="pageInfo" style="color: rgba(255, 255, 255, 0.8); font-family: 'Poppins', sans-serif;"></span>
                            <button id="nextPage" style="padding: 0.6rem 1.2rem; border-radius: 6px; border: none; background: rgba(255, 255, 255, 0.1); color: white; font-family: 'Poppins', sans-serif; cursor: pointer; transition: all 0.3s;">
                                Siguiente <i class='bx bx-chevron-right'></i>
                            </button>
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

        // Sistema de filtros y paginación para tabla de socios
        const searchInput = document.getElementById('searchInput');
        const filterPlan = document.getElementById('filterPlan');
        const filterEstado = document.getElementById('filterEstado');
        const filterEstadoCuenta = document.getElementById('filterEstadoCuenta');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const socioRows = document.querySelectorAll('.socio-row');

        // Variables de paginación
        const rowsPerPage = 15;
        let currentPage = 1;
        let filteredRows = [];

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedPlan = filterPlan.value;
            const selectedEstado = filterEstado.value;
            const selectedEstadoCuenta = filterEstadoCuenta.value;

            filteredRows = [];

            socioRows.forEach(row => {
                const nroSocio = row.getAttribute('data-nro').toLowerCase();
                const nombre = row.getAttribute('data-nombre');
                const plan = row.getAttribute('data-plan');
                const estado = row.getAttribute('data-estado');
                const estadoCuenta = row.getAttribute('data-estado-cuenta');

                // Búsqueda por número o nombre
                const matchesSearch = nroSocio.includes(searchTerm) || nombre.includes(searchTerm);

                // Filtros de selección
                const matchesPlan = !selectedPlan || plan === selectedPlan;
                const matchesEstado = !selectedEstado || estado === selectedEstado;
                const matchesEstadoCuenta = !selectedEstadoCuenta || estadoCuenta === selectedEstadoCuenta;

                // Agregar a filteredRows si cumple con los filtros
                if (matchesSearch && matchesPlan && matchesEstado && matchesEstadoCuenta) {
                    filteredRows.push(row);
                }
            });

            currentPage = 1;
            displayPage();
        }

        function displayPage() {
            // Ocultar todas las filas primero
            socioRows.forEach(row => row.style.display = 'none');

            // Calcular índices de inicio y fin
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            // Mostrar solo las filas de la página actual
            filteredRows.slice(startIndex, endIndex).forEach(row => {
                row.style.display = '';
            });

            // Actualizar información de paginación
            updatePaginationInfo();
        }

        function updatePaginationInfo() {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            const pageInfo = document.getElementById('pageInfo');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');

            pageInfo.textContent = `Página ${currentPage} de ${totalPages || 1} (${filteredRows.length} registros)`;

            // Deshabilitar botones según corresponda
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage >= totalPages;

            prevBtn.style.opacity = currentPage === 1 ? '0.5' : '1';
            prevBtn.style.cursor = currentPage === 1 ? 'not-allowed' : 'pointer';
            nextBtn.style.opacity = currentPage >= totalPages ? '0.5' : '1';
            nextBtn.style.cursor = currentPage >= totalPages ? 'not-allowed' : 'pointer';
        }

        // Event listeners para paginación
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                displayPage();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayPage();
            }
        });

        // Inicializar tabla con todos los registros
        filteredRows = Array.from(socioRows);
        displayPage();

        // Event listeners para filtros en tiempo real
        searchInput.addEventListener('input', filterTable);
        filterPlan.addEventListener('change', filterTable);
        filterEstado.addEventListener('change', filterTable);
        filterEstadoCuenta.addEventListener('change', filterTable);

        // Limpiar filtros
        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            filterPlan.value = '';
            filterEstado.value = '';
            filterEstadoCuenta.value = '';
            filterTable();
        });

        // Función para desactivar socio
        function desactivarSocio(idSocio, nombreSocio) {
            if (confirm(`¿Estás seguro de dar de baja al socio ${nombreSocio}?`)) {
                cambiarEstadoSocio(idSocio, 'Inactivo');
            }
        }

        // Función para activar socio
        function activarSocio(idSocio, nombreSocio) {
            if (confirm(`¿Estás seguro de activar al socio ${nombreSocio}?`)) {
                cambiarEstadoSocio(idSocio, 'Activo');
            }
        }

        // Función AJAX para cambiar estado
        function cambiarEstadoSocio(idSocio, nuevoEstado) {
            const formData = new FormData();
            formData.append('id_socio', idSocio);
            formData.append('nuevo_estado', nuevoEstado);

            fetch('php/cambiar_estado_socio.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    </script>

  </body>
</html>
<?php $conexion->close(); ?>
