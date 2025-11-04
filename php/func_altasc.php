<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "reyescopas");

// 1. PROTEGER EL SCRIPT Y LA CONEXIÓN
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no autorizado.");
}

if (!isset($_SESSION['email'])) {
    header("Location: sesion.php");
    exit;
}

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$email_usuario = $_SESSION['email'];

// 2. OBTENER ID DEL USUARIO LOGUEADO
$sql_usuario = "SELECT id FROM usuarios WHERE email = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $email_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows === 0) {
    die("Error: No se encontró el usuario en la base de datos.");
}
$usuario = $result_usuario->fetch_assoc();
$id_usua = $usuario['id'];
$stmt_usuario->close();

// 3. OBTENER ID DEL PLAN A PARTIR DEL NOMBRE
$nombre_plan_form = $_POST['plan']; // 'Socio Pleno', 'Socio Cadete', etc.
$sql_plan = "SELECT id FROM planes WHERE nombre_plan = ?";
$stmt_plan = $conexion->prepare($sql_plan);
$stmt_plan->bind_param("s", $nombre_plan_form);
$stmt_plan->execute();
$result_plan = $stmt_plan->get_result();

if ($result_plan->num_rows === 0) {
    die("Error: El plan seleccionado no es válido.");
}
$plan = $result_plan->fetch_assoc();
$id_plan = $plan['id'];
$stmt_plan->close();

// 4. VALIDAR EMAILS
$email_form = $_POST['email'];
$email_confirm_form = $_POST['email_confirm'];

if ($email_form !== $email_confirm_form) {
    // Si los emails no coinciden, redirigir con un error.
    header("Location: altasocio.php?error=email_mismatch");
    exit();
}
// 4. RECOGER DATOS DEL FORMULARIO (con los nombres correctos)
// Generar un número de socio aleatorio y único de 8 dígitos
do {
    // Genera un número aleatorio entre 10000000 y 99999999
    $nro_socio_generado = random_int(10000000, 99999999);
    
    // Prepara la consulta para verificar si el número ya existe
    $stmt_check = $conexion->prepare("SELECT nro_socio FROM socio WHERE nro_socio = ?");
    $stmt_check->bind_param("i", $nro_socio_generado);
    $stmt_check->execute();
    $stmt_check->store_result();
    $nro_socio = (string)$nro_socio_generado; // Convertir a string para el INSERT
} while ($stmt_check->num_rows > 0);
$dni = $_POST['num_doc']; // El DNI sí lo tomamos del formulario
$sexo = $_POST['sexo'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$fecha_registro = date("Y-m-d H:i:s"); // Fecha y hora actual del registro
$email_form = $_POST['email']; // El email sí lo tomamos del formulario
$estado = 'Activo'; // Estado inicial del socio

// 5. INSERTAR EN LA TABLA SOCIO
$sql_insert = "INSERT INTO socio (nro_socio, dni, email, sexo, fecha_nacimiento, fecha_registro, id_plan, id_usua, estado)
               VALUES (?, ?, ?,?, ?, ?, ?, ?, ?)";

$stmt_insert = $conexion->prepare($sql_insert);
// El tipo de dato para nro_socio y dni debe ser 's' (string) si pueden contener ceros a la izquierda.
// id_plan y id_usua son 'i' (integer).
$stmt_insert->bind_param("ssssssiis", $nro_socio, $dni,$email_form, $sexo, $fecha_nacimiento, $fecha_registro, $id_plan, $id_usua, $estado);

if ($stmt_insert->execute()) {
    // Redirigir a una página de éxito o al perfil.
    header("Location: ../perfil.php?status=socio_ok");
    exit();
} else {
    // Manejo de error más robusto
    error_log("Error al registrar socio: " . $stmt_insert->error);
    header("Location: altasocio.php?error=db");
    exit();
}

$stmt_insert->close();
$conexion->close();
?>
