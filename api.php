<?php

header('Content-Type: application/json');

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');


$usersCount = countUsers();






// MK -- params data
// NEW -- /api.php?data=params
if (!empty($_GET['data']) && $_GET['data'] === 'params') {
  $closedRacesCount = countClosedRaces()['COUNT(*)'];

  $params['activeOrder'] = 0;
  $params['totalBets'] = intval($closedRacesCount) * 10;

  $paramsJson = json_encode($params);

  echo $paramsJson;

}




// MK -- users data
// NEW -- /api.php?data=users
if (!empty($_GET['data']) && $_GET['data'] === 'users') {

  $users = listUsersForStats();

  foreach ($users as $key => $value) {

    $addData = getPlacementsById($value['id']);
    $steps = getStepsById($value['id']);

    $simpleSteps = [];
    foreach ($steps as $step) {
      $simpleSteps[] = $step['totStepScore'];
    }


    $users[$key]['col'] = translateColor($users[$key]['col']);
    $users[$key]['show'] = true;
    $users[$key]['wins'] = intval($addData['wins']);
    $users[$key]['prec'] = intval($addData['wp']);
    $users[$key]['regul'] = intval($addData['wp']) + intval($addData['bpx']) + intval($addData['bps']);
    $users[$key]['placed']['wp'] = $addData['wp'];
    $users[$key]['placed']['bpx'] = $addData['bpx'];
    $users[$key]['placed']['bps'] = $addData['bps'];
    $users[$key]['steps'] = $simpleSteps;
  }

  $usersJson = json_encode($users);

  echo $usersJson;
  // debugPR($users);

}
