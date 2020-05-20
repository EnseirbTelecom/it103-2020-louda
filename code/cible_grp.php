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
          <form class ="form-horizontal" action= "cible_grp.php" method="post" >
            <?php
            $source = $_SESSION['source'];
            $trans = $_SESSION['trans'];
            $montant_grp = $_SESSION['montant_grp'];
            //$des_trans = $SESSION['des_trans'];


          foreach ($source as $element) {
                $req1 ="SELECT nom ,prenom, id_utilisateur FROM utilisateur WHERE id_utilisateur ='".$element."'";
                $res1 = mysqli_query($connexion,$req1);
                $r1 = mysqli_fetch_assoc($res1);
                $nom_source = $r1["nom"];
                $prenom_source = $r1["prenom"];
                $id_source = $r1["id_utilisateur"];
                echo $nom_source;

                echo $prenom_source;?><br>
                <?php


              $req ="SELECT nom , prenom , id_utilisateur FROM utilisateur WHERE id_utilisateur !=  '".$element."'  ORDER BY nom , prenom";
              $res=mysqli_query($connexion , $req);

            ?><br>
            <?php
              while($row = mysqli_fetch_array($res)){



            ?>
          <input   type="checkbox" name = "cible[<?php echo $element; ?>][]"  value="<?php echo $row['id_utilisateur']; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?></option> <br>

          <?php
            }

          }
          ?>

          <input type ="submit" name="submit" value="submit">
        </form>

        <?php

        if(isset($_POST['submit'])){
          if(!empty($_POST['cible'])){


             $date_creation =  date('Y-m-d H:i:s');



            $_SESSION["date_creation"] = $date_creation;
            $_SESSION['cible']= $_POST['cible'];
            $s  = $_POST['cible'];


          foreach ($source as $element) {

              $id_source = $element;
              $cible1 = $s[$element];


             $nbr= count($cible1);?><br> <?php
             for($i=0;$i<$nbr;$i++){
                  $cible = intval($cible1[$i]);
               $request1 = "INSERT INTO `transaction` (`id_utilisateur_source`,`id_utilisateur_cible`,`nom_de_la_transaction`,`montant`,`date_et_heure_de_creation`,`statut`) VALUES ( '".$element."','". $cible."','".$trans."','".$montant_grp."','".$date_creation."','Ouvert')";
               executeRequest($connexion,$request1,true);
               mysqli_query($connexion,$request1);
               header("location: trans_g.php");

             }?><br> <?php

           } }  }




         ?>





        </div>
      </body>
      </html>
