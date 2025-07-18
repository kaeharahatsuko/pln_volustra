<?php
include 'db.php';

if (isset($_GET['id_transaksi'])) {
    $id_transaksi = mysqli_real_escape_string($conn, $_GET['id_transaksi']);

    $query = mysqli_query($conn, "
        SELECT tr.*, p.nama, p.alamat, tg.bulan, tg.tahun, tg.jumlah 
        FROM transaksi tr
        JOIN tagihan tg ON tr.id_tagihan = tg.id_tagihan
        JOIN pelanggan p ON tg.id_pelanggan = p.id_pelanggan
        WHERE tr.id_transaksi = '$id_transaksi'
    ");

    if ($data = mysqli_fetch_assoc($query)) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .struk {
            border: 1px solid #000;
            padding: 20px;
            width: 400px;
            margin: 40px auto;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        p { margin: 5px 0; }
        hr { margin: 15px 0; }
    </style>
</head>
<body>

<div class="struk">
    <h2>Struk Pembayaran PLN</h2>
    <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
    <p><strong>Alamat:</strong> <?= htmlspecialchars($data['alamat']) ?></p>
    <p><strong>Bulan/Tahun:</strong> <?= htmlspecialchars($data['bulan']) ?>/<?= htmlspecialchars($data['tahun']) ?></p>
    <p><strong>Jumlah Tagihan:</strong> Rp <?= number_format($data['jumlah'], 0, ',', '.') ?></p>
    <hr>
    <p><strong>Metode:</strong> <?= htmlspecialchars($data['metode_pembayaran']) ?></p>
    <p><strong>No Referensi:</strong> <?= htmlspecialchars($data['no_referensi']) ?></p>
    <p><strong>Biaya Transaksi:</strong> Rp <?= number_format($data['biaya_transaksi'], 0, ',', '.') ?></p>
    <p><strong>Total Dibayar:</strong> Rp <?= number_format($data['total_bayar'], 0, ',', '.') ?></p>
    <p><strong>Tanggal Bayar:</strong> <?= htmlspecialchars($data['tanggal_bayar']) ?></p>
</div>

<button id="btnPrint" style="display:none;">Cetak</button>

<script>
    document.getElementById('btnPrint').addEventListener('click', function() {
        window.print();
    });

    window.onload = function() {
        // Simulasi klik tombol cetak
        document.getElementById('btnPrint').click();
    }
</script>

</body>
</html>
<?php
    } else {
        echo "Data transaksi tidak ditemukan.";
    }
} else {
    echo "ID transaksi tidak diberikan.";
}
?>
