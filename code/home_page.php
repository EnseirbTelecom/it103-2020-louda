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
?>

<!DOCTYPE html>
<html lang ="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Page d'accueil </title>
  </head>
<body>
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
          <h1 class="my-0 mr-md-auto font-weight-normal">Titre du site</h1>
          <div class="container">
              <div class="row ">
                  <div class= "col"> <a class="col-sm bg-primary text-white rounded text-center" href ='home_page.php'> Home</a></div>
                  <div class= "col"> <a href ='contact_page.php'> Carnet d'amis</a></div>
                  <div class= "col"> <a href ='create_transaction_page.php'> Nouvelle transaction</a></div>
                  <div class= "col"> <a href ='historique_page.php'> Historique</a></div>
              </div>
          </div> 
          <a class="btn btn-outline-primary" id="signin" href="déconnexion.php">Déconnexion</a>
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
