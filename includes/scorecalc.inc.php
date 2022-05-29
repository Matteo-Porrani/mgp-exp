<?php


// count users
$totUsers =  countUsers();

// système de points
$scoreRef = ["emptypos", 25, 20, 16, 13, 11, 10, 9, 8, 7, 6];


// A*A -- (1) on lit le résultat de la course
// au lieu d'utiliser une requête spécifique, on réutilise getCurrentRace()
$res = getCurrentRace($_SESSION['idCurrRace'], $_SESSION['idCurrSeason'])['resultRace'];

// on récupère le résultat total de la course
$unsResTotal = unserialize($res);

// on récupère uniquement les index 1 ... 10
$unsRes = array_slice($unsResTotal, 1, 10, true);

// debugPR($unsRes);




// A*A -- (2) on initialise le variables pour determiner la victorie de manche

$maxStep = 0;   // variable qui contiendra le stepScore maximum => pour déterminer la victoire de manche
$usersTotals = [];



// T*T -- boucle users
for ($u = 1; $u <= $totUsers; $u++) {


  // A*A -- (3) on récupère le pronostic de l'user $u
  $bet = readCurrBet($idRace, $idSeason, $u);
  $unsBet = unserialize($bet['serialBet']);



  // A*A -- (4) boucle positions pour calculer scores et placements
  $userStepScore = [];
  $userTouchScore = [];



  for ($p = 1; $p <= 10; $p++) {

    if (in_array($unsBet[$p], $unsRes)) {

      $posInResult = array_search($unsBet[$p], $unsRes);

      if ($posInResult === $p) {   // bien placé 'WP'

        $userStepScore[$p] = $scoreRef[$p];
        $userTouchScore[$p] = 'WP';
      } else {  // mal placé...

        if ($p <= 3 && $posInResult <= 3) {
          // mal placé podium 'BPX'
          $userStepScore[$p] = 9;
          $userTouchScore[$p] = 'BPX';
        } else {
          // mal placé normal 'BPS'
          $userStepScore[$p] = 4;
          $userTouchScore[$p] = 'BPS';
        }
      }
    } else {
      // non placé
      $userStepScore[$p] = 0;
      $userTouchScore[$p] = 'OUT';
    }
  };



  // A*A -- (5) total de manche pour l'user
  $totStepScore = array_sum($userStepScore);

  // A*A -- verification du score qui donne la victoire de manche
  if ($totStepScore > $maxStep) {
    $maxStep = $totStepScore;
  }

  // on stocke l'id user et le total pour la vérif finale victoire de manche
  $usersTotals[$u] = $totStepScore;




  // A*A -- (6) on sérialize avant d'écrire en bdd
  $userStepScore = serialize($userStepScore);
  $userTouchScore = serialize($userTouchScore);

  // BUG -- cette écriture se fait 3 fois ?????????
  writeStepScore($idRace, $idSeason, $u, $userStepScore, $userTouchScore, $totStepScore);




  // A*A -- (7) on récupère la somme des scores partiels de l'user et écrit le TOTAL dans 'summary' 
  $userTotScore = getUserTotScore($u)['SUM(totStepScore)'];
  setUserTotScore($u, $userTotScore);


  // T*T -- end of boucle users
}


// A*A -- boucle users n°2 pour écriture victoire de manche en bdd
for ($u = 1; $u <= $totUsers; $u++) {

  if ($usersTotals[$u] === $maxStep) {
    writeRoundWin($idRace, $idSeason, $u);
  }
}





/*

      // BUG -- vérifier si le bug est réglé ???
      // j'ai remonté l'appel à 'placem.inc.php' avant la récupération de $sortedUsers


      // RPR -- [includes] appel à 'placem.inc.php'
      require_once('placem.inc.php');

      */

// MK -- ##### code de l'ancien 'placem.inc.php' #####

// ce script écrit

// A*A -- boucle users n°3 pour écrire total & détail des placements dans 'summary'
for ($u = 1; $u <= $totUsers; $u++) {

  $userRounds = readTouchData($u);

  $bpx = 0;
  $bps = 0;
  $wp = 0;
  $wpPrecisely = ["emptypos", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];



  // A*A -- boucle sur les placements de TOUTES les courses
  $raceNum = 0;
  foreach ($userRounds as $roundTouch) {

    $raceNum++;
    $unsRoundTouch = unserialize($roundTouch['userTouchScore']);


    for ($i = 1; $i <= 10; $i++) {
      if ($unsRoundTouch[$i] == 'WP') {
        $wp++;
        $wpPrecisely[$i]++;
      } elseif ($unsRoundTouch[$i] == 'BPX') {
        $bpx++;
      } elseif ($unsRoundTouch[$i] == 'BPS') {
        $bps++;
      }
    }
  }


  // on récupère le total de victoires pour l'user $u
  $totWins = readTotWins($u)['SUM(winScore)'];
  // on l'écrit dans la table 'summary' colonne 'winsSumm'
  writeTotWins($u, $totWins);



  // A*A -- écriture wpTot, bpxTot, bpsTot...
  writeTouchTot($u, $wp, $bpx, $bps);


  // boucle positions pour écrire wp1, wp2... dans la table 'summary'
  // colonnes wp01Summ, wp02Summ...

  for ($p = 1; $p <= 10; $p++) {

    $colName = "";
    if ($p < 10) {
      $colName = "wp0" . $p . "Summ";
    } else {
      $colName = "wp" . $p . "Summ";
    }

    $query = "UPDATE summary SET $colName = {$wpPrecisely[$p]} WHERE idUserSumm = $u";

    wpWrite($query);

    // ça fonctionne !!!

  }
}



// ##### ################################# #####

// A*A -- boucle users n°4 pour écrire poweredScore

// FIXME -- old code
/*
for ($u = 1; $u <= $totUsers; $u++) {

  $uNumbers = getDataToCalculatePower($u);

  $uPower = 0;

  $uPower += $uNumbers['wp10Summ'] * 1;
  $uPower += $uNumbers['wp09Summ'] * 10;
  $uPower += $uNumbers['wp08Summ'] * 100;
  $uPower += $uNumbers['wp07Summ'] * 1000;
  $uPower += $uNumbers['wp06Summ'] * 10000;
  $uPower += $uNumbers['wp05Summ'] * 100000;
  $uPower += $uNumbers['wp04Summ'] * 1000000;
  $uPower += $uNumbers['wp03Summ'] * 10000000;
  $uPower += $uNumbers['wp02Summ'] * 100000000;
  $uPower += $uNumbers['wp01Summ'] * 1000000000;
  $uPower += $uNumbers['wp01Summ'] * 10000000000;
  $uPower += $uNumbers['bpsTotSumm'] * 100000000000;
  $uPower += $uNumbers['bpxTotSumm'] * 1000000000000;
  $uPower += $uNumbers['wpTotSumm'] * 10000000000000;
  $uPower += $uNumbers['winsSumm'] * 100000000000000;
  $uPower += $uNumbers['totScoreSumm'] * 100000000000000;


  writePoweredScore($uPower, $u);
}
*/

// NEW -- NEW CODE
for ($u = 1; $u <= $totUsers; $u++) {

  $uNumbers = getDataToCalculatePower($u);

  $uPower = '';

  // poweredScore
  if ($uNumbers['totScoreSumm'] < 100) {
    $poweredScore = '0' . strval($uNumbers['totScoreSumm']);
  } else {
    $poweredScore = strval($uNumbers['totScoreSumm']);
  }

  // poweredWins
  if ($uNumbers['winsSumm'] < 10) {
    $poweredWins = '0' . strval($uNumbers['winsSumm']);
  } else {
    $poweredWins = strval($uNumbers['winsSumm']);
  }

  // first 2 components
  $uPower = $poweredScore . '-' . $poweredWins . '-';

  // other components
  $uPower = $uPower . chr(64 + intval($uNumbers['wp01Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp02Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp03Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp04Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp05Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp06Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp07Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp08Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp09Summ']));
  $uPower = $uPower . chr(64 + intval($uNumbers['wp10Summ']));

  writePoweredScore($uPower, $u);
}



// A*A -- (10) écriture des positions du classement général

// on récupère toutes les données (dans l'ordre du classement basé sur 'poweredScoreUser')
$userOrdering = getDataToCalculateRank();

// LOG -- attention cette boucle part de l'index 0
for ($p = 0; $p < $totUsers; $p++) {
  $idUser = $userOrdering[$p]['idUserSumm'];
  $rank = $p + 1;  // l'index 0 correspond à la position '1er' du classement
  setRank($rank, $idUser);
}