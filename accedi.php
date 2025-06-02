<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM registrato WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && $user['password'] === $password) {
    $_SESSION['user'] = $user;
    header("Location: index.php");
    exit;
  } else {
    $errore = "Credenziali errate";
  }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Accedi</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="main-container">
    <div class="form-container">
      <h2>Accedi</h2>
      <?php if (isset($errore)): ?><div class="message error"><?= $errore ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </main>
</body>
</html>
