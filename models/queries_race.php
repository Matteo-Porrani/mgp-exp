<?php


// XXX -- list all races
function listRaces() {
  $pdo = connectToDatabase();

  $query = "
    SELECT *
    FROM race r
    INNER JOIN nation n
    ON r.idNatRace = n.idNation
    INNER JOIN track t
    ON r.idTrackRace = t.idTrack
    WHERE r.seasonRace = 2022
    ORDER BY idRace
  ";

  $stmt = $pdo->prepare($query);    
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// XXX -- count closed races
function countClosedRaces() {
  $pdo = connectToDatabase();

  $query = "
    SELECT COUNT(*)
    FROM race r
    WHERE r.stateRace = 'closed'
  ";

  $stmt = $pdo->prepare($query);    
  $stmt->execute();

  return $stmt->fetch(PDO::FETCH_ASSOC);
}






// XXX -- get current race
function getCurrentRace() {
  $pdo = connectToDatabase();

  $query = "
    SELECT 
      r.idRace, 
      r.seasonRace, 
      r.shortNameRace, 
      r.fullNameRace, 
      r.dateRace,
      r.countdownRace,
      r.progRace,
      r.stateRace, 
      n.flagFileNation,
      r.scoreCalcRace,
      r.resultRace,
      r.dnfRace,
      t.nameTrack,
      t.filenameTrack,
      t.lengthTrack
    FROM race r
    INNER JOIN nation n
    ON r.idNatRace = n.idNation
    INNER JOIN track t
    ON r.idTrackRace = t.idTrack
    WHERE currRace = 'Y'
  ";

  $stmt = $pdo->prepare($query);      
  $stmt->execute();                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- get race by ID
function getRaceById($idRace, $idSeason) {
  
  $pdo = connectToDatabase();

  $query = "
    SELECT 
      r.idRace, 
      r.seasonRace, 
      r.shortNameRace, 
      r.fullNameRace, 
      r.dateRace,
      r.countdownRace,
      r.progRace,
      r.stateRace, 
      n.flagFileNation,
      r.scoreCalcRace,
      r.resultRace,
      t.nameTrack,
      t.filenameTrack,
      t.lengthTrack
    FROM race r
    INNER JOIN nation n
    ON r.idNatRace = n.idNation
    INNER JOIN track t
    ON r.idTrackRace = t.idTrack
    WHERE r.idRace = ?
    AND r.seasonRace = ?
  ";

  $args = [$idRace, $idSeason];
  $stmt = $pdo->prepare($query);      
  $stmt->execute($args);                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}











// XXX -- get race result
function getRaceResult($idRace, $idSeason) {
  $pdo = connectToDatabase();

  $query = "
    SELECT 
      r.idRace, 
      r.seasonRace, 
      r.shortNameRace, 
      r.fullNameRace, 
      n.flagFileNation,
      r.resultRace,
      r.dnfRace
    FROM race r
    INNER JOIN nation n
    ON r.idNatRace = n.idNation
    WHERE r.idRace = ?
    AND r.seasonRace = ?
  ";

  $args = [$idRace, $idSeason];
  $stmt = $pdo->prepare($query);      
  $stmt->execute($args);                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- get race state (open / closed)
function getRaceState($idRace) {
  $pdo = connectToDatabase();

  $query = "
    SELECT stateRace
    FROM race
    WHERE idRace = ?
  ";

  $args = [$idRace];
  $stmt = $pdo->prepare($query);      
  $stmt->execute($args);                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}




// XXX - activation de course
function activateRace($idRace, $idSeason) {
  $pdo = connectToDatabase();

  // toutes les courses à NO
  $query1 = "
    UPDATE race
    SET currRace = 'N'
  ";

  $stmt = $pdo->prepare($query1);
  $stmt->execute();

  // activation d'une course
  $query2 = "
    UPDATE race
    SET currRace = 'Y'
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$idRace, $idSeason];
  $stmt = $pdo->prepare($query2);
  $stmt->execute($args);
}





// XXX - effacer résultat de course
function deleteResult($idRace) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET 
      stateRace = 'open', 
      idWinnerRace = NULL, 
      resultRace = NULL
    WHERE idRace = ?
  ";

  $args = [$idRace];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);
}




// MK -- phases de course : GETTER / SETTER


function getRaceProgress($idRace, $idSeason) {

  $pdo = connectToDatabase();
  $query = "
    SELECT progRace 
    FROM race
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch();
}




function setRaceProgress($idRace, $idSeason, $progRace) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET progRace = ?
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$progRace, $idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}



// XXX -- écrit les valeurs qui ferment la course dans la table 'race'
function writeScoreExistsAndCloseRace($idRace, $idSeason) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET 
      scoreCalcRace = 'Y',
      stateRace = 'closed'
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  // return $stmt->rowCount();
}





// XXX -- update result
function updateResult($idWinner, $serialRes, $idRace, $idSeason) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET 
      idWinnerRace = ?,
      resultRace = ?
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$idWinner, $serialRes, $idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}




// XXX -- update DNF
function updateDnf($serialRes, $idRace, $idSeason) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET dnfRace = ?
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$serialRes, $idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}



// XXX -- clear result & DNF
function clearResAndDnf($idRace, $idSeason) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET
      resultRace = NULL,
      dnfRace = NULL
    WHERE idRace = ?
    AND seasonRace = ?
  ";

  $args = [$idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}








// XXX -- write countdown
function setCountdown($countdownString, $idRace, $idSeason) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE race
    SET countdownRace = ?
    WHERE idRace = ? 
    AND seasonRace = ?
  ";

  $args = [$countdownString, $idRace, $idSeason];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);
  return $stmt->rowCount();
}