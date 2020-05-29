
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
include("signout_popup.php");
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
                <div class= "col-3"> <a id="navbarBg" class="col-sm bg rounded text-center"  href ='create_transaction_page.php'> Transaction simple </a></div>
                <div class= "col-3"> <a id="navbar" href ='create_group_transaction.php'>  Transaction groupe </a></div>
                <div class= "col-3"> <a id="navbar" href ='historique_page.php'> Mes transactions</a></div>
              </div>
          </div>
          <button id="signout" data-toggle="modal" data-target="#SignOut" href="déconnexion.php">
            <img id="signoutpng" src="deconnexion.png" alt="..." class="rounded">
          </button>
          <?php CreateSignoutPopup(); ?>
    </div>
    <div class="container">
        <div class="header offset-md-3">
            <h1>Nouvelle transaction</h1>
        </div>
        <br>
        <br>
      <form class ="form-horizontal" action= "create_transaction_page.php" method="post" >
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
          <label for="source" class="col-sm-3 col-form-label offset-md-2"> Utilisateur source: </label>
          <div class="col-lg-4">
            <select name="source" id="source" class='form-control' >
              <?php
              $mail = $_SESSION['email'];
              $a = "SELECT id_utilisateur ,nom ,prenom ,email FROM utilisateur  WHERE email = '".$mail."' ";
              $aa= mysqli_query($connexion,$a);
              $aaa=mysqli_fetch_assoc($aa);
              $utilisateur =  intval($aaa['id_utilisateur']);
              $nom = $aaa['nom'];
              $prenom = $aaa['prenom'];
              $email = $aaa['email'];

              ?>
            <option value="<?php echo $utilisateur; ?> "> <?php echo $nom ?> <?php echo $prenom ;?> : <?php echo $email;?></option><br>

<?php
            $req ="SELECT id_utilisateur_2 FROM amitie  where id_utilisateur_1 = '".$utilisateur."' ";
            $res=mysqli_query($connexion , $req);
            $i=0;
            while($row0 = mysqli_fetch_array($res)){
              $id_u = intval($row0[$i]);

              $r ="SELECT nom ,prenom , email FROM utilisateur  where id_utilisateur= ".$id_u."";
              $rr = mysqli_query($connexion,$r);
              $row =mysqli_fetch_assoc($rr);?>

              <option value="<?php echo $id_u; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?> : <?php echo $row['email']?></option><br>



            <?php
              }
            ?>
           </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="cible" class="col-sm-3 col-form-label offset-md-2"> Utilisateur cible : </label>
          <div class="col-lg-4">
            <select name="cible" id="cible" class='form-control'>
              <?php
              $mail = $_SESSION['email'];
              $a = "SELECT id_utilisateur ,nom ,prenom , email FROM utilisateur  WHERE email = '".$mail."' ";
              $aa= mysqli_query($connexion,$a);
              $aaa=mysqli_fetch_assoc($aa);
              $utilisateur =  intval($aaa['id_utilisateur']);
              $nom = $aaa['nom'];
              $prenom = $aaa['prenom'];
              $email = $aaa['email']


              ?>
            <option value="<?php echo $utilisateur; ?> "> <?php echo $nom ?> <?php echo $prenom ;?> : <?php echo $email ;?> </option><br>

<?php
            $req ="SELECT id_utilisateur_2 FROM amitie  where id_utilisateur_1 = '".$utilisateur."' ";
            $res=mysqli_query($connexion , $req);
            $i=0;
            while($row0 = mysqli_fetch_array($res)){
              $id_u = intval($row0[$i]);

              $r ="SELECT nom ,prenom ,email FROM utilisateur  where id_utilisateur= ".$id_u."";
              $rr = mysqli_query($connexion,$r);
              $row =mysqli_fetch_assoc($rr);?>

              <option value="<?php echo $id_u; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?> :: <?php echo $row['email']?> </option><br>

          <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="montant" class="col-sm-3 col-form-label offset-md-2"> Montant:</label>
          <div class="col-lg-4">
            <input type="number"  step ="0.01"  min="0"  class ="form-control" id="montant" name="montant" placeholder="montant de la transaction"  required>
          </div>
        </div>
        <br />
        <div class="form-group row justify-content-center">
          <div class="col-2 ">
          <input type ="submit" value="Confirmer"  name="confirmer">
          </div>
        </div>

        <?php
        if( isset($_POST['trans']) && isset($_POST['des_trans']) && isset($_POST['montant']) && isset($_POST['source']) && isset($_POST['cible'])){
        if( !empty($_POST['trans']) && !empty($_POST['des_trans'])  && !empty($_POST['montant']) && !empty($_POST['source']) && !empty($_POST['cible'])){

        $date_creation =  date('Y-m-d H:i:s');
        $statut = "Ouvert";
        $montant=$_POST['montant'];

       $id_source = $_POST['source'];
       $id_cible = $_POST['cible'];

       $sql1 = "SELECT nom , prenom from utilisateur WHERE id_utilisateur  = '".$id_source."'" ;
       $r1 = mysqli_query($connexion,$sql1);
       $res1 = mysqli_fetch_assoc($r1);
       $nom_source = $res1["nom"];
       $prenom_source = $res1["prenom"];

       $sql2 = "SELECT nom , prenom from utilisateur WHERE id_utilisateur  = '".$id_cible."'" ;
       $r2 = mysqli_query($connexion,$sql2);
       $res2 = mysqli_fetch_assoc($r2);
       $nom_cible = $res2["nom"];
       $prenom_cible = $res2["prenom"];
       $request = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`) VALUES ( '".$_POST['trans']."','". $statut."','". $_POST['source']."','". $_POST['cible'] ."','". $_POST['montant'] ."','". $date_creation."','".$_POST['des_trans']."')";
       executeRequest($connexion,$request);
       ?>

      <div class="row justify-content-center"> Récapitulatif de la transaction:</div>
      <div class="row justify-content-center">
        <span class=" bg-light border border-info">
          Utilisateur source:<?php echo $prenom_source ;?> <?php echo $nom_source ;?><br>
          Utilisateur cible:<?php echo $prenom_cible ;?> <?php echo $nom_cible ;?><br>
          Montant: <?php echo $montant; ?> <br>
          Date d'ouverture: <?php echo $date_creation; ?><br>
          Statut: <?php echo $statut; ?> <br></span></div> <?PHP  } } ?>

        </form>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  </body>
</html>
