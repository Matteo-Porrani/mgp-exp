
<?php



// XXX -- list all super users
function listSuperUsers() {
  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM super
  ";

  $stmt = $pdo->prepare($query);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






// XXX -- get super by id
function getSuperById($idSuper) {
  $pdo = connectToDatabase();
  $query = "
    SELECT *
    FROM super
    WHERE idSuper = ?
  ";

  $args = [$idSuper];
  $stmt = $pdo->prepare($query);
  $stmt->execute($args);                 

  return $stmt->fetch(PDO::FETCH_ASSOC);
}
