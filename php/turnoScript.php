<?php
session_start();
header('Content-Type: application/json');

// 1. Solo procesar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

if (isset($_SESSION['user_id'])) {

/*
    require "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
*/   

    $conexion = mysqli_connect("localhost", "root", "", "reyescopas") 
        or die(json_encode(['success' => false, 'message' => 'No se pudo conectar al servidor.']));

    if (!isset($_POST['dia']) || !isset($_POST['hora'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para agendar el turno.']);
        exit();
    }
    
    $dia = $_POST['dia'];
    $hora = $_POST['hora'];
    $id = $_SESSION['user_id'];
    $mail = $_SESSION['email'];
    $nombre = $_SESSION['nombre'];

    $consulta_existencia = mysqli_query($conexion, "SELECT * FROM turnos where dia = '$dia' && hora ='$hora'");

    
        if(mysqli_num_rows($consulta_existencia) > 0){
            echo json_encode(['success' => false, 'message' => 'El turno ya está ocupado. Por favor, elija otro.']);
        }
        else{
            $sql =  "INSERT INTO turnos(dia, hora, usuario_id) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $dia, $hora, $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Turno agendado con éxito.']);
            } 
            else {
                echo json_encode(['success' => false, 'message' => 'Error al agendar el turno: ' . $stmt->error]);
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
} else {
    // Si no hay sesión iniciada
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para agendar un turno.']);
}
mysqli_close($conexion);
?>
