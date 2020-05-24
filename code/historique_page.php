<?php
session_start();
if (empty($_SESSION)){
    header("location: connexion_page.php");
}
include("database_request.php");
include("create_database.php");
include("signout_popup.php");
include("close_popup.php");

$me = getUtilisateurWithEmail($_SESSION["email"]);

if (!empty($_POST['search'])) {
  $s = $_POST['search'];
}
else {
  $s = NULL;
}

$search="";

if (isset($_GET['selection'])){
  $s = $_GET['selection'];
}

//$DateSelected = isset( $_GET['Open_date'] ) ? $_GET['Open_date'] : "" ;
//$StatueSelected = isset( $_GET['statue'] ) ? $_GET['statue'] : "" ;
$DateSelected = $_POST['Open_date'];
$SelectedValue = $_POST['statue'];
$Debt_selection = $_POST['Account'];

$CloseMessage = $_POST['message_text'];
$CloseDate = $_POST['today_date'];
$CloseStatue = $_POST['Close_reason'];
$id = $_POST['id_transaction'];

$NewMessage = $_POST['new_message_text'];
$NewAmount = $_POST['new_amount'];

if (!empty($NewAmount) || !empty($NewMessage)){
  ModifTransaction($id,$NewMessage,$NewAmount);
}
if (!empty($CloseDate) && !empty($CloseMessage)){
  CloseTransaction($id,$CloseDate,$CloseMessage,$CloseStatue);
}
else{
  if(time()-$_SESSION['last_time'] >3600 ){
    header("location:déconnexion.php");
  }
  else{
    $_SESSION['last_time']=time();
  }
}
?>



<!DOCTYPE html>
<html lang ="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Historique </title>
    <link rel="stylesheet" href="Louda.css">
  </head>
<body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
        <h1 class="my-0 mr-md-auto font-weight-normal">Louda</h1>
        <div id="green" class="container">
            <div class="row ">
                <div class= "col-1"> <a id="navbar" href ='home_page.php'>Accueil</a></div>
                <div class= "col-2"> <a id="navbar" href ='contact_page.php'>Carnet d'amis</a></div>
                <div class= "col-3"> <a id="navbar" href ='create_transaction_page.php'>Transaction simple</a></div>
                <div class= "col-3"> <a id="navbar" href ='create_group_transaction.php'>  Transaction de groupe </a></div>
                <div class= "col"> <a id="navbarBg" class="col-sm bg rounded text-center" href ='historique_page.php'> Mes transactions </a></div>
            </div>
        </div>
        <button id="signout" data-toggle="modal" data-target="#SignOut" href="déconnexion.php">
          <img id="signoutpng" src="deconnexion.png" alt="..." class="rounded">
        </button>
        <?php CreateSignoutPopup(); ?>
</div>
  <div class="container-fluid">
      <h1>Historique</h1>
      <form class="form-inline" action="historique_page.php" method="post">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th>Prénom
                  <div class="col">
                    <input class="form-control" id="search" name="search" type="search" placeholder="Search" aria-label="Search"  value="<?php echo $_POST['search'];?>">
                  </div>
            </th>
            <th> Nom de la transaction </th>
              <th>Dettes ou créances
                <div class="mr-sm-2 dropdown">
                  <select name="Account" class="browser-default custom-select">
                    <option value="1" <?php if( $Debt_selection == "1") {echo "selected";} ?>>Dettes et créances</option>
                    <option value="2" <?php if( $Debt_selection == "2") {echo "selected";} ?>>Dettes</option>
                    <option value="3" <?php if( $Debt_selection == "3") {echo "selected";} ?>>Créances</option>
                  </select>
                </div>
              </th>
              <th> Message </th>
              <th>Date d'ouverture
                <div class="mr-sm-2 dropdown">
                  <select name="Open_date" class="browser-default custom-select">
                    <option value="2" <?php if( $DateSelected == "2") {echo "selected";} ?>>Décroissante</option>
                    <option value="1" <?php if( $DateSelected == "1") {echo "selected";} ?>>Croissante</option>
                  </select>
                </div>
              </th>
              <th>Date de fermeture
              </th>
              <th>Statut de la transaction
                <div class="mr-sm-8 dropdown">
                  <select name="statue" class="browser-default custom-select">
                    <option value="1" <?php if( $SelectedValue == "1") {echo "selected";} ?>>Ouvert</option>
                    <option value="2"<?php if( $SelectedValue == "2") {echo "selected";} ?>>Remboursée</option>
                    <option value="3"<?php if( $SelectedValue == "3") {echo "selected";} ?>>Annulée</option>
                    <option value="4" <?php if( $SelectedValue == "4") {echo "selected";} ?>>Toute</option>
                  </select>
                </div>
              </th>
              <th>
                <input type="submit" value="Appliquer">
              </th>
          </tr>
        </thead>
        <tbody>
          <?php
          $open_date = "2";
          $statue = "1";
          $Debt_Receivables = "1";
              if(isset($_POST['Open_date']) && isset($_POST['statue']) && isset($_POST['statue'])) {
                $open_date = ceil($_POST['Open_date']);
                $statue = ceil($_POST['statue']);
                $Debt_Receivables = ceil($_POST['Account']);
              }
              $alltransaction = getMyTransactions($me['id_utilisateur'],$open_date,$statue,$Debt_Receivables);
              foreach ( $alltransaction as $transaction ) {
                $var=Test_my_id($me['id_utilisateur'],$transaction['id_utilisateur_source'],$transaction['id_utilisateur_cible']);

                if ($var==1){
                  if (empty($s)){
                    $rows = SelectUser($transaction['id_utilisateur_source']);
                  }
                  else {
                    $rows = Update_UserSelection($s,$transaction['id_utilisateur_source']);
                  }
                }
                else {
                  if (empty($s)){
                    $rows = SelectUser($transaction['id_utilisateur_cible']);
                  }
                  else {

                    $rows = Update_UserSelection($s,$transaction['id_utilisateur_cible']);
                  }
                }
                foreach ( $rows as $user) {
                  if ($transaction['montant_groupe']!=NULL){
                    $italique = "<I>";
                    $finItalique = "</I>";
                  }
                  else{
                    $italique = "";
                    $finItalique = "";
                  }
            ?>
              <tr <?php if ($transaction['statut']!='Ouvert') { ?> class="table-secondary" <?php }?> >
                <td><?php echo $italique.$user['prenom']." ".$user['nom'].$finItalique; ?></td>
                <td><?php echo $italique.$transaction['nom_de_la_transaction'].$finItalique;?></td>
                <td <?php if ($var==1){echo 'id="red"';?>><?php echo "- ".$transaction['montant']; ?><?php } elseif ($var==0) {echo 'id="green"';?>><?php echo "+ ".$transaction['montant'];} ?></td>
                <td><?php echo $italique.$transaction['message'].$finItalique; ?></td>
                <td><?php echo $italique.$transaction['date_et_heure_de_creation'].$finItalique; ?></td>
                <td><?php echo $italique.$transaction['date_de_fermeture'].$finItalique; ?></td>
                <td><?php echo $italique.$transaction['statut'].$finItalique; ?></td>
                <td><?php if ($transaction['statut']=='Ouvert') { ?>
                  </form>
                <div class="row">
                  <button type="button" name="modal_close" class="btn btn-danger" data-toggle="modal" data-target="#<?php echo "transaction".$transaction['id_transaction'];?>" >Fermer</button>
                  <?php popup_close_one_transaction($transaction); if (isset($_POST['modal_close'])) {echo "pouet"; }?>
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#<?php echo "La_transaction".$transaction['id_transaction'];?>" >Modifier</button>
                  <?php modifiate_transaction_popup($transaction); ?>
                </div>
                <?php }
                  else{
                    echo "<p>".$transaction['message_cloture']."</p>";
                  } ?>
                </td>
              </tr>
          <?php } } ?>
        </tbody>
      </table>




<?php

if(isset($_GET['erreur'])){
  $err = $_GET['erreur'];
  if($err == 1){
  ?>  <div class="alert alert-danger" role = "alert">Vos identifiants sont incorrectes.</div> <?php
  }
  }

?>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>





</body>

  </html>
