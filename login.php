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
require_once('includes/login_handler.inc.php');


$allUsers = listUsers();




// gestion du contexte CONNECT ou EDIT pour la page
if (!empty($_GET['action'])) {


  if ($_GET['action'] == 'connect') {
    // MK -- connection


    // A*A -- gestion de l'attribut 'action' de la balise <form> !!!
    $formActionValue = 'login.php?action=connect';


    $codeRequestText = "Composez votre code";
    $codeRequestColorClass = "text-secondary";
  } elseif ($_GET['action'] == 'edit' && $_SESSION['connected'] == true) {


    $codeRequestColorClass = "text-danger fw-bold";

    // récupération des infos user pour la création de l'option unique pour #username
    $userData = getUserById($_SESSION['id']);




    switch ($_GET['step']) {
      case 1:
        $codeRequestText = "Composez votre code actuel";
        $formActionValue = 'login.php?action=edit&step=1';
        break;




      case 2:
        $codeRequestText = "Composez votre nouveau code";
        $formActionValue = 'login.php?action=edit&step=2';
        break;




      case 3:
        $codeRequestText = "Confirmez votre nouveau code";
        $formActionValue = 'login.php?action=edit&step=3';
        break;
    }
  } else {
    header('Location: index.php');
    exit;
  }
}









require_once('views/inc/head.phtml');

require_once('views/view_login.phtml');

require_once('views/inc/tail.phtml');
