<?php
include_once "util.php";
$id=$_GET["id"];
if(getClassBySessione(getSessioneByGiorno($id))!=getUserClass())
{
	header("Location: index.php");
}

$classe=getUserClass();
$query="SELECT id_sessione, materia, descrizione, data AS d, n_minimo AS min, n_massimo AS max
					FROM `interrogazioni_giorni` AS g
					LEFT JOIN interrogazioni_sessioni AS s
					ON s.id=g.id_sessione
					WHERE g.id=$id
					AND s.classe='$classe'";
$risultato=database()->query($query);
$lastDate=null;
$row=$risultato->fetch_assoc();
$id_sessione=$row["id_sessione"];
?>
<! Autore: Matteo Ciaroni>
<html>
<head>
	<title> Interrogazioni </title>
	<?php
	echo file_get_contents('navbar/head.html');
	?>
	<script>
		function reload()
		{
			// $("#mydiv").load(location.href + " #mydiv");
			console.log("1");
		}
	</script>
</head>

<body>
<?php
echo file_get_contents('navbar/navbar-bootstrap.html');
?>
<div class="container">
	<br>
	<h1> Dettaglio Giorno </h1>
	<br>
	<div class="row">
		<div class="col-lg-6">
			<div class="container" style="text-align: left;">
				<dl class="row">
					<dt class="col-sm-4">Materia</dt>
					<dd class="col-sm-8"><? echo $row["materia"]; ?></dd>
				</dl>
				<dl class="row">
					<dt class="col-sm-4">Descrizione</dt>
					<dd class="col-sm-8"><? echo '<a href="dettagli-sessione.php?id='.$id_sessione.'">'.$row["descrizione"].'</a>'; ?></dd>
				</dl>
				<dl class="row">
					<dt class="col-sm-4">Data</dt>
					<dd class="col-sm-8"><? echo date("d/m/Y", strtotime($data=$row["d"])); ?></dd>
				</dl>
				<?
				if($row["min"]!=null && $row["min"]!=0)
				{
					echo '<dl class="row">
			<dt class="col-sm-4">N° min</dt>
			<dd class="col-sm-8">'.$minimo=$row["min"].'</dd>
		</dl>';
				}
				?>

				<?
				if($row["max"]!=null && $row["max"]!=0)
				{
					echo '<dl class="row">
			<dt class="col-sm-4">N° max</dt>
			<dd class="col-sm-8">'.$massimo=$row["max"].'</dd>
		</dl>';
				}
				?>
				<dl class="row">
					<dt class="col-sm-4">Interrogati</dt>
					<dd class="col-sm-8">
						<?
						$classe=getUserClass();
						$query="SELECT g.id, COUNT(a.id) AS num
					FROM interrogazioni_giorni AS g
					LEFT JOIN interrogazioni_interrogati AS i 
					ON g.id=i.id_giorno
					LEFT JOIN interrogazioni_alunni AS a
					ON a.id=i.id_alunno
					WHERE g.id='$id'
					ORDER BY nome DESC
					";
						$risultato=database()->query($query);
						$row=$risultato->fetch_assoc();
						$num_int=$row["num"];
						echo $num_int;
						?>
					</dd>
				</dl>
			</div>
		</div>
		<div id="mydiv" class="col-lg-6">
			<table class="table table-bordered">
				<thead>
				<tr>
					<th scope="col">Cognome</th>
					<th scope="col">Nome</th>
				</tr>
				</thead>
				<tbody>
				<?
				if(isset($minimo) && $minimo-$num_int>0)
					echo '<div class="alert alert-danger" role="alert">
  Non è stato ancora raggiunto il numero minimo! Ne mancano ancora '.($minimo-$num_int).'</div>';
				if(isset($massimo) && $num_int>$massimo)
					echo '<div class="alert alert-danger" role="alert">Ci sono troppi interrogati!</div>';

				if($row["num"]>0)
				{
					$query="SELECT g.id, a.id, a.cognome, a.nome
					FROM interrogazioni_giorni AS g
					LEFT JOIN interrogazioni_interrogati AS i 
					ON g.id=i.id_giorno
					LEFT JOIN interrogazioni_alunni AS a
					ON a.id=i.id_alunno
					WHERE g.id='$id'
					ORDER BY cognome ASC, nome ASC 
					";
					$risultato=database()->query($query);
					while($row=$risultato->fetch_assoc())
					{
						echo '<tr><td>';
						echo $row["cognome"].'</td><td>';
						echo $row["nome"].'</td></tr>';
					}

				}
				?>
				</tbody>
			</table>
			<form method="post">
				<?
				$oggi=date('Y-m-d');
				if($oggi<$data)
				{
					if(isUserInterrogato($id)>0)
					{
						echo '<input class="btn btn-raised btn-danger btn-lg" type="submit" name="formSubmit" value="Rimuovimi"/><br>';
					}
					else
					{
						if(!isGiornoFull($id))
						{
							$data_interr=whenIsUserInterrogato($id_sessione);
							if(!isset($data_interr))
								echo '<input class="btn btn-raised btn-success btn-lg" type="submit" name="formSubmit" value="Interrogami"/><br>';
							else
								echo '<div class="alert alert-danger" role="alert">Sei già stato interrogato il: '.$data_interr.'</div>';

						}
						else
						{
							echo '<div class="alert alert-danger" role="alert">Numero massimo raggiunto</div>';
						}
					}
				}
				else
				{
					echo '<div class="alert alert-danger" role="alert">Non è più possibile modificare</div>';
				}
				?>
			</form>
		</div>
		<?
		if($_POST['formSubmit']=="Interrogami")
		{
			if(!isGiornoFull($id) && !isUserInterrogato($id))
			{
				$id_alunno=getUserId();
				$query="INSERT INTO interrogazioni_interrogati (id, id_giorno, id_alunno, data_inserimento) VALUES (NULL, $id, $id_alunno, null)";
				$risultato=database()->query($query);
				echo "<script type='text/javascript'> document.location = 'dettagli-giorno.php?id=$id'; </script>";
//				header('Location: dettagli-giorno.php?id='.$id);
			}
		}
		if($_POST['formSubmit']=="Rimuovimi")
		{
			$id_alunno=getUserId();
			$query="DELETE FROM interrogazioni_interrogati WHERE id_alunno=$id_alunno AND id_giorno=$id";
			$risultato=database()->query($query);
			echo "<script type='text/javascript'> document.location = 'dettagli-giorno.php?id=$id'; </script>";
		}
		?>
	</div>
	<?
	if(isUserAdmin())
	{
		?>
		<div class="divbottom row">
			<div class="col-lg">
				<a href="modifica-giorno.php?id=<? echo $id; ?>">
					<button ype="button" class="btn btn-primary btn-standard bmd-btn-fab">
						<i class="material-icons">edit</i>
					</button>
				</a>
			</div>
			<div class="col-lg">
				<a href="modifica-interrogati.php?giorno=<? echo $id; ?>">
					<button type="button" class="btn btn-primary btn-standard bmd-btn-fab">
						<i class="material-icons">perm_contact_calendar</i>
					</button>
				</a>
			</div>
		</div>
		<!--<script>
			document.getElementById("navbarDropdown").style.display="block";
			var x='<a class="dropdown-item" href="modifica-giorno.php?id=<?/* echo $id; */
		?>">Modifica</a>' +
				'<a class="dropdown-item" href="modifica-interrogati.php?giorno=<?/* echo $id; */
		?>">Modifica Interrogati</a>';
			document.getElementById("mng").innerHTML+=x;
		</script>-->
		<?
	}
	?>
</div>
</body>
</html>
