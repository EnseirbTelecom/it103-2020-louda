<?php

include("create_database.php");
include("database_request.php");

  if(isset($_POST['email']) && isset($_POST['pwd'])){
    if(!empty($_POST['email']) || !empty($_POST['pwd'])){
      $connexion = mysqli_connect("localhost","admin","it103","louda");
      if(!$connexion){
      die("erreur de connexion à la base de donnée");}

      $email = $_POST['email'];
      $pwd = $_POST['pwd'];

      $sql = "SELECT count(*) FROM  utilisateur where email = '".$email."' AND mot_de_passe = '".$pwd."' ";
    $result = mysqli_query($connexion,$sql);

      $row = mysqli_fetch_array($result);
      $count = $row['count(*)'];
      if($count!=0){

      $SESSION['email']=$_POST['email'];
      $SESSION['pwd']=$_POST['pwd'];

  header("location: inscription.php");}
else {
      header("location: connexion_page.php?erreur=1");}}}

mysqli_close($connexion);


?>