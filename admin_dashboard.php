<?php
  session_start();
  require "koneksi.php";
  
  // Cek apakah sudah login dan apakah yg login adalah admin
  if (!isset($_SESSION["login_id"])) {
    header("Location: login_page.php");
    exit;
  } else {
    $id = $_SESSION["login_id"]; //mengambil id admin yang login
    //Query hitung jml data admin untuk cek apakah ada admin yg login
    $query = "SELECT COUNT(*) as jumlahAdmin FROM admin WHERE id_admin = $id";
    $stmt = $koneksi->prepare($query);
    $stmt->execute();
    $resultAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlahAdmin = $resultAdmin['jumlahAdmin'];
    if ($jumlahAdmin == 0) {
      echo '
        <script> 
        alert("Tidak bisa masuk ke dashboard. Anda bukan admin.");
        window.location.href = "home_page.php";  
        </script>';
        exit;
    } 
  }
  
  $selectAdmin = "SELECT * FROM admin WHERE id_admin = $id"; //select admin yg login untuk ditampilkan di profil.
  
  //Update profil/akun admin
  if (isset($_POST["edit"])) {
    $id = $_SESSION["login_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nama = $_POST["nama"];
    $update = "UPDATE admin SET email = '$email', password = '$password', nama = '$nama' WHERE id_admin = $id";
    $koneksi->exec($update);
    echo "
      <script>
        alert('Berhasil edit data admin.');
      </script>
    ";
  }

  $selectPendaftaran = "SELECT pendaftaran_beasiswa.id_pendaftaran, pendaftaran_beasiswa.waktu_pendaftaran, pengguna.nama_lengkap, beasiswa.nama_beasiswa FROM pendaftaran_beasiswa JOIN pengguna ON pendaftaran_beasiswa.id_pengguna = pengguna.id_pengguna JOIN beasiswa ON pendaftaran_beasiswa.id_beasiswa = beasiswa.id_beasiswa ORDER BY pendaftaran_beasiswa.id_pendaftaran DESC LIMIT 5"; //select pendaftaran_beasiswa untuk tabel pendaftaran terbaru
  $selectPesan = "SELECT pengguna.nama_lengkap, pesan.isi_pesan FROM pesan JOIN pengguna ON pesan.id_pengguna = pengguna.id_pengguna ORDER BY pesan.id_pesan DESC LIMIT 4"; //select pesan untuk tabel pesan terbaru
  
  //Select total
  $selectTotalPengguna = "SELECT COUNT(*) as jumlahPengguna FROM pengguna";
  $selectTotalBeasiswa = "SELECT COUNT(*) as jumlahBeasiswa FROM beasiswa";
  $selectTotalPendaftaran = "SELECT COUNT(*) as jumlahPendaftaran FROM pendaftaran_beasiswa";
  $selectTotalPesan = "SELECT COUNT(*) as jumlahPesan FROM pesan";
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Dashboard</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/admin_dashboard.css" />
    <!-- Google icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  </head>
  <body id="body">
    <div class="container-fluid" id="big-container">
      <div class="row">
        <!-- Left -->
        <div class="col-sm-2" id="sidebar-container" style="width: 18%;">
          <!-- Logo -->
          <div class="d-flex justify-content-center" id="logo-container">
            <div class="">
              <img src="img/logo1.png" alt="" id="logo-img">
            </div>
            <div id="logo-text-container">
              <p id="logo-text">ScholarGate</p>
            </div>
          </div>
          <!-- sidebar-link -->
          <div id="sidebar-link-container" style="border-top: 1px solid rgba(190, 190, 190, 0.3);">
            <a href="admin_dashboard.php" id="sidebar-link">Dashboard</a>
          </div>
          <div id="sidebar-link-container">
            <a href="admin_pengguna.php" id="sidebar-link">Pengguna</a>
          </div>
          <div id="sidebar-link-container">
            <a href="admin_beasiswa.php" id="sidebar-link">Beasiswa</a>
          </div>
          <div id="sidebar-link-container">
            <a href="admin_pendaftaran.php" id="sidebar-link">Pendaftaran Beasiswa</a>
          </div>
          <div id="sidebar-link-container">
            <a href="admin_pesan.php" id="sidebar-link">Pesan</a>
          </div>
        </div>
        <!-- Main / Right Container -->
        <div class="col-sm-9" id="main-container" style="width: 82%;">
          <!-- Navbar -->
          <nav class="navbar" id="navbar">
            <div class="container-fluid">
              <h4 id="dashboard">Dashboard</h4>
              <?php
                $id = $_SESSION["login_id"];
                $selectAdmin = "SELECT * FROM admin WHERE id_admin = $id";
                foreach ($koneksi->query($selectAdmin) as $row) {
              ?>
              <a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample" style="text-decoration: none; color: #5a5c69; font-weight: bold; font-size: 18px; padding-right: 0px;"><?php echo $row["nama"]; ?> <i class="bi bi-person-fill"></i></a>
              <?php
                }
              ?>
            </div>
          </nav>
          <!-- Title -->
          <div id="title-container">
            <p id="title">Total Data</p>
          </div>
          <!-- Total Data -->
          <div class="row row-cols-1 row-cols-md-4 g-4" id="total-container">
            <div class="col">
              <div class="card" id="card-card-total">
                <div class="card-body" id="card-total" style="border-left: 4px solid #1cc88a;">
                  <p class="card-title" id="total-title" style="color: #1cc88a;">Total Pengguna</p>
                  <?php
                    foreach ($koneksi->query($selectTotalPengguna) as $row) {
                  ?>
                  <p class="card-text"><?php echo $row["jumlahPengguna"]; ?></p>
                  <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card" id="card-card-total" style="border-left: 4px solid #4e73df;">
                <div class="card-body" id="card-total">
                  <p class="card-title" id="total-title" style="color: #4e73df;">Total Beasiswa</p>
                  <?php
                    foreach ($koneksi->query($selectTotalBeasiswa) as $row) {
                  ?>
                  <p class="card-text"><?php echo $row["jumlahBeasiswa"]; ?></p>
                  <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card" id="card-card-total" style="border-left: 4px solid #f6c23e;">
                <div class="card-body" id="card-total">
                  <p class="card-title" id="total-title" style="color: #f6c23e;">Total Pendaftaran</p>
                  <?php
                    foreach ($koneksi->query($selectTotalPendaftaran) as $row) {
                  ?>
                  <p class="card-text"><?php echo $row["jumlahPendaftaran"]; ?></p>
                  <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card" id="card-card-total" style="border-left: 4px solid #36b9cc;">
                <div class="card-body" id="card-total">
                  <p class="card-title" id="total-title" style="color: #36b9cc;">Total Pesan</p>
                  <?php
                    foreach ($koneksi->query($selectTotalPesan) as $row) {
                  ?>
                  <p class="card-text"><?php echo $row["jumlahPesan"]; ?></p>
                  <?php
                    }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!-- Terbaru -->
          <div class="container">
            <div class="row" id="terbaru-row">
              <!-- pendaftaran terbaru -->
              <div class="col-8" id="daftar-terbaru-container">
                <h5 id="terbaru-title">Pendaftaran Beasiswa Terbaru</h5>
                <div class="table-responsive">
                  <table class="table" id="table">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Waktu Pendaftaran</th>
                        <th scope="col">Nama Pendaftar</th>
                        <th scope="col">Nama Beasiswa</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($koneksi->query($selectPendaftaran) as $row) {
                      ?>
                      <tr>
                        <th scope="row"><?php echo $row["id_pendaftaran"]; ?></th>
                        <td><?php echo $row["waktu_pendaftaran"]; ?></td>
                        <td><?php echo $row["nama_lengkap"]; ?></td>
                        <td><?php echo $row["nama_beasiswa"]; ?></td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- pesan terbaru -->
              <div class="col-3" id="pesan-terbaru-container">
                <h5 id="terbaru-title">Pesan terbaru</h5>
                <?php
                  foreach ($koneksi->query($selectPesan) as $row) {
                ?>
                <div id="pesan-container">
                  <p><b><?php echo $row["nama_lengkap"]; ?></b></p>
                  <p><?php echo $row["isi_pesan"]; ?></p>
                </div>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>
          <!-- footer -->
          <div class="container text-center">
            <div class="row" id="footer-row">
              <div class="col">
                <p id="copyright">&#169; 2023 ScholarGate. All rights reserved.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- offcanfas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header">
      <?php
        $id = $_SESSION["login_id"];
        $selectAdmin = "SELECT * FROM admin WHERE id_admin = $id";
        foreach ($koneksi->query($selectAdmin) as $row) {
      ?>
        <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo $row["nama"]; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div style="box-shadow: 2px 2px 10px rgba(0,0,0,0.4); padding: 20px; border-radius: 5px;">
          <div>
            <p><b>Email</b></p>
            <p><?php echo $row["email"]; ?></p>
          </div>
          <div>
            <p><b>Password</b></p>
            <p><?php echo $row["password"]; ?></p>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2" style="width: 400px;"><i class="bi bi-pencil-square"></i> Edit</button>
          </div>
        </div>
        <div class="d-flex justify-content-center" style="padding-top: 180px;">
          <a href="logout.php" class="btn btn-danger" style="width: 330px;"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
      </div>
    </div>
    <!-- Modal Edit Admin-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Akun</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post" style="padding: 0;">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" value="<?= $row["email"] ?>">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" value="<?= $row["password"] ?>">
              </div>
              <div class="mb-3">
                <label for="exampleInputNama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="exampleInputNama" value="<?= $row["nama"] ?>">
              </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="edit" class="btn btn-primary">Edit</button>
            </form>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>
