<?php
include_once "util.php";
$id=$_GET["id"];
if(getClassBySessione($id)!=getUserClass())
{
	header("Location: index.php");
}

$classe=getUserClass();
$query="SELECT classe, id, materia, descrizione, stato
					FROM `interrogazioni_sessioni`
					WHERE id=$id
					AND classe='$classe'";
$risultato=database()->query($query);
$lastDate=null;
$row=$risultato->fetch_assoc();
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
	<h1> Dettaglio Sessione </h1>
	<br>
	<div class="container" style="text-align: left;">
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Materia</dt>
			<dd class="col-sm-4"><? echo $row["materia"]; ?></dd>
		</dl>
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Descrizione</dt>
			<dd class="col-sm-4"><? echo $row["descrizione"]; ?></dd>
		</dl>
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Stato</dt>
			<dd class="col-sm-4"><? echo getStatoSessione($id) ?> </dd>
		</dl>
		<dl class="row">
			<dt class="col-sm-3"></dt>
			<dt class="col-sm-2">Tuo giorno</dt>
			<dd class="col-sm-4"><? echo whenIsUserInterrogato($id, null) ?></dd>
		</dl>
	</div>
	<br>
	<div class="row">
		<div class="col-lg-6">
			<h2>Giorni</h2>
			<br>
			<table class="table table-bordered">
				<thead>
				<tr>
					<th scope="col">Data</th>
					<th scope="col">Interrogati</th>
					<th scope="col">Dettagli</th>
				</tr>
				</thead>
				<tbody>
				<?
				$oggi=date('Y-m-d');
				$query="SELECT g.id AS idg, data AS d, n_minimo, n_massimo, GROUP_CONCAT(a.cognome ORDER BY a.cognome ASC SEPARATOR ', ') AS nome
					FROM interrogazioni_giorni AS g
					LEFT JOIN interrogazioni_interrogati AS i 
					ON g.id=i.id_giorno
					LEFT JOIN interrogazioni_alunni AS a
					ON a.id=i.id_alunno
					WHERE id_sessione='$id'
					GROUP BY data
					ORDER BY data ASC";
				$risultato=database()->query($query);
				$i=0;
				while($row=$risultato->fetch_assoc())
				{
					$i++;
					if($row["d"]<$oggi)
					{
						echo '<tr class="table-secondary"><td scope="row">'.date("d/m/Y", strtotime($row["d"])).'</td>';
					}
					else if($row["d"]==$oggi)
					{
						echo '<tr class="table-primary"><td scope="row">'.date("d/m/Y", strtotime($row["d"])).'</td>';
					}
					else
					{
						echo '<tr><td scope="row">'.date("d/m/Y", strtotime($row["d"])).'</td>';
					}

					if(isUserInterrogato($row["idg"]))
					{
						echo '<td class="you" scope="row">'.$row["nome"].'</td>';
					}
					else
					{
						echo '<td scope="row">'.$row["nome"].'</td>';
					}
					echo '<td><a href="dettagli-giorno.php?id='.$row["idg"].'"> Dettagli </a></td></tr>';
				}
				?>
				<tr style="text-align: center">
					<td colspan="3"> Totale giorni:&emsp;<? echo $i; ?></td>
				</tr>
				</tbody>
			</table>
		</div>

		<div class="col-lg-6">
			<h2>Riepilogo Classe</h2>
			<br>
			<table class="table table-bordered">
				<thead>
				<tr>
					<th scope="col">Alunno</th>
					<th scope="col">Data</th>
				</tr>
				</thead>
				<tbody>
				<?
				$classe=getUserClass();
				$oggi=date('Y-m-d');
				$query="SELECT id, cognome, nome
					FROM interrogazioni_alunni AS a
					ORDER BY cognome ASC, nome ASC";
				$risultato=database()->query($query);
				$i=0;
				while($row=$risultato->fetch_assoc())
				{
					if($row["id"]==getUserId())
						echo '<tr><td class="you">';
					else
						echo '<tr><td>';

					echo $row["cognome"].' '.$row["nome"].'</td>';
					echo '<td>'.whenIsUserInterrogato($id, $row["id"]).'</td>';
					if(whenIsUserInterrogato($id, $row["id"])!=null)
					{
						$i++;
					}
					echo '</tr>';
				}
				?>
				<tr style="text-align: center">
					<td colspan="2">
						Interrogati:&emsp;<? echo $i; ?>&emsp;-&emsp;Rimanenti:&emsp;<? echo getNumAlunniClasse()-$i; ?></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?
	if(isUserAdmin())
	{
		?>
		<div class="divbottom row">
			<div class="col-lg">
				<a href="modifica-sessione.php?id=<? echo $id; ?>">
					<button type="button" class="btn btn-primary btn-standard bmd-btn-fab">
						<i class="material-icons">edit</i>
					</button>
				</a>
			</div>
			<div class="col-lg">
				<a href="nuovo-giorno.php?sessione=<? echo $id; ?>">
					<button type="button" class="btn btn-primary btn-standard bmd-btn-fab">
						<i class="material-icons">playlist_add</i>
					</button>
				</a>
			</div>
		</div>
		<!--<script>
			document.getElementById("navbarDropdown").style.display="block";
			var x='<a class="dropdown-item" href="modifica-sessione.php?id=<?/* echo $id; */
		?>">Modifica</a>' +
				'<a class="dropdown-item" href="nuovo-giorno.php?sessione=<?/* echo $id; */
		?>">Aggiungi Giorno</a>';
			document.getElementById("mng").innerHTML+=x;
		</script>-->
		<?
	}
	?>
</div>
</body>
</html>
