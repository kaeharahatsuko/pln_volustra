<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
include 'db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pembayaran | Volustra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background: linear-gradient(135deg, #add8ff, #ffb3e6);
      font-family: 'Segoe UI', sans-serif;
      margin-top: 90px;
      min-height: 100vh;
    }

    .navbar-custom {
      background-color: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .nav-link {
      font-weight: 500;
    }

    .card-custom {
      border-radius: 1.5rem;
      background-color: white;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .table thead {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary" href="user_dashboard.php">
      <i class="bi bi-lightning-fill text-warning"></i> Volustra
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <?php if ($_SESSION['role'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="dashboard_admin.php"><i class="bi bi-speedometer2"></i> Dashboard Admin</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="user_dashboard.php"><i class="bi bi-house-door-fill"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="riwayat.php"><i class="bi bi-clock-history"></i> History</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="bantuan.php"><i class="bi bi-info-circle"></i> Help</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout (<?= $_SESSION['username'] ?>)</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container py-5">
  <div class="card card-custom">
    <h3 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> Payment History</h3>

    <form method="GET" class="mb-4">
      <label for="id_pelanggan" class="form-label">Enter Customer ID :</label>
      <div class="input-group">
        <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" required placeholder="Contoh: 071200031710">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
      </div>
    </form>

    <?php
    if (isset($_GET['id_pelanggan'])) {
      $id = mysqli_real_escape_string($conn, $_GET['id_pelanggan']);

      $query = mysqli_query($conn, "
        SELECT tg.id_tagihan, tg.bulan, tg.tahun, tg.jumlah, 
               tr.metode_pembayaran, tr.no_referensi, tr.biaya_transaksi, 
               tr.total_bayar, tr.tanggal_bayar
        FROM transaksi tr
        JOIN tagihan tg ON tr.id_tagihan = tg.id_tagihan
        JOIN pelanggan p ON tg.id_pelanggan = p.id_pelanggan
        WHERE p.id_pelanggan = '$id'
        ORDER BY tr.tanggal_bayar DESC
      ");

      if (mysqli_num_rows($query) > 0) {
        echo "<div class='table-responsive mt-4'>
                <table class='table table-bordered table-striped'>
                  <thead class='text-center'>
                    <tr>
                      <th>Bulan</th>
                      <th>Tahun</th>
                      <th>Tagihan</th>
                      <th>Metode</th>
                      <th>Referensi</th>
                      <th>Biaya</th>
                      <th>Total</th>
                      <th>Tanggal Bayar</th>
                    </tr>
                  </thead>
                  <tbody>";
        while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>{$row['bulan']}</td>
                  <td>{$row['tahun']}</td>
                  <td>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
                  <td>{$row['metode_pembayaran']}</td>
                  <td>{$row['no_referensi']}</td>
                  <td>Rp " . number_format($row['biaya_transaksi'], 0, ',', '.') . "</td>
                  <td>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</td>
                  <td>{$row['tanggal_bayar']}</td>
                </tr>";
        }
        echo "</tbody></table></div>";
      } else {
        echo "<div class='alert alert-warning mt-4'>Tidak ada riwayat pembayaran untuk ID <strong>$id</strong>.</div>";
      }
    }
    ?>
  </div>
</div>

</body>
</html>
