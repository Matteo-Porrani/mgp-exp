<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Gestion course';


// T*T


// DEPRECATED -- suppression de résultat dans l'ancienne interface admin
// if (
//   !empty($_GET['action'])
//   && $_GET['action'] == 'delres'
//   && !empty($_GET['idRace'])
// ) {
//   deleteResult($_GET['idRace']);
// }




// A*A -- activation de course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!empty($_POST['activateRace'])) {

    $idRaceToActivate = $_POST['activateRace'];
    $idSeason = $_SESSION['idCurrSeason'];

    // activation de course
    activateRace($idRaceToActivate, $idSeason);
    // mise à jour de 'idCurrRace' en session
    $_SESSION['idCurrRace'] = $_POST['activateRace'];
  }




  if (!empty($_POST['countdownDate']) && !empty($_POST['countdownTime'])) {

    $idRace = $_SESSION['idCurrRace'];
    $idSeason = $_SESSION['idCurrSeason'];

    // formatage datetime
    $date = ($_POST['countdownDate']);
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);

    $time = ($_POST['countdownTime']);
    $hour = substr($time, 0, 2);
    $min = substr($time, 3, 2);


    // new Date("08 23, 2021 17:00:00")
    // FIXME -- format date modifié pour fonctionner sur mobile
    // $countdownString = "$month $day, $year $hour:$min:00";
    $countdownString = "$year-$month-$day"."T$hour:$min:00";

    $conf = setCountdown($countdownString, $idRace, $idSeason);


    if ($conf > 0) {
      $modTxt = "Le compte à rebours a bien été enregistré";
      setcookie('modalCookie', $modTxt, time()+2);
    }


    $_SESSION['countdownRace'] = $countdownString;


  }









}

// BUG -- [includes] gestion formulaire compte à rebours
// require_once('includes/countdown.inc.php');


$allRaces = listRaces();



// T*T

require_once('views/inc/head.phtml');

require_once('views/view_racehandler.phtml');

require_once('views/inc/tail.phtml');
