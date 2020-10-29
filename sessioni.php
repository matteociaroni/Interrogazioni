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
<div class="container">
	<br>
	<h1> Sessioni </h1>
	<br>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th scope="col">Materia</th>
			<th scope="col">Descrizione</th>
			<th scope="col">Dettagli</th>
		</tr>
		</thead>
		<tbody>
		<?
		$classe=getUserClass();
		$oggi=date('d/m');
		$domani=new DateTime('tomorrow');
		$domani=$domani->format('d/m');
		$query="SELECT id, materia, descrizione
					FROM `interrogazioni_sessioni`
					WHERE classe='$classe'";
		$risultato=database()->query($query);
		$lastDate=null;
		while($row=$risultato->fetch_assoc())
		{
			if(getStatoSessione($row["id"])=="Conclusa")
			{
				echo '<tr class="table-secondary">';
			}
			else
			{
				echo '<tr>';
			}
			echo '<td scope="row">'.$row["materia"].'</td>
      <td>'.$row["descrizione"].'</td>';
			echo '<td><a href="dettagli-sessione.php?id='.$row["id"].'"> Dettagli </a></td></tr>';
		}
		?>
		</tbody>
	</table>
	<br>
	<?
	if(isUserAdmin())
	{
		?>
		<div class="divbottom">
			<div class="col">
				<a href="nuova-sessione.php">
					<button type="button" class="btn btn-primary btn-standard bmd-btn-fab">
						<i class="material-icons">add</i>
					</button>
				</a>
			</div>
		</div>
		<!--	<script>
				document.getElementById("navbarDropdown").style.display = "block";
				var x = '<a class="dropdown-item" href="nuova-sessione.php"> Nuova sessione </a>';
				document.getElementById("mng").innerHTML += x;
			</script>-->
		<?
	}
	?>
</div>
</body>
</html>
