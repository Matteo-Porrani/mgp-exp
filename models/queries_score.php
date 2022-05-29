<?php



// XXX - écriture de 'step' score
function writeStepScore($idRace, $idSeason, $idUser, $userStepScore, $userTouchScore, $totStepScore) {

  $pdo = connectToDatabase();
  $query = "
    INSERT INTO score
    VALUES (
      NULL,
      ?, ?, ?, ?, ?, ?, 0
    )
  ";

  $args = [$idRace, $idSeason, $idUser, $userStepScore, $userTouchScore, $totStepScore];
  $stmt = $pdo->prepare($query);

  $stmt->execute($args);
}


function writeRoundWin($idRace, $idSeason, $idUser) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE score
    SET winScore = 1
    WHERE idRaceScore = ?
    AND idSeasonScore = ?
    AND idUserScore = ? 
  ";

  $args = [$idRace, $idSeason, $idUser];
  $stmt = $pdo->prepare($query);

  $stmt->execute($args);
}







// XXX - lecture du score
function readScoreUser($idRace, $idSeason, $idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM score
    WHERE idRaceScore = ? 
    AND idSeasonScore = ?
    AND idUserScore = ?
  ";

  $args = [$idRace, $idSeason, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX - récupère les placements de TOUS les pronostics d'un user
function readTouchData($idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT userTouchScore
    FROM score
    WHERE idUserScore = ?
    ORDER BY idRaceScore
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





// XXX -- calculer le total victories de manche d'un user
function readTotWins($idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT SUM(winScore)
    FROM score
    WHERE idUserScore = ?;
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}


// XXX -- écrire le total victories de manche d'un user
function writeTotWins($idUser, $totWins) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE summary
    SET winsSumm = ?
    WHERE idUserSumm = ?;
  ";

  $args = [$totWins, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

}


// XXX -- écriture bien placés - TOTAL WP
function writeTouchTot($idUser, $wpTot, $bpxTot, $bpsTot) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE summary
    SET 
      wpTotSumm = ?,
      bpxTotSumm = ?, 
      bpsTotSumm = ? 
    WHERE idUserSumm = ?
  ";

  $args = [$wpTot, $bpxTot, $bpsTot, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);
}



// XXX -- returne le score total d'un user
function getUserTotScore($idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT SUM(totStepScore)
    FROM score
    WHERE idUserScore = ?;
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- écrit le score total d'un user
function setUserTotScore($idUser, $totScore) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE summary
    SET totScoreSumm = ?
    WHERE idUserSumm = ?;
  ";

  $args = [$totScore, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

}







// XXX -- récupère les données identité & score user pour affichage résultats
function getUserScoreData($idRace, $idSeason, $idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT
      s.idRaceScore,
      s.idSeasonScore,
      s.idUserScore,
      u.idUser,
      u.nameUser,
      u.colorUser,
      s.totStepScore,
      s.winScore,
      s.userStepScore,
      s.userTouchScore
    FROM score s
    INNER JOIN user u
    ON s.idUserScore = u.idUser
    WHERE s.idRaceScore = ?
    AND s.idSeasonScore = ?
    AND s.idUserScore = ?
  ";

  $args = [$idRace, $idSeason, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// NEW
// XXX -- récupère les données prono d'un user (utilisée dans 'raceresult.php')
function getUserBetData($idRace, $idSeason, $idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT serialBet
    FROM bet
    WHERE idRaceBet = ?
    AND idSeasonBet = ?
    AND idUserBet = ?
  ";

  $args = [$idRace, $idSeason, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}






// XXX -- special function
// la requête est composée dans 'scorecalc.inc.php' et passée en argument

function wpWrite($query) {

  $pdo = connectToDatabase();

  $stmt = $pdo->prepare($query);
  $stmt->execute();

  return $stmt->rowCount();

}




// XXX - récupère les données nécessaire pour calculer poweredScore (dans 'scorecalc.php')
function getDataToCalculatePower($idUser) {

  $pdo = connectToDatabase();
  $query = "
    SELECT
      totScoreSumm,
      winsSumm,
      wpTotSumm,
      bpxTotSumm,
      bpsTotSumm,
      wp01Summ,
      wp02Summ,
      wp03Summ,
      wp04Summ,
      wp05Summ,
      wp06Summ,
      wp07Summ,
      wp08Summ,
      wp09Summ,
      wp10Summ
    FROM summary
    WHERE idUserSumm = ?
  ";

  $args = [$idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}



// XXX -- récupère les données nécessaires pour écrire l'ordre du classement (dans 'scorecalc.php')
// LOG -- l'option 'ORDER BY s.poweredScoreSumm DESC' retourne les données déjà dans l'ordre du classement  
function getDataToCalculateRank() {

  $pdo = connectToDatabase();
  $query = "
    SELECT
      s.idUserSumm,
      u.nameUser,
      s.poweredScoreSumm
    FROM summary s
    INNER JOIN user u
    ON s.idUserSumm = u.idUser
    ORDER BY s.poweredScoreSumm DESC  
  ";

  $stmt = $pdo->prepare($query);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






// XXX -- écrit le poweredScore d'un user
function writePoweredScore($poweredScore, $idUser) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE summary
    SET poweredScoreSumm = ?
    WHERE idUserSumm = ?
  ";

  $args = [$poweredScore, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}




// XXX -- écrit la position du classement d'un user
function setRank($rank, $idUser) {

  $pdo = connectToDatabase();
  $query = "
    UPDATE summary
    SET rankSumm = ?
    WHERE idUserSumm = ?
  ";

  $args = [$rank, $idUser];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);

  return $stmt->rowCount();
}




// XXX -- get highest score
function getMaxScore() {
  $pdo = connectToDatabase();
  $query = "
    SELECT totScoreSumm
    FROM summary
    ORDER BY totScoreSumm DESC
    LIMIT 1
  ";




  $stmt = $pdo->prepare($query);
  $stmt->execute();                 
  return $stmt->fetch(PDO::FETCH_ASSOC);
}