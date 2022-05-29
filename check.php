<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Check';


$idRace = $_SESSION['idCurrRace'];
$idSeason = $_SESSION['idCurrSeason'];
$totUsers = countUsers();

$usersData = [];

for ($u = 1; $u <= $totUsers; $u++) {

  $userInfo = readScoreUser($idRace, $idSeason, $u);

  $userStepScore = unserialize($userInfo['userStepScore']);
  $userTouchScore = unserialize($userInfo['userTouchScore']);

  $usersData[$u]['step'] = $userStepScore;
  $usersData[$u]['touch'] = $userTouchScore;

}



// debugPR($usersData);



require_once('views/inc/head.phtml');

require_once('views/view_check.phtml');

require_once('views/inc/tail.phtml');