<?php
session_start();

require("src/connection.php");
 
	if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['pass_confirm'])){
 
		// VARIABLE
 
		$pseudo       = $_POST['pseudo'];
		$email        = $_POST['email'];
		$password     = $_POST['password'];
		$pass_confirm = $_POST['pass_confirm'];
 
		// TEST SI PASSWORD = PASSWORD CONFIRM
 
		if($password != $pass_confirm){
				header('Location: index.php?error=1&pass=1');
					exit();
 
		}
 
		// TEST SI EMAIL UTILISE
		$req = $db->prepare("SELECT count(*) as x FROM users WHERE email = ?");
		$req->execute(array($email));
 
		while($donnees = $req->fetch()){
			if($donnees['x'] != 0) {
				header('location: index.php?error=1&email=1');
				exit();
 			}
		}
 
		// HASH
 		$secret = sha1($email).time();
		$secret = sha1($secret).time().time();
 
		// CRYPTAGE DU PASSWORD
		$password = "zju8". sha1($password."5524"). "86" ;
 
		// ENVOI DE LA REQUETE
 		$req = $db->prepare("INSERT INTO users(pseudo, email, password, secret) VALUES(?,?,?,?)");
		$value = $req->execute(array($pseudo, $email, $password, $secret));
			
		header('location: index.php?success=1');
		exit();
 
 	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PHP et MySQL : la formation ULTIME</title>
	<link rel="stylesheet" type="text/css" href="./design/default.css">
</head>
 
<body>
	<header>
		<h1>Inscription</h1>
	</header>

	<div class="container">

		<?php
		if(!isset($_SESSION['connect'])){ ?>

		<p>Bienvenue sur mon site, pour en savoir plus, inscrivez vous !</p>

		<?php
		 
		 if (isset($_GET['error'])) {
			if (isset($_GET['pass'])) {
			  $message = "";
			  $success= "";
	
			  echo  '<p style="color: red ; font-weight:bold">Vos mots de passe ne sont pas identiques !</p>' ;  
			}
	
			else if(isset($_GET['email'])){
			  echo '<p style="color: blue; font-weight:bold">Email déjà utlisé !</p>';
	
			}
		  }
		  else if (isset($_GET['success'])) {
			  echo '<p style="color: green; font-weight:bold">Bravo ! vous venez de vous inscrire</p>';
		  }
		 
		?>
	 
   <form action="index.php" method="post">
        <table>
          <tr>
            <td>Pseudo</td>
            <td>
              <input type="text" name="pseudo" placeholder="Votre pseudo" required/>
            </td>
          </tr>
          <tr>
            <td>Email</td>
            <td>
              <input type="email" name="email" placeholder="Votre email" required />
            </td>
          </tr>
          <tr>
            <td>Mot de passe</td>
            <td>
              <input
                type="password"
                name="password"
                placeholder="Votre mot de passe"   
                required           
              />
            </td>
          </tr>
          <tr>
            <td>Confirmation</td>
            <td>
              <input type="password" name="pass_confirm"
              placeholder="Confirmez le mot de passe" required/>
            </td>
          </tr>
        </table>
        <button type="submit" class="button">S'inscrire</button>
      </form>
      <p class="redirectionConnect">Déjà inscrit ?<a href="connection.php">connectez-vous</a></p>

		<?php } else { ?>
		
		<p id="info">
			Bonjour <?= $_SESSION['pseudo'] ?><br>
			<a href="disconnection.php">Déconnexion</a>
		</p>

		<?php } ?>

	</div>
</body>
</html>