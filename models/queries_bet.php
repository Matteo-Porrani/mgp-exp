<?php




// XXX - comptage de pronos enregistrés pour une course
function countExistingBets($idRaceBet, $idSeasonBet) {

  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM bet
    WHERE idRaceBet = ?
    AND idSeasonBet = ?
  ";

  $args = [$idRaceBet, $idSeasonBet];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}



/*
### DEPRECATED ###

// XXX -- vérification de l'existence d'un prono pour évén en cours
function checkCurrBet($idRaceBet, $idSeasonBet, $idUserBet) {
  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM bet
    WHERE idRaceBet = ?
    AND idSeasonBet = ?
    AND idUserBet = ?
  ";

  $args = [$idRaceBet, $idSeasonBet, $idUserBet];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);
  return $stmt->rowCount();
}
*/



// XXX - écriture de prono
function writeBet($idRaceBet, $idSeasonBet, $idUserBet, $serialzdBet, $tmstBet) {

  $pdo = connectToDatabase();
  $query = "
    INSERT INTO bet
    VALUES (NULL, ?, ?, ?, ?, ?)
  ";

  $args = [$idRaceBet, $idSeasonBet, $idUserBet, $serialzdBet, $tmstBet];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}


// XXX - modification de prono
function modifyBet($idBet, $serialBet, $tmstBet) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE bet
    SET serialBet = ?,
    tmstBet = ?
    WHERE idBet = ?
  ";

  $args = [$serialBet, $tmstBet, $idBet];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);
  return $stmt->rowCount();
}





// XXX -- lecture d'un prono
function readCurrBet($idRaceBet, $idSeasonBet, $idUserBet) {

  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM bet
    WHERE idRaceBet = ?
    AND idSeasonBet = ?
    AND idUserBet = ?
  ";

  $args = [$idRaceBet, $idSeasonBet, $idUserBet];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}


