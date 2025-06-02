<?php
require_once 'db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: accedi.php");
    exit;
}

$data = $pdo->query("SELECT b.nome, SUM(r.mi_piace) as likes FROM recensione r
                     JOIN bibita b ON r.bibita_id = b.id
                     GROUP BY b.nome")->fetchAll(PDO::FETCH_ASSOC);

$nomi = [];
$likes = [];
foreach ($data as $row) {
  $nomi[] = $row['nome'];
  $likes[] = $row['likes'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Statistiche</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .chart-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 1rem;
    }
    canvas#grafico {
      width: 100%;
      height: auto;
      aspect-ratio: 1 / 1;
      display: block;
    }
  </style>
</head>
<body>
  <main class="main-container">
    <h2 style="text-align:center">Monster più apprezzate</h2>
    <div class="chart-container">
      <canvas id="grafico"></canvas>
    </div>
    <script>
      const ctx = document.getElementById('grafico').getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: <?= json_encode($nomi) ?>,
          datasets: [{
            label: 'Mi Piace',
            data: <?= json_encode($likes) ?>,
            backgroundColor: [
              '#39ff14', '#00bcd4', '#ff1744', '#ffd700', '#2a2a2a',
              '#7c4dff', '#00e676', '#ff9100', '#d500f9', '#ff5252'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true
        }
      });
    </script>
  </main>
</body>
</html>
