<?php
  //if $log does not exist, it the same as if $log = false
  if (isset($_GET['log'])){
    $log = (bool) boolval($_GET['log']);
  }

  //connection to database louda
  $bdd = new mysqli("localhost","admin","it103","louda");
  
  if ($log){
    //to sent a message of the sate of the connection
    if ($bdd->connect_error) {
    		die("Connection failed: " . $bdd->connect_error);
    	} else {echo "Connection successful <br />";}
  }
  //SQL request to create user table
  $create = "CREATE TABLE utilisateur(
    id_utilisateur INT(11) AUTO_INCREMENT PRIMARY KEY,
    email TEXT NOT NULL,
    mot_de_passe TEXT NOT NULL,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    pseudo TEXT NOT NULL,
    date_de_naissance DATE NOT NULL)";

    //try to create user table
    if (mysqli_query($bdd,$create) ){
        //the user table as been created
    		if ($log){echo "User table successfully registered <br />";}
    	} else {
        //Echec triing to create user table
        //printing error
    		if ($log){echo "ERROR : " . $request . " " . mysqli_error($bdd) . "<br />";}
    	}

   //closing database
   $bdd->close();
?>
