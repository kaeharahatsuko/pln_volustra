<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../db.php';

// Statistik
$total_pelanggan   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggan"))['total'];
$total_tagihan     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tagihan"))['total'];
$total_transaksi   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi"))['total'];
$total_pendapatan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) AS total FROM transaksi"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Volustra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 90px;
    }
    .navbar-custom {
      background-color: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .card-custom {
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .icon {
      font-size: 2rem;
      color: #34699A;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary" href="admin_dashboard.php">
      <i class="bi bi-lightning-fill text-warning"></i> Volustra
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link" href="kelola_pelanggan.php"><i class="bi bi-people"></i> Customers</a></li>
        <li class="nav-item"><a class="nav-link" href="kelola_tagihan.php"><i class="bi bi-receipt-cutoff"></i> Bill</a></li>
        <li class="nav-item"><a class="nav-link" href="kelola_transaksi.php"><i class="bi bi-arrow-left-right"></i> Transaction</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan_admin.php"><i class="bi bi-bar-chart"></i> Report</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout (<?= $_SESSION['username'] ?>)</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container mt-4">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card card-custom p-4 text-center">
        <div class="icon mb-2"><i class="bi bi-people"></i></div>
        <h5>Total Customers</h5>
        <h4 class="fw-bold"><?= $total_pelanggan ?></h4>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-custom p-4 text-center">
        <div class="icon mb-2"><i class="bi bi-receipt"></i></div>
        <h5>Total Bill</h5>
        <h4 class="fw-bold"><?= $total_tagihan ?></h4>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-custom p-4 text-center">
        <div class="icon mb-2"><i class="bi bi-cash-coin"></i></div>
        <h5>Total Transactions</h5>
        <h4 class="fw-bold"><?= $total_transaksi ?></h4>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-custom p-4 text-center">
        <div class="icon mb-2"><i class="bi bi-wallet2"></i></div>
        <h5>Total Income</h5>
        <h4 class="fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h4>
      </div>
    </div>
  </div>

  <!-- Grafik -->
  <div class="card card-custom mt-5 p-4">
    <h5 class="mb-4">Monthly payment graph</h5>
    <canvas id="grafikTransaksi"></canvas>
  </div>
</div>

<script>
  const ctx = document.getElementById('grafikTransaksi').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
      datasets: [{
        label: 'Number of transactions',
        backgroundColor: '#77BEF0',
        data: [5, 8, 12, 6, 10, 14, 7, 9, 11, 13, 15, 6] // Ganti dengan data dinamis jika ingin
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
</body>
</html>
