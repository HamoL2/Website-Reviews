<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("SELECT r.*, b.nome AS nome_bibita, b.id AS bibita_id, reg.nome AS autore, reg.id AS autore_id 
                     FROM recensione r 
                     JOIN bibita b ON r.bibita_id = b.id 
                     JOIN registrato reg ON r.registrato_id = reg.id 
                     ORDER BY r.id DESC");
$recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Monster Reviews</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <div class="header-container">
    <div class="logo">Monster Reviews</div>
    <div class="nav-links">
      <?php if (!isset($_SESSION['user'])): ?>
        <a href="accedi.php" class="nav-link">Accedi</a>
        <a href="registrazione.php" class="nav-link">Registrati</a>
      <?php else: ?>
        <a href="logout.php" class="nav-link">Logout</a>
        <a href="statistiche.php" class="nav-link">Statistiche</a>
        <?php if ($_SESSION['user']['ruolo'] === 'moderatore' || $_SESSION['user']['ruolo'] === 'amministratore'): ?>
          <a href="modifica_recensione.php" class="nav-link">Gestione Recensioni</a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</header>
<main class="main-container">
  <?php foreach ($recensioni as $rec): ?>
    <div class="review-card">
      <div class="review-content">
        <div class="review-image">
          <img src="uploads/<?= htmlspecialchars($rec['id']) ?>.jpg" alt="Immagine Monster">
        </div>
        <div class="review-info">
          <h2><?= htmlspecialchars($rec['titolo']) ?></h2>
          <p class="review-description"><?= htmlspecialchars($rec['descrizione']) ?></p>
          <div class="rating-section">
            <div class="stars"><?= str_repeat('⭐', $rec['valutazione']) ?></div>
            <div class="rating-text">di <?= htmlspecialchars($rec['autore']) ?></div>
          </div>
        </div>
        <div class="vote-system">
          <form action="vota.php" method="POST">
            <input type="hidden" name="recensione_id" value="<?= $rec['id'] ?>">
            <button class="vote-button upvote" <?= isset($_SESSION['user']) ? '' : 'disabled' ?> title="Mi Piace">👍</button>
            <div class="vote-count"><?= $rec['mi_piace'] ?></div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</main>
</body>
</html>
