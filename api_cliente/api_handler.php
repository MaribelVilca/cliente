<?php
// api_cliente/api_handler.php (CLIENTEAPI)
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/TokenController.php';

// Obtener el token activo de la base de datos
$tokenController = new TokenController();
$token = $tokenController->obtenerTokenActivo();

if (empty($token)) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'No hay un token activo configurado en la base de datos.'
    ]);
    exit();
}

// Validar el token en APIHOTEL
$validacion = $tokenController->validarTokenEnAPIHOTEL($token);

if (empty($validacion) || !$validacion['status']) {
    echo json_encode([
        'status' => false,
        'type' => $validacion['type'] ?? 'error',
        'msg' => $validacion['msg'] ?? 'Error al validar el token en APIHOTEL.'
    ]);
    exit();
}

// Obtener el término de búsqueda
$search = $_POST['search'] ?? '';

// Redirigir la petición al APIHOTEL
$url = 'http://localhost/apihotel/api_handler.php?action=buscarHoteles';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token, 'search' => $search]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
