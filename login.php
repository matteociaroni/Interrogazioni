<?php
session_start();
if(isset($_SESSION['loggedin']) || $_SESSION['loggedin']==true)
{
	header('Location: index.php');
}
?>
<! Autore: Matteo Ciaroni>
<html>
<head>
	<title> Interrogazioni - Login </title>
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

		HTML CSSResult
		EDIT ON
		.spinner
		{
			display: block;
			margin-top: 2em;
			margin-right: auto;
			margin-left: auto;
			width: 4em;
			height: 4em;
			padding: 7px;
			border: 1px solid #ccc;
			border-radius: 50%;
			transform: scale(.7);
		}

		.spinner-wrapper
		{
			position: relative;
			width: 4em;
			height: 4em;
			border-radius: 100%;
			left: calc(50% - 2em);
		}

		.spinner-wrapper::after
		{
			content: "";
			background: #fafafa;
			border-radius: 50%;
			width: 3em;
			height: 3em;
			position: absolute;
			top: 0.5em;
			left: 0.5em;
		}

		.rotator
		{
			position: relative;
			width: 4em;
			border-radius: 4em;
			overflow: hidden;
			animation: rotate 1800ms infinite linear;
		}

		.rotator:before
		{
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: #335fac;
			border: 3px solid #fafafa;
			border-radius: 100%;
		}

		.inner-spin
		{
			background: #fafafa;
			height: 4em;
			width: 2em;
		}

		.inner-spin
		{
			animation: rotate-left 2000ms infinite cubic-bezier(0.445, 0.050, 0.550, 0.950);
			border-radius: 2em 0 0 2em;
			transform-origin: 2em 2em;
		}

		.inner-spin:last-child
		{
			animation: rotate-right 2000ms infinite cubic-bezier(0.445, 0.050, 0.550, 0.950);
			margin-top: -4em;
			border-radius: 0 2em 2em 0;
			float: right;
			transform-origin: 0 50%;
		}

		@keyframes rotate-left
		{
			60%, 75%, 100%
			{
				transform: rotate(360deg);
			}
		}

		@keyframes rotate
		{
			0%
			{
				transform: rotate(0);
			}
			100%
			{
				transform: rotate(360deg);
			}
		}

		@keyframes rotate-right
		{
			0%, 25%, 45%
			{
				transform: rotate(0);
			}

			100%
			{
				transform: rotate(360deg);
			}
		}


		Resources1×0

		.5
		×0

		.25
		×Rerun


	</style>
	<script>
		function loadspinner()
		{
			document.getElementById("spinner").style.display = "inline-block";
		}

		function loadBar()
		{
			document.getElementById("bar").style.display = "inline-block";
			document.getElementById("bar").style.width = "0%";
			document.getElementById("bar").style.width = "100%";
		}
	</script>
</head>

<body>
<nav class="navbar sticky-top navbar-dark bg-custom">
	<a class="navbar-brand">Calendario Interrogazioni</a>
</nav>
<div class="container">
	<br>
	<h1> Login </h1>
	<br>
	<h3> Inserisci le tue credenziali </h3>
	<br>
	<form method="post">
		<div class="container" style="max-width: 500px; text-align: left">
			<div class="form-group">
				<label class="bmd-label-floating">Email</label>
				<input type="email" class="form-control" name="email">
			</div>
			<div class="form-group">
				<label class="bmd-label-floating">Password</label>
				<input type="password" class="form-control" name="password">
			</div>
		</div>
		<br>
		<input onclick="document.getElementById('loader').style.display='block';"
			   class="btn btn-raised btn-primary btn-standard" type="submit" name="formSubmit" value="Login"/>
		<br>
	</form>
	<div id="loader" style="display: none" class="spinner">
		<div class="spinner-wrapper">
			<div class="rotator">
				<div class="inner-spin"></div>
				<div class="inner-spin"></div>
			</div>
		</div>
	</div>
</div>
<!--<div class="progress" style="max-width: 200px">
	<div id="bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: 0%; display: none"></div>
</div>-->
<br>
<br>
<a class="link" href="password-dimenticata.php">Password dimenticata?</a>
<br>
<?php
if($_POST['formSubmit']=="Login")
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

	$email=$_POST['email'];
	$password=$_POST['password'];
	if(empty($email) || empty($password))
	{
		echo '<p style="color:red">Devi inserire le tue credenziali.</p>';
	}
	else
	{
		$password=md5($password);
		$query="SELECT id
    	FROM interrogazioni_alunni
        WHERE Email='$email' AND Password='$password'
        ORDER BY id ASC";
		$risultato=database()->query($query);
		$i=0;
		if($risultato->num_rows==1)
		{
			session_start();
			$_SESSION['loggedin']=true;
			$_SESSION['username']=$email;
			sleep(3);
			if(isset($_GET["next"]))
			{
				echo '<script>document.location="'.$_GET["next"].'"</script>';
			}
			else
				echo '<script>document.location="index.php"</script>';
		}
		else
			echo '<p style="color:red">Email o password errate.</p>';
	}
}
?>
<br>
<br>
</div>
</body>
</html>