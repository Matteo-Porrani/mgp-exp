<?php



// XXX -- list all bets
function getBets() {
  $pdo = connectToDatabase();
  $query = "
    SELECT idBet, serialBet
    FROM bet
    ORDER BY idBet ASC
  ";

  $stmt = $pdo->prepare($query);    // $stmt est un objet PDOStatement
  $stmt->execute();                 // returne TRUE en cas de succès, FALSE si une erreur survient

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}