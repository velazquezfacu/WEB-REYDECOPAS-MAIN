<?php

session_start();



$conexion = new mysqli("localhost", "root", "", "nutricionista");

        $mail = $_POST['mail'];
        $constraseña = $_POST['contraseña'];

        $consulta_existencia = mysqli_query(
            $conexion, "SELECT * FROM usuarios where mail ='$mail'");

        if(mysqli_num_rows($consulta_existencia) > 0)
        {
            $sql = "SELECT id, nombre, mail, contraseña FROM usuarios WHERE mail = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $stmt->bind_result($id, $nombre, $mail, $hashed_password);
            $stmt->fetch();

            if (password_verify($constraseña, $hashed_password)){
                $_SESSION['user_id'] = $id;
                $_SESSION['mail'] = $mail;
                $_SESSION['nombre'] = $nombre;


                echo "Inicio de sesion exitoso";

            $stmt->close();
            }
            else
            {
                echo "Email o contraseña incorrectos1";
            }
        }
        else
        {    
            echo "Email o contraseña incorrectos";
        }

$conexion->close();
?>