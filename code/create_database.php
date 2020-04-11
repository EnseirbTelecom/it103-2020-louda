<?php
  $link = mysqli_connect("localhost", "admin", "it103");
  if ($log){
    if (!$link) {
        die('Connexion impossible : ' . mysqli_error($link) . "<br />");
    }
    else {echo "Connection successful <br />";}
  }

  $sql = "CREATE DATABASE louda";
  if (mysqli_query($link,$sql)) {
      if ($log){echo "Base de données créée correctement <br />";}
  } else {
      if ($log){echo "Erreur lors de la création de la base de données : " . mysqli_error($link) . "<br />";}
  }
?>
