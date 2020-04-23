<?php
  session_start();
  session_unset();
  session_destroy();
  header('Location: page_de_connexion.php');
  exit();

 ?>
