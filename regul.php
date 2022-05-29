<?php

// utilitaires
require_once('includes/errors.inc.php');
require_once('includes/session.inc.php');
require_once('includes/functions.inc.php');

// db & queries
require_once('lib/database.php');
require_once('includes/queries_list.inc.php');

// page title
$pageTitle = 'Règlement';







require_once('views/inc/head.phtml');
require_once('views/view_regul.phtml');
require_once('views/inc/tail.phtml');