<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['ruolo'] !== 'amministratore' && $_SESSION['user']['ruolo'] !== 'moderatore')) {
  header("Location: index.php");
  exit;
}

$errore = '';
$success = '';

// Carica recensione se si sta modificando
$modifica = null;
if (isset($_GET['edit'])) {
  $stmt = $pdo->prepare("SELECT * FROM recensione WHERE id = ?");
  $stmt->execute([$_GET['edit']]);
  $modifica = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titolo = trim($_POST['titolo'] ?? '');
  $descrizione = trim($_POST['descrizione'] ?? '');
  $valutazione = $_POST['valutazione'] ?? '';
  $bibita_nome = trim($_POST['bibita_nome'] ?? '');
  $id = $_POST['id'] ?? null;
  $user_id = $_SESSION['user']['id'];

  if ($bibita_nome === '') {
    $errore = "Inserisci il nome della bibita.";
  } else {
    $stmt_bibita = $pdo->prepare("SELECT id FROM bibita WHERE nome = ?");
    $stmt_bibita->execute([$bibita_nome]);
    $bibita = $stmt_bibita->fetch();

    if (!$bibita) {
      $errore = "La bibita inserita non esiste nel database.";
    } else {
      $bibita_id = $bibita['id'];

      if ($id) {
        $stmt = $pdo->prepare("UPDATE recensione SET titolo=?, descrizione=?, valutazione=?, bibita_id=? WHERE id=?");
        $stmt->execute([$titolo, $descrizione, $valutazione, $bibita_id, $id]);
        $success = "Recensione aggiornata con successo.";
      } else {
        $stmt = $pdo->prepare("INSERT INTO recensione (titolo, descrizione, valutazione, bibita_id, registrato_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titolo, $descrizione, $valutazione, $bibita_id, $user_id]);
        $id = $pdo->lastInsertId();
        $success = "Recensione creata con successo.";
      }

      if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
        $estensione = strtolower(pathinfo($_FILES['immagine']['name'], PATHINFO_EXTENSION));
        if (in_array($estensione, ['jpg', 'jpeg', 'png', 'webp'])) {
          move_uploaded_file($_FILES['immagine']['tmp_name'], "uploads/{$id}.{$estensione}");
        }
      }
    }
  }
}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $pdo->prepare("DELETE FROM recensione WHERE id=?")->execute([$id]);
  foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) @unlink("uploads/{$id}.{$ext}");
  header("Location: modifica_recensione.php");
  exit;
}

$recensioni = $pdo->query("SELECT r.*, b.nome AS bibita_nome FROM recensione r JOIN bibita b ON r.bibita_id = b.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Gestione Recensioni</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="main-container">
    <div class="form-container">
      <h2>Crea / Modifica Recensione</h2>
      <?php if ($errore): ?><div class="message error"><?= htmlspecialchars($errore) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="message success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($modifica['id'] ?? '') ?>">
        <div class="form-group"><label>Titolo</label><input name="titolo" required value="<?= htmlspecialchars($modifica['titolo'] ?? '') ?>"></div>
        <div class="form-group"><label>Descrizione</label><textarea name="descrizione" required><?= htmlspecialchars($modifica['descrizione'] ?? '') ?></textarea></div>
        <div class="form-group"><label>Valutazione (1-5)</label><input type="number" name="valutazione" min="1" max="5" required value="<?= htmlspecialchars($modifica['valutazione'] ?? '') ?>"></div>
        <div class="form-group"><label>Nome Bibita (esatto)</label><input type="text" name="bibita_nome" required value="<?= htmlspecialchars($modifica['bibita_nome'] ?? '') ?>"></div>
        <div class="form-group"><label>Immagine</label><input type="file" name="immagine"></div>
        <button class="btn" type="submit">Salva</button>
      </form>
    </div>

    <div class="main-container">
      <h2>Recensioni Esistenti</h2>
      <?php foreach ($recensioni as $r): ?>
        <div class="review-card">
          <h3><?= htmlspecialchars($r['titolo']) ?> - <?= htmlspecialchars($r['bibita_nome']) ?></h3>
          <p><?= htmlspecialchars($r['descrizione']) ?></p>
          <p>Valutazione: <?= $r['valutazione'] ?></p>
          <?php
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
              $path = "uploads/{$r['id']}.{$ext}";
              if (file_exists($path)) {
                echo "<img src='$path' alt='Immagine recensione' style='max-width:200px;'>";
                break;
              }
            }
          ?>
          <a href="?delete=<?= $r['id'] ?>" class="btn btn-danger">Elimina</a>
          <a href="?edit=<?= $r['id'] ?>" class="btn">Modifica</a>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
