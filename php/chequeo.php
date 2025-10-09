<?php
    session_start();

    // Verifica si el usuario es admin
    if (isset($_SESSION['mail']) && ($_SESSION['mail']) === 'admin@hotmail.com') {
        header("Location: ../lista.php");
    } else {
        header("Location: ../turno.html");
    }
    exit;
?>