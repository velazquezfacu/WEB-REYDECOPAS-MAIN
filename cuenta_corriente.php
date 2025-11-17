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

// Obtener lista de socios para el selector
$sql_socios = "SELECT s.id, s.nro_socio, u.nombre, u.apellido
               FROM socio s
               JOIN usuarios u ON s.id_usua = u.id
               WHERE s.estado = 'Activo'
               ORDER BY s.nro_socio";
$result_socios = $conexion->query($sql_socios);
$socios = [];
while($row = $result_socios->fetch_assoc()) {
    $socios[] = $row;
}

// Obtener datos de cuenta corriente y cuotas de todos los socios
$sql_cuentas = "SELECT
                    s.id as id_socio,
                    s.nro_socio,
                    u.nombre,
                    u.apellido,
                    p.nombre_plan,
                    p.costo_plan,
                    cc.saldo,
                    cc.estado as estado_cuenta,
                    cc.fech_pago as fecha_ultimo_pago,
                    (SELECT COUNT(*) FROM cuotas WHERE id_socio = s.id) as total_cuotas,
                    (SELECT COUNT(*) FROM cuotas WHERE id_socio = s.id AND estado = 'pagada') as cuotas_pagadas,
                    (SELECT COUNT(*) FROM cuotas WHERE id_socio = s.id AND estado = 'pendiente') as cuotas_pendientes
                FROM socio s
                JOIN usuarios u ON s.id_usua = u.id
                JOIN planes p ON s.id_plan = p.id
                LEFT JOIN cuenta_cte cc ON s.id = cc.id_soc
                WHERE s.estado = 'Activo'
                ORDER BY s.nro_socio";
$result_cuentas = $conexion->query($sql_cuentas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Cuenta Corriente - Panel de Administración</title>
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

         /* Estilos para el detalle de cuotas */
         .cuotas-detalle {
             display: none;
             margin-top: 1rem;
             padding: 1.5rem;
             background: rgba(255, 255, 255, 0.05);
             border-radius: 10px;
             border-left: 4px solid #e74c3c;
         }

         .cuotas-detalle.active {
             display: block;
         }

         .cuotas-detalle table {
             width: 100%;
             margin-top: 1rem;
         }

         .ver-detalle-btn {
             padding: 0.4rem 0.8rem;
             border-radius: 6px;
             border: none;
             background: linear-gradient(135deg, #3498db, #2980b9);
             color: white;
             font-family: 'Poppins', sans-serif;
             font-weight: 500;
             cursor: pointer;
             font-size: 0.85rem;
             transition: all 0.3s;
         }

         .ver-detalle-btn:hover {
             transform: scale(1.05);
             box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
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

         /* Ajuste de tabla para scroll horizontal en zoom */
         .activity-table-container {
             max-height: calc(100vh - 450px);
             overflow-y: auto;
         }

         /* Estilos para scrollbar de la tabla */
         .activity-table-container::-webkit-scrollbar {
             width: 8px;
             height: 8px;
         }

         .activity-table-container::-webkit-scrollbar-track {
             background: rgba(255, 255, 255, 0.1);
             border-radius: 10px;
         }

         .activity-table-container::-webkit-scrollbar-thumb {
             background: rgba(231, 76, 60, 0.8);
             border-radius: 10px;
         }

         .activity-table-container::-webkit-scrollbar-thumb:hover {
             background: rgba(231, 76, 60, 1);
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
                <a href="cuenta_corriente.php" class="sidebar-module active">
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

    <section class="perfil-section" id="home" style="padding: 100px 5% 20px;">
        <div class="perfil-container" style="max-width: 95%; margin-bottom: 20px; padding: 2rem;">
            <h1 class="perfil-title">Cuenta Corriente</h1>
            <p class="perfil-text">Gestión de cuotas y pagos de socios.</p>

            <div class="tabs-container">
                <div class="tab-content active">
                    <div class="perfil-form">
                        <h3 class="form-section-title"><strong>ESTADO DE CUENTAS</strong></h3>

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
                                    <i class='bx bx-filter'></i> Filtrar por Estado
                                </label>
                                <select id="filterEstado" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.05); color: white; font-family: 'Poppins', sans-serif;">
                                    <option value="">Todos</option>
                                    <option value="Pagado">Al día</option>
                                    <option value="Pendiente">Con deuda</option>
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
                                        <th>Plan</th>
                                        <th>Costo Mensual</th>
                                        <th>Total Cuotas</th>
                                        <th>Cuotas Pagadas</th>
                                        <th>Cuotas Pendientes</th>
                                        <th>Saldo Actual</th>
                                        <th>Estado</th>
                                        <th>Último Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result_cuentas->num_rows > 0): ?>
                                        <?php while($cuenta = $result_cuentas->fetch_assoc()): ?>
                                            <tr class="cuenta-row"
                                                data-nro="<?php echo htmlspecialchars($cuenta['nro_socio']); ?>"
                                                data-nombre="<?php echo htmlspecialchars(strtolower($cuenta['nombre'] . ' ' . $cuenta['apellido'])); ?>"
                                                data-estado="<?php echo htmlspecialchars($cuenta['estado_cuenta']); ?>"
                                                data-id-socio="<?php echo $cuenta['id_socio']; ?>">
                                                <td><?php echo htmlspecialchars($cuenta['nro_socio']); ?></td>
                                                <td><?php echo htmlspecialchars($cuenta['nombre'] . ' ' . $cuenta['apellido']); ?></td>
                                                <td><?php echo htmlspecialchars($cuenta['nombre_plan']); ?></td>
                                                <td>$<?php echo number_format($cuenta['costo_plan'], 0, ',', '.'); ?></td>
                                                <td><?php echo $cuenta['total_cuotas']; ?></td>
                                                <td style="color: #2ecc71; font-weight: 600;"><?php echo $cuenta['cuotas_pagadas']; ?></td>
                                                <td style="color: #e74c3c; font-weight: 600;"><?php echo $cuenta['cuotas_pendientes']; ?></td>
                                                <td>$<?php echo number_format($cuenta['saldo'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <span class="activity-badge <?php echo $cuenta['estado_cuenta'] === 'Pagado' ? 'socio' : 'reserva'; ?>">
                                                        <?php echo $cuenta['estado_cuenta'] === 'Pagado' ? 'Al día' : 'Pendiente'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $cuenta['fecha_ultimo_pago'] ? date('d/m/Y', strtotime($cuenta['fecha_ultimo_pago'])) : 'Sin pagos'; ?></td>
                                                <td>
                                                    <button class="ver-detalle-btn" onclick="toggleDetalle(<?php echo $cuenta['id_socio']; ?>)">
                                                        <i class='bx bx-show'></i> Ver Detalle
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Fila de detalle de cuotas (oculta por defecto) -->
                                            <tr id="detalle-<?php echo $cuenta['id_socio']; ?>" class="cuotas-detalle">
                                                <td colspan="11">
                                                    <h4 style="color: white; margin-bottom: 1rem;">
                                                        <i class='bx bx-list-ul'></i> Detalle de Cuotas - <?php echo htmlspecialchars($cuenta['nombre'] . ' ' . $cuenta['apellido']); ?>
                                                    </h4>
                                                    <div id="cuotas-content-<?php echo $cuenta['id_socio']; ?>">
                                                        <p style="color: rgba(255, 255, 255, 0.7);">Cargando...</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" style="text-align: center; color: rgba(255, 255, 255, 0.7);">No hay cuentas registradas</td>
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

        // Sistema de filtros y paginación para tabla de cuentas
        const searchInput = document.getElementById('searchInput');
        const filterEstado = document.getElementById('filterEstado');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const cuentaRows = document.querySelectorAll('.cuenta-row');

        // Variables de paginación
        const rowsPerPage = 15;
        let currentPage = 1;
        let filteredRows = [];

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedEstado = filterEstado.value;

            filteredRows = [];

            cuentaRows.forEach(row => {
                const nroSocio = row.getAttribute('data-nro').toLowerCase();
                const nombre = row.getAttribute('data-nombre');
                const estado = row.getAttribute('data-estado');

                // Búsqueda por número o nombre
                const matchesSearch = nroSocio.includes(searchTerm) || nombre.includes(searchTerm);

                // Filtros de selección
                const matchesEstado = !selectedEstado || estado === selectedEstado;

                // Agregar a filteredRows si cumple con los filtros
                if (matchesSearch && matchesEstado) {
                    filteredRows.push(row);
                }
            });

            currentPage = 1;
            displayPage();
        }

        function displayPage() {
            // Ocultar todas las filas y detalles primero
            cuentaRows.forEach(row => {
                row.style.display = 'none';
                const idSocio = row.getAttribute('data-id-socio');
                const detalleRow = document.getElementById('detalle-' + idSocio);
                if (detalleRow) {
                    detalleRow.style.display = 'none';
                }
            });

            // Calcular índices de inicio y fin
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            // Mostrar solo las filas de la página actual
            filteredRows.slice(startIndex, endIndex).forEach(row => {
                row.style.display = '';
                // Si el detalle estaba activo, mostrarlo también
                const idSocio = row.getAttribute('data-id-socio');
                const detalleRow = document.getElementById('detalle-' + idSocio);
                if (detalleRow && detalleRow.classList.contains('active')) {
                    detalleRow.style.display = '';
                }
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
        filteredRows = Array.from(cuentaRows);
        displayPage();

        // Event listeners para filtros en tiempo real
        searchInput.addEventListener('input', filterTable);
        filterEstado.addEventListener('change', filterTable);

        // Limpiar filtros
        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            filterEstado.value = '';
            filterTable();
        });

        // Función para mostrar/ocultar detalle de cuotas
        function toggleDetalle(idSocio) {
            console.log('toggleDetalle called with idSocio:', idSocio);
            const detalleRow = document.getElementById('detalle-' + idSocio);
            const contentDiv = document.getElementById('cuotas-content-' + idSocio);

            console.log('detalleRow:', detalleRow);
            console.log('contentDiv:', contentDiv);

            if (!detalleRow) {
                console.error('No se encontró la fila de detalle para socio ID:', idSocio);
                return;
            }

            if (detalleRow.classList.contains('active')) {
                detalleRow.classList.remove('active');
                detalleRow.style.display = 'none';
            } else {
                // Cerrar otros detalles abiertos
                document.querySelectorAll('.cuotas-detalle.active').forEach(el => {
                    el.classList.remove('active');
                    el.style.display = 'none';
                });

                detalleRow.classList.add('active');
                detalleRow.style.display = '';

                // Cargar cuotas si aún no se han cargado
                if (contentDiv && contentDiv.innerHTML.includes('Cargando')) {
                    console.log('Cargando cuotas...');
                    cargarCuotas(idSocio);
                }
            }
        }

        // Función para cargar cuotas por AJAX
        function cargarCuotas(idSocio) {
            const contentDiv = document.getElementById('cuotas-content-' + idSocio);

            fetch('php/obtener_cuotas.php?id_socio=' + idSocio)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<table class="activity-table" style="margin: 0;">';
                        html += '<thead><tr>';
                        html += '<th>Nro Cuota</th>';
                        html += '<th>Monto</th>';
                        html += '<th>Fecha Vencimiento</th>';
                        html += '<th>Estado</th>';
                        html += '<th>Fecha Pago</th>';
                        html += '<th>Método Pago</th>';
                        html += '</tr></thead><tbody>';

                        if (data.cuotas.length > 0) {
                            data.cuotas.forEach(cuota => {
                                html += '<tr>';
                                html += '<td>' + cuota.numero_cuota + '</td>';
                                html += '<td>$' + new Intl.NumberFormat('es-AR').format(cuota.monto) + '</td>';
                                html += '<td>' + cuota.fecha_vencimiento + '</td>';
                                html += '<td><span class="activity-badge ' + (cuota.estado === 'pagada' ? 'socio' : 'reserva') + '">' + cuota.estado + '</span></td>';
                                html += '<td>' + (cuota.fecha_pago || '-') + '</td>';
                                html += '<td>' + (cuota.metodo_pago || '-') + '</td>';
                                html += '</tr>';
                            });
                        } else {
                            html += '<tr><td colspan="6" style="text-align: center; color: rgba(255, 255, 255, 0.7);">No hay cuotas registradas</td></tr>';
                        }

                        html += '</tbody></table>';
                        contentDiv.innerHTML = html;
                    } else {
                        contentDiv.innerHTML = '<p style="color: #e74c3c;">Error al cargar las cuotas</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    contentDiv.innerHTML = '<p style="color: #e74c3c;">Error al cargar las cuotas</p>';
                });
        }
    </script>

  </body>
</html>
<?php $conexion->close(); ?>
