<?php

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
                        <textarea class="form-control" id="message_text" name="message_text"></textarea>
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
