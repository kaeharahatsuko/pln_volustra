<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Transaksi - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      padding-top: 80px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    @media print {
      .btn-print, nav {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary">Payment Transaction Reports</h3>
    <button class="btn btn-outline-secondary btn-sm btn-print" onclick="window.print()">
      <i class="bi bi-printer"></i> Print
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark text-center">
        <tr>
          <th>No</th>
          <th>ID Bill</th>
          <th>Customer Name</th>
          <th>Month</th>
          <th>Bill</th>
          <th>Methode</th>
          <th>Total Payment</th>
          <th>Payment Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query($conn, "
          SELECT p.nama, p.id_pelanggan, tg.bulan, tg.tahun, tg.jumlah, tr.*
          FROM transaksi tr
          JOIN tagihan tg ON tr.id_tagihan = tg.id_tagihan
          JOIN pelanggan p ON tg.id_pelanggan = p.id_pelanggan
          ORDER BY tr.tanggal_bayar DESC
        ");

        if (mysqli_num_rows($query) > 0):
          $no = 1;
          while ($row = mysqli_fetch_assoc($query)):
        ?>
          <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= $row['id_tagihan'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?><br><small class="text-muted"><?= $row['id_pelanggan'] ?></small></td>
            <td><?= $row['bulan'] . ' ' . $row['tahun'] ?></td>
            <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
            <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_bayar'])) ?></td>
          </tr>
        <?php endwhile; else: ?>
          <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data transaksi pembayaran.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
