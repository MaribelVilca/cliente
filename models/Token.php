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

    // Validar token en la base de datos local
    public function validarTokenLocal($token) {
        $tokenData = $this->obtenerTokenPorToken($token);
        if (!$tokenData) {
            return ['status' => false, 'type' => 'error', 'msg' => 'Token no encontrado en la base de datos local.'];
        }
        if ($tokenData['estado'] != 1) {
            return ['status' => false, 'type' => 'warning', 'msg' => 'Token inactivo en la base de datos local.'];
        }
        return ['status' => true, 'type' => 'success', 'msg' => 'Token válido en la base de datos local.'];
    }
    // Actualizar un token (ahora solo por token, no por ID)
    public function actualizarToken($token_viejo, $nuevo_token) {
        $stmt = $this->conexion->prepare("UPDATE tokens_api SET token = ? WHERE token = ?");
        $stmt->bind_param("ss", $nuevo_token, $token_viejo);
        return $stmt->execute();
    }
}
