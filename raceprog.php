<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');


// page title
$pageTitle = 'Phases de course';


// debugPR($_POST);

// T*T -- gestion formulaire phases de course + CALCUL scores

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!empty($_POST['racePhase'])) {

    // on crée des variables simplifiées
    $idRace = $_SESSION['idCurrRace'];
    $idSeason = $_SESSION['idCurrSeason'];
    $racePhase = $_POST['racePhase'];

    // A*A -- 1) on écrit la nouvelle phase en bdd
    $conf = setRaceProgress($idRace, $idSeason, $racePhase);



    // MK -- si on est phase 3 'red', on calcule les scores...
    if ($racePhase === 'red') {

      // A*A -- calcul des scores
      require_once('includes/scorecalc.inc.php');

      // A*A -- (8) -- écrit 'Y' dans 'race' / colonne 'scoreCalcRace' pour activer le message de confirmation
      writeScoreExistsAndCloseRace($idRace, $idSeason);

    } 
  












    // récupération de la phase qui vient d'être écrite
    $progRace = getRaceProgress($_SESSION['idCurrRace'], $_SESSION['idCurrSeason'])['progRace'];


    switch ($progRace) {
      case 'green':
        $phase = 'phase #1';
        break;
      case 'yellow':
        $phase = 'phase #2';
        break;
      case 'red':
        $phase = 'phase #3';
        break;
    }


    // création de cookie
    $modalTxt = '';
    if ($conf !== 0) {
      $modalTxt = "La $phase a bien été activée.";
    }
    setcookie('modalCookie', $modalTxt, time() + 2);
  }



} // fin gestion form changement de phase


// T*T




// FIXME -- je pense que ce code est caduque...
// $confirmMessage = '';

// BUG
// LOG -- revoir cette histoire de confirmation... 
// vérification si scoreCalcRace = YES
// $verifConfirm = getIfScoreYes($idRace)['scoreCalcRace'];


// A*A -- verification de la phase de course juste avant l'affichage
$progRace = getRaceProgress($_SESSION['idCurrRace'], $_SESSION['idCurrSeason'])['progRace'];




require_once('views/inc/head.phtml');

require_once('views/view_raceprog.phtml');

require_once('views/inc/tail.phtml');
