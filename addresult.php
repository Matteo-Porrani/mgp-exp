<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');




// page title
$pageTitle = 'Ajout résultat';


$riders = listActiveRiders();




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resIdList'])) {


  // variables qui seront utilisées dans les 2 cas
  $idRace = $_POST['res_race_id'];
  $idSeason = '2022';



  if ($_POST['res_type'] === 'resultRace') {
    // A*A -- écriture résultat

    $convertedList = explode(", ", $_POST['resIdList']);
    $serialResult = serialize($convertedList);

    $idWinner = $convertedList[1];

    $conf = updateResult($idWinner, $serialResult, $idRace, $idSeason);


    if ($conf > 0) {
      $modTxt = "Le résultat a bien été enregistré.";
      setcookie('modalCookie', $modTxt, time() + 2);
    }
  }



  if ($_POST['res_type'] === 'dnfRace') {
    // A*A -- écriture dnf

    $convertedList = explode(", ", $_POST['resIdList']);
    $serialResult = serialize($convertedList);

    $conf = updateDnf($serialResult, $idRace, $idSeason);


    if ($conf > 0) {
      $modTxt = "Le DNF a bien été enregistré.";
      setcookie('modalCookie', $modTxt, time() + 2);
    }
  }
}


// Suppression de 'result' & 'dnf'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setResAsNull']) && $_POST['setResAsNull'] === 'clearAll') {
  $idRace = $_POST['res_race_id'];
  $idSeason = '2022';
  $conf = clearResAndDnf($idRace, $idSeason);

  if ($conf > 0) {
    $modTxt = "Résultat & DNF pour cette course ont bien été effacés.";
    setcookie('modalCookie', $modTxt, time() + 2);
  }

}




require_once('views/inc/head.phtml');

require_once('views/view_addresult.phtml');

require_once('views/inc/tail.phtml');
