<?php
  session_start();
  require "koneksi.php";
  // Cek apakah sudah login dan apakah yg login adalah pengguna
  if(!isset($_SESSION["login_id"])) {
    header("Location: login_page.php");
    exit;
  } else {
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

  $id_pengguna = $_SESSION["login_id"];
  $select = "SELECT id_pesan, waktu_kirim, isi_pesan, balasan FROM pesan WHERE id_pengguna = $id_pengguna ORDER BY id_pesan DESC"; //select tabel pesan
  $selectPengguna = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna"; // select tabel pengguna

  //hapus riwayat pesan
  if (isset($_POST["hapus"])) {
    $id_pesan = $_POST["id_pesan"];
    $delete = "DELETE FROM pesan WHERE id_pesan = $id_pesan";
    $koneksi->exec($delete);
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengguna - Riwayat Pesan</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/confirm_page.css" />
    <!-- Google icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  </head>
  <body id="body">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" id="navbar" style="box-shadow: 2px 2px 10px rgba(0,0,0,0.2);">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
          <!-- logo -->
          <div>
            <a href="home_page.php" id="home-link"><i class="bi bi-house-door-fill"></i> Beranda</a>
            <a href="beasiswa.php" id="home-link" style="margin-left: 20px;"><i class="bi bi-mortarboard-fill"></i> Beasiswa</a>
          </div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <?php
            foreach ($koneksi->query($selectPengguna) as $row) {
          ?>
            <p id="nama-user" style="padding-top: 15px;"><?php echo $row["nama_lengkap"]; ?></p>
        </div>
      </div>
    </nav>
    <!-- main container -->
    <div class="container-fluid" id="main-container">
      <div class="row">
        <!-- sidebar -->
        <div class="col-md-3 mt-1">
          <div class="card sidebar">
            <div class="card-body">
              <div class="d-flex justify-content-center">
                <img src="img/profil1.png" id="profil-icon" />
              </div>
              <div>
                <h5 class="d-flex justify-content-center mt-2"><?php echo $row["nama_lengkap"]; ?></h5>
            <?php
              }
            ?>
              </div>
              <div id="sidebar-link-container">
                <a href="profil_pengguna.php" style="border-top: 1px solid #d4d5d8;">Profil</a>
                <a href="confirm_page.php">Pendaftaran Beasiswa</a>
                <a href="riwayat_pesan.php">Riwayat Pesan</a>
              </div>
              <a href="logout.php" class="btn btn-danger" style="padding: 8px; margin: 40px 5px 10px 5px; border: none; width: 240px; color: white;"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </div>
          </div>
        </div>
        <!-- right container -->
        <div class="col-md-9 mt-1" id="right-container">
          <h4 style="font-weight: bold;">Riwayat Pesan</h4>
          <div class="table-responsive" id="table-container">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID Pesan</th>
                  <th scope="col">Waktu Kirim</th>
                  <th scope="col">Isi Pesan</th>
                  <th scope="col">Balasan</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach ($koneksi->query($select) as $row) {
                ?>
                <tr>
                  <th scope="row"><?php echo $row["id_pesan"]; ?></th>
                  <td><?php echo $row["waktu_kirim"]; ?></td>
                  <td><?php echo $row["isi_pesan"]; ?></td>
                  <td><?php echo $row["balasan"]; ?></td>
                  <td>
                    <form action="" method="post" style="padding: 0;">
                      <input type="hidden" name="id_pesan" value="<?= $row["id_pesan"] ?>">
                      <button type="submit" name="hapus" class="btn btn-danger" style="font-size: 14px; width: 90px; padding: 5px 5px;"><i class="bi bi-trash"></i> Hapus</button>
                    </form>  
                  </td>
                </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- footer -->
    <div class="container-fluid text-center" id="footer-container">
      <div class="row" id="footer-row">
        <div class="col">
          <p id="copyright">&#169; 2023 ScholarGate. All rights reserved.</p>
        </div>
      </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>
