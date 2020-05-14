<?php
session_start();
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
include("database_request.php");
createTableAmitie();
$me = getUtilisateurWithEmail($_SESSION["email"]);


function filter($input){
  $taille = sizeof($input);
  if (isset($_POST['search'])){
    if ($_POST['search']==""){return $input;}
    $filtered = array();
    $search = $_POST['search'];
    for ($i = 0; $i < $taille; $i++){
      if ((stripos($input[$i]['nom'],$search) !== false)||(stripos($input[$i]['prenom'],$search) !== false)||(stripos($input[$i]['pseudo'],$search) !== false)||(stripos($input[$i]['email'],$search) !== false)){
        $ind = sizeof($filtered);
        $filtered += array($ind => $input[$i]);
      }
    }
  }
  else{return $input;}
  return $filtered;
}

function ajouterAmi($myId,$friendId){
  //return an array of all users who are different not current user
  $log=true;
  $bdd = connectDatabase($log);
  $all = "INSERT INTO amitie (id_utilisateur_1, id_utilisateur_2) VALUES ('$myId','$friendId')";
  $result = executeRequest($bdd,$all,$log);
  $bdd->close();
}
function supprimerAmi($amitieId){
  //return an array of all users who are different not current user
  $log=false;
  $bdd = connectDatabase($log);
  $all = "DELETE FROM amitie WHERE id_amitie='$amitieId'";
  $result = executeRequest($bdd,$all,$log);
  $bdd->close();
}

if(isset($_POST['submit']))
{
  if ($_POST['submit']=="Ajouter"){
    if(isset($_POST['selected']))
    {
      ajouterAmi($me["id_utilisateur"],$_POST['selected']);
      header("location: contact_page.php?selection=search");
    }
  }
  if ($_POST['submit']=="Supprimer"){
    if(isset($_POST['selected']))
    {
      $MyFriendId=GetMyFriendId($_POST['selected'],$me['id_utilisateur']);
      $balance = BalanceCalculation($me['id_utilisateur'],$MyFriendId);
      if ($balance==0){
        supprimerAmi($_POST['selected']);
        header("location: contact_page.php");
      }
      else {
        $error="Le solde avec cet amis doit etre nul !";
      }
    }
  }
}
?>


<!DOCTYPE html>
<html lang ="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Contacts</title>
  </head>
  <body>
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
        <h1 class="my-0 mr-md-auto font-weight-normal">Titre du site</h1>
        <div class="container">
            <div class="row ">
                <div class= "col"> <a href ='home_page.php'> Home</a></div>
                <div class= "col"> <a class="col-sm bg-primary text-white rounded text-center" href ='contact_page.php'> Carnet d'amis</a></div>
                <div class= "col"> <a href ='create_transaction_page.php'> Nouvelle transaction</a></div>
                <div class= "col"> <a href ='historique_page.php'> Historique</a></div>
            </div>
        </div>
        <a class="btn btn-outline-primary" id="signin" href="déconnexion.php">Déconnexion</a>
  </div>
  <div class="container">
    <div class="row">
    <div class="col">
      <div class="list-group">
        <?php
        $friends="active";
        $search="";
        if (isset($_GET['selection'])){
  				if ($_GET['selection']==search){
            $friends="";
            $search="active";
          }
        }
        ?>
        <a href="contact_page.php" class="<?php echo "list-group-item list-group-item-action ".$friends;?>">Amis</a>
        <a href="contact_page.php?selection=search" class="<?php echo "list-group-item list-group-item-action ".$search;?>" >Rechercher un nouvel ami</a>
        <form class="list-group-item list-group-item-action" action="<?php echo "contact_page.php";if($search=="active"){echo "?selection=search";} ?>" method="POST">
          <div class="col">
            <input type="text"  id="search" name="search" placeholder="Search" value="<?php echo $_POST['search'];?>"  class="form-control">
          </div>
          <div class="col">
            <input type="submit" value="Search">
          </div>
        </form>
      </div>
    </div>
      <div class="col">
      <div class="list-group">

        <?php

        $allFriends = getAllFriends($me['id_utilisateur']);
        if ($friends == "active"){
          echo "<li  class=list-group-item>Liste des amis</li>";
          $output = $allFriends;
        }
        else{
          echo "<li  class=list-group-item>Pas encore amis</li>";
          $allUsers = getAllUsers($me['id_utilisateur']);
          $n = sizeof($allUsers);
          $m = sizeof($allFriends);
          $j = 0;
          $output = array();
          for ($i = 0; $i < $n ; $i++){
            $isAmi = 0;
            for ($j = 0; $j < $m ; $j++){
              if ($allUsers[$i]['id_utilisateur']==$allFriends[$j]['id_utilisateur']){
                $isAmi++;
              }
            }
            if ($isAmi==0){
              $ind = sizeof($output);
              $output += array($ind => $allUsers[$i] );
            }
          }
        }
        $output = filter($output);
        $size = sizeof($output);
        echo "<ul>";
        if ($size > 0) {
          // output data of each row
          $row = 0;
          while($row < $size) {
            $ami = $output[$row];
            echo "<li class=\"list-group-item\"> -" . $ami['prenom']. " " . $ami["nom"]. " (".$ami['pseudo']."), " .$ami['email'];
            if($search == "active"){
              echo "<br><form method=\"post\" action=\"contact_page.php?selection=search\"><input type=\"hidden\" value=".$ami['id_utilisateur']." name=\"selected\"><input type=\"submit\" value=\"Ajouter\" name=\"submit\"></form></li>";
            }else{

              $solde = BalanceCalculation($me['id_utilisateur'],$ami['id_utilisateur']);

              if ($solde ==0){
                echo "<p style=\"color:rgb(0,125,0);\">solde : ".$solde."</p>";
                echo "<br><form method=\"post\" action=\"contact_page.php\"><input type=\"hidden\" value=".$ami['id_amitie']." name=\"selected\"><input type=\"submit\" value=\"Supprimer\" name=\"submit\"></form></li>";
              }
              else{
                echo "<p style=\"color:rgb(255,0,0);\">solde : ".$solde."</p>";
                echo "<br><form method=\"post\" action=\"contact_page.php\"><input type=\"hidden\" value=".$ami['id_amitie']." name=\"selected\"><input type=\"submit\" value=\"Supprimer\" name=\"submit\" disabled></form></li>";
              }
            }
            $row++;
          }
        }
        else{echo "<li>pas d'amis</li>";}
        echo "</ul>";
        ?>
      </div>
      </div>
    </div>
  </div>




<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>





</body>

  </html>
