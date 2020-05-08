<?php
session_start();
if (empty($_SESSION)){
    header("location: connexion_page.php");
}
<<<<<<< HEAD
include("database_request.php");
include("create_database.php");

$me = getUtilisateurWithEmail($_SESSION["email"]);
$s = $_POST['search'];
$search="";

if (isset($_GET['selection'])){
  if ($_GET['selection']=='search'){
    $search="active";
  }
}

$DateSelected = isset( $_GET['Open_date'] ) ? $_GET['Open_date'] : "" ;
$StatueSelected = isset( $_GET['statue'] ) ? $_GET['statue'] : "" ;
$selectedValue = "selected";

$CloseMessage = $_POST['message_text'];
$CloseDate = $_POST['today_date'];
$id = $_POST['id_transaction'];
if (!empty($CloseDate) && !empty($CloseMessage)){
  CloseTransaction($id,$CloseDate,$CloseMessage);
=======
else{
  if(time()-$_SESSION['last_time'] >3600 ){
    header("location:déconnexion.php");
  }
  else{
    $_SESSION['last_time']=time();
  }
>>>>>>> 51641d66b3278f13ca9948e3a0df297c7c3fff10
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
              <div class="form-inline">
                  <div class="col">
                    <input class="form-control mr-sm-2" id="search" name="search" type="search" placeholder="Search" aria-label="Search"  value="<?php echo $_POST['search'];?>">
                  </div>
                  <div class="col">
                    <input type="submit" value="Search">
                  </div>
              </div>
            </th>
              <th>Dettes ou créances
                <div class="mr-sm-2 dropdown">
                  <select class="browser-default custom-select">
                    <option value="1">Dettes et créances</option>
                    <option value="2">Dettes</option>
                    <option value="3">Créances</option> 
                  </select>
                </div>
              </th>
              <th> Message </th>
              <th>Date d'ouverture
                <div class="mr-sm-2 dropdown">
                  <select name="Open_date" class="browser-default custom-select">
                    <option value="1" <?php if( $DateSelected == "1") echo $selectedValue;?>>Croissante</option>
                    <option value="2" <?php if( $DateSelected == "2") echo $selectedValue; ?>>Décroissante</option>
                  </select>
                </div>
              </th>
              <th>Date de fermeture
              </th>
              <th>Statut de la transaction
                <div class="mr-sm-8 dropdown">
                  <select name="statue" class="browser-default custom-select">
                    <option value="1" <?php if( $StatueSelected == "1") echo $selectedValue;?>>Ouverte</option>
                    <option value="2"<?php if( $StatueSelected == "2") echo $selectedValue; ?>>Fermée</option>
                    <option value="3" selected <?php if( $StatueSelected == "3") echo $selectedValue; ?>>Toute</option> 
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
              if(isset($_POST['Open_date']) && isset($_POST['statue'])) {
                $open_date = ceil($_POST['Open_date']);
                $statue = ceil($_POST['statue']);
              } 

              $alltransaction = getMyTransactions($me['id_utilisateur'],$open_date,$statue);
              foreach ( $alltransaction as $transaction ) {
                if (empty($s)){
                  $rows = SelectUser($transaction['id_utilisateur_cible']);
                }
                else {
                  $rows = Update_UserSelection($s,$transaction['id_utilisateur_cible']);
                }
                foreach ( $rows as $user) {
            ?>
              <tr <?php if ($transaction['statut']=='Fermee') { ?> class="table-secondary" <?php }?> >
                <td><?php echo $user['prenom']." ".$user['nom']; ?></td>
                <td><?php echo $transaction['montant']; ?></td>
                <td><?php echo $transaction['message']; ?></td>
                <td><?php echo $transaction['date_et_heure_de_creation']; ?></td>
                <td><?php echo $transaction['date_de_fermeture']; ?></td>
                <td><?php echo $transaction['statut']; ?></td>
                <td><?php if ($transaction['statut']=='Ouverte') { ?>
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#<?php echo "transaction".$transaction['id_transaction'];?>" data-whatever="@getbootstrap">Fermer</button>
                  <div class="modal fade" id="<?php echo "transaction".$transaction['id_transaction'];?>" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Fermer la transaction : <?php echo $transaction['message'];?> </h5>
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
                                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
                                      <label class="form-check-label" for="gridRadios1">
                                          Remboursement
                                      </label>
                                      </div>
                                      <div class="form-check">
                                          <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                                          <label class="form-check-label" for="gridRadios2">
                                              Annulation
                                          </label>
                                      </div>
                                  </div>
                                  </div>
                              </fieldset>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <input type="hidden" value="<?php echo $transaction['id_transaction']?>" name="id_transaction">
                          <input type="submit" value="Fermer">
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <a href="#" class="btn btn-info active" role="button" aria-pressed="true">Modifier</a>
                  <?php } else {
                  ?><a href="#" class="btn btn-secondary" role="button" disabled>Fermer</a><?php } ?> </td>
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
