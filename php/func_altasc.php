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

// 3. OBTENER ID Y COSTO DEL PLAN A PARTIR DEL NOMBRE
$nombre_plan_form = $_POST['plan']; // 'Socio Pleno', 'Socio Cadete', etc.
$sql_plan = "SELECT id, costo_plan FROM planes WHERE nombre_plan = ?";
$stmt_plan = $conexion->prepare($sql_plan);
$stmt_plan->bind_param("s", $nombre_plan_form);
$stmt_plan->execute();
$result_plan = $stmt_plan->get_result();

if ($result_plan->num_rows === 0) {
    die("Error: El plan seleccionado no es válido.");
}
$plan = $result_plan->fetch_assoc();
$id_plan = $plan['id'];
$costo_plan = $plan['costo_plan'];
$stmt_plan->close();

// 4. RECOGER DATOS DEL FORMULARIO
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

$sexo = $_POST['sexo'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$fecha_registro = date("Y-m-d H:i:s"); // Fecha y hora actual del registro
$estado = 'Activo'; // Estado inicial del socio

// 5. INSERTAR EN LA TABLA SOCIO
$sql_insert = "INSERT INTO socio (nro_socio, sexo, fecha_nacimiento, fecha_registro, id_plan, id_usua, estado)
               VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt_insert = $conexion->prepare($sql_insert);
$stmt_insert->bind_param("ssssiis", $nro_socio, $sexo, $fecha_nacimiento, $fecha_registro, $id_plan, $id_usua, $estado);

if ($stmt_insert->execute()) {
    $id_socio_nuevo = $stmt_insert->insert_id;
    $stmt_insert->close();

    // 6. CREAR REGISTRO EN CUENTA_CTE
    $saldo_inicial = $costo_plan; // Primera cuota pendiente
    $sql_cuenta = "INSERT INTO cuenta_cte (id_soc, saldo, tipo_plan, estado, fech_pago, ult_fechpag)
                   VALUES (?, ?, ?, 'Pendiente', NOW(), NOW())";
    $stmt_cuenta = $conexion->prepare($sql_cuenta);
    $stmt_cuenta->bind_param("idi", $id_socio_nuevo, $saldo_inicial, $id_plan);

    if (!$stmt_cuenta->execute()) {
        error_log("Error al crear cuenta corriente: " . $stmt_cuenta->error);
    }
    $stmt_cuenta->close();

    // 7. GENERAR PRIMERA CUOTA
    $fecha_vencimiento = date('Y-m-d', strtotime('+30 days')); // Vence en 30 días
    $sql_cuota = "INSERT INTO cuotas (id_socio, numero_cuota, monto, fecha_vencimiento, estado)
                  VALUES (?, 1, ?, ?, 'pendiente')";
    $stmt_cuota = $conexion->prepare($sql_cuota);
    $stmt_cuota->bind_param("ids", $id_socio_nuevo, $costo_plan, $fecha_vencimiento);

    if (!$stmt_cuota->execute()) {
        error_log("Error al generar primera cuota: " . $stmt_cuota->error);
    }
    $stmt_cuota->close();

    $conexion->close();

    // Redirigir al perfil con el tab de cuotas abierto
    header("Location: ../perfil.php?tab=cuotas&status=socio_ok");
    exit();
} else {
    // Manejo de error más robusto
    error_log("Error al registrar socio: " . $stmt_insert->error);
    $stmt_insert->close();
    $conexion->close();
    header("Location: altasocio.php?error=db");
    exit();
}
?>
