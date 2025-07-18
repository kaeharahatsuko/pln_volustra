<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
include '../db.php';

// Hapus pelanggan
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = '$id'");
  mysqli_query($conn, "DELETE FROM users WHERE username = '$id'");
  header("Location: kelola_pelanggan.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pelanggan - Admin</title>
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
    <h4 class="text-primary">Manage Customers</h4>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-printer"></i> Print
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
      <thead class="table-dark text-center">
        <tr>
          <th>ID Customers</th>
          <th>Name</th>
          <th>Address</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr>
              <td>{$row['id_pelanggan']}</td>
              <td>{$row['nama']}</td>
              <td>{$row['alamat']}</td>
              <td class='text-center'>
                <a href='reset_password.php?id={$row['id_pelanggan']}' class='btn btn-sm btn-warning mb-1'>
                  <i class='bi bi-key'></i> Reset Password
                </a>
                <a href='?hapus={$row['id_pelanggan']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus pelanggan ini?')\">
                  <i class='bi bi-trash'></i> Delete
                </a>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='4' class='text-center text-muted'>Belum ada data pelanggan.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
