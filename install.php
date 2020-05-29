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

// Make them friend

$makefriend = "INSERT INTO amitie (id_utilisateur_1, id_utilisateur_2) VALUES ('1','2')";

// Create transactions

$transaction1 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`)
 VALUES ('Transaction 1','Ouvert','1','2','50','2020-05-15 00:00:00', 'la transaction1')";

$transaction2 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`)
VALUES ('Transaction 2','Ouvert','1','2','75','2020-04-15 00:00:00', 'la transaction2')";

$transaction3 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`,`message`)
 VALUES ('Transaction 3','Ouvert','2','1','135','2020-03-15 00:00:00', 'la transaction3')";

$transaction4 = "INSERT INTO `transaction` (`nom_de_la_transaction`,`statut`,`id_utilisateur_source`,`id_utilisateur_cible`,`montant`,`date_et_heure_de_creation`, `date_de_fermeture`, `message_cloture`,`message`)
VALUES ('Transaction 4','Remboursee','2','1','100','2020-02-15 00:00:00', '2020-06-15 00:00:00', 'transaction 4 remboursee', 'la transaction3')";

// Query all the request

$result0 = executeRequest($bdd,$makefriend);

$result1 = executeRequest($bdd,$insert1);

$result2 = executeRequest($bdd,$insert2);

$result3 = executeRequest($bdd,$transaction1);

$result4 = executeRequest($bdd,$transaction2);

$result5 = executeRequest($bdd,$transaction3);

$result6 = executeRequest($bdd,$transaction4);

$bdd->close();

echo "install is successful </br>";
echo "WARNING : LAUNCHING THIS SCRIPT AGAIN WILL CLEAR THE DATABASE";

?>
