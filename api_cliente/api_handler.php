<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/TokenController.php';

// URL del APIHOTEL (cambia por la URL real de tu APIHOTEL)
define('API_HOTEL_URL', 'http://localhost/apihotel/api_handler.php');

// Obtener el token y la acción
$token = $_POST['token'] ?? '';
$action = $_GET['action'] ?? '';
$search = $_POST['search'] ?? '';

// Validar que el token no esté vacío
if (empty($token)) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no proporcionado.'
    ]);
    exit();
}

// Validar el token en la base de datos local
$tokenController = new TokenController();
$validacionLocal = $tokenController->validarTokenLocal($token);

if (!$validacionLocal['status']) {
    echo json_encode($validacionLocal);
    exit();
}

// Redirigir la petición al APIHOTEL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_HOTEL_URL . '?' . http_build_query(['action' => $action]));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token, 'search' => $search]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Devolver la respuesta del APIHOTEL
if ($httpCode === 200) {
    echo $response;
} else {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Error al conectar con el APIHOTEL.'
    ]);
}
?>
