<?php

    function getTransactionWith($myId,$friendId,$log=false)
    {
      $bdd = connectDatabase($log);
      //SQL request to create user table
      $select = "SELECT * FROM transaction WHERE (
        (id_utilisateur_source='$myId' AND id_utilisateur_cible='$friendId')
        Or
        (id_utilisateur_source='$friendId' AND id_utilisateur_cible='$myId'))";


      $result = executeRequest($bdd,$select,$log);
      if ($log){
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "montant: " . $row["montant"]."<br>";
          }
        } else {
            echo "0 results";
        }
      }
      $bdd->close();
      return $result;
    }

    function getAllFriends($myId,$log=false){
      //return an array of all friends of the current user
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM `amitie` WHERE (id_utilisateur1='$myId' OR id_utilisateur2='$myId')";
      $result = executeRequest($bdd,$select,$log);
      $ind = 0;
      if ($result->num_rows > 0) {
          $Friends = array();
          while($row = $result->fetch_assoc()) {
            $id = $row["id_utilisateur1"] +$row["id_utilisateur2"];
            $id -= $myId;
            $ami = getUtilisateurWithId($id);
            $ami["id_amitie"] =$row['id_amitie'];
            $Friends += array($ind => $ami);
            if ($log){
              echo "id: " . $row["id_amitié"]. " - users: " . $row["id_utilisateur1"]. " : " . $row["id_utilisateur2"]. "<br>";
            }
            $ind++;
          }
      }
      $bdd->close();
      return $Friends;
    }

    function getUtilisateurWithEmail($email,$log=false){
      //return an user by seraching itself with is email
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM utilisateur WHERE email='$email'";
      $result = executeRequest($bdd,$select,$log);
      if ($log){
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "id: " . $row["id_utilisateur"]. " - Name: " . $row["nom"]. " " . $row["prenom"]. "<br>";
              return $row;
          }
        } else {
            echo "0 results";
        }
      }
      $bdd->close();
      return mysqli_fetch_assoc($result);
    }

    function getUtilisateurWithId($Id,$log=false){
      //return an user by seraching itself with is id
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM utilisateur WHERE id_utilisateur='$Id'";
      $result = executeRequest($bdd,$select,$log);
      if ($log){
        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "id: " . $row["id_utilisateur"]. " - Name: " . $row["nom"]. " " . $row["prenom"]. "<br>";
              return $row;
          }
        } else {
            echo "0 results";
        }
      }
      $bdd->close();
      return mysqli_fetch_assoc($result);
    }


    function getAllUsers($myId,$log=false){
      //return an array of all users who are different not current user
      $bdd = connectDatabase($log);
      $all = "SELECT * FROM utilisateur WHERE id_utilisateur!='$myId'";
      $result = executeRequest($bdd,$all,$log);
      $ind = 0;
      if ($result->num_rows > 0) {
          // output data of each row
          $Users = array();
          while($row = $result->fetch_assoc()) {
            $Users += array($ind => $row);
            if ($log){
              echo " - users: " . $row["prenom"]. " : " . $row["nom"]. "<br>";
            }
            $ind++;
          }
      }
      $bdd->close();
      return $Users;
    }
?>
