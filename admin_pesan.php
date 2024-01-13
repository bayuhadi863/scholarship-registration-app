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

  //Pagination
  $jumlahDataPerHalaman = 5;
  $query = "SELECT COUNT(*) as jumlah FROM pesan";
  $stmt = $koneksi->prepare($query);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $jumlahData = $result['jumlah'];
  $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
  $halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
  $awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
  //select data beasiswa dg limit 10 per halaman
  if (!isset($_POST["ascending"]) || isset($_POST["descending"])) {
    $lihatData = "SELECT * FROM pesan JOIN pengguna ON pesan.id_pengguna = pengguna.id_pengguna  ORDER BY id_pesan ASC LIMIT $awalData , $jumlahDataPerHalaman";
  } else if (isset($_POST["ascending"])) {
    $lihatData = "SELECT * FROM pesan JOIN pengguna ON pesan.id_pengguna = pengguna.id_pengguna  ORDER BY id_pesan DESC LIMIT $awalData , $jumlahDataPerHalaman";
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

  //kirim balasan
  if (isset($_POST["kirim"])) {
    $id_pesan = $_POST["id_pesan"];
    $balasan = $_POST["balasan"];
    $balas = "UPDATE pesan SET balasan = '$balasan' WHERE id_pesan = $id_pesan";
    $koneksi->exec($balas);
    echo "
      <script>
        alert('Berhasil mengirim balasan.');
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
    <title>Admin - Tabel Pesan</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.min.css" />
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles/admin_beasiswa.css" />
    <!-- Google icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- link bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  </head>
  <body id="body">
    <div class="container-fluid" id="big-container">
      <div class="row">
        <!-- Left -->
        <div class="col-sm-2" id="sidebar-container" style="width: 18%">
          <!-- Logo -->
          <div class="d-flex justify-content-center" id="logo-container">
            <div class="">
              <img src="img/logo1.png" alt="" id="logo-img" />
            </div>
            <div id="logo-text-container">
              <p id="logo-text">ScholarGate</p>
            </div>
          </div>
          <!-- sidebar-link -->
          <div id="sidebar-link-container" style="border-top: 1px solid rgba(190, 190, 190, 0.3)">
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
        <div class="col-sm-9" id="main-container" style="width: 82%; min-height: 100vh;">
          <!-- Navbar -->
          <nav class="navbar" id="navbar">
            <div class="container-fluid">
              <h4 id="dashboard">Data Pesan</h4>
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
          <!-- Search Bar -->
          <div id="title-container">
            <form action="" method="post">
              <div class="input-group mb-3">
                <input type="number" name="cariId" class="form-control" placeholder="Cari ID Pesan" aria-label="Recipient's username" aria-describedby="button-addon2" style="background-color: #f8f9fc;">
                <input type="text" name="cariWaktuKirim" class="form-control" placeholder="Cari Waktu Kirim" aria-label="Recipient's username" aria-describedby="button-addon2" style="background-color: #f8f9fc;">
                <input type="number" name="cariIdPengguna" class="form-control" placeholder="Cari ID Pengguna" aria-label="Recipient's username" aria-describedby="button-addon2" style="background-color: #f8f9fc;">
                <button class="btn btn-primary" type="submit" name="cari" id="button-addon2"><i class="bi bi-search"></i></button>
              </div>
            </form>
          </div>
          <!-- Table -->
          <div class="container-fluid">
            <div class="row">
              <div class="col" id="table-col">          
                <div class="table-responsive bg-light" id="table-container">
                  <!-- Pagination -->
                  <?php
                    if (!isset($_POST["cari"]) || isset($_POST["lihatSemuaData"])) { 
                  ?>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-3">
                        <nav aria-label="Page navigation example" >
                          <ul class="pagination">
                            <li class="page-item"  style="border-color: black;">
                            <!-- Atur Pagination prev -->
                            <?php if($halamanAktif > 1) : ?>
                              <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>" aria-label="Previous" style="color: #5a5c69;">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            <?php else : ?>
                              <a class="page-link disabled" href="#" aria-label="Previous" style="color: lightgray;">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            <?php endif; ?>
                            </li>
                            <!-- Atur Pagination page num -->
                            <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                              <?php if($i == $halamanAktif) : ?>
                                <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>" style="color: #5a5c69; font-weight: bold;"><?= $i; ?></a></li>
                              <?php else : ?>
                                <li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>" style="color: #5a5c69;"><?= $i; ?></a></li>
                              <?php endif; ?>
                            <?php endfor; ?>
                            <!-- Atur Pagination next -->
                            <li class="page-item">
                            <?php if($halamanAktif < $jumlahHalaman) : ?>
                              <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>" aria-label="Next" style="color: #5a5c69;">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                            <?php else : ?>
                              <a class="page-link disabled" href="#" aria-label="Next" style="color: lightgray;">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                              <?php endif; ?>
                            </li>
                          </ul>
                        </nav>
                      </div>
                      <!-- ascending-descending -->
                      <div class="col d-flex">
                        <?php
                          if (!isset($_POST["ascending"])) {
                        ?>
                        <form action="" method="post" style="padding: 0;">
                          <button class="btn btn-secondary" name="ascending" style="font-size: 12px; margin-top: 3px"><i class="bi bi-arrow-up"></i> Ascending</button>
                        </form>
                        <?php
                          } else {
                        ?>
                        <form action="" method="post" style="padding: 0;">
                          <button class="btn btn-secondary" name="descending" style="font-size: 12px; margin-top: 3px"><i class="bi bi-arrow-down"></i> Descending</button>
                        </form>
                        <?php
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                  <!-- tabel pesan -->
                  <table class="table border-secondary" id="table" style="font-size: 13px;">
                    <thead>
                      <tr id="row-head">
                        <th scope="col">ID</th>
                        <th scope="col">Waktu Kirim</th>
                        <th scope="col">ID User</th>
                        <th scope="col">Nama Pengirim</th>
                        <th scope="col">Email Pengirim</th>
                        <th scope="col">No. Handphone</th>
                        <th scope="col">Isi Pesan</th>
                        <th scope="col">Balasan</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody class="">
                    <?php
                      foreach ($koneksi->query($lihatData) as $kolom){
                    ?>
                      <tr>
                        <th scope="row"><?php echo $kolom["id_pesan"]; ?></th>
                        <td><?php echo $kolom["waktu_kirim"]; ?></td>
                        <td><?php echo $kolom["id_pengguna"]; ?></td>
                        <td><?php echo $kolom["nama_lengkap"]; ?></td>
                        <td><?php echo $kolom["email_pengirim"]; ?></td>
                        <td><?php echo $kolom["handphone_pengirim"]; ?></td>
                        <td><?php echo $kolom["isi_pesan"]; ?></td>
                        <td><?php echo $kolom["balasan"]; ?></td>
                        <td>
                          <!-- Accordion untuk balas pesan -->
                          <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item" style="background-color: #f8f9fc;">
                              <p class="accordion-header" >
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $kolom['id_pesan']; ?>" aria-expanded="false" aria-controls="flush-collapseOne" style="background-color: #8fabff; border-radius: 5px; padding:5px 10px; font-size: 12px;">
                                  Balas
                                </button>
                              </p>
                              <div id="flush-collapse<?php echo $kolom['id_pesan']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body" style="padding:5px;">
                                <!-- form balas pesan -->
                                  <form action="" method="post" style="padding: 0;">
                                    <textarea name="balasan" cols="21" rows="6" style="background-color: #f1f1f1; font-size: 12px"><?php echo $kolom['balasan'];?></textarea>
                                    <input type="hidden" name="id_pesan" value="<?= $kolom['id_pesan'] ?>">
                                    <button type="submit" name="kirim" class="btn btn-success" style="font-size: 11px; padding: 5px 10px;"><i class="bi bi-send"></i> Kirim</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>   
                        </td>
                      <?php
                        }
                      ?>
                      </tr>
                    </tbody>
                  </table>
                  <!-- jika tombol search dipencet -->
                  <?php
                    } else if (isset($_POST["cari"])) {
                      $cariId = $_POST["cariId"];
                      $cariWaktuKirim = $_POST["cariWaktuKirim"];
                      $cariIdPengguna = $_POST["cariIdPengguna"];
                      $selectCari = "SELECT * FROM pesan WHERE UPPER(id_pesan) LIKE UPPER('%$cariId%') AND UPPER(id_pengguna) LIKE UPPER('%$cariIdPengguna%') AND UPPER(waktu_kirim) LIKE UPPER('%$cariWaktuKirim%')";
                      ?>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col d-flex">
                        <form action="" method="post" style="padding: 0;">
                          <button name="lihatSemuaData" class="btn btn-dark" type="submit" style="font-size: 12px; margin-bottom: 10px;">Lihat Semua Data</button>
                        </form>
                      </div>
                    </div>
                  </div>
                  <table class="table border-secondary" id="table">
                    <thead>
                      <tr id="row-head">
                        <th scope="col">ID</th>
                        <th scope="col">Waktu Kirim</th>
                        <th scope="col">ID User</th>
                        <th scope="col">Nama Pengirim</th>
                        <th scope="col">Email Pengirim</th>
                        <th scope="col">No. Handphone</th>
                        <th scope="col">Isi Pesan</th>
                        <th scope="col">Balasan</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody class="">
                    <?php
                      foreach ($koneksi->query($selectCari) as $row) {
                    ?>
                      <tr>
                        <th scope="row"><?php echo $row["id_pesan"]; ?></th>
                        <td><?php echo $row["waktu_kirim"]; ?></td>
                        <td><?php echo $row["id_pengguna"]; ?></td>
                        <td><?php echo $row["nama_lengkap"]; ?></td>
                        <td><?php echo $row["email_pengirim"]; ?></td>
                        <td><?php echo $row["handphone_pengirim"]; ?></td>
                        <td><?php echo $row["isi_pesan"]; ?></td>
                        <td><?php echo $row["balasan"]; ?></td>
                        <td>
                          <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item" style="background-color: #f8f9fc;">
                              <p class="accordion-header" >
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $row['id_pesan']; ?>" aria-expanded="false" aria-controls="flush-collapseOne" style="background-color: #8fabff; border-radius: 5px; padding:5px 10px; font-size: 12px;">
                                  Balas
                                </button>
                              </p>
                              <div id="flush-collapse<?php echo $row['id_pesan']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body" style="padding:5px;">
                                  <form action="" method="post" style="padding: 0;">
                                    <textarea name="balasan" cols="21" rows="6" style="background-color: #f1f1f1; font-size: 12px"><?php echo $row['balasan'];?></textarea>
                                    <input type="hidden" name="id_pesan" value="<?= $row['id_pesan'] ?>">
                                    <button type="submit" name="kirim" class="btn btn-success" style="font-size: 11px; padding: 5px 10px;"><i class="bi bi-send"></i> Kirim</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>   
                        </td>
                      </tr>
                      <?php
                          }
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- footer -->
          <div class="container text-center" style="min-height: 38vh; padding-top: 100px;">
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
    <!-- Modal edit admin-->
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
