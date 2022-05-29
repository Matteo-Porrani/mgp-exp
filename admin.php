<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');




// page title
$pageTitle = 'Admin';


// MK -- redirection si user non reconnu
if ($_SESSION['connected'] !== true) {
  header('Location: index.php');
}




$superuserTag = 'ADM';





// A*A -- read data for current event
$currRace = getCurrentRace($_SESSION['idCurrRace']);
// on réécrit la phase en $_SESSION
$_SESSION['progRace'] = $currRace['progRace'];

if (isset($currRace['resultRace'])) {
  $unsResult = unserialize($currRace['resultRace']);
  $unsDnf = unserialize($currRace['dnfRace']);

  // si le DNF n'est pas encore renseigné on lui affecte un array vide
  if (!$unsDnf) {
    $unsDnf = [];
  }
}


// A*A -- read data for active riders
$activeRiders = listActiveRiders();



require_once('views/inc/head.phtml');

if ($superuserTag === 'ADM') {
  require_once('views/view_admin.phtml');
} else if ($superuserTag === 'RAC') {
  require_once('views/view_racedir.phtml');
}


require_once('views/inc/tail.phtml');