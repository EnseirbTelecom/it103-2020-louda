<?php
session_start();
$connexion = mysqli_connect("localhost","admin","it103","louda");
if(!$connexion){
  die("erreur de de connexion à la base de données");
}
if (empty($_SESSION)){
    header("location: connexion_page.php");
}
else{
  if(time()-$_SESSION['last_time'] >3600 ){
    header("location:déconnexion.php");
  }
  else{
    $_SESSION['last_time']=time();
  }
}
include("database_request.php");
include("create_database.php");
createDatabase();
createTableUtilisateur();
createTableTransaction();
createTableAmitie();


?>



<!DOCTYPE html>

<html lang= "fr">
<head>
  <meta charset="utf-8">
  <title> Transaction </title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

    <body>

      <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
            <h1 class="my-0 mr-md-auto font-weight-normal">Titre du site</h1>
            <div class="container">
                <div class="row ">
                    <div class= "col"> <a href ='home_page.php'> Home</a></div>
                    <div class= "col"> <a href ='contact_page.php'> Carnet d'amis</a></div>
                    <div class= "col"> <a class="col-sm bg-primary text-white rounded text-center" href ='create_transaction_page.php'> Nouvelle transaction</a></div>
                    <div class= "col"> <a href ='historique_page.php'> Historique</a></div>
                </div>
            </div>
            <a class="btn btn-outline-primary" id="signin" href="déconnexion.php">Déconnexion</a>
      </div>

      <div class ="container">
          <form class ="form-horizontal" action= "create_group_transaction.php" method="post" >
            <div class="form-group row">
              <label for="trans" class="col-sm-3 col-form-label offset-md-2"> Nom de la transaction : </label>
              <div class="col-lg-4">
                <input type="text" class ="form-control" id="trans" name="trans"  placeholder="Nom de la transaction" required >
              </div>
            </div>
            <div class="form-group row">
              <label for="des_trans" class="col-sm-3 col-form-label offset-md-2"> Description de la transaction: </label>
              <div class="col-lg-4">
                <textarea class ="form-control" id="des_trans" name="des_trans"  placeholder="décrire votre transaction" rows="3" cols="6" ></textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="montant_grp" class="col-sm-3 col-form-label offset-md-2"> Montant de la transaction de groupe:</label>
              <div class="col-lg-4">
                <input type="number"  step ="0.01"  min="0"  class ="form-control" id="montant_grp" name="montant_grp" placeholder="montant de la transaction de groupe"  required>
              </div>
            </div>
            <br/>

          <div class="form-group row">

              <?php
                $req ='SELECT nom , prenom , id_utilisateur FROM utilisateur ORDER BY nom , prenom';
                $res=mysqli_query($connexion , $req);
                while($row = mysqli_fetch_array($res)){

              ?>
            <input   type="checkbox" name = "source[]"  value="<?php echo $row['id_utilisateur']; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?></option>
            <?php
              }
            ?>

        </div>

          <input type ="submit" name="submit" value="submit">
   </form>


<?php

if(isset($_POST['source'])){
  if(!empty($_POST['source'])){
    $_SESSION['source']= $_POST['source'];
    $_SESSION['trans']=$_POST['trans'];
    $_SESSION['montant_grp']=$_POST['montant_grp'];

      header("location: cible_grp.php");

  }
}

 ?>




</div>
  </body>
</html>
