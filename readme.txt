
Dans le but de respecter les consignes de déploiement, voici le fichier read me.

Notre projet ce constitue de cette manière:
>racine
  >readme.txt
  >install.php
  >install_2.php
  >code
    >close_popup.php
    >connexion.php
    >connexion_page.php
    >contact_page.php
    >create_database.php
    >create_group_transaction.php
    >create_transaction_page.php
    >database_request.php
    >déconnexion.png
    >déconnexion.php
    >historique_page.php
    >home_page.php
    >inscription.php
    >Louda.css
    >signout_popup.php
    >trans_g_ne.php

################################################################
MISE EN PLACE DE LA BASE DE DONNÉES

  1) exécuter le script install.php
    * il crée les tables dans la base de données et ajoutes certaines données

  2) Nous conseillons de lancer le script install_2.php à la place (ou après) le script install.php
    * En effet nous avons remarqué que l'ajout des données en respectant la consignes ne permet pas de tester toutes les fonctionnalitées
    * Dans le but de facilité les tests nous avons crée un second script qui ajoute plus de DONNÉES

#############################################################
COMPOSITION DES FICHIERS

#close_popup.php : contient une fonction qui permet d'afficher la popup qui permet de fermer des transactions

#connexion.php : contient le code qui permet la connexion
                WARNING : Ne doit pas être exécuter, utiliser connexion_page.php à la place

#connexion_page : contient le code qui affiche la page de connexion
                  INFO : utilise le code connexion.php

#contact_page.php : contient le code qui affiche le carnet d'amis et qui permet d'ajouter, rechercher ou supprimer un ami

#create_database.php : contient des fonctions qui permettent de créer la base de données, d'y ajouter les tables ou de faire des requêtes

#create_group_transaction.php : contient le code qui affiche et gère l'ajout de nouvelle transaction d'un groupe d'amis en choisissant le mode de répartition

#create_transaction_page.php : contient le code qui affiche la page qui permet d'ajouter une nouvelle transaction avec un ami

#database_request.php : contient diverse fonction qui exécute des requêtes à la base de données pour l'ensemble des pages web

#déconnexion.png : contient le code qui permet la déconnexion

#historique_page.php : contient le code qui affiche l'historique des transactions, permet de triées les éléments selon divers critères

#home_page.php : contient le code qui permet l'affichage de la page d'accueil du site
                * on y retrouve les tableaux séparés de dettes et de créances en cours
                * on y trouve également le montant total des encours

#inscription.php : contient le code qui affiche la page de formulaire d'inscription

#Louda.css : Contient des class css qui sont utilisées à divers endroit du projet

#signout_popup.php :contient une fonction qui permet d'afficher la popup qui demande une confirmation avant de se déconnecter

#trans_g_ne.php : contient le code qui affiche une page intermédiaire lors de la création d'une transaction de groupe pour une répartition manuelle uniquement
                  (page permettant la répartition manuelle d'une transaction de groupe)
