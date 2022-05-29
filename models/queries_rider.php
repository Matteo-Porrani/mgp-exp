<?php

// XXX -- list all EXISTING riders
function listExistingRiders() {
  $pdo = connectToDatabase();

  $query = "
    SELECT *
    FROM rider
  ";

  $stmt = $pdo->prepare($query);    
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// XXX -- list all ACTIVE riders
function listActiveRiders() {
  $pdo = connectToDatabase();

  $query = "
    SELECT *
    FROM rider
    WHERE activeRider = 'Y'
    ORDER BY numRider
  ";

  $stmt = $pdo->prepare($query);    
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// XXX -- get rider by id
function getRiderById($idRider) {
  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM rider r
    INNER JOIN nation n
    ON r.idNatRider = n.idNation
    WHERE idRider = ?
  ";

  $args = [$idRider];
  $stmt = $pdo->prepare($query);         
  $stmt->execute($args);                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- activation/désactivation pilote
function setRiderStatus($idRider, $status) {
  $pdo = connectToDatabase();

  $query = "
    UPDATE rider
    SET activeRider = ?
    WHERE idRider = ?
  ";

  $args = [$status, $idRider];
  $stmt = $pdo->prepare($query);      
  
  $stmt->execute($args);
}