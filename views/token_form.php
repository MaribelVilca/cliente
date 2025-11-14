<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TokenController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

$tokenController = new TokenController();
$token = null;
$token_value = '';

if (isset($_GET['edit'])) {
    $token_value = $_GET['edit'];
    $token = $tokenController->obtenerToken($token_value);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_token'])) {
    $token_viejo = $_POST['token_viejo'];
    $nuevo_token = $_POST['nuevo_token'];
    $tokenController->actualizar($token_viejo, $nuevo_token);
    header('Location: ' . BASE_URL . 'views/tokens_list.php');
    exit();
}

require_once __DIR__ . '/include/header.php';
?>

<style>
    /* Estilos globales */
    .container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    .dashboard-container {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    .dashboard-container h2 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }
    /* Formulario */
    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    label {
        font-weight: 500;
        color: #2c3e50;
    }
    input[type="text"] {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }
    input[type="text"]:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
    }
    /* Botón de guardar */
    .btn-primary {
        background-color: #3498db;
        color: white;
        padding: 0.75rem;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    .btn-primary:hover {
        background-color: #2980b9;
    }
    /* Animación */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="container fade-in">
    <div class="dashboard-container">
        <h2><i class="fas fa-edit"></i> Actualizar Token</h2>
        <form method="POST" action="">
            <input type="hidden" name="token_viejo" value="<?php echo htmlspecialchars($token_value); ?>">
            <div style="margin-bottom: 1rem;">
                <label for="nuevo_token" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Nuevo Token:</label>
                <input type="text" id="nuevo_token" name="nuevo_token" value="<?php echo htmlspecialchars($token ? $token['token'] : ''); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" name="actualizar_token" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
