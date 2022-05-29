<?php


// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Pronostic';



// MK -- [includes] gestionnaire de formulaire
require_once('includes/bet_handler.inc.php');


// récupère les pilotes pour la création des <option>s
$allRiders = listActiveRiders();





// récupère l'événement courant
$currRace = getCurrentRace();
$idRace = $currRace['idRace'];
$fullNameRace = $currRace['fullNameRace'];
$dateRace = date("d/m", $currRace['dateRace']);



// A*A -- analyse la phase
$phase = $currRace['progRace'];



/*
if ($_SESSION['id'] != 7) {
  if ($phase !== 'green') {
    header('Location: user.php');
    exit;
  }
}
*/




// debugPR($_SESSION);


require_once('views/inc/head.phtml');

require_once('views/view_bet.phtml');

require_once('views/inc/tail.phtml');