<?php
function CreateSignoutPopup(){
    ?>
    <div class="modal fade" id="SignOut" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Déconnexion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action='déconnexion.php' method="POST">
                    Voulez-vous vous déconnecter ?
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button submit" class="button">Oui</button>
                        <button type="button" data-dismiss="modal" class="button">Non</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>