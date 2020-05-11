<?php

    function Update_UserSelection($research,$id)
    {
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM `utilisateur` WHERE ((id_utilisateur='$id') AND ((`prenom` LIKE '{$research}')  OR (`nom` LIKE '{$research}') OR (`pseudo` LIKE '{$research}'))) ";
      $res = executeRequest($bdd, $select);
      $rows = array();
      while($row = $res->fetch_assoc()) {
        $ind = sizeof($rows);
        $rows += array($ind => $row);
      }
      return $rows;
    }

    function GetMyFriendId($friendshipId,$MyId)
    {
      $log = false;
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM `amitie` WHERE (id_amitie='$friendshipId')";
      $res = executeRequest($bdd, $select);
      $rows = array();
      while($row = $res->fetch_assoc()) {
        $ind = sizeof($rows);
        $rows += array($ind => $row);
      }
      foreach ($rows as $id){
        if ($id['id_utilisateur_1']==$MyId){
          return $id['id_utilisateur_2'];
        }
        elseif ($id['id_utilisateur_2']==$MyId){
          return $id['id_utilisateur_1'];
        }
      }      
    }

    function BalanceCalculation($MyId,$friendId)
    {
      $log = false;
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM `transaction` WHERE ((id_utilisateur_source='$MyId' AND id_utilisateur_cible='$friendId') OR (id_utilisateur_cible='$MyId' AND id_utilisateur_source='$friendId'))";
      $res = executeRequest($bdd, $select);
      $rows = array();
      while($row = $res->fetch_assoc()) {
        $ind = sizeof($rows);
        $rows += array($ind => $row);
      }
      $balance = 0;
      foreach ($rows as $TransacBalance){
        $var = Test_my_id($MyId,$TransacBalance['id_utilisateur_source'],$TransacBalance['id_utilisateur_cible']);
        if ($var==1){
          $balance = $balance - $TransacBalance['montant'];
        }
        elseif($var==0){
          $balance = $balance + $TransacBalance['montant'];
        }
      }
      return $balance;
    }

    function SelectUser($id,$log=false)
    {
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM `utilisateur` WHERE (id_utilisateur='$id')";
      $res = executeRequest($bdd, $select);
      $rows = array();
      while($row = $res->fetch_assoc()) {
        $ind = sizeof($rows);
        $rows += array($ind => $row);
      }
      return $rows;
    }
    
    // La fonction fonctionne mais je n'arrive pas à l'utiliser comme je souhaite
    function Select_correct_user($research,$id_source,$id_cible,$var)
    {
      if ($research!=NULL){
        if ($var==0){
          $select = Update_UserSelection($research,$id_cible);
        }
        else{
          $select = Update_UserSelection($research,$id_source);
        }
      }
      elseif ($research==NULL) {
        if ($var==0){
          $select = SelectUser($id_cible);
        }
        else{
          $select = SelectUser($id_source);
        }
      }
      return $select;
    }

    function Test_my_id($MyId,$id_source,$id_cible)
    {
      if ($MyId==$id_cible){
        $var=1;
      }
      elseif($MyId==$id_source){
        $var=0;
      }
      return $var;
    }

    function CloseTransaction($id_transaction,$Close_date,$Close_message,$Close_statue)
    {
      $log = false;
      $bdd = connectDatabase($log);
      $request = "UPDATE `transaction` SET date_de_fermeture='$Close_date', message_cloture='$Close_message',statut='$Close_statue' WHERE `transaction`.`id_transaction` = $id_transaction";
      $result = executeRequest($bdd,$request,$log);
      $bdd->close();
    }

    function ModifTransaction($id_transaction,$NewMessage,$NewAmount)
    {
      $log=false;
      $bdd = connectDatabase($log);
      $request = "UPDATE `transaction` SET montant='$NewAmount', message='$NewMessage' WHERE `transaction`.`id_transaction` = $id_transaction";
      $result = executeRequest($bdd,$request,$log);
      $bdd->close();
    }

    function getMyTransactions($myId,$order,$statue,$Debt_Receivables, $log=false)
    {
      $bdd = connectDatabase($log);
      //SQL request to create user table
      if ($order == 1){
        $asc="asc";
      }
      elseif ($order == 2){
        $asc="desc";
      }
      if ($Debt_Receivables == 1){
        $choose = "(id_utilisateur_source='$myId')
        Or
          (id_utilisateur_cible='$myId'))";
      }
      elseif ($Debt_Receivables == 2){
        $choose = "(id_utilisateur_cible='$myId'))";
      }
      elseif ($Debt_Receivables == 3){
        $choose = "(id_utilisateur_source='$myId'))";
      }
      switch ($statue){
        case 4 :
          $select = "SELECT * FROM transaction WHERE (
          $choose
          order by date_et_heure_de_creation $asc";
        break;
        case 1:
          $select = "SELECT * FROM transaction WHERE ((
          $choose
          AND
            (statut='Ouvert'))
          order by date_et_heure_de_creation $asc";
        break;
        case 3:
          $select = "SELECT * FROM transaction WHERE ((
            $choose
            AND
              (statut='Annulee'))
            order by date_et_heure_de_creation $asc";
        break;
        case 2:
          $select = "SELECT * FROM transaction WHERE ((
          $choose
          AND
            (statut='Remboursee'))
          order by date_et_heure_de_creation $asc";
        break;
      }
      $result = executeRequest($bdd,$select,$log);
      $rows = array();
      while($row = $result->fetch_assoc()) {
        $ind = sizeof($rows);
        $rows += array($ind => $row);

        if ($log){
          echo "montant: " . $row["montant"]."<br>";
        }
      }
      $bdd->close();
      return $rows;
    }


    function getTransactionWith($myId,$friendId,$log=false)
    {
      $bdd = connectDatabase($log);
      //SQL request to create user table
      $select = "SELECT * FROM transaction WHERE (
        (id_utilisateur_source='$myId' AND id_utilisateur_cible='$friendId')
        Or
        (id_utilisateur_source='$friendId' AND id_utilisateur_cible='$myId'))";

      $result = executeRequest($bdd,$select,$log);
      $rows = array();
      while($row = $result->fetch_assoc()) {
        $ind = sizeof($rows);
        $rows += array($ind => $row);

        if ($log){
          echo "montant: " . $row["montant"]."<br>";
        }
      }
      $bdd->close();
      return $rows;
      }


    function getAllFriends($myId,$log=false){
      //return an array of all friends of the current user
      $bdd = connectDatabase($log);
      $select = "SELECT * FROM `amitie` WHERE (id_utilisateur_1='$myId' OR id_utilisateur_2='$myId')";
      $result = executeRequest($bdd,$select,$log);
      $ind = 0;
      if ($result->num_rows > 0) {
          $Friends = array();
          while($row = $result->fetch_assoc()) {
            $id = $row["id_utilisateur_1"] +$row["id_utilisateur_2"];
            $id -= $myId;
            $ami = getUtilisateurWithId($id);
            $ami["id_amitie"] =$row['id_amitie'];
            $Friends += array($ind => $ami);
            if ($log){
              echo "id: " . $row["id_amitié"]. " - users: " . $row["id_utilisateur_1"]. " : " . $row["id_utilisateur_2"]. "<br>";
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
      //return an user by seraching itself with his id
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
