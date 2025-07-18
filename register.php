<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $nama     = trim($_POST['nama']);
    $alamat   = trim($_POST['alamat']);

    // Validasi kosong
    if ($username === '' || $password === '' || $nama === '' || $alamat === '') {
        $error = 'Semua kolom harus diisi.';
    } else {
        // Cek username sudah dipakai
        $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            // Insert ke tabel users
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed, $role);
            $stmt->execute();

            // Insert ke tabel pelanggan (gunakan username sebagai id_pelanggan)
            $stmt2 = $conn->prepare("INSERT INTO pelanggan (id_pelanggan, nama, alamat) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $username, $nama, $alamat);
            $stmt2->execute();

            // Set session & redirect
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: user_dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #add8ff, #ffb3e6);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      max-width: 480px;
      width: 100%;
      background: #fff;
      padding: 2rem;
      border-radius: 1.5rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="register-card">
    <h4 class="text-center text-primary mb-4">Create New Account</h4>
    <h5 class="text-center text-primary mb-4">⚡Volustra⚡</h5>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">ID Customer (Username)</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="nama" class="form-label">Full Name</label>
        <input type="text" name="nama" id="nama" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="alamat" class="form-label">Address</label>
        <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <div class="text-center mt-3">
      Already have account? <a href="login.php" class="text-decoration-none">Login here</a>
    </div>
  </div>
</body>
</html>
