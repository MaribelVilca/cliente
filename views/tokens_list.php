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
$tokens = $tokenController->listarTokens();
require_once __DIR__ . '/include/header.php';
?>
<style>
    /* Estilos globales */
    .container {
        max-width: 1000px;
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
        margin-bottom: 1rem;
        font-size: 1.75rem;
    }
    .dashboard-container p {
        color: #7f8c8d;
        margin-bottom: 1.5rem;
    }
    /* Tabla de tokens */
    .table-container {
        overflow-x: auto;
        margin-top: 1.5rem;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .table th, .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }
    .table th {
        background-color: #f7f9fa;
        font-weight: 600;
        color: #2c3e50;
    }
    .table tr:hover {
        background-color: #f5f7fa;
    }
    /* Botones */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }
    .btn-warning {
        background-color: #f39c12;
        color: white;
    }
    .btn-warning:hover {
        background-color: #e67e22;
    }
    /* Código del token */
    code {
        font-family: 'Courier New', Courier, monospace;
        background: #f0f0f0;
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.85rem;
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
        <h2></i> Tokens API</h2>
        <p>Aquí puedes ver y actualizar tus tokens de acceso a la API.</p>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                      
                        <th>Token</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($tokens as $token): ?>
<tr>
    <td>
        <code style="font-size: 0.85rem; background: #f0f0f0; padding: 0.25rem 0.5rem; border-radius: 3px;">
            <?php echo substr($token['token'], 0, 20) . '...'; ?>
        </code>
    </td>
    <td>
        <a href="<?php echo BASE_URL; ?>views/token_form.php?edit=<?php echo urlencode($token['token']); ?>" class="btn btn-small btn-warning">
            </i> Actualizar
        </a>
    </td>
</tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
