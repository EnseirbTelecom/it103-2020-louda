<?php
session_start();
include("code/create_database.php");
include("code/database_request.php");

createDatabase();
$log = false;
$bdd = connectDatabase($log);

// Clean the table to avoid duplicate

$reset = "DROP TABLE `amitie`,`transaction`,`utilisateur` ";
executeRequest($bdd,$reset);

// Create all the tables

createTableUtilisateur();
createTableTransaction();
createTableAmitie();

// Create two users

$insert1 = "INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
VALUES ('1','tester@gmail.com', 'mdp', 'Prenom1','Nom1','pseudo1', '2000-01-01')";

$insert2 = "INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
VALUES ('2','utilisateur2@gmail.com', 'mdp', 'Prenom2','Nom2','pseudo2', '2000-01-01')";

$insert3 = "INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
VALUES ('3','thibaut.robinet@gmail.com', 'mdp', 'Thibaut','Robinet','Tibs', '1998-03-11')";

$insert4 = "INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
VALUES ('4','enowominski@gmail.com', 'mdp', 'Elie','Nowominski','Enowin', '1998-11-01')";

$insert5 = "INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `mot_de_passe` ,`prenom` ,`nom` ,`pseudo` ,`date_de_naissance`)
VALUES ('5','Imsaoubi@gmail.com', 'mdp', 'Imane','Msaoubi','Imoune', '1999-07-12')";

// Make them friend

$makefriend1 = "INSERT INTO amitie (id_utilisateur_1, id_utilisateur_2) VALUES ('1','2')";

$makefriend2 = "INSERT INTO amitie (id_utilisateur_1, id_utilisateur_2) VALUES ('1','3')";

$makefriend3 = "INSERT INTO amitie (id_utilisateur_1, id_utilisateur_2) VALUES ('1','4')";



// Create transactions

$transaction1 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`)
 VALUES ('Transaction 1','Ouvert','1','2','50','2020-05-15 00:00:00', 'la transaction1')";

$transaction2 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`)
VALUES ('Transaction 2','Ouvert','1','2','75','2020-04-15 00:00:00', 'la transaction2')";

$transaction3 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`)
 VALUES ('Transaction 3','Ouvert','2','1','135','2020-03-15 00:00:00', 'la transaction3')";

$transaction4 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`, `date_de_fermeture`, `message_cloture`,`message`)
VALUES ('Transaction 4','Remboursee','2','1','100','2020-02-15 00:00:00', '2020-06-15 00:00:00', 'transaction 4 remboursee', 'la transaction3')";

//illustrate a group transaction

$transaction5 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`montant_groupe`,`date_et_heure_de_creation`,`message`)
VALUES ('Transaction groupe','Ouvert','1','3','500','1000','2020-05-15 00:01:03', 'la transaction de groupe entre prenom1, Thibaut et Elie')";

$transaction6 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`montant_groupe`,`date_et_heure_de_creation`,`message`)
VALUES ('Transaction groupe','Ouvert','1','4','500','1000','2020-05-15 00:01:03', 'la transaction de groupe entre prenom1, Thibaut et Elie')";

// Query all the request

executeRequest($bdd,$insert1);

executeRequest($bdd,$insert2);

executeRequest($bdd,$insert3);

executeRequest($bdd,$insert4);

executeRequest($bdd,$insert5);

executeRequest($bdd,$makefriend1);

executeRequest($bdd,$makefriend2);

executeRequest($bdd,$makefriend3);

executeRequest($bdd,$transaction1);

executeRequest($bdd,$transaction2);

executeRequest($bdd,$transaction3);

executeRequest($bdd,$transaction4);

executeRequest($bdd,$transaction5);

executeRequest($bdd,$transaction6);

$bdd->close();

echo "install is successful </br>";
echo "WARNING : LAUNCHING THIS SCRIPT AGAIN WILL CLEAR THE DATABASE";

?>
