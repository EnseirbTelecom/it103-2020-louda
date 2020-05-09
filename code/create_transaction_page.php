
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

      <div class="container">

          <div class="row ">
              <div class= "col"> <a href ='home_page.php'> Home</a></div>
              <div class= "col"> <a href ='contact_page.php'> Carnet d'amis</a></div>
              <div class= "col"> <a class="col-sm bg-primary text-white rounded text-center" href ='create_transaction_page.php'> Nouvelle transaction</a></div>
              <div class= "col"> <a href ='historique_page.php'> Historique</a></div>
              <div class= "col"> <a class="btn btn-outline-primary" href ='déconnexion.php'> déconnexion</a></div>
        </div>


      <form action= "create_transaction_page.php" method="post" >
        <div class="form-group">
        <label> Nom de la transaction : </label>
        <input type="text" class ="form-control" name="trans"  placeholder="Nom de la transaction" required >
        </div>

          <div class="form-group">
          <label> Description de la transaction: </label>
          <textarea class ="form-control" name="des_trans"  placeholder="décrire votre transaction" rows="3" cols="6" ></textarea>
          </div>


          <div class="form-group">
        <label> Utilisateur source: </label>
        <select name="source" class='form-control' >
           <?php
        $req ='SELECT nom , prenom , id_utilisateur FROM utilisateur ORDER BY nom , prenom';
        $res=mysqli_query($connexion , $req);
        while($row = mysqli_fetch_array($res)){ ?>
        <option value="<?php echo $row['id_utilisateur']; ?> "> <?php echo $row['nom'] ?> <?php echo $row['prenom'] ;?></option>
        <?php
      }
      ?></select>


        <label> Utilisateur cible : </label>
        <select name="cible" class='form-control'>
        <?php
        $req ='SELECT nom , prenom , id_utilisateur FROM utilisateur ORDER BY nom,prenom';
        $res=mysqli_query($connexion , $req);
        while($row = mysqli_fetch_array($res)){ ?>
        <option value="<?php echo $row['id_utilisateur']; ?>"> <?php echo $row['nom']; ?> <?php echo $row['prenom']; ?></option>

      <?php } ?>

    </select>


        </div>
        <div class="form-group">
        <label> Montant:</label>
        <input type="number"  step ="0.01"  min="0"  class ="form-control" name="montant" placeholder="montant de la transaction"  required>
      </div>


        <input type ="submit" value="Confirmer"  name="confirmer">

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
  </body>
</html>
