<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['nombre'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club de Socios C.A.I</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/escudocai.ico" type="image/x-icon">
    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/contacto.css">
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
                <li><a href="#home">Inicio</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#recetas">Ustedes</a></li>
                <li><a href="experiencias.php">Experiencias</a></li>
                <li><a href="#contacto">Contacto</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="user-menu">
                        <div class="user-avatar" id="user-avatar">
                            <i class='bx bxs-user-circle'></i>
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
            <li><a href="#home">Inicio</a></li>
            <li><a href="#servicios">Servicios</a></li>
            <li><a href="#recetas">Ustedes</a></li>
            <li><a href="#contacto">Contacto</a></li>
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

    <!-- WhatsApp Me -->
    <div id="whatsapp-button" class="whatsapp-button">
        <a href="https://wa.me/+5491138204268" target="_blank">
            <img src="images/wasap.png" alt="WhatsApp">
        </a>
    </div>

    <section class="modal">
        <div class="modal-container">
            <div class="receta-img-container">
                <img src="" alt="" class="modal-img">
            </div>
            <h1 class="receta-title"></h1>
            <div class="ingredientes-container-receta">
                <h4>Ingredientes:</h4>
                <ul class="lista-ingredientes">
                    <li></li>
                </ul>
            </div>
            <div class="preparacion-container">
                <h4>Preparacion:</h4>
                <ul class="lista-pasos">
                    <li></li>
                </ul>
            </div>

            <a href="" class="modal-close">Volver atras</a>
            
        </div>
    </section>

    <!-- Home Section Start -->
    <section class="home" id="home">
        <div class="home-container">
            <h1 class="home-title"></h1>
            <p class="home-text">¡Bienvenido! Accedé para conocer todo los beneficios que el club puede otorgale al socio.</p>
        </div>

        <!-- Go Top Button -->

        <div class="go-top-container">
            <div class="go-top-button">
                <i class='bx bx-chevron-up'></i>
            </div>
        </div>

        <!-- Go Bot Button -->

        <div class="go-bot-button">
            <i class='bx bx-chevron-down go-bot'></i>
        </div>
        <div class="home-bot-shape">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>
    <!-- About Section End-->

    <!-- Servicios Section Start -->

    <!--Seccion de descripción -->
    <section class="about" id="about">
        <div class="about-description">
            <div class="about-item">
                <div class="text-description">
                    <h1>Nosotros</h1>
                    <h4 class="padbot">Ricardo Enrique Bochini 751, Avellaneda, Provincia de Buenos Aires</h4>
    
                    <p class="padbot">Somos la comunidad más grande y apasionada de hinchas del Club Atlético Independiente, la base de lo que históricamente se conoce como el <b>"Rey de Copas"</b>. Nuestra identidad se forjó a base de glorias internacionales inigualables. Como socios, ofrecemos a nuestros miembros acceso exclusivo a todos los partidos en nuestro emblemático <b>Estadio Libertadores de América</b>, <b>participación activa en la vida social y deportiva</b> y la certeza de pertenecer a una familia de valores, orgullo y pertenencia.</p>
                    
                    <p class="padbot">Nuestra fuerza radica en el número y el compromiso. Actualmente, superamos <b>el millón de socios y socias</b> dispersos por todo el mundo, lo que nos convierte en una de las instituciones con mayor caudal social a nivel global. No solo brindamos respaldo económico para el crecimiento del club, sino que también garantiza que cada decisión y cada proyecto deportivo cuente con el apoyo de una comunidad inquebrantable.</p>
    
                    <p>Al ser socio, no solo obténes beneficios como <b>descuentos en indumentaria oficial</b> y <b>prioridad en la compra de entradas</b>, sino que te convertís en <b>dueño y custodio de un legado</b> que se transmite de generación en generación. Te invitamos a ser parte activa de esta historia, a sumarte a los más de un millon de corazones que laten al ritmo del "Orgullo Nacional" y a ayudarnos a escribir capítulos de gloria que forjaran nuestro futuro como la institución deportiva más prestigiosa.</p>
    
                </div>
            </div>
            <div class="about-item">
                <div class="image-container">
                    <div class="image-description">
                        <img src="./images/hinchassocios2.png" alt="">
                    </div>
                    <div class="image-description">
                        <img src="./images/hinchassocios.png" alt="">
                    </div>
                    <div class="image-description">
                        <img src="./images/hinchassocios3.png" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="about-instagram">
            <div class="image-instagram">
                <img src="./images/igclub.png" alt="">
            </div>
            <div class="text-instagram">
                <h3>No te pierdas nada de la pasión roja!</h3>
                <p class="padbot">Queremos invitarte a ser parte de nuestra vibrante comunidad en <b>Instagram</b>, donde la pasión por el "Rey de Copas" cobra vida cada día. En nuestra cuenta oficial <b>"Club Atlético Independiente Socios"</b>, encontrarás un acceso directo al corazón de nuestro club. Compartimos contenido exclusivo que te mantendrá al tanto de cada momento glorioso, desde los preparativos de los partidos y las noticias más relevantes de nuestro primer equipo. !Seguinos ahora y llevá la bandera roja y blanca en tu feed! Tu apoyo en redes también suma y nos ayuda a mostrar al mundo la grandeza del <b>"Orgullo Nacional"</b>.</p>
            </div>
        </div>

        <div class="about-bot-shape">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>
    <!-- About Section End-->

    <section class="servicios" id="servicios">
        <h1 class="servicios-title padbot">Afiliaciones</h1>
        <p>EL CARNET DE SOCIO SERÁ ENTREGADO EN MANO UNA VEZ INSCRIPTO.</p>
        <div class="servicios-container">
            <div class="servicios-item">
                <div class="item-description">
                    <img src="./images/chat.webp" alt="">
                    <h3>Plan Básico: Espíritu Rojo</h3>
                </div>
                <div class="item-text">
                    <p>Este plan es la puerta de entrada para todo hincha que quiera respaldar al club y ser oficialmente parte de la familia del Rey de Copas. <br>
                       <b>Costo:</b> $25.000 ARS <br>
                       <b>Renovación:</b> Mensual <br>       
                    </p>
                    <h3><b>Beneficios Principales:</b></h3>
                    <ul>
                        <li><b>-Carnet de Socio oficial</b> del C.A.I</li>
                        <li><b>-Voz y Voto</b> en Asambleas y elecciones.</li>
                        <li><b>-Prioridad</b> para la compra de entradas y abonos.</li>
                    </ul>
                    
                </div>
                <a class="item-turno" href="sesion.php?redirect_url=altasocio.php">¡Quiero ser socio!</a>
            </div>
            <div class="servicios-item">
                <div class="item-description">
                    <img src="./images/seguimiento.webp" alt="">
                    <h3>Plan Intermedio: Pasión Eterna</h3>
                </div>
                <div class="item-text">
                    <p>Diseñado para el hincha que quiere más presencia y beneficios tangibles, ofreciendo una mejor relación costo-beneficio para el uso de las instalaciones. <br>
                    <b>Costo:</b> $33.000 ARS <br>
                    <b>Renovación:</b> Mensual <br>                    
                    </p>
                    <h3><b>Beneficios Adicionales: (incluye todos los del Plan Básico)</b></h3>
                    <ul>
                        <li><b>-Descuento del 10%</b> en la cuota de la Sede Social y en las actividades amateur.</li>
                        <li><b>-Acceso Gratuito</b> a partidos de divisiones inferiores y reserva.</li>
                        <li><b>-Prioridad de Nivel 2</b> para la compra de tickets.</li>
                    </ul>
                </div>

                <a class="item-turno" href="sesion.php?redirect_url=altasocio.php">¡Quiero ser socio!</a>
            </div>
            <div class="servicios-item">
                <div class="item-description">
                    <img src="./images/inbody.webp" alt="">
                    <h3>Plan Premium: Orgullo Nacional</h3>
                </div>
                <div class="item-text">
                    <p>El plan ideal para el socio que vive en club al máximo y busca asegurar su lugar en el estadio con importantes descuentos en merchandising y acceso preferencial. <br>
                        <b>Costo:</b> $50.000 ARS <br>
                        <b>Renovación:</b> Mensual <br>                    
                    </p>
                    <h3><b>Beneficios Adicionales: (incluye todos los anteriores)</b></h3>
                    <ul>
                        <li><b>-Descuento del 20%</b> en la cuota de la Sede Social y actividades.</li>
                        <li><b>-Descuento fijo del 15%</b> en la tienda oficial del club.</li>
                        <li><b>-Acceso preferencial</b> a preventas de tickets para copas internacionales.</li>
                    </ul>
                                         
                </div>
                <a class="item-turno" href="sesion.php?redirect_url=altasocio.php">¡Quiero ser socio!</a>
            </div>

            <h2>Experiencias!</h2>
       
            <div class="servicios-item">
                <div class="item-description">
                    <img src="./images/metabolismo.webp" alt="">
                    <h3>Recorrido Sagrado: Pisá el Libertadores de América</h3>
                </div>
                <div class="item-text">
                    <p>Es tu oportunidad única de vivir el estadio como nunca antes. Nuestro tour guiado te lleva a través de los rincones del <b>Estadio Libertadores de América</b>. Con acceso a los <b>vestuarios locales</b>, la <b>Sala de prensa</b>, el <b>túnel de salida</b> y más!</p>
                    <ul>
                        <li><b>-Duración Aproximada:</b> 60 - 75 minutos.</li>
                        <li><b>-Precio: $40.000 ARS</b> por persona</li>
                        <li><b>-Reservas:</b> Turnos disponibles de Martes a Viernes.</li>
                    </ul>
                </div>
                <a class="item-turno" href="php/chequeo.php?redirect_url=experiencias.php">Reservar</a>
            </div>
            <div class="servicios-item">
                <div class="item-description">
                    <img src="./images/corporate.webp" alt="">
                    <h3>Viaje a la Gloria: Museo C.A.I</h3>
                </div>
                <div class="item-text">
                    <p>Adentrate en la historia que nos convirtió en el "Orgullo Nacional". El museo del Club Atlético Independiente exhibe con orgullo la colección más grande de trofeos de la Copa Libertadores, las <b>siete copas</b> que definen nuestra identidad. Podrás admirar camisetas históricas y revivir los momentos más épicos de los títulos locales e internacionales.</p>
                    <ul>
                        <li><b>-Duración Aproximada:</b> Sin límite de tiempo (tiempo sugerido 45 min).</li>
                        <li><b>-Precio: $20.000 ARS</b> por persona (Socios tienen 20% de descuento).</li>
                        <li><b>-Reservas:</b> Abierto todo el fin de semana.</li>
                    </ul>
                </div>
                <a class="item-turno" href="php/chequeo.php?redirect_url=experiencias.php">Reservar</a>
            </div>
            <div class="servicios-item">
                <div class="item-description">
                    <img src="./images/grocery.webp" alt="">
                    <h3>VIP: Almuerzo con Leyendas</h3>
                </div>
                <div class="item-text">
                    <p>Una experiencia única e inolvidable para el hincha que busca exclusividad. Te invitamos a compartir un <b>almuerzo privado</b> en un sector VIP junto a <b>Nicolás Tagliafico</b>. Podrás escuchar anécdotas únicas de primera mano, hacer preguntas y llevarte una foto y autógrafo en un ambiente íntimo. Esta oportunidad limitada de conectar personalmente con la historia viva del club.</p>
                    <ul>
                        <li><b>-Duración Aproximada:</b> 90 minutos.</li>
                        <li><b>-Precio: $100.000 ARS</b> por persona (incluye comida y bebida).</li>
                        <li><b>-Reservas:</b> Evento exclusivo con cupos muy limitados, solo disponible una vez al mes.</li>
                    </ul>
                </div>
                <a class="item-turno" href="php/chequeo.php?redirect_url=experiencias.php">Reservar</a>
            </div>
        </div>

        <div class="servicios-shape">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
        </section>

    <!-- Servicios Section End -->

    <!-- Recetas Section Start -->

    <section class="recetas" id="recetas">
        <h1 class="recetas-title">Ustedes</h1>
        <p>La satisfacción y el profundo sentido de pertenencia que miles de socios ya experimenten en cada partido o actividad es la prueba más clara de que ser parte del Rey de Copas es inigualable. Más allá de ver fútbol, te sumergís en una comunidad vibrante donde cada cuota se convierte en historia y futuro deportivo. Te ofrecemos la oportunidad de ser dueño de este inmenso legado y participar en su grandeza. <b>¡Es el momento de ser socio y escribir tu propio capítulo de gloria en el Club Atlético Independiente!</b></p>
        <div class="container">

            <div class="card">
                <div class="img-container">
                    <img src="./images/jubiladasinde.png" alt="" class="img-receta">
                </div>
                <div class="content">
                    <h5>Visita a la cancha</h5>
                    <h3>"Ana Morales"</h3>
                    <p>¡Super Emocionante! fue una salida de diez, El estadio es hermoso, el trato de los chicos super amable. Es un paseo divino, se los recomiendo a todas ...</p>
                </div>
            </div>

            <div class="card">
                <div class="img-container">
                    <img src="./images/nacidoinde.png" alt="" class="img-receta">
                </div>
                <div class="content">
                    <h5>Salón de trofeos</h5>
                    <h3>"Gustavo Rodriguez"</h3>
                    <p>¡Que barbaridad! Fui al museo y me emocioné como un nene. Ver esas siete Copas Libertadores, una hermosura. Y lo mejor de todo es que te dejan verlas bien, sentis la historia...</p>
                </div>
            </div>

            <div class="card">
                <div class="img-container">
                    <img src="./images/excursioninde.png" alt="" class="img-receta">
                </div>
                <div class="content">
                    <h5>Escuelita</h5>
                    <h3>"Antonia Reyes"</h3>
                    <p>¡Estoy fascinada con ser socia! Vale cada peso, no solo por el fútbol. Mi hijo se fe de excursión con la escuelita del club hace poco y volvió contentisimo. Estupendo trato de los profes...</p>
                </div>
            </div>

            <div class="card">
                <div class="img-container">
                    <img src="./images/coloniainde.png" alt="" class="img-receta">
                </div>
                <div class="content">
                    <h5>Primer Campeonato Ganado</h5>
                    <h3>"Victor Marin"</h3>
                    <p>¡Una alegria inmensa! Mi pibe salió campeón el fin de semana, recibieron medallas espectaculares, la Copa y hasta una camiseta oficial autografiada para el equipo...</p>
                </div>
            </div>

        </div>
       <!--<div class="more-container">
            <a href="">VER TODAS LAS RECETAS</a>
        </div>-->       
    </section>

    <!-- Recetas Section End -->

    <!-- Contacto Section Start -->
    <section class="contacto-section" id="contacto">
        <div class="contacto-wrapper">
            <div class="contacto-content">
                <div class="contacto-info">
                    <h2 class="contacto-title">Hablemos</h2>
                    <p class="contacto-subtitle">¿Tenés alguna consulta o sugerencia? Estamos acá para ayudarte</p>

                    <div class="contacto-details">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-envelope'></i>
                            </div>
                            <div class="detail-text">
                                <h4>Email</h4>
                                <p>lorem@ipsum.com</p>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-phone'></i>
                            </div>
                            <div class="detail-text">
                                <h4>Teléfono</h4>
                                <p>11-1234-5678</p>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-map'></i>
                            </div>
                            <div class="detail-text">
                                <h4>Ubicación</h4>
                                <p>Diego A. Milito, Avellaneda</p>
                                <p>Av. SeFueronAlaB 4141, Villa Urquiza</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contacto-form-container">
                    <form id="formContacto" class="contacto-form">
                        <div class="form-header">
                            <i class='bx bx-message-square-dots'></i>
                            <h3>Envianos tu mensaje</h3>
                        </div>

                        <div class="form-group">
                            <label for="nombre_contacto">
                                <i class='bx bx-user'></i>
                                Tu nombre
                            </label>
                            <input type="text" id="nombre_contacto" name="nombre" placeholder="Juan Pérez" required>
                        </div>

                        <div class="form-group">
                            <label for="email_contacto">
                                <i class='bx bx-envelope'></i>
                                Tu email
                            </label>
                            <input type="email" id="email_contacto" name="email" placeholder="juan@ejemplo.com" required>
                        </div>

                        <div class="form-group">
                            <label for="mensaje_contacto">
                                <i class='bx bx-message-detail'></i>
                                Tu mensaje
                            </label>
                            <textarea id="mensaje_contacto" name="mensaje" rows="5" placeholder="Escribí tu consulta o sugerencia..." required></textarea>
                        </div>

                        <div id="mensaje-contacto" class="mensaje-feedback"></div>

                        <button type="submit" class="btn-enviar-contacto">
                            <i class='bx bx-send'></i>
                            <span>Enviar Mensaje</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Contacto Section End -->

    <!-- Footer Start -->

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
    <!-- Footer End -->


    <!-- Scroll Reveal -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!-- Typed js -->
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <!-- Custom Scripts Link -->
    <script src="./js/scripts.js"></script>
    <script src="./js/scriptsRecipe.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Contacto Form Script -->
    <script>
        $('#formContacto').on('submit', function(e) {
            e.preventDefault();

            const btn = $(this).find('button[type="submit"]');
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i><span>Enviando...</span>');

            $.ajax({
                type: 'POST',
                url: 'php/procesar_contacto.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    const mensaje = $('#mensaje-contacto');
                    mensaje.text(response.message);

                    if (response.success) {
                        mensaje.removeClass('error').addClass('success').show();
                        $('#formContacto')[0].reset();

                        setTimeout(function() {
                            mensaje.fadeOut();
                        }, 5000);
                    } else {
                        mensaje.removeClass('success').addClass('error').show();
                    }

                    btn.prop('disabled', false).html(originalHtml);
                },
                error: function() {
                    $('#mensaje-contacto')
                        .text('Error al enviar el mensaje. Intentá de nuevo.')
                        .removeClass('success')
                        .addClass('error')
                        .show();
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    </script>

    <!-- User Menu Dropdown Script -->
    <script>
        const userAvatar = document.getElementById('user-avatar');
        const userDropdown = document.getElementById('user-dropdown');

        if (userAvatar && userDropdown) {
            userAvatar.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            // Cerrar el dropdown al hacer clic fuera de él
            document.addEventListener('click', function(e) {
                if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }
    </script>

</body>
</html>