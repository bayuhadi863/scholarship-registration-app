<?php
  session_start();
  require "koneksi.php";
  // Cek apakah sudah login dan apakah yg login adalah Pengguna
  if (isset($_SESSION["login_id"])) {
    $id = $_SESSION["login_id"];
    //Query hitung jml data admin
    $query = "SELECT COUNT(*) as jumlahAdmin FROM admin WHERE id_admin = $id";
    $stmt = $koneksi->prepare($query);
    $stmt->execute();
    $resultAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlahAdmin = $resultAdmin['jumlahAdmin'];
    if ($jumlahAdmin != 0) {
      echo '
        <script> 
        alert("Anda bukan pengguna.Logout, lalu Login sebagai pengguna untuk masuk ke halaman web ini.");
        window.location.href = "admin_dashboard.php";  
        </script>';
      exit;
    }
  }

  //Jika Modal login di tekan oke maka masuk ke halaman login
  if(isset($_POST["login"])) {
    header("Location: login_page.php");
    exit;
  }

  //Pagination
  $jumlahDataPerHalaman = 6;
  $query = "SELECT COUNT(*) as jumlah FROM beasiswa";
  $stmt = $koneksi->prepare($query);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $jumlahData = $result['jumlah'];
  $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
  $halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
  $awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
  //select data beasiswa dg limit 6 per halaman
  $lihatData = "SELECT * FROM beasiswa LIMIT $awalData , $jumlahDataPerHalaman";

  //jika beasiswa sudah ada di confirm page (sudah pernah didaftar)
  if (isset($_POST["sudahAda"])) {
    echo "
      <script>
        alert('Beasiswa ini sudah ada di Confirmation Page Anda.');
        window.location.href = 'confirm_page.php';
      </script>
    ";
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengguna - Halaman Beasiswa</title>
    <!-- link bootstrap -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- link css -->
    <link rel="stylesheet" href="styles/beasiswa.css" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary" id="navbar">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
          <!-- logo -->
          <img src="img/logo1.png" alt="logo" id="logo" class="ms-5" />
          <a class="navbar-brand" href="home_page.php" id="logo-link">ScholarGate</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <!-- Link Menu -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="home_page.php" id="menu-link">Beranda</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#beasiswa-container" id="menu-link">Beasiswa</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="home_page.php" id="menu-link">Hubungi Kami</a>
              </li>
            </ul>
            <!-- Tombol Get Started -->
            <?php
              if(isset($_SESSION["login_id"])) {
                $id = $_SESSION["login_id"];
                $selectPengguna = "SELECT * FROM pengguna WHERE id_pengguna = $id";
            ?>
            <?php
              foreach ($koneksi->query($selectPengguna) as $row) {
            ?>
            <a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample" style="text-decoration: none; color: whitesmoke; font-weight: bold; font-size: 18px; padding-right: 20px;"><?php echo $row["nama_lengkap"]; ?> <i class="bi bi-person-fill"></i></a>
            <?php
              }
            ?>
            <?php
              } else {
            ?>
            <a class="btn btn-primary me-5" href="login_page.php" role="button" id="get-started-button">Masuk/Daftar</a>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
    </nav>
    <!-- Headline -->
    <div class="container-fluid" id="headline-container">
      <div class="row">
        <div class="col-sm-4">
          <img src="img/person2.png" alt="" id="person-img">
        </div>
        <div class="col-sm-8 text-end" id="headline-text-container">
          <h2 id="headline">Temukan Beasiswa yang Tersedia untuk Membuka Peluangmu ke Masa Depan.</h2>
          <p>Nikmati cara termudah mendaftar berbagai jenis beasiswa. Cari jurusan beasiswa impianmu, daftar sekarang, dan dapatkan keputusan kelayakan penerimaan hanya dalam hitungan menit.</p>
        </div>
      </div>
    </div>
    
    <div class="container-fluid text-center" id="beasiswa-container">
      <div class="row row-cols-1 row-cols-md-3 g-4 d-flex justify-content-center">
        <!-- Tampilkan data -->
        <?php
          foreach ($koneksi->query($lihatData) as $row){
        ?>
        <div class="col" id="card-col">
          <div class="card" id="card">
            <h5 class="card-title" id="card-title"><?php echo $row['1'] ?></h5>
            <p class="card-text">Penyelenggara: <?php echo $row['2'] ?>.</p>
            <p class="card-text">Untuk mahasiswa yang sedang menempuh pendidikan <?php echo $row['3'] ?>.</p>
            <p class="card-text">Pendaftaran dibuka pada <?php echo $row['4'] ?>.</p>
            <p class="card-text">Dengan syarat memiliki ipk minimal <?php echo $row['5'] ?>.</p>
            <p class="card-text">Dapatkan bantuan biaya <?php echo $row['6'] ?>.</p>
            <div class="container d-flex justify-content-center">
            <?php
            //jika sudah login baru bisa daftar beasiswa
              if (isset($_SESSION["login_id"])) {
                $id_pengguna = $_SESSION["login_id"];
                $id_beasiswa = $row["id_beasiswa"];
                //Query hitung jml data pendaftaran
                $query = "SELECT COUNT(*) as jumlah FROM pendaftaran_beasiswa WHERE id_pengguna = $id_pengguna AND id_beasiswa = $id_beasiswa";
                $stmt = $koneksi->prepare($query);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $jumlah = $result['jumlah'];
                //select pendaftaran
                $select2 = "SELECT * FROM pendaftaran_beasiswa 
                JOIN beasiswa ON pendaftaran_beasiswa.id_beasiswa = beasiswa.id_beasiswa
                WHERE pendaftaran_beasiswa.id_pengguna = 1;
                ";
                if ($jumlah == 0) {
            ?>
                <form action="" method="post">
                  <input type="hidden" name="id_beasiswa" value="<?= $row["id_beasiswa"] ?>">
                  <input type="hidden" name="id_pengguna" value="<?= $_SESSION["login_id"] ?>">
                  <button type="submit" name="daftarBeasiswa" class="btn btn-primary" id="button">Daftar Beasiswa</button>
                </form>
              <?php
                } else {
              ?>
                <form action="" method="post" style="padding: 0;">
                  <button type="submit" name="sudahAda" class="btn btn-primary" id="button">Daftar Beasiswa</button>
                </form>
                <?php
                }
                ?>
            <?php
              } else {
            ?>
                <form action="">
                  <button type="button" name="NotLogin" class="btn btn-primary" id="button" data-bs-toggle="modal" data-bs-target="#exampleModal2">Daftar Beasiswa</button>
                </form>
                
            <?php
              } 
            ?>
            </div>
          </div>
        </div>
        <?php
          }
        ?>
      </div>
    </div>
    <!-- Pagination -->
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <nav aria-label="Page navigation example" style="background-color: #fff;">
            <ul class="pagination justify-content-center">
              <li class="page-item"  style="border-color: black;">
              <!-- Atur Pagination prev -->
              <?php if($halamanAktif > 1) : ?>
                <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>" aria-label="Previous" style="color: #2f2fa2;">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              <?php else : ?>
                <a class="page-link disabled" href="#" aria-label="Previous" style="color: grey;">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              <?php endif; ?>
              </li>
              <!-- Atur Pagination page num -->
              <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                <?php if($i == $halamanAktif) : ?>
                  <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>" style="color: #2f2fa2; font-weight: bold;"><?= $i; ?></a></li>
                <?php else : ?>
                  <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>" style="color: #2f2fa2;"><?= $i; ?></a></li>
                <?php endif; ?>
              <?php endfor; ?>
              <!-- Atur Pagination next -->
              <li class="page-item">
              <?php if($halamanAktif < $jumlahHalaman) : ?>
                <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>" aria-label="Next" style="color: #2f2fa2;">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              <?php else : ?>
                <a class="page-link disabled" href="#" aria-label="Next" style="color: grey;">
                  <span aria-hidden="true">&raquo;</span>
                </a>
                <?php endif; ?>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    <!-- Footer -->
    <div class="container-fluid" id="footer">
      <div class="row">
        <div class="col d-flex justify-content-center">
          <p>&#169; 2023 ScholarGate. All rights reserved.</p>
        </div>
      </div>
    </div>
    <!-- Modal Pengingat Login -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Status Login</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Anda belum login. Silahkan login terlebih dahulu.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <form action="" method="post" style="padding: 0;">
              <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- offcanfas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header">
        <?php
          $id = $_SESSION["login_id"];
          $selectPengguna = "SELECT * FROM pengguna WHERE id_pengguna = $id";
          foreach ($koneksi->query($selectPengguna) as $row) {
        ?>
        <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo $row["nama_lengkap"]; ?></h5>
        <?php
          }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="d-flex justify-content-center" >
          <a href="profil_pengguna.php" class="btn btn-dark" style="width: 400px; margin-bottom: 30px;">Profil</a>
        </div>
        <div class="d-flex justify-content-center" >
          <a href="confirm_page.php" class="btn btn-dark" style="width: 400px; margin-bottom: 30px;">Pendaftaran Beasiswa</a>
        </div>
        <div class="d-flex justify-content-center">
          <a href="riwayat_pesan.php" class="btn btn-dark" style="width: 400px; margin-bottom: 250px;">Riwayat Pesan</a>
        </div>
        <div class="d-flex justify-content-center">
          <a href="logout.php" class="btn btn-danger" style="width: 400px;">Logout</a>
        </div>
      </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Link JS -->
    <script src="app.js"></script>
    <?php
    //jika tombol daftar beasiswa dipencet maka insert data ke pendaftaran_beasiswa
      if(isset($_POST["daftarBeasiswa"])) {
        $id_beasiswa = $_POST["id_beasiswa"];
        $id_pengguna = $_POST["id_pengguna"];
    
        $insert = "INSERT INTO pendaftaran_beasiswa (id_beasiswa, id_pengguna) VALUES ('$id_beasiswa', '$id_pengguna')";
        $koneksi->exec($insert);
    ?>
    <script>
      alert("Pendaftaran Anda masuk ke Confirmation Page. Lihat List Pendaftaran!");
      window.location.href = "confirm_page.php"; 
    </script>
    <?php
      }
    ?>
  </body>
</html>
