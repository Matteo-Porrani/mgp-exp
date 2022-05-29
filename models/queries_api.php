<?php



// XXX -- list users for betwatcher
function listUsersForStats() {
  $pdo = connectToDatabase();
  // queries only a few columns to implement betwatcher.php
  $query = "
    SELECT 
      idUser as id, 
      nameUser as name, 
      colorUser as col, 
      rankSumm as rank, 
      totScoreSumm as score
    FROM user u
    INNER JOIN summary s
    ON u.idUser = s.idUserSumm
    ORDER BY u.idUser ASC
  ";

  $stmt = $pdo->prepare($query);    
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getPlacementsById($idUser) {
  $pdo = connectToDatabase();
  // queries only a few columns to implement betwatcher.php
  $query = "
    SELECT 
      winsSumm as wins, 
      wpTotSumm as wp, 
      bpxTotSumm as bpx, 
      bpsTotSumm as bps 
    FROM summary
    WHERE idUserSumm = ?
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);    
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



function getStepsById($idUser) {
  $pdo = connectToDatabase();
  // queries only a few columns to implement betwatcher.php
  $query = "
    SELECT totStepScore
    FROM score
    WHERE idUserScore = ?
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);    
  $stmt->execute($args);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
