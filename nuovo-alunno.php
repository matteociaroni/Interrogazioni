<?php
include_once "util.php";
if(!isUserAdmin())
{
	header("Location: index.php");
}
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
<div class="container">
	<br>
	<h1>Nuovo Utente</h1>
	<br>
	<form method="post">
		<div class="container" style="max-width: 500px; text-align: left">
			<div class="form-group">
				<label class="bmd-label-floating">Nome</label>
				<input type="text" class="form-control" name="nome">
			</div>
			<div class="form-group">
				<label class="bmd-label-floating">Cognome</label>
				<input type="text" class="form-control" name="cognome">
			</div>
			<div class="form-group">
				<label class="bmd-label-floating">Classe</label>
				<input type="text" class="form-control" value="<? echo getUserClass();?>" disabled>
			</div>
			<small>Puoi inserire solo utenti della tua classe.</small>
			<div class="form-group">
				<label class="bmd-label-floating">Email</label>
				<input type="email" class="form-control" name="email">
			</div>
			<div class="form-group">
				<label class="bmd-label-floating">Password</label>
				<input type="password" class="form-control" disabled>
			</div>
			<small>Non puoi impostare la password per i nuovi utenti.
				<br> Sarà l'utente stesso ad effettuare il recupero password dalla pagina "Login".
				<br> Gli arriverà per email una password automatica che potrà poi cambiare nella pagina "Profilo".</small>
			<br>
			<br>
			<div class="switch">
				<label>
					Amministratore
					<input type="checkbox" disabled>
				</label>
			</div>
			<small>Non puoi creare utenti amministratori.</small>
		</div>
		<br>
		<small style="color: red">Una volta creato un nuovo utente, non potrai più cancellarlo.</small>
		<br>
		<br>
		<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Aggiungi"/><br/>
	</form>
</div>
</body>
</html>
<?php
if($_POST['formSubmit']=="Aggiungi")
{
	$classe=getUserClass();
	$nome=$_POST['nome'];
	$cognome=$_POST['cognome'];
	$email=$_POST['email'];
	if(empty($nome) || empty($cognome) || empty($email))
	{
		echo '<p style="color:red">Devi riempire tutti i campi.</p>';
	}
	else if(isUserAdmin())
	{
		include_once "../util.php";
		$sql="INSERT INTO interrogazioni_alunni (id, nome, cognome, classe, email, password, auth_admin) VALUES (null, '$nome', '$cognome', '$classe', '$email', 'bhdvowf', 0)";
		database()->query($sql);
		echo "<script type='text/javascript'> document.location = 'classe.php'; </script>";
	}
}
?>