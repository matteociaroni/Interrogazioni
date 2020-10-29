<! Autore: Matteo Ciaroni>
<html>
<head>
	<title> Login Videolezioni </title>
	<?php
	echo file_get_contents('navbar/head.html');
	?>
	<style>
		h1
		{
			margin-top: 40px;
		}
		.navbar-brand, .navbar-brand:hover
		{
			text-decoration: none !important;
			color: white !important;
		}
	</style>
</head>

<body>
<nav class="navbar sticky-top navbar-dark bg-custom">
	<a class="navbar-brand">Calendario Interrogazioni</a>
</nav>
<div class="container">
	<br>
	<h1> Login </h1>
	<br>
	<h3> Recupero password </h3>
	<br>

	<form method="post">
		<div class="container" style="max-width: 500px; text-align: left">
			<p>Ti verrà inviata un'email con la tua nuova password, che potrai modificare successivamente.</p>
			<div class="form-group">
				<label class="bmd-label-floating">Email</label>
				<input type="email" class="form-control" name="email">
			</div>
		</div>
		<br>
		<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Invia"/><br/>
	</form>
	<br>
	<?php
	if($_POST['formSubmit']=="Invia")
	{
		function database()
		{
			$username="acquamarinapesaro";
			$password="";
			$host="localhost";
			$database="my_acquamarinapesaro";
			$conn=new mysqli($host, $username, $password, $database);
			if($conn->connect_error)
			{
				die("Connection failed: ".$conn->connect_error);
			}
			return $conn;
		}
		function randomString($length=10)
		{
			return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
		}

		$email=$_POST['email'];
		if(empty($email))
		{
			echo '<p style="color:red">Inserisci la tua email</p>';
		}
		else
		{
			$query="SELECT id
    	FROM interrogazioni_alunni
        WHERE Email='$email'
        ORDER BY id ASC";
			$risultato=database()->query($query);
			$i=0;
			if($risultato->num_rows==1)
			{
				$nuovapassword=randomString(10);
				$nuovapassword_hashed=md5($nuovapassword);
				$sql="UPDATE interrogazioni_alunni SET password='$nuovapassword_hashed' WHERE email='$email'";
				database()->query($sql);
				$headers='From: Interrogazioni'."\r\n".
					'Reply-To: s_crnmtt02a22d488h@itisurbino.it'."\r\n".
					'X-Mailer: PHP/'.phpversion();
				mail($email, 'Interrogazioni - Recupero passord', 'La nuova password è: '.$nuovapassword."\r\n".'Poi cambiarla nella pagina "Profilo".', $headers);
				echo "Fatto, controlla la posta (anche nella casella SPAM)";
				echo '<br><br> <a class="link" href="login.php">Login</a>';
			}
			else
				echo '<p style="color:red">Email inesistente.</p>';
		}
	}
	?>
	<br>
	<br>
</div>
</body>
</html>