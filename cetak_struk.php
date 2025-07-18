<?php
include 'db.php';

$id_tagihan = $_GET['id_tagihan'] ?? '';

if (!$id_tagihan) {
    echo "ID tagihan tidak ditemukan.";
    exit;
}

// Ambil detail tagihan dan transaksi
$query = mysqli_query($conn, "
  SELECT 
    p.nama, p.alamat,
    t.bulan, t.tahun, t.jumlah,
    tr.metode_pembayaran, tr.no_referensi, tr.biaya_transaksi, tr.total_bayar
  FROM tagihan t
  JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
  JOIN transaksi tr ON tr.id_tagihan = t.id_tagihan
  WHERE t.id_tagihan = '$id_tagihan'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
      padding: 20px;
    }
    .struk {
      max-width: 500px;
      margin: auto;
      border: 1px solid #ddd;
      padding: 30px;
      border-radius: 10px;
    }
    h4 {
      text-align: center;
      margin-bottom: 25px;
      color: #113F67;
    }
    .btn-print {
      display: none;
    }
    @media print {
      .btn-print {
        display: none !important;
      }
    }
  </style>
</head>
<body onload="window.print()">

<div class="struk">
  <h4>Proof Payment of Volustra</h4>
  
  <p><strong>Name:</strong> <?= $data['nama'] ?></p>
  <p><strong>Address:</strong> <?= $data['alamat'] ?></p>
  <hr>
  <p><strong>Month:</strong> <?= $data['bulan'] ?> <?= $data['tahun'] ?></p>
  <p><strong>Total Bill:</strong> Rp <?= number_format($data['jumlah'], 0, ',', '.') ?></p>
  <p><strong>Cost Transaction:</strong> Rp <?= number_format($data['biaya_transaksi'], 0, ',', '.') ?></p>
  <hr>
  <p><strong>Total Payment:</strong> Rp <?= number_format($data['total_bayar'], 0, ',', '.') ?></p>
  <p><strong>Method:</strong> <?= $data['metode_pembayaran'] ?></p>
  <p><strong>Reference No:</strong> <?= $data['no_referensi'] ?></p>
  <hr>
  <p class="text-center small">Thankyou for making payment.<br>⚡Volustra⚡</p>

  <div class="text-center mt-3 btn-print">
    <a href="index.php" class="btn btn-primary">Back to home</a>
  </div>
</div>

</body>
</html>
