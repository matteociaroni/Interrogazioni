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
		<h1>Invia Notifica</h1>
		<br>
		<form method="post">
			<div class="container" style="max-width: 500px; text-align: left">
				<div class="form-group">
					<label class="bmd-label-floating">Titolo</label>
					<input type="text" class="form-control" name="titolo">
				</div>
				<div class="form-group">
					<label class="bmd-label-floating">Messaggio</label>
					<textarea class="form-control" name="messaggio"></textarea>
				</div>
				<div class="form-group">
					<label class="bmd-label-floating">Link</label>
					<input type="url" class="form-control" name="link">
				</div>
				<div class="form-group">
					<label>Alunno</label>
					<select class="form-control" name="alunno[]" id="select" multiple>
						<?
						$classe=getUserClass();
						$query="SELECT * FROM interrogazioni_alunni WHERE classe='$classe' ORDER BY cognome ASC, nome ASC";
						$risultato=database()->query($query);
						while($row=$risultato->fetch_assoc())
						{
							echo '<option value="'.$row["id"].'">'.$row["cognome"].' '.$row["nome"].'</option>';
						}
						?>
					</select>
					<small>Selezione multipla con <i>ctrl + click</i>; seleziona tutto con <i>ctrl + a</i>.</small>
				</div>
				<br>
			</div>
			<br>
			<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Invia"/><br/>
		</form>
	</div>
	</body>
	</html>
<?php
if($_POST['formSubmit']=="Invia")
{
	$titolo=$_POST['titolo'];
	$messaggio=$_POST['messaggio'];
	$link=$_POST['link'];
	if(empty($messaggio) || empty($titolo))
	{
		echo '<p style="color:red">Compila i campi.</p>';
	}
	else if(isUserAdmin())
	{
		include_once "util.php";
		$id_mittente=getUserId();
		foreach($_POST['alunno'] as $id_alunno)
		{
			$sql="INSERT INTO interrogazioni_notifiche (id_notifica, id_alunno, titolo, messaggio, data, id_mittente, link) VALUES (NULL, '$id_alunno', '$titolo', '$messaggio', null, '$id_mittente', '$link')";
			database()->query($sql);
		}
		echo '<script> document.location="notifiche.php"; </script>';
	}
}
?>