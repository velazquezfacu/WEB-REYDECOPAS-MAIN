<?php
session_start();

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['nombre'] : '';
$userPhoto = null;

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "reyescopas");

// Obtener foto de perfil si el usuario está logueado
if ($isLoggedIn && !$conexion->connect_error) {
    $stmt = $conexion->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $userPhoto = $row['foto_perfil'];
    }
    $stmt->close();
}

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener todas las experiencias
$sql = "SELECT * FROM experiencias ORDER BY id";
$resultado = $conexion->query($sql);

// Mapeo de imágenes por ID de experiencia
$imagenes = [
    1 => 'cancharoja.png',  // Recorrido Sagrado
    2 => 'museocopas.png',    // Viaje a la Gloria
    3 => 'tagliaalmuer.png'       // Almuerzo con Leyendas
];

$descripciones = [
    1 => 'Pisá el césped sagrado del Libertadores de América. Visitá vestuarios, túnel de salida y áreas VIP en un tour exclusivo de 75 minutos.',
    2 => 'Recorré nuestro museo y admirá las 7 Copas Libertadores. Camisetas históricas, fotos y trofeos que cuentan décadas de gloria.',
    3 => 'Compartí un almuerzo íntimo con una leyenda del club. Anécdotas, fotos, autógrafos y una experiencia que recordarás para siempre.'
];

$detalles = [
    1 => ['Duración: 60-75 min', 'Martes a Viernes', 'Acceso a zonas exclusivas'],
    2 => ['Sin límite de tiempo', 'Fines de semana', '20% descuento socios'],
    3 => ['Duración: 90 min', 'Evento mensual', 'Incluye comida y bebida']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiencias Exclusivas - Rey de Copas</title>
    <link rel="shortcut icon" href="images/escudocai.ico" type="image/x-icon">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/experiencias.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Belleza&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <li><a href="index.php#contacto">Contacto</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="user-menu">
                        <div class="user-avatar" id="user-avatar">
                            <?php if ($userPhoto && file_exists($userPhoto)): ?>
                                <img src="<?php echo htmlspecialchars($userPhoto); ?>" alt="Foto de perfil" class="navbar-user-photo">
                            <?php else: ?>
                                <i class='bx bxs-user-circle'></i>
                            <?php endif; ?>
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
            <li><a href="index.php#servicios">Servicios</a></li>
            <li><a href="experiencias.php">Experiencias</a></li>
            <?php if ($isLoggedIn): ?>
                <?php if (isset($_SESSION['nombre']) && $_SESSION['nombre'] === 'super'): ?>
                    <li><a href="admin.php"><i class='bx bxs-dashboard'></i> Panel Admin</a></li>
                <?php else: ?>
                    <li><a href="perfil.php"><i class='bx bxs-user'></i> Mi Perfil</a></li>
                <?php endif; ?>
                <li><a href="php/cerrarSesion.php"><i class='bx bx-log-out'></i> Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="php/chequeo.php">Cuenta</a></li>
            <?php endif; ?>
        </ul>
        <i class='bx bx-x' id="nav-close"></i>
    </div>

    <!-- Hero Section -->
    <section class="experiencias-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Experiencias Inolvidables</h1>
            <p class="hero-subtitle">Viví el Rey de Copas como nunca antes</p>
            <div class="hero-badge">
                <i class='bx bx-user-check'></i>
                <span>Disponible para todos los usuarios</span>
            </div>
        </div>
        <div class="scroll-indicator">
            <i class='bx bx-chevron-down'></i>
        </div>
    </section>

    <!-- Experiencias Grid -->
    <section class="experiencias-main">
        <div class="experiencias-grid">
            <?php
            $resultado->data_seek(0);
            while($exp = $resultado->fetch_assoc()):
                $id = $exp['id'];
            ?>
                <div class="exp-card" data-id="<?php echo $id; ?>">
                    <div class="exp-card-image">
                        <img src="images/<?php echo $imagenes[$id]; ?>" alt="<?php echo htmlspecialchars($exp['nombre_expe']); ?>">
                        <div class="exp-card-badge">
                            <i class='bx bx-star'></i>
                        </div>
                    </div>

                    <div class="exp-card-content">
                        <h2 class="exp-card-title"><?php echo htmlspecialchars($exp['nombre_expe']); ?></h2>
                        <p class="exp-card-description"><?php echo $descripciones[$id]; ?></p>

                        <div class="exp-card-details">
                            <?php foreach($detalles[$id] as $detalle): ?>
                                <div class="exp-detail-item">
                                    <i class='bx bx-check-circle'></i>
                                    <span><?php echo $detalle; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="exp-card-footer">
                            <div class="exp-price">
                                <span class="price-label">Desde</span>
                                <span class="price-amount">$<?php echo number_format($exp['costo_expe'], 0, ',', '.'); ?></span>
                            </div>

                            <?php if ($isLoggedIn): ?>
                                <button class="exp-btn-reservar" onclick="abrirModalReserva(<?php echo $id; ?>, '<?php echo htmlspecialchars($exp['nombre_expe']); ?>', <?php echo $exp['costo_expe']; ?>, '<?php echo $imagenes[$id]; ?>')">
                                    <span>Reservar Ahora</span>
                                    <i class='bx bx-right-arrow-alt'></i>
                                </button>
                            <?php else: ?>
                                <a href="php/chequeo.php?redirect_url=experiencias.php" class="exp-btn-reservar">
                                    <span>Iniciar Sesión</span>
                                    <i class='bx bx-log-in'></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="exp-card-capacity">
                            <i class='bx bx-group'></i>
                            <span>Cupo limitado: <?php echo $exp['cupo_max']; ?> personas por fecha</span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Modal Moderno -->
    <div id="modalReserva" class="modal-overlay">
        <div class="modal-container">
            <button class="modal-close" onclick="cerrarModalReserva()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-icon">
                        <i class='bx bx-calendar-star'></i>
                    </div>
                    <h2 id="modal_titulo">Reservar Experiencia</h2>
                    <p id="modal_nombre"></p>
                </div>

                <div class="modal-image-container">
                    <img id="modal_imagen" src="" alt="">
                </div>

                <form id="formReserva" class="modal-form">
                    <input type="hidden" id="id_experiencia" name="id_experiencia">

                    <div class="modal-info-box">
                        <div class="info-item">
                            <i class='bx bx-money'></i>
                            <div>
                                <span class="info-label">Precio</span>
                                <span class="info-value" id="modal_precio"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_experiencia">
                            <i class='bx bx-calendar'></i>
                            Seleccioná la fecha
                        </label>
                        <input type="date" id="fecha_experiencia" name="fecha_experiencia" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>

                    <div id="mensaje-reserva" class="modal-message"></div>

                    <button type="submit" class="modal-submit-btn">
                        <i class='bx bx-check-circle'></i>
                        <span>Confirmar Reserva</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-logo-container">
            <img src="./images/Reyes de copas.png" alt="">
        </div>
        <div class="footer-box-container">
            <div class="footer-box">
                <h4>Rey de Copas - Club de socios</h4>
                <p>El infierno esta encantador</p>
            </div>
            <div class="footer-box">
                <h4>Sede Social</h4>
                <p>Diego A. Milito, Avellaneda</p>
            </div>
            <div class="footer-box">
                <h4>Contacto</h4>
                <p>lorem@ipsum.com</p>
                <p>11-1234-5678</p>
            </div>
        </div>
    </footer>

    <script>
        // Nav Menu
        const navMenu = document.getElementById('nav-menu'),
              navToggle = document.getElementById('nav-toggle'),
              navClose = document.getElementById('nav-close')

        if(navToggle) navToggle.addEventListener('click', () => navMenu.classList.add('show-menu'))
        if(navClose) navClose.addEventListener('click', () => navMenu.classList.remove('show-menu'))

        // User Dropdown
        const userAvatar = document.getElementById('user-avatar');
        const userDropdown = document.getElementById('user-dropdown');
        if (userAvatar && userDropdown) {
            userAvatar.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });
            document.addEventListener('click', (e) => {
                if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }

        // Scroll Indicator
        document.querySelector('.scroll-indicator')?.addEventListener('click', () => {
            document.querySelector('.experiencias-main').scrollIntoView({ behavior: 'smooth' });
        });

        // Modal Functions
        function abrirModalReserva(id, nombre, precio, imagen) {
            document.getElementById('id_experiencia').value = id;
            document.getElementById('modal_nombre').textContent = nombre;
            document.getElementById('modal_precio').textContent = '$' + new Intl.NumberFormat('es-AR').format(precio);
            document.getElementById('modal_imagen').src = 'images/' + imagen;
            document.getElementById('modalReserva').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalReserva() {
            document.getElementById('modalReserva').classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('formReserva').reset();
            document.getElementById('mensaje-reserva').textContent = '';
            document.getElementById('mensaje-reserva').className = 'modal-message';
        }

        // Form Submit
        $('#formReserva').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i><span>Procesando...</span>');

            $.ajax({
                type: 'POST',
                url: 'php/procesar_reserva.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    const mensaje = $('#mensaje-reserva');
                    mensaje.text(response.message);

                    if (response.success) {
                        mensaje.removeClass('error').addClass('success');
                        setTimeout(() => {
                            window.location.href = 'perfil.php#mis-reservas';
                        }, 1500);
                    } else {
                        mensaje.removeClass('success').addClass('error');
                        btn.prop('disabled', false).html('<i class="bx bx-check-circle"></i><span>Confirmar Reserva</span>');
                    }
                },
                error: function() {
                    $('#mensaje-reserva').text('Error al procesar la reserva').removeClass('success').addClass('error');
                    btn.prop('disabled', false).html('<i class="bx bx-check-circle"></i><span>Confirmar Reserva</span>');
                }
            });
        });

        // Escape key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') cerrarModalReserva();
        });
    </script>
</body>
</html>
<?php $conexion->close(); ?>
