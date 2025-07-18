<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../db.php';

// Hapus transaksi
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi='$id'");
  header("Location: kelola_transaksi.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Transaksi - Admin</title>
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
    .btn-danger {
      font-size: 0.875rem;
    }
    .btn-cetak {
      float: right;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary">Manage Transaction</h3>
    <a href="#" onclick="window.print()" class="btn btn-outline-secondary btn-sm btn-cetak">
      <i class="bi bi-printer"></i> Print
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark text-center">
        <tr>
          <th>ID Transaction</th>
          <th>ID Bill</th>
          <th>Customer Name</th>
          <th>ID Customer</th>
          <th>Method</th>
          <th>Reference No</th>
          <th>Cost Transaction</th>
          <th>Total Payment</th>
          <th>Payment Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query($conn, "
          SELECT tr.*, p.nama, p.id_pelanggan
          FROM transaksi tr
          JOIN tagihan tg ON tr.id_tagihan = tg.id_tagihan
          JOIN pelanggan p ON tg.id_pelanggan = p.id_pelanggan
          ORDER BY tr.tanggal_bayar DESC
        ");

        if (mysqli_num_rows($query) > 0) {
          while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
              <td>{$row['id_transaksi']}</td>
              <td>{$row['id_tagihan']}</td>
              <td>" . htmlspecialchars($row['nama']) . "</td>
              <td>{$row['id_pelanggan']}</td>
              <td>{$row['metode_pembayaran']}</td>
              <td>{$row['no_referensi']}</td>
              <td>Rp " . number_format($row['biaya_transaksi'], 0, ',', '.') . "</td>
              <td>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</td>
              <td>" . date('d-m-Y', strtotime($row['tanggal_bayar'])) . "</td>
              <td class='text-center'>
                <a href='?hapus={$row['id_transaksi']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Hapus transaksi ini?')\">
                  <i class='bi bi-trash'></i>
                </a>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='10' class='text-center text-muted'>Belum ada transaksi tercatat.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
