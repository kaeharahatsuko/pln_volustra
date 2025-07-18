<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../db.php';

// Hapus data tagihan
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM tagihan WHERE id_tagihan = '$id'");
  header("Location: kelola_tagihan.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Tagihan - Admin</title>
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
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary">Manage Bill</h4>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-printer"></i> Print
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark text-center">
        <tr>
          <th>ID Bill</th>
          <th>Customer Name</th>
          <th>ID Customer</th>
          <th>Month</th>
          <th>Year</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query($conn, "
          SELECT t.*, p.nama FROM tagihan t
          JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
          ORDER BY t.tahun DESC, FIELD(t.bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')
        ");

        if (mysqli_num_rows($query) > 0) {
          while ($row = mysqli_fetch_assoc($query)) {
            $status = $row['status'] === 'Belum Bayar' 
                      ? "<span class='badge bg-danger'>Not yet Paid</span>" 
                      : "<span class='badge bg-success'>Paid off</span>";

            echo "<tr>
              <td>{$row['id_tagihan']}</td>
              <td>{$row['nama']}</td>
              <td>{$row['id_pelanggan']}</td>
              <td>{$row['bulan']}</td>
              <td>{$row['tahun']}</td>
              <td>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
              <td class='text-center'>{$status}</td>
              <td class='text-center'>
                <a href='?hapus={$row['id_tagihan']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Hapus tagihan ini?')\">
                  <i class='bi bi-trash'></i>
                </a>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='8' class='text-center text-muted'>Tidak ada data tagihan.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
