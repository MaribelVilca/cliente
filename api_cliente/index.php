<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscador de Hoteles - API Cliente</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    /* === BASE GENERAL === */
    body {
      font-family: 'Poppins', sans-serif;
      background: #f6f8fa;
      color: #2d3436;
      margin: 0;
      padding: 0;
    }
    h2 {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
      color: #2d3436;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    p {
      margin: 0.3rem 0;
    }
    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      animation: fadeIn 0.5s ease-in-out;
    }
    /* === BOTÓN DE REGRESO === */
    .back-button {
      display: inline-flex;
      align-items: center;
      margin-bottom: 1.5rem;
      padding: 0.75rem 1.25rem;
      background: #f0f0f0;
      color: #2d3436;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .back-button:hover {
      background: #e0e0e0;
      transform: translateX(-2px);
    }
    .back-button i {
      margin-right: 8px;
      color: #6c8ea4;
    }
    /* === TARJETAS PRINCIPALES === */
    .card {
      background: #fff;
      border-radius: 14px;
      padding: 1.8rem;
      margin-bottom: 1.8rem;
      box-shadow: 0 6px 15px rgba(0,0,0,0.08);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .card h3 {
      font-size: 1.3rem;
      margin-bottom: 1rem;
      color: #34495e;
    }
    .card i {
      margin-right: 8px;
      color: #6c8ea4;
    }
    /* === BUSCADOR === */
    .search-box {
      display: flex;
      margin-bottom: 1.5rem;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    #search {
      flex: 1;
      padding: 0.85rem 1rem;
      border: 1px solid #ddd;
      border-right: none;
      font-size: 1rem;
      outline: none;
    }
    #btn_buscar {
      padding: 0 1.5rem;
      background: #6c8ea4;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    #btn_buscar:hover {
      background: #5a758a;
    }
    /* === CONTENEDOR DE RESULTADOS === */
    .resultados-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
      gap: 1.8rem;
      margin-top: 1rem;
    }
    /* === TARJETA DE HOTEL === */
    .hotel-card {
      background: #fff;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 6px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hotel-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .hotel-header {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    .hotel-image {
      width: 90px;
      height: 90px;
      border-radius: 10px;
      object-fit: cover;
      margin-right: 1rem;
      border: 1px solid #eee;
    }
    .hotel-info {
      flex: 1;
    }
    .hotel-nombre {
      font-size: 1.25rem;
      font-weight: 600;
      color: #2d3436;
      margin: 0 0 0.4rem 0;
    }
    .hotel-direccion {
      font-size: 0.95rem;
      color: #7f8c8d;
      margin: 0;
    }
    .hotel-details {
      margin-top: 1rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.4rem 1rem;
    }
    .hotel-details p {
      margin: 0.3rem 0;
      display: flex;
      align-items: center;
      font-size: 0.9rem;
      color: #34495e;
    }
    .hotel-details i {
      margin-right: 0.5rem;
      color: #6c8ea4;
    }
    /* === MENSAJES === */
    .loading, .error, .no-results {
      text-align: center;
      padding: 2rem;
      font-size: 1.1rem;
      border-radius: 12px;
      margin: 1rem 0;
      grid-column: 1 / -1;
    }
    .loading {
      color: #6c8ea4;
    }
    .error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .no-results {
      background: #f8f9fa;
      color: #6c757d;
      border: 1px solid #dee2e6;
    }
    /* === ANIMACIONES === */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    /* === RESPONSIVE === */
    @media (max-width: 768px) {
      .resultados-container {
        grid-template-columns: 1fr;
      }
      .hotel-header {
        flex-direction: column;
      }
      .hotel-image {
        margin-bottom: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Botón de regreso al dashboard -->
    <a href="../views/dashboard.php" class="back-button">
      <i class="fas fa-arrow-left"></i> Regresar al Dashboard
    </a>
    <div class="card">
      <h2><i class="fas fa-hotel"></i> Buscador de Hoteles</h2>
      <form id="frmApi">
        <div class="search-box">
          <input
            type="text"
            id="search"
            placeholder="Buscar por nombre, servicio, dirección o ubicación..."
            autocomplete="off"
            oninput="buscarHoteles()"
          >
          <button type="button" id="btn_buscar" onclick="buscarHoteles()">
            <i class="fas fa-search"></i> Buscar
          </button>
        </div>
      </form>
      <div id="resultados" class="resultados-container"></div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('search').oninput = buscarHoteles;
    });
  </script>
  <script src="js/api.js"></script>
</body>
</html>
