

<!DOCTYPE html>
<html lang ="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Page de connexion </title>
  </head>
<body>
  <div class="container">
    <div class="row ">
      <div class="col-8">
        <h1>Titre du site</h1>
      </div>
    </div>
    <div class="row">
      <div class= "col-md-4 offset-md-8"> Pas encore inscrit ? <a href ='inscription.php'> inscription</a></div>
    </div>

    <div class="jumbotron" style="margin-top:60px">
    <div class=" row">

      <div class= "col-md-4 offset-md-5"> Se connecter </div>
    </div>


    <form action= "connexion.php" method="post" >
      <div class="form-group">

      <label> Email : </label>
      <input type="text" class ="form-control" name="email" required  placeholder="Email">



      <label>Mot de passe :</label>
      <input type ="password" class="form-control" name="pwd" required placeholder="Mot de passe">

         <br>
       <input type ="submit" value="Confirmer">
<?php

if(isset($_GET['erreur'])){
  $err = $_GET['erreur'];
  if($err = 1){
  ?>  <div class="alert alert-danger" role = "alert">Vos identifiants sont incorrectes.</div> <?php
  }
  }

?>
      </div>
    </form>
  </div>
  </div>

</body>

  </html>
