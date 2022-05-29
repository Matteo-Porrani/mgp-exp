<?php


// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
// require_once('lib/database.php');
// require_once('includes/queries_list.inc.php');

// BUG -- procéder à une destruction plus poussée de la session
session_unset();



header('Location: index.php');

