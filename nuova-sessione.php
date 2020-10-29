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
		<h1>Nuova Sessione</h1>
		<br>
		<form method="post">
			<div class="container" style="max-width: 500px; text-align: left">
				<div class="form-group">
					<label class="bmd-label-floating">Materia</label>
					<input type="text" class="form-control" name="materia">
				</div>
				<div class="form-group">
					<label class="bmd-label-floating">Descrizione</label>
					<input type="text" class="form-control" name="descrizione">
				</div>
				<br>
				<div class="form-group">
					<label>Notifica a tutta la classe</label>
					<input type="checkbox" class="form-control" name="notifica" value="1">
				</div>
			</div>
			<br>
			<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Salva"/><br/>
		</form>
	</div>
	</body>
	</html>
<?php
if($_POST['formSubmit']=="Salva")
{
	$classe=getUserClass();
	$materia=$_POST['materia'];
	$descrizione=$_POST['descrizione'];
	$notifica=$_POST['notifica'];
	if(empty($materia) || empty($descrizione))
	{
		echo '<p style="color:red">Devi inserire almeno i primi 2 campi.</p>';
	}
	else if(isUserAdmin())
	{
		include_once "../util.php";
		$sql="INSERT INTO interrogazioni_sessioni (id, classe, materia, descrizione, stato, data_inserimento) VALUES (NULL, '$classe', '$materia', '$descrizione', '0', null)";
		database()->query($sql);
		$query="SELECT MAX(id) AS m FROM interrogazioni_sessioni WHERE classe='$classe'";
		$risultato=database()->query($query);
		$row=$risultato->fetch_assoc();
		$id_sessione=$row["m"];
		if($notifica==1)
		{
			$id_mittente=getUserId();
			$link="http://acquamarinapesaro.altervista.org/Vari/interrogazioni/dettagli-sessione.php?id=".$id_sessione;
			$query="SELECT id FROM interrogazioni_alunni WHERE classe='$classe'";
			$risultato=database()->query($query);
			while($row=$risultato->fetch_assoc())
			{
				$id_alunno=$row["id"];
				$sql="INSERT INTO interrogazioni_notifiche (id_notifica, id_alunno, titolo, messaggio, data, id_mittente, link) VALUES (NULL, '$id_alunno', 'Nuova sessione', 'Nuova sessione di $materia creata', null, '$id_mittente', '$link')";
				database()->query($sql);
			}
		}
		header('Location: sessioni.php');
	}
}
?>