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
    // jika yg login admin maka diarahkan ke halaman admin dashboard
    if ($jumlahAdmin != 0) {
      header("Location: admin_dashboard.php");
      exit;
      // jika yg login pengguna maka diarahkan ke halaman beranda pengguna
    } else {
      header("Location: home_page.php");
      exit;
    }
  }
  // Login Pengguna
  if(isset($_POST["submit"])){
    $email = $_POST["email"];
    $passowrd = $_POST["password"];
    //Query hitung jml data admin
    $query = "SELECT COUNT(*) as jumlahAdmin FROM admin WHERE email = BINARY '$email'";
    $stmt = $koneksi->prepare($query);
    $stmt->execute();
    $resultAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlahAdmin = $resultAdmin['jumlahAdmin'];
    //Query hitung jml data pengguna
    $queryPengguna = "SELECT COUNT(*) as jumlahPengguna FROM pengguna WHERE email = BINARY '$email'";
    $prepare = $koneksi->prepare($queryPengguna);
    $prepare->execute();
    $resultPengguna = $prepare->fetch(PDO::FETCH_ASSOC);
    $jumlahPengguna = $resultPengguna['jumlahPengguna'];
    //Query select data Admin
    $selectAdmin = "SELECT * FROM admin WHERE email = '$email'";
    //Query select data Pengguna
    $selectPengguna = "SELECT * FROM pengguna WHERE email = '$email'";
    //cek email admin
    if($jumlahAdmin == 1) {
      foreach ($koneksi->query($selectAdmin) as $row) {
        //cek password
        if ($passowrd === $row["password"]) {
          // set session
          $_SESSION["login_id"] = $row["id_admin"];
          header("Location: admin_dashboard.php");
          exit;
        } else {
          echo '
          <script> alert("Email atau password salah. Pastikan anda sudah mendaftarkan akun terlebih dahulu.");  
          </script>';
        }
      }
    } else if ($jumlahPengguna) {   //cek email pengguna
      foreach ($koneksi->query($selectPengguna) as $row) {
        //cek password
        if ($passowrd === $row["password"]) {
          // set session
          $_SESSION["login_id"] = $row["id_pengguna"];
          header("Location: home_page.php");
          exit;
        } else {
          echo '
          <script> alert("Email atau password salah. Pastikan anda sudah mendaftarkan akun terlebih dahulu.");  
          </script>';
        }
      }
    } else {
      echo '
      <script> alert("Email atau password salah. Pastikan anda sudah mendaftarkan akun terlebih dahulu.");  
      </script>';
    }
  }
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/login_page.css" />

    <style>
      #reset-button {
        background-color: #e74a3b;
        margin-left: 30px;
        border: none;
        padding: 10px 22px;
        font-size: 14px;
        transition: 0.2s background-color;
      }
      #reset-button:hover {
        background-color: #f8466d;
      }
    </style>
  </head>
  <body>
    <!-- Logo -->
    <div class="container-fluid d-flex justify-content-center align-items-center" id="logo-container">
      <div class="row">
        <div class="col">
          <img src="img/logo1.png" alt="logo" id="logo">
        </div>
      </div>
    </div>
    <!-- Judul -->
    <div class="container-fluid d-flex justify-content-center align-items-center" id="judul-container">
      <div class="row">
        <div class="col">
          <p>Masuk ke <span id="text-logo">ScholarGate</span></p>
        </div>
      </div>
    </div>
    <!-- Form -->
    <div class="container-fluid d-flex justify-content-center align-items-center" id="big-form-container">
      <div class="row" >
        <div class="col" id="form-container">
          <form action="" method="post">
            <div class="mb-3">
              <label for="InputEmail" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" required/>
              <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
              <label for="InputPassword" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" id="InputPassword" aria-describedby="password" required/>
              <div id="password" class="form-text">Enter the correct password.</div>
            </div>
            <div class="d-flex justify-content-center">
            <button type="submit" name="submit" class="btn btn-primary" id="submit-button">Masuk</button>
            <button type="reset" name="reset" class="btn btn-primary" id="reset-button">Reset</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Daftar Akun -->
    <div class="container-fluid d-flex justify-content-center align-items-center text-center" id="sign-up-container">
      <div class="row" >
        <div class="col" id="sign-up">Belum Memiliki Akun? <a href="sign_up.php" id="daftar">Daftar Sekarang!</a></div>
      </div>
    </div>
    <!-- Kembali ke Home -->
    <div class="container-fluid d-flex justify-content-center align-items-center text-center" id="kembali-container">
      <div class="row" >
        <div class="col" id="kembali">kembali ke <a href="home_page.php" id="home">Beranda</a></div>
      </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>
