<?php
session_start();
if (empty($_SESSION)){
    header("location: connexion_page.php");
}
include("database_request.php");
include("create_database.php");

$me = getUtilisateurWithEmail($_SESSION["email"]);
if (!empty($_POST['search'])) { 
  $s = $_POST['search']; 
}
else {
  $s = NULL;
}

$search="";

if (isset($_GET['selection'])){
  if ($_GET['selection']=='search'){
    $search="active";
  }
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
    <link rel="stylesheet" href="historique.css">
  </head>
<body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
        <h1 class="my-0 mr-md-auto font-weight-normal">Titre du site</h1>
        <div class="container">
            <div class="row ">
                <div class= "col"> <a href ='home_page.php'> Home</a></div>
                <div class= "col"> <a href ='contact_page.php'> Carnet d'amis</a></div>
                <div class= "col"> <a href ='create_transaction_page.php'> Nouvelle transaction</a></div>
                <div class= "col"> <a class="col-sm bg-primary text-white rounded text-center" href ='historique_page.php'> Historique</a></div>
            </div>
        </div> 
        <a class="btn btn-outline-primary" id="signin" href="déconnexion_page.php">Déconnexion</a>
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
            ?>
              <tr <?php if ($transaction['statut']!='Ouvert') { ?> class="table-secondary" <?php }?> >
                <td><?php echo $user['prenom']." ".$user['nom']; ?></td>
                <td><?php echo $transaction['nom_de_la_transaction'];?></td>
                <td <?php if ($var==1){echo 'style="color:red;"';?>><?php echo "- ".$transaction['montant']; ?><?php } elseif ($var==0) {echo 'style="color:green;"';?>><?php echo "+ ".$transaction['montant'];} ?></td>
                <td><?php echo $transaction['message']; ?></td>
                <td><?php echo $transaction['date_et_heure_de_creation']; ?></td>
                <td><?php echo $transaction['date_de_fermeture']; ?></td>
                <td><?php echo $transaction['statut']; ?></td>
                <td><?php if ($transaction['statut']=='Ouvert') { ?>
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#<?php echo "transaction".$transaction['id_transaction'];?>" >Fermer</button>
                  <div class="modal fade" id="<?php echo "transaction".$transaction['id_transaction'];?>" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="Close_title">Fermer la transaction : <?php echo $transaction['nom_de_la_transaction'];?> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                          <form class="form-horizontal" action='historique_page.php' method="POST">
                              <div class="form-group row offset-md-1">
                                  <label for="message_text" class="col-md-3 form-control-label">Message de fermeture :</label>
                                  <div class="col-sm-10">
                                      <textarea class="form-control" id="message_text" name="message_text"></textarea>
                                  </div>
                              </div>
                              <div class="form-group row offset-md-1">
                                  <label for="today_date" class="col-form-label col-md-3">Date de fermeture :</label>
                                  <div class="col-sm-10">
                                      <input type="date" id="today_date" name="today_date"  class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                  </div>
                              </div>
                              <fieldset class="form-group row offset-md-1">
                                  <div class="row">
                                  <legend class="col-form-label col-md-3 pt-0">Statut de fermeture</legend>
                                  <div class="col-sm-10">
                                      <div class="form-check">
                                      <input class="form-check-input" type="radio" name="Close_reason" id="Close_reason1" value="Remboursee" checked>
                                      <label class="form-check-label" for="Close_reason1">
                                          Remboursement
                                      </label>
                                      </div>
                                      <div class="form-check">
                                          <input class="form-check-input" type="radio" name="Close_reason" id="Close_reason2" value="Annulee">
                                          <label class="form-check-label" for="Close_reason2">
                                          Annulation
                                          </label>
                                      </div>
                                  </div>
                                  </div>
                              </fieldset>
                        </div>
                        <div class="modal-footer">
                          <input type="hidden" value="<?php echo $transaction['id_transaction']?>" name="id_transaction">
                          <input type="submit" class="btn btn-secondary" value="Fermer la transaction">
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#<?php echo "La_transaction".$transaction['id_transaction'];?>" >Modifier</button>
                  <div class="modal fade" id="<?php echo "La_transaction".$transaction['id_transaction'];?>" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="Modif_title">Modifier la transaction : <?php echo $transaction['nom_de_la_transaction'];?> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                          <form class="form-horizontal" action='historique_page.php' method="POST">
                              <div class="form-group row offset-md-1">
                                  <label for="message_text" class="col-md-3 form-control-label">Nouveau message  :</label>
                                  <div class="col-sm-10">
                                      <textarea class="form-control" id="message_text" name="new_message_text" ><?php echo $transaction['message'];?></textarea>
                                  </div>
                              </div>
                              <div class="form-group row offset-md-1">
                                  <label for="new_amount" class="col-form-label col-md-3">Nouveau montant de la transaction  :</label>
                                  <div class="col-sm-10">
                                      <input type="number" step ="0.01"  min="0" id="new_amount" name="new_amount"  class="form-control">
                                  </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                          <input type="hidden" value="<?php echo $transaction['id_transaction']?>" name="id_transaction">
                          <input type="submit" class="btn btn-secondary" value="Modifier la transaction">
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <?php } ?> </td>
              </tr>
          <?php } } ?>
        </tbody>
      </table>
      </form>
 




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
