<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Connexion';



$verificationText = "";
// A*A -- [includes] gestionnaire de formulaire
require_once('includes/loginsuper_handler.inc.php');


$allUsers = listSuperUsers();

// debugPR($allUsers);

$codeRequestText = "Composez votre code";


$formActionValue = "loginsuper.php?action=connect&super=yes";









require_once('views/inc/head.phtml');

require_once('views/view_login.phtml');

require_once('views/inc/tail.phtml');