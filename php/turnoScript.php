<?php
session_start();

if (isset($_SESSION['user_id'])){

/*
    require "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
*/   

    $conexion = mysqli_connect("localhost", "root", "", "nutricionista") 
    or die('no se pudo conectar al servidor');

    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $id = $_SESSION['user_id'];
    $mail = $_SESSION['mail'];
    $nombre = $_SESSION['nombre'];

    $consulta_existencia = mysqli_query($conexion, "SELECT * FROM turnos where dia = '$dia' && hora ='$hora'");

    
        if(mysqli_num_rows($consulta_existencia) > 0){
            echo "El turno ya esta ocupado";
        }
        else{
            $sql =  "INSERT INTO turnos(dia, hora, usuario_id) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $dia, $hora, $id);

            if($stmt->execute())
            {
                echo "Turno agendado con exito";
            } 
            else 
            {
                echo "Error: " . $stmt->error;
            }

            $stmt->close(); 
        }

/*
                $e_mail = new PHPMailer(true);
                try {
                    $e_mail->isSMTP();
                    $e_mail->Host = "smtp.office365.com";
                    $e_mail->Port = 587;
                    $e_mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $e_mail->SMTPAuth = true;
                    $e_mail->Username = "matikonig13@hotmail.com";
                    $e_mail->Password = "Matifeli2001";
                    $e_mail->setFrom("matikonig13@hotmail.com", "Lic. Carla Cuenca");
                    $e_mail->addAddress($mail);
                    $e_mail->isHTML(true);
                    $e_mail->Subject = "Confirmación de turno";
                    $e_mail->Body = "Hola " . htmlspecialchars($nombre) . ", tu turno se agendó satisfactoriamente para el día " . htmlspecialchars($dia) . " a las " . htmlspecialchars($hora) . "hs, gracias por confiar";

                    $e_mail->send();        
                    } catch (Exception $e) {
                            echo "El mensaje no pudo ser enviado. Error de Mailer: {$e_mail->ErrorInfo}";
                    }
*/
}else{
    echo "Debes inciar sesion para agendar un turno";
}
?>
