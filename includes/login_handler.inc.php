<?php

// debugPR($_GET);
// debugPR($_POST);

// error handling var
$errors = [];
// alert handling var
$alert = "";
// expected fields
$expected = ['userid', 'usercode', 'loginBtn'];
// required fields
$required = ['userid', 'usercode'];



// A*A -- gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  foreach ($_POST as $key => $value) {

    if (!in_array($key, $expected)) {
      continue;
    }
    // création de variables simplifiées
    $$key = $value;
  }


  // MK -- gestion CONNEXION
  if (!empty($_GET) && $_GET['action'] == 'connect') {

    if ($userid === 'noUser') {
      $alert = 'Veuillez sélectionner un utilisateur';
    }

    // A*A -- si user choisi et code envoyé, on vérifie...
    if (!empty($userid) && ($userid !== 'noUser') && !empty($usercode)) {

      // lecture du code de l'user choisi en bdd
      $userInfo = getUserById($userid);

      // si le code est bon, on initialise les variables de session
      if (password_verify($usercode, $userInfo['passUser'])) {

        $_SESSION['connected'] = true;
        $_SESSION['id'] = $userid;
        $_SESSION['name'] = $userInfo['nameUser'];
        $_SESSION['type'] = $userInfo['typeUser'];
        $_SESSION['color'] = $userInfo['colorUser'];

        /*
        if ($userInfo['tagUser'] !== 'RAC') {
          header('Location: user.php');
        } else {
          header('Location: racedir.php');
        }
        */


        // redirection vers page profil
        header('Location: user.php');
      } else {
        // sinon on affiche un message d'erreur
        $alert = 'Code non reconnu !';
      }
    }
  }








  // MK -- gestion MODIFICATION password
  if (!empty($_GET) && $_GET['action'] == 'edit') {

    // A*A -- si STEP 1
    if ($_GET['step'] == 1 && !empty($userid) && ($userid !== 'noUser') && !empty($usercode)) {

      // lecture du code de l'user choisi en bdd
      $userInfo = getUserById($userid);

      // si le code est bon
      if (password_verify($usercode, $userInfo['passUser'])) {

        header('Location: login.php?action=edit&step=2');

      } else {
        $alert = 'Code non reconnu !';
      }


      // A*A -- si STEP 2
    } elseif ($_GET['step'] == 2 && !empty($userid) && ($userid !== 'noUser') && !empty($usercode)) {

      $_SESSION['newCode'] = $usercode;
      header('Location: login.php?action=edit&step=3');

      // A*A -- si STEP 3
    } elseif ($_GET['step'] == 3 && !empty($userid) && ($userid !== 'noUser') && !empty($usercode)) {

      if ($usercode == $_SESSION['newCode']) {

        // RPR -- modfication code en bdd
        $hashedNewCode = password_hash($_SESSION['newCode'], PASSWORD_DEFAULT);
        $editDatetime = date("d/m/Y @ H:i:s", time());

        // TODO -- réunir en une seule requête l'écriture new pass + update du 'lastEdit'

        // écrit le nouveau code
        $conf1 = writeNewCode($hashedNewCode, $editDatetime, $_SESSION['id']);
        
        // écrit date & heure de modification
        // BUG -- réactiver cette ligne
        // $conf2 = writeCodeEditDatetime($_SESSION['id'], $editDatetime);

        $_SESSION['newCode'] = "";

        if ($conf1 > 0) {

          $modTxt = "Le code a bien été modifié.";
          setcookie('modalCookie', $modTxt, time() + 2);

        } else {
          $alert = 'La confirmation a échoué. <br>Merci de réessayer.';
        }

        header('Location: user.php');

      } else {
        $alert = 'La confirmation a échoué. <br>Merci de réessayer.';
      }
    }
  }
}
