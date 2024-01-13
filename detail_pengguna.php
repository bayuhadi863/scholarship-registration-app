<?php
  session_start();
  require "koneksi.php";

  //Jika ada session lihatDetail (yg berasal dari tombol detailyg dipencet pada halaman admin - tabel pengguna)
  if(!isset($_SESSION["lihatDetail"])) {
    header("Location: admin_pengguna.php");
    exit;
  }
  $id_pengguna = $_SESSION["lihatDetail"];

  // update data pengguna
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

  //blokir pengguna
  if (isset($_POST["blokir"])) {
    $blokir = "UPDATE pengguna SET is_blocked = true WHERE id_pengguna = $id_pengguna";
    $koneksi->exec($blokir);
    echo '
    <script>
      alert("Berhasil memblokir akun ini.");
    </script>
    ';
  } else if (isset($_POST["bukaBlokir"])) { //buka blokir
    $bukaBlokir = "UPDATE pengguna SET is_blocked = false WHERE id_pengguna = $id_pengguna";
    $koneksi->exec($bukaBlokir);
    echo '
    <script>
      alert("Berhasil membuka blokir akun ini.");
    </script>
    ';
  } else if (isset($_POST["hapus"])) { //hapus pengguna
    $hapusPendaftaran = "DELETE FROM pendaftaran_beasiswa WHERE id_pengguna = $id_pengguna";
    $koneksi->exec($hapusPendaftaran);
    $hapusPesan = "DELETE FROM pesan WHERE id_pengguna = $id_pengguna";
    $koneksi->exec($hapusPesan);
    $hapusAkun = "DELETE FROM pengguna WHERE id_pengguna = $id_pengguna";
    $koneksi->exec($hapusAkun);
    echo '
    <script>
      alert("Berhasil menghapus akun ini.");
    </script>
    ';
    header("Location: admin_pengguna.php");
  }

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
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Detail Pengguna</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/detail_pengguna.css" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  </head>
  <body id="body">
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
      <div class="container-fluid">
        <a href="admin_pengguna.php" id="kembali"><i class="bi bi-arrow-90deg-left"></i> Kembali</a>
        <?php
          $id = $_SESSION["login_id"];
          $selectAdmin = "SELECT * FROM admin WHERE id_admin = $id";
          foreach ($koneksi->query($selectAdmin) as $row) {
        ?>
        <a href="#offcanvasExample" id="profil" role="button" data-bs-toggle="offcanvas" aria-controls="offcanvasExample"><?php echo $row["nama"]; ?> <i class="bi bi-person-fill"></i></a>
        <?php
          }
        ?>
      </div>
    </nav>
    <!-- main container -->
    <div class="container-fluid">
      <div class="row">
        <div class="col d-flex justify-content-center">
          <?php
              $select = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna";
              foreach ($koneksi->query($select) as $row) {
          ?>
          <!-- Card Detail pengguna -->
          <div id="detail-container">
            <table>
              <tr>
                <td id="detail-title">Detail Pengguna</td>
              </tr>
              <tr>
                <td>ID Pengguna</td>
                <td>: <?php echo $row["0"]; ?></td>
              </tr>
              <tr>
                <td>Email</td>
                <td>: <?php echo $row["1"]; ?></td>
              </tr>
              <tr>
                <td>Password</td>
                <td>: <?php echo $row["2"]; ?></td>
              </tr>
              <tr>
                <td>Nama Lengkap</td>
                <td>: <?php echo $row["3"]; ?></td>
              </tr>
              <tr>
                <td>Nama Panggilan</td>
                <td>: <?php echo $row["4"]; ?></td>
              </tr>
              <tr>
                <td>NIK</td>
                <td>: <?php echo $row["5"]; ?></td>
              </tr>
              <tr>
                <td>Tempat Lahir</td>
                <td>: <?php echo $row["6"]; ?></td>
              </tr>
              <tr>
                <td>Tanggal Lahir</td>
                <td>: <?php echo $row["7"]; ?></td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>: <?php echo $row["8"]; ?></td>
              </tr>
              <tr>
                <td>Kota</td>
                <td>: <?php echo $row["9"]; ?></td>
              </tr>
              <tr>
                <td>Provinsi</td>
                <td>: <?php echo $row["10"]; ?></td>
              </tr>
              <tr>
                <td>Kode Pos</td>
                <td>: <?php echo $row["11"]; ?></td>
              </tr>
              <tr>
                <td>Perguruan Tinggi</td>
                <td>: <?php echo $row["12"]; ?></td>
              </tr>
              <tr>
                <td>Nomor Handphone</td>
                <td>: <?php echo $row["13"]; ?></td>
              </tr>
            </table>
            <div class="d-flex justify-content-center" id="button-container">
              <button type="button" class="btn btn-primary" id="button" data-bs-toggle = "modal"  data-bs-target="#exampleModal2" style="width: 110px; font-size: 14px; padding: 8px 0px;"><i class="bi bi-pencil-square"></i> Edit</button>
              <!-- jika is_blocked = false yg muncul button blokir -->
              <?php
                if ($row["is_blocked"] == false) { 
              ?>
              <button type="button" class="btn btn-danger" id="button" style="width: 110px; font-size: 14px; padding: 8px 0px;" data-bs-toggle="modal" data-bs-target="#modal-blokir"><i class="bi bi-slash-circle"></i> Blokir</button>
            </div>
            <!-- jika is_blocked = true yg muncul button buka blokir dan hapus akun -->
            <?php
              } else {
            ?>
            <button type="button" class="btn btn-outline-dark" id="button" style="width: 110px; font-size: 14px; padding: 8px 0px;" data-bs-toggle="modal" data-bs-target="#modal-buka-blokir"><i class="bi bi-slash-circle"></i> Buka Blokir</button>
            <button type="button" class="btn btn-danger" id="button" style="width: 110px; font-size: 14px; padding: 8px 0px;" data-bs-toggle="modal" data-bs-target="#modal-hapus"><i class="bi bi-trash"></i> Hapus Akun</button>
            </div>
          </div>
          <?php
              }
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
    <!-- ModalEdit -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Pengguna</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php
                $select = "SELECT * FROM pengguna WHERE id_pengguna = $id_pengguna";
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
    <!-- Modal Blokir -->
    <div class="modal fade" id="modal-blokir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Apakah anda yakin ingin memblokir akun ini?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <form action="" method="post" style="padding: 0;">
              <button type="submit" name="blokir" class="btn btn-danger">Ya</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Buka Blokir -->
    <div class="modal fade" id="modal-buka-blokir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Anda ingin membuka blokir akun ini?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <form action="" method="post" style="padding: 0;">
              <button type="submit" name="bukaBlokir" class="btn btn-primary">Ya</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Hapus -->
    <div class="modal fade" id="modal-hapus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus akun ini?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <form action="" method="post" style="padding: 0;">
              <button type="submit" name="hapus" class="btn btn-danger">Ya</button>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-admin" style="width: 400px;"><i class="bi bi-pencil-square"></i> Edit</button>
          </div>
        </div>
        <div class="d-flex justify-content-center" style="padding-top: 180px;">
          <a href="logout.php" class="btn btn-danger" style="width: 330px;"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
      </div>
    </div>
    <!-- Modal Edit Admin-->
    <div class="modal fade" id="modal-edit-admin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          </div>
        </div>
      </div>
    </div>
    <?php
        }
    ?>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>