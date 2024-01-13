<?php
  session_start();
  require "koneksi.php";

  // Cek apakah sudah login dan apakah yg login adalah pengguna
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
  //Jika alert login di tekan oke maka masuk ke halaman login
  if(isset($_POST["login"])) {
    header("Location: login_page.php");
    exit;
  }
  //Select 2 data beasiswa untuk card
  $select = "SELECT * FROM beasiswa ORDER BY id_beasiswa ASC LIMIT 0, 2";
  //jika tombol daftar beasiswa dipencet, insert data ke pendaftaran_beasiswa
  if(isset($_POST["daftarBeasiswa"])) {
    $id_beasiswa = $_POST["id_beasiswa"];
    $id_pengguna = $_POST["id_pengguna"];
    $insert = "INSERT INTO pendaftaran_beasiswa (id_beasiswa, id_pengguna) VALUES ('$id_beasiswa', '$id_pengguna')";
    $koneksi->exec($insert);
    //tampilkan alert
    echo '
    <script>
      alert("Pendaftaran Anda masuk ke Confirmation Page. Lihat List Pendaftaran?");
      window.location.href = "confirm_page.php"; 
    </script>
    ';
  }

  // jika beasiswa sudah ada di halaman konfirmasi (sudah pernah didaftar)
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
    <title>Pengguna - Beranda</title>
    <!-- link bootstrap -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- link css -->
    <link rel="stylesheet" href="styles/home_page.css" />
    <link rel="stylesheet" href="styles/contact.css" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>

    <style>
      #menu-link {
        color: whitesmoke;
      }

      #menu-link:hover {
        color: #f64c72;
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary" id="navbar">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
          <!-- logo -->
          <img src="img/logo1.png" alt="logo" id="logo" class="ms-5" />
          <a class="navbar-brand" href="#home" id="logo-link">ScholarGate</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <!-- Link Menu -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#home" id="menu-link" >Beranda</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#card-big-container" id="menu-link" >Beasiswa</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#contact" id="menu-link" >Hubungi Kami</a>
              </li>
            </ul>
            <!-- Tombol kanan atas -->
            <!-- jika sudah login maka muncul tombol nama pengguna -->
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
            <!-- jika belum login maka muncul tombol masuk/daftar -->
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
    <!-- Homepage -->
    <div class="container-fluid" id="home">
      <div class="row">
        <div class="col-sm-7" id="home-left">
          <!-- Judul & Deskripsi -->
          <h1 id="title">Buka Potensimu: Ajukan Beasiswa Sekarang!</h1>
          <p id="description">Peluang beasiswa menanti. Wujudkan impian pendidikanmu dengan dukungan finansial kami. Mulai sekarang!</p>
          <a class="btn btn-primary" href="beasiswa.php" role="button" id="get-started-button">Mulai Di Sini</a>
        </div>
        <div class="col-sm-4" id="home-right">
          <!-- Foto Orang -->
          <img src="img/person.png" alt="foto" class="img-person" />
        </div>
      </div>
    </div>
    <!-- Section Beasiswa -->
    <div class="container-fluid text-center" id="card-big-container">
      <div class="row">
        <!-- Headline -->
        <div class="col">
          <p id="headline">Temukan Beasiswa yang Anda Inginkan</p>
        </div>
      </div>
      <!-- Card -->
      <div class="row d-flex justify-content-center">
        <?php
        //tampilkan data dari query select beasiswa
          foreach ($koneksi->query($select) as $row) {
        ?>
        <div class="col-sm-6" id="card-container">
          <div class="card" id="card" >
            <div class="card-body" id="card-body" style="padding: 25px;">
              <div id="title-card">
                <h4 class="card-title"><?php echo $row['1'] ?></h4>
              </div>
              <div id="card-desc">
                <p>Penyelenggara: <?php echo $row['2'] ?>.</p>
              </div>
              <div id="card-desc">
                <p>Untuk mahasiswa yang sedang menempuh pendidikan <?php echo $row['3'] ?>.</p>
              </div>
              <div id="card-desc">
                <p>Pendaftaran akan dibuka pada <?php echo $row['4'] ?>.</p>
              </div>
              <div id="card-desc">
                <p>Dengan syarat memiliki ipk minimal <?php echo $row['5'] ?>.</p>
              </div>
              <div id="card-desc">
                <p>Dapatkan bantuan biaya <?php echo $row['6'] ?>.</p>
              </div>
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
                    <form action="" method="post" style="padding: 0;">
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
                    <button type="button" class="btn btn-primary" id="button" data-bs-toggle="modal" data-bs-target="#exampleModal2">Daftar Beasiswa</button>
                <?php
                  } 
                ?>
              </div>
            </div>
          </div>
        </div>
        <?php
          }
        ?>
      </div>
      <!-- Teks Tambahan -->
      <div class="row">
        <div class="col" id="add-text">
          <p>Dan masih <b>banyak beasiswa</b> lainnya..</p>
          <p>Belum menemukan beasiswa yang cocok untukmu?</p>
        </div>
      </div>
      <!-- Tombol Temukan -->
      <div class="row">
        <div class="col">
          <a href="beasiswa.php" class="btn btn-primary" id="button2">Temukan Sekarang</a>
        </div>
      </div>
    </div>
    <!-- SECTION Contact -->
    <div class="main-container" id="contact">
      <span class="big-circle"></span>
      <img src="img/shape.png" class="square" alt="" />
      <div class="form">
        <div class="contact-info">
          <h3 class="title">Mari Kita Tetap Terhubung</h3>
          <p class="text">
            Hubungi kami sekarang untuk informasi lebih lanjut, pertanyaan, atau bantuan apa pun yang Anda butuhkan.
          </p>
          <!-- info kontak -->
          <div class="info">
            <div class="information">
              <img src="img/location.png" class="icon" alt="" />
              <p>Jl. Raya ITS, Keputih, Kec. Sukolilo, Surabaya</p>
            </div>
            <div class="information">
              <img src="img/email.png" class="icon" alt="" />
              <p>coloneurban@gmail.com</p>
            </div>
            <div class="information">
              <img src="img/phone.png" class="icon" alt="" />
              <p>0896-0141-5100</p>
            </div>
          </div>
          <!-- icon sosmed -->
          <div class="social-media">
            <p>Kunjungi Kami :</p>
            <div class="social-icons">
              <a href="#">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>
        </div>
        <!-- bagian kanan -->
        <div class="contact-form">
          <span class="circle one"></span>
          <span class="circle two"></span>
          <!-- form -->
          <form action="" method="post" autocomplete="off">
            <h3 class="title">Hubungi Kami</h3>
            <div class="input-container">
              <input type="text" name="nama_lengkap" class="input" required/>
              <label for="">Nama Lengkap</label>
              <span>Nama Lengkap</span>
            </div>
            <div class="input-container">
              <input type="email" name="email" class="input" requred/>
              <label for="">Email</label>
              <span>Email</span>
            </div>
            <div class="input-container">
              <input type="tel" name="handphone" class="input" required/>
              <label for="">No. Handphone</label>
              <span>No. Handphone</span>
            </div>
            <div class="input-container textarea">
              <textarea name="isi_pesan" class="input" required></textarea>
              <label for="">Pesan</label>
              <span>Pesan</span>
            </div>
            <?php
              if (isset($_SESSION["login_id"])) {
            ?>
              <button type="submit" name="submit" class="send-button" id="button-kirim" data-bs-toggle="modal" data-bs-target="#exampleModal3">Kirim</button>
            <?php
              } else {
            ?>
              <button type="button" class="send-button" id="button-kirim" data-bs-toggle="modal" data-bs-target="#exampleModal2">Kirim</button>
            <?php
              }
            ?>
          </form>
        </div>
      </div>
    </div>
    <!-- Footer -->
    <div class="container-fluid" style="height: 200px; background-color: #2f2fa2;">
      <div class="row" id="link-footer-row">
        <ul class="nav justify-content-center">
          <li class="nav-item">
            <a class="nav-link" id="footer-link" href="#home">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="footer-link" href="#card-big-container">Beasiswa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="footer-link" href="#contact">Hubungi Kami</a>
          </li>
        </ul>
      </div>
      <div class="row" id="copyright-row">
        <div class="col d-flex justify-content-center">
          <p>&#169; 2023 ScholarGate. All rights reserved.</p>
        </div>
      </div>
    </div>
    <!-- Modal2 (diarahkan ke halaman login karena belum login-->
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
          <a href="logout.php" class="btn btn-danger" style="width: 400px;"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
      </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Link JS -->
    <script src="scripts/app.js"></script>
    <?php
    //jika tombol kirim pada contact dipencet maka insert data ke tabel pesan
      if (isset($_POST["submit"])) {
        $nama_lengkap = $_POST["nama_lengkap"];
        $email = $_POST["email"];
        $handphone = $_POST["handphone"];
        $isi_pesan = $_POST["isi_pesan"];
        $id_pengguna = $_SESSION["login_id"];
        $insert = "INSERT INTO pesan (nama_lengkap, email_pengirim, handphone_pengirim, isi_pesan, id_pengguna) 
        VALUES ('$nama_lengkap', '$email', '$handphone', '$isi_pesan', '$id_pengguna')";
        $koneksi->exec($insert);
    ?>
      <script>
        alert("Berhasil mengirim pesan.");
      </script>
    <?php
      }
    ?>
  </body>
</html>
