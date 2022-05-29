<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Calendrier';

// debugPR($_SESSION);


$races = listRaces();

// debugPR($races);






require_once('views/inc/head.phtml');

require_once('views/view_cal.phtml');

require_once('views/inc/tail.phtml');