<?php
include_once "util.php";
$id_sessione=$_GET["sessione"];
if(!isUserAdmin() || !isset($id_sessione))
{
	header("Location: index.php");
}
$classe=getUserClass();
$query="SELECT * FROM interrogazioni_sessioni WHERE id='$id_sessione' AND classe='$classe'";
$risultato=database()->query($query);
$row=$risultato->fetch_assoc();
if($row["id"]==null)
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
		<h1>Nuovo Giorno</h1>
		<br>
		<form method="post">
			<div class="container" style="max-width: 500px; text-align: left">
				<div class="form-group">
					<label>Data</label>
					<input type="date" class="form-control" name="data">
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label class="bmd-label-floating">Minimo</label>
							<input type="number" class="form-control" name="min">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label class="bmd-label-floating">Massimo</label>
							<input type="number" class="form-control" name="max">
						</div>
					</div>
					<br>
					<small>Puoi lasciare vuoti i campi "minimo" e "massimo", in tal caso non verranno considerati</small>
				</div>
			</div>
			<br>
			<br>
			<input class="btn btn-raised btn-standard" type="submit" name="formSubmit" value="Salva"/><br/>
		</form>
	</div>
	</body>
	</html>
<?php
if($_POST['formSubmit']=="Salva")
{
	$data=$_POST['data'];
	$min=$_POST['min'];
	$max=$_POST['max'];
	if($min==null)
	{
		$min=0;
	}
	if($max==null)
	{
		$max=0;
	}
	if(empty($data))
	{
		echo '<p style="color:red">Devi inserire almeno la data.</p>';
	}
	else if(isUserAdmin())
	{
		include_once "../util.php";
		$sql="INSERT INTO interrogazioni_giorni (id, id_sessione, data, n_minimo, n_massimo, data_inserimento) VALUES (NULL, '$id_sessione', '$data', '$min', '$max', null)";
		database()->query($sql);
		echo "<script type='text/javascript'> document.location = 'dettagli-sessione.php?id=$id_sessione'; </script>";
	}
}
?>