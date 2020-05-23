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

if(empty($_SESSION['source']) || empty($_SESSION['cible'])){
  header("location: create_group_transaction.php");}


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
              <h1 class="my-0 mr-md-auto font-weight-normal">Titre du site</h1>
              <div class="container">
                  <div class="row ">
                    <div class= "col-1"> <a id="navbar" href ='home_page.php'>Accueil</a></div>
                    <div class= "col-2"> <a id="navbar" href ='contact_page.php'> Carnet d'amis</a></div>
                    <div class= "col-3"> <a id="navbar"  href ='create_transaction_page.php'> Transaction simple </a></div>
                    <div class= "col-3"> <a id="navbar"  href ='create_group_transaction.php'>  Transaction de groupe </a></div>
                    <div class= "col-3"> <a id="navbar" href ='historique_page.php'> Mes transactions</a></div>
                  </div>
              </div>

              <button id="signout" data-toggle="modal" data-target="#SignOut" href="déconnexion.php">
                <img id="signoutpng" src="deconnexion.png" alt="..." class="rounded">
              </button>
            </div>
        <div class="container">
            <div class="header offset-md-3">
                <h1>Nouvelle transaction</h1>
            </div>
            <br>
            <br>
        <form class ="form-horizontal" action= "trans_g_ne.php" method="post" >
    <?php


   $taille_source = count(($_SESSION['source']));
   $taille_cible = count($_SESSION['cible']);
   $source = $_SESSION['source'];
   $cible = $_SESSION['cible'];
   $trans = $_SESSION['trans'];
   $type = "groupe";
  $des_trans = $_SESSION['des_trans'];
     $montant_grp = $_SESSION['montant_grp'];
   $statut = "ouvert";
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

               $request="INSERT INTO `transaction` (`id_utilisateur_source`,`id_utilisateur_cible`,`type_de_transaction`,`montant_groupe`,`nom_de_la_transaction`,`statut`,`date_et_heure_de_creation`,`message`) VALUES ( '".$id_cible."','".$id_source."','".$type."','".$montant_grp."','".$trans."','".$statut."','".$date_creation."','".$des_trans."')";
               mysqli_query($connexion, $request);
               $req_s = "DELETE FROM `transaction` WHERE id_utilisateur_cible = id_utilisateur_source";
               mysqli_query($connexion,$req_s);
               $sql = "SELECT MAX(id_transaction) AS id_transaction FROM transaction";
               $res = mysqli_query($connexion,$sql);
               $row= mysqli_fetch_assoc($res);
               $last_id = $row["id_transaction"];



               $req2 ="SELECT nom , prenom FROM utilisateur WHERE id_utilisateur =  '".$id_source."'  ORDER BY nom , prenom ";
               $res2 =mysqli_query($connexion , $req2);
               $row2 = mysqli_fetch_assoc($res2);
               $nom_source = $row2["nom"];
               $prenom_source = $row2["prenom"];?>
              <label> <?php echo $nom_cible ?> <?php echo $prenom_cible ?> doit à <?php echo $nom_source ?> <?php echo $prenom_source ?>: <br> </label>
              <input type="number"  step ="0.01"  min="0"  class ="form-control" id="montant" name="montant[]" placeholder="montant de la transaction"  required>


<?php
   }
 }  }

 ?>
<input type ="submit" name="submit" value="submit">

</form>
<?php

if(isset($_POST['submit'])){

$somme=0;
$tab = $_POST['montant'];
$taille_insertion = count($tab);
 $montant_grp = $_SESSION['montant_grp'];
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
mysqli_query($connexion,$sql);}

if($somme != $montant_grp){
  for($i=$init +1 ;$i<=$last__id;$i++){
  $id =$i;


  $ss = "DELETE FROM `transaction` WHERE `transaction`.`id_transaction` = '".$id."' ";
mysqli_query($connexion,$ss);
}
  ?>
  <div class="alert alert-danger" role = "alert"><?php echo "Les montants saisies sont erronés.";?> </div> <?php

}
else{
  unset($_SESSION['source']);
  unset($_SESSION['trans']);
  unset($_SESSION['montant_grp']);
  unset($_SESSION['source']);
  unset($_SESSION['cible'] );
  header("location: historique_page.php");

}}




?>
</body>
</html>
