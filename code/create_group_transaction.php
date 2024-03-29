<?php
session_start();
$connexion = mysqli_connect("localhost","root","","louda");
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
include("signout_popup.php");
createDatabase();
createTableUtilisateur();
createTableTransaction();
createTableAmitie();


if(isset($_POST['submit'])){



  if($_POST['repartition'] == "egale"){
    $statut = "Ouvert";
    $_SESSION['trans']= $_POST['trans'];
    $_SESSION['montant_grp'] = $_POST['montant_grp'];
    $_SESSION['source']= $_POST['source'];
    $_SESSION['cible'] = $_POST['cible'];
    $source= $_POST['source'];
    $cible= $_POST['cible'];
    $type="groupe";
    $montant_grp=$_POST['montant_grp'];
    $taille_source= count($source);
    $taille_cible = count($cible);
    $date_creation =  date('Y-m-d H:i:s');
    if ($taille_source == 1 && $taille_cible ==1){
      if ($source[0] != $cible[0]){
        $sq  = "INSERT INTO `transaction` (`id_utilisateur_source` , `id_utilisateur_cible` ,`montant`,`statut`,`date_et_heure_de_creation`,`nom_de_la_transaction`,`message`) VALUES (".$source[0].",".$cible[0].",".$montant_grp.",'".$statut."','".$date_creation."','".$_POST['trans']."','".$_POST['des_trans']."')";
        executeRequest($connexion , $sq);
      }
      header("location: historique_page.php");
    }
    else {
    for($i=0;$i<$taille_source;$i++){
      for($j=0;$j<$taille_cible;$j++){
        $id_source = intval($source[$i]);
        $id_cible =intval($cible[$j]);
        $montant_cible=$montant_grp/$taille_cible;
        $montant_sim = $montant_cible/$taille_source;
        $sq  = "INSERT INTO `transaction` (`id_utilisateur_source` , `id_utilisateur_cible` ,`montant`,`montant_groupe`,`statut`,`date_et_heure_de_creation`,`nom_de_la_transaction`,`message`) VALUES (".$id_source.",".$id_cible.",".$montant_sim.",".$montant_grp.",'".$statut."','".$date_creation."','".$_POST['trans']."','".$_POST['des_trans']."')";
        executeRequest($connexion , $sq);
       if($id_source==$id_cible){
         $ss = "DELETE FROM `transaction` WHERE id_utilisateur_cible = ".$id_cible." AND id_utilisateur_source=".$id_source." ";
         mysqli_query($connexion,$ss);}
      }
    }
  }
}

    else{
        $_SESSION['trans']= $_POST['trans'];
        $_SESSION['source']= $_POST['source'];
        $_SESSION['cible'] = $_POST['cible'];
        $_SESSION['montant_grp'] = $_POST['montant_grp'];
        $_SESSION['des_trans'] = $_POST['des_trans'];

        $source= $_POST['source'];
        $cible= $_POST['cible'];
        $montant_grp=$_POST['montant_grp'];
        $statut = "Ouvert";
        $taille_source= count($source);
        $taille_cible = count($cible);
        $date_creation =  date('Y-m-d H:i:s');
        if ($taille_source == 1 && $taille_cible ==1){
          //echo $source[0].":".$cible[0];
          if ($source[0] != $cible[0]){
            $sq  = "INSERT INTO `transaction` (`id_utilisateur_source` , `id_utilisateur_cible` ,`montant`,`statut`,`date_et_heure_de_creation`,`nom_de_la_transaction`,`message`) VALUES (".$source[0].",".$cible[0].",".$montant_grp.",'".$statut."','".$date_creation."','".$_POST['trans']."','".$_POST['des_trans']."')";
            executeRequest($connexion , $sq);
          }
          header("location: historique_page.php");
        }
        else{

        header("location: trans_g_ne.php");
      }
    }
  }

 ?>

<!DOCTYPE html>

<html lang= "fr">
<head>
  <meta charset="utf-8">
  <title> Transaction </title>

  <link rel="stylesheet" href="Louda.css">


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

    <body>

      <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
            <h1 class="my-0 mr-md-auto font-weight-normal">Louda</h1>
            <div class="container">
                <div class="row ">
                  <div class= "col-1"> <a id="navbar" href ='home_page.php'>Accueil</a></div>
                  <div class= "col-2"> <a id="navbar" href ='contact_page.php'> Carnet d'amis</a></div>
                  <div class= "col-3"> <a id="navbar"  href ='create_transaction_page.php'> Transaction simple </a></div>
                  <div class= "col-3"> <a id="navbarBg" class="col-sm bg rounded text-center" href ='create_group_transaction.php'>  Transaction groupe </a></div>
                  <div class= "col-3"> <a id="navbar" href ='historique_page.php'> Mes transactions</a></div>
                </div>
            </div>

            <button id="signout" data-toggle="modal" data-target="#SignOut" href="déconnexion.php">
              <img id="signoutpng" src="deconnexion.png" alt="..." class="rounded">
            </button>
            <?php CreateSignoutPopup(); ?>
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
            <div class=col>
              <h6>Les utilisateurs sources:</h6>
            </div>
              <div class="col">
              <?php

              $mail = $_SESSION['email'];
              $a = "SELECT id_utilisateur ,nom ,prenom FROM utilisateur  WHERE email = '".$mail."' ";
              $aa= mysqli_query($connexion,$a);
              $aaa=mysqli_fetch_assoc($aa);
              $utilisateur =  intval($aaa['id_utilisateur']);
              $nom = $aaa['nom'];
              $prenom = $aaa['prenom'];?>
              <div class="row">
                <input   type="checkbox" name = "source[]"  value="<?php echo $utilisateur; ?> "> <?php echo $nom; ?> <?php echo $prenom;?> : <?php echo $mail;?></input> <br>
              </div>

  <?php
              $req ="SELECT id_utilisateur_2 FROM amitie  where id_utilisateur_1 = '".$utilisateur."' ";
              $res=mysqli_query($connexion , $req);
              $i=0;
              while($row0 = mysqli_fetch_array($res)){
                $id_u = intval($row0[$i]);

                $r ="SELECT nom ,prenom ,email FROM utilisateur  where id_utilisateur= ".$id_u."";
                $rr = mysqli_query($connexion,$r);
                $row =mysqli_fetch_assoc($rr);

              ?>
              <div class="row">
                <input   type="checkbox" name = "source[]"  value="<?php echo $id_u; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?> : <?php echo $row['email'] ;?></input>
              </div>
            <?php
          }
            ?>
          </div>
        </div>
        <div class="form-group row">
          <div class="col">
            <h6> Les utilisateurs cibles :</h6>
          </div>
          <div class="col">
            <?php

            $mail = $_SESSION['email'];
            $a = "SELECT id_utilisateur ,nom ,prenom FROM utilisateur  WHERE email = '".$mail."' ";
            $aa= mysqli_query($connexion,$a);
            $aaa=mysqli_fetch_assoc($aa);
            $utilisateur =  intval($aaa['id_utilisateur']);
            $nom = $aaa['nom'];
            $prenom = $aaa['prenom'];?>
            <div class="row">
              <input   type="checkbox" name = "cible[]"  value="<?php echo $utilisateur; ?> "> <?php echo $nom; ?> <?php echo $prenom;?> : <?php echo $mail ;?></input>
            </div>

<?php
            $req ="SELECT id_utilisateur_2 FROM amitie  where id_utilisateur_1 = '".$utilisateur."' ";
            $res=mysqli_query($connexion , $req);
            $i=0;
            while($row0 = mysqli_fetch_array($res)){
              $id_u = intval($row0[$i]);
              $r ="SELECT nom ,prenom , email FROM utilisateur  where id_utilisateur= ".$id_u."";
              $rr = mysqli_query($connexion,$r);
              $row =mysqli_fetch_assoc($rr);
            ?>
            <div class="row">
              <input   type="checkbox" name = "cible[]"  value="<?php echo $id_u; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?> : <?php echo $row['email'] ;?></input>
            </div>
          <?php
        }
          ?>
        </div>
      </div>


      <div class="row">
        <div class="col">
          <label for="repartition">Type de répartition: </label>
        </div>
        <div class="col">
          <input type = "radio" id="repartition" name="repartition"  value="egale" >Répartition égale <br>
            <input type = "radio" id="repartition" name="repartition"  value="non_egale" checked>Répartition non égale <br>
      </div>
    </div>
    </br>
    </br>
    </br>
    <div style="text-align:center;">
      <div class="form-group">
        <input type ="submit" name="submit" value="Valider"></input>
      </div>
    </div>
   </form>
</div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  </body>
</html>
