<?php
	session_start();
	require('src/connection.php');
	
	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_two']))
	{
		$email			=	htmlspecialchars($_POST['email']);
		$password		=	htmlspecialchars($_POST['password']);
		$password_two	=	htmlspecialchars($_POST['password_two']);
		if($password != $password_two)
		{
			header('location: ?error=1&message=vos mot de passe sont pas identique');
			exit();
		}
		if(!filter_var($email , FILTER_VALIDATE_EMAIL))
		{
			header('location: ?error=1&message=Votre email est invalid');
			exit();
		}
		$req = $db -> prepare("SELECT count(*) as numberEmail FROM user WHERE email = ?");
		while ($email_verification = $req->fetch()) {
			if ($email_verification['numberEmail'] != 0) {
				header('location: ?error=1&message=Cette adresse email est deja utilisé par un autre utilisateur');
			}
		}
		$password = (sha1($password)."1234");
		$secret = sha1($email).time();
		$req = $db -> prepare('INSERT INTO user(email,password,secret) VALUES(?,?,?)');
		$req -> execute(array($email,$password,$secret));
		header('location: ?success=1');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>
			<?php if (isset($_GET['error'])) {
				if (isset($_GET['message'])) {
					echo '<div class="alert error">'. htmlspecialchars($_GET['message']) . '</div>';
				}
			}
			if(isset($_GET['success']))
			{
				echo '<div class="alert success">vous ete inscrit </div>';
			}
			?>
			<form method="post" action="inscription.php">
			<?php if(isset($_SESSION['connect']))
					{
						echo "connecté click <a href=''>ici</a> si tu veux visiter le site";
						echo "et si tu veux deconnecter click <a href='deconnexion.php'>ici</a>";
					}
					else{
				?>
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
			<?php } ?>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>