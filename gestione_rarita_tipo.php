<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['ruolo'] !== 'amministratore' && $_SESSION['user']['ruolo'] !== 'moderatore')) {
  header("Location: index.php");
  die();
}

$success = [];
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tipo_nome = trim($_POST['nome_tipo'] ?? '');
  $rarita_nome = trim($_POST['nome_rarita'] ?? '');

  if ($tipo_nome !== '') {
    $check = $pdo->prepare("SELECT id FROM tipo WHERE nome = ?");
    $check->execute([$tipo_nome]);
    if ($check->rowCount() > 0) {
      $success[] = 'Il tipo "' . $tipo_nome . '" è già presente.';
    } else {
      $insert = $pdo->prepare("INSERT INTO tipo (nome) VALUES (?)");
      $insert->execute([$tipo_nome]);
      $success[] = 'Tipo "' . $tipo_nome . '" inserito con successo.';
    }
  }

  if ($rarita_nome !== '') {
    $check = $pdo->prepare("SELECT id FROM rarita WHERE nome = ?");
    $check->execute([$rarita_nome]);
    if ($check->rowCount() > 0) {
      $success[] = 'La rarità "' . $rarita_nome . '" è già presente.';
    } else {
      $insert = $pdo->prepare("INSERT INTO rarita (nome) VALUES (?)");
      $insert->execute([$rarita_nome]);
      $success[] = 'Rarità "' . $rarita_nome . '" inserita con successo.';
    }
  }

  if ($tipo_nome === '' && $rarita_nome === '') {
    $error[] = "Inserisci almeno un tipo o una rarità.";
  }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Gestione Tipi e Rarità</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="main-container">
    <div class="form-container">
      <h2>Gestione Tipi e Rarità</h2>
      <?php foreach ($error as $e): ?><div class="message error"><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
      <?php foreach ($success as $s): ?><div class="message success"><?= htmlspecialchars($s) ?></div><?php endforeach; ?>
      <form method="POST">
        <div class="form-group">
          <label for="nome_tipo">Nome Tipo</label>
          <input type="text" name="nome_tipo" id="nome_tipo" placeholder="Es: Energy, Zero, Rehab">
        </div>
        <div class="form-group">
          <label for="nome_rarita">Nome Rarità</label>
          <input type="text" name="nome_rarita" id="nome_rarita" placeholder="Es: Standard, Limited, Collector">
        </div>
        <button type="submit" class="btn">Salva Tipi e Rarità</button>
      </form>
    </div>
  </main>
</body>
</html>
