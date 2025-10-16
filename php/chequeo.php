<?php
    session_start();

    if (isset($_SESSION['email'])) {
        // Si el usuario ya inició sesión, lo redirigimos a su perfil.
        // Si es admin, el perfil podría tener opciones de administrador.
        if ($_SESSION['email'] === 'admin@superadmin.com' && $_SESSION['contrasena'] === 'admin') {
            header("Location: ../perfil.php");
        } else {
            header("Location: ../perfil.php");
        }
    } else {
        // Si no hay sesión, lo mandamos a la página de inicio de sesión.
        header("Location: sesion.php");
    }
    exit;
?>