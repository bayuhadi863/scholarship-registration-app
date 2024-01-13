<?php
  try{
    //koneksi ke database
    $hostname = 'localhost';
    $dbname = 'pendaftaran_beasiswa';
    $username = 'root';
    $password = '';

    $koneksi = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

  } catch(PDOException $error){
      //gagal koneksi
      echo $error->getMessage();
  }

?>