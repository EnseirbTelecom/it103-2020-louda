<?php
  function firstConnectDatabase($log = false){
    $link = mysqli_connect("localhost", "admin", "it103");
    if ($log){
      if (!$link) {
          die('Connexion impossible : ' . mysqli_error($link) . "<br />");
      }
      else {echo "Connection successful <br />";}
    }

    return $link;
  }

 function connectDatabase($log = false){
   $link = new mysqli("localhost", "admin", "it103","louda");
   if ($log){
     if (!$link) {
         die('Connexion impossible : ' . mysqli_error($link) . "<br />");
     }
     else {echo "Connection successful <br />";}
   }

   return $link;
 }

 function executeRequest($bdd,$request,$log = false){
   $result = mysqli_query($bdd,$request);
   if ($result) {
       if ($log){echo "Request is done successfully <br />";}
   } else {
       if ($log){echo "Error during request: ".$request ." : <br />" . mysqli_error($bdd) . "<br />";}
   }
   return $result;
 }

 function createDatabase($log = false){
   $bdd = firstConnectDatabase($log);
   $request = "CREATE DATABASE louda";
   executeRequest($bdd,$request,$log);
   $bdd->close();
 }

 function createTableUtilisateur($log=false){
   $bdd = connectDatabase($log);
   //SQL request to create user table
   $create = "CREATE TABLE utilisateur(
     id_utilisateur INT(11) AUTO_INCREMENT PRIMARY KEY,
     email TEXT NOT NULL,
     mot_de_passe TEXT NOT NULL,
     nom TEXT NOT NULL,
     prenom TEXT NOT NULL,
     pseudo TEXT NOT NULL,
     date_de_naissance DATE NOT NULL)";
   executeRequest($bdd,$create,$log);
   $bdd->close();
 }

   function createTableTransaction($log=false){
     $bdd = connectDatabase($log);
     //SQL request to create user table
     $create = "CREATE TABLE transaction(
       id_transaction INT(11) AUTO_INCREMENT PRIMARY KEY,
       id_utilisateur_source INT(11),
       id_utilisateur_cible INT(11),
       date_et_heure_de_creation DATETIME NOT NULL,
       date_de_naissance DATETIME,
       montant DECIMAL,
       message TEXT,
       message_cloture TEXT,
       statut TEXT NOT NULL)";
     executeRequest($bdd,$create,$log);
     $bdd->close();
   }
?>
