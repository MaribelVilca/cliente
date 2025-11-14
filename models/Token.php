<?php
// models/Token.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class Token {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB();
    }

     // Obtener todos los tokens
    public function obtenerTokens() {
        $resultado = $this->conexion->query("SELECT token FROM tokens_api WHERE estado = 1");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
 // Actualizar un token (ahora solo por token, no por ID)
    public function actualizarToken($token_viejo, $nuevo_token) {
        $stmt = $this->conexion->prepare("UPDATE tokens_api SET token = ? WHERE token = ?");
        $stmt->bind_param("ss", $nuevo_token, $token_viejo);
        return $stmt->execute();
    }
    // Obtener un token por su valor
    public function obtenerTokenPorToken($token) {
        $stmt = $this->conexion->prepare("SELECT token, estado FROM tokens_api WHERE token = ?");
        if (!$stmt) {
            die("Error en la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

  
    // Obtener el token activo de la base de datos
    public function obtenerTokenActivo() {
        $query = "SELECT token FROM tokens_api WHERE estado = 1 LIMIT 1";
        $resultado = $this->conexion->query($query);
        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return $fila['token'];
        }
        return null;
    }

    // Validar el token en APIHOTEL
    public function validarTokenEnAPIHOTEL($token) {
        $url = 'http://localhost/apihotel/api_handler.php?action=validarToken';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Depuración: Mostrar la respuesta cruda
        error_log("Respuesta de APIHOTEL: " . $response);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Si no es un JSON válido, devolver un error
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status' => false,
                'type' => 'error',
                'msg' => 'Respuesta inválida del servidor de APIHOTEL.'
            ];
        }

        return $data;
    }
}
