<?php
session_start();
    require('src/connection.php');
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email   =$_POST['email'];
        $password = $_POST['password'];
        $error =1;
        

        $password = (sha1($password)."1234");
        $req = $db->prepare('SELECT * FROM user WHERE email=?');
        $req->execute(array($email));
        while ($user = $req->fetch()) {
            if ($password == $user['password']) {
                $_SESSION['connect']=1;
                $_SESSION['email']=$user['email'];
                header('location: ?connect=1');
                if(isset($_POST['auto']))
                {
                    setcookie('log',$user['secret'], time() + 365*24*3600 , '/' ,null,false,true);
                }
                $error = 0;
            }
        }
        if ($error == 1) {
            header('location: ?error=1'); 
        }
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
				<h1>S'identifier</h1>
				<form method="post" action="index.php">
					<?php if(isset($_SESSION['connect']))
					{
						echo "connecté click <a href=''>ici</a> si tu veux visiter le site";
						echo "et si tu veux deconnecter click <a href='deconnexion.php'>ici</a>";
					}
					else{
				?>
				<?php
                if(isset($_GET['error']))
                {
                        echo '<p class="alert error" class="ERSU">email ou mot de passe incorect</p>';
                }
                else if(isset($_GET['connect']))
                {
                        echo '<p class="alert success" class="ERSU">connecté.</p>';
                }
            ?>
					<input type="email" name="email" placeholder="Votre adresse email" required />
					<input type="password" name="password" placeholder="Mot de passe" required />
					<button type="submit">S'identifier</button>
					<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label>
				</form>
			

				<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
				<?php } ?>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>