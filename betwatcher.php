<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');


// page title
$pageTitle = 'Situation pronostics';





// A*A -- (1) -- get users list
$usersBetData = listUsersForBetwatcher();


// A*A -- (2) - get current race identifiers
$idRace = $_SESSION['idCurrRace'];
$idSeason = $_SESSION['idCurrSeason'];

// A*A -- (3) -- loop through users, if user has a bet, set status & bet datetime
for ($i = 0; $i < count($usersBetData); $i++) {

  $currBetData = readCurrBet($idRace, $idSeason, ($i + 1));
  if (!empty($currBetData)) {
    $usersBetData[$i]['betStatus'] = 'YES';
    // transform timestamp in readable string
    $betDateTime = date("d/m à H:i", $currBetData['tmstBet']);
    $usersBetData[$i]['betDateTime'] = $betDateTime;
  } else {
    $usersBetData[$i]['betStatus'] = 'NO';
    $usersBetData[$i]['betDateTime'] = '';
  }

}




require_once('views/inc/head.phtml');

require_once('views/view_betwatcher.phtml');

require_once('views/inc/tail.phtml');
