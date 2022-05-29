<?php


// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Résultats';





// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// données générales du GP
$idRace = intval($_GET['idRace']);
$idSeason = $_GET['idSeason'];

$generalRaceData = getRaceById($idRace, $idSeason);
$fullNameRace = $generalRaceData['fullNameRace'];
$flagFile = $generalRaceData['flagFileNation'];

// var_dump($idRace);


// résultat GP
$raceData = getRaceResult($idRace, $idSeason); 
$raceResSerial = $raceData['resultRace']; 
$raceDnfSerial = $raceData['dnfRace']; 
$raceRes = unserialize($raceResSerial);
$raceDnf = unserialize($raceDnfSerial);

// debugPR($raceRes);
// debugPR($raceDnf);


// comptage joueurs
$totUsers = countUsers();


// LOG -- pour l'instant on ne s'occupe pas de l'affichage du résultat course
// $result = readRaceResult($idRace);
// $unsResult = unserialize($result['resultRace']);
// $raceNumb = $result['idRace'];
// $raceName = $result['fullNameRace'];







// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// gestion des boutons PREC. & SUIV.

// on récupère le total GP disputés
$countClosedRaces = intval(countClosedRaces()['COUNT(*)']);

$prevRaceAnch = "";
$nextRaceAnch = "";


if ($idRace > 1 && $idRace < $countClosedRaces) {
  $prevRaceAnch = "raceresult.php?idRace=" . ($idRace - 1) . "&idSeason=" . $idSeason;
  $nextRaceAnch = "raceresult.php?idRace=" . ($idRace + 1) . "&idSeason=" . $idSeason;
}


if ($idRace === 1) {
  $nextRaceAnch = "raceresult.php?idRace=" . ($idRace + 1) . "&idSeason=" . $idSeason;
}

if ($idRace === $countClosedRaces) {
  $prevRaceAnch = "raceresult.php?idRace=" . ($idRace - 1) . "&idSeason=" . $idSeason;
}


// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// MK -- données affichage résultat de courses


$resPositions = [];
$dnfPositions = [];


// récupération données pilotes classés
foreach ($raceRes as $pos) {

  if ($pos != 'emptypos') {

    $line = [];
    $rider = getRiderById($pos);

    $line[0] = $rider['numRider'];
    $line[1] = $rider['lsNameRider'];
    $line[2] = $rider['fsNameRider'];
    $line[3] = $rider['flagFileNation'];
    
    $resPositions[] = $line;
  }

}


// récupération données pilotes DNF
foreach ($raceDnf as $pos) {

  if ($pos != 'emptypos') {

    $line = [];
    $rider = getRiderById($pos);

    $line[0] = $rider['numRider'];
    $line[1] = $rider['lsNameRider'];
    $line[2] = $rider['fsNameRider'];
    $line[3] = $rider['flagFileNation'];

    $dnfPositions[] = $line;
  }

}


// debugPR($resPositions);
// debugPR($dnfPositions);




// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// MK -- données affichage résultats des pronostics

// variable globale qui contiendra les données de tous les joueurs
$resultDisplayData = [];

for ($u = 1; $u <= $totUsers; $u++) {


  // cette f. pioche dans les tables user, bet & score pour récupérer toutes les données nécessaires
  // à l'affichage des résultats...
  $userScoreData = getUserScoreData($idRace, $idSeason, $u);
  $userBetData = getUserBetData($idRace, $idSeason, $u);
  // debugPR($userBetData);




  // ensuite on désérialize les arrays
  $userScoreData['unsBet'] = unserialize($userBetData['serialBet']);

  $userScoreData['userStepScore'] = unserialize($userScoreData['userStepScore']);
  $userScoreData['userTouchScore'] = unserialize($userScoreData['userTouchScore']);



  $uDisplay = [];
  $uDisplay['resHeader'][0] = $userScoreData['idUser'];
  $uDisplay['resHeader'][1] = $userScoreData['nameUser'];
  $uDisplay['resHeader'][2] = $userScoreData['colorUser'];
  $uDisplay['resHeader'][3] = $userScoreData['totStepScore'];
  $uDisplay['resHeader'][4] = $userScoreData['winScore'];

  $uDisplay['resBody'][0] = "emptypos";

  // boucle sur les 10 positions
  for ($pos = 1; $pos <= 10; $pos++) {

    switch ($userScoreData['userTouchScore'][$pos]) {
      case 'WP':
        $iconClass = "fas fa-star fa-spin text-greenlight";
        $lineColor = "greenlight";
        break;
      case 'BPX':
        $iconClass = "fas fa-square text-orange";
        $lineColor = "orange";
        break;
      case 'BPS':
        $iconClass = "fas fa-circle text-yellow";
        $lineColor = "yellow";
        break;
      case 'OUT':
        $iconClass = "fas fa-times text-gray2";
        $lineColor = "gray2";
        break;
    }

    $uDisplay['resBody'][$pos][0] = $iconClass;
    $uDisplay['resBody'][$pos][1] = getRiderById($userScoreData['unsBet'][$pos])['numRider'];
    $uDisplay['resBody'][$pos][2] = getRiderById($userScoreData['unsBet'][$pos])['lsNameRider'];
    $uDisplay['resBody'][$pos][3] = substr(getRiderById($userScoreData['unsBet'][$pos])['fsNameRider'], 0, 1) . ".";
    $uDisplay['resBody'][$pos][4] = $userScoreData['userStepScore'][$pos];
    $uDisplay['resBody'][$pos][5] = $lineColor;
  }


  $resultDisplayData[$u] = $uDisplay;

}



// debugPR($resultDisplayData);

// A*A -- on tri l'array en fonction du total points de manche (contenu à l'emplacement $user['resHeader'][3])
usort($resultDisplayData, function ($u1, $u2) {
  return $u2['resHeader'][3] <=> $u1['resHeader'][3];
});


// debugPR($resultDisplayData);








// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// exécution avec un idUser


/** 
 * cette f. a besoin de connaître :
 * - l'id de course
 * - l'array unserialized des résultats
 * - l'id user
 * 
 * Elle va retourner un array multidimensionnel qui contient :
 * 
 * [0] => $uName
 * [1] => $totStepScore
 * [2] => $allLines
 *        [0] => [12, 20, 2, "<i>", "bg-warning"]
 *        [1]
 *        [2]
 *        [3]
 *        [4]
 * */




/*

function userContentGen($idRace, $unsResult, $u)
{


  // récupération nom user
  $uName = getUserFromId($u)['nameUser'];


  // récupération prono
  $bet = readCurrBet($idRace, $u);
  $unsBet = unserialize($bet['serialzdBet']);
  // debugPR($unsBet);


  // récupération du score
  $userScore = readScoreUser($idRace, $u);
  $stepScore = unserialize($userScore['userStepScore']);
  $totStepScore = $userScore['totStepScore'];
  $confirmWin = $userScore['winScore'];
  // debugPR($stepScore);




  // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  $allLines = [];


  // création des lignes pilotes
  for ($p = 0; $p <= 4; $p++) {

    $line = [];

    $riderRes = $unsResult[$p];   // un id
    $fullRiderRes = getRiderFromId($riderRes);
    $showRiderRes = "#{$fullRiderRes['numRider']}";
    $line[] = $showRiderRes;


    $riderBet = $unsBet[$p];      // un id
    $fullRiderBet = getRiderFromId($riderBet);
    $showRiderBet = "#{$fullRiderBet['numRider']} {$fullRiderBet['lsNameRider']}";
    $line[] = $showRiderBet;


    $pts = $stepScore[$p];


    $line[] = $pts;


    if ($pts == 0) {
      $icon = "";
      $bgCol = "bg-mid-gray";
    } elseif ($pts > 2) {
      $icon = '<i class="far fa-star fa-spin"></i>';
      $bgCol = "bg-dodger";
    } else {
      $icon = '<i class="far fa-flag"></i>';
      $bgCol = "bg-warning";
    }


    $line[] = $icon;
    $line[] = $bgCol;


    $allLines[] = $line;
  }




  $masterData = [];
  $masterData[] = $uName;
  $masterData[] = $totStepScore;
  $masterData[] = $allLines;
  $masterData[] = $confirmWin;


  return $masterData;


}

*/



require_once('views/inc/head.phtml');
require_once('views/view_raceresult.phtml');
require_once('views/inc/tail.phtml');
