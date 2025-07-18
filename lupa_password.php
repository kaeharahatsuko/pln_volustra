<?php
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password_baru = $_POST['password_baru'] ?? '';
  $konfirmasi = $_POST['konfirmasi'] ?? '';

  if (!$username || !$password_baru || !$konfirmasi) {
    $error = "Semua kolom wajib diisi.";
  } elseif ($password_baru !== $konfirmasi) {
    $error = "Konfirmasi password tidak cocok.";
  } else {
    // Cek apakah username ada
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($query) === 1) {
      // Update password
      $hash = password_hash($password_baru, PASSWORD_DEFAULT);
      $update = mysqli_query($conn, "UPDATE users SET password = '$hash' WHERE username = '$username'");
      if ($update) {
        $success = "Password berhasil diubah. Silakan login kembali.";
      } else {
        $error = "Gagal memperbarui password. Silakan coba lagi.";
      }
    } else {
      $error = "Username tidak ditemukan.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password - PLN Pascabayar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #c2e9fb, #f9d1d1);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card-custom {
      background: #fff;
      border-radius: 1.5rem;
      padding: 2rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 450px;
    }
    .btn-primary {
      background-color: #007bff;
    }
  </style>
</head>
<body>
<div class="card-custom">
  <h3 class="mb-4 text-center text-primary">Reset Password</h3>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="username" class="form-label">Username Anda</label>
      <input type="text" name="username" id="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="password_baru" class="form-label">Password Baru</label>
      <input type="password" name="password_baru" id="password_baru" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="konfirmasi" class="form-label">Konfirmasi Password</label>
      <input type="password" name="konfirmasi" id="konfirmasi" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
  </form>

  <div class="mt-3 text-center">
    <a href="login.php" class="text-decoration-none text-dark">← Kembali ke Login</a>
  </div>
</div>
</body>
</html>
