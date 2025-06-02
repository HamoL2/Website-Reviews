<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: accedi.php");
  exit;
}

$user_id = $_SESSION['user']['id'];
$recensione_id = $_POST['recensione_id'];
$oggi = date('Y-m-d');
$inizio_mese = date('Y-m-01');

$stmt = $pdo->prepare("SELECT * FROM voto WHERE recensione_id=? AND utente_id=? AND data_voto >= ?");
$stmt->execute([$recensione_id, $user_id, $inizio_mese]);
$voto_esistente = $stmt->fetch();

if (!$voto_esistente) {
  $pdo->prepare("INSERT INTO voto (recensione_id, utente_id, data_voto) VALUES (?, ?, ?)")
      ->execute([$recensione_id, $user_id, $oggi]);
  $pdo->prepare("UPDATE recensione SET mi_piace = mi_piace + 1 WHERE id = ?")
      ->execute([$recensione_id]);
}

header("Location: index.php");
exit;
