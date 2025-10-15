<?php

    $conexion = new mysqli("localhost", "root", "", "reyescopas");


        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $mail = $_POST['email'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
        $dni = $_POST['dni'];
        $telefono = $_POST['telefono'];
        

        $consulta_existencia = mysqli_query(
            $conexion, "SELECT * FROM usuarios where dni ='$dni' OR email = '$mail'");

        if(mysqli_num_rows($consulta_existencia) > 0)
        {
            echo "El usuario ya esta registrado";
        }
        else
        {
            $sql =  "INSERT INTO usuarios(nombre, apellido, email, contrasena, dni, telefono) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssss", $nombre, $apellido, $mail, $contrasena, $dni, $telefono);

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