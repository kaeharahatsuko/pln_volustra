<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input kosong
    if (!$username || !$password) {
        $error = 'Harap isi username dan password.';
    } else {
        // Gunakan prepared statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit;
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Username tidak ditemukan!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>⚡Volustra⚡</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #add8ff, #ffb3e6);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      width: 100%;
      max-width: 450px;
      background: white;
      border-radius: 2rem;
      padding: 2.5rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .btn-pink {
      background-color: #456882;
      color: white;
    }

    .btn-pink:hover {
      background-color: #749BC2;
    }

    .form-control:focus {
      border-color: #749BC2;
      box-shadow: 0 0 0 0.2rem rgba(255, 95, 162, 0.25);
    }

    .text-center img {
      max-width: 100px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="text-center mb-4">
      <img src="power.png" alt="Logo">
      <h3 class="fw-bold text-primary">⚡ Volustra ⚡</h3>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-pink w-100">Login</button>
    </form>

    <div class="text-center mt-3">
      Don't have an account ? <a href="register.php" class="text-decoration-none text-primary fw-semibold">Register Here</a>
    </div>
    <div class="text-center mt-2">
      Forgot the password? <a href="lupa_password.php" class="text-decoration-none text-danger fw-semibold">Reset here</a>
    </div>
  </div>
</body>
</html>
