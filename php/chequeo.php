<?php
    session_start();

    // 1. Verifica si hay una sesión iniciada.
    if (isset($_SESSION['email'])) {
        // 2. Si la sesión es del admin, lo redirige a la lista de turnos.
        if ($_SESSION['email'] === 'admin@superadmin.com') {
            header("Location: ../sesion.php");
        } else {
            // 3. Si es un usuario normal, lo redirige a la página para sacar turno.
            header("Location: ../turno.html");
        }
    } else {
        // 4. Si no hay sesión iniciada, lo redirige a la página de registro.
        header("Location: ../sesion.php");
    }
    exit;
?>