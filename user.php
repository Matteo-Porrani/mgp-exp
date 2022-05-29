<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'User';


// MK -- redirection si user non reconnu
if ($_SESSION['connected'] !== true) {
  header('Location: index.php');
}


// TODO

$userName = getUserById($_SESSION['id'])['nameUser'];
$userMail = getUserById($_SESSION['id'])['mailUser'];

// debugPR($userName);
// debugPR($userMail);





// A*A -- DOUBLE SECURITÉ : on vérifie à nouveau la phase (si jamais elle a changé et qu'on n'est pas repassé par home) 
$currRace = getCurrentRace($_SESSION['idCurrRace']);
$nameCurrRace = $currRace['fullNameRace'];
// on réécrit la phase en $_SESSION
$_SESSION['progRace'] = $currRace['progRace'];
// la variable $phase est nécessaire pour désactiver le bouton 'MODIFIER' à partir de la phase 'yellow'
$phase = $_SESSION['progRace'];





// on lit les données de l'user courant (toutes les colonnes des tables 'user' et 'summary')
$userInfo = getUserById($_SESSION['id']);

// LOG -- on vérifie si un prono pour le GP en cours existe. Si rien ne vient, on affichera un message
$currBet = readCurrBet($_SESSION['idCurrRace'], $_SESSION['idCurrSeason'], $_SESSION['id']);


if (!empty($currBet)) { 
  $_SESSION['idCurrBet'] = $currBet['idBet'];
} else {
  $_SESSION['idCurrBet'] = "";
}



// on compte le nb de GP terminés (pour les stats sur la page user)
$closedRaces = countClosedRaces()['COUNT(*)'];






// debugPR($_SESSION);


// on prépare les variables d'affichage en fonction de la présence d'un prono ou pas
if (!empty($currBet)) {
  
  $idBet = $currBet['idBet'];
  

  $unsBet = unserialize($currBet['serialBet']);
  $datetimeBet = date("d/m à H:i", $currBet['tmstBet']);

  $userAlert = "Ton pronostic pour le GP $nameCurrRace <br>a été enregistré le $datetimeBet";

  
  $betAction = "Modifier pronostic";
  $betActionTarget = "bet.php?action=edit&betnum=$idBet&pos1=$unsBet[1]&pos2=$unsBet[2]&pos3=$unsBet[3]&pos4=$unsBet[4]&pos5=$unsBet[5]&pos6=$unsBet[6]&pos7=$unsBet[7]&pos8=$unsBet[8]&pos9=$unsBet[9]&pos10=$unsBet[10]";
  
} else {

  

  $userAlert = "Tu n'as pas encore de pronostic pour GP $nameCurrRace";
  $betAction = "Ajouter pronostic";

  $betActionTarget = "bet.php";
}









require_once('views/inc/head.phtml');

require_once('views/view_user.phtml');

require_once('views/inc/tail.phtml');