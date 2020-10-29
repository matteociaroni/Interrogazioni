<?php
include_once "util.php";
$id_giorno=$_GET["id"];
if(!isUserAdmin() || !isset($id_giorno))
{
	header("Location: index.php");
}
else
{
	$classe=getUserClass();
	$query="SELECT s.id AS ids, g.id AS idg, g.data AS data, g.n_minimo AS min, g.n_massimo AS max 
			FROM interrogazioni_giorni AS g
 			JOIN interrogazioni_sessioni AS s
 			ON s.id=g.id_sessione
 			WHERE s.classe='$classe' AND g.id='$id_giorno'";
	$risultato=database()->query($query);
	$row=$risultato->fetch_assoc();
	$id_sessione=$row["ids"];
	if($id_sessione==null)
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
		<h1>Modifica Giorno</h1>
		<br>
		<form method="post">
			<div class="container" style="text-align: left; max-width: 500px">
				<div class="form-group">
					<label>Data</label>
					<input type="date" class="form-control" name="data" value="<? echo $row["data"] ?>">
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label class="bmd-label-floating">Minimo</label>
							<input type="number" class="form-control" name="min"
								   value="<? echo $row["min"] ?>">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label class="bmd-label-floating">Massimo</label>
							<input type="number" class="form-control" name="max"
								   value="<? echo $row["max"] ?>">
						</div>
					</div>
				</div>
				<small>Puoi lasciare vuoti i campi "minimo" e "massimo", in tal caso non verranno considerati</small>
				<br>
				<br>
				<div class="container" style="max-width: 250px">
					<div class="row" style="">
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
		$sql="SELECT id FROM interrogazioni_giorni WHERE data='$data' AND id_sessione='$id_sessione'";
		$risultato=database()->query($sql);
		$row=$risultato->fetch_assoc();
		if($risultato->num_rows>0 && $row["id"]!=$id_giorno)
		{
			echo '<p style="color: red">Questo giorno è già presente</p>';
		}
		else
		{
			$sql="UPDATE interrogazioni_giorni SET data='$data', n_massimo='$max', n_minimo='$min'  WHERE id='$id_giorno'";
			database()->query($sql);
			echo "<script type='text/javascript'> document.location = 'dettagli-sessione.php?id=$id_sessione'; </script>";
		}
	}
}
else if($_POST['formSubmit']=="Elimina")
{
	if(isUserAdmin())
	{
		/*$sql="DELETE FROM interrogazioni_giorni WHERE id='$id_giorno';";
		database()->query($sql);
		$sql="DELETE FROM interrogazioni_interrogati WHERE id_giorno='$id_giorno'";
		database()->query($sql);*/

		$sql="DELETE FROM interrogazioni_giorni WHERE id=$id_giorno;";
		database()->query($sql);
		$sql="DELETE FROM interrogazioni_interrogati WHERE id_giorno=$id_giorno";
		database()->query($sql);
		echo "<script type='text/javascript'> document.location = 'dettagli-sessione.php?id=$id_sessione'; </script>";
	}
}
?>