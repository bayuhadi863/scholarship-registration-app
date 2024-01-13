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

  //jika tombol tambah pada halaman admin beasiswa ditekan
  if (isset($_POST["tambah"])) {
    $nama_beasiswa = $_POST["nama_beasiswa"];
    $penyelenggara = $_POST["penyelenggara"];
    $jenjang = $_POST["jenjang"];
    $timeline = $_POST["timeline"];
    $min_ipk = $_POST["min_ipk"];
    $nominal = $_POST["nominal"];
    $insert = "INSERT INTO beasiswa (nama_beasiswa, penyelenggara, jenjang, timeline, min_ipk, nominal) VALUES ('$nama_beasiswa', '$penyelenggara', '$jenjang', '$timeline', '$min_ipk', '$nominal')";
    $koneksi->exec($insert);
    echo "
      <script>
        alert('Berhasil menambah data.');
      </script>
    ";
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
    <title>Admin - Tambah Beasiswa</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/admin_edit_beasiswa.css" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  </head>
  <body id="body">
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
      <div class="container-fluid">
        <a href="admin_beasiswa.php" id="kembali"><i class="bi bi-arrow-90deg-left"></i> Kembali</a>
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
    <div class="container-fluid">
      <div class="row">
        <div class="col d-flex justify-content-center" style="padding: 20px 350px;">
          <div id="detail-container" style="padding: 40px;">
            <h3>Tambah Data Beasiswa</h3>
            <form class="row g-3" action="" method="post">
              <div class="col-md-12">
                <label for="nama_beasiswa" class="form-label">Nama Beasiswa</label>
                <input style="background-color: #f1f1f1;" type="text" class="form-control" id="nama_beasiswa" name="nama_beasiswa" placeholder="DJARUM BEASISWA PLUS">
              </div>
              <div class="col-md-12">
                <label for="penyelenggara" class="form-label">Penyelenggara</label>
                <input style="background-color: #f1f1f1;" type="text" class="form-control" id="penyelenggara" name="penyelenggara" placeholder="Djarum Foundation">
              </div>
              <div class="col-md-12">
                <label for="jenjang" class="form-label">Jenjang</label>
                <input style="background-color: #f1f1f1;" type="text" class="form-control" id="jenjang" name="jenjang" placeholder="Strata 1/Diploma 4">
              </div>
              <div class="col-md-12">
                <label for="timeline" class="form-label">Timeline</label>
                <input style="background-color: #f1f1f1;" type="text" class="form-control" id="timeline" name="timeline" placeholder="1 Mei - 1 Juni 2023">
              </div>
              <div class="col-md-6">
                <label for="min_ipk" class="form-label">Minimal IPK</label>
                <input style="background-color: #f1f1f1;" type="text" class="form-control" id="min_ipk" name="min_ipk" placeholder="3.00">
              </div>
              <div class="col-md-6">
                <label for="nominal" class="form-label">Nominal</label>
                <input style="background-color: #f1f1f1;" type="text" class="form-control" id="nominal" name="nominal" placeholder="Rp1.000.000 per bulan">
              </div>
              <div class="col-md-12 d-flex justify-content-center" >
                <button class="btn btn-primary" name="tambah" type="submit" style="width: 100px; margin: 7px; padding:6px 10px; font-size: 14px;"><i class="bi bi-plus-circle"></i> Tambah</button>
                <button class="btn btn-danger" type="reset" style="width: 100px; margin: 7px; padding:6px 10px; font-size: 14px;"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
              </div>
            </form>
          </div>
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