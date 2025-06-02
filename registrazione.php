<?php
require_once 'db.php';

$errore = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $cognome = $_POST['cognome'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $data_nascita = $_POST['data_nascita'];

  try {
    // Verifica se esiste già l'email
    $check = $pdo->prepare("SELECT id FROM registrato WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
      $errore = "Questa email è già registrata.";
    } else {
      // Inserisce nuovo utente
      $stmt = $pdo->prepare("INSERT INTO registrato (nome, cognome, email, password, data_nascita, ruolo) VALUES (?, ?, ?, ?, ?, 'utente')");
      $stmt->execute([$nome, $cognome, $email, $password, $data_nascita]);
      header("Location: accedi.php");
      exit;
    }
  } catch (PDOException $e) {
    $errore = "Errore durante la registrazione. Riprova più tardi.";
  }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Registrazione</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="main-container">
    <div class="form-container">
      <h2>Registrati</h2>
      <?php if ($errore): ?><div class="message error"><?= htmlspecialchars($errore) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group"><label>Nome</label><input type="text" name="nome" required></div>
        <div class="form-group"><label>Cognome</label><input type="text" name="cognome" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <div class="form-group"><label>Data di nascita</label><input type="date" name="data_nascita"></div>
        <button type="submit" class="btn">Registrati</button>
      </form>
    </div>
  </main>
</body>
</html>
