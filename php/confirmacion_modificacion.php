<?php
    session_start();

    // Proteger la página: si no hay sesión, redirigir al login
    if (!isset($_SESSION['nombre'])) {
        header("Location: sesion.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Actualizados</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/escudocai.ico" type="image/x-icon">
    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .confirmation-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 3rem 2rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .confirmation-icon {
            font-size: 6rem;
            color: #28a745; /* Verde éxito */
            text-shadow: 0 0 15px rgba(40, 167, 69, 0.7);
            line-height: 1;
        }
        .confirmation-title {
            color: var(--white-color);
            font-size: 1.8rem;
            margin-top: 1.5rem;
            margin-bottom: 2.5rem;
            font-family: 'Poppins', sans-serif;
        }
    </style>
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
                <li><a href="cerrarSesion.php">Cerrar Sesión</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <i class="bx bx-menu" id="nav-open"></i>
            </div>
        </nav>
    </header>

     <section class="perfil-section" id="home">
        <div class="confirmation-container">
            <i class='bx bxs-check-circle confirmation-icon'></i>
            <h2 class="confirmation-title">Se actualizaron los datos del perfil</h2>
            <a href="../perfil.php" class="btn custom-btn btn-form">Volver</a>
        </div>
     </section>

</body>
</html>