<?php
session_start();
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
                <li><a href="sesion.php">Cuenta</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

    <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
            <li><a href="../index.html#home">Inicio</a></li>
            <li><a href="../index.html#about">Sobre mi</a></li>
            <li><a href="../index.html#servicios">Servicios</a></li>
            <li><a href="../index.html#recetas">Ustedes</a></li>
            <li><a href="sesion.php">Cuenta</a></li>
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
            <form action="#" method="POST" class="alta-form">
                <div class="form-grid">
                    <div class="input-container">
                        <label for="plan">Tipo de plan</label>
                        <select id="plan" name="plan" class="input">
                            <option value="socio_pleno">Socio Pleno</option>
                            <option value="socio_cadete">Socio Cadete</option>
                            <option value="socio_infantil">Socio Infantil</option>
                            <option value="adherente">Adherente</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="input" required>
                    </div>
                    <div class="input-container">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="input" required>
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
                        <label for="nacionalidad">Nacionalidad</label>
                        <input type="text" id="nacionalidad" name="nacionalidad" class="input" required>
                    </div>
                </div>

                <h3 class="form-section-title"><strong>DOMICILIO</strong></h3>
                <div class="form-grid">
                    <div class="input-container span-2">
                        <label for="calle">Calle y Número</label>
                        <div class="form-row">
                            <input type="text" id="calle" name="calle" class="input" placeholder="Calle" required>
                            <input type="text" id="nro" name="nro" class="input nro-input" placeholder="Nro" required>
                        </div>
                    </div>
                    <div class="input-container">
                        <label for="piso">Piso</label>
                        <input type="text" id="piso" name="piso" class="input">
                    </div>
                    <div class="input-container">
                        <label for="departamento">Departamento</label>
                        <input type="text" id="departamento" name="departamento" class="input">
                    </div>
                    <div class="input-container">
                        <label for="cp">Código Postal</label>
                        <input type="text" id="cp" name="cp" class="input" required>
                    </div>
                    <div class="input-container">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" class="input" required>
                    </div>
                    <div class="input-container span-2">
                        <label for="provincia">Estado/Provincia</label>
                        <input type="text" id="provincia" name="provincia" class="input" required>
                    </div>
                </div>

                <h3 class="form-section-title"><strong>CONTACTO</strong></h3>
                <div class="form-grid">
                    <div class="input-container"><label for="celular">Número de celular</label><input type="tel" id="celular" name="celular" class="input" required></div>
                    <div class="input-container"><label for="email">Email</label><input type="email" id="email" name="email" class="input" required></div>
                    <div class="input-container"><label for="email_confirm">Confirmación de email</label><input type="email" id="email_confirm" name="email_confirm" class="input" required></div>
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
            <img src="../images/Reyes de copas.png" alt="">
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
</html>