<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Gestion pilotes';


$allRiders = listExistingRiders();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  for ($id = 1; $id <= count($allRiders); $id++) {

    if (isset($_POST[$id]) && $_POST[$id] == true) {
      setRiderStatus($id, 'Y');
    } else {
      setRiderStatus($id, 'N');
    }

  }

  header('Location: admin.php');

}



$allActiveRiders = listActiveRiders();
$totActives = count($allActiveRiders);

require_once('views/inc/head.phtml');
require_once('views/view_riders.phtml');
require_once('views/inc/tail.phtml');
