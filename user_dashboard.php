<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

$username = $_SESSION['username'];

// Ambil data pelanggan berdasarkan username
$query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$username'");
$pelanggan = mysqli_fetch_assoc($query);

$nama = $pelanggan['nama'] ?? '-';
$id_pelanggan = $pelanggan['id_pelanggan'] ?? '-';
$alamat = $pelanggan['alamat'] ?? '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Volustra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #f0f5ff, #ffe6f0);
      font-family: 'Segoe UI', sans-serif;
      padding-top: 80px;
    }
    .card-custom {
      border-radius: 1.5rem;
      padding: 2rem;
      background: white;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      height: 100%;
    }
    .grafik-container {
      height: 280px;
    }
    .username-highlight {
      font-weight: 700;
      color: #4ED7F1;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary" href="#"><i class="bi bi-lightning-charge-fill me-2"></i>Volustra</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="riwayat.php"><i class="bi bi-clock-history me-1"></i>History</a></li>
        <li class="nav-item"><a class="nav-link" href="edit_profil.php"><i class="bi bi-person-lines-fill me-1"></i>Edit Profile</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card card-custom">
        <h5 class="text-secondary mb-2">Hallo, <span class="username-highlight"><?= htmlspecialchars($nama) ?></span></h5>
        <p class="mb-2"><i class="bi bi-person-badge"></i> <strong>ID Customer:</strong> <?= htmlspecialchars($id_pelanggan) ?></p>
        <p class="mb-4"><i class="bi bi-geo-alt-fill"></i> <strong>Address:</strong> <?= htmlspecialchars($alamat) ?></p>
        <div class="d-grid gap-2">
          <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-receipt"></i> Check Bill</a>
          <a href="riwayat.php" class="btn btn-outline-success"><i class="bi bi-journal-text"></i> Payment History</a>
          <a href="edit_profil.php" class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i> Edit Profile</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-custom text-center">
        <h6 class="text-primary mb-3"><i class="bi bi-bar-chart-fill"></i> Monthly Electricity Usage Graph</h6>
        <canvas id="grafikPemakaian" class="grafik-container"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
const ctx = document.getElementById('grafikPemakaian').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
    datasets: [{
      label: 'Pemakaian kWh',
      data: [90, 120, 100, 140, 130, 150, 160],
      backgroundColor: function(context) {
        const chart = context.chart;
        const {ctx, chartArea} = chart;
        if (!chartArea) return;
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#ADEED9');
        gradient.addColorStop(1, '#0ABAB5');
        return gradient;
      },
      borderRadius: 5
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 20
        },
        title: {
          display: true,
          text: 'Pemakaian (kWh)'
        }
      }
    }
  }
});
</script>
</body>
</html>
