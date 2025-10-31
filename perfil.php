<?php
    session_start();

    // 1. Proteger la página: si no hay sesión, redirigir al login
    if (!isset($_SESSION['user_id'])) { // Cambiado a user_id para mayor consistencia
        header("Location: sesion.php"); // Redirige a sesion.php en la raíz
        exit();
    }

    // 2. Incluir el script que obtiene los datos del perfil
    include 'php/func_perfil.php';

    // 3. Obtener las reservas del usuario
    $conexion = new mysqli("localhost", "root", "", "reyescopas");
    $sql_reservas = "SELECT r.*, e.nombre_expe, e.costo_expe
                     FROM reservas_experiencias r
                     INNER JOIN experiencias e ON r.id_experiencia = e.id
                     WHERE r.id_usuario = ?
                     ORDER BY r.fecha_experiencia DESC";
    $stmt_reservas = $conexion->prepare($sql_reservas);
    $stmt_reservas->bind_param("i", $_SESSION['user_id']);
    $stmt_reservas->execute();
    $reservas = $stmt_reservas->get_result();
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
    <link rel="stylesheet" href="./styles/perfil-tabs.css">
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
        <a href="index.php#home" class="logo"><img src="images/Reyes de copas.png" alt=""></a>
        <nav>
            <ul class="navbar">                
                <li><a href="index.php#home">Inicio</a></li>
                <li><a href="index.php#servicios">Servicios</a></li>
                <li><a href="index.php#recetas">Ustedes</a></li>
                <li><a href="experiencias.php">Experiencias</a></li>
                <li><a href="php/cerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="index.php#home">Inicio</a></li>
            <li><a href="index.php#servicios">Servicios</a></li>
            <li><a href="index.php#recetas">Ustedes</a></li>
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
            <h1 class="perfil-title">Te damos la Bienvenida, <?php echo htmlspecialchars($nombreusuario); ?></h1>
            <p class="perfil-text">Desde aquí podrás gestionar tu cuenta, ver tus datos y acceder a tus reservas.</p>

            <!-- Tabs Navigation -->
            <div class="tabs-container">
                <div class="tabs-nav">
                    <button class="tab-btn active" data-tab="datos">
                        <i class='bx bx-user'></i>
                        <span>Mis Datos</span>
                    </button>
                    <button class="tab-btn" data-tab="reservas">
                        <i class='bx bx-calendar'></i>
                        <span>Mis Reservas</span>
                        <?php if ($reservas->num_rows > 0): ?>
                            <span class="tab-badge"><?php echo $reservas->num_rows; ?></span>
                        <?php endif; ?>
                    </button>
                </div>

                <!-- Tab Content: Datos -->
                <div class="tab-content active" id="tab-datos">
                    <form action="php/modificar.php" method="POST" class="perfil-form">
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
                            <button type="submit" class="btn custom-btn btn-form">
                                <i class='bx bx-save'></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab Content: Reservas -->
                <div class="tab-content" id="tab-reservas">
                    <div class="reservas-header">
                        <h3 class="form-section-title">Mis Reservas de Experiencias</h3>
                        <a href="experiencias.php" class="btn-nueva-reserva">
                            <i class='bx bx-plus-circle'></i>
                            <span>Nueva Reserva</span>
                        </a>
                    </div>

                    <?php if ($reservas->num_rows > 0): ?>
                        <div class="reservas-grid">
                            <?php
                            $reservas->data_seek(0);
                            while($reserva = $reservas->fetch_assoc()):
                                $esFutura = strtotime($reserva['fecha_experiencia']) > time();
                                $estadoClass = $reserva['estado'];
                            ?>
                                <div class="reserva-card-modern <?php echo $estadoClass; ?>">
                                    <div class="reserva-card-header">
                                        <div class="reserva-icon">
                                            <i class='bx <?php echo $reserva['estado'] == 'confirmada' ? 'bx-check-circle' : ($reserva['estado'] == 'cancelada' ? 'bx-x-circle' : 'bx-time-five'); ?>'></i>
                                        </div>
                                        <span class="reserva-badge <?php echo $estadoClass; ?>">
                                            <?php
                                                $estados = ['confirmada' => 'Confirmada', 'pendiente' => 'Pendiente', 'cancelada' => 'Cancelada'];
                                                echo $estados[$reserva['estado']];
                                            ?>
                                        </span>
                                    </div>

                                    <div class="reserva-card-body">
                                        <h3 class="reserva-titulo"><?php echo htmlspecialchars($reserva['nombre_expe']); ?></h3>

                                        <div class="reserva-info-grid">
                                            <div class="info-item">
                                                <i class='bx bx-calendar-event'></i>
                                                <div>
                                                    <span class="info-label">Fecha de experiencia</span>
                                                    <span class="info-value"><?php echo date('d/m/Y', strtotime($reserva['fecha_experiencia'])); ?></span>
                                                </div>
                                            </div>

                                            <div class="info-item">
                                                <i class='bx bx-money'></i>
                                                <div>
                                                    <span class="info-label">Costo</span>
                                                    <span class="info-value">$<?php echo number_format($reserva['costo_expe'], 0, ',', '.'); ?></span>
                                                </div>
                                            </div>

                                            <div class="info-item">
                                                <i class='bx bx-time'></i>
                                                <div>
                                                    <span class="info-label">Reservado el</span>
                                                    <span class="info-value"><?php echo date('d/m/Y', strtotime($reserva['fecha_reserva'])); ?></span>
                                                </div>
                                            </div>

                                            <div class="info-item">
                                                <i class='bx bx-info-circle'></i>
                                                <div>
                                                    <span class="info-label">Estado</span>
                                                    <span class="info-value"><?php echo $esFutura ? 'Próxima' : 'Realizada'; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($reserva['estado'] == 'confirmada' && $esFutura): ?>
                                        <div class="reserva-card-footer">
                                            <button class="btn-cancelar-moderna" onclick="cancelarReserva(<?php echo $reserva['id']; ?>)">
                                                <i class='bx bx-trash'></i>
                                                <span>Cancelar Reserva</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class='bx bx-calendar-x'></i>
                            </div>
                            <h3>No tenés reservas aún</h3>
                            <p>Explorá nuestras experiencias exclusivas y reservá tu lugar</p>
                            <a href="experiencias.php" class="btn-explorar">
                                <i class='bx bx-compass'></i>
                                <span>Explorar Experiencias</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
     </section>
    
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Switching
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabName = btn.getAttribute('data-tab');

                    // Remove active class from all
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked
                    btn.classList.add('active');
                    document.getElementById(`tab-${tabName}`).classList.add('active');
                });
            });

            // Check URL hash for direct tab access
            const hash = window.location.hash;
            if (hash === '#mis-reservas') {
                document.querySelector('[data-tab="reservas"]').click();
            }

            // Script para permitir solo números en el campo de teléfono
            const phoneInput = document.getElementById('telefono');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
        });

        // Función para cancelar reserva
        function cancelarReserva(idReserva) {
            if (!confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'php/cancelar_reserva.php',
                data: { id_reserva: idReserva },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error al cancelar la reserva');
                }
            });
        }
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
</body>
</html>