<?php
include 'db.php';

if (isset($_POST['id_pelanggan'])) {
  $id = $_POST['id_pelanggan'];
  $biaya_transaksi = 2500;

  $query = mysqli_query($conn, "
    SELECT p.nama, p.alamat, t.bulan, t.tahun, t.jumlah 
    FROM pelanggan p 
    JOIN tagihan t ON p.id_pelanggan = t.id_pelanggan 
    WHERE p.id_pelanggan = '$id' AND t.status = 'Belum Bayar'
    LIMIT 1
  ");

  if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $total = $data['jumlah'] + $biaya_transaksi;
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <title>Struk Pembayaran</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <style>
        body {
          background: #f7f7f7;
          padding: 2rem;
        }
        .struk {
          background: white;
          padding: 2rem;
          border-radius: 1rem;
          max-width: 500px;
          margin: auto;
          box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
      </style>
    </head>
    <body>
      <div class="struk">
        <h4 class="text-center mb-4">Struk Pembayaran PLN Pascabayar</h4>
        <p><strong>ID Pelanggan:</strong> <?= $id ?></p>
        <p><strong>Nama:</strong> <?= $data['nama'] ?></p>
        <p><strong>Alamat:</strong> <?= $data['alamat'] ?></p>
        <p><strong>Bulan:</strong> <?= $data['bulan'] ?> <?= $data['tahun'] ?></p>
        <p><strong>Tagihan Listrik:</strong> Rp <?= number_format($data['jumlah'], 0, ',', '.') ?></p>
        <p><strong>Biaya Transaksi:</strong> Rp <?= number_format($biaya_transaksi, 0, ',', '.') ?></p>
        <hr>
        <h5><strong>Total Bayar:</strong> Rp <?= number_format($total, 0, ',', '.') ?></h5>

        <button class="btn btn-primary mt-3 w-100" onclick="window.print()">Cetak Struk</button>
        <a href="index.php" class="btn btn-link mt-2 w-100">← Kembali ke Beranda</a>
      </div>
    </body>
    </html>

    <?php
  } else {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Tagihan tidak ditemukan atau sudah dibayar.</div><a href='index.php'>← Kembali</a></div>";
  }
}
?>
