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
      $userInfo = getSuperById($userid);

      // si le code est bon, on initialise les variables de session
      if (password_verify($usercode, $userInfo['passSuper'])) {

        $_SESSION['connected'] = true;
        $_SESSION['id'] = $userid;
        $_SESSION['name'] = $userInfo['nameSuper'];
        $_SESSION['type'] = $userInfo['typeSuper'];
        $_SESSION['color'] = $userInfo['colorSuper'];


        // redirection vers page profil
        header('Location: admin.php');

      } else {
        // sinon on affiche un message d'erreur
        $alert = 'Code non reconnu !';
      }
    }
  }








  
}
