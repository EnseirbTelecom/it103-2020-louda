<?php
function popup_close_one_transaction($transaction){

  ?>
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
          <form class="form-horizontal" id="form_modal" action='historique_page.php' method="POST">
          <div class="form-group row offset-md-1">
            <label for="message_text" class="col-md-3 form-control-label">Message de fermeture :</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="message_text" name="message_text" required></textarea>
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
          <input type="submit" class="btn btn-success" value="Fermer la transaction">
        </div>
        </form>
      </div>
    </div>
  </div>

  <?php
}

function modifiate_transaction_popup($transaction){
  ?>
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
                      <textarea class="form-control" id="message_text" name="new_message_text" required ><?php echo $transaction['message'];?></textarea>
                  </div>
              </div>
              <div class="form-group row offset-md-1">
                  <label for="new_amount" class="col-form-label col-md-3">Nouveau montant de la transaction  :</label>
                  <div class="col-sm-10">
                      <input type="number" step ="0.01"  min="0" id="new_amount" name="new_amount"  class="form-control" value="<?php echo $transaction['montant'];?>" required>
                  </div>
              </div>
          </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" value="<?php echo $transaction['id_transaction']?>" name="id_transaction">
            <input type="submit" class="btn btn-success" value="Modifier la transaction">
          </div>
      </div>
    </div>
  </div> 
<?php 
}

function createPopup($me,$friend,$allSelected=false){
  $friendId = $friend['id_utilisateur'];
  $alltransactions = getTransactionWith($me['id_utilisateur'],$friendId);

  ?>
  <div class="modal fade" id="u_<?php if ($allSelected){echo "a_";}
  echo $friendId;?>" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="Close_title">Ami : <?php echo $friend['pseudo'];?> </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <div class="modal-body">
            <div class="row"><p class="col-sm-10">Message de fermeture :</p></div>
            <form class="form-horizontal" action='home_page.php' method="POST">
              <input type="hidden" value="<?php echo $friendId ?>" name="my_friend">

                <div class="container-fluid">
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th> nom de la transaction</th>
                      <th> Montant </th>
                      <th> Selection </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ( $alltransactions as $transaction ) {
                     echo "<tr><td>".$transaction['nom_de_la_transaction']."</td>";
                     if ($transaction['id_utilisateur_cible'] == $me['id_utilisateur']){
                        echo "<td> - ".$transaction['montant']."</td>";
                     }
                     else{
                       echo "<td> ".$transaction['montant']."</td>";
                     }
                     echo "<td><input type=\"checkbox\" name=\"transaction_".$transaction['id_transaction']."\"";
                     if ($allSelected){
                      echo "checked>";
                    }
                    else{
                      echo "unchecked>";
                    }
                    echo "</td></tr>";
                   }?>
                  </tbody>
                </table>
              </div>
                <div class="form-group row offset-md-1">

                    <div class="col-sm-10">
                        <textarea class="form-control" id="message_text" name="message_text" required></textarea>
                    </div>
                </div>
                <div class="row"><p class="col-sm-10">Date de fermeture :</p></div>
                <div class="form-group row offset-md-1">
                    <div class="col-sm-10">
                        <input type="date" id="today_date" name="today_date"  class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="row"><p class="col-sm-10">Statut de fermeture :</p></div>
                <fieldset class="form-group row offset-md-1">
                    <div class="row">
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
              <input type="hidden" value="<?php echo $friendId?>" name="id_transaction">
              <input type="submit" class="btn btn-success" value="Fermer la transaction">
            </div>
            </form>
      </div>
    </div>
  </div>
<?php } ?>
