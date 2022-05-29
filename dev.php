<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');


// page title
$pageTitle = 'Dev';

$closedRacesCount = countClosedRaces()['COUNT(*)'];
$usersCount = countUsers();


// T*T -- preparing an empty array for each rider

$riders = listExistingRiders();

$ridersStatsData = [];

foreach ($riders as $rider) {
  $singleRider['votes'] = 0;
  $singleRider['number'] = $rider['numRider'];
  $singleRider['fullname'] = $rider['lsNameRider'] . " " . $rider['fsNameRider'];
  $singleRider['votesPercent'] = 0;
  $singleRider['points'] = 0;
  
  $singleRider['steps'] = [];

  for ($step = 1; $step <= 21; $step++) {
    $singleRider['steps'][$step] = 0;
  }

  $ridersStatsData[$rider['idRider']] = $singleRider;
}

// T*T ++++++++++++++++++++++++++++++++++++++++++++++++++++++

$seasonID = 2022;

// LOG -- loop through all closed races
for ($raceID = 1; $raceID <= $closedRacesCount; $raceID++) {

  // LOG -- loop through ALL USERS
  for ($u = 1; $u <= $usersCount; $u++) {

    $userID = $u;

    $bet = readCurrBet($raceID, $seasonID, $userID);
    $unsBet = unserialize($bet['serialBet']);

    $score = readScoreUser($raceID, $seasonID, $userID);
    $unsScore = unserialize($score['userStepScore']);

    $combined = [];

    for ($i = 1; $i <= 10; $i++) {
      $combined[$i]['rid'] = $unsBet[$i];
      $combined[$i]['sco'] = $unsScore[$i];
    }

    // LOG -- loop over combined
    for ($i = 1; $i <= 10; $i++) {
      $ridersStatsData[$combined[$i]['rid']]['votes'] = $ridersStatsData[$combined[$i]['rid']]['votes'] + 1;
      $ridersStatsData[$combined[$i]['rid']]['points'] += $combined[$i]['sco'];
      // updating steps
      $ridersStatsData[$combined[$i]['rid']]['steps'][$raceID]++;
      // votes percent for progress bar
      $ridersStatsData[$combined[$i]['rid']]['votesPercent'] = (($ridersStatsData[$combined[$i]['rid']]['votes'] * 100) / ($closedRacesCount * $usersCount));
    }
  }


}


// sorting DESC on number of counts
arsort($ridersStatsData);

debugPR($ridersStatsData);



// putting all scores in a single array
$globalScores = [];

foreach($ridersStatsData as $rider) {
  $globalScores[] = $rider['points'];
}

$maxScore = max($globalScores);


debugPR($maxScore);



// require_once('views/inc/head.phtml');

/*
require_once('views/view_stats.phtml');

require_once('views/inc/tail.phtml');
*/