<?php
include_once "util.php";
?>
<! Autore: Matteo Ciaroni>
<html>
<head>
	<title> Interrogazioni </title>
	<?php
	echo file_get_contents('navbar/head.html');
	?>
</head>
<body>
<?php
echo file_get_contents('navbar/navbar-bootstrap.html');
?>
<br>
<div class="container">
	<h1> Profilo </h1>
	<br>
	<div class="container" style="text-align: left;">
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Nome</dt>
			<dd class="col-sm-4"> <? echo getUserName(); ?> </dd>
		</dl>
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Cognome</dt>
			<dd class="col-sm-4"> <? echo getUserSurname(); ?> </dd>
		</dl>
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Classe</dt>
			<dd class="col-sm-4"> <? echo getUserClass(); ?> </dd>
		</dl>
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Email</dt>
			<dd class="col-sm-4"> <? echo getUserEmail(); ?> </dd>
		</dl>
	</div>
	<br>
	<div class="container" style="max-width: 250px">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Opzioni</h5>
				<a class="link" href="cambia-password.php">Cambia Password </a>
				<br>
				<a class="link" href="contatti.php">Hai un problema?</a>
				<br>
				<a class="link" href="mailto:admin-email">Email amministratore</a>
			</div>
		</div>
	</div>
</body>
</html>
