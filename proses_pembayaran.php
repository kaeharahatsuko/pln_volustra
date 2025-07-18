<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tagihan       = $_POST['id_tagihan'] ?? '';
    $metode           = $_POST['metode_pembayaran'] ?? '';
    $ref              = $_POST['no_referensi'] ?? '';
    $biaya_transaksi  = $_POST['biaya_transaksi'] ?? 2500;
    $total_bayar      = $_POST['total_bayar'] ?? 0;

    // Validasi dasar
    if (empty($id_tagihan) || empty($metode) || empty($ref)) {
        die("Data tidak lengkap.");
    }

    // Simpan transaksi
    $insert = mysqli_query($conn, "
        INSERT INTO transaksi (id_tagihan, metode_pembayaran, no_referensi, biaya_transaksi, total_bayar)
        VALUES ('$id_tagihan', '$metode', '$ref', '$biaya_transaksi', '$total_bayar')
    ");

    // Update status tagihan
    $update = mysqli_query($conn, "
        UPDATE tagihan SET status = 'Sudah Bayar' WHERE id_tagihan = '$id_tagihan'
    ");

    // Jika sukses, redirect ke struk
    if ($insert && $update) {
        header("Location: cetak_struk.php?id_tagihan=$id_tagihan");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan transaksi.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>Akses tidak sah.</div>";
}
?>
