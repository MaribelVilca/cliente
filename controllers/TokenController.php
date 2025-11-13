<?php
// controllers/TokenController.php
require_once __DIR__ . '/../models/Token.php';

class TokenController {
    private $tokenModel;

    public function __construct() {
        $this->tokenModel = new Token();
    }

    // Listar todos los tokens
    public function listarTokens() {
        return $this->tokenModel->obtenerTokens();
    }

    // Obtener un token por token
    public function obtenerToken($token) {
        return $this->tokenModel->obtenerTokenPorToken($token);
    }

    // Actualizar un token
    public function actualizar($token_viejo, $nuevo_token) {
        return $this->tokenModel->actualizarToken($token_viejo, $nuevo_token);
    }
     // Validar token en la base de datos local
    public function validarTokenLocal($token) {
        return $this->tokenModel->validarTokenLocal($token);
    }
}
