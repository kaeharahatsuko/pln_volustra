<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Transaksi - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Laporan Transaksi PLN</h2>

  <table class="table table-bordered bg-white">
    <thead>
      <tr>
        <th>ID Tagihan</th>
        <th>Pelanggan</th>
        <th>Bulan</th>
        <th>Tagihan</th>
        <th>Metode</th>
        <th>Total</th>
        <th>Tanggal Bayar</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = mysqli_query($conn, "
        SELECT p.nama, p.id_pelanggan, tg.bulan, tg.jumlah, tr.*
        FROM transaksi tr
        JOIN tagihan tg ON tr.id_tagihan = tg.id_tagihan
        JOIN pelanggan p ON tg.id_pelanggan = p.id_pelanggan
        ORDER BY tr.tanggal_bayar DESC
      ");
      while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>
          <td>{$row['id_tagihan']}</td>
          <td>{$row['nama']}<br><small>{$row['id_pelanggan']}</small></td>
          <td>{$row['bulan']} {$row['tahun']}</td>
          <td>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</td>
          <td>{$row['metode_pembayaran']}</td>
          <td>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</td>
          <td>{$row['tanggal_bayar']}</td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
