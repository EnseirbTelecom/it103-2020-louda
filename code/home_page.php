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


$CloseMessage = $_POST['message_text'];
$CloseDate = $_POST['today_date'];
$CloseStatue = $_POST['Close_reason'];
$myFriend = $_POST['my_friend'];

if (!empty($CloseDate) && !empty($CloseMessage)){
  $alltransactions = getTransactionWith($me['id_utilisateur'],$myFriend);
  foreach ($alltransactions as $transaction) {
    $t_id = $transaction['id_transaction'];
    $checked = $_POST["transaction_".$transaction['id_transaction']];
    if ($checked)
      CloseTransaction($t_id,$CloseDate,$CloseMessage,$CloseStatue);
    }
}

?>

<!DOCTYPE html>
<html lang ="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Louda.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Page d'accueil </title>
  </head>
<body>
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
          <h1 class="my-0 mr-md-auto font-weight-normal">Louda</h1>
          <div class="container">
              <div class="row ">
                  <div class= "col"> <a id="navbarBg" class="col-sm bg rounded text-center" href ='home_page.php'>Accueil</a></div>
                  <div class= "col"> <a id="navbar" href ='contact_page.php'> Carnet d'amis</a></div>
                  <div class= "col"> <a id="navbar" href ='create_transaction_page.php'> Nouvelle transaction</a></div>
                  <div class= "col"> <a id="navbar" href ='historique_page.php'> Mes transactions</a></div>
              </div>
          </div>
          <button id="signout" data-toggle="modal" data-target="#SignOut" href="déconnexion.php">
            <img id="signoutpng" src="deconnexion.png" alt="..." class="rounded">
          </button>
          <?php CreateSignoutPopup(); ?>
  </div>
  <div class="row">
    <div class="col">
      <div class="container-fluid">
          <h2>Dettes</h2>
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th>Prénom
                      <div class="col">
                        <input class="form-control" id="search" name="search" type="search" placeholder="Search" aria-label="Search"  value="<?php echo $_POST['search'];?>">
                      </div>
                </th>

                <th> Montant </th>
                <th> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php
              include("close_popup.php");
              $dette = 0;
              $allfriends = getAllFriends($me['id_utilisateur']);
              foreach ( $allfriends as $friend ) {
                $solde = BalanceCalculation($me['id_utilisateur'],$friend['id_utilisateur']);

                if ($solde < 0){
                 echo "<tr><td>"; ?> <a id="friendColor" href="historique_page.php?selection=<?php echo $friend['email'] ?>"><?php echo $friend['prenom']." ".$friend['nom']."</a></td>";
                 echo "<td>".$solde."€</td>";
                 echo "<td><button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#u_".$friend['id_utilisateur']."\">Fermer</button>";

                 createPopup($me,$friend);

                 echo "<button type=\"button\" class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#u_a_".$friend['id_utilisateur']."\">Tout fermer</button>";
                 createPopup($me,$friend,true);
                 echo "</td></tr>";
                 $dette += $solde;
                }
              }

              ?>
            </tbody>
          </table>
          </form>
    </div>
  </div>
  <div class="col">
    <div class="container-fluid">
        <h2>Créances</h2>
        <table class="table">
          <thead class="thead-light">
            <tr>
              <th>Prénom
                    <div class="col">
                      <input class="form-control" id="search" name="search" type="search" placeholder="Search" aria-label="Search"  value="<?php echo $_POST['search'];?>">
                    </div>
              </th>

              <th> Montant </th>
              <th> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php
            $credit = 0;
            foreach ( $allfriends as $friend ) {
              $solde = BalanceCalculation($me['id_utilisateur'],$friend['id_utilisateur']);

              if ($solde > 0){
               echo "<tr><td>"; ?> <a id="friendColor" href="historique_page.php?selection=<?php echo $friend['email'] ?>"><?php echo $friend['prenom']." ".$friend['nom']."</a></td>";
               echo "<td>".$solde."€</td>";
               echo "<td><button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#u_".$friend['id_utilisateur']."\">Fermer</button>";
               createPopup($me,$friend);
               echo "<button type=\"button\" class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#u_a_".$friend['id_utilisateur']."\">Tout fermer</button>";
               createPopup($me,$friend,true);
               echo "</td></tr>";
               $credit += $solde;
              }
            }

            ?>
          </tbody>
        </table>
        </form>
    </div>
  </div>
</div>
<div class="row">
  <div class="container-fluid">
    <h2>Total</h2>
    <table class="table">
      <thead class="thead-light">
        <tr>
          <th> Dettes </th>
          <th> Créances </th>
          <th> Total </th>
        </tr>
      </thead>
      <tbody>
        <?php
          $total = $dette +$credit;
          echo "<td id=\"red\">".$dette."€</td>";
          echo "<td id=\"green\" >".$credit."€</td>";
          if ($total < 0)
            echo "<td id=\"red\">".$total."€</td>";
          else
            echo "<td id=\"green\" >".$total."€</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>


<?php
if(isset($_GET['erreur'])){
  $err = $_GET['erreur'];
  if($err = 1){
  ?>  <div class="alert alert-danger" role = "alert">Vos identifiants sont incorrectes.</div> <?php
  }
  }

?>
      </div>
    </form>
  </div>
  </div>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>





</body>

  </html>
