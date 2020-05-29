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

if(empty($_SESSION['source']) || empty($_SESSION['cible'])){
  header("location: create_group_transaction.php");}

$afficher_champs = true;
?>

<?php
$alerte = false;
if(isset($_POST['submit'])){
  $afficher_champs = false;
  $somme=0;
  $tab = $_POST['montant'];
  $taille_insertion = count($tab);
  $montant_grp = $_SESSION['montant_grp'];
  $last_id = $_POST['last_id'];
  $last__id = intval($last_id);
  $init = $last__id - $taille_insertion;

  for($i=$init +1 ;$i<=$last__id;$i++){
    $id =$i;
    $montant_simple = intval($tab[$i-$init -1]);
    $somme=$somme+$montant_simple;

    if(empty($montant_simple)){
      $s="DELETE FROM `transaction` WHERE `transaction`.`id_transaction` = '".$id."' ";
      mysqli_query($connexion,$s);
    }
    $sql ="UPDATE transaction SET  montant = ".$montant_simple."  WHERE id_transaction = ".$id."";
    executeRequest($connexion,$sql);
  }

  if($somme != $montant_grp){
    for($i=$init +1 ;$i<=$last__id;$i++){
      $id =$i;
      $ss = "DELETE FROM `transaction` WHERE `transaction`.`id_transaction` = '".$id."' ";
      executeRequest($connexion,$ss);
      $afficher_champs = true;
      $alerte = true;
    }
  }
  else{
    unset($_SESSION['source']);
    unset($_SESSION['trans']);
    unset($_SESSION['montant_grp']);
    unset($_SESSION['source']);
    unset($_SESSION['cible'] );
    header("location: historique_page.php");
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

      <body>
        <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
              <h1 class="my-0 mr-md-auto font-weight-normal">Louda</h1>
              <div class="container">
                  <div class="row ">
                    <div class= "col-1"> <a id="navbar" href ='home_page.php'>Accueil</a></div>
                    <div class= "col-2"> <a id="navbar" href ='contact_page.php'> Carnet d'amis</a></div>
                    <div class= "col-3"> <a id="navbar"  href ='create_transaction_page.php'> Transaction simple </a></div>
                    <div class= "col-3"> <a id="navbarBg" class="col-sm bg rounded text-center"  href ='create_group_transaction.php'>  Transaction de groupe </a></div>
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
        <form class ="form-horizontal" action= "trans_g_ne.php" method="post" >
    <?php


    if($afficher_champs){
      $taille_source = count(($_SESSION['source']));
      $taille_cible = count($_SESSION['cible']);
      $source = $_SESSION['source'];
      $cible = $_SESSION['cible'];
      $trans = $_SESSION['trans'];
      $type = "groupe";
      $des_trans = $_SESSION['des_trans'];
      $montant_grp = $_SESSION['montant_grp'];
      $statut = "Ouvert";
      $date_creation =  date('Y-m-d H:i:s');

      for($i=0;$i<$taille_cible;$i++){
        $id_cible = intval($cible[$i]);
        $req1 ="SELECT nom , prenom FROM utilisateur WHERE id_utilisateur =  '".$id_cible."' ORDER BY nom , prenom ";
        $res1=mysqli_query($connexion , $req1);
        $row1 = mysqli_fetch_assoc($res1);
        $nom_cible = $row1["nom"];
        $prenom_cible = $row1["prenom"];
        for($j=0;$j<$taille_source;$j++){

         $id_source = intval($source[$j]);
         if($id_source != $id_cible){

           $request="INSERT INTO `transaction` (`id_utilisateur_source`,`id_utilisateur_cible`,`montant_groupe`,`nom_de_la_transaction`,`statut`,`date_et_heure_de_creation`,`message`) VALUES ( '".$id_cible."','".$id_source."','".$montant_grp."','".$trans."','".$statut."','".$date_creation."','".$des_trans."')";
           mysqli_query($connexion, $request);
           $req_s = "DELETE FROM `transaction` WHERE id_utilisateur_cible = id_utilisateur_source";
           mysqli_query($connexion,$req_s);
           $sql = "SELECT MAX(id_transaction) AS id_transaction FROM transaction";
           $res = mysqli_query($connexion,$sql);
           $row= mysqli_fetch_assoc($res);
           $last_id = $row["id_transaction"];
           echo "<input type=\"hidden\" value=".$last_id." name=\"last_id\">";



           $req2 ="SELECT nom , prenom FROM utilisateur WHERE id_utilisateur =  '".$id_source."'  ORDER BY nom , prenom ";
           $res2 =mysqli_query($connexion , $req2);
           $row2 = mysqli_fetch_assoc($res2);
           $nom_source = $row2["nom"];
           $prenom_source = $row2["prenom"];?>
          <label> <?php echo $nom_cible ?> <?php echo $prenom_cible ?> doit à <?php echo $nom_source ?> <?php echo $prenom_source ?>: <br> </label>
          <input type="number"  step ="0.01"  min="0"  class ="form-control" id="montant" name="montant[]" placeholder="montant de la transaction"  required>


<?php
   }
  }
 }
}
?>

<input type ="submit" name="submit" value="Valider">
<?php
  if ($alerte){?>
  <div class="alert alert-danger" role = "alert"><?php echo "Les montants saisies sont erronés, la somme des montants doit valoirs :".$montant_grp.".";?> </div>
<?php } ?>
</form>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
