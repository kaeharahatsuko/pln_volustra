<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bantuan - Volustra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background: linear-gradient(135deg, #add8ff, #ffb3e6);
      font-family: 'Segoe UI', sans-serif;
      margin-top: 90px;
      min-height: 100vh;
    }
    .navbar-custom {
      background-color: white;
      border-radius: 0 0 1rem 1rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      font-weight: bold;
      color: #007bff !important;
    }
    .nav-link {
      font-weight: 500;
    }
    .card-custom {
      border-radius: 1rem;
      background-color: white;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand text-primary" href="user_dashboard.php">
      ⚡ Volustra
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="user_dashboard.php"><i class="bi bi-house-door-fill"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="riwayat.php"><i class="bi bi-clock-history"></i> History</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="bantuan.php"><i class="bi bi-question-circle-fill"></i> Help</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten Bantuan -->
<div class="container py-5">
  <div class="card card-custom">
    <h3 class="fw-bold mb-4">Help Center</h3>

    <div class="accordion" id="faqAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
            Bagaimana cara membayar tagihan?
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show">
          <div class="accordion-body">
            Masukkan ID pelanggan Anda di halaman Beranda, kemudian pilih metode pembayaran dan isi detail pembayaran. Klik "Bayar Sekarang".
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
            Apa itu biaya transaksi Rp 2.500?
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse">
          <div class="accordion-body">
            Biaya transaksi adalah biaya tambahan untuk memproses pembayaran secara online melalui sistem kami.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
            Mengapa tagihan saya tidak muncul?
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse">
          <div class="accordion-body">
            Pastikan ID pelanggan yang dimasukkan benar dan belum melakukan pembayaran. Tagihan hanya muncul jika statusnya "Belum Bayar".
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
            Siapa yang bisa saya hubungi jika mengalami kendala?
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse">
          <div class="accordion-body">
            Anda dapat menghubungi layanan pelanggan kami melalui email di <strong>support@volustra.com</strong> atau WhatsApp di <strong>0712-1017-2003</strong>.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
