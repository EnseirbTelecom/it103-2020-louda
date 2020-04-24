<?php
    session_start();
    include("create_database.php");
    createDatabase();
    include("create_utilisateur.php");
    createTableUtilisateur();
    createTableTransaction();

    $bdd =  mysqli_connect("localhost","admin","it103","louda");
                if ($bdd->connect_error) {
                    die("Connexion failed: " . $bdd->connect_error);
                } else {
                    //echo "Connexion successful <br />";
                }


    if (isset($_POST['email'], $_POST['fname'], $_POST['lname'],$_POST['birth'],$_POST['pseudo'], $_POST['psw'],$_POST['confpsw'])){

        // Initialisation of error variables
        $testpseudo = 0;
        $testemail = 0;
        $testpsw = 0;
        $error = true;

        // Change date format
        $Date = $_POST['birth'];
        $newDate = implode('-', array_reverse (explode('/',$Date)));

        // Check if Pseudo and email are already taken
        $req = "SELECT * FROM `utilisateur` WHERE email ='".$_POST['email']."'";
        $req2 = "SELECT * FROM `utilisateur` WHERE pseudo ='".$_POST['pseudo']."'";
        $email = mysqli_query($bdd,$req);
        $pseudo = mysqli_query($bdd,$req2);

        // Check if every field is correct and answered

        if(empty($_POST['fname'])){
            $error = false;
        }
        // Nom
        if(empty($_POST['lname'])){
            $error = false;
        }
        // Email
        if (empty($_POST['email'])){
            $error = false;
        }
        // Date de naissance
        if (empty($_POST['birth'])){
            $error = false;
        }
        // Pseudo is not required

        // Mot de passe
        if(empty($_POST['psw'])){
            $error = false;
        }
        // Confirmation de mot de passe
        if(empty( $_POST['confpsw'])){
            $error = false;
        }
        if($_POST['confpsw'] != $_POST['psw']){
            $error = false;
            $testpsw = 1;
        }
        if ($error == true ){
            if (mysqli_num_rows($email) != 1) {
                if (mysqli_num_rows($pseudo) != 1){
                    /* Permet d'avoir les logs
                    /*$request = ("INSERT INTO `utilisateur` (`email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
                        VALUES ( '". $_POST['email'] ."' ,'". $_POST['psw'] ."' ,'". $_POST['fname'] ."','". $_POST['lname'] ."' ,'". $_POST['pseudo'] ."' , '". $newDate ."')");*/
                    mysqli_query($bdd,"INSERT INTO `utilisateur` (`email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
                        VALUES ( '". $_POST['email'] ."' ,'". $_POST['psw'] ."' ,'". $_POST['fname'] ."','". $_POST['lname'] ."' ,'". $_POST['pseudo'] ."' , '" . $newDate . "' )");
                    //echo "$request <br />";
                }
                else {
                    $testpseudo = 1;
                }
            }
            else {
                $testemail = 1;
            }

        }
    }
		//try to query SQL request
		/*if (mysqli_query($bdd,$request)){
			echo "NEW user added <br />";
		} else {
			echo "ERROR : " . $request . " " . mysqli_error($bdd) . "<br />";
        }*/
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .alert{
            height:70%;
        }
    </style>
    <title>Notre Tricount</title>
    </head>
    <body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white">
        <h1 class="my-0 mr-md-auto font-weight-normal">Titre du site</h1>
        <!-- A remplacer par un boutton clickable qui renvoie vers la page de connexion-->
        <a class="btn btn-outline-primary" id="signin" href="#">Connexion</a>
    </div>
    <div class="container">
        <div class="header offset-md-3">
                <h1>Formulaire d'inscription</h1>
        </div>
        <br>
        <br>
        <form class="form-horizontal" action='inscription.php' method="POST">
            <!-- Prénom -->
            <div class="form-group row">
                <label for="fname" class="col-sm-2 col-form-label offset-md-3">Prénom</label>
                <div class="col-sm-2">
                    <input type="text"  id="fname" name="fname" placeholder="Prénom" class="form-control" required>
                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Prénom manquant</div>
                </div>
            </div>
            <br />
            <!-- Nom -->
            <div class="form-group row ">
                <label for="lname" class="col-sm-2 col-form-label offset-md-3">Nom</label>
                <div class="col-sm-2">
                    <input type="text"  id="lname" name="lname" placeholder="Nom" class="form-control" required>
                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Nom manquant</div>
                </div>
            </div>
            <br />
            <!-- Email-->
            <div class="form-group row ">
                <label for="email" class="col-sm-2 col-form-label offset-md-3">Email</label>
                <div class="col-sm-2 ">
                    <input type="email"  id="email" name="email" placeholder="Email" class="form-control" required>
                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Email manquant</div>
                </div>
                <div class="col-auto ">
                    <!--<?php if ($testemail == 1) { ?>-->
                        <div class="alert alert-danger" role = "alert" > Adresse mail déja utilisée </div>
                    <!--<?php } ?>-->
                </div>
            </div>
            <br />
            <!-- Date de naissance -->
            <div class="form-group row ">
                <label for="birth" class="col-sm-2 col-form-label offset-md-3">Date de naissance</label>
                <div class="col-sm-2">
                    <input type="text"  id="birth" name="birth" placeholder="ex : 01/01/2001" class="form-control" required>
                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Date de naissance manquante</div>
                </div>
            </div>
            <br />
            <!-- Pseudo-->
            <div class="form-group row ">
                <label for="pseudo" class="col-sm-2 col-form-label offset-md-3">Pseudo</label>
                <div class="col-sm-2">
                    <input type="text"  id="pseudo" name="pseudo" placeholder="Pseudo" class="form-control">
                </div>
                <div class="col-auto ">
                    <!--<?php if ($testpseudo == 1) { ?>-->
                        <div class="alert alert-danger" role = "alert"> Pseudo déja utilisé </div>
                    <!--<?php } ?>-->
                </div>
            </div>
            <br />
            <!-- Mot de passe -->
            <div class="form-group row ">
                <label for="psw" class="col-sm-2 col-form-label offset-md-3">Mot de passe</label>
                <div class="col-sm-2">
                    <input type="password"  id="psw" name="psw" placeholder="" class="form-control" required>
                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Mot de passe manquant</div>
                </div>
            </div>
            <br />
            <!-- Confirmation de mot de passe -->
            <div class="form-group row ">
                <label for="confpsw" class="col-sm-2 col-form-label offset-md-3">Confirmation de mot de passe</label>
                <div class="col-sm-2">
                    <input type="password"  id="confpsw" name="confpsw" placeholder="" class="form-control" required>
                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Confirmation de mot de passe manquant</div>
                </div>
                <div class="col-auto">
                    <!--<?php if ($testpsw == 1) { ?>-->
                        <div class="alert alert-danger" role = "alert"> Mauvaise confirmation de mot de passe </div>
                    <!--<?php } ?>-->
                </div>
            </div>
            <br />
            <!-- Submit -->
            <div class="form-group row justify-content-center">
                <div class="col-2 ">
                    <input type="submit" value="S'enregistrer">
                </div>
            </div>
        </form>
    </div>
    </body>
