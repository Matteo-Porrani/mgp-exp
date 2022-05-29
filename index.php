<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Accueil';



// récupérer l'événement courant (celui avec currRace = 'Y')
$currRace = getCurrentRace();
// debugPR($currRace);



// MK -- création dynamique de variables simplifiées à partir de '$currRace'
foreach ($currRace as $key => $val) {
  $$key = $val;
}


// on écrit les infos de l'événement courant en session
$_SESSION['idCurrRace'] = $idRace;
$_SESSION['idCurrSeason'] = $seasonRace;
$_SESSION['fullNameCurrRace'] = $fullNameRace;
$_SESSION['flagCurrRace'] = $flagFileNation;
$_SESSION['racePhase'] = $progRace;

// debugPR(session_id());



// MK -- si un COUNTDOWN est renseigné, on le stocke en session ET dans un cookie
if (!empty($countdownRace)) {
  // création d'un cookie

  // FIXME -- affecter un path = '/' au cookie
  setcookie('countdownCk', $countdownRace, time() + 60);
  $_SESSION['countdownRace'] = $countdownRace;
} else {
  $_SESSION['countdownRace'] = "";
}


// gestion des messages de phase
$lightIcon = "";
$phaseMessage = "";
switch ($progRace) {

  case 'white':
    $lightIcon = "fas fa-circle text-gray2";
    $phaseMessage = "EN ATTENTE D'OUVERTURE";
    $bgMessage = "bg-gray2";
    $txMessage = "text-dark";
    break;

  case 'green':
    $lightIcon = "fas fa-circle text-greenlight";
    $phaseMessage = "PRONOSTICS OUVERTS";
    $bgMessage = "bg-greenlight";
    $txMessage = "text-light";
    break;

  case 'yellow':
    $lightIcon = "fas fa-circle text-yellow";
    $phaseMessage = "PRONOSTICS FERMÉS";
    $bgMessage = "bg-yellow";
    $txMessage = "text-dark";
    break;

  case 'red':
    $lightIcon = "fas fa-circle text-red";
    $phaseMessage = "COURSE TERMINÉE";
    $bgMessage = "bg-red";
    $txMessage = "text-dark";
    break;
}




$displayDateRace = date("d/m", $dateRace);


// récupère 'open' / 'closed' pour la course courante
$stateRace = getRaceState($idRace)['stateRace'];


// BUG -- comptage des pronos déjà enregistrés
$nbBets = countExistingBets($idRace,  $seasonRace);
$nbUsers = countUsers();






// debugPR($_SESSION);

require_once('views/inc/head.phtml');
require_once('views/view_index.phtml');
require_once('views/inc/tail.phtml');
