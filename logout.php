<?php
  session_start();
  //hapus session
  $_SESSION = [];
  session_unset();
  session_destroy();

  header("Location: login_page.php");
?>