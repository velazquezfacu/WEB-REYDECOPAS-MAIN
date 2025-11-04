<?php
    session_start();

    // Capturar el redirect_url si existe
    $redirect_url = isset($_GET['redirect_url']) ? $_GET['redirect_url'] : '';

    if (isset($_SESSION['email'])) {
        // Si el usuario ya inició sesión
        if (!empty($redirect_url)) {
            // Redirigir a la URL especificada
            header("Location: ../" . $redirect_url);
        } else {
            // Redirigir al perfil por defecto
            header("Location: ../perfil.php");
        }
    } else {
        // Si no hay sesión, lo mandamos a la página de inicio de sesión
        if (!empty($redirect_url)) {
            // Pasar el redirect_url a la página de sesión
            header("Location: ../sesion.php?redirect_url=" . urlencode($redirect_url));
        } else {
            header("Location: ../sesion.php");
        }
    }
    exit;
?>