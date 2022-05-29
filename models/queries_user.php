<?php


// XXX -- list all users
function listUsers() {
  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM user
    ORDER BY nameUser ASC
  ";

  $stmt = $pdo->prepare($query);    // $stmt est un objet PDOStatement
  $stmt->execute();                 // returne TRUE en cas de succès, FALSE si une erreur survient

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// XXX -- list users for betwatcher
function listUsersForBetwatcher() {
  $pdo = connectToDatabase();
  // queries only a few columns to implement betwatcher.php
  $query = "
    SELECT idUser, nameUser, colorUser
    FROM user
    ORDER BY idUser ASC
  ";

  $stmt = $pdo->prepare($query);    
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// XXX - count users
function countUsers() {

  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM user
  ";

  $stmt = $pdo->prepare($query);
  $stmt->execute();
  
  return $stmt->rowCount();
}




// XXX -- get user by id
function getUserById($idUser) {
  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM user u
    INNER JOIN summary s
    ON u.idUser = s.idUserSumm
    WHERE idUser = ?
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- get user by rank
function getUserByRank($rank) {
  $pdo = connectToDatabase();
  $query = "
    SELECT 
      u.idUser, 
      u.nameUser,
      u.colorUser,
      s.totScoreSumm, 
      s.winsSumm
    FROM user u
    INNER JOIN summary s
    ON u.idUser = s.idUserSumm
    WHERE rankSumm = ?
  ";

  $args = [$rank];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);                 


  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- write new user code + datetime
function writeNewCode($newCodeUser, $codeEditDatetime, $idUser) {
  $pdo = connectToDatabase();

  $query = "
    UPDATE user
    SET passUser = ?,
    lastPassEditUser = ?
    WHERE idUser = ?
  ";

  $args = [$newCodeUser, $codeEditDatetime, $idUser];
  $stmt = $pdo->prepare($query);      
  $stmt->execute($args);

  return $stmt->rowCount();
}



/*

// XXX -- écrit la date & heure de la dernière modification de code 
function writeCodeEditDatetime($idUser, $codeEditDatetime) {
  $pdo = connectToDatabase();

  $query = "
    UPDATE user
    SET lastPassEditUser = ?
    WHERE idUser = ?
  ";

  $args = [$codeEditDatetime, $idUser];
  $stmt = $pdo->prepare($query);      
  $stmt->execute($args);

  return $stmt->rowCount();
}

*/