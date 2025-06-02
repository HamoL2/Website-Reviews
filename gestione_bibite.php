<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['ruolo'] !== 'amministratore' && $_SESSION['user']['ruolo'] !== 'moderatore')) {
  header("Location: index.php");
  exit;
}

$success = '';
$error = '';

// Inserimento bibita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bibita_nome'])) {
  $nome = trim($_POST['bibita_nome']);
  $tipo_id = $_POST['tipo_id'];
  $rarita_id = $_POST['rarita_id'];
  $prezzo_range = $_POST['prezzo_range'];

  if ($nome !== '' && is_numeric($prezzo_range)) {
    try {
      $stmt = $pdo->prepare("INSERT INTO bibita (nome, tipo, rarita, prezzo_range) VALUES (?, ?, ?, ?)");
      $stmt->execute([$nome, $tipo_id, $rarita_id, $prezzo_range]);
      $success = "Bibita inserita con successo.";
    } catch (PDOException $e) {
      $error = "Errore: nome bibita già esistente o dati non validi.";
    }
  } else {
    $error = "Inserisci tutti i dati richiesti correttamente.";
  }
}

// Dati per i form
$tipi = $pdo->query("SELECT * FROM tipo")->fetchAll(PDO::FETCH_ASSOC);
$rarita = $pdo->query("SELECT * FROM rarita")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Gestione Bibite</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="main-container">
    <div class="form-container">
      <h2>Inserisci una nuova Bibita</h2>
      <?php if ($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group"><label>Nome bibita</label><input name="bibita_nome" required></div>
        <div class="form-group">
          <label>Tipo</label>
          <select name="tipo_id" required>
            <?php foreach ($tipi as $t): ?>
              <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Rarità</label>
          <select name="rarita_id" required>
            <?php foreach ($rarita as $r): ?>
              <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label>Prezzo Range</label><input type="number" name="prezzo_range" min="0" required></div>
        <button type="submit" class="btn">Aggiungi Bibita</button>
      </form>
    </div>
  </main>
</body>
</html>
