<?php

session_start();

if (isset($_SESSION['connect'])) {
  header('location: ./');
}

require('src/connection.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {
  
  // VARIABLES
  $email      = $_POST['email'];
  $password   = $_POST['password'];
  $error      = 0;

  // PASSWORD CRYPT
  $password = "zju8".sha1($password."5524"). "86" ;

  $req = $db->prepare('SELECT * FROM users WHERE email = ? ');
  $req->execute(array($email)); 

  while ($user = $req->fetch()) {
    
    if ($password == $user['password']) {
      $success = "";
      $_SESSION['connect'] = 1;
      $_SESSION['pseudo'] = $user['pseudo'];

      // COOKIE
      if (isset($_POST['connect'])) {

        setcookie('logAuto', $user['secret'], time() + (365 * 60 * 3600), '/', null, false, true);
        // echo "<pre>"; print_r($logAuto); echo "</pre>";

      }

      header('location: connection.php?success=1'); exit();

    }

  }
  
  header('location: connection.php?error=1'); exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    
    <link rel="stylesheet" href="./design/default.css" />
    <title>Connection</title>
</head>
<body>

<?php

  if (isset($_GET['error']) == 1) {
      echo "<p style='color: red; font-weight:bold'>Email ou mot de passe incorrect !</p>";
  }

  else if (isset($_GET['success']) == 1) {
      echo "<p style='color: green; font-weight:bold'>Connexion reussie !</p>";
  }

?>

<h1>Connexion</h1>

<p>Bienvenue sur mon site ! Déja inscrit ?</p>

<form action="connection.php" method="POST">

      <table>        
        <tr>
          <td>Email</td>
          <td>
            <input type="email" required
            name="email" placeholder="Votre email" />
          </td>
        </tr>
        <tr>
          <td>Mot de passe</td>
          <td>
            <input
              type="password"
              name="password"
              required
              placeholder="Votre mot de passe"
            />
          </td>
        </tr>         
    </table>

      <button type="submit" class="button">Se connecter</button>
      
      <label><p class="connect"><input type="checkbox" checked name="aut_connec">Rester connecté</p></label>
    </form>

    <p>Pas encore inscrit ?<a href="index.php">Inscrivez-vous</a></p>  
    
</body>
</html>