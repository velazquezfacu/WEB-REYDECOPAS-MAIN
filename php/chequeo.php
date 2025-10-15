<?php
    session_start();

    // Verifica si el usuario es admin
    if (isset($_SESSION['email']) && ($_SESSION['email']) === 'admin@superadmin.com') {
        header("Location: lista.php");
    } else {
        header("Location: turno.html");
    }
    exit;
?>