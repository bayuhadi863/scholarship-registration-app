<?php
  session_start();
  require "koneksi.php";
  // Jika sudah login
  if (isset($_SESSION["login_id"])) {
    $id = $_SESSION["login_id"];
    //Query hitung jml data admin
    $query = "SELECT COUNT(*) as jumlahAdmin FROM admin WHERE id_admin = $id";
    $stmt = $koneksi->prepare($query);
    $stmt->execute();
    $resultAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlahAdmin = $resultAdmin['jumlahAdmin'];
    // jika sudah login sebagai admin
    if ($jumlahAdmin != 0) {
      header("Location: admin_dashboard.php");
      exit;
      // jika sudah login sebagai pengguna
    } else {
      header("Location: home_page.php");
      exit;
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/sign_up.css" />
    <style>
      #reset-button {
        background-color: #e74a3b;
        margin-left: 30px;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        transition: 0.2s background-color;
      }
      #reset-button:hover {
        background-color: #f8466d;
      }   
    </style>
  </head>
  <body>
    <div class="container-fluid d-flex justify-content-center align-items-center" id="container">
      <div class="row">
        <!-- Left Container (Judul) -->
        <div class="col-4 d-flex justify-content-center align-items-center" id="left-container">
          <div class="container" id="title-container">
            <!-- Logo Image -->
            <div class="row">
              <div class="col d-flex justify-content-center" id="logo-img-container">
                <img src="img/logo1.png" alt="logo" id="logo-img" />
              </div>
            </div>
            <!-- Logo Text -->
            <div class="row">
              <div class="col d-flex justify-content-center" id="logo-text-container">
                <p id="text-logo">ScholarGate</p>
              </div>
            </div>
            <!-- Judul Daftar Akun -->
            <div class="row">
              <div class="col d-flex justify-content-center align-items-center" id="daftar-container">
                <h1 id="daftar">Daftar Akun</h1>
              </div>
            </div>
            <!-- Sudah memiliki -->
            <div class="row">
              <div class="col d-flex justify-content-center" id="login-container">
                <p id="sudah">Sudah memiliki akun? <a href="login_page.php" id="masuk">Masuk sekarang!</a></p>
              </div>
            </div>
          </div>
        </div>
        <!-- Form -->
        <div class="col-8" id="form-big-container">
          <div id="form-container">
            <form class="row g-3" action="" method="POST">
              <div class="form-floating col-md-6">
                <input type="email" class="form-control" name="email" id="inputEmail" placeholder="name@example.com" required />
                <label for="inputEmail" class="form-label">Email</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="password" class="form-control" name="password" id="inputPassword" placeholder="name@example.com" required />
                <label for="inputPassword" class="form-label">Password</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="nama_lengkap" id="inputNamaLengkap" placeholder="name@example.com" required />
                <label for="inputNamaLengkap" class="form-label">Nama Lengkap</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="nama_panggilan" id="inputNamaPanggilan" placeholder="name@example.com" required />
                <label for="inputNamaPanggilan" class="form-label">Nama Panggilan</label>
              </div>
              <div class="form-floating col-12">
                <input type="number" class="form-control" name="nik" id="inputNIK" placeholder="name@example.com" required />
                <label for="inputNIK" class="form-label">Nomor Induk Kependudukan (NIK)</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="tempat_lahir" id="inputTempatLahir" placeholder="name@example.com" required />
                <label for="inputTempatLahir" class="form-label">Tempat Lahir</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="tanggal_lahir" id="inputTanggalLahir" placeholder="name@example.com" required />
                <label for="inputTanggalLahir" class="form-label">Tanggal Lahir</label>
              </div>
              <div class="form-floating col-12">
                <textarea class="form-control" name="alamat" id="inputAlamat" style="height: 80px; font-size: 14px;"  placeholder="Leave a comment here" ></textarea>
                <label for="inputAlamat" class="form-label">Alamat</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="kota" id="inputCity" placeholder="name@example.com" required />
                <label for="inputCity" class="form-label">Kota</label>
              </div>
              <div class="form-floating col-md-4">
                <select name="provinsi" id="inputState" class="form-select" required>
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
                <label for="inputState" class="form-label">Provinsi</label>
              </div>
              <div class="form-floating col-md-2">
                <input type="number" name="kode_pos" class="form-control" id="inputZip" placeholder="name@example.com" required />
                <label for="inputZip" class="form-label">Kode Pos</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="perguruan_tinggi" id="inputPerguruanTinggi" placeholder="name@example.com" required />
                <label for="inputPerguruanTinggi" class="form-label">Asal Perguruan Tinggi</label>
              </div>
              <div class="form-floating col-md-6">
                <input type="text" class="form-control" name="handphone" id="inputHandphone" placeholder="name@example.com" required />
                <label for="inputHandphone" class="form-label">Nomor Handphone</label>
              </div>
              <div class="col-12 d-flex justify-content-center">
                <button name="submit" type="submit" class="btn btn-primary" id="signup-button">Daftar</button>
                <button name="reset" type="reset" class="btn btn-primary" id="reset-button" >Reset</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
    <!-- JS -->
    <?php
    //jika tombol daftar dipencet
      if (isset($_POST['submit'])){
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
        //Query hitung jml data pengguna
        $queryPengguna = "SELECT COUNT(*) as jumlahPengguna FROM pengguna WHERE email = '$email'";
        $prepare = $koneksi->prepare($queryPengguna);
        $prepare->execute();
        $resultPengguna = $prepare->fetch(PDO::FETCH_ASSOC);
        $jumlahPengguna = $resultPengguna['jumlahPengguna'];
        
        $insert = "INSERT INTO pengguna (email, password, nama_lengkap, nama_panggilan, nik, tempat_lahir, tanggal_lahir, alamat, kota, provinsi, kode_pos, perguruan_tinggi, handphone) 
        VALUES ('$email', '$password', '$nama_lengkap', '$nama_panggilan', 
        '$nik', '$tempat_lahir', '$tanggal_lahir', '$alamat', 
        '$kota', '$provinsi', '$kode_pos', '$perguruan_tinggi', '$handphone')";
        // jika email sudah ada maka gagal daftar, jika tidak ada pengguna dg email itu, maka insert berhasil
        if ($jumlahPengguna == 0) {
          $koneksi->exec($insert);
          $koneksi = null;
    ?>
        <script>
          alert("Berhasil Daftar Akun. Login sekarang!");
          window.location.href = "login_page.php";
        </script>
    <?php
        } else {}
    ?>
        <script>
          alert("Email sudah terdaftar. Login atau Daftar dengan email yang lain.");
        </script>
    <?php
      }
    ?>
  </body>
</html>
