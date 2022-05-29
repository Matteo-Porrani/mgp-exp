<?php


// error handling var
$errors = false;
// alerts var
$alert = "";
// expected fields
$expected = ['pos1', 'pos2', 'pos3', 'pos4', 'pos5', 'pos6', 'pos7', 'pos8', 'pos9', 'pos10'];
// required fields
$required = ['pos1', 'pos2', 'pos3', 'pos4', 'pos5', 'pos6', 'pos7', 'pos8', 'pos9', 'pos10'];


// A*A -- si requête en POST gestion de formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  // debugPR($_POST);

  foreach ($_POST as $key => $value) {
    // si une  clé est inattendue, on l'ignore
    if (!in_array($key, $expected)) {
      continue;
    }

    // création des variables simplifiées
    $$key = $value;
  }

  // création de l'array pronostic
  // l'index 0 est 'neutralisé' pour que les indexes correspondent aux positions
  $bet = ["emptypos", $pos1, $pos2, $pos3, $pos4, $pos5, $pos6, $pos7, $pos8, $pos9, $pos10];


  // on vérifie si un des champs est vide
  if (in_array("noRider", $bet)) {
    $alert = "Le pronostic n'est pas complèt !";
    $errors = true;
  }


  // on vérifie les doublons
  if (!in_array("noRider", $bet)) {

    for ($i = 0; $i <= 10; $i++) {

      $currRider = $bet[$i];

      for ($j = 0; $j <= 10; $j++) {
        if ($i !== $j) {
          if ($bet[$j] === $currRider) {
            $alert = "Vérifiez les doublons !";
            $errors = true;
          }
        }
      }
    }
  }


  if (!$errors) {
    createBet($bet);
  }
}




function createBet($bet)
{

  if ($_SESSION['type'] === 'STD') {

    if (!empty($_SESSION['idCurrBet'])) {

      // (+) modification prono
      $idBet = $_SESSION['idCurrBet'];
      $serialBet = serialize($bet);

      // BUG -- heure envoi / décalage 1h
      // $tmstBet = time() + (60 * 60 * 1);
      $tmstBet = time();


      // NEW -- récupération idUser
      $idUserBet = $_SESSION['id'];


      $conf = modifyBet($idBet, $serialBet, $tmstBet);

      // confirmation et redirection
      if ($conf > 0) {
        $modTxt = "Le pronostic a bien été modifié.";
        setcookie('modalCookie', $modTxt, time() + 2);

        // NEW -- envoi mail SEULEMENT APRÈS ECRITURE EN BD
        sendMail($idUserBet, $bet, $tmstBet);
      }

      header('Location: user.php');
    } else {

      // PREMIERE ECRITURE PRONO
      $idRaceBet = $_SESSION['idCurrRace'];
      $idSeasonBet = $_SESSION['idCurrSeason'];
      $idUserBet = $_SESSION['id'];
      $serialBet = serialize($bet);

      // BUG -- heure envoi / décalage 1h
      // $tmstBet = time() + (60 * 60 * 1);
      $tmstBet = time();





      $conf = writeBet($idRaceBet, $idSeasonBet, $idUserBet, $serialBet, $tmstBet);

      // confirmation et redirection
      if ($conf > 0) {
        $modTxt = "Le pronostic a bien été enregistré.";
        setcookie('modalCookie', $modTxt, time() + 2);

        // NEW -- envoi mail SEULEMENT APRÈS ECRITURE EN BD
        sendMail($idUserBet, $bet, $tmstBet);
      }

      header('Location: user.php');
    }
  } /* end of 'type' === 'STD' */
} /* end of function */





function sendMail($idUser, $bet, $tmst)
{

  $userName = getUserById($idUser)['nameUser'];
  $userMail = getUserById($idUser)['mailUser'];

  // envoi copie à motorace.battle@gmail.com
  // $dest = $userMail . ", motorace.battle@gmail.com";
  $dest = $userMail;

  $datetimeBet = date("d/m à H:i", $tmst);

  $mailObject = "MGP BATTLE - pronostic ($userName)";

  $headers[] = 'From: mgpbattle<mgpbattle@alwaysdata.net>';
  $headers[] = 'Content-Type: text/plain; charset=utf-8';
  $headers[] = "Reply-To: motorace.battle@gmail.com";

  $message = "Pronostic de $userName \r\n";
  $message .= "Enregistré le $datetimeBet \r\n\r\n";

  for ($p = 1; $p <= 10; $p++) {

    $riderId = $bet[$p];
    $riderInfo = getRiderById($riderId);

    $line = $p . ") — " . $riderInfo['numRider'] . " / " . $riderInfo['lsNameRider'] . " " . $riderInfo['fsNameRider'] . "\r\n";

    $message .= $line;
  }

  $headers = implode("\r\n", $headers);
  // envoi mail
  mail($dest, $mailObject, $message, $headers);
}
