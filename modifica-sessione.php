<?php
include_once "util.php";
$id_sessione=$_GET["id"];
if(!isUserAdmin() || !isset($id_sessione))
{
	header("Location: index.php");
}
else
{
	$classe=getUserClass();
	$query="SELECT * FROM interrogazioni_sessioni WHERE classe='$classe' AND id='$id_sessione'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	if($row["id"]==null)
	{
		header("Location: index.php");
	}
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
		<h1>Modifica Sessione</h1>
		<br>
		<form method="post">
			<div class="container" style="text-align: left; max-width: 500px">
				<div class="form-group">
					<label class="bmd-label-floating">Materia</label>
					<input type="text" class="form-control" name="materia" value="<? echo $row["materia"] ?>">
				</div>
				<div class="form-group">
					<label class="bmd-label-floating">Descrizione</label>
					<input type="text" class="form-control" name="descrizione"
						   value="<? echo $row["descrizione"] ?>">
				</div>
				<br>
				<div class="form-group">
					<label>Conclusa</label>
					<input type="checkbox" class="form-control" name="conclusa"
						   value="1" <? if($row["stato"]==1) echo "checked"; ?>>
				</div>
				<br>
				<br>
				<div class="container" style="max-width: 250px">
					<div class="row">
						<div class="col">
							<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Salva"/><br/>
						</div>
						<div class="col">
							<input class="btn btn-raised btn-danger" type="submit" name="formSubmit" value="Elimina"/><br/>
						</div>
					</div>
				</div>
			</div>
			<br>
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
	$conclusa=$_POST['conclusa'];
	if($conclusa!=1)
		$conclusa=0;
	if(empty($materia) || empty($descrizione))
	{
		echo '<p style="color:red">Devi inserire almeno i primi 2 campi.</p>';
	}
	else if(isUserAdmin())
	{
		include_once "../util.php";
		$sql="UPDATE interrogazioni_sessioni SET materia='$materia', descrizione='$descrizione', stato='$conclusa' WHERE id='$id_sessione'";
		database()->query($sql);
		echo "<script type='text/javascript'> document.location = 'sessioni.php'; </script>";
	}
}
else if($_POST['formSubmit']=="Elimina")
{
	if(isUserAdmin())
	{
		$sql="DELETE s,g,i
			FROM interrogazioni_sessioni AS s
			LEFT JOIN interrogazioni_giorni AS g ON g.id_sessione=s.id
			LEFT JOIN interrogazioni_interrogati AS i ON i.id_giorno=g.id
			WHERE s.id=$id_sessione";
		database()->query($sql);
		echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
	}
}
?>