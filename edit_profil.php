<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

$username = $_SESSION['username'];
$error = '';
$success = '';

// Ambil data pelanggan berdasarkan ID (id_pelanggan = username)
$query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$username'");
$pelanggan = mysqli_fetch_assoc($query);

if (!$pelanggan) {
  $error = "Data pelanggan tidak ditemukan.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
  $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
  $password_lama = $_POST['password_lama'] ?? '';
  $password_baru = $_POST['password_baru'] ?? '';
  $konfirmasi_baru = $_POST['konfirmasi_baru'] ?? '';

  // Validasi nama & alamat
  if ($nama === '' || $alamat === '') {
    $error = "Nama dan alamat tidak boleh kosong.";
  } else {
    $update = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', alamat='$alamat' WHERE id_pelanggan='$username'");
    if (!$update) {
      $error = "Gagal memperbarui data profil.";
    }
  }

  // Ubah password jika diisi
  if ($password_lama || $password_baru || $konfirmasi_baru) {
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_baru)) {
      $error = "Semua kolom password harus diisi.";
    } elseif ($password_baru !== $konfirmasi_baru) {
      $error = "Konfirmasi password tidak cocok.";
    } else {
      $user_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
      $user = mysqli_fetch_assoc($user_query);

      if ($user && password_verify($password_lama, $user['password'])) {
        $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);
        $update_pw = mysqli_query($conn, "UPDATE users SET password='$hash_baru' WHERE username='$username'");
        if (!$update_pw) {
          $error = "Gagal mengubah password.";
        }
      } else {
        $error = "Password lama salah.";
      }
    }
  }

  if (!$error) {
    header("Location: user_dashboard.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #add8ff, #ffb3e6);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      padding-top: 80px;
    }
    .card-custom {
      border-radius: 1.5rem;
      padding: 2rem;
      background: #fff;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      max-width: 700px;
      margin: auto;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card card-custom">
    <h3 class="mb-4 text-primary">Edit Profil Pengguna</h3>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" name="nama" id="nama" value="<?= htmlspecialchars($pelanggan['nama'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" name="alamat" id="alamat" rows="3" required><?= htmlspecialchars($pelanggan['alamat'] ?? '') ?></textarea>
      </div>

      <hr>
      <h5 class="text-primary">Ubah Password (Opsional)</h5>
      <div class="mb-3">
        <label for="password_lama" class="form-label">Password Lama</label>
        <input type="password" class="form-control" name="password_lama" id="password_lama">
      </div>
      <div class="mb-3">
        <label for="password_baru" class="form-label">Password Baru</label>
        <input type="password" class="form-control" name="password_baru" id="password_baru">
      </div>
      <div class="mb-3">
        <label for="konfirmasi_baru" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" class="form-control" name="konfirmasi_baru" id="konfirmasi_baru">
      </div>

      <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
    </form>
  </div>
</div>
</body>
</html>
