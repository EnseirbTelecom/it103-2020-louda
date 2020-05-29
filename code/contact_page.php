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
include("signout_popup.php");
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
    <link rel="stylesheet" href="Louda.css">
  </head>
  <body>
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
        <h1 class="my-0 mr-md-auto font-weight-normal">Louda</h1>
        <div class="container">
            <div class="row ">
                <div class= "col-1"> <a id="navbar" href ='home_page.php'>Accueil</a></div>
                <div class= "col-2"> <a id="navbarBg" class="col-sm bg rounded text-center" href ='contact_page.php'> Carnet d'amis</a></div>
                <div class= "col-3"> <a id="navbar" href ='create_transaction_page.php'> Transaction simple</a></div>
                <div class= "col-3"> <a id="navbar" href ='create_group_transaction.php'>  Transaction de groupe </a></div>
                <div class= "col-3"> <a id="navbar" href ='historique_page.php'> Mes transactions</a></div>
            </div>
        </div>
        <button id="signout" data-toggle="modal" data-target="#SignOut" href="déconnexion.php">
          <img id="signoutpng" src="deconnexion.png" alt="..." class="rounded">
        </button>
        <?php CreateSignoutPopup(); ?>
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
            <input type="text"  id="search" name="search" placeholder="Search" value="<?php echo isset( $_POST['search'] ) ? $_POST['search'] : "";?>"  class="form-control">
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
            ?>
            <li class="list-group-item border border-secondary" > - <a href="historique_page.php?selection=<?php echo $ami['email'] ?>" id="friendColor"><?php echo $ami['prenom']. " " . $ami['nom']. "</a> (".$ami['pseudo']."), " .$ami['email'];
            if($search == "active"){
              echo "<br><form method=\"post\" action=\"contact_page.php?selection=search\"><input type=\"hidden\" value=".$ami['id_utilisateur']." name=\"selected\"><input type=\"submit\" value=\"Ajouter\" name=\"submit\"></form></li>";
            }else{

              $solde = BalanceCalculation($me['id_utilisateur'],$ami['id_utilisateur']);

              if ($solde ==0){
                echo "<br> <br> <h3 id=\"solde\" class=\" mx-auto col-sm-5 border border-dark\">Solde : ".$solde."€</h3>";
                echo "<br><form method=\"post\" action=\"contact_page.php\"><input type=\"hidden\" value=".$ami['id_amitie']." name=\"selected\"><input type=\"submit\" value=\"Supprimer\" name=\"submit\"></form></li>";
              }
              else{
                echo "<br> <br> <h3 class=\"col-sm-5 mx-auto border border-dark\" id=\"solde\">Solde : ".$solde."€</h3>";
                echo "<br><form method=\"post\" action=\"contact_page.php\"><input type=\"hidden\" value=".$ami['id_amitie']." name=\"selected\"><input type=\"submit\" value=\"Supprimer\" name=\"submit\" disabled></form></li>";
              }
            }
            $row++;
          }
        }
        else{echo "<li>Pas de nouveaux amis disponibles</li>";}
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
