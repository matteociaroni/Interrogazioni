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
	<h3>Cambio password</h3>
	<br>
	<p>La password viene salvata nel database con crittografia md5.</p>
	<form method="post">
		<div class="container" style="max-width: 500px; text-align: left">
			<br>
			<div class="col">
				<div class="form-group">
					<label class="bmd-label-floating">Vecchia password</label>
					<input type="password" class="form-control" name="vecchia">
				</div>
				<div class="form-group">
					<label class="bmd-label-floating">Nuova Password</label>
					<input type="password" class="form-control" name="nuova1">
				</div>
				<div class="form-group">
					<label class="bmd-label-floating">Conferma nuova Password</label>
					<input type="password" class="form-control" name="nuova2">
				</div>
			</div>
		</div>
		<br>
		<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Salva"/><br/>
		<br>
</body>
<?php
if($_POST['formSubmit']=="Salva")
{
	$vecchia=$_POST['vecchia'];
	$nuova1=$_POST['nuova1'];
	$nuova2=$_POST['nuova2'];
	if(empty($vecchia) || empty($nuova1) || empty($nuova2))
	{
		echo '<p style="color:red">Compila tutti i campi.</p>';
	}
	else if($nuova1!=$nuova2)
	{
		echo '<p style="color:red">Le due nuove password non coincidono.</p>';
	}
	else
	{
		$email=getUserEmail();
		$vecchia=md5($vecchia);
		$query="SELECT id
    	FROM interrogazioni_alunni
        WHERE email='$email' AND password='$vecchia'
        ORDER BY id ASC";
		$risultato=database()->query($query);
		if($risultato->num_rows==1)
		{
			$nuova1=md5($nuova1);
			$sql="UPDATE interrogazioni_alunni SET password='$nuova1' WHERE email='$email'";
			database()->query($sql);
			header('Location: logout.php');
		}
		else
			echo '<p style="color:red">La password vecchia è errata.</p>';
	}
}
?>
</html>
