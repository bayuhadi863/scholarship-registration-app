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
  $select = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna"; //select tabel pengguna
  //update data pengguna
  if (isset($_POST["update"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $nama_panggilan = $_POST['nama_panggilan'];
    $nik = $_POST['nik'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $kode_pos = $_POST['kode_pos'];
    $perguruan_tinggi = $_POST['perguruan_tinggi'];
    $handphone = $_POST['handphone'];

    $update = "UPDATE pengguna SET email = '$email', password = '$password', nama_lengkap = '$nama_lengkap', nama_panggilan = '$nama_panggilan', nik = '$nik', tempat_lahir = '$tempat_lahir', tanggal_lahir = '$tanggal_lahir', alamat = '$alamat', kota = '$kota', provinsi = '$provinsi', kode_pos = '$kode_pos', perguruan_tinggi = '$perguruan_tinggi', handphone = '$handphone' WHERE id_pengguna = $id_pengguna";
    
    $koneksi->exec($update);

    echo "
      <script>
        alert('Berhasil update data.');
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
    <title>Pengguna - Profil Pengguna</title>
    <link rel="stylesheet" type="text/css" href="bootstrap-5.2.3-dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="styles/profil_pengguna.css" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" id="navbar" style="box-shadow: 2px 2px 10px rgba(0,0,0,0.2);">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
          <!-- logo -->
          <div>
            <a href="home_page.php" id="home-link" ><i class="bi bi-house-door-fill"></i> Beranda</a>
            <a href="beasiswa.php" id="home-link" style="margin-left: 20px;"><i class="bi bi-mortarboard-fill"></i> Beasiswa</a>
          </div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
            <?php
              foreach ($koneksi->query($select) as $row) {
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
              <h3 id="profil-title">Data Diri</h3>
              <?php
                foreach ($koneksi->query($select) as $row) {
              ?>
              <div class="row" id="data-row">
                <div class="col-3">ID</div>
                <div class="col-9">: <?php echo $row["0"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Email</div>
                <div class="col-9">: <?php echo $row["1"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Password</div>
                <div class="col-9">: <?php echo $row["2"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Nama Lengkap</div>
                <div class="col-9">: <?php echo $row["3"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Nama Panggilan</div>
                <div class="col-9">: <?php echo $row["4"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">NIK</div>
                <div class="col-9">: <?php echo $row["5"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Tempat Lahir</div>
                <div class="col-9">: <?php echo $row["6"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Tanggal Lahir</div>
                <div class="col-9">: <?php echo $row["7"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Alamat</div>
                <div class="col-9">: <?php echo $row["8"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Kota</div>
                <div class="col-9">: <?php echo $row["9"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Provinsi</div>
                <div class="col-9">: <?php echo $row["10"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Kode Pos</div>
                <div class="col-9">: <?php echo $row["11"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Perguruan Tinggi</div>
                <div class="col-9">: <?php echo $row["12"]; ?></div>
              </div>
              <div class="row" id="data-row">
                <div class="col-3">Nomor Handphone</div>
                <div class="col-9">: <?php echo $row["13"]; ?></div>
              </div>
              <?php
                }
              ?>
              <div class="row" style="padding-left: 20px; padding-top: 20px; padding-right: 20px;">
                <button type="button" name="edit" class="btn btn-primary" style="padding: 5px 20px;" data-bs-toggle="modal" data-bs-target="#exampleModal2"><i class="bi bi-pencil-square"></i> Edit</button>
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
    <!-- Modal Edit Pengguna-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Diri</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php
                foreach ($koneksi->query($select) as $row) {
              ?>
            <form class="row g-3" action="" method="post">
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $row['1'] ?>">
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?= $row['2'] ?>">
              </div>
              <div class="col-md-6">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $row['3'] ?>">
              </div>
              <div class="col-md-6">
                <label for="nama_panggilan" class="form-label">Nama_panggilan</label>
                <input type="text" class="form-control" id="nama_panggilan" name="nama_panggilan" value="<?= $row['4'] ?>">
              </div>
              <div class="col-md-12">
                <label for="nik" class="form-label">NIK</label>
                <input type="number" class="form-control" id="nik" name="nik" value="<?= $row['5'] ?>">
              </div>
              <div class="col-md-6">
                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= $row['6'] ?>">
              </div>
              <div class="col-md-6">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $row['7'] ?>">
              </div>
              <div class="col-12">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $row['8'] ?>">
              </div>
              <div class="col-md-6">
                <label for="kota" class="form-label">Kota</label>
                <input type="text" class="form-control" id="kota" name="kota" value="<?= $row['9'] ?>">
              </div>
              <div class="col-md-4">
                <label for="provinsi" class="form-label">Provinsi</label>
                <select id="provinsi" class="form-select" name="provinsi" value="<?= $row['10'] ?>">
                  <option value="Nanggroe Aceh Darussalam" selected>Nanggroe Aceh Darussalam</option>
                  <option value="Sumatera Utara">Sumatera Utara</option>
                  <option value="Sumatera Selatan">Sumatera Selatan</option>
                  <option value="Sumatera Barat">Sumatera Barat</option>
                  <option value="Bengkulu">Bengkulu</option>
                  <option value="Riau">Riau</option>
                  <option value="Kepulauan Riau">Kepulauan Riau</option>
                  <option value="Jambi">Jambi</option>
                  <option value="Lampung">Lampung</option>
                  <option value="Bangka Belitung">Bangka Belitung</option>
                  <option value="Kalimantan Barat">Kalimantan Barat</option>
                  <option value="Kalimantan Timur">Kalimantan Timur</option>
                  <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                  <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                  <option value="Kalimantan Utara">Kalimantan Utara</option>
                  <option value="Banten">Banten</option>
                  <option value="DKI Jakarta">DKI Jakarta</option>
                  <option value="Jawa Barat">Jawa Barat</option>
                  <option value="Jawa Tengah">Jawa Tengah</option>
                  <option value="Daerah Istimewa Yogyakarta">Daerah Istimewa Yogyakarta</option>
                  <option value="Jawa Timur">Jawa Timur</option>
                  <option value="Bali">Bali</option>
                  <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                  <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                  <option value="Gorontalo">Gorontalo</option>
                  <option value="Sulawesi Barat">Sulawesi Barat</option>
                  <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                  <option value="Sulawesi Utara">Sulawesi Utara</option>
                  <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                  <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                  <option value="Maluku Utara">Maluku Utara</option>
                  <option value="Maluku">Maluku</option>
                  <option value="Papua Barat">Papua Barat</option>
                  <option value="Papua">Papua</option>
                  <option value="Papua Tengah">Papua Tengah</option>
                  <option value="Papua Pegunungan">Papua Pegunungan</option>
                  <option value="Papua Selatan">Papua Selatan</option>
                  <option value="Papua Barat Daya">Papua Barat Daya</option>
                </select>
              </div>
              <div class="col-md-2">
                <label for="kode_pos" class="form-label">Kode Pos</label>
                <input type="number" class="form-control" id="kode_pos" name="kode_pos" value="<?= $row['11'] ?>">
              </div>
              <div class="col-md-6">
                <label for="perguruan_tinggi" class="form-label">Perguruan Tinggi</label>
                <input type="text" class="form-control" id="perguruan_tinggi" name="perguruan_tinggi" value="<?= $row['12'] ?>">
              </div>
              <div class="col-md-6">
                <label for="handphone" class="form-label">No. Handphone</label>
                <input type="text" class="form-control" id="handphone" name="handphone" value="<?= $row['13'] ?>">
              </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id_pengguna" value="<?=$row['0']?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100px;">Batal</button>
            <button type="submit" name="update" class="btn btn-primary" style="width: 100px;">Edit</button>
          </div>
          </form>
          <?php
              }
            ?>
        </div>
      </div>
    </div>
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>
