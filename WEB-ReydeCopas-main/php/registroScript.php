<?php

    $conexion = new mysqli("localhost", "root", "", "nutricionista");


        $nombre = $_POST['nombre'];
        $mail = $_POST['mail'];
        $constraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
        $dni = $_POST['dni'];
        $telefono = $_POST['telefono'];
        

        $consulta_existencia = mysqli_query(
            $conexion, "SELECT * FROM usuarios where dni ='$dni' OR mail = '$mail'");

        if(mysqli_num_rows($consulta_existencia) > 0)
        {
            echo "El usuario ya esta registrado";
        }
        else
        {
            $sql =  "INSERT INTO usuarios(nombre, mail, contraseña, dni, telefono) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssss", $nombre, $mail, $constraseña, $dni, $telefono);

            if($stmt->execute())
            {
                echo 'Registro exitoso';
            }
            else{
                echo "Error";
            }
             $stmt->close();
        }
       



    $conexion->close();
?>