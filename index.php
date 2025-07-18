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
  <title>ListrikKu - Pembayaran PLN Pascabayar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background: linear-gradient(135deg, #add8ff, #ffb3e6);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      padding-top: 90px;
    }

    .card-custom {
      border-radius: 1.5rem;
      padding: 2rem;
      background-color: white;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .btn-pink {
      background-color: #749BC2;
      color: white;
    }

    .btn-pink:hover {
      background-color: #749BC2;
    }

    .illustration {
      max-width: 100%;
      height: auto;
    }

    .navbar-custom {
      background-color: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .nav-link {
      font-weight: 500;
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
          <a class="nav-link" href="riwayat.php"><i class="bi bi-clock-history"></i> History</a>
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
    <div class="row align-items-center">
      <div class="col-md-6">
        <h2 class="fw-bold text-primary mb-3">Check your electricity bill</h2>
        <p>Enter your customer ID to view your bill details.</p>

        <form method="POST">
          <div class="mb-3">
            <label for="id_pelanggan" class="form-label">ID Customer</label>
            <input type="text" class="form-control" id="id_pelanggan" name="id_pelanggan" placeholder="Enter your Customer ID" required>
          </div>
          <button type="submit" class="btn btn-pink w-100">Check Bill</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id = mysqli_real_escape_string($conn, $_POST['id_pelanggan']);
          $biaya_transaksi = 2500;

          // Cek apakah ID pelanggan valid
          $cekPelanggan = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id'");
          if (mysqli_num_rows($cekPelanggan) == 0) {
            echo "<div class='alert alert-danger mt-4'>ID Customer Not Found.</div>";
            return;
          }

          // Cek tagihan belum bayar
          $query = mysqli_query($conn, "
            SELECT t.id_tagihan, p.nama, p.alamat, t.bulan, t.tahun, t.jumlah 
            FROM pelanggan p 
            JOIN tagihan t ON p.id_pelanggan = t.id_pelanggan 
            WHERE p.id_pelanggan = '$id' AND t.status = 'Belum Bayar'
            ORDER BY t.id_tagihan DESC
            LIMIT 1
          ");

          // Jika tidak ada tagihan "Belum Bayar", buat tagihan baru
          if (mysqli_num_rows($query) == 0) {
            $bulan = date('F');
            $tahun = date('Y');
            $jumlah = rand(100000, 250000); // Tagihan acak

            mysqli_query($conn, "
              INSERT INTO tagihan (id_pelanggan, bulan, tahun, jumlah, status)
              VALUES ('$id', '$bulan', '$tahun', '$jumlah', 'Belum Bayar')
            ");

            // Ambil ulang tagihan terbaru
            $query = mysqli_query($conn, "
              SELECT t.id_tagihan, p.nama, p.alamat, t.bulan, t.tahun, t.jumlah 
              FROM pelanggan p 
              JOIN tagihan t ON p.id_pelanggan = t.id_pelanggan 
              WHERE p.id_pelanggan = '$id' AND t.status = 'Belum Bayar'
              ORDER BY t.id_tagihan DESC
              LIMIT 1
            ");
          }

          if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $total = $data['jumlah'] + $biaya_transaksi;
        ?>
          <div class='alert alert-success mt-4'>
            <h5 class='fw-bold'>Bill Details</h5>
            <p><strong>ID:</strong> <?= $id ?></p>
            <p><strong>Name:</strong> <?= $data['nama'] ?></p>
            <p><strong>Address:</strong> <?= $data['alamat'] ?></p>
            <p><strong>Month:</strong> <?= $data['bulan'] ?> <?= $data['tahun'] ?></p>
            <p><strong>Bill:</strong> Rp <?= number_format($data['jumlah'], 0, ',', '.') ?></p>
            <p><strong>Cost Transaction:</strong> Rp <?= number_format($biaya_transaksi, 0, ',', '.') ?></p>
            <hr>
            <p><strong>Total Payment:</strong> Rp <?= number_format($total, 0, ',', '.') ?></p>
          </div>

          <form method="POST" action="proses_pembayaran.php">
            <input type="hidden" name="id_tagihan" value="<?= $data['id_tagihan'] ?>">
            <input type="hidden" name="biaya_transaksi" value="<?= $biaya_transaksi ?>">
            <input type="hidden" name="total_bayar" value="<?= $total ?>">

            <div class="mb-3">
              <label class="form-label">Payment Method</label>
              <select name="metode_pembayaran" class="form-select" required>
                <option value="">-- Select Method --</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet</option>
                <option value="Virtual Account">Virtual Account</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Reference no</label>
              <input type="text" name="no_referensi" class="form-control" placeholder="Example: FEA101720" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Pay Now</button>
          </form>
        <?php
          } else {
            echo "<div class='alert alert-danger mt-4'>Bill not found or already pay off.</div>";
          }
        }
        ?>
      </div>

      <!-- Ilustrasi -->
      <div class="col-md-6 text-center">
        <img src="power.png" class="illustration" alt="PLN Image">
      </div>
    </div>
  </div>
</div>

</body>
</html>
