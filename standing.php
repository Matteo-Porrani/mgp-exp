<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Classement';


// on récupère le nb de joueurs
$nbUsers = countUsers();



// T*T -- old code
function getUserStandingData($rank) {

  $maxScore = getMaxScore()['totScoreSumm'];
  $user = getUserByRank($rank);

  $id = $user['idUser'];
  $name = $user['nameUser'];
  $pts = $user['totScoreSumm'];
  $gap = $maxScore - $user['totScoreSumm'];
  $wins = $user['winsSumm'];
  $color = $user['colorUser'];

  $userData = [$name, $pts, $gap, $wins, $id, $maxScore, $color];
  return $userData;

}


$raceId = $_SESSION['idCurrRace'];
$raceFullName = getCurrentRace($raceId)['fullNameRace'];





require_once('views/inc/head.phtml');
require_once('views/view_standing.phtml');
require_once('views/inc/tail.phtml');