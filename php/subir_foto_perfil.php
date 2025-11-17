<?php
session_start();
header('Content-Type: application/json');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

// Verificar que se haya enviado un archivo
if (!isset($_FILES['foto_perfil']) || $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No se recibió ningún archivo']);
    exit();
}

$file = $_FILES['foto_perfil'];
$userId = $_SESSION['user_id'];

// Validar tipo de archivo
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
$fileType = mime_content_type($file['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido. Solo JPG, JPEG o PNG']);
    exit();
}

// Validar tamaño (máximo 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande. Máximo 5MB']);
    exit();
}

// Obtener extensión del archivo
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$allowedExtensions = ['jpg', 'jpeg', 'png'];

if (!in_array(strtolower($extension), $allowedExtensions)) {
    $extension = 'jpg'; // Por defecto
}

// Crear nombre único para el archivo
$newFileName = $userId . '_' . time() . '.' . $extension;
$uploadDir = '../uploads/fotos_perfil/';
$uploadPath = $uploadDir . $newFileName;

// Crear directorio si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Conectar a la base de datos para obtener la foto anterior
$conexion = new mysqli("localhost", "root", "", "reyescopas");
if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit();
}

// Obtener foto anterior para eliminarla
$sql = "SELECT foto_perfil FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$oldPhoto = null;

if ($row = $result->fetch_assoc()) {
    $oldPhoto = $row['foto_perfil'];
}
$stmt->close();

// Mover el archivo subido
if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo']);
    $conexion->close();
    exit();
}

// Actualizar la ruta en la base de datos
$dbPath = 'uploads/fotos_perfil/' . $newFileName;
$sql = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $dbPath, $userId);

if ($stmt->execute()) {
    // Eliminar foto anterior si existe
    if ($oldPhoto && file_exists('../' . $oldPhoto)) {
        unlink('../' . $oldPhoto);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Foto de perfil actualizada correctamente',
        'foto_url' => $dbPath
    ]);
} else {
    // Si falla la actualización, eliminar el archivo subido
    unlink($uploadPath);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la base de datos']);
}

$stmt->close();
$conexion->close();
?>
