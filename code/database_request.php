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

?>
