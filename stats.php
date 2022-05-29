<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');


// page title
$pageTitle = 'Statistiques';


$closedRacesCount = countClosedRaces()['COUNT(*)'];
$usersCount = countUsers();


// NEW ++ RIDER STATS +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

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

// A*A -- loop through all closed races
for ($raceID = 1; $raceID <= $closedRacesCount; $raceID++) {

  // A*A -- loop through users
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

    // A*A -- loop over combined array
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

// putting all scores in a single array to get the maximum score
// the maximum score will represent 100% width in points progress bar
$globalScores = [];

foreach($ridersStatsData as $rider) {
  $globalScores[] = $rider['points'];
}

$maxScore = max($globalScores);





require_once('views/inc/head.phtml');

require_once('views/view_stats.phtml');

require_once('views/inc/tail.phtml');








// (+) -- OLD CODE
/*
$allBets = getBets();

$allBetsUns = [];
foreach ($allBets as $bet) {
  // this creates a single array containing all the IDs of all bets (561 elements after 3 races)
  $allBetsUns = array_merge($allBetsUns, unserialize($bet['serialBet']));
}

// We count how many times every rider appears in the array
$ridersCount = array_count_values($allBetsUns);

// sorting DESC on number of counts
arsort($ridersCount);
// debugPR($ridersCount);


$ridersCountData = [];

foreach ($ridersCount as $key => $value) {

  if ($key != 'emptypos') {
    $rider = getRiderById($key);

    $riderData['numRider'] = $rider['numRider'];
    $riderData['lsNameRider'] = $rider['lsNameRider'];
    $riderData['fsNameRider'] = $rider['fsNameRider'];
    $riderData['countRider'] = $value;
    $riderData['percentRider'] = (($riderData['countRider'] * 100) / ($closedRacesCount * $usersCount));
  
    $ridersCountData[] = $riderData;
  }
}
*/

