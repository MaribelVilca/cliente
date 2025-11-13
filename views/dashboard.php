<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}
require_once __DIR__ . '/include/header.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* === BASE GENERAL === */
body {
  font-family: 'Poppins', sans-serif;
  background: #f6f8fa;
  color: #2d3436;
  margin: 0;
  padding: 0;
}

.container {
  animation: fadeIn 0.5s ease-in-out;
}

h2 {
  font-size: 1.9rem;
  margin-bottom: 0.3rem;
  color: #2d3436;
}

p {
  margin: 0.3rem 0 1rem;
  color: #636e72;
}

/* === CONTENEDOR PRINCIPAL === */
.dashboard-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 1.5rem;
}

/* === TARJETAS === */
.card {
  background: #fff;
  border-radius: 14px;
  padding: 1.8rem;
  margin-bottom: 1.8rem;
  box-shadow: 0 6px 18px rgba(0,0,0,0.07);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.card h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: #34495e;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card h3 i {
  color: #6c8ea4;
}

/* === ACCIONES RÁPIDAS === */
.quick-actions {
  background: #fff;
  border-radius: 14px;
  padding: 1.5rem 1.8rem;
  box-shadow: 0 6px 15px rgba(0,0,0,0.05);
}

.quick-actions h3 {
  margin-bottom: 1rem;
}

.btn-container {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

/* === BOTONES === */
.quick-actions a {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  background: #6c8ea4;
  color: #fff;
  padding: 0.8rem 1.2rem;
  border-radius: 10px;
  font-weight: 600;
  letter-spacing: 0.3px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(108,142,164,0.25);
}

.quick-actions a:hover {
  background: #5a758a;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(108,142,164,0.35);
}

/* === TABLAS === */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

table th, table td {
  padding: 0.9rem 1rem;
  text-align: left;
  border-bottom: 1px solid #e0e6ed;
}

table th {
  background: #f0f3f6;
  color: #2c3e50;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

table th i {
  margin-right: 8px;
  color: #6c8ea4;
}

table tr:hover {
  background: #f9fafb;
}

/* === ANIMACIONES === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .btn-container {
    flex-direction: column;
  }
  .quick-actions a {
    width: 100%;
    justify-content: center;
  }
}
</style>

<div class="container">
  <div class="dashboard-container">
    <!-- Bienvenida -->
    <div class="dashboard-welcome card">
      <h2>¡Hola, <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['username']); ?>!</h2>
      <p>Bienvenido a tu panel de cliente.</p>
    </div>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
      </i> Acciones Rápidas</h3>
      <div class="btn-container">
        <a href="<?php echo BASE_URL; ?>views/tokens_list.php" class="btn btn-primary">
          </i> Mis Tokens API
        </a>
        <a href="<?php echo BASE_URL; ?>api_cliente/" class="btn btn-primary" target="">
          </i> Probar API Cliente
        </a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
