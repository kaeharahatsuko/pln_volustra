<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

$id_pelanggan = $_GET['id'] ?? '';
$error = '';
$success = '';

if ($id_pelanggan) {
  $result = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
  $pelanggan = mysqli_fetch_assoc($result);

  if (!$pelanggan) {
    $error = "Pelanggan tidak ditemukan.";
  }
} else {
  $error = "ID pelanggan tidak diberikan.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $password_baru = $_POST['password_baru'] ?? '';
  $konfirmasi = $_POST['konfirmasi'] ?? '';

  if (!$password_baru || !$konfirmasi) {
    $error = "Silakan isi semua kolom.";
  } elseif ($password_baru !== $konfirmasi) {
    $error = "Konfirmasi password tidak cocok.";
  } else {
    $hash = password_hash($password_baru, PASSWORD_DEFAULT);
    $update = mysqli_query($conn, "UPDATE users SET password='$hash' WHERE username='$id_pelanggan'");

    if ($update) {
      $success = "Password berhasil direset.";
    } else {
      $error = "Gagal mengubah password.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password Pelanggan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6fa;
      padding-top: 70px;
    }
    .card-custom {
      max-width: 550px;
      margin: auto;
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .form-label {
      font-weight: 500;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
  <div class="card card-custom mt-5">
    <h4 class="mb-4 text-primary"><i class="bi bi-key me-1"></i>Reset Password Pelanggan</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (isset($pelanggan)): ?>
      <p><strong>Nama:</strong> <?= htmlspecialchars($pelanggan['nama']) ?></p>
      <p><strong>ID Pelanggan:</strong> <?= htmlspecialchars($pelanggan['id_pelanggan']) ?></p>

      <form method="POST">
        <div class="mb-3">
          <label for="password_baru" class="form-label">Password Baru</label>
          <input type="password" name="password_baru" id="password_baru" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="konfirmasi" class="form-label">Konfirmasi Password</label>
          <input type="password" name="konfirmasi" id="konfirmasi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-shield-lock-fill me-1"></i> Reset Password</button>
        <a href="kelola_pelanggan.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
      </form>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
